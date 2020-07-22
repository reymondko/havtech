<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Auth;
use App\Models\Events;
use App\Models\EventAttendees;
use App\User;
use Carbon\Carbon;
use DateTime;
use DatePeriod;
use DateInterval;
use MetaTag;
use DB;
class WebsiteHomePageController extends Controller
{
    
    private $eventClasses = [1=>'ge-ci',2=>'se-ci',3=>'li-ci',4=>'ai-ci']; // Classes used for the homepage calendar

    public function __construct(){
        MetaTag::set('title', 'Havtech Events Hub');
        MetaTag::set('description', '');
        MetaTag::set('image', asset('images/havtech-logo.png'));
    }

    /**
     * 
     * Havtech Events Homepage Controller
     */
    public function index(Request $request) 
    {
        $user = Auth::user();
        if(!$user){
            $user = null;
        }
        $tmpUpcomings = $this->getSliderEvents();
        $tmp_events = $this->getEvents();
        $first_day = strtotime(date('Y-m-d',strtotime('first day of this month')));
        $last_day = strtotime(date('Y-m-d',strtotime('last day of this month')));

        // $latestUpcomingEvents = array_slice($tmp_events,-3);
        // array_reverse($latestUpcomingEvents);
        // Filter Events by Current Month
        // Filter upcoming
        $events = [];
        $tmpUpcoming = [];
        foreach($tmp_events as $event){
            if($event['start_timestamp'] >= $first_day && $event['start_timestamp'] <= $last_day){
                $events[] = $event;
            }
        }
        foreach($tmpUpcomings as $event){
            $today = strtotime('today midnight');
            if($today <= $event['start_timestamp'] || $today <= $event['end_timestamp']){
                $tmpUpcoming[] = $event;
            }
        }

        
        //$latestUpcomingEventsz = array_reverse($tmpUpcoming);
        //show only 4 latest events
        $latestUpcomingEvents = array_slice($tmpUpcoming, 0, 4);

        return view('frontend.index',compact(['events','latestUpcomingEvents','user']));
    }
    
    /**
     * 
     * Retrieves recent events
     * if user is authenticated retrieve all event types 
     * otherwise do not retrieve special events
     * 
     * @return Array
     */
    public function getEvents()
    {
        $event_array = [];
        $user =[];
        $invted_Cnt=0;
        $invited = [];
        if(Auth::check()) {
            $user = Auth::user();
            $invited = EventAttendees::where('user_id',$user->id)->where('email_sent_approved',1)->pluck('event_id')->toArray();
            $invted_Cnt=count($invited);
        }
           // DB::enableQueryLog();
          /*  $events = Events::with('event_type','attendees')
                                    ->where('visibility_web','Published')
                                  //  ->whereIn('id',$invited)
                                  ->when(($invted_Cnt>0) , function ($query) use ($invited) {
                                    return $query->where(function($q) use ($invited) {
                                        $q->whereIn('id',$invited);
                                    });
                                    })
                                    ->leftJoin(
                                        DB::raw("(SELECT es.start_date as es_startdate,es.event_id
                                         from event_schedule as es order by start_date asc) as sched"), 
                                        'sched.event_id', '=', 'events.id' 
                                        )
                                    ->when(!isset($user->id) , function ($query) {
                                        return $query->where('event_types',[1,3]);
                                    })
                                    ->when(isset($user->id) , function ($query) {
                                        return $query->orWhere('event_types',[1,3]);
                                    })  
                                    ->when(empty($request->event_date) , function ($query) {
                                        return $query->where(function($q) {
                                            $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                                                ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
                                        });
                                      })
                                      
                                    ->groupBy('id')
                                    ->orderBy('start_date','desc')
                                    ->orderBy('es_startdate','asc')
                                    
                                    ->get();*/
                                $events = Events::with('event_type','attendees')
                                    ->where('visibility_web','Published')
                                  //  ->whereIn('id',$invited)
                                  ->when(($invted_Cnt > 0) , function ($query) use ($invited) {
                                    return $query->where(function($q) use ($invited) {
                                        $q->whereIn('events.id',$invited)->orWhereIn('event_types',[1,3]);
                                    });
                                    })
                                    ->when(($invted_Cnt <= 0) , function ($query) use ($invited) {
                                        return $query->where(function($q) use ($invited) {
                                            $q->where('event_types','!=',"2");
                                        });
                                    })
                                    ->leftJoin(
                                        DB::raw("(SELECT es.start_date as es_startdate,es.event_id
                                         from event_schedule as es where start_date >= '". date('Y-m-d 00:00:00')."'  order by start_date asc ) as sched"),
                                        'sched.event_id', '=', 'events.id'
                                        )
                                    ->when(!isset($user->id) , function ($query) {
                                        return $query->whereIn('event_types',[1,3]);
                                    })
                                    ->when(empty($request->event_date) , function ($query) {
                                        return $query->where(function($q) {
                                            $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                                                ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
                                        });
                                      })
                                    ->where('event_types','!=',"4")
                                    ->groupBy('id')
                                    ->orderBy('start_date','asc')
                                    ->orderBy('es_startdate','asc')
                                    ->get();
                                   // $query = DB::getQueryLog();
                                    // dd($query);die();
                                  //  print_r($invited_events);die();
            foreach($events as $key=>$value){ 
                $DateRange = $this->getDatesFromRange($value->start_date, $value->end_date); 
                //echo $value->start_date." === "; print_r($DateRange); echo "<br>";
                $checker=$value->id;
                $x=0;
                if($value->start_date==$value->end_date){
                    $daterange_display=date('M d, Y', strtotime($value->end_date));
                    $daterange_display_formatted = date('D, M d, Y', strtotime($value->end_date));
                }
                else{
                    if(date("Y-m",strtotime($value->start_date))== date("Y-m",strtotime($value->end_date))){
                        $daterange_display = date('M d', strtotime($value->start_date)) . ' - ' . date('d, Y', strtotime($value->end_date));
                        $daterange_display_formatted = date('D, M d', strtotime($value->start_date)) . ' - ' . date('d, Y', strtotime($value->end_date));
                    }
                    else{
                        $daterange_display = date('M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date));
                        $daterange_display_formatted = date('D, M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date));
                    }
                }
                $monthchecker="";
                foreach($DateRange as $Dates){
                    
                    if(($checker== $value->id && $x==0) || ( $monthchecker!= date('M', strtotime($Dates)))){
                        $display="block";
                    }else{ $display="hidden"; }
                    
                    $event_array[] = [
                        'id' => $value->id.$x,
                        'description' => str_replace('&nbsp','',strip_tags($value->description)),
                        'startDate' => $Dates,//$value->start_date,
                        'startDateFormatted' => $daterange_display_formatted,//date('D, M j, Y', strtotime($Dates)),//$value->start_date
                        'end_date' => $value->end_date,
                        'event_name' => $value->event_name,
                        'type' => $value->event_type->description,
                        'type_id' => $value->event_type->id,
                        'event_date_range' => $daterange_display,//date('M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date)),
                        'image' => (isset($value->image) ? $this->encodeSpace(asset($value->image)) : NULL ),
                        'start_timestamp' => strtotime($Dates),
                        'end_timestamp' => strtotime($value->end_date),
                        'title' => date('j', strtotime($Dates)),
                        'classes' => $this->eventClasses[$value->event_type->id],
                        'url' => '/events/' . $value->id,
                        'register_url' => '/events/registration/' . $value->id,
                        'description_raw' => $value->description,
                        'display' => $display
                    ];
                    $x++;
                    $monthchecker = date('M', strtotime($Dates));
                }
            }
         //   $query = DB::getQueryLog();
      // dd($query);die();
                    /*}

                    $general_li_events = Events::with('event_type','attendees')
                                ->whereIn('event_types',[1,3])
                                ->where('visibility_web','Published')
                                ->orderBy('id','desc')
                                ->get();

                    foreach($general_li_events as $key=>$value){
                        $event_array[] = [
                            'id' => $value->id,
                            'description' => str_replace('&nbsp','',strip_tags($value->description)),
                            'startDate' => $value->start_date,
                            'startDateFormatted' => date('D, M j, Y', strtotime($value->start_date)),
                            'end_date' => $value->end_date,
                            'event_name' => $value->event_name,
                            'type' => $value->event_type->description,
                            'type_id' => $value->event_type->id,
                            'event_date_range' => date('M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date)),
                            'image' => (isset($value->image) ? $this->encodeSpace(asset($value->image)) : NULL ),
                            'start_timestamp' => strtotime($value->start_date),
                            'end_timestamp' => strtotime($value->end_date),
                            'title' => date('j', strtotime($value->start_date)),
                            'classes' => $this->eventClasses[$value->event_type->id],
                            'url' => '/events/' . $value->id,
                            'register_url' => '/events/registration/' . $value->id,
                            'description_raw' => $value->description
                        ];
                    }
                    
            usort($event_array, function ($event1, $event2) {
                return $event2['start_timestamp'] <=> $event1['start_timestamp'];
            });*/
            
        return $event_array;
    }

    /**
     * 
     * Retrieves recent events
     * if user is authenticated retrieve all event types 
     * otherwise do not retrieve special events
     * 
     * @return Array
     */
    public function getMyEvents()
    {
        /*$event_array = [];
        $user =[];
        $invted_Cnt=0;
        $invited = [];
        if(Auth::check()) {
            $user = Auth::user();
            $invited = EventAttendees::where('user_id',$user->id)->leftJoin('events','events.id','event_attendees.event_id')->where('event_types',2)->where('email_sent_approved',1)->pluck('event_id')->toArray();
            $invted_Cnt=count($invited);
        }
        //DB::enableQueryLog();    
        $events = Events::with('event_type','attendees')
            ->where('visibility_web','Published')
            //  ->whereIn('id',$invited)
            ->leftJoin(
                DB::raw("(SELECT es.start_date as es_startdate,es.event_id
                    from event_schedule as es where start_date >= '". date('Y-m-d 00:00:00')."'  order by start_date asc ) as sched"),
                'sched.event_id', '=', 'events.id'
                )
            ->leftJoin(
                DB::raw("(SELECT er.event_id,er.user_id
                    from event_registrations as er where er.user_id = '".$user->id."' ) as reg"),
                'reg.event_id', '=', 'events.id'
                )
            ->when(empty($request->event_date) , function ($query) {
                return $query->where(function($q) {
                    $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                        ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
                });
                })
            ->when(($invted_Cnt>0) , function ($query) use ($invited,$user) {
                return $query->where(function($q) use ($invited,$user) {
                    $q->whereIn('id',$invited)->orWhere('user_id',$user->id);
                });
                })
            ->groupBy('id')
            ->orderBy('start_date','asc')
            ->orderBy('es_startdate','asc')
            ->get();*/
            $event_array = [];
            $user =[];
            $invted_Cnt=0;
            $invited = [];
            if(Auth::check()) {
                $user = Auth::user();
                $invited = EventAttendees::where('user_id',$user->id)->where('email_sent_approved',1)->pluck('event_id')->toArray();
                $invted_Cnt=count($invited);
            }
            //DB::enableQueryLog();

            $events = Events::with('event_type','attendees')
                ->where('visibility_web','Published')
                ->when(($invted_Cnt>0) , function ($query) use ($invited) {
                        return $query->where(function($q) use ($invited) {
                            $q->whereIn('events.id',$invited)->orWhereIn('event_types',[1,3]);
                        });
                })
                ->leftJoin('event_registrations', function($join)use($user){
                    $join->on('event_registrations.event_id','=','events.id');
                    $join->on('event_registrations.user_id',DB::raw($user->id));
                  })
                ->leftJoin(
                    DB::raw("(SELECT es.start_date as es_startdate,es.event_id
                        from event_schedule as es where start_date >= '". date('Y-m-d 00:00:00')."'  order by start_date asc ) as sched"),
                    'sched.event_id', '=', 'events.id'
                    )
                    
                ->leftJoin('event_attendees', function($join2)use($user){
                    $join2->on('event_attendees.event_id','=','events.id');
                    $join2->on('event_attendees.user_id','=',DB::raw($user->id));
                    $join2->on('event_attendees.email_sent_approved','=',DB::raw('1'));
                })
                ->when(!isset($user->id) , function ($query) {
                    return $query->whereIn('event_types',[1,3]);
                })->where(function($q) {
                    $q->where(function($q) {
                      $q->whereNotNull('event_attendees.id')
                        ->where('event_types','2');
                      })
                      ->orwhereNotNull('event_registrations.id');
              })
                ->when(empty($request->event_date) , function ($query) {
                    return $query->where(function($q) {
                        $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                            ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
                    });
                    })
                ->where('event_types','!=',"4")
                ->where('event_registrations.completed','1')
                ->groupBy('events.id')
                ->orderBy('start_date','asc')
                ->orderBy('es_startdate','asc')
                ->get(['events.*']);
               // $query = DB::getQueryLog();
                //print_r($query);die();
            foreach($events as $key=>$value){ 
                $DateRange = $this->getDatesFromRange($value->start_date, $value->end_date); 
                //echo $value->start_date." === "; print_r($DateRange); echo "<br>";
                $checker=$value->id;
                $x=0;
                if($value->start_date==$value->end_date){
                    $daterange_display=date('M d, Y', strtotime($value->end_date));
                    $daterange_display_formatted = date('D, M d, Y', strtotime($value->end_date));
                }
                else{
                    if(date("Y-m",strtotime($value->start_date))== date("Y-m",strtotime($value->end_date))){
                        $daterange_display = date('M d', strtotime($value->start_date)) . ' - ' . date('d, Y', strtotime($value->end_date));
                        $daterange_display_formatted = date('D, M d', strtotime($value->start_date)) . ' - ' . date('d, Y', strtotime($value->end_date));
                    }
                    else{
                        $daterange_display = date('M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date));
                        $daterange_display_formatted = date('D, M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date));
                    }
                }
                foreach($DateRange as $Dates){
                    if($checker== $value->id && $x==0){
                        $display="block";
                    }else{ $display="hidden"; }
                    
                    $event_array[] = [
                        'id' => $value->id.$x,
                        'description' => str_replace('&nbsp','',strip_tags($value->description)),
                        'startDate' => $Dates,//$value->start_date,
                        'startDateFormatted' => $daterange_display_formatted,//date('D, M j, Y', strtotime($Dates)),//$value->start_date
                        'end_date' => $value->end_date,
                        'event_name' => $value->event_name,
                        'type' => $value->event_type->description,
                        'type_id' => $value->event_type->id,
                        'event_date_range' => $daterange_display,//date('M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date)),
                        'image' => (isset($value->image) ? $this->encodeSpace(asset($value->image)) : NULL ),
                        'start_timestamp' => strtotime($Dates),
                        'end_timestamp' => strtotime($value->end_date),
                        'title' => date('j', strtotime($Dates)),
                        'classes' => $this->eventClasses[$value->event_type->id],
                        'url' => '/events/' . $value->id,
                        'register_url' => '/events/registration/' . $value->id,
                        'description_raw' => $value->description,
                        'display' => $display
                    ];
                    $x++;
                }
            }
            
        return $event_array;
    }

    /**
     * 
     * Retrieves recent events
     * if user is authenticated retrieve all event types 
     * otherwise do not retrieve special events
     * 
     * @return Array
     */
    public function getSliderEvents()
    {
        $event_array = [];
        $user =[];
        $invted_Cnt=0;
        $invited = [];
        if(Auth::check()) {
            $user = Auth::user();
            $invited = EventAttendees::where('user_id',$user->id)->leftJoin('events','events.id','event_attendees.event_id')->where('event_types',2)->where('email_sent_approved',1)->pluck('event_id')->toArray();
            $invted_Cnt=count($invited);
        }
        //DB::enableQueryLog();    
        $events = Events::with('event_type','attendees')
                    ->where('visibility_web','Published')
                    //  ->whereIn('id',$invited)
                    ->when(($invted_Cnt>0) , function ($query) use ($invited) {
                    return $query->where(function($q) use ($invited) {
                        $q->whereIn('id',$invited)->orWhereIn('event_types',[1,3]);
                    });
                    })
                    ->leftJoin(
                        DB::raw("(SELECT es.start_date as es_startdate,es.event_id
                            from event_schedule as es where start_date >= '". date('Y-m-d 00:00:00')."'  order by start_date asc ) as sched"),
                        'sched.event_id', '=', 'events.id'
                        )
                    ->when(!isset($user->id) , function ($query) {
                        return $query->whereIn('event_types',[1,3]);
                    })
                    ->when(empty($request->event_date) , function ($query) {
                        return $query->where(function($q) {
                            $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                                ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
                        });
                        })
                    ->groupBy('id')
                    ->orderBy('start_date','asc')
                    ->orderBy('es_startdate','asc')
                    ->get();
        foreach($events as $key=>$value){  
                if($value->start_date==$value->end_date){
                    $daterange_display=date('M d, Y', strtotime($value->end_date));
                    $daterange_display_formatted = date('D, M d, Y', strtotime($value->end_date));
                }
                else{
                    if(date("Y-m",strtotime($value->start_date))== date("Y-m",strtotime($value->end_date))){
                        $daterange_display = date('M d', strtotime($value->start_date)) . ' - ' . date('d, Y', strtotime($value->end_date));
                        $daterange_display_formatted = date('D, M d', strtotime($value->start_date)) . ' - ' . date('d, Y', strtotime($value->end_date));
                    }
                    else{
                        $daterange_display = date('M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date));
                        $daterange_display_formatted = date('D, M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date));
                    }
                }
                    
                    $event_array[] = [
                        'id' => $value->id,
                        'description' => str_replace('&nbsp','',strip_tags($value->description)),
                        'startDate' => $value->start_date,//$value->start_date,
                        'startDateFormatted' => $daterange_display_formatted,//date('D, M j, Y', strtotime($Dates)),//$value->start_date
                        'end_date' => $value->end_date,
                        'event_name' => $value->event_name,
                        'type' => $value->event_type->description,
                        'type_id' => $value->event_type->id,
                        'event_date_range' => $daterange_display,//date('M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date)),
                        'image' => (isset($value->image) ? $this->encodeSpace(asset($value->image)) : NULL ),
                        'start_timestamp' => strtotime($value->start_date),
                        'end_timestamp' => strtotime($value->end_date),
                        'title' => date('j', strtotime($value->start_date)),
                        'classes' => $this->eventClasses[$value->event_type->id],
                        'url' => '/events/' . $value->id,
                        'register_url' => '/events/registration/' . $value->id,
                        'description_raw' => $value->description
                    ];
                
            }
            
        return $event_array;
    }

    private function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
      
        // Declare an empty array 
        $array = array(); 
          
        // Variable that store the date interval 
        // of period 1 day 
        $interval = new DateInterval('P1D'); 
      
        $realEnd = new DateTime($end); 
        $realEnd->add($interval); 
      
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
      
        // Use loop to store date into array 
        foreach($period as $date) {                  
            $array[] = $date->format($format);  
        } 
      
        // Return the array elements 
        return $array; 
    } 

    /**
     * 
     * Filters events based on filter parameters
     * @param Illuminate\Http\Request
     * @return Json
     */
    public function filterEvents(Request $request){
        $periodDate = new DateTime($request->period);
        $first_day = strtotime($periodDate->modify('first day of this month')->format('Y-m-d'));
        $last_day = strtotime($periodDate->modify('last day of this month')->format('Y-m-d h:i:s'));
        if($request->filter=="99"){
            $tmp_events = $this->getMyEvents();
        }
        else{
            $tmp_events = $this->getEvents();
        }
        //print_r($tmp_events);die();
        // Filter Events by Current Month
        $events = [];
        foreach($tmp_events as $event){
            if($request->filter != 0 & $request->filter != 99){
                if($event['start_timestamp'] >= $first_day 
                    && $event['start_timestamp'] <= $last_day
                    && $event['type_id'] == $request->filter){
                    $events[] = $event;
                }
            }
            else{
                if($event['start_timestamp'] >= $first_day && $event['start_timestamp'] <= $last_day){
                    $events[] = $event;
                }
            }
            
        }

        return response()->json(['status' => 'success','data'=>['events'=>$events]], 200);
    }

    /**
     * 
     * Returns string with space replaced with %20
     * @param String $str
     * @return String $str
     */
    private function encodeSpace($str)
    {
        if($str){
           return str_replace(' ','%20',$str);
        }
        return null;
    }
  
}
