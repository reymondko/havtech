<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTypes extends Model
{
    protected $table = 'event_type';
    protected $primaryKey = 'id';
    public function events()
    {
        return $this->belongsTo('App\Events');
    }

}
