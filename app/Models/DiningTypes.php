<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiningTypes extends Model
{
    protected $table = 'dining_types';
    protected $primaryKey = 'id';

    public function events()
    {
        return $this->belongsTo('App\Events');
    }
}
