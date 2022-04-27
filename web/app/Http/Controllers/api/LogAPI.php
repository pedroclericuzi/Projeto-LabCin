<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChangeLog;

class LogAPI extends Controller
{
    public static function baiaLog($aluno, $monitor, $mensagem, $baia, $evento){
        $changeLog = new ChangeLog();
        $changeLog->mat_aluno = $aluno;
        $changeLog->mat_monitor = $monitor;
        $changeLog->mensagem = $mensagem;
        $changeLog->baia_id = $baia;
        $changeLog->evento_id = $evento;
        $changeLog->reserva_id = null;
        $changeLog->equipamento_id = null;
        $changeLog->save();
    }
}
