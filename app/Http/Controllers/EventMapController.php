<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use App\Models\Events;
use App\Models\EventTypes;
use App\Models\EventMaps;


use DB;
use DataTables;
use Response;

class EventMapController extends Controller
{
    private $photos_path;
 
    public function __construct()
    {
        $this->photos_path = public_path('/uploads/maps');
    }

    /**
     * Show the application Events.
     *
     * @return \Illuminate\Http\Response
     */
    public function createMapPage($step,$total,$event_id)
    {
        $events= Events::where('id', $event_id)->first();
        $eventtypes=EventTypes::all();
       
        $event_data=EventMaps::where('event_id', $event_id)->get();
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'eventdata' => json_decode($event_data, true),
        );
        return view('layouts/events/create/eventmap')->with('event',$events)->with('step',$step)->with('total',$total)->with('data',$data);
    }

    public function editEventMap($step,$event_id)
    {
        $eventtypes=EventTypes::all();
        //get all tabs 
        $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
        $event_data=EventMaps::where('event_id', $event_id)->get();
        $event= Events::select('event_name')->where('id', $event_id)->first();
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'event_name' => $event->event_name,
            'eventdata' => json_decode($event_data, true),
            'events' => $tabs
        );
        return view('layouts/events/edit/eventmap')->with('data', $data);
    }

    public function loadEventMaps($event_id)
    {
        $eventtypes=EventTypes::all();
        //get all tabs 
        $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
        $event_data=EventMaps::where('event_id', $event_id)->get();
        $event= Events::select('event_name')->where('id', $event_id)->first();
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'event_name' => $event->event_name,
            'eventdata' => json_decode($event_data, true),
            'events' => $tabs
        );
        return $data;
    }
    /**
     * Saving images uploaded through XHR Request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addEventMap(Request $request)
    {
        $photos = $request->file('file');
        

        if (!is_array($photos)) {
            $photos = [$photos];
        }
 
        if (!is_dir($this->photos_path)) {
            mkdir($this->photos_path, 0777);
        }
 
        for ($i = 0; $i < count($photos); $i++) {
            $photo = $photos[$i];
            $name = sha1(date('YmdHis') . str_random(30));
            $save_name = $name . '.' . $photo->getClientOriginalExtension();
            $resize_name = $name . str_random(2) . '.' . $photo->getClientOriginalExtension();
 
            Image::make($photo)
                ->resize(250, null, function ($constraints) {
                    $constraints->aspectRatio();
                })
                ->save($this->photos_path . '/' . $resize_name);
            //resize if more than
            $width = Image::make($photo)->width();
            /*
            removed resize as per client request
            if($width > 1300){
                Image::make($photo)
                ->resize(1300, null, function ($constraints) {
                    $constraints->aspectRatio();
                })->save($this->photos_path . '/' . $save_name);
            }
            else{
                $photo->move($this->photos_path, $save_name);
            }*/
            $photo->move($this->photos_path, $save_name);
            
            $map_name=basename($photo->getClientOriginalName(), '.'.$photo->getClientOriginalExtension());
            $upload = new EventMaps();
            $upload->event_id=$request->event_id;
            $upload->map_name = $map_name;
            $upload->filename = $save_name;
            $upload->resized_name = $resize_name;            
            $upload->original_name = basename($photo->getClientOriginalName());
            $upload->save();
        }
        return Response::json([
            'message' => 'Image saved Successfully'
        ], 200);
    }
    /**
     * Remove the images from the storage.
     *
     * @param Request $request
     */
    public function destroyEventMap(Request $request)
    {
        $filename = $request->id;
        $uploaded_image = EventMaps::where('original_name', basename($filename))->first();
 
        if (empty($uploaded_image)) {
            return Response::json(['message' => 'Sorry file does not exist'], 400);
        }
 
        $file_path = $this->photos_path . '/' . $uploaded_image->filename;
        $resized_file = $this->photos_path . '/' . $uploaded_image->resized_name;
 
        if (file_exists($file_path)) {
            unlink($file_path);
        }
 
        if (file_exists($resized_file)) {
            unlink($resized_file);
        }
 
        if (!empty($uploaded_image)) {
            $uploaded_image->delete();
        }
 
        return Response::json(['message' => 'File successfully delete'], 200);
    }

    public function updateMapNames(Request $request){
        $x=0;
        foreach($request->map_ids as $id){
            $map = EventMaps::where('id',$id)->first();
            $map->map_name = $request->map_name[$x];
            $map->save();
            $x++;
        }
        return Response::json(array('success' => true,'message' => 'Event Map Names Updated.'), 200); 
    }
}