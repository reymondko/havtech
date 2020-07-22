<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = 'event_notifications';
    protected $primaryKey = 'id';
    
    public function events()
    {
        return $this->belongsTo('App\Events');
    }

    public function notification_recipients(){
        return $this->hasMany('App\Models\NotificationRecipients','notifications_id','id');
    }
}
