<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipamento;

class EquipamentoAPI extends Controller
{
    public static function equipamentoPorBaia($baia){
        $equipamentos = Equipamento::where("baia_id", $baia)->get();
        return $equipamentos;
    }

    public static function equipamentoAusente($id){
        Equipamento::where('id',$id)->update(array(
            'status_id'=>config('status_equipamento.AUSENTE')
        ));
    } 

    public static function equipamentoPresente($id){
        Equipamento::where('id',$id)->update(array(
            'status_id'=>config('status_equipamento.LIVRE')
        ));
    } 
}
