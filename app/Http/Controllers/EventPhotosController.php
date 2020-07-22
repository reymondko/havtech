<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use App\Models\Events;
use App\Models\EventTypes;
use App\Models\EventPhotos;
use DB;
use DataTables;
use Response;
use Auth;

class EventPhotosController extends Controller
{
    private $photos_path;
 
    public function __construct()
    {
        $this->photos_path = public_path('/uploads/photos');
    }
    /**
     * Show the application Events.
     *
     * @return \Illuminate\Http\Response
     */
    public function createEventPhotosPage($step,$total,$event_id)
    {
        $events= Events::where('id', $event_id)->first();
        $event_data=EventPhotos::where('event_id', $event_id)->get();
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'eventdata' => json_decode($event_data, true)
        );
        return view('layouts/events/create/eventphotos')->with('event',$events)->with('step',$step)->with('total',$total)->with('data', $data);
    }

    
    public function editEventPhotosPage($step,$event_id)
    {
        $eventtypes=EventTypes::all();
        //get all tabs 
        $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
        $event_data=EventPhotos::where('event_id', $event_id)->get();
        $event= Events::select('event_name')->where('id', $event_id)->first();
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'event_name' => $event->event_name,
            'eventdata' => json_decode($event_data, true),
            'events' => $tabs
        );
        return view('layouts/events/edit/eventphotos')->with('data', $data);
    }

    /**
     * Saving images uploaded through XHR Request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addEventPhoto(Request $request)
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
            //dd($photo);die();
            $name = sha1(date('YmdHis') . str_random(30));
            $save_name = $name . '.' . $photo->getClientOriginalExtension();
            $orig_save_name = $name . '_orig.' . $photo->getClientOriginalExtension();
            $resize_name = $name . str_random(2) . '.' . $photo->getClientOriginalExtension();
            $img = Image::make($photo)->save($this->photos_path . '/' . $orig_save_name);;
            Image::make($photo)
                ->resize(250, null, function ($constraints) {
                    $constraints->aspectRatio();
                })
                ->save($this->photos_path . '/' . $resize_name);
 
            //resize if more than 1300
            $width = Image::make($photo)->width();
            if($width>1300){
                Image::make($photo)
                ->resize(1300, null, function ($constraints) {
                    $constraints->aspectRatio();
                })->save($this->photos_path . '/' . $save_name);
            }
            else{
                $photo->move($this->photos_path, $save_name);
            }

            $upload = new EventPhotos();
            $upload->event_id=$request->event_id;
            $upload->filename = $save_name;
            $upload->resized_name = $resize_name;
            $upload->photo = $orig_save_name;            
            $upload->original_name = basename($photo->getClientOriginalName());
            $user= Auth::user();
            $upload->user_id =$user->id;
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
    public function destroyEventPhoto(Request $request)
    {
        $filename = $request->id;
        $uploaded_image = EventPhotos::where('original_name', basename($filename))->first();
 
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
}