<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Escalas;
use App\Models\Contactos;
use App\Models\FeedBacks;
use App\Models\Moradas;
use App\Models\Localizacoes;

class Funcionarios extends Model
{
    public function usuario(){
        return $this->belongsTo(User::class);
    }

    public function contactos(){
        return $this->hasMany(FuncionarioContactos::class);
    }

    public function escala(){
        return $this->belongsToMany(Escalas::class, 'funcionario_escala', 'funcionario_id', 'escala_id');
    }

    public function feed_backs(){
        return $this->hasMany(FeedBacks::class);
    }

    public function morada(){
        return $this->hasOne(Moradas::class);
    }

    public function localizacoes(){
        return $this->hasMany(Localizacoes::class);
    }

}
