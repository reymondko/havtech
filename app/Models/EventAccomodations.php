<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAccomodations extends Model
{
    protected $table = 'event_accomodations';
    protected $primaryKey = 'id';
    
    public function events()
    {
        return $this->belongsTo('App\Events');
    }
}
