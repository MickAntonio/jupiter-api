<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Viagens extends Model
{
    protected $table = 'viagens';

    public function enderecos(){
        return $this->hasMany(ViagensEnderecos::class, 'viagem_id');
    }
}
