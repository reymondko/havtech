<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use App\Models\Events;
use App\Models\EventTypes;
use App\Models\EventTravelHosts;
use App\Models\EventFaqs;

use DB;
use DataTables;
use Response;

class EventTravelHostFaqsController extends Controller
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
    public function createTravelHostAndInformationPage($step,$total,$event_id)
    {
        $events= Events::where('id', $event_id)->first();
        $eventdata=EventTravelHosts::where('event_id', $event_id)->get();
        $bannerimage="";
        if(count($eventdata)>0){$bannerimage=$eventdata[0]->image;        }
        $eventdata2=EventFaqs::where('event_id', $event_id)->get();
        $event_info_file="";
        $download_link=null;
        if(count($eventdata2)>0){$event_info_file=$eventdata2[0]->event_info_file; $download_link=$eventdata2[0]->download_link;       }
        $event= Events::select('event_name')->where('id', $event_id)->first();
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'eventdata' => json_decode($eventdata, true),
            'eventdata2' => json_decode($eventdata2, true)
        );
        return view('layouts/events/create/eventtravelhostandinformation')
        ->with('event',$events)->with('step',$step)->with('total',$total)
        ->with('data', $data)
        ->with('banner_image',$bannerimage)
        ->with('download_link',$download_link)
        ->with('event_info_file',$event_info_file);;
    }
    
    //edit event transportation Page
    public function editTravelHostAndInformation($step,$event_id)
    {
        $eventtypes=EventTypes::all();
        //get all tabs 
        $tabs = app('App\Http\Controllers\EventsController')->fetchTabs($event_id);
        $eventdata=EventTravelHosts::where('event_id', $event_id)->get();
        $bannerimage="";
        if(count($eventdata)>0){$bannerimage=$eventdata[0]->image;        }
        $eventdata2=EventFaqs::where('event_id', $event_id)->get();
        $event_info_file="";
        $download_link=null;
        if(count($eventdata2)>0){
            $event_info_file=$eventdata2[0]->event_info_file; 
            $download_link=$eventdata2[0]->download_link;       
        }
        $event= Events::select('event_name')->where('id', $event_id)->first();
        $data = array(
            'step' => $step,
            'event_id' => $event_id,
            'event_name' => $event->event_name,
            'eventdata' => json_decode($eventdata, true),
            'eventdata2' => json_decode($eventdata2, true),
            'events' => $tabs
        );
        return view('layouts/events/edit/eventtravelhostandinformation')
        ->with('data', $data)
        ->with('banner_image',$bannerimage)
        ->with('download_link',$download_link)
        ->with('event_info_file',$event_info_file);
    }
    public function updateEventTravelHost (Request $request)
    {
        $x=0;
        $z=1;
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
        if($request->hasFile('info_files')) { 
            $file = $request->file('info_files');
            $info_file = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
            $image['filePath'] = $info_file;
            $file->move(public_path().'/uploads/', $info_file);
            $event_info_file= '/uploads/'. $info_file; 
        }
        
        if(!empty($request->travelhost_id)){
            foreach($request->travelhost_id as $travelhost_id){
                if($travelhost_id=='0'){
                    $travelhost= new EventTravelHosts;
                    $travelhost->event_id= $request->event_id;
                    $travelhost->host_name = $request->host_name[$x];
                    //$travelhost->address1 = $request->address1[$x];
                    //$travelhost->address2 = $request->address2[$x];
                    $travelhost->description = $request->description[$x];
                    //$travelhost->email_button = $request->input("email_button".$z);
                    $travelhost->email = $request->email[$x];
                    if(!empty($bannerimage)){
                        $travelhost->image=$bannerimage;
                    }
                    //check if there is thumb image
                    if($request->hasFile('thumb_images.'.$x)) { 
                        $file = $request->file('thumb_images.'.$x);
                        $thumbname = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                        $image['filePath'] = $thumbname;
                        //resize if more than 1300
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
                        $travelhost->thumb_image= '/uploads/'. $thumbname; //public_path().
                    }
                    else{
                        $travelhost->thumb_image = $request->thumb_image_id[$x];
                    }
                    $travelhost->save();
                }
                else{
                    $values= array('event_id' => $request->event_id,
                        'host_name' => $request->host_name[$x],
                        'address1' => $request->address1[$x],
                        'address2' => $request->address2[$x],
                        'description' => $request->description[$x],
                        'email_button' => $request->input("email_button".$z),
                        'email' => $request->email[$x]);
                    
                    if(!empty($bannerimage)){
                        $values['image']=$bannerimage;
                    }
                    //check if there is thumb image
                    if($request->hasFile('thumb_images.'.$x)) { 
                        $file = $request->file('thumb_images.'.$x);
                        $thumbname = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                        $image['filePath'] = $thumbname;
                        //resize if more than 1300
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
                    EventTravelHosts::where('id', $travelhost_id)->update($values);
                }
                $x++;
                $z++;
            }
        }
        $y=0;
        $a=1;
        if(!empty($request->faq_id)){
            foreach($request->faq_id as $faq_id){
                if($faq_id=='0'){
                    $event_info= new EventFaqs;                            
                    $event_info->event_id= $request->event_id;
                    $event_info->download_link = $request->download_link;//$request->input("download_link".$a);            
                    //$event_info->event_info_file = $event_info_file;
                    if(!empty($event_info_file)){
                        $event_info->event_info_file=$event_info_file;
                    }
                    $event_info->faq_title = $request->faq_title[$y];
                    $event_info->faq_answer = $request->faq_answer[$y];
                    
                    $event_info->save();
                }
                else{
                    $values= array('download_link' => $request->download_link, #$request->input("download_link".$a), 
                            'faq_title' => $request->faq_title[$y],
                            'faq_answer' => $request->faq_answer[$y]);
                    if(!empty($event_info_file)){
                        $values['event_info_file']=$event_info_file;
                    }
                    EventFaqs::where('id', $faq_id)->update($values);

                }
                $y++;
                $a++;
            }
        }
        if($request->from=="create"){
            $nextstep= $request->step;
            return app('App\Http\Controllers\EventsController')->getNextstep($nextstep,$request->event_id); 
        }
        else{
            $request->session()->flash('status', 'saved');
            $request->session()->flash('type', 'Travel Host and Information');
            return Response::json(array('success' => true,'message' => 'Event Travel Host and Information Saved.'), 200); 
        }
        
    }

    //delete event parts
    public function deleteEventTravelHost(Request $request){
        $delete=EventTravelHosts::where('id', $request->id)->first();
        if (file_exists($delete->image)) {
            unlink($delete->image);
        }
 
        if (file_exists($delete->thumb_image)) {
            unlink($delete->thumb_image);
        }
        $delete->delete();
        return Response::json(array('success' => true,'message' => 'Event Travel Host Deleted.'), 200); 
    }

    //delete event faq
    public function deleteEventFaq(Request $request){
        $delete=EventFaqs::where('id', $request->id)->first();
        if (file_exists($delete->event_info_file)) {
            unlink($delete->event_info_file);
        }
        $delete->delete();
        return Response::json(array('success' => true,'message' => 'Event Faq Deleted.'), 200); 
    }

}