<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use DB;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     // dd($request->toArray());

    //     if(Auth::user()->role_type==1) {
    //         if($request->str_search && $request->str_search != '' )
    //             {
    //                 $searchvalue = preg_replace('/\s*,\s*/', ',', $request->str_search);
    //                 $skl_arr  =  explode(',',$searchvalue);
    //                 $data = Community::whereRaw('FIND_IN_SET("'.$skl_arr[0].'", keywords)');

    //                 for ($i=1; $i < count($skl_arr) ; $i++) {
    //                     $data->orwhereRaw('FIND_IN_SET("'.$skl_arr[$i].'", keywords)');
    //                 }
    //                 $users=$data->get();
    //             }
    //             else{
    //                 $users = Community::get();
    //                 $searchvalue=null;
    //             }

    //         return view('admin.community.show',compact('users' ,'searchvalue'));
    //     }else{
    //         // dd($request->toArray());
    //             if($request->str_search && $request->str_search != '' && (!$request->has('location_search') || $request->location_search==null))
    //             {

    //                 $keyword_value = preg_replace('/\s*,\s*/', ',', $request->str_search);
    //                 $skl_arr  =  explode(',',$keyword_value);
    //                 $data = Community::where('email','!=',Auth::user()->email)->whereRaw('FIND_IN_SET("'.$skl_arr[0].'", keywords)');
    //                 // $user_locations = Community::where('email','!=',Auth::user()->email)->pluck('location')->toArray();
    //                 $user_locations = Community::where('email', '!=', Auth::user()->email)->whereNotNull('location')->distinct()->pluck('location')->toArray();
    //                 $location_value = null;

    //                 for ($i=1; $i < count($skl_arr) ; $i++) {
    //                     $data->orwhereRaw('FIND_IN_SET("'.$skl_arr[$i].'", keywords)')->where('email','!=',Auth::user()->email);
    //                 }
    //                 $users=$data->get();
    //             }else if($request->str_search && $request->str_search != '' && $request->has('location_search') && $request->location_search != '' )
    //             {
    //                 $keyword_value = preg_replace('/\s*,\s*/', ',', $request->str_search);
    //                 $skl_arr  =  explode(',',$keyword_value);
    //                 $location_value = $request->location_search;

    //                 if($location_value == 'All'){
    //                     $data = Community::where('email', '!=', Auth::user()->email)->whereRaw('FIND_IN_SET("' . $skl_arr[0] . '", keywords)');
    //                 }else{
    //                     $data = Community::where('email', '!=', Auth::user()->email)->where('location', $location_value)->whereRaw('FIND_IN_SET("' . $skl_arr[0] . '", keywords)');
    //                 }
    //                 // $user_locations = Community::where('email','!=',Auth::user()->email)->pluck('location')->toArray();
    //                 $user_locations = Community::where('email', '!=', Auth::user()->email)->whereNotNull('location')->distinct()->pluck('location')->toArray();

    //                 for ($i=1; $i < count($skl_arr) ; $i++) {
    //                     $data->orwhereRaw('FIND_IN_SET("'.$skl_arr[$i].'", keywords)')->where('email','!=',Auth::user()->email);
    //                 }
    //                 $users=$data->get();
    //             }else if($request->location_search && $request->location_search != '' && (!$request->has('str_search') || $request->str_search==null) ){

    //                 $location_value = $request->location_search;
    //                 if($location_value == 'All'){
    //                     $users = Community::where('email','!=',Auth::user()->email)->get();
    //                 }else{
    //                     $users = Community::where('email','!=',Auth::user()->email)->where('location',$request->location_search)->get();
    //                 }

    //                 // $user_locations = Community::where('email','!=',Auth::user()->email)->pluck('location')->toArray();
    //                 $user_locations = Community::where('email', '!=', Auth::user()->email)->whereNotNull('location')->distinct()->pluck('location')->toArray();
    //                 $keyword_value =null;

    //             }else{
    //                 $users = Community::where('email','!=',Auth::user()->email)->get();
    //                 // $user_locations = Community::where('email','!=',Auth::user()->email)->pluck('location')->toArray();
    //                 $user_locations = Community::where('email', '!=', Auth::user()->email)->whereNotNull('location')->distinct()->pluck('location')->toArray();
    //                 $keyword_value =null;
    //                 $location_value = null;
    //             }
    //             //  dd($location_value);
    //         return view('client.community.show',compact('users','keyword_value','user_locations','location_value'));
    //     }
    // }

    public function index(Request $request)
    {
        $user = Auth::user();
        $roleType = $user->role_type;

        // Build the base query
        $query = Community::query();

        if ($roleType != 1) {
            $query->where('email', '!=', $user->email);
        }

        // Execute the query
        $users = $query->get();

        // For non-admin users, get distinct user locations
        $userLocations = [];
        if ($roleType != 1) {
            $userLocations = Community::where('email', '!=', $user->email)
                ->whereNotNull('location')
                ->distinct()
                ->pluck('location')
                ->toArray();
        }

        return response()->json([
            'users' => $users,
            'user_locations' => $userLocations,
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.community.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => ['required'],
                'email' => ['required', 'unique:community_users,email,'],
                // 'specialist' => ['required'],
                'keywords' => ['required'],
            ]);

            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }

            $community_user = new Community;
            $community_user->name  = $request->name;
            $community_user->email = $request->email;
            $community_user->status = $request->status;
            $community_user->specialist = $request->specialist ?? '';
            $community_user->location = $request->location;
            $community_user->industry = $request->industry;
            $community_user->keywords  = preg_replace('/\s*,\s*/', ',', $request->keywords);

            if ($request->logo != null) {
                $file = $request->file('logo')->getClientOriginalName();
                $image_input['imagename']  = time() . '-' . $file;
                $destinationPath = public_path('community_user_logos/');
                $request->logo->move($destinationPath, $image_input['imagename']);
                $community_user->logo = 'community_user_logos' . '/' . $image_input['imagename'];
            }
            $community_user->save();

            return redirect('communities')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            // echo "$e"; exit;
            return redirect()->back()->withInput()->with('danger', 'Sorry could not process.');
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
        $community_user = Community::where('id', $id)->first();
        return view('admin.community.edit', compact('community_user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => ['required'],
                'email' => ['required', 'unique:community_users,email,' . $id],
                // 'specialist' => ['required'],
                'keywords' => ['required'],
            ]);

            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }

            $community_user = Community::where('id', $id)->first();
            $community_user->name  = $request->name;
            $community_user->email = $request->email;
            $community_user->status = $request->status;
            $community_user->specialist = $request->specialist ?? '';
            $community_user->location = $request->location;
            $community_user->industry = $request->industry;
            $community_user->keywords  = preg_replace('/\s*,\s*/', ',', $request->keywords);

            if ($request->logo != null) {
                $file = $request->file('logo')->getClientOriginalName();
                $image_input['imagename']  = time() . '-' . $file;
                $destinationPath = public_path('community_user_logos/');
                $request->logo->move($destinationPath, $image_input['imagename']);
                $community_user->logo = 'community_user_logos/' . $image_input['imagename'];
            }
            $community_user->save();

            return redirect('communities')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            //   echo "$e"; exit;
            return redirect()->back()->withInput()->with('danger', 'Sorry could not process.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Community::find($id);
        $user->delete();

        return redirect('communities')->with('success', 'User deleted successfully');
    }
}
