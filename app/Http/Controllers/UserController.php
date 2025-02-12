<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Employee;
use App\Models\EmployeeDetail;
use App\Models\EmployeeDocument;
use App\Models\Event;
use App\Models\HRDocument;
use App\Models\KnowledgeBase;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserDocument;
use App\Models\UserTicket;
use App\Models\XeroDetail;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Hash;
use Auth;
use File;
use App\Models\JobadderDetail;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role_type == 1) {
            $users = User::where('role_type', 2)->get();
            return response()->json(['users'=>$users],200); 
        } else {
            return redirect('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return response()->json(["user"=>"sf"],200);
            
        // dd($request->toArray());
        try {
            $validator = \Validator::make($request->all(), [
                'name' => ['required'],
                'email' => ['required', 'unique:users,email,NULL,id,deleted_at,NULL'],
                'status' => ['required']
            ]);

            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            
            $client = new User;
            $client->name = $request->name;
            $client->email  = $request->email;
            $client->status = $request->status;
            $client->password  = get_common_password();
            $client->remember_token  = get_unique_id();
            $role = Role::where('name', 'Client')->first();
            $client->role_type  = $role->id;
            $client->save();
            $client->assignRole('Client');

            $client_details = new UserDetail;
            $client_details->user_id = $client->id;
            $client_details->company_name = $request->company_name;
            $client_details->company_number  = $request->company_number;
            $client_details->registered_address = $request->registered_address;
            $client_details->vat_number = $request->vat_number;
            $client_details->authentication_code  = $request->authentication_code;
            $client_details->company_utr = $request->company_utr;
            $client_details->bank_name  = $request->bank_name;
            $client_details->sort_code = $request->sort_code;
            $client_details->account_number = $request->account_number;
            $client_details->iban  = $request->iban;
            $client_details->swift_code = $request->swift_code;

            // Start: TO save user(client) logo
            if ($request->logo != null) {
                $dir = public_path('client_documents/');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $file = $request->file('logo')->getClientOriginalName();
                $image_input['imagename']  = 'CL-' . time() . '-' . $file;
                $destinationPath = $dir . 'CLIENT_' . $client->id;
                $request->logo->move($destinationPath, $image_input['imagename']);
                $client_details->logo = 'client_documents/CLIENT_' . $client->id . '/' . $image_input['imagename'];
            }
            // End: TO save user(client) logo
            $client_details->save();

            // Start: add new documnents to user documents table
            if ($request->images) {
                foreach ($request->images as $image_key => $image) {
                    $dir = public_path('client_documents/');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $user_document = new UserDocument();
                    $user_document->file_size = $image->getSize();
                    $image_input['imagename'] = 'CD-' . time() . '-' . $image->getClientOriginalName();
                    $destinationPath = $dir . 'CLIENT_' . $client->id;
                    $image->move($destinationPath, $image_input['imagename']);
                    $user_document->file = 'client_documents/CLIENT_' . $client->id . '/' . $image_input['imagename'];
                    $user_document->user_id = $client->id;
                    $user_document->file_ext = $image->getClientOriginalExtension();
                    $user_document->status = 1;
                    foreach ($request->document as $doc_key => $doc) {
                        //  dd($image_key,$doc_key,$doc);
                        if ($image_key == $doc_key) {
                            $user_document->type_id = $doc;
                            $user_document->save();
                        }
                    }

                    //TO add DOcument Title
                    foreach ($request->image_title as $title_key => $title) {
                        //  dd($image_key,$doc_key,$doc);
                        if ($image_key == $title_key) {
                            $user_document->title = $title;
                            $user_document->save();
                        }
                    }
                }
            }
            // End: add new documnents to user documents table

            if ($request->has('community')) {
                $community_user = new Community;
                $community_user->name  = $client->name;
                $community_user->email = $client->email;
                $community_user->status = 1;
                $community_user->specialist = $request->specialist ? $request->specialist : '';
                $community_user->keywords  = preg_replace('/\s*,\s*/', ',', $request->keywords);
                $community_user->location = $request->registered_address;
                $community_user->industry = $request->industry ? $request->industry : '';

                if ($request->logo != null) {
                    $file = $request->file('logo')->getClientOriginalName();
                    $image_input['imagename']  = time() . '-' . $file;
                    $destinationPath = public_path('community_user_logos/');
                    copy($client_details->logo,$destinationPath. $image_input['imagename']);
                    $community_user->logo = 'community_user_logos/' . $image_input['imagename'];
                }
                $community_user->save();
            }

            //Start: Create entry in xero api table
            $xero_data = new XeroDetail;
            $xero_data->user_id  = $client->id;
            $xero_data->client_id = $request->xero_client_id ? $request->xero_client_id : null;
            $xero_data->client_secret  = $request->xero_client_secret ? $request->xero_client_secret : null;
            $xero_data->status = 1;
            $xero_data->save();
            //End: Create entry in xero api table

            //Start: Create entry in xero api table
            $jobadder_data = new JobadderDetail;
            $jobadder_data->user_id  = $client->id;
            $jobadder_data->analytics_view_id  = $request->analytics_view_id ? $request->analytics_view_id : null;
            $jobadder_data->status = 1;
            $jobadder_data->save();
            //End: Create entry in xero api table

            //send reset password link
            // if ($request->has('send_email')) {
            //     $site_details = get_site_details();
            //     $SITE_NAME = $site_details['SITE_NAME'];
            //     $SITE_EMAIL = $site_details['SITE_EMAIL'];

            //     $email_array = array(
            //         'to_email' => $client->email,
            //         'to_name' =>  $client->name,
            //         'subject' => 'Recruiterlabs Activation Link',
            //         'from_email' => $SITE_EMAIL,
            //         'from_name' => $SITE_NAME,
            //         'web_access_token' => get_encrypt_value($client->remember_token)
            //     );

            //     html_sent_email('email/activation_template', $email_array);
            // }

            //send reset password link

            return response()->json(["user"=>$client],200);
        } catch (\Exception $e) {
            //  echo "$e"; exit;
            return response()->json($e->getmessage(),500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        // dd($request->toArray(),$id);
        if (Auth::user()->role_type == 1) {
            $user = User::where('id', $id)->with('xero_details','user_details', 'user_documents','jobadder_details')->first();
            if ($request->has('type_search') && $request->type_search != '') {
                $type_search = $request->type_search;
                // dd($type_search);
                $user = User::where('id', $id)->with('user_details')
                    ->with('user_documents', function ($q) use ($type_search) {
                        $q->where('type_id', '=', $type_search);
                    })
                    ->first();
                return $user->user_documents;
            } elseif ($request->has('type_search')) {
                $user = User::where('id', $id)->with('xero_details','user_details', 'user_documents')->first();
                return $user->user_documents;
            } elseif ($request->has('on_upload_click')) { // If upload is clicked show all docs
                $user = User::where('id', $id)->with('xero_details','user_details', 'user_documents')->first();
                return response()->json(['user'=>$user_documents],200);
            }
            return response()->json(['user'=>$user],200);
        }
    }

    //  To update client name, email, password
    public function update_profile(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->with('user_details')->first();
        return view('client.update_profile', compact('user'));
    }

    //  To update client company details, documents, bank details
    public function update_client_profile(Request $request)
    {
        // dd($request->toArray());
        try {
            $client = Auth::user();

            $validator = \Validator::make($request->all(), [
                'name' => ['required'],
                'email' => ['required', 'email', 'unique:users,email,' . $client->id],
                'password' => 'nullable|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            get_lastvalued_logged("users", $client);

            $client->name = $request->name;
            $client->email = $request->email;

            if ($request->filled('password') && !Hash::check($request->password, $client->password)) {
                $client->password = Hash::make($request->password);
            }

            $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $client
            ], 200);
        } catch (\Exception $e) {
            // echo "$e"; exit;
            return response()->json([
                'success' => false,
                'message' => 'Sorry, could not process.',
                'error' => $e->getMessage() // Optional: include this for debugging
            ], 500);
        }
    }
    public function documents(Request $request){
        $docs=doc_type();
        return response()->json(['documents' => $docs ], 200);
    }
    //  To update client profile
    public function my_business(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->with('user_details', 'user_documents', 'xero_details', 'jobadder_details')->first();
        // CHecking type_search by ajax call
        if($request->has('search_doc')&&$request->search_doc!=''){
            $search_doc=$request->search_doc;
            $user=User::where('id',Auth::user()->id)->with('user_details', 'user_documents', 'xero_details', 'jobadder_details')
            ->with('user_documents',function($q) use($search_doc){
                $q->where('title','like',"%".$search_doc."%");
            })->first();
            return response()->json(['user' => $user ], 200);
        }else if ($request->has('type_search') && $request->type_search != '') {
            $type_search = $request->type_search;
            // dd($type_search);
            $user = User::where('id', Auth::user()->id)->with('user_details', 'user_documents', 'xero_details', 'jobadder_details')
                ->with('user_documents', function ($q) use ($type_search) {
                    $q->where('type_id', '=', $type_search);
                })
                ->first();
            return response()->json(['user' => $user, 'type_search' => $type_search,], 200);        
            // return $user->user_documents;
        } else if ($request->has('type_search')) {
            $user = User::where('id', Auth::user()->id)->with('user_details', 'user_documents', 'xero_details', 'jobadder_details')->first();
            return response()->json(['user' => $user ], 200);
            // return $user->user_documents;
        } else if ($request->has('on_upload_click')) { // If upload is clicked show all docs
            $user = User::where('id', Auth::user()->id)->with('user_details', 'user_documents')->first();
            return $user->user_documents;
        }
        return response()->json(['user' => $user ], 200);
    }

    // public function update_client_business(Request $request, string $id)
    // {
    //     // dd($request->toArray());
    //     try {
    //             $validator = \Validator::make($request->all(), [
    //                 // 'company_name' => ['required'],
    //                 // 'company_number' => ['required'],
    //                 // 'registered_address' => ['required']
    //                 'document.*'=> ['required']
    //             ]);

    //             if($validator->fails()){
    //                 return back()->withInput()->withErrors($validator);
    //             }

    //             // $client_details = UserDetail::where('user_id',$id)->first();
    //             // get_lastvalued_logged("user_details",$client_details);
    //             // $client_details->company_name = $request->company_name;
    //             // $client_details->company_number  = $request->company_number;
    //             // $client_details->registered_address = $request->registered_address;
    //             // $client_details->vat_number = $request->vat_number;
    //             // $client_details->authentication_code  = $request->authentication_code;
    //             // $client_details->company_utr = $request->company_utr;
    //             // $client_details->bank_name  = $request->bank_name;
    //             // $client_details->sort_code = $request->sort_code;
    //             // $client_details->account_number = $request->account_number;
    //             // $client_details->iban  = $request->iban;
    //             // $client_details->swift_code = $request->swift_code;

    //             // Start: TO save user(client) logo
    //             // if($request->logo!=null) {
    //             //     $dir = public_path('client_documents/');
    //             //     if (!file_exists($dir)) {
    //             //         mkdir($dir, 0777, true);
    //             //     }
    //             //     $file = $request->file('logo')->getClientOriginalName();
    //             //     $image_input['imagename']  = 'CL-'.time().'-'.$file;
    //             //     $destinationPath = $dir.'CLIENT_'.Auth::user()->id;
    //             //     $request->logo->move($destinationPath, $image_input['imagename']);
    //             //     $client_details->logo = 'client_documents/CLIENT_'.Auth::user()->id.'/'.$image_input['imagename'];
    //             // }
    //             // End: TO save user(client) logo
    //             // $client_details->save();
    //                 //Start: Update documnets only if search is not applied
    //                 // if(!$request->has('type_search') || $request->type_search == null) {
    //                 //     // Start: TO update or delete old images
    //                 //     if($request->old_images) {
    //                 //         foreach ($request->old_images as $image_key => $oldimage) {
    //                 //             $oldimageData = UserDocument::where('id', $oldimage)->first();
    //                 //             get_lastvalued_logged("user_documents", $oldimageData);
    //                 //             foreach ($request->old_document as $doc_key => $doc) {
    //                 //                 if ($image_key==$doc_key) {
    //                 //                     $oldimageData->type_id = $doc;
    //                 //                     $oldimageData->save();
    //                 //                 }
    //                 //             }
    //                 //             //TO add DOcument Title
    //                 //             foreach ($request->old_title as $title_key => $title) {
    //                 //                 //  dd($image_key,$doc_key,$doc);
    //                 //                 if ($image_key==$title_key) {
    //                 //                     $oldimageData->title = $title;
    //                 //                     $oldimageData->save();
    //                 //                 }
    //                 //             }
    //                 //         }
    //                 //         //compare and delete
    //                 //         $user_doc_old_ids = UserDocument::where('user_id', $id)->pluck('id')->toArray();
    //                 //         $newimageIds = $request->old_images;
    //                 //         $images_diff_array = array_diff($user_doc_old_ids, $newimageIds);
    //                 //         // dd($user_doc_old_ids,$newimageIds,$images_diff_array);
    //                 //         foreach ($images_diff_array as $imageId) {
    //                 //             $imageData = UserDocument::where('id', $imageId)->first();
    //                 //             if ($imageData) {
    //                 //                 $image_path = public_path('/').$imageData->file;  // Value is not URL but directory file path
    //                 //                 if (File::exists($image_path)) {
    //                 //                     File::delete($image_path);
    //                 //                 }
    //                 //                 $imageData->delete();
    //                 //             }
    //                 //         }
    //                 //     } else {
    //                 //         $user_doc_old_ids = UserDocument::where('user_id', $id)->pluck('id')->toArray();
    //                 //         foreach ($user_doc_old_ids as $imageId) {
    //                 //             $imageData = UserDocument::where('id', $imageId)->first();
    //                 //             if ($imageData) {
    //                 //                 $image_path = public_path('/').$imageData->file;  // Value is not URL but directory file path
    //                 //                 if (File::exists($image_path)) {
    //                 //                     File::delete($image_path);
    //                 //                 }
    //                 //                 $imageData->delete();
    //                 //             }
    //                 //         }
    //                 //     }
    //                 //     // End: TO update or delete old images

    //                 //     // Start: To add new images
    //                 //     if ($request->images) {
    //                 //         foreach ($request->images as $image_key => $image) {
    //                 //             $dir = public_path('client_documents/');
    //                 //             if (!file_exists($dir)) {
    //                 //                 mkdir($dir, 0777, true);
    //                 //             }
    //                 //             $user_document = new UserDocument();
    //                 //             $user_document->file_size = $image->getSize();
    //                 //             $image_input['imagename'] = 'CD-'.time().'-'.$image->getClientOriginalName();
    //                 //             $destinationPath = $dir.'CLIENT_'.Auth::user()->id;
    //                 //             $image->move($destinationPath, $image_input['imagename']);
    //                 //             $user_document->file = 'client_documents/CLIENT_'.Auth::user()->id.'/'.$image_input['imagename'];
    //                 //             $user_document->user_id = Auth::user()->id;
    //                 //             $user_document->file_ext = $image->getClientOriginalExtension();
    //                 //             $user_document->status = 1;
    //                 //             foreach ($request->document as $doc_key => $doc) {
    //                 //                 //  dd($image_key,$doc_key,$doc);
    //                 //                 if ($image_key==$doc_key) {
    //                 //                     $user_document->type_id = $doc;
    //                 //                     $user_document->save();
    //                 //                 }
    //                 //             }

    //                 //             //TO add DOcument Title
    //                 //             foreach ($request->image_title as $title_key => $title) {
    //                 //                 //  dd($image_key,$doc_key,$doc);
    //                 //                 if ($image_key==$title_key) {
    //                 //                     $user_document->title = $title;
    //                 //                     $user_document->save();
    //                 //                 }
    //                 //             }

    //                 //         }
    //                 //     }
    //                 //     // End: To add new images

    //                 // }
    //                 //End: Update documnets only if search is not applied

    //                 //Start: Update entry in xero api table
    //                 // if($request->has('xero_client_id') && $request->xero_client_id != null) {
    //                 //     $xero_data = XeroDetail::where('user_id',Auth::user()->id)->first();
    //                 //     if($xero_data){
    //                 //         $xero_data->client_id = $request->xero_client_id;
    //                 //         $xero_data->client_secret  = $request->xero_client_secret;
    //                 //         $xero_data->save();
    //                 //     }else{
    //                 //         $xero_data = new XeroDetail;
    //                 //         $xero_data->user_id  = Auth::user()->id;
    //                 //         $xero_data->client_id = $request->xero_client_id;
    //                 //         $xero_data->client_secret  = $request->xero_client_secret;
    //                 //         $xero_data->status = 1;
    //                 //         $xero_data->save();
    //                 //     }
    //                 // }
    //                 //End   : Update entry in xero table

    //                 //Start : Update entry in jobadder  table
    //                 // if($request->has('jobadder_client_id') && $request->jobadder_client_id != null) {
    //                 //     $jobadder_data = JobadderDetail::where('user_id',Auth::user()->id)->first();
    //                 //     if($jobadder_data){
    //                 //         $jobadder_data->client_id = $request->jobadder_client_id;
    //                 //         $jobadder_data->client_secret  = $request->jobadder_client_secret ?? null;
    //                 //         $jobadder_data->analytics_view_id  = $request->analytics_view_id ?? null;
    //                 //         $jobadder_data->save();
    //                 //     }else{
    //                 //         $jobadder_data = new JobadderDetail;
    //                 //         $jobadder_data->user_id  = Auth::user()->id;
    //                 //         $jobadder_data->client_id = $request->jobadder_client_id;
    //                 //         $jobadder_data->client_secret  = $request->jobadder_client_secret ?? null;
    //                 //         $jobadder_data->analytics_view_id  = $request->analytics_view_id ?? null;
    //                 //         $jobadder_data->status = 1;
    //                 //         $jobadder_data->save();
    //                 //     }
    //                 // }else if($request->has('analytics_view_id') && $request->analytics_view_id != null){
    //                 //     $jobadder_data = JobadderDetail::where('user_id',Auth::user()->id)->first();
    //                 //     if($jobadder_data){
    //                 //         $jobadder_data->analytics_view_id  = $request->analytics_view_id ?? null;
    //                 //         $jobadder_data->save();
    //                 //     }else{
    //                 //         $jobadder_data = new JobadderDetail;
    //                 //         $jobadder_data->analytics_view_id  = $request->analytics_view_id ?? null;
    //                 //         $jobadder_data->user_id  = Auth::user()->id;
    //                 //         $jobadder_data->status = 1;
    //                 //         $jobadder_data->save();
    //                 //     }
    //                 // }
    //                 //End   : Update entry in jobadder table

    //             return redirect()->back()->with('success','Client updated successfully');

    //         } catch (\Exception $e) {
    //             // echo "$e"; exit;
    //             return redirect()->back()->withInput()->with('danger','Sorry could not process.');
    //         }
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    { 
        dd($request->all());
        exit;
        // dd($request->toArray());
        try {
            // $validator = \Validator::make($request->all(), [
            //     'name' => ['required'],
            //     'email' => 'unique:users,email,' . $id . ',id,deleted_at,NULL',
            //     'status' => ['required']
            // ]);  
            // if ($validator->fails()) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Validation failed',
            //         'errors' => $validator->errors()
            //     ], 422); // 422 is the HTTP status code for validation errors 
            // }

            // return response()->json(['user'=>'123'],200);
            $client = User::where('id', $id)->first();
            get_lastvalued_logged("users", $client);
            
            $client->name = $request->name;
            $client->email  = $request->email;
            $client->status = $request->status;
            if ($client->password == $request->password) {
                $client->password = $client->password;
            } else {
                $client->password = Hash::make($request->password);
            }
            $client->save();
            $client_details = UserDetail::where('user_id', $id)->first();
            get_lastvalued_logged("user_details", $client_details);
            $client_details->company_name = $request->company_name;
            $client_details->company_number  = $request->company_number;
            $client_details->registered_address = $request->registered_address;
            $client_details->vat_number = $request->vat_number;
            $client_details->authentication_code  = $request->authentication_code;
            $client_details->company_utr = $request->company_utr;
            $client_details->bank_name  = $request->bank_name;
            $client_details->sort_code = $request->sort_code;
            $client_details->account_number = $request->account_number;
            $client_details->iban  = $request->iban;
            $client_details->swift_code = $request->swift_code;

            // Start: TO save user(client) logo
            if ($request->logo != null) {
                $dir = public_path('client_documents/');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $file = $request->file('logo')->getClientOriginalName();
                $image_input['imagename']  = 'CL-' . time() . '-' . $file;
                $destinationPath = $dir . 'CLIENT_' . $client->id;
                $request->logo->move($destinationPath, $image_input['imagename']);
                $client_details->logo = 'client_documents/CLIENT_' . $client->id . '/' . $image_input['imagename'];
            }
            // End: TO save user(client) logo
            $client_details->save();
            // Update documents only if search is not applied
            if (!$request->has('type_search') || $request->type_search == null) {
                // Start: compare and delete old documnents from user documents table
                if ($request->old_images) {
                    foreach ($request->old_images as $image_key => $oldimage) {
                        $oldimageData = UserDocument::where('id', $oldimage)->first();
                        get_lastvalued_logged("user_documents", $oldimageData);
                        foreach ($request->old_document as $doc_key => $doc) {
                            if ($image_key == $doc_key) {
                                $oldimageData->type_id = $doc;
                                $oldimageData->save();
                            }
                        }
                        //TO add DOcument Title
                        foreach ($request->old_title as $title_key => $title) {
                            //  dd($image_key,$doc_key,$doc);
                            if ($image_key == $title_key) {
                                $oldimageData->title = $title;
                                $oldimageData->save();
                            }
                        }
                    }
                    //compare and delete
                    $user_doc_old_ids = UserDocument::where('user_id', $id)->pluck('id')->toArray();
                    $newimageIds = $request->old_images;
                    $images_diff_array = array_diff($user_doc_old_ids, $newimageIds);
                    // dd($user_doc_old_ids,$newimageIds,$images_diff_array);
                    foreach ($images_diff_array as $imageId) {
                        $imageData = UserDocument::where('id', $imageId)->first();
                        if ($imageData) {
                            $image_path = public_path('/') . $imageData->file;  // Value is not URL but directory file path
                            if (File::exists($image_path)) {
                                File::delete($image_path);
                            }
                            $imageData->delete();
                        }
                    }
                } else {
                    $user_doc_old_ids = UserDocument::where('user_id', $id)->pluck('id')->toArray();
                    foreach ($user_doc_old_ids as $imageId) {
                        $imageData = UserDocument::where('id', $imageId)->first();
                        if ($imageData) {
                            $image_path = public_path('/') . $imageData->file;  // Value is not URL but directory file path
                            if (File::exists($image_path)) {
                                File::delete($image_path);
                            }
                            $imageData->delete();
                        }
                    }
                }
                // End: compare and delete old documnents from user documents table

                // Start: add new documnents to user documents table
                if ($request->images) {
                    foreach ($request->images as $image_key => $image) {
                        $dir = public_path('client_documents/');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0777, true);
                        }
                        $user_document = new UserDocument();
                        $user_document->file_size = $image->getSize();
                        $image_input['imagename'] = 'CD-' . time() . '-' . $image->getClientOriginalName();
                        $destinationPath = $dir . 'CLIENT_' . $client->id;
                        $image->move($destinationPath, $image_input['imagename']);
                        $user_document->file = 'client_documents/CLIENT_' . $client->id . '/' . $image_input['imagename'];
                        $user_document->user_id = $client->id;
                        $user_document->file_ext = $image->getClientOriginalExtension();
                        $user_document->status = 1;
                        foreach ($request->document as $doc_key => $doc) {
                            //   dd($image_key,$doc_key,$doc);
                            if ($image_key == $doc_key) {
                                $user_document->type_id = $doc ? $doc : '';
                                $user_document->save();
                            }
                        }

                        //TO add DOcument Title
                        foreach ($request->image_title as $title_key => $title) {
                            //  dd($image_key,$doc_key,$doc);
                            if ($image_key == $title_key) {
                                $user_document->title = $title;
                                $user_document->save();
                            }
                        }
                    }
                }
                // End: add new documnents to user documents table
            }
            //End: Update documents only if search is not applied

            //Start: Update entry in xero api table
            if ($request->has('xero_client_id')) {
                $xero_data = XeroDetail::where('user_id', $client->id)->first();
                if ($xero_data) {
                    $xero_data->client_id = $request->xero_client_id ? $request->xero_client_id : null;
                    $xero_data->client_secret  = $request->xero_client_secret ? $request->xero_client_secret : null;
                    $xero_data->save();
                } else {
                    $xero_data = new XeroDetail;
                    $xero_data->user_id  = $client->id;
                    $xero_data->client_id = $request->xero_client_id ? $request->xero_client_id : null;
                    $xero_data->client_secret  = $request->xero_client_secret ? $request->xero_client_secret : null;
                    $xero_data->status = 1;
                    $xero_data->save();
                }
            }
            if ($xero_data->client_id == null || $xero_data->xero_client_secret == null) {
                $client->xero_oauth = null;
                $client->save();
            }
            //End   : Update entry in xero table

            //Start : Update entry in jobadder  table
            // if($request->has('jobadder_client_id') && $request->jobadder_client_id != null) {
            //     $jobadder_data = JobadderDetail::where('user_id',$client->id)->first();
            //     if($jobadder_data){
            //         $jobadder_data->client_id = $request->jobadder_client_id;
            //         $jobadder_data->client_secret  = $request->jobadder_client_secret ?? null;
            //         $jobadder_data->analytics_view_id  = $request->analytics_view_id ?? null;
            //         $jobadder_data->save();
            //     }else{
            //         $jobadder_data = new JobadderDetail;
            //         $jobadder_data->user_id  = $client->id;
            //         $jobadder_data->analytics_view_id  = $request->analytics_view_id ?? null;
            //         $jobadder_data->status = 1;
            //         $jobadder_data->save();
            //     }
            // }else
            if ($request->has('analytics_view_id')) {
                $jobadder_data = JobadderDetail::where('user_id', $client->id)->first();
                if ($jobadder_data) {
                    $jobadder_data->analytics_view_id  =  $request->analytics_view_id ? $request->analytics_view_id : null;
                    $jobadder_data->save();
                } else {
                    $jobadder_data = new JobadderDetail;
                    $jobadder_data->analytics_view_id  = $request->analytics_view_id ? $request->analytics_view_id : null;
                    $jobadder_data->user_id  = $client->id;
                    $jobadder_data->status = 1;
                    $jobadder_data->save();
                }
            }
            //End   : Update entry in jobadder table

            return response()->json(['user'=>$client],200);
        } catch (\Exception $e) {
            //    echo "$e"; exit;
            return response()->json(["msg"=>$e->getmessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        get_lastvalued_logged("users", $user);

        // //Start: Delete all employees data
        //     $employee_docs =  EmployeeDocument::where('user_id',$id)->get();
        //     foreach ($employee_docs as $employee_doc) {
        //         $employee_data = EmployeeDocument::where('id', $employee_doc->id)->first();
        //         get_lastvalued_logged("employee_documents",$employee_data);
        //             if ($employee_data) {
        //                 $image_path = public_path('/').$employee_doc->file;
        //                  // Value is not URL but directory file path
        //                 // if (File::exists($image_path)) {
        //                 //     // dd($image_path);
        //                 //     File::delete($image_path);
        //                 // }
        //                 $employee_data->delete();
        //             }
        //     }
        //     $employee_details_data =  EmployeeDetail::where('user_id',$id)->get();
        //     foreach ($employee_details_data as $employee_data) {
        //         $employee_details = EmployeeDetail::where('id', $employee_data->id)->first();
        //         // dd($employee_details->toArray());
        //         get_lastvalued_logged("employee_details", $employee_details);
        //         if ($employee_details) {
        //             $image_path = public_path('/').$employee_details->emp_picture;  // Value is not URL but directory file path
        //             // if (File::exists($image_path)) {
        //             //     File::delete($image_path);
        //             // }
        //             $employee_details->delete();
        //         }
        //     }
        //     $employees =  Employee::where('user_id',$id)->get();
        //     foreach ($employees as $employee) {
        //         $employee = Employee::where('id', $employee->id)->first();
        //         get_lastvalued_logged("employees", $employee);
        //         if($employee) {
        //             $employee->delete();
        //         }
        //     }
        // //End: Delete all client-employees data
        // //Start: Delete all client tickets data
        //     $user_tickets =  Ticket::where('user_id',$id)->get();
        //     foreach ($user_tickets as $user_ticket) {
        //         $ticket = Ticket::where('id', $user_ticket->id)->first();
        //         get_lastvalued_logged("user_tickets", $ticket);
        //         if($ticket) {
        //             $ticket->delete();
        //         }
        //     }
        // //End: Delete all client tickets data

        // //Start: Delete all client HRDocument data
        // $hr_docs =  HRDocument::where('user_id',$id)->get();
        //     foreach ($hr_docs as $hr_doc) {
        //         $hr_doc_data = HRDocument::where('id', $hr_doc->id)->first();
        //         get_lastvalued_logged("hr_documents",$hr_doc_data);
        //             if ($hr_doc_data) {
        //                 $image_path = public_path('/').$hr_doc_data->file;
        //                  // Value is not URL but directory file path
        //                 // if (File::exists($image_path)) {
        //                 //     // dd($image_path);
        //                 //     File::delete($image_path);
        //                 // }
        //                 $hr_doc_data->delete();
        //             }
        //     }
        // //End: Delete all client HRDocument data



        // //Start: Delete all client KnowledgeBase data
        //     $kb_docs =  KnowledgeBase::where('user_id',$id)->get();
        //     foreach ($kb_docs as $kb_doc) {
        //         $kb_doc_data = KnowledgeBase::where('id', $kb_doc->id)->first();
        //         get_lastvalued_logged("knowledge_bases",$kb_doc_data);
        //             if ($kb_doc_data) {
        //                 $image_path = public_path('/').$kb_doc_data->file;
        //                  // Value is not URL but directory file path
        //                 // if (File::exists($image_path)) {
        //                 //     // dd($image_path);
        //                 //     File::delete($image_path);
        //                 // }
        //                 $kb_doc_data->delete();
        //             }
        //     }
        // //End: Delete all client KnowledgeBase data

        // //Start: Delete all client events data
        // $user_events =  Event::where('user_id',$id)->get();
        // foreach ($user_events as $user_event) {
        //     $user_event_data = Event::where('id', $user_event->id)->first();
        //     get_lastvalued_logged("events", $user_event_data);
        //     if($user_event_data) {
        //         $user_event_data->delete();
        //     }
        // }
        // //End: Delete all client events data



        // $user_detail = UserDetail::where('user_id',$user->id)->first();
        // get_lastvalued_logged("user_details",$user_detail);
        // if ($user_detail) {
        //     $image_path = public_path('/').$user_detail->logo;  // Value is not URL but directory file path
        //     // dd($image_path);
        //     // if (File::exists($image_path)) {
        //     //     File::delete($image_path);
        //     // }
        //     $user_detail->delete();
        // }

        // $user_docs = UserDocument::where('user_id',$id)->get();
        //     foreach ($user_docs as $user_doc) {
        //         $user_data = UserDocument::where('id', $user_doc->id)->first();
        //         get_lastvalued_logged("user_documents",$user_data);
        //                 // if ($user_data) {
        //                 //     $image_path = public_path('/').$user_data->file;  // Value is not URL but directory file path
        //                 //     if (File::exists($image_path)) {
        //                 //         File::delete($image_path);
        //                 //     }
        //                     $user_data->delete();
        //                 // }
        //     }

        // XeroDetail::where('user_id',$id)->get();
        // JobadderDetail::where('user_id',$id)->get();
        //     File::deleteDirectory(public_path('client_documents/CLIENT_'.$user->id));

        User::destroy($id);

        return  response()->json([],200);
    }

    //check token for reset password and first time login
    public function resetPassword(Request $request)
    {
        // Validate input
        try {
            $token = get_decrypt_value($request->q);

            $user = User::where('remember_token', $token)->first();
            \Session::put('user', $user);
            // dd($user);
            // Redirect the user back to the password reset request form if the token is invalid
            if ($user) {
                $email = $user->email;
                // dd($email);
                return view('reset_password', compact('email'));
            } else {
                return view('reset_password');
            }
        } catch (\Exception $e) {
            // echo "$e"; exit;
            return view('reset_password');
        }
        return view('reset_password');
    }

    //check token for reset password and first time login
    public function changePassword(Request $request)
    {
        //Validate input
        $validator = \Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|confirmed'
        ]);

        //check if payload is valid before moving on
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = \Session::get('user');
        $user = User::where('email', $user->email)->first();
        // Redirect the user back if the email is invalid
        if (!$user) {
            return redirect()->back()->with('danger', 'Email did not match');
        }
        //Hash and update the new password
        $user->password = Hash::make($request->password);
        $user->update(); //or $user->save();

        return redirect('/')->with('success', 'Password changed successfully');
    }

    // TO upload client related HR documents
    public function hr_documents(Request $request)
    {
        // dd($request->toArray());

        if (Auth::user()->role_type == 1) {
            $user_id = $request->user_id;
        } else {
            $user_id = Auth::user()->id;
        }

        // IF search keyword is set we will not update any docs
        if (!$request->has('search_title') || $request->search_title == null) {
            // Start: compare and delete old documnents from user documents table
            if ($request->old_images) {
                foreach ($request->old_images as $image_key => $oldimage) {
                    $oldimageData = HRDocument::where('id', $oldimage)->first();
                    get_lastvalued_logged("user_documents", $oldimageData);

                    //TO add DOcument Title
                    foreach ($request->old_title as $title_key => $title) {
                        //  dd($image_key,$doc_key,$doc);
                        if ($image_key == $title_key) {
                            $oldimageData->title = $title;
                            $oldimageData->save();
                        }
                    }

                    $oldimageData->type_id = 7; //Type id 7 is for HR documents
                    $oldimageData->save();
                }
                //compare and delete
                $user_doc_old_ids = HRDocument::where('user_id', $user_id)->pluck('id')->toArray();
                $newimageIds = $request->old_images;
                $images_diff_array = array_diff($user_doc_old_ids, $newimageIds);
                // dd($user_doc_old_ids,$newimageIds,$images_diff_array);
                foreach ($images_diff_array as $imageId) {
                    $imageData = HRDocument::where('id', $imageId)->first();
                    if ($imageData) {
                        $image_path = public_path('/') . $imageData->file;  // Value is not URL but directory file path
                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                        $imageData->delete();
                    }
                }
            } else {
                $user_doc_old_ids = HRDocument::where('user_id', $user_id)->pluck('id')->toArray();
                foreach ($user_doc_old_ids as $imageId) {
                    $imageData = HRDocument::where('id', $imageId)->first();
                    if ($imageData) {
                        $image_path = public_path('/') . $imageData->file;  // Value is not URL but directory file path
                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                        $imageData->delete();
                    }
                }
            }
            // End: compare and delete old documnents from user documents table

            // Start: add new documnents to user documents table
            if ($request->images) {
                foreach ($request->images as $image_key => $image) {
                    $dir = public_path('client_documents/');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $hr_document = new HRDocument();
                    $hr_document->file_size = $image->getSize();
                    $image_input['imagename'] = 'HRD-' . time() . '-' . $image->getClientOriginalName();
                    $destinationPath = $dir . 'CLIENT_' . $user_id;
                    $image->move($destinationPath, $image_input['imagename']);
                    $hr_document->file = 'client_documents/CLIENT_' . $user_id . '/' . $image_input['imagename'];
                    $hr_document->user_id = $user_id;
                    $hr_document->file_ext = $image->getClientOriginalExtension();
                    $hr_document->status = 1;
                    $hr_document->type_id = 7; //Type id 7 is for HR documents

                    //To add DOcument Title
                    foreach ($request->image_title as $title_key => $title) {
                        //  dd($image_key,$doc_key,$doc);
                        if ($image_key == $title_key) {
                            $hr_document->title = $title;
                            $hr_document->save();
                        }
                    }
                    $hr_document->save();
                }
            }
        }

        if (Auth::user()->role_type == 1) {
            return redirect('employee_list/' . $user_id)->with('success', 'HR documents updated successfully');
        } else {
            return redirect('employees')->with('success', 'HR documents updated successfully');
        }
        // End: add new documnents to user documents table
    }


    public function privacy()
    {
        return view('privacy');
    }


    public function terms()
    {
        return view('terms');
    }


    // public function jobadder()
    // {
    //         return 1;
    // }

    public function reset_link(Request $request)
    {
        try {

            if (Auth::user()->role_type == 1) {
                // dd($request->user_id);
                $client = User::where('id', $request->user_id)->first();
                //send reset password link
                $site_details = get_site_details();
                $SITE_NAME = $site_details['SITE_NAME'];
                $SITE_EMAIL = $site_details['SITE_EMAIL'];

                $email_array = array(
                    'to_email' => $client->email,
                    'to_name' =>  $client->name,
                    'subject' => 'Recruiterlabs Activation Link',
                    'from_email' => $SITE_EMAIL,
                    'from_name' => $SITE_NAME,
                    'web_access_token' => get_encrypt_value($client->remember_token)
                );

                html_sent_email('email/activation_template', $email_array);
            }

            //send reset password link
            return redirect('users')->with('success', 'Reset password link sent successfully');
        } catch (\Exception $e) {
            //  echo "$e"; exit;
            return redirect()->back()->withInput()->with('danger', 'Sorry, Mail send unsuccessfull.');
        }
    }



    public function delete_api_data(Request $request)
    {
        try {
            if (Auth::user()->role_type == 1) {
                if ($request->thirdparty_data == 'xero_data') {
                    $xero_data = Xerodetail::where('user_id', $request->client_id)->first();
                    if ($xero_data) {
                        $xero_data->client_id =  null;
                        $xero_data->client_secret  =  null;
                        $xero_data->save();
                    }
                    $user_oauth_data = User::where('id', $request->client_id)->first();
                    $user_oauth_data->xero_oauth  =  null;
                    $user_oauth_data->save();
                }
                if ($request->thirdparty_data == 'jobadder_data') {
                    $jobadder_data = JobadderDetail::where('user_id', $request->client_id)->first();
                    if ($jobadder_data) {
                        $jobadder_data->access_token =  null;
                        $jobadder_data->refresh_token  =  null;
                        $jobadder_data->code  =  null;
                        $jobadder_data->state =  null;
                        $jobadder_data->token  =  null;
                        $jobadder_data->auth_response  =  null;
                        $jobadder_data->refresh_token_response  =  null;
                        $jobadder_data->save();
                    }
                }
                if ($request->thirdparty_data == 'ga_data') {
                    $ga_data = JobadderDetail::where('user_id', $request->client_id)->first();
                    if ($ga_data) {
                        $ga_data->analytics_view_id  =  null;
                        $ga_data->save();
                    }
                }
            }

            return redirect()->back()->with('success', 'Data deleted successfully');
        } catch (\Exception $e) {
            //  echo "$e"; exit;
            return redirect()->back()->withInput()->with('danger', 'Sorry, Data delete unsuccessfull.');
        }
    }
}
