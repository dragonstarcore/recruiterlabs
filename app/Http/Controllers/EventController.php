<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     //for all events
     public function allevents(Request $request)
    {
        if($request->ajax()) {
            $data = Event::where('user_id',Auth::user()->id)->get(['id', 'title', 'location', 'start', 'end']);

            return response()->json($data);
        }
        return response()->json(['calendar'=>$data],200);
    }

    //for upcoming events
    public function index(Request $request,$id=null)
    {
        if(Auth::user()->role_type==1) {
            $user_id = $id ;
        }else{
            $user_id = Auth::user()->id ;
        }

        $today = date('Y-m-d');
        
        // Date filter removed dor now
         
       
            $data = Event::where('user_id',$user_id)->get(['id', 'title', 'location', 'end','start']);
            return response()->json($data); 
    }
 
    public function add_update_delete(Request $request)
    {
        // dd($request->toArray());
        switch ($request->type) {
           case 'add':
              $event = Event::create([
                  'user_id' => Auth::user()->id,
                  'status' => 1,
                  'title' => $request->title,
                  'location' => $request->location,
                  'start' => $request->start,
                  'end' => $request->end ? $request->end : $request->start,
              ]);
                //    dd($event);
              return response()->json($event);
             break;
  
           case 'update':
            $old_event = Event::find($request->id);
                    get_lastvalued_logged("events",$old_event);
              $event = Event::find($request->id)->update([
                    'user_id' => Auth::user()->id,
                    'status' => 1,
                    'title' => $request->title,
                    'location' => $request->location,
                    'start' => $request->start,
                    'end' => $request->end ? $request->end : $request->start,
              ]);
              return response()->json($event);
             break;
  
           case 'delete':
              $event = Event::find($request->id)->delete();
  
              return response()->json($event);
             break;
             
           default:
             # ...
             break;
        }
    }

    public function client_events_list($user_id)
    {
        // dd($user_id);
        $user = User::where('id',$user_id)->first();
        $events = Event::where('user_id',$user_id)->get();

        return view('admin.events.show',compact('events','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        // dd($request->user_id,$id);
        $events = Event::where(['user_id'=>$request->user_id,'id'=>$id])->delete();
        return redirect()->back()->with('success','Event deleted successfully');
    }


}
