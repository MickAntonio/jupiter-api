<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vans extends Model
{
    public function contactos(){
        return $this->hasMany(VanContactos::class);
    }
}
