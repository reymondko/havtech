<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Events;
use App\Models\EventTypes;
use App\Models\EventSchedule;
use App\Models\EventDateTime;
use App\Models\Country;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use DB;
use DataTables;
use Response;

class EventScheduleController extends Controller
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
    public function createEventSchedulePage($step,$total,$event_id)
    {
        $events= Events::where('id', $event_id)->first();
        $eventtypes=EventTypes::all();
        $eventscheds=EventSchedule::where('event_id', $event_id)->get();
        $bannerimage="";
        if(count($eventscheds)>0){$bannerimage=$eventscheds[0]->image; }
        //$dates=EventDateTime::where('event_id',$event_id)->get();
        //this is for the date select drop down
        $date_from = date("Y-m-d",strtotime($events->start_date));    
        $date_from = strtotime($date_from);  
        
        // Specify the end date. This date can be any English textual format  
        $date_to = date("Y-m-d",strtotime($events->end_date));  
        $date_to = strtotime($date_to); // Convert date to a UNIX timestamp  
        
        // Loop from the start date to end date and output all dates inbetween  
        for ($i=$date_from; $i<=$date_to; $i+=86400) { 
            $dates[]= date("F j,Y", $i);
        } 
        $countries=Country::all();
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'event_name' => $events->event_name,
            'eventscheds' => json_decode($eventscheds, true)
        );
        return view('layouts/events/create/eventschedule')->with('event',$events)->with('step',$step)->with('total',$total)->with('data', $data)->with('datez',$dates)->with('countries',$countries)->with('banner_image',$bannerimage);
    }

    public function editEventSchedule($step,$event_id)
    {
        $eventtypes=EventTypes::all();
        //get all tabs 
        $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
        $eventscheds=EventSchedule::where('event_id', $event_id)->get();
        $event= Events::where('id', $event_id)->first();
        $bannerimage="";
        if(count($eventscheds)>0){$bannerimage=$eventscheds[0]->image;}

        //$dates=EventDateTime::where('event_id',$event_id)->get();
        //this is for the date select drop down
        $date_from = date("Y-m-d",strtotime($event->start_date));    
        $date_from = strtotime($date_from);  
        
        // Specify the end date. This date can be any English textual format  
        $date_to = date("Y-m-d",strtotime($event->end_date));  
        $date_to = strtotime($date_to); // Convert date to a UNIX timestamp  
        $dates = [];
        // Loop from the start date to end date and output all dates inbetween  
        for ($i=$date_from; $i<=$date_to; $i+=86400) { 
            $dates[]= date("F j,Y", $i);
        } 
        $countries=Country::all();
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'event_name' => $event->event_name,
            'eventscheds' => json_decode($eventscheds, true),
            'events' => $tabs
        );
        return view('layouts/events/edit/eventschedule')->with('data', $data)
        ->with('datez',$dates)->with('countries',$countries)
        ->with('banner_image',$bannerimage);
    }

    public function updateEventSchedule(Request $request)
    {  
        $x=0;
        $y=1;
        if($request->hasFile('banner_images')) { 
            $file = $request->file('banner_images');
            $name = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
            $image['filePath'] = $name;
            $width = Image::make($file)->width();
            if($width>1300){
                Image::make($file)
                ->resize(1300, null, function ($constraints) {
                    $constraints->aspectRatio();
                })->save(public_path().'/uploads/' . $name);
            }
            else{
                $file->move(public_path().'/uploads/', $name);
            }
            $bannerimage= '/uploads/'. $name; //public_path().
        }
        if(!empty($request->schedule_ids)){
            //upload banner image
            //check if there is banner image
            
            foreach($request->schedule_ids as $sched_id){
            //check if new schedule added by user
                if($sched_id=='0'){
                    $date1=$request->start_dateonly[$x];
                    $date2=$request->end_dateonly[$x];
                    $start_date=date("Y-m-d H:i:s",strtotime($date1." ".$request->start_time[$x]));
                    $end_date=date("Y-m-d H:i:s",strtotime($date2." ".$request->end_time[$x]));    
                    
                    $eventsched= new EventSchedule;
                    if(!empty($bannerimage)){
                        $eventsched->image =$bannerimage;     
                    }
                    $thumbnames=" ";
                    //check if there is thumb image
                    if($request->hasFile('thumb_images.'.$x)) { 
                        $file = $request->file('thumb_images.'.$x);
                        $thumbname = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                        $image['filePath'] = $thumbname;
                        $width = Image::make($file)->width();
                        if($width>1300){
                            Image::make($file)
                            ->resize(1300, null, function ($constraints) {
                                $constraints->aspectRatio();
                            })->save(public_path().'/uploads/' . $thumbname);
                        }
                        else{
                            $file->move(public_path().'/uploads/', $thumbname);
                        }
                        $eventsched->thumb_image= '/uploads/'. $thumbname; //public_path().
                        $thumbname.="   --  ".$thumbname;         
                    }
                    else{
                        $eventsched->thumb_image = $request->thumb_image_id[$x];
                    }
                    //check if there is itinerary_file
                    if($request->hasFile('itinerary_file.'.$x)) { 
                        $file = $request->file('itinerary_file.'.$x);
                        $itinerary_file = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                        $image['filePath'] = $itinerary_file;
                        $file->move(public_path().'/uploads/', $itinerary_file);
                        $eventsched->itinerary_file= '/uploads/'. $itinerary_file; //public_path().
                    }
                    $eventsched->event_id= $request->event_id;
                    $eventsched->title = $request->title[$x];
                    $eventsched->presenter_name = $request->presenter_name[$x];
                    $eventsched->presenter_company = $request->presenter_company[$x];
                    $eventsched->start_date = $start_date;
                    $eventsched->end_date = $end_date;
                    $eventsched->location = $request->location[$x];
                    $eventsched->location_address = $request->location_address[$x];
                    $eventsched->location_address2 = $request->location_address2[$x];
                    $eventsched->city = $request->city[$x];
                    $eventsched->state = $request->state[$x];
                    $eventsched->description = $request->description[$x];
                    $eventsched->directions_button = $request->directions_button[$x]; //$request->input("directions_button".$y);
                    $eventsched->website_url = $request->website_url[$x];
                    $eventsched->zip = $request->zip[$x];
                    $eventsched->room_number = $request->room_number[$x];
                    $eventsched->download_link = $request->input("download_link".$y);
                    $eventsched->mandatory = $request->input("mandatory".$y) ?? 0;
                    $eventsched->allow_overlapping_schedule = $request->input("allow_overlapping_schedule".$y) ?? 0;
                    $eventsched->country = $request->country[$x];
                    $eventsched->phone = $request->phone[$x];
                    $eventsched->price = $request->price[$x];
                    
                    $eventsched->save();
                    $sched_ids[]=$eventsched->id;
                }
                else{
                    $date1=$request->start_dateonly[$x];
                    $date2=$request->end_dateonly[$x];
                    $start_date=date("Y-m-d H:i:s",strtotime($date1." ".$request->start_time[$x]));
                    $end_date=date("Y-m-d H:i:s",strtotime($date2." ".$request->end_time[$x]));    
                    
                    $values=array('event_id' => $request->event_id,
                                'title' => $request->title[$x],
                                'presenter_name' => $request->presenter_name[$x],
                                'presenter_company' => $request->presenter_company[$x],
                                'start_date' => $start_date,
                                'end_date' => $end_date,
                                'location' => $request->location[$x],
                                'location_address' => $request->location_address[$x],
                                'location_address2' => $request->location_address2[$x],
                                'city' => $request->city[$x],
                                'state' => $request->state[$x],
                                'zip' => $request->zip[$x],
                                'download_link' => $request->input("download_link".$y),
                                'mandatory' => $request->input("mandatory".$y) ?? 0,
                                'allow_overlapping_schedule' => $request->input("allow_overlapping_schedule".$y) ?? 0,
                                'room_number' => $request->room_number[$x],
                                'country' => $request->country[$x],
                                'phone' => $request->phone[$x],
                                'description' => $request->description[$x],
                                //'directions_button' => $request->input("directions_button".$y),
                                'directions_button' => $request->directions_button[$x],
                                'website_url' => $request->website_url[$x],
                                'price' => $request->price[$x]);
                    if(!empty($bannerimage)){
                        $values['image'] =$bannerimage;     
                    }
                    //check if there is thumb image
                    if($request->hasFile('thumb_images.'.$x)) { 
                        $file = $request->file('thumb_images.'.$x);
                        $thumbname = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                        $image['filePath'] = $thumbname;
                        $width = Image::make($file)->width();
                        if($width>1300){
                            Image::make($file)
                            ->resize(1300, null, function ($constraints) {
                                $constraints->aspectRatio();
                            })->save(public_path().'/uploads/' . $thumbname);
                        }
                        else{
                            $file->move(public_path().'/uploads/', $thumbname);
                        }
                        
                        $values['thumb_image']= '/uploads/'. $thumbname; //public_path().
                    }
                    else{
                        $values['thumb_image'] = $request->thumb_image_id[$x];
                    }
                    //check if there is itinerary_file
                    if($request->hasFile('itinerary_file.'.$x)) { 
                        $file = $request->file('itinerary_file.'.$x);
                        $itinerary_file = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                        $image['filePath'] = $itinerary_file;
                        $file->move(public_path().'/uploads/', $itinerary_file);
                        $values['itinerary_file']= '/uploads/'. $itinerary_file; //public_path().
                    }
                    EventSchedule::where('id', $sched_id)->update($values);
            
                }
                $x++;
                $y++;
            }
        }

        
        if($request->from=="create"){
            $nextstep= $request->step;
            return app('App\Http\Controllers\EventsController')->getNextstep($nextstep,$request->event_id); 
        }
        else{
            $request->session()->flash('status', 'saved');

            return Response::json(array('success' => true,'message' => 'Event Schedule Saved.'), 200); 
        }
    }
    
    //delete event parts
    public function deleteEventSchedule(Request $request){
        $delete=EventSchedule::where('id', $request->sched_id)->first();
        
        if (file_exists($delete->image)) {
            unlink($delete->image);
        }
 
        if (file_exists($delete->thumb_image)) {
            unlink($delete->thumb_image);
        }
        $delete->delete();
        return Response::json(array('success' => true,'message' => 'Event Schedule Deleted.'), 200); 
    }
}