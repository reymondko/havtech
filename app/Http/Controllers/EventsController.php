<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Events;
use App\Models\EventTypes;
use App\Models\EventSchedule;
use App\Models\EventAccomodations;
use App\Models\EventDining;
use App\Models\EventTransportation;
use App\Models\EventAttendees;
use App\Models\EventMaps;
use App\Models\EventTravelHosts;
use App\Models\EventFaqs;
use App\Models\EventPhotos;
use App\Models\EventDateTime;
use Intervention\Image\Facades\Image;

use DB;
use DataTables;
use Response;

class EventsController extends Controller
{
    private $photos_path;
    private $banner_path;
 
    public function __construct()
    {
        $this->photos_path = public_path('/uploads/maps');
        $this->banner_path = public_path('/uploads');
    }
    /**
     * Show the application Events.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // if (Gate::allows('user-only', auth()->user()) || Gate::allows('company-only', auth()->user())) {
        //DB::enableQueryLog();
        $events=Events::join('event_type', 'events.event_types', '=', 'event_type.id')
                ->leftjoin('event_schedule','event_id','events.id')
                ->groupby('events.id')
                ->orderby(DB::raw('DATE_FORMAT(event_schedule.start_date, "%y-%m-%d")'),'desc')
                ->orderby(DB::raw('DATE_FORMAT(event_schedule.start_date, "%H:%i-%s")'),'asc')
                ->select(DB::raw('DATE_FORMAT(event_schedule.start_date, "%b %e, %Y at %l:%i %p") as start_date'),'event_schedule.start_date as sd','events.id','event_name','event_schedule.end_date','event_type.description as event_type','events.created_at','visibility_web','visibility_app') //'DATE_FORMAT(start_date, "%b %e, %Y at %l:%i %p") as start_date'
                ->get();

         //       dd(DB::getQueryLog());
        $eventtypes=EventTypes::all();  

                $data = array(
                    'events' => $events,                  
                    'eventtypes' => $eventtypes
                );
        return view('layouts/events/events')->with('data', $data);
        //}elseif(Gate::allows('tpl-only', auth()->user())){
         //   return redirect('/thirdparty/Events');
        //}

    }

    public function specialEvents(){
        $events=Events::join('event_type', 'events.event_types', '=', 'event_type.id')
                ->where('event_types','=',2)
                ->leftjoin('event_schedule','event_id','events.id')
                ->groupby('events.id')
                ->orderby(DB::raw('DATE_FORMAT(event_schedule.start_date, "%y-%m-%d")'),'desc')
                ->orderby(DB::raw('DATE_FORMAT(event_schedule.start_date, "%H:%i-%s")'),'asc')
                ->select(DB::raw('DATE_FORMAT(event_schedule.start_date, "%b %e, %Y at %l:%i %p") as start_date'),'event_schedule.start_date as sd','events.id','event_name','event_schedule.end_date','event_type.description as event_type','events.created_at','visibility_web','visibility_app') //'DATE_FORMAT(start_date, "%b %e, %Y at %l:%i %p") as start_date'
                ->get();

        $eventtypes=EventTypes::all();  
                $data = array(
                    'events' => $events
                    //,'eventtypes' => $eventtypes
                );
        return view('layouts/events/specialevents')->with('data', $data);
    }

    public function generalEvents(){
        $events=Events::join('event_type', 'events.event_types', '=', 'event_type.id')
                ->where('event_types','=',1)
                ->leftjoin('event_schedule','event_id','events.id')
                ->groupby('events.id')
                ->orderby(DB::raw('DATE_FORMAT(event_schedule.start_date, "%y-%m-%d")'),'desc')
                ->orderby(DB::raw('DATE_FORMAT(event_schedule.start_date, "%H:%i-%s")'),'asc')
                ->select(DB::raw('DATE_FORMAT(event_schedule.start_date, "%b %e, %Y at %l:%i %p") as start_date'),'event_schedule.start_date as sd','events.id','event_name','event_schedule.end_date','event_type.description as event_type','events.created_at','visibility_web','visibility_app') //'DATE_FORMAT(start_date, "%b %e, %Y at %l:%i %p") as start_date'
                ->get();

        $eventtypes=EventTypes::all();  
                $data = array(
                    'events' => $events
                    //,'eventtypes' => $eventtypes
                );
        return view('layouts/events/generalevents')->with('data', $data);
    }

    public function upcomingEvents(){
        $events=Events::join('event_type', 'events.event_types', '=', 'event_type.id')
                #->where('event_types','=',3)
                
                ->leftjoin('event_schedule','event_id','events.id')
                ->where('event_types','!=',"4")
                ->groupby('events.id')
                ->orderby(DB::raw('DATE_FORMAT(event_schedule.start_date, "%y-%m-%d")'),'desc')
                ->orderby(DB::raw('DATE_FORMAT(event_schedule.start_date, "%H:%i-%s")'),'asc')
                ->select(DB::raw('DATE_FORMAT(event_schedule.start_date, "%b %e, %Y at %l:%i %p") as start_date'),'event_schedule.start_date as sd','events.id','event_name','event_schedule.end_date','event_type.description as event_type','events.created_at','visibility_web','visibility_app') //'DATE_FORMAT(start_date, "%b %e, %Y at %l:%i %p") as start_date'
                ->get();

        $eventtypes=EventTypes::all();  
                $data = array(
                    'events' => $events
                    //,'eventtypes' => $eventtypes
                );
        return view('layouts/events/upcomingevents')->with('data', $data);
    }
    public function archiveEvents(){
        $events=Events::join('event_type', 'events.event_types', '=', 'event_type.id')
                ->where('event_types','=',4)
                ->leftjoin('event_schedule','event_id','events.id')
                ->groupby('events.id')
                ->orderby(DB::raw('DATE_FORMAT(event_schedule.start_date, "%y-%m-%d")'),'desc')
                ->orderby(DB::raw('DATE_FORMAT(event_schedule.start_date, "%H:%i-%s")'),'asc')
                ->select(DB::raw('DATE_FORMAT(event_schedule.start_date, "%b %e, %Y at %l:%i %p") as start_date'),'event_schedule.start_date as sd','events.id','event_name','event_schedule.end_date','event_type.description as event_type','events.created_at','visibility_web','visibility_app') //'DATE_FORMAT(start_date, "%b %e, %Y at %l:%i %p") as start_date'
                ->get();

        $eventtypes=EventTypes::all();  
                $data = array(
                    'events' => $events
                    //,'eventtypes' => $eventtypes
                );
        return view('layouts/events/archiveevents')->with('data', $data);
    }
    public function LIEvents(){
        $events=Events::join('event_type', 'events.event_types', '=', 'event_type.id')
                ->where('event_types','=',3)
                ->leftjoin('event_schedule','event_id','events.id')
                ->groupby('events.id')
                ->orderby(DB::raw('DATE_FORMAT(event_schedule.start_date, "%y-%m-%d")'),'desc')
                ->orderby(DB::raw('DATE_FORMAT(event_schedule.start_date, "%H:%i-%s")'),'asc')
                ->select(DB::raw('DATE_FORMAT(event_schedule.start_date, "%b %e, %Y at %l:%i %p") as start_date'),'event_schedule.start_date as sd','events.id','event_name','event_schedule.end_date','event_type.description as event_type','events.created_at','visibility_web','visibility_app') //'DATE_FORMAT(start_date, "%b %e, %Y at %l:%i %p") as start_date'
                ->get();

        $eventtypes=EventTypes::all();  
                $data = array(
                    'events' => $events
                    //,'eventtypes' => $eventtypes
                );
        return view('layouts/events/lievents')->with('data', $data);
    }
    

    public function createEventOverviewPage($step,$total,$event_id)
    {
        $eventtypes=EventTypes::all();  
        $eventoverview=Events::where('id', $event_id)->get();
       //DB::enableQueryLog(); 
        $datez=EventDateTime::where('event_id', $event_id)->get();
        //dd(DB::getQueryLog());
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'eventtypes' => $eventtypes,
            'eventoverview' => $eventoverview
        );
        return view('layouts/events/create/eventoverview')->with('eventtypes', $eventtypes)->with('data', $data)->with('event_id',$event_id)->with('datez',$datez);
    }
    
    public function fetchTabs($event_id){
        $events= Events::select('schedule_image as schedule',
        'accomodations_image as accomodations'
        ,'dining_image as dining'
        ,'transportation_image as transportation'
        ,'map as map'
        ,'travelhost as travel-host')->where('id', $event_id)->get();
        $arr=$events->toArray();
        //remove for now
        $arr[0]['attendee-list']=1;
        $arr[0]['photos']=1;
        //remove null or blank (forms that were not checked)
        $arr = array_filter($arr[0], 'strlen');
        $tabs[]="Overview";
        foreach($arr as $k=>$v){
            $tabs[]=$k;
        }
        return $tabs;
    }
    //edit part
    public function editEventOverview($step,$event_id)
    {
        $eventtypes=EventTypes::all();
        //get all tabs 
        $tabs = $this->fetchTabs($event_id);
        $eventoverview=Events::where('id', $event_id)->get();
        $datez=EventDateTime::where('event_id', $event_id)->get();
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'eventtypes' => $eventtypes,
            'eventoverview' => $eventoverview,
            'events' => $tabs
        );
        
        return view('layouts/events/edit/eventoverview')->with('data', $data)->with('datez',$datez);
    }
    
    //get all forms that are checked from the event overview form and check which form is next
    public function getNextstep($step,$event_id){
        $events= Events::select('schedule_image as schedule',
        'accomodations_image as accomodations'
        ,'dining_image as dining'
        ,'transportation_image as transportation'
        ,'map as map'
        ,'travelhost as travel-host')->where('id', $event_id)->get();
        $arr=$events->toArray();
        //remove for now
        $arr[0]['attendee-list']=1;
        $arr[0]['photos']=1;
        
        //remove null or blank (forms that were not checked)
        $arr = array_filter($arr[0], 'strlen');
        $x=2;
        $total=count($arr) + 1;
        $nextstep=$step++;
        foreach($arr as $key=>$value){
            if( $step == $x){
               //echo $step. " ".$event_id."  == ".$x."    "  .$key. " ". $total;
               return Response::json(array('success' => true, 'event_id' => $event_id, 'step' => $step, 'total' => $total, 'url' => $key), 200);
            }
            $x++;
        }
        return false;
    }

    //get all forms that are checked from the event overview form and check which form is previous
    public function getPrevStep(Request $request){
        $event_id=$request->event_id;
        $step=$request->step;
        $events= Events::select('schedule_image as schedule',
        'accomodations_image as accomodations'
        ,'dining_image as dining'
        ,'transportation_image as transportation'
        ,'map as map'
        ,'travelhost as travel-host')->where('id', $event_id)->get();
        $arr=$events->toArray();
        $arr[0]['attendee-list']=1;
        $arr[0]['photos']=1;
        ;
        //remove null or blank (forms that were not checked)
        $arr = array_filter($arr[0], 'strlen');
        $x=2;
        $total=count($arr) + 1;
        $prevstep=$step--;
        if($step==1){
            return Response::json(array('success' => true, 'event_id' => $event_id, 'step' => $step, 'total' => $total, 'url' => 'overview'), 200);
        }
        foreach($arr as $key=>$value){
            if( $step == $x){
               //echo $step. " ".$event_id."  == ".$x."    "  .$key. " ". $total;
               return Response::json(array('success' => true, 'event_id' => $event_id, 'step' => $step, 'total' => $total, 'url' => $key), 200);
            }
            $x++;
        }
        return false;
    }

    //create event overview
    public function addEventOverview(Request $request)
    {        
        if($request->event_id==0){
            $events = new Events;
            //convert start date and end date to Y-m-d H:i:s
            $start_date=date("Y-m-d H:i:s",strtotime($request->start_dateonly));
            $end_date=date("Y-m-d H:i:s",strtotime($request->end_dateonly));        
            //return $start_date; die();          
                
            $events->start_date = $start_date;
            $events->end_date = $end_date;
            $events->event_name = $request->event_name;
            $events->event_types = $request->event_type;
            $events->event_status = $request->status;  
            $events->description = $request->description; 
            $events->custom_calendar_message = $request->custom_calendar_message;
            $events->visibility_web = $request->visibility_web;
            $events->visibility_app = $request->visibility_app;
            $events->register_button = $request->register_button;
            #$events->is_register_url = $request->is_register_url;
            #$events->register_url = $request->register_url;
            $events->cost_per_person = $request->cost_per_person;
            $events->number_of_person = $request->number_of_person;
            #$events->directions_button = $request->directions_button;
            $events->directions_url = $request->directions_url; 
            $events->website_url = $request->website_url;
            $events->schedule_image = $request->schedule_image;
            $events->accomodations_image = $request->accommodations_image;
            $events->dining_image = $request->dining_image;
            $events->transportation_image = $request->transportation_image;
            $events->map = $request->map;
            $events->travelhost = $request->travelhost;
            $events->event_host = $request->event_host;
            $events->event_host_title = $request->event_host_title;
            $events->event_host_description = $request->event_host_description;
            $events->event_host_email = $request->event_host_email;
            $events->timezone_offset = $request->timezone_offset;
            
            //upload overview image
            if($request->hasFile('banner_image')) {
                $file = $request->file('banner_image');

                $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();

                $image['filePath'] = $name;
                //resize if more than 1300
                $width = Image::make($file)->width();
                if($width>1300){
                    Image::make($file)
                    ->resize(1300, null, function ($constraints) {
                        $constraints->aspectRatio();
                    })->save($this->banner_path.'/' . $name);
                }
                else{
                    $file->move($this->banner_path.'/', $name);
                }
                $events->image= '/uploads/'. $name; //public_path().
                
            }

            //check if there is overview_file
            if($request->hasFile('overview_file')) { 
                $file = $request->file('overview_file');
                $overview_file = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                $image['filePath'] = $overview_file;
                $file->move(public_path().'/uploads/', $overview_file);
                $events->overview_file = '/uploads/'. $overview_file; //public_path().
            }

            if($events->save()){
                if(!empty($request->schedule_image)){
                    $populate= new EventSchedule;
                    $populate->event_id = $events->id;
                    $populate->save();
                }
                if(!empty($request->accommodations_image)){
                    $populate= new EventAccomodations;
                    $populate->event_id=$events->id;
                    $populate->save();
                }
                if(!empty($request->dining_image)){
                    $populate= new EventDining;
                    $populate->event_id=$events->id;
                    $populate->save();
                }
                if(!empty($request->transportation_image)){
                    $populate= new EventTransportation;
                    $populate->event_id=$events->id;
                    $populate->save();
                }
                if(!empty($request->travelhost)){
                    $populate= new EventTravelHosts;
                    $populate->event_id=$events->id;
                    $populate->save();
                    $populate= new EventFaqs;
                    $populate->event_id=$events->id;
                    $populate->save();
                }
                if(!empty($request->dates)){
                    $d=0;
                    foreach($request->dates as $date){
                        $eventdt = new EventDateTime;    
                        $eventdt->event_date = date("Y-m-d",strtotime($date));
                        $eventdt->start_time = date("H:i:s",strtotime($request->start_time[$d]));
                        $eventdt->end_time = date("H:i:s",strtotime($request->end_time[$d]));
                        $eventdt->event_id = $events->id;
                        $eventdt->save();
                        $d++;
                    }
                }
                return $this->getNextstep(1,$events->id);
                return Response::json(array('success' => true, 'event_id' => $events->id), 200);
            }
            else{
                return "failed";
            }        
        }
        else{
            //convert start date and end date to Y-m-d H:i:s
            $start_date=date("Y-m-d H:i:s",strtotime($request->start_dateonly));
            $end_date=date("Y-m-d H:i:s",strtotime($request->end_dateonly));        
            
            #'is_register_url' => $request->is_register_url,
            #'register_url' => $request->register_url,
            #'directions_button' => $request->directions_button,
            $values=array('start_date' => $start_date,
            'end_date' => $end_date,
            'event_name' => $request->event_name,
            'event_types' => $request->event_type,
            'event_status' => $request->status,        
            'register_button' => $request->register_button,
            'description' => $request->description,
            'custom_calendar_message' => $request->custom_calendar_message,
            'directions_url' => $request->directions_url,
            'website_url' => $request->website_url,
            'cost_per_person' => $request->cost_per_person,
            'number_of_person' => $request->number_of_person,
            'schedule_image' => $request->schedule_image,
            'accomodations_image' => $request->accommodations_image,
            'dining_image' => $request->dining_image,
            'transportation_image' => $request->transportation_image,
            'map' => $request->map,
            'travelhost' => $request->travelhost,
            'event_host' => $request->event_host,
            'event_host_title' => $request->event_host_title,
            'event_host_description' => $request->event_host_description,
            'event_host_email' => $request->event_host_email,
            'visibility_web' => $request->visibility_web,
            'visibility_app' => $request->visibility_app);
            //upload overview image
            if($request->hasFile('banner_image')) {
                $file = $request->file('banner_image');
                $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();
                $image['filePath'] = $name;
                //resize if more than 1300
                $width = Image::make($file)->width();
                if($width>1300){
                    Image::make($file)
                    ->resize(1300, null, function ($constraints) {
                        $constraints->aspectRatio();
                    })->save($this->banner_path.'/' . $name);
                }
                else{
                    $file->move($this->banner_path.'/', $name);
                }
                
                //$events->image= '/uploads/'. $name; //public_path().
                $values['image'] = '/uploads/'. $name;
            }
            //check if there is overview_file
            if($request->hasFile('overview_file')) { 
                $file = $request->file('overview_file');
                $overview_file = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                $image['filePath'] = $overview_file;
                $file->move(public_path().'/uploads/', $overview_file);
                $values['overview_file'] = '/uploads/'. $overview_file; //public_path().
            }
            //$events->update();
            
            if(!empty($request->dates)){
                $d=0;
                $del = EventDateTime::where('event_id', $request->event_id)->delete();
                foreach($request->dates as $date){
                    $eventdt = new EventDateTime;    
                    $eventdt->event_date = date("Y-m-d",strtotime($date));
                    $eventdt->start_time = date("H:i:s",strtotime($request->start_time[$d]));
                    $eventdt->end_time = date("H:i:s",strtotime($request->end_time[$d]));
                    $eventdt->event_id = $request->event_id;
                    $eventdt->save();
                    $d++;
                }
            }
            Events::where('id', $request->event_id)->update($values);
            return $this->getNextstep(1,$request->event_id);
        }
    }
    
    //create event Map (skip for now)    
    public function addEventMap(Request $request)
    {        
        //skip for now
        $nextstep= $request->step;
        return $this->getNextstep($nextstep,$request->event_id);
         
    }
    //go to next (skip for now)    
    public function goNextStep(Request $request)
    {        
        //skip for now
        $nextstep= $request->step;
        return $this->getNextstep($nextstep,$request->event_id);
         
    }
    
    //create event Attendee List (skip for now)    
    public function addEventAttendeeList(Request $request)
    {        
        //skip for now
        $nextstep= $request->step;
        return $this->getNextstep($nextstep,$request->event_id);
         
    }
    
    //edit overview
    public function updateEventOverview(Request $request)
    {   
        //convert start date and end date to Y-m-d H:i:s
        $start_date=date("Y-m-d H:i:s",strtotime($request->start_dateonly));
        $end_date=date("Y-m-d H:i:s",strtotime($request->end_dateonly));        
        
        #'is_register_url' => $request->is_register_url,
            #'register_url' => $request->register_url,
            #'directions_button' => $request->directions_button,
            $values=array('start_date' => $start_date,
            'end_date' => $end_date,
            'event_name' => $request->event_name,
            'event_types' => $request->event_type,
            'event_status' => $request->status,        
            'register_button' => $request->register_button,
            'description' => $request->description,
            'custom_calendar_message' => $request->custom_calendar_message,
            'directions_url' => $request->directions_url,
            'website_url' => $request->website_url,
            'cost_per_person' => $request->cost_per_person,
            'number_of_person' => $request->number_of_person,
            'schedule_image' => $request->schedule_image,
            'accomodations_image' => $request->accommodations_image,
            'dining_image' => $request->dining_image,
            'transportation_image' => $request->transportation_image,
            'map' => $request->map,
            'travelhost' => $request->travelhost,
            'event_host' => $request->event_host,
            'event_host_title' => $request->event_host_title,
            'event_host_description' => $request->event_host_description,
            'event_host_email' => $request->event_host_email,
            'visibility_web' => $request->visibility_web,
            'visibility_app' => $request->visibility_app,
            'timezone_offset' => $request->timezone_offset);
        //upload overview image
        if($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();
            $image['filePath'] = $name;
            //resize if more than 1300
            $width = Image::make($file)->width();
            if($width>1300){
                Image::make($file)
                ->resize(1300, null, function ($constraints) {
                    $constraints->aspectRatio();
                })->save($this->banner_path.'/' . $name);
            }
            else{
                $file->move($this->banner_path.'/', $name);
            }
            //$events->image= '/uploads/'. $name; //public_path().
            $values['image'] = '/uploads/'. $name;
        }

         //check if there is overview_file
         if($request->hasFile('overview_file')) { 
            $file = $request->file('overview_file');
            $overview_file = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
            $image['filePath'] = $overview_file;
            $file->move(public_path().'/uploads/', $overview_file);
            $values['overview_file'] = '/uploads/'. $overview_file; //public_path().
        }
        //$events->update();
        
            
        $events=Events::where('id', $request->event_id)->update($values);
        if(!empty($request->dates)){
            $d=0;
            $del = EventDateTime::where('event_id', $request->event_id)->delete();
            foreach($request->dates as $date){
                $eventdt = new EventDateTime;    
                $eventdt->event_date = date("Y-m-d",strtotime($date));
                $eventdt->start_time = date("H:i:s",strtotime($request->start_time[$d]));
                $eventdt->end_time = date("H:i:s",strtotime($request->end_time[$d]));
                $eventdt->event_id = $request->event_id;
                $eventdt->save();
                $d++;
            }
        }
        
        if(!empty($request->schedule_image)){
            $check = EventSchedule::where('event_id', $request->event_id)->first();
            if($check){ }
            else{
                $populate= new EventSchedule;
                $populate->event_id = $request->event_id;
                $populate->save();
            }
        }
        if(!empty($request->accommodations_image)){
            $check = EventAccomodations::where('event_id', $request->event_id)->first();
            if($check){ }
            else{
                $populate= new EventAccomodations;
                $populate->event_id=$request->event_id;
                $populate->save();
            }
        }
        if(!empty($request->dining_image)){
            $check = EventDining::where('event_id', $request->event_id)->first();
            if($check){ }
            else{
                $populate= new EventDining;
                $populate->event_id=$request->event_id;
                $populate->save();
            }
        }
        if(!empty($request->transportation_image)){
            $check = EventTransportation::where('event_id', $request->event_id)->first();
            if($check){ }
            else{
                $populate= new EventTransportation;
                $populate->event_id=$request->event_id;
                $populate->save();
            }
        }
        if(!empty($request->travelhost)){
            $check = EventTransportation::where('event_id', $request->event_id)->first();
            if($check){ }
            else{
                $populate= new EventTravelHosts;
                $populate->event_id=$request->event_id;
                $populate->save();
                $populate= new EventFaqs;
                $populate->event_id=$request->event_id;
                $populate->save();
            }
        }
        if(!empty($request->dates)){
            $d=0;
            foreach($request->dates as $date){
                $eventdt = new EventDateTime;    
                $eventdt->event_date = date("Y-m-d",strtotime($date));
                $eventdt->start_time = date("H:i:s",strtotime($request->start_time[$d]));
                $eventdt->end_time = date("H:i:s",strtotime($request->end_time[$d]));
                $eventdt->event_id = $request->event_id;
                $eventdt->save();
                $d++;
            }
        }
        return Response::json(array('success' => true,'message' => 'Event Overview Saved.'), 200);
    }

    public function deleteEvent(Request $request){
        $event_id=$request->event_id;
        //delete events photo 
        $photos=EventPhotos::where('event_id', $event_id);
        if (!empty($photos)) {
            foreach($photos as $p){
                $file_path = $this->photos_path . '/' . $p->filename;
                $resized_file = $this->photos_path . '/' . $p->resized_name;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                if (file_exists($resized_file)) {
                    unlink($resized_file);
                }
            }
            $photos->delete();
        }

        //delete event_Date_time
        $del = EventDateTime::where('event_id', $request->event_id)->delete();

        //delete attendee lists
        $uninvite = EventAttendees::where('event_id', $request->event_id)->delete();

        //delete EventTravelHosts
        $th=EventTravelHosts::where('event_id', $request->event_id);
        if (!empty($th)) {
            foreach($th as $t){
                if (file_exists($t->image)) {
                    unlink($t->image);
                }
        
                if (file_exists($t->thumb_image)) {
                    unlink($t->thumb_image);
                }
            }
            $th->delete();
        }

        //delete EventFaqs
        $ef=EventFaqs::where('event_id', $request->event_id);
        if (!empty($ef)) {
            foreach($ef as $e){
                if (file_exists($e->event_info_file)) {
                    unlink($e->event_info_file);
                }
            }
            $ef->delete();
        }

        //delete Maps
        $maps=EventMaps::where('event_id', $event_id);
        if (!empty($maps)) {
            foreach($maps as $m){
                $file_path = $this->maps_path . '/' . $m->filename;
                $resized_file = $this->maps_path . '/' . $m->resized_name;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                if (file_exists($resized_file)) {
                    unlink($resized_file);
                }
            }
            $maps->delete();
        }

        //delete transportation
        $uninvite = EventTransportation::where('event_id', $request->event_id)->delete();
        
        //delete EventDining
        $dining = EventDining::where('event_id', $request->event_id);
        if (!empty($dining)) {
            foreach($dining as $d){
                if (file_exists($d->image)) {
                    unlink($d->image);
                }
            }
            $dining->delete();
        }

        //delete EventAccomodations
        $accomodations = EventAccomodations::where('event_id', $request->event_id);
        if (!empty($accomodations)) {
            foreach($accomodations as $a){
                if (file_exists($a->image)) {
                    unlink($a->image);
                }
            }
            $accomodations->delete();
        }

        //delete EventSchedule
        $schedules = EventSchedule::where('event_id', $request->event_id);
        if (!empty($schedules)) {
            foreach($schedules as $s){
                if (file_exists($s->image)) {
                    unlink($s->image);
                }
                if (file_exists($s->thumb_image)) {
                    unlink($s->thumb_image);
                }
            }
            $schedules->delete();
        }

        //delete Events
        $event = Events::where('id', $request->event_id)->first();
        if (!empty($event)) {
            if (file_exists($event->image)) {
                unlink($event->image);
            }
            $event->delete();
        }
        
        

        return $this->index();
        
    }
    public function deleteBanner(Request $request){
        if($request->eventType=="events"){
            $event = DB::table($request->eventType)->where('id',$request->event_id)->first(['id','image as banner_image']);
            //echo $event->banner_image;die();
            if (!empty($event)) {
                if (file_exists($event->banner_image)) {
                    unlink($event->banner_image);
                }
                DB::table($request->eventType)->where('id',$request->event_id)->update(array('image' => ""));
            }
        }
        else{
            $event = DB::table($request->eventType)->where('event_id',$request->event_id)->first(['id','image as banner_image']);
            
            if (!empty($event)) {
                if (file_exists($event->banner_image)) {
                    unlink($event->banner_image);
                }
                DB::table($request->eventType)->where('event_id',$request->event_id)->update(array('image' => ""));
            }
        }
        return Response::json(array('success' => true,'message' => 'Event banner deleted.'), 200);

    }

    public function deleteItineraryFile(Request $request){
        $eventoverview=Events::where('id', $request->event_id)->first();
        if($eventoverview){
            $eventoverview->overview_file = null;
            $eventoverview->save();
        }
        return Response::json(array('success' => true,'message' => 'Event Itinerary deleted.'), 200);

    } 
}
