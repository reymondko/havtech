<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Notifications;
use App\Models\NotificationRecipients;
use App\Models\Events;
use App\Models\EventAttendees;
use App\Models\EventRegistrations;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use App\Imports\UsersImport;
use App\Models\OnesignalUserPlayerIds;
use Twilio\Rest\Client;
use Maatwebsite\Excel\Facades\Excel;

use DB;
use Session;
use Response;

class NotificationsController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifs=Notifications::all();  
        
        return view('layouts/notifications')->with('notifications', $notifs);

    }
    
    public function NewNotification()
    {
        $users = Users::ALL();
        $events=Events::orderBy('event_name')->get();
        return view('layouts/newnotifications')->with('users', $users)->with('events',$events);
    }

    public function userList(Request $request)
    {
        $search_user=$request->search_user;
        $notif_id=$request->notif_id;
        
        $event_id=$request->event_id;
        if($event_id=="0"){
            //DB::enableQueryLog(); 
            $users = Users::where(function($q) use($search_user) {
                $q->where('users.first_name', 'like', '%'.$search_user.'%')            
                ->orWhere('users.last_name', 'like', '%'.$search_user.'%');
             })
            ->leftJoin('event_notifications_history as eh' , function($q) use ($notif_id)
            {
                $q->on('users.id', '=', 'eh.user_id')
                    ->where('eh.notifications_id', '=', $notif_id);
            })
            ->where('users.enabled',1)->get(['users.id','users.first_name','users.last_name','eh.id as inv_id']);
            //dd(DB::getQueryLog()); 
        }
        else{
           
            $event=Events::where('id', $event_id)->first();
            //DB::enableQueryLog(); 
            if($event->event_types == "2"){
                $users=EventAttendees::where('event_id',$event_id)
                ->leftjoin('users','users.id', 'event_attendees.user_id')->where('event_attendees.user_id',"!=","")
                ->where(function($q) use($search_user) {
                    $q->where('users.first_name', 'like', '%'.$search_user.'%')            
                    ->orWhere('users.last_name', 'like', '%'.$search_user.'%');
                 })
                 
                ->leftJoin('event_notifications_history as eh' , function($q) use ($notif_id)
                {
                    $q->on('users.id', '=', 'eh.user_id')
                        ->where('eh.notifications_id', '=', $notif_id);
                })
                ->where('users.enabled',1)
                ->get();
            }
            else{
                $users=EventRegistrations::where('event_id',$event_id)
                ->leftjoin('users','users.id', 'event_registrations.user_id')->where('event_registrations.user_id',"!=","")
                ->where(function($q) use($search_user)  {
                    $q->where('users.first_name', 'like', '%'.$search_user.'%')            
                    ->orWhere('users.last_name', 'like', '%'.$search_user.'%');
                 })
                 
            ->leftJoin('event_notifications_history as eh' , function($q) use ($notif_id)
            {
                $q->on('users.id', '=', 'eh.user_id')
                    ->where('eh.notifications_id', '=', $notif_id);
            })
            ->where('users.enabled',1)
                ->get();
            }
           // dd(DB::getQueryLog());
        }
        $data = array(
            'users' => json_decode($users, true)            
            //'attendees' => json_decode($attendees, true)
        );
        return $data;
    }

    public function editNotification($id)
    {
        $Notification = Notifications::where('id',$id)->first();
        //DB::enableQueryLog();
        $users = DB::table('users')
        ->leftJoin('event_notifications_history as eh' , function($q) use ($id)
        {
            $q->on('users.id', '=', 'eh.user_id')
                ->where('eh.notifications_id', '=', $id);
        })
        ->where('users.enabled',1)
        ->get(['users.id','users.first_name','users.last_name','eh.id as inv_id']);
        $events=Events::orderBy('event_name')->get();
        
        //dd(DB::getQueryLog());
        return view('layouts/editnotifications')->with('users', json_decode($users, true))->with('n',$Notification)->with('events',$events);
    }
    
    public function CreateNotification(Request $request)
    {
        //dd($request);
            if($request->notif_id == 0){
                $notif = new Notifications;
            }
            else{
                $notif=Notifications::where('id',$request->notif_id)->first();
            }   

            //$notif = new Notifications;
            $date=date("Y-m-d H:i:s",strtotime($request->notif_date." ".$request->start_time));    
            $notif->title = $request->notification_title;
            $notif->notif_date = $date;
            $notif->description = $request->description;
            $notif->button_link = $request->button_link;
            $notif->with_button_url = $request->with_button_url;
            $notif->button_url = $request->button_url;
            $notif->visibility = $request->visibility;
            $notif->send_as = $request->send_as;
            
            $notif->save();
            $notif_id=$notif->id;
            if($request->recipient){
                foreach($request->recipient as $r){
                    //DB::enableQueryLog();
                    $notifz=NotificationRecipients::where('notifications_id',$notif_id)->where('user_id',$r)->get();
                    //dd(DB::getQueryLog());

                    if($notifz->isEmpty()){
                        $notifr = new NotificationRecipients;
                        $notifr->notifications_id=$notif_id;
                        $notifr->user_id=$r;
                        $notifr->save();
                    }
                }
            }

            $description = $request->description;
            if($notif->button_link == 1){
                $description .= ' ' . $notif->button_url;
            }
        // $this->sendNotificationToOnesignal($request->recipient,$request->notification_title,$description);
        return Response::json(array('success' => true), 200);
    }
    public function updateNotification(Request $request){
        $date=date("Y-m-d H:i:s",strtotime($request->notif_date." ".$request->start_time));    
        $notif = Notifications::where('id',$request->notif_id)->first();
        $notif->title = $request->notification_title;
        $notif->notif_date = $date;
        $notif->description = $request->description;
        $notif->button_link = $request->button_link;
        $notif->with_button_url = $request->with_button_url;
        $notif->button_url = $request->button_url;
        $notif->visibility = $request->visibility;
        $notif->send_as = $request->send_as;
        
        $notif->save();

        /*//removerecpients */
        NotificationRecipients::where('notifications_id', $request->notif_id)->delete();
       
        //check if there is checked recipient then re insert if there is
        if($request->recipient){
            foreach($request->recipient as $r){
                $notifr = new NotificationRecipients;
                $notifr->notifications_id=$request->notif_id;
                $notifr->user_id=$r;
                $notifr->save();
            }
        }
        return Response::json(array('success' => true), 200);
    }
    
    public function notificationDelete(Request $request){
        $deleterecipients=NotificationRecipients::where('notifications_id',$request->notif_id);
        $deleterecipients->delete();
        $delete=Notifications::where('id', $request->notif_id)->first();
        $delete->delete();
        return $this->index();
    }

    public function addRecipient(Request $request)
    { 
        if($request->notif_id == 0){
            $date=date("Y-m-d H:i:s",strtotime($request->notif_date." ".$request->start_time));    
            $notif = new Notifications;
            $notif->title = $request->notification_title;
            $notif->notif_date = $date;
            $notif->description = $request->description;
            $notif->button_link = $request->button_link;
            $notif->with_button_url = $request->with_button_url;
            $notif->button_url = $request->button_url;
            $notif->visibility = $request->visibility;
            $notif->save();
            $notif_id=$notif->id;
        }
        else{
            $notif_id=$request->notif_id;
        }
        $notifz=NotificationRecipients::where('notifications_id',$notif_id)->where('user_id',$request->userid)->get();
        if($notifz->isEmpty()){
            $notifr = new NotificationRecipients;
            $notifr->notifications_id=$notif_id;
            $notifr->user_id=$request->userid;
            if($notifr->save()){
                return $notif_id;
            }
        }
        return $notif_id;
    }
    public function removeRecipient(Request $request)
    { 
        $uninvite = NotificationRecipients::where('notifications_id', $request->notif_id)
        ->where('user_id', $request->userid)->delete();
        if($uninvite){
            return Response::json(array('success' => true), 200);
        }
    }
    public function addRecipientAll(Request $request)
    { 
        if($request->notif_id == 0){
            $date=date("Y-m-d H:i:s",strtotime($request->notif_date." ".$request->start_time));    
            $notif = new Notifications;
            $notif->title = $request->notification_title;
            $notif->notif_date = $date;
            $notif->description = $request->description;
            $notif->button_link = $request->button_link;
            $notif->with_button_url = $request->with_button_url;
            $notif->button_url = $request->button_url;
            $notif->visibility = $request->visibility;
            $notif->save();
            $notif_id=$notif->id;
        }
        else{
            $notif_id=$request->notif_id;
        }
       
        $users = Users::ALL();
        //DB::enableQueryLog();
        foreach($users as $u){
            $invite = NotificationRecipients::where('notifications_id', $notif_id)->where('user_id',$u->id)->get();
            if($invite->isEmpty()){
               
                $notifr = new NotificationRecipients;
                $notifr->notifications_id=$notif_id;
                $notifr->user_id=$u->id;
                $notifr->save();
            }
        }
        //$query = DB::getQueryLog();
        //print_r($query);die();
        return $notif_id;
    }
        
      
    public function removeRecipientAll(Request $request)
    { 
        $uninvite = NotificationRecipients::where('notifications_id', $request->notif_id)->delete();
        if($uninvite){
            return Response::json(array('success' => true), 200);
        }
    }

    /**
     * Send onesignal notification
     * 
     * @param Array $user_ids
     *  User ids to send the notifications to
     * @param String title 
     *  title of the notification
     * @param Message
     *  Message of the notification
     */
    private function sendNotificationToOnesignal($user_ids,$title,$message){
        $onesignal_player_ids = OnesignalUserPlayerIds::whereIn('user_id',$user_ids)
                                ->pluck('onesignal_player_id')
                                ->toArray();
                                
        if(count($onesignal_player_ids) > 0){

            $message = strip_tags($message);
            $message = str_replace('&nbsp;',' ',$message);
            $nbsp = html_entity_decode("&nbsp;");
            $message = html_entity_decode($message);
            $message = str_replace($nbsp, " ", $message);
            $message = str_replace('&nbsp;',' ',$message);

            // Onesignal Push
            $heading = array(
                "en" => $title
            );
            $subtitle = array(
                "en" => ''
            );
            $content = array(
                "en" => $message
            );

            $fields = array(
              'app_id' => "9a6fca07-fd3d-4195-a53b-e059b93d2748",
              'include_player_ids' => $onesignal_player_ids,
              'data' => array("foo" => "bar"),
              'large_icon' =>"ic_launcher_round.png",
              'contents' => $content,
              'headings' => $heading,
              'subtitle' => $subtitle
            );

            $fields = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                    'Authorization: Basic NmYyYmU4OWYtMzMyYS00MTIxLWE2Y2MtNTY3MGY1ZGNmYjQ5'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    
        
            $response = curl_exec($ch);
            curl_close($ch);
          }
    }
    
    /**
     * Send Text notification via Twilio
     * 
     * @param Array $phonenumbers
     *  phone numbers to send the notifications to
     * @param Message
     *  Message of the notification
     */
    public function sendSms( $phone_numbers, $message )
    {
       // Your Account SID and Auth Token from twilio.com/console
       $sid    = env( 'TWILIO_SID' );
       $token  = env( 'TWILIO_TOKEN' );
       $client = new Client( $sid, $token );

        $count = 0;

        foreach( $phonenumbers as $number )
        {
            $count++;
            $client->messages->create(
                $number,
                [
                    'from' => env( 'TWILIO_NUMBER' ),
                    'body' => $message,
                ]
            );
            
            Log::info("sms sent to $number");
        }
    }
}
