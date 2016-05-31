<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gamer extends Model
{
    public function games(){
        return $this->hasMany('App\GamerGame');
    }
}