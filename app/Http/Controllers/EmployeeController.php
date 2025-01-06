<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDetail;
use App\Models\EmployeeDocument;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use File;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $events = Event::all();
        $employees = Employee::with('employee_details')->where('user_id',Auth::user()->id)->get();
        $data = User::with('user_hr_documents')->where('id',Auth::user()->id)->first();
                if($request->has('str_search') && $request->str_search != '' )
                {
                    $value = $request->str_search;
                    $data = User::with(['user_hr_documents' => function($q) use($value) {
                        // Query the name field in status table
                        $q->where('title', 'LIKE', '%'.$value.'%'); // '=' is optional
                    }])
                    ->where('id',Auth::user()->id)
                    ->first();
                    return $data->user_hr_documents;
                }else if($request->has('str_search')){
                    $data = User::with('user_hr_documents')->where('id',Auth::user()->id)->first();
                    return $data->user_hr_documents;
                }else if($request->has('on_upload_click')){
                    $data = User::with('user_hr_documents')->where('id',Auth::user()->id)->first();
                    return $data->user_hr_documents;
                }
                //  dd($user->toArray());
        return view('client.employees.show', compact('employees','events','data'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if(Auth::user()->role_type==1){
            // dd($request->toArray());
            $user = User::where('id',$request->user_id)->first();
            $employees = Employee::where('user_id',$request->user_id)->get();
            return view('admin.employees.create',compact('employees','user'));
        }else{
            // $employees = Employee::where('user_id',Auth::user()->id)->get();
            // return view('client.employees.create',compact('employees'));
            return redirect('/home');
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->toArray());
        try {
            $validator = \Validator::make($request->all(), [
                'name' => ['required'],
                'email' => ['required','unique:employees,email,'],
                'address' => ['required'],
                'phone_number' => ['required'],
                'date_of_birth' => ['required'],
                'title' => ['required'],
                'salary'=> ['required'],
                'date_of_joining' => ['required']
            ]);

            if($validator->fails()){
                return back()->withInput()->withErrors($validator);
            }

            if(Auth::user()->role_type==1) {
                $user_id = $request->user_id;
            }else{
                $user_id = Auth::user()->id;
            }

            $employee = new Employee;
            $employee->user_id = $user_id;
            $last_employee = Employee::latest()->pluck('employee_code')->first();
            if($last_employee){
                $last_employee_id =substr($last_employee, strpos($last_employee, "-") + 1)+1;
            }else{
                $last_employee_id ='1';
            }
            // dd($last_employee,$last_employee_id);
            $employee->employee_code = 'EMP'.$user_id.'-'.$last_employee_id;
            $employee->name  = $request->name;
            $employee->email = $request->email;
            $employee->address = $request->address;
            $employee->phone_number  = $request->phone_number;
            $employee->date_of_birth = $request->date_of_birth;
            $employee->status  = $request->status ? $request->status : 1;

            $employee->save();

            $employee_details = new EmployeeDetail();
            $employee_details->user_id = $user_id;
            $employee_details->employee_id = $employee->id;
            $employee_details->title = $request->title;
            $salary = str_replace('£ ', '', $request->salary);
            $employee_details->salary = $salary;
            $employee_details->direct_reports  = $request->direct_reports;
            $employee_details->date_of_joining = $request->date_of_joining;
            $employee_details->bank_name  = $request->bank_name;
            $employee_details->sort_code = $request->sort_code;
            $employee_details->account_number = $request->account_number;
            if($request->emp_picture!=null) {
                $dir = public_path('client_documents/');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0777, true);
                    }
                $file = $request->file('emp_picture')->getClientOriginalName();
                $image_input['imagename']  = 'PL-'.$employee->id.'-'.time().'-'.$file;
                $destinationPath = $dir.'CLIENT_'.$user_id;
                $request->emp_picture->move($destinationPath, $image_input['imagename']);
                $employee_details->emp_picture = 'client_documents/CLIENT_'.$user_id.'/'.$image_input['imagename'];
            }
            $employee_details->save();

            if ($request->images) {
                foreach ($request->images as $image_key => $image) {
                    $dir = public_path('client_documents/');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0777, true);
                    }
                    $employee_document = new EmployeeDocument();
                    $employee_document->user_id = $user_id;
                    $employee_document->employee_id = $employee->id;
                    $employee_document->status = 1;
                    $employee_document->title = $image->getClientOriginalName();
                    $employee_document->file_size = $image->getSize();
                    $image_input['imagename'] = 'PD-'.$employee->id.'-'.time().'-'.$image->getClientOriginalName();
                    $destinationPath = $dir.'CLIENT_'.$user_id;
                    $image->move($destinationPath, $image_input['imagename']);
                    $employee_document->file = 'client_documents/CLIENT_'.$user_id.'/'.$image_input['imagename'];
                    $employee_document->file_ext = $image->getClientOriginalExtension();
                    $employee_document->type_id = 8;//Doc type id 8 is for People documents

                    //TO add DOcument Title
                    foreach ($request->image_title as $title_key => $title) {
                        //  dd($image_key,$doc_key,$doc);
                        if ($image_key==$title_key) {
                            $employee_document->title = $title;
                            $employee_document->save();
                        }
                    }
                    $employee_document->save();

                }
            }

            if(Auth::user()->role_type==1) {
                return redirect('employee_list/'.$user_id)->with('success','Employee created successfully');
            }else{
                return redirect('employees')->with('success','Employee created successfully');
            }

        } catch (\Exception $e) {
            //  echo "$e"; exit;
            return redirect()->back()->withInput()->with('danger','Sorry could not process.');
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
    public function edit(Request $request,string $id)
    {

        if(Auth::user()->role_type==1) {
            $user_id = $request->user_id;
        }else{
            $user_id = Auth::user()->id;
        }

            $user = User::where('id',$user_id)->first();
            $employee_list = Employee::where('user_id',$user_id)->where('id','!=',$id)->get();
            $employee = Employee::where('id',$id)->with('employee_details','employee_documents')->first();

                if($request->has('title_search') && $request->title_search != '' )
                {
                    $value = $request->title_search;
                    $employee = Employee::where('id',$id)->with('employee_details')
                    ->with(['employee_documents' => function($q) use($value) {
                        // Query the name field in status table
                        $q->where('title', 'LIKE', '%'.$value.'%'); // '=' is optional
                    }])
                    ->first();
                    return $employee->employee_documents;
                }else if($request->has('title_search')){
                    $employee = Employee::where('id',$id)->with('employee_details','employee_documents')->first();
                    return $employee->employee_documents;
                }else if($request->has('on_upload_click')){
                    $employee = Employee::where('id',$id)->with('employee_details','employee_documents')->first();
                    return $employee->employee_documents;
                }

            if(Auth::user()->role_type==1) {
                return view('admin.employees.edit', compact('employee','employee_list','user'));
            }else{
                return view('client.employees.edit', compact('employee','employee_list'));
            }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->toArray());
        try {
            $validator = \Validator::make($request->all(), [
                'name' => ['required'],
                'email' => ['required','unique:employees,email,'. $id],
                'address' => ['required'],
                'phone_number' => ['required'],
                'date_of_birth' => ['required'],
                'title' => ['required'],
                'salary'=> ['required'],
                'date_of_joining' => ['required']
            ]);

            if($validator->fails()){
                return back()->withInput()->withErrors($validator);
            }

            if(Auth::user()->role_type==1) {
                $user_id = $request->user_id;
            }else{
                $user_id = Auth::user()->id;
            }

            $employee = Employee::where('id',$id)->first();
            get_lastvalued_logged("employees",$employee);
            $employee->name  = $request->name;
            $employee->email = $request->email;
            $employee->address = $request->address;
            $employee->phone_number  = $request->phone_number;
            $employee->date_of_birth = $request->date_of_birth;
            $employee->status  = $request->status ? $request->status : 1;
            $employee->save();

            $employee_details = EmployeeDetail::where('employee_id',$id)->first();
            get_lastvalued_logged("employee_details",$employee_details);
            $employee_details->title = $request->title;
            $salary = str_replace('£ ', '', $request->salary);
            $employee_details->salary = $salary;
            $employee_details->direct_reports  = $request->direct_reports;
            $employee_details->date_of_joining = $request->date_of_joining;
            $employee_details->bank_name  = $request->bank_name;
            $employee_details->sort_code = $request->sort_code;
            $employee_details->account_number = $request->account_number;
            if($request->emp_picture!=null) {
                $dir = public_path('client_documents/');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0777, true);
                    }
                $file = $request->file('emp_picture')->getClientOriginalName();
                $image_input['imagename']  = 'PL-'.$employee->id.'-'.time().'-'.$file;
                $destinationPath = $dir.'CLIENT_'.$user_id;
                $request->emp_picture->move($destinationPath, $image_input['imagename']);
                $employee_details->emp_picture = 'client_documents/CLIENT_'.$user_id.'/'.$image_input['imagename'];
            }
            $employee_details->save();

            if(!$request->has('title_search') || $request->title_search == null) {
                // Start: TO update or delete old images
                if($request->old_images) {
                    foreach ($request->old_images as $image_key => $oldimage) {
                        $oldimageData = EmployeeDocument::where('id', $oldimage)->first();
                        get_lastvalued_logged("employee_documents", $oldimageData);

                        $oldimageData->type_id = 8;//Type id 8 is for people document
                        $oldimageData->save();


                        //TO add DOcument Title
                        foreach ($request->old_title as $title_key => $title) {
                            //  dd($image_key,$doc_key,$doc);
                            if ($image_key==$title_key) {
                                $oldimageData->title = $title;
                                $oldimageData->save();
                            }
                        }
                    }
                    //compare and delete
                    $employee_doc_old_ids = EmployeeDocument::where('user_id', $user_id)->where('employee_id', $id)->pluck('id')->toArray();
                    $newimageIds = $request->old_images;
                    $images_diff_array = array_diff($employee_doc_old_ids, $newimageIds);
                    // dd($employee_doc_old_ids,$newimageIds,$images_diff_array);
                    foreach ($images_diff_array as $imageId) {
                        $imageData = EmployeeDocument::where('id', $imageId)->first();
                        if ($imageData) {
                            $image_path = public_path('/').$imageData->file;  // Value is not URL but directory file path
                            if (File::exists($image_path)) {
                                File::delete($image_path);
                            }
                            $imageData->delete();
                        }
                    }
                } else {
                    $employee_doc_old_ids = EmployeeDocument::where('user_id', $user_id)->where('employee_id', $id)->pluck('id')->toArray();
                    foreach ($employee_doc_old_ids as $imageId) {
                        $imageData = EmployeeDocument::where('id', $imageId)->first();
                        if ($imageData) {
                            $image_path = public_path('/').$imageData->file;  // Value is not URL but directory file path
                            if (File::exists($image_path)) {
                                File::delete($image_path);
                            }
                            $imageData->delete();
                        }
                    }
                }
                // End: TO update or delete old images

                // Start: TO store new images
                if ($request->images) {
                    // dd($request->image_title);
                    foreach ($request->images as $image_key => $image) {
                        $dir = public_path('client_documents/');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0777, true);
                        }
                        $employee_document = new EmployeeDocument();
                        $employee_document->user_id = $user_id;
                        $employee_document->employee_id = $employee->id;
                        $employee_document->status = 1;
                        $employee_document->file_size = $image->getSize();
                        $image_input['imagename'] = 'PD-'.$employee->id.'-'.time().'-'.$image->getClientOriginalName();
                        $destinationPath = $dir.'CLIENT_'.$user_id;
                        $image->move($destinationPath, $image_input['imagename']);
                        $employee_document->file = 'client_documents/CLIENT_'.$user_id.'/'.$image_input['imagename'];
                        $employee_document->file_ext = $image->getClientOriginalExtension();

                        //TO add document type
                        $employee_document->type_id = 8;//Type id 8 is for people document
                        // $employee_document->save();

                        //TO add DOcument Title
                        foreach ($request->image_title as $title_key => $title) {
                            if ($image_key==$title_key) {
                                $employee_document->title = $title;
                                $employee_document->save();
                            }
                        }
                    }
                }
                // End: TO store new images
            }

            if(Auth::user()->role_type==1) {
                return redirect('employee_list/'.$user_id)->with('success','Employee updated successfully');
            }else{
                return redirect('employees')->with('success','Employee updated successfully');
            }

        } catch (\Exception $e) {
            echo "$e"; exit;
            return redirect()->back()->withInput()->with('danger','Sorry could not process.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        //  dd($request->toArray(),$id);
        if(Auth::user()->role_type==1) {
            $user_id = $request->user_id;
                // }else{
                //     $user_id = Auth::user()->id;
                // }

            $employee_docs =  EmployeeDocument::where(['user_id'=>$user_id,'employee_id'=>$id])->get();
                foreach ($employee_docs as $employee_doc) {
                    $employee_data = EmployeeDocument::where('id', $employee_doc->id)->first();
                    get_lastvalued_logged("employee_documents",$employee_data);
                        if ($employee_data) {
                            $image_path = public_path('/').$employee_doc->file;
                            // Value is not URL but directory file path
                            if (File::exists($image_path)) {
                                // dd($image_path);
                                File::delete($image_path);
                            }
                            $employee_data->delete();
                        }
                }
                $employee_details = EmployeeDetail::where(['user_id'=>$user_id,'employee_id'=>$id])->first();
                get_lastvalued_logged("employee_details", $employee_details);
                    if ($employee_details) {
                        $image_path = public_path('/').$employee_details->emp_picture;  // Value is not URL but directory file path
                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                        $employee_details->delete();
                    }

                $employee=Employee::where('id',$id)->first();
                get_lastvalued_logged("employees", $employee);
                if($employee) {
                    $employee->delete();
                }

                // if(Auth::user()->role_type==1) {
                    return redirect('employee_list/'.$user_id)->with('success','Employee deleted successfully');
                // }else{
                //     return redirect('employees')->with('success','Employee deleted successfully');
        }

    }

    public function client_list()
    {
        if(Auth::user()->role_type==1) {
            $users = User::where('role_type', 2)->with('user_details', 'user_people')->get();
            return view('admin.employees.client_list', compact('users'));
        }
    }

    public function employee_list(Request $request,$id)
    {
        if(Auth::user()->role_type==1) {
            $employee_list = Employee::where('user_id', $id)->get();
            $user = User::with('user_hr_documents')->where('id', $id)->first();
            $value = null;
            if($request->has('str_search') && $request->str_search != '') {
                $value = $request->str_search;
                // dd($value);
                $user = User::with(['user_hr_documents' => function ($q) use ($value) {
                    // Query the title field in user_documents table
                    $q->where('title', 'LIKE', '%'.$value.'%'); // '=' is optional
                }])
                ->where('id', $id)
                ->first();
                return $user->user_hr_documents;
            } elseif($request->has('str_search')) {
                $value = null;
                $user = User::with('user_hr_documents')->where('id', $id)->first();
                return $user->user_hr_documents;
            }

            if($request->has('on_upload_click')) {
                $user = User::with('user_hr_documents')->where('id', $id)->first();
                return $user->user_hr_documents;
            }
            // dd($user->toArray());
            return view('admin.employees.show', compact('employee_list', 'user', 'value'));
        }
    }

    public function employee_view($id)
    {
        if(Auth::user()->role_type==1) {
            $employee_list = Employee::where('user_id', $id)->with('employee_details')->get();
            $employee = Employee::where('id', $id)->first();
            return view('admin.employees.view', compact('employee', 'employee_list'));
        }
    }
}
