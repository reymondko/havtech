<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTransportation extends Model
{
    protected $table = 'event_transportation';
    protected $primaryKey = 'id';
    
    public function events()
    {
        return $this->belongsTo('App\Events');
    }
}
