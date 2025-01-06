<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBase;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use File;

class KnowledgeBaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // if(Auth::user()->role_type==1){
        //     $user_id = $user_id;
        // }else{
        //     $user_id = Auth::user()->id;
        // }
        $documents = KnowledgeBase::where('type','DOCUMENT')->get();
        $videos = KnowledgeBase::where('type','VIDEO')->get();
        // $FAQS = KnowledgeBase::where('type','FAQS')->get();
        // $user = User::where('id',$user_id)->first();
        $FAQS = KnowledgeBase::where('type','FAQS')->paginate(10);
        if(Auth::user()->role_type==1){
            return view('admin.kb.show', compact('documents','videos','FAQS'));
        }else{
            return view('client.knowledge_base.show', compact('documents','videos','FAQS'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($user_id=null)
    {
        if(Auth::user()->role_type==1) {
            $user = User::where('id',$user_id)->first();
            return view('admin.kb.create',compact('user'));
        }else{
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
                // $validator = \Validator::make($request->all(), [
                //     'title' => ['required'],
                //     'description' => ['required'],
                //     'message' => ['required'],
                //     // 'status' => ['required'],
                // ]);

                // if($validator->fails()){
                //     return back()->withInput()->withErrors($validator);
                // }


                $data = new KnowledgeBase();
                // $data->user_id = $request->user_id;
                $data->type = $request->type;
                $data->status = 1;

                if ($request->type=='FAQS') {
                    $data->question  = $request->question;
                    $data->answer = $request->answer ;
                }

                // if (($request->type=='DOCUMENT' || $request->type=='VIDEO')  && $request->images) {
                //     foreach ($request->images as $image_key => $image) {
                //         $dir = public_path('client_documents/');
                //         if (!file_exists($dir)) {
                //             mkdir($dir, 0777, true);
                //         }
                //         $data->title = $request->title;
                //         $data->description = $request->description;
                //         $data->file_size = $image->getSize();
                //         $image_input['imagename'] = 'KB-'.time().'-'.$image->getClientOriginalName();
                //         $destinationPath = $dir.'CLIENT_'.$request->user_id;
                //         $image->move($destinationPath, $image_input['imagename']);
                //         $data->file = 'client_documents/CLIENT_'.$request->user_id.'/'.$image_input['imagename'];
                //         $data->file_ext = $image->getClientOriginalExtension();
                //     }
                // }

                if (($request->type=='DOCUMENT' || $request->type=='VIDEO')  && $request->images) {
                    foreach ($request->images as $image_key => $image) {
                        $dir = public_path('KB/');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0777, true);
                        }
                        $data->title = $request->title;
                        $data->description = $request->description;
                        $data->file_size = $image->getSize();
                        $image_input['imagename'] = time().'-'.$image->getClientOriginalName();
                        $destinationPath = $dir;
                        $image->move($destinationPath, $image_input['imagename']);
                        $data->file = 'KB/'.$image_input['imagename'];
                        $data->file_ext = $image->getClientOriginalExtension();
                    }
                }

                $data->title = $request->title ? $request->title : null;
                $data->description = $request->description ? $request->description : null;
                $data->embedded_link = $request->embedded_link ? $request->embedded_link : null;
                $data->save();

                return redirect('kb_show')->with('success','Data added successfully');

            } catch (\Exception $e) {
                echo "$e"; exit;
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
    public function edit(string $id)
    {
        // dd($user_id);
        if(Auth::user()->role_type==1) {
            $data = KnowledgeBase::where(['id'=>$id])->first();
            // $user = User::where('id',$user_id)->first();
            return view('admin.kb.edit', compact('data'));
        }else{
            return redirect('/home');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            $data = KnowledgeBase::where(['id'=>$id])->first();
            get_lastvalued_logged("knowledge_bases",$data);
            $data->type = 'FAQS';
            $data->status = 1;
            $data->question  = $request->question;
            $data->answer = $request->answer;
            $data->save();

            return redirect('kb_show')->with('success','Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        // dd($request->toArray(),$id);
        if(Auth::user()->role_type==1){
            if($request->has('ajax_request')){
                $data = KnowledgeBase::where('id', $id)->first();
                get_lastvalued_logged("knowledge_bases", $data);
                $image_path = public_path().'/'.$data->file;  // Value is not URL but directory file path
                // dd($image_path);
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                $data->delete();
                return "success";
            }else{
                $data = KnowledgeBase::where('id', $id)->first();
                get_lastvalued_logged("knowledge_bases", $data);
                $data->delete();
                return redirect('kb_show')->with('success', 'Data deleted successfully');
            }
        }
        else{
            return redirect('/home');
        }
    }

    public function kb_client_list()
    {
        if(Auth::user()->role_type==1) {
            $users = User::where('role_type', 2)->with('user_details', 'user_people')->get();
            return view('admin.kb.client_list', compact('users'));
        }
    }
}
