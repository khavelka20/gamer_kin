<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameGenre extends Model
{
    public function genre(){
        return $this->hasOne('App\Genre');
    }
}
