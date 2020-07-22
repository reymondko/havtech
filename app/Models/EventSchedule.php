<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
    protected $table = 'event_schedule';
    protected $primaryKey = 'id';
    
    public function events()
    {
        return $this->belongsTo('App\Events');
    }
}
