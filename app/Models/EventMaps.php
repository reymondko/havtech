<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventMaps extends Model
{
    protected $table = 'event_maps';
    protected $primaryKey = 'id';
    
    public function events()
    {
        return $this->belongsTo('App\Events');
    }
}
