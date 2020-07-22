<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDateTime extends Model
{
    protected $table = 'event_date_time';
    protected $primaryKey = 'id';
    
    public function events()
    {
        return $this->belongsTo('App\Events');
    }
}
