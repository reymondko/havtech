<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Events;
use App\Models\EventTypes;
use App\Models\EventAccomodations;
use App\Models\Country;
use Intervention\Image\Facades\Image;

use DB;
use DataTables;
use Response;

class EventAccomodationController extends Controller
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
    public function createAccomodationsPage($step,$total,$event_id)
    {
        $events= Events::where('id', $event_id)->first();
        $eventaccs=EventAccomodations::where('event_id', $event_id)->get();
        $bannerimage="";
        if(count($eventaccs)>0){$bannerimage=$eventaccs[0]->image; }
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'eventaccs' => json_decode($eventaccs, true)
        );
        $countries=Country::all();
        return view('layouts/events/create/eventaccomodations')
        ->with('event',$events)->with('step',$step)->with('total',$total)
        ->with('data', $data)->with('countries',$countries)
        ->with('banner_image',$bannerimage);
    }
    
    //edit event accomodation Page
    public function editEventAccomodations($step,$event_id)
    {
        $eventtypes=EventTypes::all();
        //get all tabs 
        $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
        $eventaccs=EventAccomodations::where('event_id', $event_id)->get();
        $bannerimage="";
        if(count($eventaccs)>0){$bannerimage=$eventaccs[0]->image; }
        $event= Events::select('event_name')->where('id', $event_id)->first();
        $countries=Country::all();
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'event_name' => $event->event_name,
            'eventaccs' => json_decode($eventaccs, true),
            'events' => $tabs
        );
        return view('layouts/events/edit/eventaccomodations')
        ->with('data', $data)->with('countries',$countries)
        ->with('banner_image',$bannerimage);
    }
    //Update data
    //create event Accomodations
    public function updateAccomodations(Request $request)
    {  
        $x=0; 
        $y=1;//upload banner image
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
            $bannerimage= '/uploads/'. $name; //public_path().         
        }
        if(!empty($request->acc_ids)){            
            foreach($request->acc_ids as $acc_ids){
                //check if new Accomodation added by user
                if($acc_ids=='0'){
                    $eventaccomodations= new EventAccomodations;
                    if(!empty($bannerimage)){
                        $eventaccomodations->image=$bannerimage;
                    }
                    $eventaccomodations->event_id= $request->event_id;
                    $eventaccomodations->hotel = $request->hotel[$x];
                    $eventaccomodations->name = $request->name[$x];
                    $eventaccomodations->confirmation_number = $request->confirmation_number[$x];
                    $eventaccomodations->room_number = $request->room_number[$x];
                    $eventaccomodations->location = $request->location[$x];
                    $eventaccomodations->address1 = $request->location_address[$x];
                    $eventaccomodations->address2 = $request->location_address2[$x];
                    $eventaccomodations->city = $request->city[$x];
                    $eventaccomodations->state = $request->state[$x];
                    $eventaccomodations->zip = $request->zip[$x];
                    $eventaccomodations->country = $request->country[$x];
                    $eventaccomodations->phone = $request->phone[$x];
                    $eventaccomodations->description = $request->description[$x];
                    #$eventaccomodations->directions_button = $request->input("directions_button".$y);
                    $eventaccomodations->directions_button = $request->directions_button[$x];
                    $eventaccomodations->website_url = $request->website_url[$x];
                    $eventaccomodations->save();
                }
                else{
                    $values=array('event_id' => $request->event_id,
                    'hotel' => $request->hotel[$x],
                    'name' => $request->name[$x],
                    'confirmation_number' => $request->confirmation_number[$x],
                    'room_number' => $request->room_number[$x],
                    'location' => $request->location[$x],
                    'address1' => $request->location_address[$x],
                    'address2' => $request->location_address2[$x],
                    'city' => $request->city[$x],
                    'state' => $request->state[$x],
                    'zip' => $request->zip[$x],
                    'phone' => $request->phone[$x],
                    'description' => $request->description[$x],
                    'country' => $request->country[$x],
                    
                    //'directions_button' => $request->input("directions_button".$y),
                    'directions_button' => $request->directions_button[$x],
                    'website_url' => $request->website_url[$x]);
                    
                    if(!empty($bannerimage)){
                        $values['image']=$bannerimage;
                    }
                    EventAccomodations::where('id', $acc_ids)->update($values);
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
            $request->session()->flash('type', 'Accomodations');
            return Response::json(array('success' => true,'message' => 'Event Accomodations Saved.'), 200); 
        }
    }
    //delete event parts
    public function deleteAccomodations(Request $request){
        $delete=EventAccomodations::where('id', $request->acc_id)->first();
        if (file_exists($delete->image)) {
            unlink($delete->image);
        }
        $delete->delete();
        return Response::json(array('success' => true,'message' => 'Event Accomodation Deleted.'), 200); 
    }

    
}