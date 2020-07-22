<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    
    public function travelfaqs()
    {
        return $this->hasMany('App\EventFaqs','event_id','id');
    }

    public function faqs()
    {
        return $this->hasMany('App\Models\EventFaqs','event_id','id');
    }

    public function schedules()
    {
        return $this->hasMany('App\Models\EventSchedule','event_id','id')->orderBy('start_date');
    }

    public function accomodations()
    {
        return $this->hasMany('App\Models\EventAccomodations','event_id','id');
    }

    public function dining()
    {
        return $this->hasMany('App\Models\EventDining','event_id','id');
    }

    public function transportations()
    {
        return $this->hasMany('App\Models\EventTransportation','event_id','id');
    }

    public function hosts()
    {
        return $this->hasMany('App\Models\EventTravelHosts','event_id','id');
    }

    public function maps()
    {
        return $this->hasMany('App\Models\EventMaps','event_id','id');
    }

    public function photos()
    {
        return $this->hasMany('App\Models\EventPhotos','event_id','id')->orderBy('id','desc');
    }

    public function approved_photos()
    {
        return $this->hasMany('App\Models\EventPhotos','event_id','id')->where('pending',0)->orderBy('id','desc');
    }

    public function attendees()
    {
        return $this->hasMany('App\Models\EventAttendees','event_id','id');
    }

    public function event_type()
    {
        return $this->hasOne('App\Models\EventTypes','id','event_types');
    }
}
