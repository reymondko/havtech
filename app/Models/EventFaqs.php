<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventFaqs extends Model
{
    protected $table = 'event_faqs';
    protected $primaryKey = 'id';
    
    public function events()
    {
        return $this->belongsTo('App\Events');
    }
}
