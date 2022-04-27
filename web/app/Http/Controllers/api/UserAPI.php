<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserAPI extends Controller
{
    public function getOne($rfid) {
        $user = UserAPI::getByRFID($rfid);
        return response()->json([
            'code' => 200,
            'data' => $user
        ]);
    }

    public function getAll() {
        $user = User::all();
        return response()->json([
            'code' => 200,
            'data' => $user
        ]);
    }

    public static function allAssistents(){
        $assistentsProfessor = User::where("permissao_id", config('permissao.MONITOR_ID'))->get();
        $emails = array();
        foreach ($assistentsProfessor as $assistent) {
            array_push($emails, $assistent->email);
        }
        return $emails;
    }

    public static function getByRFID($rfid) {
        $user = User::where("rfid", $rfid)->first();
        return $user;
    }

    public static function changeStatusUser($status, $rfid){
        User::where("rfid", $rfid)->update(array(
            'status_id'=>$status
        ));
    }
}
