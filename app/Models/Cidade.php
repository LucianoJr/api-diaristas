<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    /**
     * Definindo a relação com os diaristas
     */
    public function diaristas()
    {
        //informando qual tabela o model tem ligação e qual o tipo de ligação 
        return $this->belongsToMany(User::class, 'cidade_diarista');
    }
}
