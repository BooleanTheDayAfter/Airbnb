<?php

namespace App;
use App\User;
use App\Img;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $table = 'apartments';

    protected $fillable = [
      'title',
      'description',
      'bathrooms',
      'price',
      'beds',
      'rooms',
      'metri_quadrati',
      'services',
      '_token',
      'address',
      'city',
      'cover_img'
    ];

    public function user(){
      return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function imgs(){
      return $this->hasMany('App\Img', 'apartment_id', 'id');
    }

    public function messages(){
      return $this->hasMany('App\Message', 'apartment_id', 'id');
    }

    public function position(){
      return $this->hasOne('App\Position', 'apartment_id', 'id');
    }

    public function sponsors(){
      return $this->hasMany('App\Sponsor', 'apartment_id', 'id');
    }
    public function services()
    {
      return $this->belongsToMany('App\Service', 'apartment_service');
    }
    public function views()
    {
      return $this->hasMany('App\View', 'apartment_id', 'id');
    }
    public function saveView()
    {
      $newView = new View;
      $newView->create([
        'apartment_id' => $this->id,
      ]);
      return $newView;
    }
}
