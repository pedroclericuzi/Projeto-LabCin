<?php

namespace App\Http\Controllers\portal;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PermisionsController
{
   
    static function notIsAluno(){
        $naoAluno = (Auth::user()->permissao_id == config('permissao.ALUNO_ID')) ? false : true;
        return $naoAluno;
    }

}
