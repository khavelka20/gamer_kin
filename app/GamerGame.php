<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GamerGame extends Model
{
    public function game(){
        return $this->belongsTo('App\Game');
    }
}
