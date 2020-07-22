<?php

namespace App\Http\Controllers;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
use Illuminate\Support\Facades\Hash;
use Spatie\CalendarLinks\Link;
use DB;
use DateTime;
use MetaTag;
use Storage;

class ApiController extends Controller
{

    private $photos_path; //path of event photos

    public function __construct()
    {
        $this->photos_path = public_path('/uploads/photos');
    }

    /**
     * Get the authenticated User
     * 
     * @param Request $request
     * @return String user object
     *  JSON encoded string details about the user
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Get all events or events by type
     * 
     * @param Request $requestuser()
     * @param Integer $type
     *    $type = event type
     *    - 1 = General Events
     *    - 2 = Special Events
     *    - 3 = All Upcoming Events
     *    - 4 = Archived Events
     * 
     * @return String event object
     *  JSON encoded string of all events
     */
    public function events(Request $request,$type = null){
        $user = $request->user();
        if($type == null){

            // Select All Invited Special Events and General Events
            $event_array = [];
            $invited_events = EventAttendees::where('user_id',$user->id)->where('email_sent_approved',1)->pluck('event_id')->toArray();
            $invited_events = Events::with('event_type','attendees')
                            ->where('event_types',2)
                            ->where('visibility_app','Published')
                            ->whereIn('id',$invited_events)
                            ->orderBy('id','desc')
                            ->get();
            
            $general_events = Events::with('event_type','attendees')
                            ->where('event_types',1)
                            ->where('visibility_app','Published')
                            ->where(function($q) {
                                $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                                  ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
                             })
                            ->orderBy('id','desc')
                            ->get();

            foreach($invited_events as $key=>$value){
                if( date('M d', strtotime($value->start_date)) == date('M d', strtotime($value->end_date))){
                    $event_daterange = date('M d, Y', strtotime($value->end_date));
                }
                else{
                    $event_daterange = date('M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date));
                }
                
                $event_array[] = [
                    'id' => $value->id,
                    'start_date' => $value->start_date,
                    'end_date' => $value->end_date,
                    'event_name' => $value->event_name,
                    'type' => $value->event_type->description,
                    'event_date_range' => $event_daterange,
                    'image' => (isset($value->image) ? $this->encodeSpace(asset($value->image)) : NULL ),
                    'start_timestamp' => strtotime($value->start_date)
                ];
            }

            foreach($general_events as $key=>$value){
                if( date('M d', strtotime($value->start_date)) == date('M d', strtotime($value->end_date))){
                    $event_daterange = date('M d, Y', strtotime($value->end_date));
                }
                else{
                    $event_daterange = date('M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date));
                }
                $event_array[] = [
                    'id' => $value->id,
                    'start_date' => $value->start_date,
                    'end_date' => $value->end_date,
                    'event_name' => $value->event_name,
                    'type' => $value->event_type->description,
                    'event_date_range' => $event_daterange,
                    'image' => (isset($value->image) ? $this->encodeSpace(asset($value->image)) : NULL ),
                    'start_timestamp' => strtotime($value->start_date)
                ];
            }

            usort($event_array, function ($event1, $event2) {
                return $event2['start_timestamp'] <=> $event1['start_timestamp'];
            });
            
            return response()->json($event_array);

        }elseif($type == 2){
            // Select all invited special events
            $invited_events = EventAttendees::where('user_id',$user->id)->where('email_sent_approved',1)->pluck('event_id')->toArray();
            $events = Events::with('event_type','attendees')
                            ->where('event_types',$type)
                            ->where('visibility_app','Published')
                            ->whereIn('id',$invited_events)
                            ->where(function($q) {
                                $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                                    ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
                            })
                            ->orderBy('start_date','desc')
                            ->get();
        }elseif($type == 99){
           // DB::enableQueryLog();
            // Select all invited special events
            $events = Events::with('event_type')
                            ->select('events.*')
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
                            /*
                            ->where(function($q)use($user) {
                                $q->where('event_registrations.user_id',"=",$user->id);
                                   # ->orWhere('event_types',"2");
                            })*/
                            ->where('visibility_app','Published')
                            ->where('event_registrations.completed','1')
                            ->where(function($q) {
                                $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                                    ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
                            })
                            ->where(function($q) {
                                $q->where(function($q) {
                                    $q->whereNotNull('event_attendees.id')
                                        ->where('event_types','2');
                                    })
                                ->orwhereNotNull('event_registrations.id');
                            })
                            ->groupBy('events.id')
                            ->orderBy('start_date','desc')
                            ->get();
                           //$query = DB::getQueryLog();
                            //return($query);die();
        }else{
            // Select all general events
            $events = Events::with('event_type','attendees')
                            ->where('event_types',$type)
                            ->where('visibility_app','Published')
                            ->where(function($q) {
                                $q->where('start_date', '>=', date('Y-m-d 00:00:00'))
                                  ->orWhere('end_date', '>=', date('Y-m-d 00:00:00'));
                             })

                            ->orderBy('start_date','desc')
                            ->get();
        }

        $event_array = [];
        foreach($events as $key=>$value){
            if( date('M d', strtotime($value->start_date)) == date('M d', strtotime($value->end_date))){
                $event_daterange = date('M d, Y', strtotime($value->end_date));
            }
            else{
                $event_daterange = date('M d', strtotime($value->start_date)) . ' - ' . date('M d, Y', strtotime($value->end_date));
            }
            $event_array[] = [
                'id' => $value->id,
                'start_date' => $value->start_date,
                'end_date' => $value->end_date,
                'event_name' => $value->event_name,
                'type' => rtrim($value->event_type->description, "s"),
                'event_date_range' => $event_daterange,
                'image' => (isset($value->image) ? $this->encodeSpace(asset($value->image)) : NULL )
            ];
        }
        if($event_array){
            $event_array = array_reverse($event_array);
        }
        return response()->json($event_array);
    }

    /**
     * Get Event data based on the provided id
     * 
     * @param Request $request
     * @param Integer $event_id
     *   The id of the event
     * 
     * @return String event object
     *  JSON encoded string of the event
     */
    public function event(Request $request,$event_id){
        $event=Events::with('faqs','schedules','accomodations','dining','dining.type','transportations','hosts','maps','approved_photos')->where('id',$event_id)->first();
        $user = $request->user();

        $is_registered = false;
        $event_registration = EventRegistrations::where('user_id',$user->id)
                                                ->where('event_id',$event_id)
                                                ->where('completed','1')
                                                ->first();
                                                
        if($event_registration){
            $is_registered = true;
        }

        if($event){
            if($event->cost_per_person){
                $cost=preg_replace('/\s+/', '', str_replace("$",'',($event->cost_per_person)));
                $event->cost_per_person_display = "$".number_format($cost, 2, '.', '');
                $event->cost_per_person =$cost;
            }
            else{
                $event->cost_per_person_display ="FREE";
                $event->cost_per_person =0;
            }
            if( date('M d', strtotime($event->start_date)) == date('M d', strtotime($event->end_date))){
                $event_daterange = date('M d, Y', strtotime($event->end_date));
            }
            else{
                $event_daterange = date('M d', strtotime($event->start_date)) . ' - ' . date('M d, Y', strtotime($event->end_date));
            }
            $event->event_date_range =$event_daterange;

            if(isset($event->image)){
                $event->image = $this->encodeSpace(asset($event->image));
            }

            if(isset($event->overview_file)){
                $event->overview_file = $this->encodeSpace(asset($event->overview_file));
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
                                                ->where('user_id',$user->id)
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
                if(count($event->schedules)<=1){
                    $display_current_schedule = false;
                }
                else{
                    $display_current_schedule = true;
                }
                
                $tmp_schedule = [];
                $tmp_raw_schedule = [];
                foreach($event->schedules as $key=>$schedule){
                    if($schedule->price){
                        $schedule->price=number_format((float)$schedule->price, 2, '.', '');
                      }
                    $schedule->start_timestamp = strtotime($schedule->start_date);
                    $schedule->end_timestamp = strtotime($schedule->end_date);
                    $schedule->image = $this->encodeSpace(asset($schedule->image));
                    $schedule->itinerary_file = asset($schedule->itinerary_file);
                    $schedule->thumb_image = isset($schedule->thumb_image) ? $this->encodeSpace(asset($schedule->thumb_image)) : NULL;
                    
                    if($schedule->price){

                    }
                    else{
                        $schedule->price=0;
                    }

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

                    $tmp_raw_schedule[] = $schedule;
                    $tmp_schedule[date('m-d-y',strtotime($schedule->start_date))][] = $schedule;

                }
                
                // Sort (grouped schedule by start_date) by start_timestamp
                $tmp_schedule_sorted = [];
                $tmp_custom_schedule_sorted = [];
                $tmp_custom_unselected_schedule_sorted = [];

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
            
            // Sort and Group dining
            if(isset($event->dining)){
                $tmp_dining = [];
                foreach($event->dining as $key=>$dining){
                    $dining->start_timestamp = strtotime($dining->meal_date);
                    $dining->type_name = $dining->type->name ?? NULL;
                    $dining->image = $this->encodeSpace(asset($dining->image));
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
                    $accomodation->image = $this->encodeSpace(asset($accomodation->image));
                    $tmp_accomodations[] = $accomodation;
                    if($accomodation->country)
                    {
                        $country = Country::where('id',$accomodation->country)->first();
                        $accomodation->country = $country->name;
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
                if(count($event->schedules)<=1){
                    $display_current_schedule = false;
                }
                else{
                    $display_current_schedule = true;
                }
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

                $event->display_current_schedule = $display_current_schedule;
            }

            if($event->current_schedule == null){
                // remove upcoming event $event->current_schedule = $upcoming_schedule;
            }

            $event->is_registered = $is_registered;
            $event->event_host_description = $event->event_host_description;

            // Check if event type is Learning Institute
            // I user is registered show the tabs
            // Otherwise hide the tabs
            if($event->event_types == 3){
                // Check for user registrations
                if(!$is_registered){
                    unset($event->schedule_image);
                    unset($event->accomodations_image);
                    unset($event->dining_image);
                    unset($event->transportation_image);
                    unset($event->map);
                    unset($event->travelhost);
                }
            }
            
        }
        return response()->json($event);
    }
    
    /**
    * Saving images uploaded through XHR Request.
    *
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function eventPhotosUpload(Request $request){
        $user = $request->user();
        $status = false;
        if(count($request->images) > 0){

            if (!is_dir($this->photos_path)) {
                mkdir($this->photos_path, 0777);
            }

            foreach($request->images as $image){
                $image = str_replace('data:image/png;base64,', '', $image);
                $save_name = sha1(date('YmdHis') . str_random(30)).'.'.'png';
                $orig_save_name = $save_name . '_orig'.'.'.'png';
                $resize_name = sha1(date('YmdHis') . str_random(30)).'.'.'png';
                $decoded_image = base64_decode($image);

                Image::make($decoded_image)
                ->save($this->photos_path . '/' . $orig_save_name)
                ->destroy();
                
                Image::make($decoded_image)
                ->resize(640, 480, function ($constraints) {
                    $constraints->aspectRatio();
                })
                ->save($this->photos_path . '/' . $save_name)
                ->destroy();

                Image::make($decoded_image)
                ->resize(100, null, function ($constraints) {
                    $constraints->aspectRatio();
                })
                ->save($this->photos_path . '/' . $resize_name)
                ->destroy();

                $event_photo = new EventPhotos();
                $event_photo->event_id=$request->event_id;
                $event_photo->filename = $save_name;
                $event_photo->resized_name = $resize_name;            
                $event_photo->original_name = $save_name;
                $event_photo->photo = $orig_save_name;    
                $event_photo->user_id =$user->id;
                $event_photo->save();
            }

            $status = true;
        }

        
        return response()->json(['success'=>$status]);
    }

    /**
     * Get user notifications
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return String event object
     *  JSON encoded string of the notifications
     */
    public function getUserNotifications(Request $request){
        $user = $request->user();
        $user_notifications = NotificationRecipients::with('notification')
                            ->where('user_id',$user->id)
                            ->orderBy('id','desc')
                            ->get();
        $read_notifications = [];
        $unread_notifications = [];

        foreach($user_notifications as $notification){
            if($notification->notification['is_sent'] == 1){
                if($notification->is_read){
                    $read_notifications[] = $notification;
                }else{
                    $unread_notifications[] = $notification;
                }
            }
        }
        return response()->json(['read'=>$read_notifications,'unread'=>$unread_notifications]);
    }
   
    /**
     * Mark user notifications as read
     * 
     * @param \Illuminate\Http\Request $request
     * @param Int $user_notifiation_id 
     *    Id of the notification assigned to user
     * 
     * @return String event object
     *  JSON encoded string of the notifications
     */
    public function markUserNotificationsAsRead(Request $request,$user_notifiation_id){
        $user = $request->user();

        // Save and mark user notification
        $marked_notification =  NotificationRecipients::with('notification')
                                ->where('id',$user_notifiation_id)
                                ->where('user_id',$user->id)
                                ->first();
        $marked_notification->is_read = 1;
        $marked_notification->save();
        
        $user_notifications = NotificationRecipients::with('notification')
                            ->where('user_id',$user->id)
                            ->orderBy('id','desc')
                            ->get();
        $read_notifications = [];
        $unread_notifications = [];

        foreach($user_notifications as $notification){
            if($notification->notification->is_sent == 1){
                if($notification->is_read){
                    $read_notifications[] = $notification;
                }else{
                    $unread_notifications[] = $notification;
                }
            }
        }
        return response()->json(['read'=>$read_notifications,'unread'=>$unread_notifications]);
    }

    /**
     * Create or Update Onesignal Player ID
     * 
     * @param \Illuminate\Http\Request $request
     * @return String
     * 
     */
    public function updateOrCreateOnesgnalPlayerId(Request $request){
        $user = $request->user();
        $onesignal_player_id = OnesignalUserPlayerIds::where('user_id',$user->id)->first();
        if(empty($onesignal_player_id)){
            $onesignal_player_id = new OnesignalUserPlayerIds;
        }
        if(isset($request->onesignal_player_id)){
            $onesignal_player_id->user_id = $user->id;
            $onesignal_player_id->onesignal_player_id = $request->onesignal_player_id;
            if($onesignal_player_id->save()){
                return response()->json(['success'=>true]);
            }
        }
        return response()->json(['success'=>false],500);
    }

    /**
    * Get customer types 
    * !!NOTE changed from `EventIndustries` 
    *
    * @param \Illuminate\Http\Request $request
    * @return String
    */
    public function industries(){
        $industries = CustomerType::pluck('type')->toArray();
        $industries[] = 'Other'; // Add other as an option
        return response()->json($industries);
    }

    /**
    * Register Event
    *
    * @param \Illuminate\Http\Request $request
    * @return String
    */
    public function registerEvent(Request $request,$event_id){
        $user = $request->user();
        $data = $request->data;
        $payment = new PaymentsRegistration;
        $payment->event_id = $event_id;
        $payment->user_id = $user->id;
        //$payment->payment_order = $payment_order;
        $payment->number_persons = 1;
        $payment->amount = $data['total'];
        $payment->save();
        $payment_id = $payment->id;
        $order_number = $payment->id;

        $registration = new EventRegistrations;
        $registration->user_id = $user->id;
        $registration->event_id = $event_id;
        $registration->first_name = $data['first_name'] ?? 'N/A';
        $registration->last_name = $data['last_name']  ?? 'N/A';
        $registration->company = $data['company']  ?? 'N/A';
        $registration->title = $data['title']  ?? 'N/A';
        $registration->industry = $data['industry']  ?? 'N/A';
        $registration->email = $data['email']  ?? 'N/A';
        $registration->telephone = $data['telephone']  ?? 'N/A';
        $registration->save();
        $reg_id = $registration->id;
        $time = null;
        $first_sched = date("Y-m-d");
        $last_sched = date("Y-m-d");
        // email register with eent calendar invite
        $registration_event = Events::where('id',$event_id)->first();
       
        if($registration_event){
            $user = $request->user();
            $amount = $data['total'] ?? 0;  
            
            $email = $user->email;
            if($amount > 0){
                $payment = PaymentsRegistration::where('id',$payment_id)->first();
                
                /*if($data['Business-Check-Number') != null
                && $data['Card-Number') == null){*/
                if(!isset($data['payment_method'])){
                    $response = ['responsetext'=>'SUCCESS'];
                    $payment->business_check_number = $data['check_number'];
                }
                else{
                    $card_number = $data['cc_number'];
                    $name = $data['cc_name'];
                    $name =explode(" ",$name);
                    $firstname=$name[0] ?? null;
                    $lastname=$name[1] ?? null;
                    $expdate = $data['expiration_month'].substr($data['expiration_year'], -2);//$data['Expiration-Date');
                    $cvv = $data['CVV-CVC'];
                    $address = $data['billing_address'];
                    #$aptsuite = $data['Apt-Suite'];
                    $city = $data['billing_city'];
                    $state = $data['billing_state'];
                    $zip = $data['billing_zip'];
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
                    $gw->setOrder($order_number."12244","Havtech Payment","0", "0","na" ,"65.192.14.10");
            
                    /* transaction part */
                    /* doSale($amount, $ccnumber, $ccexp, $cvv="")  */
                    //$r = $gw->doSale("1.00","4111111111111111","1010");//add ccv at the end
                    $r = $gw->doSale($amount,$card_number,$expdate, $cvv);
                    $response = $gw->responses;
                    //print_r( $response);
                    //print($gw->responses['transactionid']);
                    //print $response->responsetext;
                    $payment->transaction_id = $response['transactionid'];
                    $payment->order_id = $response['orderid'];
                    $payment->payment_status = $response['responsetext'];
                    $payment->magothy_response = json_encode($response);
                    $payment->save();

                }
                //echo $response['responsetext']; 
                //echo $user->id." ".$event_id;
                if(str_replace(' ', '',strtoupper($response['responsetext']))=="SUCCESS" ||$response['responsetext']=="SUCCESS" || $response['responsetext']=="Approval" || $response['responsetext']=="APPROVAL"){
                    //echo "here agaon";
                    $values['payment_status']="1";
                    $custmevents=CustomEventSchedule::where('payment_id',$payment->id)->update($values);
                    $values2['completed']="1";
                   
                    $reg=EventRegistrations::where('user_id',$user->id)
                                ->where('event_id',$event_id)->update($values2);
                    
                    //$event_id=$event_id;
                    //echo $event_id;die();
                    // email register with eent calendar invite
                    $registration_event = Events::where('id',$event_id)->first();
                    
                    //print_r( $registration_event);die();
                    if($registration_event){
                        if(count($data['event_schedule_id']) > 0){
                                $event_id=$data['event_id'][0];
                                $insert_data = [];
                                foreach($data['event_schedule_id'] as $val){
                                    $registrationces = new CustomEventSchedule;
                                    $registrationces->event_id = $event_id;
                                    $registrationces->event_schedule_id = $val;
                                    $registrationces->user_id =  $user->id;
                                    $registrationces->registration_id = $reg_id;
                                    $registrationces->created_at = date('Y-m-d H:i:s');
                                    $registrationces->updated_at = date('Y-m-d H:i:s');
                                    $registrationces->save();
                                    //$insert_data[] = $dataz;
                                    $first_schedule = EventSchedule::where('id',$val)
                                    ->orderBy('start_date','asc')
                                    ->first();
                                    $first_sched = $first_schedule->start_date;
                                    $last_sched = $first_schedule->end_date;
                                    $schedule_title = $first_schedule->title;
                                    
                                    if($first_schedule->location=="Room Number"){
                                        $address=$first_schedule->room_number;
                                    }
                                    else{
                                        $address=$first_schedule->location_address.", ".$first_schedule->cname." ".$first_schedule->city." ".$first_schedule->state." ".$first_schedule->zip." ".$first_schedule->country_name;
                                    }
        
                                    if(isset($registration_event->cost_per_person)){
                                        $amount=preg_replace('/\s+/', '', str_replace("$",'',($registration_event->cost_per_person)));
                                    }
                                    else{
                                    $amount = 0;
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
                        
                                    $startz = date("F j, Y H:i:s",strtotime($first_sched));
                                    $endz = date("F j, Y  H:i:s",strtotime($last_sched));
        
                                    $starttime = date("g:i a",strtotime($first_sched));
                                    $endtime = date("g:i a",strtotime($last_sched));
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
                                    //$to_email = 'reymondb@codev.com';
                                    Mail::send('emails.eventregistered2',$data, function($message) use ($to_email, $to_name, $ics,$event_name,$startz,$endz) {
                                        $message->to($to_email, $to_name)
                                                ->subject('Thank you for registering ('.$event_name.' '.$startz.'-'.$endz.')');
                                        $message->from('marketing@havtech.com','Havtech Events');
                                        $message->attachData($ics, 'Havtech Event.ics', ['mime' => 'data:text charset=utf8']);
                                    });
                                    
                                }
                            // $result = CustomEventSchedule::insert($insert_data);
                        }
                        
                        // return view('frontend.registration-complete')->with('tabs', $tabs)->with('event_id', $event_id)->with('amount',$amount);
                    }
                }
                
                else{
                    return response()->json(['success'=>false],400);
                }
            }

            /* send email for free events */
            else{
                if(count($data['event_schedule_id']) > 0){
                    $values2['completed']="1";
                    $reg=EventRegistrations::where('user_id',$user->id)
                                ->where('event_id',$event_id)->update($values2);

                        $event_id=$data['event_id'][0];
                        $insert_data = [];
                        foreach($data['event_schedule_id'] as $val){
                            $registrationces = new CustomEventSchedule;
                            $registrationces->event_id = $event_id;
                            $registrationces->event_schedule_id = $val;
                            $registrationces->user_id =  $user->id;
                            $registrationces->registration_id = $reg_id;
                            $registrationces->created_at = date('Y-m-d H:i:s');
                            $registrationces->updated_at = date('Y-m-d H:i:s');
                            $registrationces->save();
                            //$insert_data[] = $dataz;
                            $first_schedule = EventSchedule::where('id',$val)
                            ->orderBy('start_date','asc')
                            ->first();
                            $first_sched = $first_schedule->start_date;
                            $last_sched = $first_schedule->end_date;
                            $schedule_title = $first_schedule->title;
                            
                            if($first_schedule->location=="Room Number"){
                                $address=$first_schedule->room_number;
                            }
                            else{
                                $address=$first_schedule->location_address.", ".$first_schedule->cname." ".$first_schedule->city." ".$first_schedule->state." ".$first_schedule->zip." ".$first_schedule->country_name;
                            }

                            if(isset($registration_event->cost_per_person)){
                                $amount=preg_replace('/\s+/', '', str_replace("$",'',($registration_event->cost_per_person)));
                            }
                            else{
                            $amount = 0;
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
                
                            $startz = date("F j, Y H:i:s",strtotime($first_sched));
                            $endz = date("F j, Y  H:i:s",strtotime($last_sched));

                            $starttime = date("g:i a",strtotime($first_sched));
                            $endtime = date("g:i a",strtotime($last_sched));
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
                            //$to_email = 'reymondb@codev.com';
                            Mail::send('emails.eventregistered2',$data, function($message) use ($to_email, $to_name, $ics,$event_name,$startz,$endz) {
                                $message->to($to_email, $to_name)
                                        ->subject('Thank you for registering ('.$event_name.' '.$startz.'-'.$endz.')');
                                $message->from('marketing@havtech.com','Havtech Events');
                                $message->attachData($ics, 'Havtech Event.ics', ['mime' => 'data:text charset=utf8']);
                            });
                            
                        }
                    // $result = CustomEventSchedule::insert($insert_data);
                }
                    else{
                        // check first event schedule
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
                            
                            $schedule_title = $first_schedule->title;
                        }
                        else{
                            $schedule_title = "";
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
                        if(isset($registration_event->cost_per_person)){
                            $amount=preg_replace('/\s+/', '', str_replace("$",'',($registration_event->cost_per_person)));
                        }
                        else{
                        $amount = 0;
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
                        //$to_email = 'reymondb@codev.com';
                        Mail::send('emails.eventregistered',$data, function($message) use ($to_email, $to_name, $ics,$event_name) {
                            $message->to($to_email, $to_name)
                                    ->subject('Thank you for registering ('.$event_name.')');
                            $message->from('marketing@havtech.com','Havtech Events');
                            $message->attachData($ics, 'Havtech Event.ics', ['mime' => 'data:text charset=utf8']);
                        });
                    }
                }
                
        }


        return response()->json();
    }

    /**
    * Update user profile
    *
    * @param \Illuminate\Http\Request $request
    * @return String
    */
    public function updateUser(Request $request){
       
        $user = $request->user();
        $user = User::where('id',$user->id)->first();
        $data = $request->data;
        $customer_type = CustomerType::where('type','=',$data['industry'])->first();
        $receive_notifications = $request->receive_notifications;
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->company = $data['company'];
        $user->title = $data['title'];
        $user->customer_type = $customer_type->id;
        $user->industry = $data['industry'];
        $user->receive_notifications = $receive_notifications;
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        if($data['password'] != null || $data['password'] != ''){
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        return response()->json();
    }

    /**
     * Returns a file response
     * 
     * @param String $filename
     * @return File
     */
    public function downloadFile($filename){
        $file= public_path(). "/uploads/".$filename;   
        return response()->file($file);
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

    /**
     * Save Custom Event Schedules
     * 
     * @param \Illuminate\Http\Request $request
     * @return String $str
     */
    public function saveCustomEventSchedule(Request $request){
        $user = $request->user();
        if(count($request->data) > 0){
            $insert_data = [];
            foreach($request->data as $data){
                $data['user_id'] = $user->id;
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $insert_data[] = $data;
            }
            $result = CustomEventSchedule::insert($insert_data);
        }
        return response()->json();
    }

    /**
     * Removes schedule from custom schedules
     * 
     * @param \Illuminate\Http\Request $request
     * @param  Integer $id 
     *  - event schedule id
     * 
     * @return String $str
     */
    public function  deleteCustomEventSchedule(Request $request,$id){
        $user = $request->user();
        CustomEventSchedule::where('user_id',$user->id)
                            ->where('event_schedule_id',$id)
                            ->delete();
        return response()->json();
    }
    
}
