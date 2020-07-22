<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPhotos extends Model
{
    protected $table = 'event_photos';
    protected $primaryKey = 'id';
    
    public function events()
    {
        return $this->belongsTo('App\Events')->where('pending','=',0);
    }
}
