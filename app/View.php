<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $table = 'views';

    protected $guarded = [];

    public function apartment(){
        return $this->belongsTo('App\Apartment', 'apartment_id', 'id');
    }
}

