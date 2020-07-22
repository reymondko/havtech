<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Users;
use App\Models\Events;
use App\Models\EventRegistrations;

use Illuminate\Support\Facades\Hash;
use App\Imports\UsersImport;
use App\Models\CustomerType;

use Maatwebsite\Excel\Facades\Excel;

use DB;
use Session;
use Extension;

class EventRegistrationsController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $events =Events::orderBy('event_name')->get();
      # DB::enableQueryLog();
        $registered = DB::table('event_registrations')
        ->leftJoin('events','event_registrations.event_id','events.id')
        ->leftJoin(
            DB::raw("(SELECT user_logs.users_id, max(date_add(user_logs.created_at, interval -5 hour)) as created_at from user_logs group by user_logs.users_id) as logs"), 'logs.users_id', '=', 'event_registrations.user_id'
            )
        ->leftJoin(
            DB::raw("(SELECT es.id, es.start_date,registration_id,es.title FROM custom_event_schedule 
            LEFT JOIN event_schedule es ON event_schedule_id = es.id)as sched"), 'sched.registration_id', '=', 'event_registrations.id'
            )
        ->where('event_registrations.completed',1)
        ->where('sched.id','!=','')
        ->where('events.id','!=','')
        #->groupby('event_registrations.id')
        ->get(['event_registrations.*','events.event_name','sched.id as sched_id','sched.title as sched_title', 'sched.start_date','logs.created_at as last_login']);
            #$query = DB::getQueryLog();
            #print_r($query);die();

        return view('layouts/registrations')->with('registered', $registered)
        ->with('events',$events);

    }
}
