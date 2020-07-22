<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDining extends Model
{
    protected $table = 'event_dining';
    protected $primaryKey = 'id';

    public function events()
    {
        return $this->belongsTo('App\Events');
    }

    public function type(){
        return $this->hasOne('App\Models\DiningTypes','id','dining_type');
    }
}
