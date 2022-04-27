<?php

namespace App\Http\Controllers\portal;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    private $none = "display: none;";

    public function index()
    {
        //dd(config('constants.MONITOR_ID'));
        //$this->addManualmente();

        if(Auth::check()){
            return redirect()->route("baias");
        }

        $matricula = "10314910409";
        return view("portal.user.login.index", compact('matricula'));
    }

    public function logout(Request $req){
        Auth::logout();
        return redirect()->route("login");
    }

    public function login(Request $req){
        $credentials = [
            'matricula' => $req->matricula,
            'password' => $req->password
        ];
        //Auth::attempt($credentials);
        if(Auth::attempt($credentials)){
            return redirect()->route("baias");
        } else {
            return redirect()->route("login");
        }
    }

    public function cadastrar(Request $req){

    }

    public function ir_cadastro(){
        $v1 = $this->none;
        $v2 = "";
    }
    
    public function voltar_login(){

    }

    public function ir_esqueceu_senha(){

    }

    function addManualmente(){
        $user = new User();
        $user->password = Hash::make('123456');
        $user->email = 'pvbc@cin.ufpe.br';
        $user->permissao_id = 3;
        $user->disciplina_monitor = "Sistemas Digitais";
        $user->matricula = '10314910409';
        $user->nome = 'Pedro Clericuzi';
        $user->rfid = 'X1X2X3';
        $user->save();

        $user = new User();
        $user->password = Hash::make('123456');
        $user->email = 'lucas@cin.ufpe.br';
        $user->permissao_id = 1;
        $user->disciplina_monitor = "";
        $user->matricula = '10314910410';
        $user->nome = 'Lucas Amorim';
        $user->rfid = 'X4X2X3';
        $user->save();

        $user = new User();
        $user->password = Hash::make('123456');
        $user->email = 'edna@cin.ufpe.br';
        $user->permissao_id = 2;
        $user->disciplina_monitor = "";
        $user->matricula = '10314910411';
        $user->nome = 'Edna Barros';
        $user->rfid = 'X4X5X3';
        $user->save();
    }
}
