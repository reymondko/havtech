<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\EventTypes;
use App\Models\EventAttendees;
use App\Models\EventSchedule;
use App\Models\EventAccomodations;
use App\Models\EventDining;
use App\Models\EventTransportation;
use App\Models\EventTravelHosts;
use App\Models\EventFaqs;
use App\Models\EventPhotos;
use App\Models\NotificationRecipients;
use Intervention\Image\Facades\Image;
use App\Models\OnesignalUserPlayerIds;
use App\Models\EventIndustries;
use App\Models\CustomerType;
use App\Models\EventRegistrations;
use App\Models\CustomEventSchedule;
use App\Models\Country;
use App\Models\PaymentsRegistration;
use App\Classes\SecurePaymentIntegration;
use Spatie\CalendarLinks\Link;

use DB;
use DateTime;
use MetaTag;
use Storage;

class WebsiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        MetaTag::set('title', 'Havtech Events Hub');
        MetaTag::set('description', '');
        MetaTag::set('image', asset('images/havtech-logo.png'));
    }

    public function index(Request $request) {
        return view('frontend.index');
    }
    /**
     * Returns string with space replaced with %20
     * 
     * @param String $str
     * @return String $str
     */
    public function encodeSpace($str){
        if($str){
          return str_replace(' ','%20',$str);
        }
        return null;
    }
    
    public function events(Request $request) {
      $user= \Auth::user();
      //app('App\Http\Controllers\WebsiteHomePageController')->getEvents();
      $events = Events::select('events.start_date as sd', DB::raw('DATE_FORMAT(events.start_date, "%a, %b %e, %Y") as start_date'),'events.*','event_name','end_date','event_type.description as event_type','events.created_at','events.description as event_description','address.location_address', 'address.location_address2', 'address.city', 'address.state', 'address.cname','address.zip','events.image','address.location','address.room_number')
                ->join('event_type', 'events.event_types', '=', 'event_type.id')
                ->leftJoin(
                  DB::raw("(SELECT event_id,location_address, location_address2 ,city, zip,state ,country,c.name as cname,room_number,location
                   from event_schedule as es
                   left join countries as c ON es.country=c.id  group by event_id order by start_date) as address"), 
                  'address.event_id', '=', 'events.id' 
                  )
                ->where('visibility_web',"Published")
                ->where('event_types','!=',"4")
                ->where(function($q) {
                    $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                      ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
                 })
                ->when(empty($user->id) , function ($query) {
                  return $query->where('event_types', '!=', 2);
                  })  
                ->orderBy('events.start_date', 'asc')
                ->get();
               // print date("Y-m-d");

        if($user){
            $invited = EventAttendees::where('user_id',$user->id)->pluck('event_id')->toArray();
        
            $tmpEvents = [];
            foreach($events as $event){
                if($event->event_type == 'Special Events'){
                    if(in_array($event->id,$invited)){
                        $tmpEvents[] = $event;
                    }
                }else{
                    $tmpEvents[] = $event;
                }
            }
            $events = $tmpEvents;
        }

      /* for filter location drop down */
      $locations = []; 
      foreach($events as $e){
        $loc = "";
        if(isset($e->city) && !isset($e->state)){
          $loc = $e->city;
        }
        else if(!isset($e->city) && isset($e->state)){
          $loc = $e->state;
        }
        else if(isset($e->city) && isset($e->state)){
          $loc = $e->city.", ".$e->state;
        }
        else{
          $loc = "";
        }
        //insert if not in the array
        if (!in_array($loc, $locations)) {
          $locations[]=$loc;
        }
        $addressfull="";
        if($e->location=="Room Number"){
          $addressfull=$e->room_number;
        }
        else{
          if($e->location_address){
            $addressfull .= trim($e->location_address).",  ";
          }
          if($e->cname){ $addressfull .= trim($e->cname).",  "; }
          if($e->state){ $addressfull .= trim($e->city).",  "; }
          if($e->zip){ $addressfull .= trim($e->zip); }
        }
        $e->addressfull=$addressfull;
        
      }
      // sort location alphabetical
      sort($locations);

      /* for date filter drop-down */
      $addressfull="";       
      
        
      
      $datesArr = [];
      $datesArr[] = date("F Y");
      for ($i = 1; $i <= 8; $i++) 
      {
        $datesArr[] = date("F Y", strtotime( date( 'Y-m-01' )." +$i months"));
      }

      /* fetch all event types */
      $eventtypes=EventTypes::when(empty($user->id), function ($query) {
        return $query->where('id', '!=', 2)->where('id', '!=', 4);
        })->get();  
      
      $data = array(
          'events' => $events,                  
          'eventtypes' => $eventtypes   ,          
          'date_filter' => $datesArr,
          'location_filter' => $locations
      );

      return view('frontend.events')->with('data', $data);
    }

    public function events_filtered(Request $request) {        
      $user= \Auth::user();
      $start="";
      $end="";

      if(isset($request->event_date)){        
        if(date("m",strtotime($request->event_date))==date("m")){
          $start=date("Y-m-1");
        }
        else{
          $start=date("Y-m-1",strtotime($request->event_date));
        }
        $end=date("Y-m-t",strtotime($request->event_date));
      }
      
      $city=$request->city;
      $state=$request->state;
      if($request->eventtype_id==0){
        $events=Events::select('events.*','events.start_date as sd', 
        DB::raw('DATE_FORMAT(events.start_date, "%a, %b %e, %Y")    
        as start_date'),'events.id','event_name',
        'end_date','event_type.description as event_type','events.created_at','events.description as event_description','address.location','address.room_number','address.location_address', 'address.location_address2', 'address.city', 'address.state', 'address.cname','address.zip')
        ->join('event_type', 'events.event_types', '=', 'event_type.id')
        ->leftJoin(
          DB::raw("(SELECT event_id,location_address, location_address2 ,city, zip,state ,country,c.name as cname,location,room_number
           from event_schedule as es
           left join countries as c ON es.country=c.id  group by event_id order by start_date) as address"), 
          'address.event_id', '=', 'events.id' 
          )
        ->where('visibility_web','Published')        
        ->where('event_types','!=',"4")
        ->when(!empty($request->event_date) , function ($query) use($start,$end){
          return $query->whereBetween('start_date', [$start, $end]);
          })
        ->when(empty($request->event_date) , function ($query) {
            return $query->where(function($q) {
                $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                    ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
            });
          })
        ->when(empty($user->id) , function ($query) {
          return $query->where('event_types', '!=', 2);
          })                  
        ->when(!empty($request->city) , function ($query) use($city,$state){
          return $query->where('city', 'like', '%' . $city . '%')
          ->where('state', 'like', '%' . $state . '%')
          ;
          })
        ->orderBy('sd', 'asc')
        ->get();
      }
      elseif($request->eventtype_id==99){
        //DB::enableQueryLog();
       
        $events=Events::select('events.start_date as sd', DB::raw('DATE_FORMAT(start_date, "%a, %b %e, %Y") as start_date'),
        'events.id','event_name','events.end_date','event_type.description as event_type',
        'events.created_at','events.description as event_description','address.location','address.room_number','address.location_address', 'address.location_address2', 'address.city', 'address.state', 
        'address.cname','address.zip','events.image')
        ->join('event_type', 'events.event_types', '=', 'event_type.id')
        ->leftJoin(
          DB::raw("(SELECT event_id,location_address, location_address2 ,city, zip,state ,country,c.name as cname,location,room_number
           from event_schedule  as es
           left join countries as c ON es.country=c.id  group by event_id order by start_date) as address"), 
          'address.event_id', '=', 'events.id' 
          )
          #->leftjoin('event_registrations','event_registrations.event_id','events.id')
          ->leftJoin('event_registrations', function($join)use($user){
            $join->on('event_registrations.event_id','=','events.id');
            $join->on('event_registrations.user_id',DB::raw($user->id));
          })
          ->leftJoin('event_attendees', function($join2)use($user){
              $join2->on('event_attendees.event_id','=','events.id');
              $join2->on('event_attendees.user_id','=',DB::raw($user->id));
              $join2->on('event_attendees.email_sent_approved','=',DB::raw('1'));
          })
          ->where('visibility_web','Published')
          ->where('event_registrations.completed','1')
          /*->where(function($q)use($user) {
              $q->where('event_registrations.user_id',"=",$user->id)  ;
               # ->orWhere('event_types',"2");
          })*/
          ->when(!empty($request->event_date) , function ($query) use($start,$end){
            return $query->whereBetween('start_date', [$start, $end]);
            })
            ->when(empty($request->event_date) , function ($query) {
                return $query->where(function($q) {
                    $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                        ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
                });
              })
            ->when(!empty($request->city) , function ($query) use($city,$state){
              return $query->where('country', 'like', '%' . $city . '%')
              ->orWhere('state', 'like', '%' . $state . '%')
              ;
            })->where(function($q) {
                  $q->where(function($q) {
                    $q->whereNotNull('event_attendees.id')
                      ->where('event_types','2');
                    })
                    ->orwhereNotNull('event_registrations.id');
            })        
            ->where('event_types','!=',"4")
        ->groupBy('events.id')
        ->orderBy('events.start_date', 'asc')
        ->get();
        //$query = DB::getQueryLog();
       // print_r($query);die();
        
      }
      else{
       // DB::enableQueryLog();
        $events=Events::select('events.start_date as sd','events.start_date as ed', DB::raw('DATE_FORMAT(start_date, "%a, %b %e, %Y") as start_date'),
        'events.id','event_name','end_date','event_type.description as event_type',
        'events.created_at','events.description as event_description','address.location','address.room_number','address.location_address', 'address.location_address2', 'address.city', 'address.state', 'address.cname','address.zip','events.image')
        ->join('event_type', 'events.event_types', '=', 'event_type.id')
        ->leftJoin(
          DB::raw("(SELECT event_id,location_address, location_address2 ,city, zip,state ,country,c.name as cname,location,room_number
           from event_schedule as es
           left join countries as c ON es.country=c.id  group by event_id order by start_date) as address"), 
          'address.event_id', '=', 'events.id' 
          )
          ->where('visibility_web','Published')
          ->where('event_types',$request->eventtype_id)
          ->when(!empty($request->event_date) , function ($query) use($start,$end){
            return $query->whereBetween('start_date', [$start, $end]);
            })
            ->when(empty($request->event_date) , function ($query) {
                return $query->where(function($q) {
                    $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                        ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
                });
              })
            ->when(empty($user->id) , function ($query) {
                return $query->where('event_types', '!=', 2);
                })  
            ->when(!empty($request->city) , function ($query) use($city,$state){
              return $query->where('country', 'like', '%' . $city . '%')
              ->orWhere('state', 'like', '%' . $state . '%')
              ;
              })        
              ->where('event_types','!=',"4")
        ->orderBy('events.start_date', 'asc')
        ->get();
       // $query = DB::getQueryLog();
       //print_r($query);die();
      }
      // check events where the user is invited
      if($user){
          $invited = EventAttendees::where('user_id',$user->id)->pluck('event_id')->toArray();

            $tmpEvents = [];
            foreach($events as $event){
                if($event->event_type == 'Special Events'){
                    if(in_array($event->id,$invited)){
                        $tmpEvents[] = $event;
                    }
                }else{
                    $tmpEvents[] = $event;
                }
            }
            $events = $tmpEvents;
        }

      $event_html="";
        if($events){
          foreach($events as $e){
            if($e->event_type=='Special Events'){ $class=" orange-overlay";}
            elseif($e->event_type=='Learning Institute'){ $class=" gray-overlay";}
            else { $class=" blue-overlay";}
            if($e->image){
              $addimage="style='background-image: url(".asset($e->image)."'";
            }
            else{
              $addimage="";
            }

            if($e->sd==$e->end_date){
              $dateshow=date('D, M d, Y', strtotime($e->end_date));}
            else{
              if(date("Y-m",strtotime($e->start_date)) == date("Y-m",strtotime($e->end_date))){
                $dateshow=date('D, M d', strtotime($e->start_date)) . ' - ' . date('d, Y', strtotime($e->end_date));}
              else{
                $dateshow=date('D, M d', strtotime($e->start_date)) . ' - ' . date('M d, Y', strtotime($e->end_date));}
              }
            
            $event_html.="
              <div id='".str_replace(' ','-',substr($e->event_type, 0, -1))."' class='event-row' >
              <div class='image-container event' $addimage>
                <a href='/events/$e->id' class='events-image-link w-inline-block'>
                  <div class='$class'>
                    <div class='event-label'>";
                      if($e->event_type=='Special Events')
                      { 
                        $event_html.="<img src='/eventshub/images/Star.svg' alt='Special Event' class='special-star'>"; 
                      }
            $event_html.="          
                      <div class='event-category events'>".$e->event_type."</div>
                    </div>
                  </div>
                </a>
              </div>
              <div class='event-content'>
                <h3 class='heading-3 event-title'>".$e->event_name."</h3>
                <p class='event-date'>".$dateshow."</p>
                <p class='event-subtitle'>";
                /*if($e->location=="Room Number"){ 
                  $event_html .=$e->room_number;
                }
                else{
                  if($e->location_address)
                  {
                    $event_html .= $e->location_address;
                  }
                  if($e->cname)
                  {
                    $event_html.= ", ".$e->cname;
                  }
                  if($e->city)
                  {
                    $event_html.= ", ".$e->city;
                  }
                  if($e->state)
                  {
                    $event_html.= ", ".$e->state;
                  }

                  if($e->zip)
                  {
                    $event_html.= ", ".$e->zip;
                  }
                }
                ".$e->event_description."
                */

            $event_html.= "
                  </p>
                <p class='paragraph'></p> 
                <a href='/events/$e->id' class='base-button orange-button w-button' data-ix='button-hover'>More Info</a></div>
            </div>";
          }     
        }
        else{
          $events_html="No Events Found";
        }
        return $event_html;
    }

    public function event(Request $request, $event_id) {
        
      $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
      
      $event=Events::with('faqs','schedules','accomodations','dining','dining.type','transportations','hosts','maps','approved_photos')->where('id',$event_id)->first();
      
     
      $user =\Auth::user();

      $is_registered = false;
      if($user){
          $event_registration = EventRegistrations::where('user_id',$user->id)
                                              ->where('event_id',$event_id)
                                              ->where('completed',1)
                                              ->first();
          if($event_registration){
              $is_registered = true;
          }
      }  
                      
      if($event){
          $event->event_date_range = date('M d', strtotime($event->start_date)) . ' - ' . date('M d, Y', strtotime($event->end_date));
          if(isset($event->image) && $event->image!=""){
              $raw_event_image =$event->image;
              $event->image = $this->encodeSpace(asset($event->image));
          }
          
          if($event->overview_file){
            $event->overview_file = asset($event->overview_file);
          }

          if($event->register_button){
            $event->register_button = $this->addhttp($event->register_button);
          }
          if($event->directions_url){
            $event->directions_url = $this->addhttp($event->directions_url);
          }
          if($event->website_url){
            $event->website_url = $this->addhttp($event->website_url);
          }

          
          if(isset($event->hosts)){
              $tmp_hosts = [];
              foreach($event->hosts as $key=>$host){
                  $tmp_hosts[$key] = $host;
                  $tmp_hosts[$key]['image'] = $this->encodeSpace(asset($host->image));
                  $tmp_hosts[$key]['thumb_image'] = $this->encodeSpace(asset($host->thumb_image));
              }
              $event->hosts = $tmp_hosts;
          }

          // Check if user has custom schedule for event
          $has_custom_schedule = false;
          $custom_schedule_list = [];
          $custom_schedules = CustomEventSchedule::where('event_id',$event_id)
                                             #reymond ->where('user_id',$user->id)
                                              ->get();
          if($custom_schedules){
              foreach($custom_schedules as $custom_schedule){
                  $custom_schedule_list[] = $custom_schedule->event_schedule_id;
              }
              if(count($custom_schedule_list)>0){
                  $has_custom_schedule = true;
              }
          }
          // Sort and Group schedule by date
          if(isset($event->schedules)){
              $tmp_schedule = [];
              $tmp_raw_schedule = [];
              
              foreach($event->schedules as $key=>$schedule){
                  if($schedule->price){
                    $schedule->price=number_format((float)$schedule->price, 2, '.', '');
                  }
                  $schedule->start_timestamp = strtotime($schedule->start_date);
                  $schedule->end_timestamp = strtotime($schedule->end_date);
                  $schedule->image = $this->encodeSpace(asset($schedule->image));
                  if($schedule->itinerary_file){
                    $schedule->itinerary_file = asset($schedule->itinerary_file);
                  }
                  $schedule->thumb_image = isset($schedule->thumb_image) ? $this->encodeSpace(asset($schedule->thumb_image)) : NULL;

                  if($schedule->country)
                  {
                      $country = Country::where('id',$schedule->country)->first();
                      $schedule->country_name = $country->name;
                  }
                 
                  $addressfull="";
                  
                  if($schedule->location=="Room Number"){
                    $addressfull=$schedule->room_number;
                  }
                  else{
                    if($schedule->location_address){
                      $addressfull .= trim($schedule->location_address).",  ";
                    }
                    if($schedule->cname){ $addressfull .= trim($schedule->cname).",  "; }
                    if($schedule->state){ $addressfull .= trim($schedule->city).",  "; }
                    if($schedule->zip){ $addressfull .= trim($schedule->zip); }
                  }
                    
                  $schedule->addressfull=$addressfull;

                  // filename
                  $schedule->itinerary_filename = 'file.txt';
                  if($schedule->itinerary_file != null){
                      $itinerary = explode('/',$schedule->itinerary_file);
                      $schedule->itinerary_filename = end($itinerary);
                      $schedule->itinerary_filemime = \GuzzleHttp\Psr7\mimetype_from_filename($schedule->itinerary_filename);
                  }

                  // Auto select schedule if it's mandatory
                  if($schedule->mandatory == 1){
                      $schedule->selected = true;
                  }

                  $tmp_raw_schedule[] = $schedule;
                  $tmp_schedule[date('Y-m-d',strtotime($schedule->start_date))][] = $schedule;

              }
              
              // Sort (grouped schedule by start_date) by start_timestamp
              $tmp_schedule_sorted = [];
              $tmp_custom_schedule_sorted = [];
              $tmp_custom_unselected_schedule_sorted = [];
              ksort($tmp_schedule);
              foreach($tmp_schedule as $key=>$schedules){
                  $tmp_schedule_sorted_by_time = [];
                  $tmp_custom_schedule_sorted_by_time = [];
                  $tmp_custom_unselected_schedule_sorted_by_time = [];
                  foreach($schedules as $schedule){
                      if(isset($tmp_schedule_sorted_by_time[$schedule->start_timestamp])){
                          $tmp_start_timestamp = $schedule->start_timestamp;
                          do{
                              $tmp_start_timestamp = $tmp_start_timestamp + 1;
                          }while(isset($tmp_schedule_sorted_by_time[$tmp_start_timestamp]));

                          $tmp_schedule_sorted_by_time[$tmp_start_timestamp] = $schedule;

                          // Check for custom schedule and uselected custom schedule
                          if($has_custom_schedule){
                              if(in_array($schedule->id,$custom_schedule_list)){
                                  $tmp_custom_schedule_sorted_by_time[$tmp_start_timestamp] = $schedule;
                              }else{
                                  $tmp_custom_unselected_schedule_sorted_by_time[$tmp_start_timestamp] = $schedule;
                              }
                          }else{
                              $tmp_custom_unselected_schedule_sorted_by_time[$tmp_start_timestamp] = $schedule;
                          }

                      }else{
                          $tmp_schedule_sorted_by_time[$schedule->start_timestamp] = $schedule;

                           // Check for custom schedule and uselected custom schedule
                           if($has_custom_schedule){
                              if(in_array($schedule->id,$custom_schedule_list)){
                                  $tmp_custom_schedule_sorted_by_time[$schedule->start_timestamp] = $schedule;
                              }else{
                                  $tmp_custom_unselected_schedule_sorted_by_time[$schedule->start_timestamp] = $schedule;
                              }
                          }else{
                              $tmp_custom_unselected_schedule_sorted_by_time[$schedule->start_timestamp] = $schedule;
                          }

                      }
                  }
                  //$tmp_schedule_sorted[$key] = array_reverse($tmp_schedule_sorted_by_time,true);
                  //var_dump($tmp_schedule_sorted_by_time);
                  //print_r($tmp_schedule_sorted_by_time);
                  $tmp_schedule_sorted[$key] = $tmp_schedule_sorted_by_time;//array_reverse($tmp_schedule_sorted_by_time,true);
                  if(count($tmp_custom_schedule_sorted_by_time) > 0){
                      $tmp_custom_schedule_sorted[$key] = $tmp_custom_schedule_sorted_by_time;
                  }
                  if(count($tmp_custom_unselected_schedule_sorted_by_time) > 0){
                      $tmp_custom_unselected_schedule_sorted[$key] = $tmp_custom_unselected_schedule_sorted_by_time;
                  }
              }
              
              $event->schedules = $tmp_raw_schedule;
              //$event->grouped_schedules = $tmp_schedule_sorted;
              
              $event->grouped_schedules = $tmp_schedule_sorted;// array_reverse($tmp_schedule_sorted,true);
              //print_r($event->grouped_schedules);
              $event->grouped_custom_schedules = $tmp_custom_schedule_sorted;
              if(count($tmp_custom_unselected_schedule_sorted)>0){
                  $event->grouped_unselected_schedules = $tmp_custom_unselected_schedule_sorted;
              }
              $event->has_custom_schedule = $has_custom_schedule;
          }

          // Sort and Group dining
          if(isset($event->dining)){
              $tmp_dining = [];
              foreach($event->dining as $key=>$dining){
                  $dining->start_timestamp = strtotime($dining->meal_date);
                  $dining->type_name = $dining->type->name ?? NULL;
                  if(isset($dining->image)){
                    $dining->image = $this->encodeSpace(asset($dining->image));
                  }
                  $tmp_dining[date('m-d-y',strtotime($dining->meal_date))][] = $dining;
              }

              // Sort and Group (grouped dining by meal_date) by dining_type
              $tmp_dining_sorted = [];
              $ctr = 0;
              foreach($tmp_dining as $key=>$dinings){
                  $tmp_dining_sorted_by_dining_type = [];
                  foreach($dinings as $dining){
                      if($dining->country)
                      {
                          $country = Country::where('id',$dining->country)->first();
                          $dining->country = $country->name;
                      }
                      $tmp_dining_sorted_by_dining_type[] = $dining;
                  }
                  usort($tmp_dining_sorted_by_dining_type,function($obj_a, $obj_b){
                      return strcmp($obj_a->dining_type, $obj_b->dining_type);
                  });
                  $tmp_dining_sorted[$ctr]['meal_date'] = $dining->meal_date;
                  $tmp_dining_sorted[$ctr]['data'] = $tmp_dining_sorted_by_dining_type;
                  $ctr++;
              }

              $event->grouped_dining = $tmp_dining_sorted;
          }

          // Alter accomodation data
          if(isset($event->accomodations)){
              $tmp_accomodations = [];
              foreach($event->accomodations as $accomodation){
                if(isset($accomodation->image)){
                  $accomodation->image = $this->encodeSpace(asset($accomodation->image));
                }
                  $tmp_accomodations[] = $accomodation;
                  if($accomodation->country)
                  {
                      $country = Country::where('id',$accomodation->country)->first();
                      $accomodation->country = $country->name;
                  }
                  if(isset($accomodation->website_url)){
                    if(strpos($accomodation->website_url, "http://") === false && strpos($accomodation->website_url, "https://") === false){
                        $accomodation->website_url = 'http://' . $accomodation->website_url;
                    }
                  }
              }

              $event->accomodations = $tmp_accomodations;
          }

          // Alter transportation data
          if(isset($event->transportations)){
              $tmp_transportation = [];
              foreach($event->transportations as $transportation){
                  $transportation->image = $this->encodeSpace(asset($transportation->image));
                  if($transportation->country)
                      {
                          $country = Country::where('id',$transportation->country)->first();
                          $transportation->country = $country->name;
                      }
                if(isset($transportation->website_url)){
                    if(strpos($transportation->website_url, "http://") === false && strpos($transportation->website_url, "https://") === false){
                        $transportation->website_url = 'http://' . $transportation->website_url;
                    }
                }
                  $tmp_transportation = $transportation;

              }
              $event->transportations = $tmp_transportation;
          }

          // Alter map data
          if(isset($event->maps)){
              $tmp_maps = [];
              foreach($event->maps as $map){
                  $map->filename = asset('uploads/maps/' . $map->filename);
                  $tmp_maps[] = $map;
              }
              $event->maps = $tmp_maps;
          }

          // Alter photos data
          if(isset($event->approved_photos)){
              $tmp_photos = [];
              foreach($event->approved_photos as $photo){
                  $photo->filename = asset('uploads/photos/' . $photo->filename);
                  $photo->resized_name = asset('uploads/photos/' . $photo->resized_name);
                  $tmp_photos[] = $photo;
              }

              $event->photos = $tmp_photos;
          }

          // Alter faq data
          if(isset($event->faqs)){
              $tmp_faqs = [];
              foreach($event->faqs as $faq){
                  $faq->event_info_file = asset($faq->event_info_file);

                  // filename
                  $faq->event_info_filename = 'file.txt';
                  if($faq->event_info_file != null){
                      $faq_file = explode('/',$faq->event_info_file);
                      $faq->event_info_filename = end($faq_file);
                  }

                  $tmp_faqs[] = $faq;
              }
              $event->faqs = $tmp_faqs;
          }

          // Current Event
          $event->current_schedule = null;
          $upcoming_schedule = null;
          $upcoming_diff = null;
          $ctr = 0;
          if(isset($event->schedules)){
              // Get current timestamp of timezone
              $current_date = new \DateTime(null, new \DateTimeZone('America/New_York'));
              $operator = $event->timezone_offset >= 0 ? '+':'-';
              $current_date->modify($operator.' '.abs($event->timezone_offset).' hours');
              $current_timestamp = $current_date->getTimestamp();
              foreach($event->schedules as $schedule){
                if($schedule->price){
                  $schedule->price=number_format((float)$schedule->price, 2, '.', '');
                }
                  if($has_custom_schedule){
                      if(!in_array($schedule->id,$custom_schedule_list)){
                          continue;
                      }
                  }

                  $schedule_start = new \DateTime($schedule->start_date, new \DateTimeZone('America/New_York'));
                  $schedule_start = $schedule_start->getTimestamp();
                  $schedule_end = new \DateTime($schedule->end_date, new \DateTimeZone('America/New_York'));
                  $schedule_end = $schedule_end->getTimestamp();

                  if($schedule_start <= $current_timestamp
                      && $schedule_end >= $current_timestamp
                  ){
                      $event->current_schedule = $schedule;
                      $event->current_schedule->index = $ctr;
                  }

                  // Check upcoming event
                  if($current_timestamp < $schedule_start){
                      if($upcoming_diff == null){
                          $upcoming_diff = $schedule_start - $current_timestamp;
                          $upcoming_schedule = $schedule;
                      }else{
                          if(($schedule_start - $current_timestamp) < $upcoming_diff){
                              $upcoming_diff = $schedule_start - $current_timestamp;
                              $upcoming_schedule = $schedule;
                              $upcoming_schedule->index = $ctr;
                          }
                      }
                  }
                  $ctr++;
              }
          }

          if($event->current_schedule == null){
              // remove upcoming event $event->current_schedule = $upcoming_schedule;
          }

          $event->is_registered = $is_registered;
          $event->event_host_description = strip_tags($event->event_host_description);
          
                // Set Meta Tags
            MetaTag::set('title', $event->event_name);
            MetaTag::set('description', $event->description);
            if($event->image){
              MetaTag::set('image', asset($raw_event_image));
            }
        }


      
      return view('frontend.event')->with('tabs', $tabs)->with('event',$event);
    }

    public function registration(Request $request,$event_id) {
      
      $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
      $event=Events::with('faqs','schedules','hosts','maps')->where('id',$event_id)->first();
      $user =\Auth::user();
      
      $is_registered = false;
      if($user){
          $event_registration = EventRegistrations::where('user_id',$user->id)
                                              ->where('event_id',$event_id)
                                              ->where('completed',1)
                                              ->first();
          if($event_registration){
              $is_registered = true;
          }
      }else{
        return redirect()->route('login'); // redirect to login if user is not authenticated
      }  
      if(isset($event->hosts)){
        $tmp_hosts = [];
        foreach($event->hosts as $key=>$host){
            $tmp_hosts[$key] = $host;
            $tmp_hosts[$key]['image'] = $this->encodeSpace(asset($host->image));
            $tmp_hosts[$key]['thumb_image'] = $this->encodeSpace(asset($host->thumb_image));
        }
        $event->hosts = $tmp_hosts;
    }
       // Alter map data
      if(isset($event->maps)){
          $tmp_maps = [];
          foreach($event->maps as $map){
              $map->filename = asset('uploads/maps/' . $map->filename);
              $tmp_maps[] = $map;
          }
          $event->maps = $tmp_maps;
      }
      
      if(isset($event->cost_per_person)){
        $event->cost_per_person=preg_replace('/\s+/', '', str_replace("$",'',($event->cost_per_person)));
        $event->totalcost=$event->cost_per_person;
      }
      else{
        $event->cost_per_person = 0;
        $event->totalcost = 0;
      }
      // Check if user has custom schedule for event
      $has_custom_schedule = false;
      $custom_schedule_list = [];
      $custom_schedules = CustomEventSchedule::where('event_id',$event_id)
                                         #reymond ->where('user_id',$user->id)
                                          ->get();
      if($custom_schedules){
          foreach($custom_schedules as $custom_schedule){
              $custom_schedule_list[] = $custom_schedule->event_schedule_id;
          }
          if(count($custom_schedule_list)>0){
              $has_custom_schedule = true;
          }
      }
      // Sort and Group schedule by date
      if(isset($event->schedules)){
        $tmp_schedule = [];
        $tmp_raw_schedule = [];
        foreach($event->schedules as $key=>$schedule){
          if($schedule->price){
            $schedule->price=number_format((float)$schedule->price, 2, '.', '');
          }
          $schedule->googlemap="";
            $schedule->start_timestamp = strtotime($schedule->start_date);
            $schedule->end_timestamp = strtotime($schedule->end_date);
            $schedule->image = $this->encodeSpace(asset($schedule->image));
            if($schedule->itinerary_file){
              $schedule->itinerary_file = asset($schedule->itinerary_file);
            }
            $schedule->thumb_image = isset($schedule->thumb_image) ? $this->encodeSpace(asset($schedule->thumb_image)) : NULL;

            if($schedule->country)
            {
                $country = Country::where('id',$schedule->country)->first();
                $schedule->country_name = $country->name;
                $schedule->code = $country->code;
            }

            // filename
            $schedule->itinerary_filename = 'file.txt';
            if($schedule->itinerary_file != null){
                $itinerary = explode('/',$schedule->itinerary_file);
                $schedule->itinerary_filename = end($itinerary);
                $schedule->itinerary_filemime = \GuzzleHttp\Psr7\mimetype_from_filename($schedule->itinerary_filename);
            }

            // Auto select schedule if it's mandatory
            if($schedule->mandatory == 1){
                $schedule->selected = true;
            }

            //google maps part
            if($schedule->location =="Specify Address"){
              $addressmap = $schedule->location_address.",  ";
              if($schedule->cname){
                $addressmap.=$schedule->cname;
              }
              $addressmap.=$schedule->city." ".$schedule->state." ".$schedule->zip;
              if($schedule->country_name){
                $addressmap .= ", ".$schedule->country_name;
              }
              $schedule->googlemap=urlencode($addressmap);
            }
            
            $tmp_raw_schedule[] = $schedule;
            $tmp_schedule[date('Y-m-d',strtotime($schedule->start_date))][] = $schedule;

        }
       
        // Sort (grouped schedule by start_date) by start_timestamp
        $tmp_schedule_sorted = [];
        $tmp_custom_schedule_sorted = [];
        $tmp_custom_unselected_schedule_sorted = [];
        
        ksort($tmp_schedule);
        foreach($tmp_schedule as $key=>$schedules){
          
          $tmp_schedule_sorted_by_time = [];
          $tmp_custom_schedule_sorted_by_time = [];
          $tmp_custom_unselected_schedule_sorted_by_time = [];
          foreach($schedules as $schedule){
           
              if(isset($tmp_schedule_sorted_by_time[$schedule->start_timestamp])){
                $tmp_start_timestamp = $schedule->start_timestamp;
                do{
                    $tmp_start_timestamp = $tmp_start_timestamp + 1;
                }while(isset($tmp_schedule_sorted_by_time[$tmp_start_timestamp]));

                $tmp_schedule_sorted_by_time[$tmp_start_timestamp] = $schedule;

                // Check for custom schedule and uselected custom schedule
                if($has_custom_schedule){
                    if(in_array($schedule->id,$custom_schedule_list)){
                        $tmp_custom_schedule_sorted_by_time[$tmp_start_timestamp] = $schedule;
                    }else{
                        $tmp_custom_unselected_schedule_sorted_by_time[$tmp_start_timestamp] = $schedule;
                    }
                }else{
                    $tmp_custom_unselected_schedule_sorted_by_time[$tmp_start_timestamp] = $schedule;
                }
              }else{
                $tmp_schedule_sorted_by_time[$schedule->start_timestamp] = $schedule;

                // Check for custom schedule and uselected custom schedule
                if($has_custom_schedule){
                    if(in_array($schedule->id,$custom_schedule_list)){
                        $tmp_custom_schedule_sorted_by_time[$schedule->start_timestamp] = $schedule;
                    }else{
                        $tmp_custom_unselected_schedule_sorted_by_time[$schedule->start_timestamp] = $schedule;
                    }
                }else{
                    $tmp_custom_unselected_schedule_sorted_by_time[$schedule->start_timestamp] = $schedule;
                }
              }
            }
            $tmp_schedule_sorted[$key] = $tmp_schedule_sorted_by_time;
            if(count($tmp_custom_schedule_sorted_by_time) > 0){
                $tmp_custom_schedule_sorted[$key] = $tmp_custom_schedule_sorted_by_time;
            }
            if(count($tmp_custom_unselected_schedule_sorted_by_time) > 0){
                $tmp_custom_unselected_schedule_sorted[$key] = $tmp_custom_unselected_schedule_sorted_by_time;
            }
          }
          
          $event->schedules = $tmp_raw_schedule;
          $event->grouped_schedules = $tmp_schedule_sorted;
          $event->grouped_custom_schedules = $tmp_custom_schedule_sorted;
          if(count($tmp_custom_unselected_schedule_sorted)>0){
              $event->grouped_unselected_schedules = $tmp_custom_unselected_schedule_sorted;
          }
          $event->has_custom_schedule = $has_custom_schedule;          
      }  
      /*if($event->schedules && $event->schedules[0]->location =="Specify Address"){
        $addressmap = $event->schedules[0]->location_address.",  ";
        if($event->schedules[0]->cname){
          $addressmap.=$event->schedules[0]->cname;
        }
        $addressmap.=$event->schedules[0]->city." ".$event->schedules[0]->state." ".$event->schedules[0]->zip;
        if($event->schedules[0]->country_name){
          $addressmap .= ", ".$event->schedules[0]->country_name;
        }
        //$event->googlemap=urlencode($addressmap);
        //print $event->schedules[0]->location_address.", ".$event->schedules[0]->cname." ".$event->schedules[0]->city." ".$event->schedules[0]->state." ".$event->schedules[0]->zip." ".$event->schedules[0]->country_name;die();
      }*/
      $event->is_registered = $is_registered;
      $user = USER::where('id',$user->id)->first();
      return view('frontend.registration')->with('tabs', $tabs)->with('event',$event)->with('user',$user);
    }

    
    public function register(Request $request) {
      
      $input = $request->all();
      $firstnames = $request->input('firstname');      
      $lastname = $request->input('lastname');      
      $company = $request->input('company');      
      $title = $request->input('title');     
      $email = $request->input('email');     
      $phone = $request->input('phone');     
      $dietaryrestrictions = $request->input('dietaryrestrictions');      
      $schedules = $request->input('schedule');
      $event_id = $request->input('event_id');
      $amount = $request->input('overalltotal');
      $number_persons = $request->input('attendees_num');
      #print_r($input);die();
      //$user =\Auth::user()->id;
      
      //$payment_order = rand(1111111111,9999999999);
      $payment_order = $event_id."-".time();
      $user = \Auth::user()->id;
      $payment = new PaymentsRegistration;
      $payment->event_id = $event_id;
      $payment->user_id = $user;
      //$payment->payment_order = $payment_order;
      $payment->number_persons = $number_persons;
      $payment->amount = $amount;
      $payment->save();
      $payment_id = $payment->id;
      
      foreach($firstnames as $key => $firstname){
        $registration = new EventRegistrations;
        $registration->event_id = $event_id;
        $registration->user_id = $user;
        $registration->first_name = $firstname;
        $registration->last_name = $lastname[$key];
        $registration->company = $company[$key];
        $registration->title = $title[$key];
        $registration->email = $email[$key];
        $registration->telephone = $phone[$key];
        $registration->notes = $dietaryrestrictions[$key];
        $registration->save();
        $registration_id = $registration->id;
        if($schedules){
          foreach($schedules as $key => $schedule){
            $custom_event = new CustomEventSchedule;
            $custom_event->event_id = $event_id;
            $custom_event->event_schedule_id = $schedule;
            $custom_event->registration_id = $registration_id;
            $custom_event->payment_id = $payment_id;
            $custom_event->save();
          }
        }

        return redirect("/events/register-payment/$event_id/$payment_id");
      }

    }
    
   
    public function registerPayment($event_id, $payment_id){
      $event=Events::where('id',$event_id)->first();
      if(isset($event->cost_per_person)){
        $event->cost_per_person=preg_replace('/\s+/', '', str_replace("$",'',($event->cost_per_person)));
        $event->totalcost=$event->cost_per_person;
      }
      else{
        $event->cost_per_person = 0;
        $event->totalcost = 0;
      }
      $payment = PaymentsRegistration::where('id',$payment_id)->first();
     // DB::enableQueryLog();
   
      $custom_schedule = CustomEventSchedule::join('event_schedule', 'event_schedule.id', '=', 'custom_event_schedule.event_schedule_id')
        ->where('payment_id',$payment_id)->get();
      //$query = DB::getQueryLog();
      //print_r($query);die();
      // Sort and Group schedule by date
        $tmp_schedule = [];
        $tmp_raw_schedule = [];
        foreach($custom_schedule as $key=>$schedule){
            $schedule->start_timestamp = strtotime($schedule->start_date);
            $schedule->end_timestamp = strtotime($schedule->end_date);
            $schedule->image = $this->encodeSpace(asset($schedule->image));
            if($schedule->itinerary_file){
              $schedule->itinerary_file = asset($schedule->itinerary_file);
            }
            $schedule->thumb_image = isset($schedule->thumb_image) ? $this->encodeSpace(asset($schedule->thumb_image)) : NULL;

            if($schedule->country)
            {
                $country = Country::where('id',$schedule->country)->first();
                $schedule->country_name = $country->name;
            }

            // filename
            $schedule->itinerary_filename = 'file.txt';
            if($schedule->itinerary_file != null){
                $itinerary = explode('/',$schedule->itinerary_file);
                $schedule->itinerary_filename = end($itinerary);
                $schedule->itinerary_filemime = \GuzzleHttp\Psr7\mimetype_from_filename($schedule->itinerary_filename);
            }

            // Auto select schedule if it's mandatory
            if($schedule->mandatory == 1){
                $schedule->selected = true;
            }

            //google maps part
            if($schedule->location =="Specify Address"){
              $addressmap = $schedule->location_address.",  ";
              if($schedule->cname){
                $addressmap.=$schedule->cname;
              }
              $addressmap.=$schedule->city." ".$schedule->state." ".$schedule->zip;
              if($schedule->country_name){
                $addressmap .= ", ".$schedule->country_name;
              }
              $schedule->googlemap=urlencode($addressmap);
            }
            
            $tmp_raw_schedule[] = $schedule;
            $tmp_schedule[date('Y-m-d',strtotime($schedule->start_date))][] = $schedule;

        }
        
        // Sort (grouped schedule by start_date) by start_timestamp
        $tmp_schedule_sorted = [];
        $tmp_custom_schedule_sorted = [];
        $tmp_custom_unselected_schedule_sorted = [];
        
        ksort($tmp_schedule);
        foreach($tmp_schedule as $key=>$schedules){
          $tmp_schedule_sorted_by_time = [];
          $tmp_custom_schedule_sorted_by_time = [];
          $tmp_custom_unselected_schedule_sorted_by_time = [];
          foreach($schedules as $schedule){
              if(isset($tmp_schedule_sorted_by_time[$schedule->start_timestamp])){
                $tmp_start_timestamp = $schedule->start_timestamp;
                do{
                    $tmp_start_timestamp = $tmp_start_timestamp + 1;
                }while(isset($tmp_schedule_sorted_by_time[$tmp_start_timestamp]));

                $tmp_schedule_sorted_by_time[$tmp_start_timestamp] = $schedule;

                
              }else{
                $tmp_schedule_sorted_by_time[$schedule->start_timestamp] = $schedule;

                
              }
            }
            $tmp_schedule_sorted[$key] = $tmp_schedule_sorted_by_time;
            if(count($tmp_custom_schedule_sorted_by_time) > 0){
                $tmp_custom_schedule_sorted[$key] = $tmp_custom_schedule_sorted_by_time;
            }
            if(count($tmp_custom_unselected_schedule_sorted_by_time) > 0){
                $tmp_custom_unselected_schedule_sorted[$key] = $tmp_custom_unselected_schedule_sorted_by_time;
            }
          }
          $custom_schedule->schedules = $tmp_raw_schedule;
          $custom_schedule->grouped_schedules = $tmp_schedule_sorted;
          $custom_schedule->grouped_custom_schedules = $tmp_custom_schedule_sorted;
          if(count($tmp_custom_unselected_schedule_sorted)>0){
              $custom_schedule->grouped_unselected_schedules = $tmp_custom_unselected_schedule_sorted;
          }

      $current_year = date('Y');
      $date_range = range($current_year, $current_year+5);
      $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
      return view('frontend.registration-payment')->with('tabs', $tabs)
      ->with('custom_schedule', $custom_schedule)
      ->with('event', $event)
      ->with('payment', $payment)
      ->with('date_range',$date_range);
  }
    public function registercomplete(Request $request) {
      
        $user = Auth::user();
        $input = $request->all();
        //echo $user->email;die();
        if($request->input('payment_method')=="check"){
          $order_number = $request->input('Purchase-Order-Number');
        }else{
          $order_number = $request->input('payment_registration_id')."-".time();
        }
        // $payment = PaymentsRegistration::where('id',$order_number)->first();
        $payment = PaymentsRegistration::where('id',$request->input('payment_registration_id'))->first();
        $amount = $payment->amount ?? 0;
        $card_number = $request->input('Card-Number');
        $name = $request->input('Name-On-Card');
        $name =explode(" ",$name);
        $firstname=$name[0] ?? null;
        $lastname=$name[1] ?? null;
        $expdate = $request->input('Expiration-Date-Month').substr($request->input('Expiration-Date-Year'), -2);//$request->input('Expiration-Date');
        $cvv = $request->input('CVV-CVC');
        $address = $request->input('Billing-Address');
        $aptsuite = $request->input('Apt-Suite');
        $city = $request->input('Billing-City');
        $state = $request->input('Billing-State');
        $zip = $request->input('Billing-Zip');
        $email = $user->email;
        if($amount > 0){
            $payment->business_check_number = $request->input('Business-Check-Number');
            if($payment){ 
                /*if($request->input('Business-Check-Number') != null
                && $request->input('Card-Number') == null){*/
                if($request->input('payment_method')=="check"){
                    $response = ['responsetext'=>'SUCCESS'];
                    $payment->business_check_number = $request->input('Business-Check-Number');
                }
                else{
                    $gw = new SecurePaymentIntegration;
                    //$gw->setLogin(env( 'PAYMENT_USERNAME' ), env( 'PAYMENT_PASSWORD' ));
                    $gw->setLogin(env( 'PAYMENT_KEY' )); //6457Thfj624V5r7WUwc5v6a68Zsd6YEm
                    
                    /* Billing part */
                    /*setBilling($firstname, $lastname, $company, $address1, $address2, $city, $state, $zip, $country, $phone, $fax, $email, $website)  */
                    $gw->setBilling($firstname,$lastname,"na",$address,"na", $city, 
                      $state, $zip,"US","na","na",$email,
                    "na");
                    
                    /* shipping part set to na if not applcibable */
                    /* setShipping($firstname, $lastname, $company, $address1, $address2, $city, $state, $zip, $country, $email) */
                    $gw->setShipping($firstname,$lastname,"na",$address,"na", $city, 
                      $state, $zip,"US",$email);
              
                    /* Place Order */
                    /* setOrder($orderid, $orderdescription, $tax, $shipping, $ponumber, $ipaddress) */
                    $gw->setOrder($order_number,"Havtech Payment","0", "0","na" ,"65.192.14.10");
              
                    /* transaction part */
                    /* doSale($amount, $ccnumber, $ccexp, $cvv="")  */
                    //$r = $gw->doSale("1.00","4111111111111111","1010");//add ccv at the end
                    $r = $gw->doSale($amount,$card_number,$expdate, $cvv);
                    $response = $gw->responses;
                   // print_r( $response);
                    //print($gw->responses['transactionid']);
                    //print $response->responsetext;
                    $payment->transaction_id = $response['transactionid'];
                    $payment->order_id = $response['orderid'];
                    $payment->payment_status = $response['responsetext'];
                    $payment->magothy_response = json_encode($response);
                  }
                $payment->save();
              }else{

                  $response = [];
                  $response['responsetext'] = false;
              }
              //echo $response['responsetext']; 
              if(str_replace(' ', '',strtoupper($response['responsetext']))=="SUCCESS" ||$response['responsetext']=="SUCCESS" || $response['responsetext']=="Approval" || $response['responsetext']=="APPROVAL"){
                $values['payment_status']="1";
                $custmevents=CustomEventSchedule::where('payment_id',$payment->id)->update($values);
                $values2['completed']="1";
                $reg=EventRegistrations::where('user_id',$user->id)
                              ->where('event_id',$payment->event_id)->update($values2);
                
                $event_id=$payment->event_id;
                //echo $event_id;die();
                $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
                // email register with eent calendar invite
                $registration_event = Events::where('id',$event_id)->first();
                
                 //print_r( $registration_event);die();
                if($registration_event){
                    if(isset($registration_event->cost_per_person)){
                      $amount=preg_replace('/\s+/', '', str_replace("$",'',($registration_event->cost_per_person)));
                    }
                    else{
                      $amount = 0;
                    }
                    $time = null;
                    $first_sched = date("Y-m-d");
                    $last_sched = date("Y-m-d");
                    // check first event schedule
                    //change to email all events schedules
                    $custmeventz=CustomEventSchedule::leftjoin('event_schedule','event_schedule.id','event_schedule_id')->where('payment_id',$payment->id)->get();
                    if($custmeventz){
                      foreach($custmeventz as $schedule){
                        $first_sched = $schedule->start_date;
                        $last_sched = $schedule->end_date;
                        $schedule_title = $schedule->title;
                        if($schedule->location=="Room Number"){
                            $address=$schedule->room_number;
                        }
                        else{
                          if($schedule->location_address){
                            $address=$schedule->location_address.", ".$schedule->cname." ".$schedule->city." ".$schedule->state." ".$schedule->zip." ".$schedule->country_name;
                          }
                          else{
                            $address="N/A";
                          }
                            
                        }
                        if(strtotime($first_sched) > strtotime($last_sched)){
                          
                          $last_sched = $first_sched ;
                        }
                        //echo $first_sched."=".$last_sched;echo strtotime($first_sched) ."<". strtotime($last_sched)
                        
                      // die();
                        $calendar_description = $registration_event->custom_calendar_message ?? '';
                        
                        $calendarLink =  Link::create(
                              $registration_event->event_name,
                              DateTime::createFromFormat('Y-m-d H:i:s',$first_sched),
                              DateTime::createFromFormat('Y-m-d H:i:s',$last_sched))
                              ->description($calendar_description)
                              ->address($address);

                      
                        $time =  date("h:i:A",strtotime($schedule->start_date));
                      
                        $event_registration = EventRegistrations::where('user_id',$user->id)
                                                                ->where('event_id',$registration_event->event_id)
                                                                ->orderBy('id','asc')
                                                                ->first();
                        $to_email = $event_registration->email ?? $user->email;
                        $to_name = $user->first_name.' '.$user->last_name;
                        $ics=$calendarLink->ics();
                        $encodedData = str_replace(' ','+',$ics);
                        $decodedData = base64_decode($encodedData);
                        $custom_calendar_message = strip_tags(str_replace('</p>', "</p>\r\n", $registration_event->custom_calendar_message));

                        //date_default_timezone_set('US/Eastern');
                        $start = date("Ymd\THis",strtotime($first_sched));
                        $end = date("Ymd\THis",strtotime($last_sched));

                          //render ICS file
                          $vCalendar = new \Eluceo\iCal\Component\Calendar('www.havtechevents.com');
                          $vEvent = new \Eluceo\iCal\Component\Event();

                          $tz  = 'America/New_York';
                          $dtz = new \DateTimeZone($tz);
                          date_default_timezone_set($tz);

                          $vEvent->setDtStart(new \DateTime($start, new \DateTimeZone('America/New_York')))
                                ->setDtEnd(new \DateTime($end, new \DateTimeZone('America/New_York')))
                                ->setLocation($address)
                                //->setUseTimezone(true)
                                ->setSummary($registration_event->event_name)
                                ->setDescription(str_replace("&nbsp;"," ",$custom_calendar_message))
                                ->setDescriptionHTML($registration_event->custom_calendar_message);
                          $vCalendar->addComponent($vEvent);
                          
                          $startz = date("F j, Y H:i:s",strtotime($first_sched));
                          $endz = date("F j, Y  H:i:s",strtotime($last_sched));

                          $starttime = date("g:i a",strtotime($first_sched));
                          $endtime = date("g:i a",strtotime($last_sched));
                          $ics = $vCalendar->render();

                          //$icsname = str_replace(' ','',$registration_event->event_name);
                          $icsname = 'HavtechEvent-'.$registration_event->event_id;
                          Storage::disk('public')->put("ics/".$icsname.'.ics', $ics);
                          
                          $ics_link= Storage::disk('public')->url("ics/".$icsname.'.ics');
                          $data = [
                              'event_name' => $registration_event->event_name,
                              'schedule_title' => $schedule_title,
                              'date' => date("l, F j, Y",strtotime($registration_event->start_date)),
                              'enddate' => date("l, F j, Y",strtotime($registration_event->end_date)),
                              'time' => $time,
                              'starttime' => $starttime,
                              'endtime' => $endtime,
                              'cost' => $amount,
                              'google_calendar_url' => $calendarLink->google(),
                              'outlook_calendar_url' => $ics_link,
                              'ics' => $ics_link,
                          ];
                          $event_name=$registration_event->event_name;

                          //$to_email = 'mark@mojo.biz';
                          //$to_email = 'mojodevtesting@gmail.com';  ." [".$startz."-".$endz."] "
                          Mail::send('emails.eventregistered2',$data, function($message) use ($to_email, $to_name, $ics,$event_name,$startz,$endz) {
                              $message->to($to_email, $to_name)
                                      ->subject('Thank you for registering ('.$event_name.' '.$startz.'-'.$endz.')');
                              $message->from('marketing@havtech.com','Havtech Events');
                              $message->attachData($ics, 'Havtech Event.ics', ['mime' => 'data:text charset=utf8']);
                          });

                      }
                    }
                    
                      return view('frontend.registration-complete')->with('tabs', $tabs)->with('event_id', $payment->event_id)->with('amount',$amount);
               }
             else{
                return back();
              }
          }
        }
        /* send email for free events */
        else{
              $values['payment_status']="1";
              $custmevents=CustomEventSchedule::where('payment_id',$payment->id)->update($values);
              $values2['completed']="1";
              $reg=EventRegistrations::where('user_id',$user->id)
                            ->where('event_id',$payment->event_id)->update($values2);
              $event_id=$payment->event_id;
              $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
              // email register with eent calendar invite
              $registration_event = Events::where('id',$payment->event_id)->first();
              
              // print $registration_event;die();
              if($registration_event){
                if(isset($registration_event->cost_per_person)){
                  $amount=preg_replace('/\s+/', '', str_replace("$",'',($registration_event->cost_per_person)));
                }
                else{
                  $amount = 0;
                }
                $time = null;
                $first_sched = date("Y-m-d");
                $last_sched = date("Y-m-d");
                // check first event schedule
                //change to email all events schedules
                $custmeventz=CustomEventSchedule::leftjoin('event_schedule','event_schedule.id','event_schedule_id')->where('payment_id',$payment->id)->get();
                if($custmeventz){
                  foreach($custmeventz as $schedule){
                    $first_sched = $schedule->start_date;
                    $last_sched = $schedule->end_date;
                    $schedule_title = $schedule->title;
                    if($schedule->location=="Room Number"){
                      $address=$schedule->room_number;
                    }
                    else{
                      if($schedule->location_address){
                        $address=$schedule->location_address.", ".$schedule->cname." ".$schedule->city." ".$schedule->state." ".$schedule->zip." ".$schedule->country_name;
                      }
                      else{
                        $address="N/A";
                      }
                        
                    }
                    if(strtotime($first_sched) > strtotime($last_sched)){
                      $last_sched = $first_sched ;
                    }

                    $calendar_description = $registration_event->custom_calendar_message ?? '';

                    $calendarLink =  Link::create(
                          $registration_event->event_name,
                          DateTime::createFromFormat('Y-m-d H:i:s',$first_sched),
                          DateTime::createFromFormat('Y-m-d H:i:s',$last_sched))
                          ->description($calendar_description)
                          ->address($address);

                  
                    $time =  date("h:i:A",strtotime($schedule->start_date));
                  
                    $event_registration = EventRegistrations::where('user_id',$user->id)
                                                            ->where('event_id',$registration_event->event_id)
                                                            ->orderBy('id','asc')
                                                            ->first();
                    $to_email = $event_registration->email ?? $user->email;
                    $to_name = $user->first_name.' '.$user->last_name;
                    $ics=$calendarLink->ics();
                    $encodedData = str_replace(' ','+',$ics);
                    $decodedData = base64_decode($encodedData);
                    $custom_calendar_message = strip_tags(str_replace('</p>', "</p>\r\n", $registration_event->custom_calendar_message));

                    //date_default_timezone_set('US/Eastern');
                    $start = date("Ymd\THis",strtotime($first_sched));
                    $end = date("Ymd\THis",strtotime($last_sched));

                      //render ICS file
                      $vCalendar = new \Eluceo\iCal\Component\Calendar('www.havtechevents.com');
                      $vEvent = new \Eluceo\iCal\Component\Event();

                      $tz  = 'America/New_York';
                      $dtz = new \DateTimeZone($tz);
                      date_default_timezone_set($tz);

                      $vEvent->setDtStart(new \DateTime($start, new \DateTimeZone('America/New_York')))
                            ->setDtEnd(new \DateTime($end, new \DateTimeZone('America/New_York')))
                            ->setLocation($address)
                            //->setUseTimezone(true)
                            ->setSummary($registration_event->event_name)
                            ->setDescription(str_replace("&nbsp;"," ",$custom_calendar_message))
                            ->setDescriptionHTML($registration_event->custom_calendar_message);
                      $vCalendar->addComponent($vEvent);
                      $ics = $vCalendar->render();
                      $startz = date("F j, Y H:i:s",strtotime($first_sched));
                      $endz = date("F j, Y  H:i:s",strtotime($last_sched));

                      $starttime = date("g:i a",strtotime($first_sched));
                      $endtime = date("g:i a",strtotime($last_sched));

                      //$icsname = str_replace(' ','',$registration_event->event_name);
                      $icsname = 'HavtechEvent-'.$registration_event->event_id;
                      Storage::disk('public')->put("ics/".$icsname.'.ics', $ics);
                      
                      $ics_link= Storage::disk('public')->url("ics/".$icsname.'.ics');
                      $data = [
                          'event_name' => $registration_event->event_name,
                          'schedule_title' => $schedule_title,
                          'date' => date("l, F j, Y",strtotime($registration_event->start_date)),
                          'enddate' => date("l, F j, Y",strtotime($registration_event->end_date)),
                          'time' => $time,
                          'starttime' => $starttime,
                          'endtime' => $endtime,
                          'cost' => $amount,
                          'google_calendar_url' => $calendarLink->google(),
                          'outlook_calendar_url' => $ics_link,
                          'ics' => $ics_link,
                      ];
                      $event_name=$registration_event->event_name;

                      //$to_email = 'mark@mojo.biz';
                      //$to_email = 'mojodevtesting@gmail.com';
                      Mail::send('emails.eventregistered2',$data, function($message) use ($to_email, $to_name, $ics,$event_name,$startz,$endz) {
                          $message->to($to_email, $to_name)
                              ->subject('Thank you for registering ('.$event_name.' '.$startz.'-'.$endz.')');
                          $message->from('marketing@havtech.com','Havtech Events');
                          $message->attachData($ics, 'Havtech Event.ics', ['mime' => 'data:text charset=utf8']);
                      });

                  }
                }
                //if no custom events
                else{
                    $first_schedule = EventSchedule::where('event_id',$registration_event->id)
                                  ->orderBy('start_date','asc')
                                  ->first();
                    // check first event schedule
                    $last_schedule = EventSchedule::where('event_id',$registration_event->id)
                                    ->orderBy('end_date','desc')
                                    ->first();
                    if($first_schedule && strlen($first_schedule->start_date)){
                        $first_sched = $first_schedule->start_date;
                        $last_sched = $last_schedule->end_date;
                        if($first_schedule->location=="Room Number"){
                            $address=$first_schedule->room_number;
                        }
                        else{
                            $address=$first_schedule->location_address.", ".$first_schedule->cname." ".$first_schedule->city." ".$first_schedule->state." ".$first_schedule->zip." ".$first_schedule->country_name;
                        }
                    }
                    else{
                        if($registration_event->start_date){
                            $first_sched = $registration_event->start_date;
                            $last_sched = $registration_event->end_date;
                            $address = "";
                        }
                        else{
                            $first_sched = date("Y-m-d");
                            $last_sched = date("Y-m-d");
                        }
                    }
                   
                      if($address == ""){
                        $address="N/A";
                        
                      }
                    if(strtotime($first_sched) > strtotime($last_sched)){
                      $last_sched = $first_sched ;
                    }
                    $calendar_description = $registration_event->custom_calendar_message ?? '';

                    $calendarLink =  Link::create(
                            $registration_event->event_name,
                            DateTime::createFromFormat('Y-m-d H:i:s',$first_sched),
                            DateTime::createFromFormat('Y-m-d H:i:s',$last_sched))
                            ->description($calendar_description)
                            ->address($address);

                    if($first_schedule){
                        $time =  date("h:i:A",strtotime($first_schedule->start_date));
                    }
                    $event_registration = EventRegistrations::where('user_id',$user->id)
                                                            ->where('event_id',$registration_event->event_id)
                                                            ->orderBy('id','asc')
                                                            ->first();
                    $to_email = $event_registration->email ?? $user->email;
                    $to_name = $user->first_name.' '.$user->last_name;
                    $ics=$calendarLink->ics();
                    $encodedData = str_replace(' ','+',$ics);
                    $decodedData = base64_decode($encodedData);
                    $custom_calendar_message = strip_tags(str_replace('</p>', "</p>\r\n", $registration_event->custom_calendar_message));

                    //date_default_timezone_set('US/Eastern');
                    $start = date("Ymd\THis",strtotime($first_sched));
                    $end = date("Ymd\THis",strtotime($last_sched));

                      //render ICS file
                      $vCalendar = new \Eluceo\iCal\Component\Calendar('www.havtechevents.com');
                      $vEvent = new \Eluceo\iCal\Component\Event();

                      $tz  = 'America/New_York';
                      $dtz = new \DateTimeZone($tz);
                      date_default_timezone_set($tz);

                      $vEvent->setDtStart(new \DateTime($start, new \DateTimeZone('America/New_York')))
                            ->setDtEnd(new \DateTime($end, new \DateTimeZone('America/New_York')))
                            ->setLocation($address)
                            //->setUseTimezone(true)
                            ->setSummary($registration_event->event_name)
                            ->setDescription(str_replace("&nbsp;"," ",$custom_calendar_message))
                            ->setDescriptionHTML($registration_event->custom_calendar_message);
                      $vCalendar->addComponent($vEvent);
                      $ics = $vCalendar->render();


                      //$icsname = str_replace(' ','',$registration_event->event_name);
                      $icsname = 'HavtechEvent-'.$registration_event->event_id;
                      Storage::disk('public')->put("ics/".$icsname.'.ics', $ics);
                      $ics_link= Storage::disk('public')->url("ics/".$icsname.'.ics');
                      $data = [
                          'event_name' => $registration_event->event_name,
                          'date' => date("l, F j, Y",strtotime($registration_event->start_date)),
                          'enddate' => date("l, F j, Y",strtotime($registration_event->end_date)),
                          'time' => $time,
                          'cost' => $amount,
                          'google_calendar_url' => $calendarLink->google(),
                          'outlook_calendar_url' => $ics_link,
                          'ics' => $ics_link,
                      ];
                      $event_name=$registration_event->event_name;

                      //$to_email = 'mark@mojo.biz';
                      //$to_email = 'mojodevtesting@gmail.com';
                      Mail::send('emails.eventregistered',$data, function($message) use ($to_email, $to_name, $ics,$event_name) {
                          $message->to($to_email, $to_name)
                                  ->subject('Thank you for registering ('.$event_name.')');
                          $message->from('marketing@havtech.com','Havtech Events');
                          $message->attachData($ics, 'Havtech Event.ics', ['mime' => 'data:text charset=utf8']);
                      });
                }
                return view('frontend.registration-complete')->with('tabs', $tabs)->with('event_id', $event_id)->with('amount',$amount);
              }
              else{
                return back();
              }
        }
    }

    public function contact(Request $request) {
      MetaTag::set('title', 'Havtech Events Hub - Contact');
      $userz =\Auth::user();
      $user = [];
      if($userz){
        $user = USER::where('id',$userz->id)->first();
      }
      return view('frontend.contact')->with('user',$user);
    }

    public function about(Request $request) {
      MetaTag::set('title', 'Havtech Events Hub - About');
      return view('frontend.about');
    }

    public function mission(Request $request) {
      MetaTag::set('title', 'Havtech Events Hub - Mission');
      return view('frontend.mission');
    }

    public function faqs(Request $request) {
      MetaTag::set('title', 'Havtech Events Hub - FAQs');
      return view('frontend.faqs');
    }

    public function downloadMobileApp(Request $request) {
      MetaTag::set('title', 'Havtech Events Hub - Download App');
      return view('frontend.downloadmobileapp');
    }

    public function programs(Request $request) {
      MetaTag::set('title', 'Havtech Events Hub - Programs');
      return view('frontend.programs');
    }

    public function eventtypes(Request $request) {
      MetaTag::set('title', 'Havtech Events Hub - Event Types');
      return view('frontend.eventtypes');
    }

    public function contactSend(Request $request){
      // Validate
      $validate = $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'company' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'title' => 'required',
        'message' => 'required'
      ]);

      $to_email = env('CONTACT_EMAIL');
      $to_name = env('CONTACT_EMAIL_NAME');
      $data = [
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'company' => $request->company,
        'email' => $request->email,
        'phone' => $request->phone,
        'title' => $request->title,
        'message_body' => $request->message
      ];

      
      Mail::send('emails.contact', $data, function($message) use ($to_email, $to_name) {
          $message->to($to_email, $to_name)
                  ->subject('Havtech Events Hub Contact Form');
          $message->from('marketing@havtech.com','Havtech Contact Form'); //support@havtech.com
      });

      return redirect()->route('contact')->with('status', 'saved');
    }
  function getUser(){
    $user =\Auth::user();
      $userz="";
      if($user){
        return $user->id;
      }
      else{
        return $userz;
      }
  }
  function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
  }
}
