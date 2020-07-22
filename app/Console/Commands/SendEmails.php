<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Events;
use App\Models\Users;
use App\Models\EventAttendees;
use App\Models\EventSchedule;
use App\Mail\SendEventInvites;
use Spatie\CalendarLinks\Link;
use Storage;
use DateTime;
use Mail;
use Log;
use DB;
class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sendinvites'; // {event_id}

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send event e-mails invites to invited users';

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
     *
     * @return mixed
     */
    public function handle()
    {
        /*$event_id = $this->argument('event_id');
        $attendees = EventAttendees::where('event_id',$event_id)
        ->join('users','users.id',"=",'user_id')->get();*/
        //DB::enableQueryLog();
        $attendees = DB::table('event_attendees')->where('event_attendees.email_sent',"=","0")
        ->where('event_attendees.email_sent_approved',"=","1")
        ->join('users','users.id',"=",'user_id')
        ->join('events','events.id',"=",'event_attendees.event_id')
        ->where('visibility_web','Published')
        ->get(['event_attendees.event_id','event_attendees.email_sent','event_attendees.id','users.email','users.first_name','users.last_name']);
        //dd(DB::getQueryLog());
        $Emails = array();
        foreach($attendees as $attendee){
            /*
            old email
            $data = new \stdClass();
            $data->sender = 'Havtech EventsHub';
            $data->email = $attendee->email;
            #$data->temporary_pw_status= $attendee->temporary_pw_status;
            $data->password = $attendee->email;
            Mail::to($attendee->email)->send(new SendEventInvites($data));
            $affectedRows = EventAttendees::where('id', $attendee->id)->update(['email_sent' => 1]);
            //$affectedRows = EventAttendees::where('user_id', $attendee->user_id)->update(['email_sent' => 1]);
            */
            $registration_event = Events::where('id',$attendee->event_id)->first();

            if($registration_event){
                if(isset($registration_event->cost_per_person)){
                    $amount=preg_replace('/\s+/', '', str_replace("$",'',($registration_event->cost_per_person)));
                  }
                  else{
                    $amount = 0;
                  }
                $time = null;
                $first_sched = date("Y-m-d");
                $last_sched = date("Y-m-d");
                // check first event schedule
                $first_schedule = EventSchedule::where('event_id',$registration_event->id)
                                ->orderBy('start_date','asc')
                                ->first();
                // check first event schedule
                $last_schedule = EventSchedule::where('event_id',$registration_event->id)
                                ->orderBy('end_date','desc')
                                ->first();
                if($first_schedule && strlen($first_schedule->start_date)){
                    $first_sched = $first_schedule->start_date;
                    $last_sched = $last_schedule->end_date;
                    if($first_schedule->location=="Room Number"){
                        $address=$first_schedule->room_number;
                    }
                    else{
                        $address=$first_schedule->location_address.", ".$first_schedule->cname." ".$first_schedule->city." ".$first_schedule->state." ".$first_schedule->zip." ".$first_schedule->country_name;
                    }
                }
                else{
                    if($registration_event->start_date){
                        $first_sched = $registration_event->start_date;
                        $last_sched = $registration_event->end_date;
                        $address = "";
                    }
                    else{
                        $first_sched = date("Y-m-d");
                        $last_sched = date("Y-m-d");
                    }
                }
                //print $first_sched." ".$last_sched;die();
                $calendar_description = $registration_event->custom_calendar_message ?? '';
                if($first_sched <= $last_sched){
                    $calendarLink =  Link::create(
                            $registration_event->event_name,
                            DateTime::createFromFormat('Y-m-d H:i:s',$first_sched),
                            DateTime::createFromFormat('Y-m-d H:i:s',$last_sched))
                            ->description($calendar_description)
                            ->address($address);
                        

                    if($first_schedule){
                        $time =  date("h:i:A",strtotime($first_schedule->start_date));
                    }


                    $to_email = $attendee->email;
                    $to_name = $attendee->first_name.' '.$attendee->last_name;

                    $ics=$calendarLink->ics();
                    $encodedData = str_replace(' ','+',$ics);
                    $decodedData = base64_decode($encodedData);
                    $custom_calendar_message = strip_tags(str_replace('</p>', "</p>\r\n", $registration_event->custom_calendar_message));

                    //date_default_timezone_set('US/Eastern');
                    $start = date("Ymd\THis",strtotime($first_sched));
                    $end = date("Ymd\THis",strtotime($last_sched));
                // echo $start." - ".$end;die();
                    //echo date_default_timezone_get(); die();

                    //render ICS file
                    $vCalendar = new \Eluceo\iCal\Component\Calendar('www.havtechevents.com');
                    $vEvent = new \Eluceo\iCal\Component\Event();

                    $tz  = 'America/New_York';
                    $dtz = new \DateTimeZone($tz);
                    date_default_timezone_set($tz);

                    /*
                    // 2. Create timezone rule object for Daylight Saving Time
                    $vTimezoneRuleDst = new \Eluceo\iCal\Component\TimezoneRule(\Eluceo\iCal\Component\TimezoneRule::TYPE_DAYLIGHT);
                    $vTimezoneRuleDst->setTzName('CEST');
                    $vTimezoneRuleDst->setDtStart(new \DateTime('1981-03-29 02:00:00', $dtz));
                    $vTimezoneRuleDst->setTzOffsetFrom('+0100');
                    $vTimezoneRuleDst->setTzOffsetTo('+0200');
                    $dstRecurrenceRule = new \Eluceo\iCal\Property\Event\RecurrenceRule();
                    $dstRecurrenceRule->setFreq(\Eluceo\iCal\Property\Event\RecurrenceRule::FREQ_YEARLY);
                    $dstRecurrenceRule->setByMonth(3);
                    $dstRecurrenceRule->setByDay('-1SU');
                    $vTimezoneRuleDst->setRecurrenceRule($dstRecurrenceRule);

                    // 3. Create timezone rule object for Standard Time
                    $vTimezoneRuleStd = new \Eluceo\iCal\Component\TimezoneRule(\Eluceo\iCal\Component\TimezoneRule::TYPE_STANDARD);
                    $vTimezoneRuleStd->setTzName('CET');
                    $vTimezoneRuleStd->setDtStart(new \DateTime('1996-10-27 03:00:00', $dtz));
                    $vTimezoneRuleStd->setTzOffsetFrom('+0200');
                    $vTimezoneRuleStd->setTzOffsetTo('+0100');
                    $stdRecurrenceRule = new \Eluceo\iCal\Property\Event\RecurrenceRule();
                    $stdRecurrenceRule->setFreq(\Eluceo\iCal\Property\Event\RecurrenceRule::FREQ_YEARLY);
                    $stdRecurrenceRule->setByMonth(10);
                    $stdRecurrenceRule->setByDay('-1SU');
                    $vTimezoneRuleStd->setRecurrenceRule($stdRecurrenceRule);

                    // 4. Create timezone definition and add rules
                    $vTimezone = new \Eluceo\iCal\Component\Timezone($tz);
                    $vTimezone->addComponent($vTimezoneRuleDst);
                    $vTimezone->addComponent($vTimezoneRuleStd);
                    $vCalendar->setTimezone($vTimezone);*/

                    $vEvent->setDtStart(new \DateTime($start, new \DateTimeZone('America/New_York')))
                        ->setDtEnd(new \DateTime($end, new \DateTimeZone('America/New_York')))
                        ->setLocation($address)
                        //->setUseTimezone(true)
                        ->setSummary($registration_event->event_name)
                        ->setDescription(str_replace("&nbsp;"," ",$custom_calendar_message))
                        ->setDescriptionHTML($registration_event->custom_calendar_message);
                    $vCalendar->addComponent($vEvent);
                    $ics = $vCalendar->render();

                    \Log::info("ICS: ".$ics);

                    //$icsname = str_replace(' ','',$registration_event->event_name);
                    $icsname = 'HavtechEvent-'.$attendee->event_id;
                    Storage::disk('public')->put("ics/".$icsname.'.ics', $ics);
                    $ics_link= Storage::disk('public')->url("ics/".$icsname.'.ics');
                    $data = [
                        'event_name' => $registration_event->event_name,
                        'date' => date("l, F j, Y",strtotime($registration_event->start_date)),
                        'enddate' => date("l, F j, Y",strtotime($registration_event->end_date)),
                        'time' => $time,
                        'cost' => $amount,
                        'google_calendar_url' => $calendarLink->google(),
                        'outlook_calendar_url' => $ics_link,
                        'ics' => $ics_link,
                    ];

                    //$to_email = 'mark@mojo.biz';
                    //$to_email = 'reymondb@codev.com';
                    $event_name=$registration_event->event_name;

                    Mail::send('emails.eventinvites',$data, function($message) use ($to_email, $to_name, $ics,$event_name) {
                        $message->to($to_email, $to_name)
                                ->subject('You Have Been Invited ('.$event_name.')');
                        $message->from('marketing@havtech.com','Havtech Events');
                        $message->attachData($ics, 'Havtech Event.ics', ['mime' => 'data:text charset=utf8']);
                    });
                }
                $affectedRows = EventAttendees::where('id', $attendee->id)->update(['email_sent' => 1]);
            }
        }
        $log = ' Invites Sent ';
            //$log .= $notification->id;
            $log .= ' Sent: Total invites Recipients = ';
            $log .= count($attendees);
            Log::info($log);
    }
}
