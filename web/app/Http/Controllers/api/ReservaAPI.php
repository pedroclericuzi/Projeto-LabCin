<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Reserva;

class ReservaAPI extends Controller
{

    public function getAll()
    {
        $reservas = Reserva::all();
        return response()->json([
            'code' => 200,
            'data' => $reservas,
        ]);
    }

    public function getOne($id)
    {
        $reserva = Reserva::where('id', $slug)->first();
        return response()->json([
            'code' => 200,
            'data' => $reservas,
        ]);

    }

    public static function check($user, $date, $time, $baiaId)
    {
        $reserva = Reserva::where('cpf', $user)
                        ->where('baia_id', '=', $baiaId)
                        ->where('data', '=', $date)
                        ->where('hora', '<=', $time)
                        ->where('status_id', '=', config('status_reserva.LIBERADA'))->first();
        return $reserva;
    }
}
