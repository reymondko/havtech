<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealTypes extends Model
{
    protected $table = 'meal_types';
    protected $primaryKey = 'id';
    
    public function events()
    {
        return $this->belongsTo('App\Events');
    }
}
