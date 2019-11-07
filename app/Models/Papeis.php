<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Papeis extends Model
{
    public function usuario(){
        return $this->belongsToMany(User::class, 'usuario_papel', 'papel_id', 'usuario_id');
    }
}
