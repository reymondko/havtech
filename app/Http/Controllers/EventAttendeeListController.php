<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Events;
use App\Models\EventTypes;
use App\Models\Users;
use App\Models\EventAttendees;
use App\Models\CustomerType;
use App\Models\EventRegistrations;
use App\Models\CustomEventSchedule;
use App\Models\EventSchedule;

use App\Exports\AttendeeListExport;

use DB;
use DataTables;
use Response;

class EventAttendeeListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application Events.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAttendeeListPage($step,$total,$event_id)
    {
        $events = Events::where('id', $event_id)->first(); 
        $customertype=CustomerType::all();
        $users = DB::table('users')
        ->leftJoin('event_attendees' , function($q) use ($event_id)
        {
            $q->on('users.id', '=', 'event_attendees.user_id')
                ->where('event_attendees.event_id', '=', $event_id);
        })
        ->where('enabled',1)
        ->get(['users.id','users.first_name','users.last_name','event_attendees.id as inv_id','email_sent_approved']);
        $data = array(
            'step' => $step,
            'event_id' => $event_id
        );
        return view('layouts/events/create/eventattendeelist')->with('event',$events)->with('step',$step)->with('total',$total)->with('data', $data)->with('attendees',json_decode($users, true))
        ->with('customertype',$customertype);
    }

    public function editAttendeeListPage($step,$event_id)
    {
        $eventtypes=EventTypes::all();
        $customertype=CustomerType::all();
        //get all tabs 
        $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
        //$eventaccs=EventAccomodations::where('event_id', $event_id)->get();
        $event= Events::select('event_name')->where('id', $event_id)->first();
        $users = DB::table('users')
        ->leftJoin('event_attendees' , function($q) use ($event_id)
        {
            $q->on('users.id', '=', 'event_attendees.user_id')
                ->where('event_attendees.event_id', '=', $event_id);
        })
        ->where('enabled',1)
        ->get(['users.id','users.first_name','users.last_name','event_attendees.id as inv_id','email_sent_approved']);
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'event_name' => $event->event_name,
            //'eventaccs' => json_decode($eventaccs, true),
            'events' => $tabs
        );

        return view('layouts/events/edit/eventattendeelist')->with('data', $data)
        ->with('attendees',json_decode($users, true))->with('customertype',$customertype);
    }

    public function getAttendeeList(Request $request)
    {     
        $event_id=$request->event_id;
        $attendee=$request->search_attendee;
        $customertype=$request->customer_type;
        if(empty($customertype)){
            $customertype="";
        }
        //DB::enableQueryLog();
        $users = DB::table('users')
        ->leftJoin('event_attendees' , function($q) use ($event_id)
        {
            $q->on('users.id', '=', 'event_attendees.user_id')
                ->where('event_attendees.event_id', '=', $event_id);
        })->when( $customertype != '', function ($q) use ($customertype) {
            $q->where('users.customer_type', '=',  $customertype);
        })
        ->where(function ($query) use ($attendee,$customertype) {
            $query
            ->where('users.first_name', 'like', '%'.$attendee.'%')            
            ->orWhere('users.last_name', 'like', '%'.$attendee.'%');
        })
        ->where('enabled',1)
        ->orderBy('users.first_name', $request->filterby)
        ->get(['users.id','users.first_name','users.last_name','users.customer_type',
        'event_attendees.id as inv_id','email_sent_approved']);

        //dd(DB::getQueryLog());
        //$attendees = EventAttendees::select('user_id')->where('event_id', $request->event_id)->groupBy('user_id')->get();
        // dd(DB::getQueryLog());
        $data = array(
            'step' => $request->step,
            'event_id' => $request->event_id,
            'users' => json_decode($users, true)            
            //'attendees' => json_decode($attendees, true)
        );
        return $data;
    }

    public function inviteUser(Request $request)
    { 
        /* remove for now.. automated event registration
        check if event is special*/

        // UPDATE THIS HAS BEEN UPDATED TO SET IF AN EVENT IS GENERAL
        // OR LEARNING INSTITUTE THE ATTENDEE IS AUTOMATICALLY REGISTERED

        $event = Events::where('id',$request->event_id)->first();
        //if general event, attendee is automatically registered
        if($event->event_types==1 || $event->event_types==3 ){
            $user = Users::where('id',$request->userid)->first();
            //check if user already invite
            $checkRegistration = EventRegistrations::where('user_id',$request->userid)->where('event_id',$request->event_id)->first();
            if(empty($checkRegistration)){
                $registration = new EventRegistrations;
                $registration->user_id = $user->id;
                $registration->event_id = $request->event_id;
                $registration->first_name = $user->first_name ?? 'N/A';
                $registration->last_name = $user->last_name  ?? 'N/A';
                $registration->company = $user->company  ?? 'N/A';
                $registration->title = $user->title  ?? 'N/A';
                $registration->industry = $user->industry  ?? 'N/A';
                $registration->email = $user->email  ?? 'N/A';
                $registration->telephone = $user->telephone  ?? 'N/A';
                $registration->completed = 1;
                $registration->save();

                // Get Event Schedules
                $schedules = EventSchedule::where('event_id',$request->event_id)->get();
                foreach($schedules as $schedule){
                    $custom_event = new CustomEventSchedule;
                    $custom_event->user_id = $request->userid;
                    $custom_event->event_id = $request->event_id;
                    $custom_event->event_schedule_id = $schedule->id;
                    $custom_event->registration_id = $registration->id;
                    $custom_event->payment_id = null;
                    $custom_event->save();
                }
            }
        }

        $invite = new EventAttendees;
        $invite->user_id = $request->userid;
        $invite->event_id = $request->event_id;
        if($invite->save()){
            return Response::json(array('success' => true), 200);
        }
    }
    public function uninviteUser(Request $request)
    { 
        $uninvite = EventAttendees::where('event_id', $request->event_id)
        ->where('user_id', $request->userid)->delete();

        // Check if event is general or learning institute
        // Delete event registration and schedules if true
        $event = Events::where('id',$request->event_id)->first();
        if($event->event_types==1 || $event->event_types==3 ){
            $user = Users::where('id',$request->userid)->first();
            //check if user already invite
            $checkRegistration = EventRegistrations::where('user_id',$request->userid)->where('event_id',$request->event_id)->first();
            if($checkRegistration){
                // Get Event Schedules
                $schedules = CustomEventSchedule::where('event_id',$request->event_id)
                                        ->where('registration_id',$checkRegistration->id)
                                        ->get();
                if($schedules){
                    foreach($schedules as $schedule){
                        $schedule->delete();
                    }
                }
                $checkRegistration->delete();
            }
        }


        if($uninvite){
            return Response::json(array('success' => true), 200);
        }
    }

    public function attendeeExport(Request $request){
        //echo $event_id;
        $event_id =$request->event_id;
        $type ="xlsx";
        $events = Events::where('id', $event_id)->first(); 
        return Excel::download(new AttendeeListExport($event_id), $events->event_name." - Attendee List.". $type);
       // return Excel::(new AttendeeListExport($event_id))->download($events->event_name." - Attendee List.xlsx");

        //return (new AttendeeListExport($event_id))->download('invoices.xlsx');

        //return back();
    }

    public function attendeeExport2(Request $request,$step,$event_id){
        //$event_id =$request->event_id;
        $type ="xlsx";
        $events = Events::where('id', $event_id)->first(); 
        return Excel::download(new AttendeeListExport($event_id), $events->event_name." - Attendee List.". $type);
       // return Excel::(new AttendeeListExport($event_id))->download($events->event_name." - Attendee List.xlsx");

        //return (new AttendeeListExport($event_id))->download('invoices.xlsx');
        
        //return back();
    }

    function updateAttendees(Request $request)
    {
        if($request->todo == 2){
            foreach($request->attendee as $att){
                $ea = EventAttendees::where('event_id', $request->eventid)               
                ->where('user_id', $att)->first();
                if($ea){ }
                else{
                    $invite = new EventAttendees;
                    $invite->user_id = $att;
                    $invite->event_id = $request->eventid;
                    if($invite->save()){
                       
                    } 
                }
            }
            return Response::json(array('success' => true,'message' => 'All have been selected.'), 200);
        }
        elseif($request->todo == 3){
            $uninvite = EventAttendees::where('event_id', $request->eventid)->delete();
            if($uninvite){
                return Response::json(array('success' => true,'message' => 'All have been removed.'), 200);
            }
        }
        elseif($request->todo == 1){
            $eas = EventAttendees::where('event_id', $request->eventid)->where('email_sent_approved',"!=",'1')->get();
            $events = Events::where('id', $request->eventid)->first(); 
            
            foreach($eas as $ea){
                $ea->email_sent_approved = 1;
                if($events->event_types==2){
                    $ea->email_sent = 0 ; // this will skip sending invites
                }
                else{
                    $ea->email_sent = 1 ; // this will skip sending invites
                }
                  $ea->save();
            }
            /*foreach($request->attendee as $att){
                $ea = EventAttendees::where('event_id', $request->eventid)               
                ->where('user_id', $att)->first();
                if($ea){ 
                    $ea->email_sent_approved = 1;
                    //$ea->email_sent = 0;
                    $ea->save();
                }
                else{
                    $invite = new EventAttendees;
                    $invite->user_id = $att;
                    $invite->email_sent_approved = 1;
                    $invite->email_sent = 0;
                    $invite->event_id = $request->eventid;
                    if($invite->save()){
                       
                    } 
                }
            }*/

            return Response::json(array('success' => true,'message' => 'Invites Sent.'), 200);
        }
        else{




        }
    }
    
    public function checkallAttendees(Request $request)
    { 
        print_r($request);die();
        $uninvite = EventAttendees::where('event_id', $request->event_id)->delete();
        if($uninvite){
            return Response::json(array('success' => true), 200);
        }
    }

    public function removeAttendees(Request $request)
    { 
        print_r($request);die();
        $uninvite = EventAttendees::where('event_id', $request->event_id)->delete();
        if($uninvite){
            return Response::json(array('success' => true), 200);
        }
    }
    
    
}