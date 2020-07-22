<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use App\Models\Events;
use App\Models\EventTypes;
use App\Models\EventDining;
use App\Models\DiningTypes;
use App\Models\EventDateTime;
use App\Models\Country;

use DB;
use DataTables;
use Response;

class EventDiningController extends Controller
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
    public function createDiningPage($step,$total,$event_id)
    {
        $events= Events::where('id', $event_id)->first();
        $eventdining=DiningTypes::all();
        //get all tabs 
        $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
        $eventdata=EventDining::where('event_id', $event_id)->get();
        $bannerimage="";
        if(count($eventdata)>0){$bannerimage=$eventdata[0]->image; }
        $event= Events::where('id', $event_id)->first();
        $countries=Country::all();
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
        //end select drop down dates
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'dates' => $dates ,
            'event_dining' => $eventdining,
            'eventdata' => json_decode($eventdata, true)
        );
        
        return view('layouts/events/create/eventdining')->with('event',$events)
        ->with('step',$step)->with('total',$total)->with('eventdining',$eventdining)
        ->with('data', $data)->with('countries',$countries)
        ->with('banner_image',$bannerimage);
    }
    
    //edit event Dining Page
    public function editEventDining($step,$event_id)
    {
        $eventtypes=EventTypes::all();
        $eventdining=DiningTypes::all();
        $countries=Country::all();
        //get all tabs 
        $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
        $eventdata=EventDining::where('event_id', $event_id)->get();
        $bannerimage="";
        if(count($eventdata)>0){$bannerimage=$eventdata[0]->image; }
        $event= Events::where('id', $event_id)->first();
        //$dates=EventDateTime::where('event_id',$event_id)->get();
        //this is for the date select drop down
        $date_from = date("Y-m-d",strtotime($event->start_date));    
        $date_from = strtotime($date_from);  
        
        // Specify the end date. This date can be any English textual format  
        $date_to = date("Y-m-d",strtotime($event->end_date));  
        $date_to = strtotime($date_to); // Convert date to a UNIX timestamp  
        
        // Loop from the start date to end date and output all dates inbetween  
        for ($i=$date_from; $i<=$date_to; $i+=86400) { 
            $dates[]= date("F j,Y", $i);
        } 
        //end select drop down dates
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'event_name' => $event->event_name,
            'dates' => $dates ,
            'event_dining' => $eventdining,
            'eventdata' => json_decode($eventdata, true),
            'events' => $tabs
        );
        return view('layouts/events/edit/eventdining')
        ->with('data', $data)->with('countries',$countries)
        ->with('banner_image',$bannerimage);
    }

    public function updateEventDining(Request $request)
    {  
        $x=0;
        $y=1;
        //upload banner image
        //check if there is banner image
        if($request->hasFile('banner_images')) { 
            $file = $request->file('banner_images');
            $name = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
            $image['filePath'] = $name;
            //resize if more than 1300
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
            $bannerimage = '/uploads/'. $name; //public_path().         
        }           
        if(!empty($request->e_ids)){
            foreach($request->e_ids as $e_id){
                //check if new Dining added by user
                if($e_id=='0'){
                    $eventdining= new EventDining;
                     if(!empty($bannerimage)){
                        $eventdining->image=$bannerimage;
                     }
                    $eventdining->event_id= $request->event_id;
                    $eventdining->dining_type = $request->dining_type[$x];
                    $eventdining->location = $request->location[$x];
                    $eventdining->meal_date = date("Y-m-d",strtotime($request->meal_date[$x]));
                    $eventdining->start_time = $request->start_time[$x];
                    $eventdining->end_time = $request->end_time[$x];
                    $eventdining->address1 = $request->address1[$x];
                    $eventdining->address2 = $request->address2[$x];
                    $eventdining->city = $request->city[$x];
                    $eventdining->state = $request->state[$x];
                    $eventdining->zip = $request->zip[$x];
                    $eventdining->country = $request->country[$x];
                    $eventdining->phone = $request->phone[$x];
                    $eventdining->description = $request->description[$x];
                    //$eventdining->directions_button = $request->input("directions_button".$y);
                    $eventdining->directions_button = $request->directions_button[$x];
                    $eventdining->website_url = $request->website_url[$x];
                    $eventdining->save();
                }
                else{
                    $values=array('dining_type' => $request->dining_type[$x],
                            'location' => $request->location[$x],
                            'meal_date' => date("Y-m-d",strtotime($request->meal_date[$x])),
                            'start_time' => $request->start_time[$x],
                            'end_time' => $request->end_time[$x],
                            'address1' => $request->address1[$x],
                            'address2' => $request->address2[$x],
                            'city' => $request->city[$x],
                            'state' => $request->state[$x],
                            'zip' => $request->zip[$x],
                            'country' => $request->country[$x],
                            'phone' => $request->phone[$x],
                            'description' => $request->description[$x],
                            'directions_button' => $request->directions_button[$x],
                            //'directions_button' =>$request->input("directions_button".$y),
                            'website_url' => $request->website_url[$x]);
                    if(!empty($bannerimage)){
                        $values['image'] = $bannerimage;
                    }  
                    EventDining::where('id', $e_id)->update($values);
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
            $request->session()->flash('type', 'Dining');
            return Response::json(array('success' => true,'message' => 'Event Dining Saved.'), 200); 
        }
       
    }
    
    //delete event parts
    public function deleteEventDining(Request $request){
        $delete=EventDining::where('id', $request->id)->first();
        if (file_exists($delete->image)) {
            unlink($delete->image);
        }
        $delete->delete();
        return Response::json(array('success' => true,'message' => 'Event Dining Deleted.'), 200); 
    }
}