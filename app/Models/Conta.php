<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    protected $table = 'contas';

    protected $fillable = ['jogo', 'usuario', 'senha', 'senha_texto_claro'];
}
