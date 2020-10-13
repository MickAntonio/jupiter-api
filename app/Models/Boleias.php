<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boleias extends Model
{
    public function solicitante(){
        return $this->belongsTo(Funcionarios::class);
    }


    public function motorista(){
        return $this->belongsTo(Funcionarios::class);
    }
}
