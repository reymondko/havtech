<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\EventPhotos;
use App\Models\Events;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use App\Imports\UsersImport;
use App\Models\OnesignalUserPlayerIds;

use Maatwebsite\Excel\Facades\Excel;

use DB;
use Session;
use Response;
use File;
use ZipArchive;

class PhotosController extends Controller
{
    private $photos_path;
 
    public function __construct()
    {
        $this->photos_path = public_path('/uploads/photos');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($event_id)
    {
        $events=Events::orderBy('event_name')->get();
        $filter="";
        //DB::enableQueryLog(); // Enable query log

        if($event_id=="0"){
            $pending_photos =EventPhotos::select('event_photos.*','e.id as event_id','e.event_name','u.first_name','u.last_name')
            ->join('events as e','e.id','=','event_photos.event_id')
            ->join('users as u','u.id','=','event_photos.user_id')
            ->where('event_photos.pending','=',1)
            ->get(); #->latest()->paginate(10) 
            $approved_photos =EventPhotos::select('event_photos.*','e.id as event_id','e.event_name','u.first_name','u.last_name')
            ->join('events as e','e.id','=','event_photos.event_id')
            ->join('users as u','u.id','=','event_photos.user_id')
            ->where('event_photos.pending','=',0)
            ->get(); #->latest()->paginate(10) 
        }
        else{
            $pending_photos =EventPhotos::select('event_photos.*','e.id as event_id','e.event_name','u.first_name','u.last_name')
            ->join('events as e','e.id','=','event_photos.event_id')
            ->join('users as u','u.id','=','event_photos.user_id')
            ->where('event_photos.event_id',$event_id)
            ->where('event_photos.pending','=',1)
            ->get();
            $approved_photos =EventPhotos::select('event_photos.*','e.id as event_id','e.event_name','u.first_name','u.last_name')
            ->join('events as e','e.id','=','event_photos.event_id')
            ->join('users as u','u.id','=','event_photos.user_id')
            ->where('event_photos.event_id',$event_id)
            ->where('event_photos.pending','=',0)
            ->get();
        }
        //dd(DB::getQueryLog());
        return view('layouts/photos')->with('pending_photos', $pending_photos)
        ->with('approved_photos', $approved_photos)
        ->with('events',$events)->with('event_id',$event_id);

    }

    /** download ALL */
    public function downloadAllPhotos(Request $request){
        // Define Dir Folder
        $public_dir=public_path()."/download/";
        // Zip File Name
        $zipFileName = 'photos.zip';
        $event_id=$request->event_id;

        $filetopath=$public_dir.'/'.$zipFileName;
        if (file_exists($filetopath)) {
            unlink($filetopath);
        }
        // Initializing PHP class
        $zip = new \ZipArchive();
        
        if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE)     
        {
            if($event_id=="0"){
                $photos =EventPhotos::select('event_photos.*','e.id as event_id','e.event_name')
                ->join('events as e','e.id','=','event_photos.event_id')
                //->whereNotNull('event_photos.photo')
                ->get(); #->latest()->paginate(10) 
            }
            else{
                $photos =EventPhotos::select('event_photos.*','e.id as event_id','e.event_name')
                ->join('events as e','e.id','=','event_photos.event_id')
                ->where('event_photos.event_id',$event_id)
                //->whereNotNull('event_photos.photo')
                ->get(); #->latest()->paginate(10)
            }
            
            foreach($photos as $p){
                #changed the downloaded photos to resized image instead of the original photo
                //if(empty($p->photo)){
                if(empty($p->resized_name)){
                    $file = $p->filename;
                }
                else{
                    $file = $p->resized_name;
                   // $file=$p->photo;
                }
                $photofile=$this->photos_path."/".$file;
                $relativeNameInZipFile = basename($file);
                if (file_exists($photofile)) {
                    $zip->addFile($photofile, $relativeNameInZipFile);
                }
            }
             
            $zip->close();
        }
    
        // Set Header
        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );
        
        // Create Download Response
        if(file_exists($filetopath)){
            return response()->download($filetopath,$zipFileName,['Content-Type: application/octet-stream']);
        }
      
    }

    /** download Selected photos */
    public function downloadPhotos(Request $request){
        
        // Define Dir Folder
        $public_dir=public_path()."/download/";
        // Zip File Name
        $zipFileName = 'photos.zip';
        $event_id=$request->event_id;

        $filetopath=$public_dir.'/'.$zipFileName;
        if (file_exists($filetopath)) {
            unlink($filetopath);
        }
        // Initializing PHP class
        $zip = new \ZipArchive();
        
        if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE)     
        {
            foreach($request->photoselect as $p)
            {
                $photos =EventPhotos::where('id',$p)->first();
                
                #changed the downloaded photos to resized image instead of the original photo
                //if(empty($photos->photo)){
                if(empty($photos->resized_name)){
                    $file = $photos->filename;
                }
                else{
                    // $file=$photos->photo;
                    $file = $photos->resized_name;
                }
                $photofile=$this->photos_path."/".$file;
                $relativeNameInZipFile = basename($file);
                if (file_exists($photofile)) {
                    $zip->addFile($photofile, $relativeNameInZipFile);
                }
            }
            $zip->close();
        }
    
        // Set Header
        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );
        
        // Create Download Response
        if(file_exists($filetopath)){
            return response()->download($filetopath,$zipFileName,['Content-Type: application/octet-stream']);
        }
    }

    public function deletePhotos(Request $request){
        //$uploaded_image = EventPhotos::where('original_name', basename($filename))->first();
        foreach($request->photoselect as $p)
        {
            $uploaded_image =EventPhotos::where('id',$p)->first();
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
        }
 
        return Response::json(['message' => 'File successfully deleted'], 200);
    }
    public function approvePhotos(Request $request){
        foreach($request->photoselect as $p)
        {
            $approve =EventPhotos::where('id',$p)->first();
            $approve->pending = 0;
            $approve->save();
        }
        return Response::json(['message' => 'File successfully approved'], 200);
    }
    public function approveAll(Request $request){
       // DB::enableQueryLog(); 
        $approve =EventPhotos::where('event_id',$request->event_id)->update(array('pending' => 0));
        //dd(DB::getQueryLog());
        return Response::json(['message' => 'All Pending Photos successfully approved'], 200);
    }
    public function deleteAllPhotos(Request $request){
        $deletes =EventPhotos::where('event_id',$request->event_id)->where('pending',$request->pending)->get();
        foreach($deletes as $p)
        {
            $uploaded_image =EventPhotos::where('id',$p->id)->first();
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
        }
 
        return Response::json(['message' => 'All Photos successfully deleted'], 200);
    }

}
