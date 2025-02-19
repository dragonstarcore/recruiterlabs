<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role_type==1) {
            $tickets = Ticket::where('user_id',Auth::user()->id)->get();
            return response()->json(['tickets'=>$tickets],200);
        } elseif (Auth::user()->role_type==2) {
            $tickets = Ticket::where('user_id',Auth::user()->id)->get();
            return response()->json(['tickets'=>$tickets],200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role_type==1) {
            return view('admin.tickets.create');
        } elseif (Auth::user()->role_type==2) {
            return view('client.tickets.create');
        }
       
    }

        public function search(Request $request)
        {
            // Get the search query from the request
            $searchTitle = $request->search_title; // assuming 'title' is the search term you are sending from the frontend

            // Check if the authenticated user's role is '1' (admin or superuser)
            if (Auth::user()->role_type == 1) {
                $query = Ticket::query();

                // If there's a search query, filter tickets by title
                if ($searchTitle) {
                    $query->where('title', 'like', '%' . $searchTitle . '%');
                }

                $tickets = $query->with('user')->get();

                return response()->json(['tickets' => $tickets], 200);
            } 

            // Check if the authenticated user's role is '2' (user)
            elseif (Auth::user()->role_type == 2) {
                $query = Ticket::where('user_id', Auth::user()->id);

                // If there's a search query, filter tickets by title
                if ($searchTitle) {
                    $query->where('title', 'like', '%' . $searchTitle . '%');
                }

                $tickets = $query->get();

                return response()->json(['tickets' => $tickets], 200);
            }
        }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
                $validator = \Validator::make($request->all(), [
                    'title' => ['required'],
                    'description' => ['required'],
                    'message' => ['required'],
                    // 'status' => ['required'],
                    'priority' => ['required'],
                ]);

                if($validator->fails()){ 
                    return back()->withInput()->withErrors($validator);
                }

                $ticket = new Ticket;
                $ticket->user_id = Auth::user()->id;
                $ticket->team = $request->team;
                $ticket->title = $request->title;
                $ticket->description  = $request->description;
                $ticket->status = $request->status ? $request->status : 1 ;
                $ticket->priority = $request->priority ? $request->priority : 'Low';
                if ($request->message!=null) {
                    $message = [];
                    $message[] = array(
                        'user'=>Auth::user()->id,
                        'datetime'=>date('Y-m-d H:i:s'),
                        'message'=>$request->message ? $request->message : "",
                    );
                    $ticket->message = json_encode($message);
                }
                $ticket->save();

                return response()->json(['ticket'=>$ticket],200);

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
        if (Auth::user()->role_type==1) {
            $ticket = Ticket::where('id',$id)->first();
            $history = [];
            $messages = json_decode($ticket->message, true);
            foreach($messages as $message){
                $user = User::where('id',$message['user'])->first();
                array_push($history,array('user' =>  ucfirst($user->name),'message'=>$message['message']));
            }
            // dd($history);
            return response()->json(['ticket'=>$ticket,'history'=>$history]);
        } elseif (Auth::user()->role_type==2) {
            $ticket = Ticket::where('id',$id)->first();
            $history = [];
            $messages = json_decode($ticket->message, true);
            foreach($messages as $message){
                $user = User::where('id',$message['user'])->first();
                array_push($history,array('user' => ucfirst($user->name),'message'=>$message['message']));
            }
            return response()->json(['ticket'=>$ticket,'history'=>$history]);
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
                // 'title' => ['required'],
                // 'description' => ['required'],
                'message' => ['required'],
                // 'status' => ['required'],
                // 'priority' => ['required'],
            ]);

            if($validator->fails()){ 
                return back()->withInput()->withErrors($validator);
            }

            $ticket = Ticket::where('id',$id)->first();
            get_lastvalued_logged("user_tickets",$ticket);
            $ticket->title = $request->title;
            $ticket->description  = $request->description;
            $ticket->status = $request->status ? $request->status : 1 ;
            $ticket->priority = $request->priority ? $request->priority : $ticket->priority;
            $old_messages = json_decode($ticket->message, true);
            if ($request->message!=null) {
                $new_message = [];
                $new_message[] = array(
                    'user'=>Auth::user()->id,
                    'datetime'=>date('Y-m-d H:i:s'),
                    'message'=>$request->message ? $request->message : "",
                );

                if ($old_messages!=null) {
                    $updated_messages = array_merge($old_messages, $new_message);
                } else {
                    $updated_messages = $new_message;
                }
                $ticket->message = json_encode($updated_messages);
            }
            $ticket->save();
            return response()->json(['ticket'=>$ticket],200);

        } catch (\Exception $e) {
            // echo "$e"; exit;
            return response()->json([],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Ticket::find($id);
        $user->delete();
        return response()->json(['',200]);
    }
}
