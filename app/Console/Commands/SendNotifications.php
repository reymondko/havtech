<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Notifications;
use App\Models\NotificationRecipients;
use App\Models\Users;
use Carbon\Carbon;
use Twilio\Rest\Client;
use Log;
use DB;
class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * Retrieve all unsent published notifications 5 minutes ago
     * 
     * @return mixed
     */
    public function handle()
    {
        $date = new \DateTime("now",new \DateTimeZone('America/New_York'));
        
        $date_end = $date->format('Y-m-d H:i:s');
        $date->modify('-10 minutes');
        $date_start = $date->format('Y-m-d H:i:s');

        //DB::enableQueryLog();
        
        $unsent_notifications = Notifications::with('notification_recipients','notification_recipients.recipient_onesignal_player_id')
                                            ->where('visibility','Published')
                                            ->where('is_sent',0)
                                            ->whereBetween('notif_date',[$date_start,$date_end])
                                            ->get();
        
        //$query = DB::getQueryLog();
        //print_r($query);
        if($unsent_notifications){
            foreach($unsent_notifications as $notification){
                $recipients = $notification->notification_recipients;
                $log_player_ids = [];
                $log_phone_numbers = [];
                $player_ids = [];
                $phone_numbers = [];
                #test number:
                #$phone_numbers[] ="17653604497";            
                foreach($recipients as $recipient){
                    $user = Users::where('id',$recipient->user_id)->first();
                    if($user){
                        try{
                            $to_email =  $user->email;
                            $to_name  = $user->first_name." ".$user->last_name;
                            //check if user can receive notifications
                            if($user->receive_notifications=="1"){
                                if(isset($recipient->recipient_onesignal_player_id)){
                                    $log_player_ids[] = [
                                        $user->email => $recipient->recipient_onesignal_player_id->onesignal_player_id
                                    ];
                                    $player_ids[] = $recipient->recipient_onesignal_player_id->onesignal_player_id;
                                }
                                if(isset($user->phone)){
                                    $log_phone_numbers[] = [
                                        $user->email => $user->phone
                                    ];
                                    $phonenum = preg_replace("/[^0-9]/", "", $user->phone );
                                    $phone_numbers[]=$phonenum;
                                }
                                if($notification->send_as =="email_notification" || $notification->send_as =="both"){
                                    $data = [
                                        'titlez' => $notification->title,
                                        'messagez' => $notification->description
                                    ];
                                    print $user->id." ".$to_email ." - ".$notification->title ." - ".$notification->description;
                                    //$to_email = 'mark@mojo.biz';
                                    //$to_email = 'mojodevtesting@gmail.com';  ." [".$startz."-".$endz."] "
                                    Mail::send('emails.notification',$data, function($message) use ($to_email, $to_name) {
                                        $message->to($to_email, $to_name)
                                                ->subject('A Notification has been sent to you.');
                                        $message->from('marketing@havtech.com','Havtech Events');
                                    });
                                    
                                }
                            }
                        }catch(\Exception $e){
                            Log::info("NOTIFICATION ERROR: " . $e->getMessage());
                        }
                        
                    }
                }
                print_r($phone_numbers);
                
                $log = ' NOTIFICATION COMMAND VERBOSE LOG: ';
                $log .= $notification->id;
                Log::info($log);
               
                if($notification->send_as =="push_notification" || $notification->send_as =="both")
                {
    
                    $log = 'ONESIGNAL NOTIFICATION PLAYER IDS: ';
                    $log .= json_encode($log_player_ids);
                    Log::info($log);
    
                    if(count($player_ids) > 0){
                       $onesignelResult = $this->sendNotificationToOnesignal($player_ids,$notification->title,$notification->description);
                       $log = 'ONESIGNAL RESULT: ';
                       $log .= $onesignelResult;
                       Log::info($log);
                    }
                }
                if($notification->send_as =="text_notification" || $notification->send_as =="both"){
                    $log = 'TWILIO LOG NOTIFICATION LOG START: ';
                    $log .= json_encode($log_phone_numbers);
                    Log::info($log);
                    if(count($phone_numbers) > 0){
                        $this->sendSms($phone_numbers,$notification->description);
                    }
                    
                } 
                
                $notification->is_sent = 1;
                $notification->save();
                
                Log::info('==================END NOTIFICATION COMMAND=======================');
               
            }
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
    private function sendNotificationToOnesignal($player_ids,$title,$message){
        if(count($player_ids) > 0){

            $message = strip_tags($message);
            $message = str_replace('&nbsp;','',$message);

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
              'include_player_ids' => $player_ids,
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

            return $response;
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
        
        $message = str_replace("<br>","\n",$message);
        $message = strip_tags($message);

        $count = 0;

        foreach( $phone_numbers as $number )
        {
            $count++;

            try{
                $client->messages->create(
                    $number,
                    [
                        'from' => env( 'TWILIO_NUMBER' ),
                        'body' => $message,
                    ]
                );
                Log::info("SMS SENT TO: $number");
            }catch(\Exception $e){
                Log::info("SMS NOT SENT TO: $number");
                Log::info("ERROR: " . $e->getMessage());
            }
        }
    }
}
