<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use App\Models\Events;
use App\Models\EventTypes;
use App\Models\EventTransportation;
use App\Models\Country;

use DB;
use DataTables;
use Response;

class EventTransportationController extends Controller
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
    public function createTransortationPage($step,$total,$event_id)
    {
        $events= Events::where('id', $event_id)->first();
        $eventdata=EventTransportation::where('event_id', $event_id)->get();
        $bannerimage="";
        if(count($eventdata)>0){$bannerimage=$eventdata[0]->image; }
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'eventdata' => json_decode($eventdata, true)
        );
        $countries=Country::all();
        return view('layouts/events/create/eventtrasportation')
        ->with('event',$events)->with('step',$step)->with('total',$total)
        ->with('data', $data)->with('countries',$countries)
        ->with('banner_image',$bannerimage);
    }

    //edit event transportation Page
    public function editEventTransportation($step,$event_id)
    {
        $eventtypes=EventTypes::all();
        //get all tabs 
        $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
        $eventdata=EventTransportation::where('event_id', $event_id)->get();
        $bannerimage="";
        if(count($eventdata)>0){$bannerimage=$eventdata[0]->image; }
        $event= Events::select('event_name')->where('id', $event_id)->first();
        $countries=Country::all();
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'event_name' => $event->event_name,
            'eventdata' => json_decode($eventdata, true),
            'events' => $tabs
        );
        return view('layouts/events/edit/eventtrasportation')->with('data', $data)
        ->with('countries',$countries)
        ->with('banner_image',$bannerimage);
    }

    public function updateEventTransportation(Request $request)
    {
        $values=array('company_name' => $request->company_name,
        'service_address1' => $request->service_address1,        
        'service_address2' => $request->service_address2,
        'address1' => $request->address1,
        'address2' => $request->address2,
        'city' => $request->city,
        'state' => $request->state,
        'zip' => $request->zip,
        'country' => $request->country,
        'phone' => $request->phone,
        'description' => $request->description,
        'directions_button' => $request->directions_button,
        'website_url' => $request->website_url,
        'flight_description' => $request->flight_description);
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
                })->save(public_path().'/uploads/' . $name);
            }
            else{
                $file->move(public_path().'/uploads/', $name);
            }
            $values['image']= '/uploads/'. $name;     
        }
        
        EventTransportation::where('id', $request->id)->update($values);
        if($request->from=="create"){
            $nextstep= $request->step;
            return app('App\Http\Controllers\EventsController')->getNextstep($nextstep,$request->event_id); 
        }
        else{
            return Response::json(array('success' => true,'message' => 'Event Transportation Saved.'), 200);
        }
        
    }
    
}