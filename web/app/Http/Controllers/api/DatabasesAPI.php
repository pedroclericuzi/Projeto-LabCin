<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Baia;
use App\Models\ChangeLog;
use App\Models\Equipamento;
use App\Models\Permissao;
use App\Models\Reserva;
use App\Models\StatusBaia;
use App\Models\StatusEquipamento;
use App\Models\StatusReserva;
use App\Models\StatusUser;
use App\Models\TipoEquipamento;
use App\Models\TipoEvento;
use App\Models\User;
use Carbon\Carbon;

class DatabasesAPI extends Controller
{
    function getAllData(){
        $statusBaia = StatusBaia::orderBy('updated_at', 'ASC')->get();
        $statusReserva = StatusReserva::orderBy('updated_at', 'ASC')->get();
        $status_equipamento = StatusEquipamento::orderBy('updated_at', 'ASC')->get();
        $status_user = StatusUser::orderBy('updated_at', 'ASC')->get();
        $tipoEquipamento = TipoEquipamento::orderBy('updated_at', 'ASC')->get();
        $tipoEvento = TipoEvento::orderBy('updated_at', 'ASC')->get();
        $changeLog = ChangeLog::orderBy('updated_at', 'ASC')->get();
        $permissao = Permissao::orderBy('updated_at', 'ASC')->get();
        $user = User::orderBy('updated_at', 'ASC')->get();
        $baia = Baia::orderBy('updated_at', 'ASC')->get();
        $reserva = Reserva::orderBy('updated_at', 'ASC')->get();
        $equipamento = Equipamento::orderBy('updated_at', 'ASC')->get();

        return response()->json([
            'code' => 200,
            'status_baia' => $statusBaia,
            'status_reserva' => $statusReserva,
            'status_user' => $status_user,
            'permissao' => $permissao,
            'status_equipamento' => $status_equipamento,
            'tipo_equipamento' => $tipoEquipamento,
            'tipo_evento' => $tipoEvento,
            'user' => $user,
            'baia' => $baia,
            'reserva' => $reserva,
            'change_log' => $changeLog,
            'equipamento' => $equipamento,
        ]);

    }

    function selectTableByDate($table, $date) {
        return response()->json([
            'code' => 200,
            'data' => $this->getDataByPeriod($table, $date)
        ]);
    }

    function getDataByPeriod($table, $date){
        $dataFormatoCorreto = date("Y-m-d H:i:s", strtotime($date));
        $now = date('Y-m-d H:i:s', time());
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $dataFormatoCorreto)->subHours(3)->addSeconds(1);
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $now);
        switch ($table) {
            case 'permissao':
                return Permissao::whereBetween('updated_at', [$startDate, $endDate])->get();
            case 'status_user':
                return StatusUser::whereBetween('updated_at', [$startDate, $endDate])->get();
            case 'status_equipamento':
                return StatusEquipamento::whereBetween('updated_at', [$startDate, $endDate])->get();
            case 'status_reserva':
                return StatusReserva::whereBetween('updated_at', [$startDate, $endDate])->get();
            case 'tipo_equipamento':
                return TipoEquipamento::whereBetween('updated_at', [$startDate, $endDate])->get();
            case 'tipo_evento':
                return TipoEvento::whereBetween('updated_at', [$startDate, $endDate])->get();
            case 'status_baia':
                return StatusBaia::whereBetween('updated_at', [$startDate, $endDate])->get();
            case 'user':
                return User::whereBetween('updated_at', [$startDate, $endDate])->get();
            case 'baia':
                return Baia::whereBetween('updated_at', [$startDate, $endDate])->get();
            case 'reserva':
                return Reserva::whereBetween('updated_at', [$startDate, $endDate])->get();
            case 'change_log':
                return ChangeLog::whereBetween('updated_at', [$startDate, $endDate])->get();
            case 'equipamento':
                return Equipamento::whereBetween('updated_at', [$startDate, $endDate])->get();
            default:
                return "A tabela ". $table ." não existe";
                break;
        }
    }
}
