<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function genres(){
        return $this->hasMany('App\GameGenre');
    }
}
