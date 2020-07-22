<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\Events;
use App\Models\EventRegistrations;
use App\Models\EventAttendees;

class RegisterUserSpecialEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Register:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register user for Special Events';

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
        // Get all special events
        $event = Events::where('event_types','2')->get();
        foreach($event as $e){
            
            //get all attendees for the special event
            $attendees = EventAttendees::where('event_id', $e->id)->get();
            foreach($attendees as $attendee){
                //check if user already invite
                $checkRegistration = EventRegistrations::where('user_id',$attendee->user_id)->where('event_id',$e->id)->first();
                if(empty($checkRegistration)){
                    $user = Users::where('id',$attendee->user_id)->first();
                    $registration = new EventRegistrations;
                    $registration->user_id = $user->id;
                    $registration->event_id = $e->id;
                    $registration->first_name = $user->first_name ?? 'N/A';
                    $registration->last_name = $user->last_name  ?? 'N/A';
                    $registration->company = $user->company  ?? 'N/A';
                    $registration->title = $user->title  ?? 'N/A';
                    $registration->industry = $user->industry  ?? 'N/A';
                    $registration->email = $user->email  ?? 'N/A';
                    $registration->telephone = $user->telephone  ?? 'N/A';
                    $registration->save();
                }
                
            }
        }
    }
}
