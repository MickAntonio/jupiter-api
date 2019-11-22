<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelos extends Model
{
    public function marca(){
        return $this->belongsTo(Marcas::class, 'marca_id');
    }
}
