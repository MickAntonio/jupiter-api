<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Viagens extends Model
{
    protected $table = 'viagens';

    public function enderecos(){
        return $this->hasMany(ViagensEnderecos::class, 'viagem_id');
    }

    public function usuario(){
        return $this->belongsTo(User::class);
    }

    public function van(){
        return $this->belongsTo(Vans::class);
    }

    public function motorista(){
        return $this->belongsTo(Funcionarios::class);
    }

}
