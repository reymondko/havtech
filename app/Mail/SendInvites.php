<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInvites extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'marketing@havtech.com';
        $subject = 'You have been invited!';
        $name = 'Havtech Events';
        $test=$this->data;
        //echo "come ".$test['sender'];die();
        //print_r($test);die();
        //dd($test);
        return $this->from($address, $name)
        ->cc($address, $name)
        ->bcc($address, $name)
        ->replyTo($address, $name)
        ->subject($subject)
        ->view('mail.invite')->with('data',$this->data);
    }
}
