<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationRecipients extends Model
{
    protected $table = 'event_notifications_history';
    protected $primaryKey = 'id';
    
    public function events()
    {
        return $this->belongsTo('App\Events');
    }

    public function notification(){
        return $this->hasOne('App\Models\Notifications','id','notifications_id');
    }

    public function recipient_onesignal_player_id(){
        return $this->hasOne('App\Models\OnesignalUserPlayerIds','user_id','user_id');
    }
}
