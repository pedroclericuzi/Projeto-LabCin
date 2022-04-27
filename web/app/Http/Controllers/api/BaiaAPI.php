<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Baia;
use App\Mail\MailAlert;
use App\Http\Controllers\api\UserAPI;
use App\Http\Controllers\api\LogAPI;
use App\Http\Controllers\api\EquipamentoAPI;
use Illuminate\Support\Facades\Mail;

class BaiaAPI extends Controller
{
    public $idConfig_status_baia_emUso;
    public $idConfig_status_baia_bloqueada;
    public $idConfig_status_baia_emChecagem;
    public $idConfig_baia_liberada;
    public $idConfig_user_liberado;
    public $idConfig_user_bloqueado;
    public $configHoraAulaInicio;
    public $configHoraAulaFim;

    public function __construct() {
        $this->idConfig_status_baia_emUso = config('status_baia.EM_USO');
        $this->idConfig_status_baia_bloqueada = config('status_baia.BLOQUEADA');
        $this->idConfig_status_baia_emChecagem = config('status_baia.EM_CHECAGEM');
        $this->idConfig_baia_liberada = config('status_baia.LIBERADA');
        $this->idConfig_user_liberado = config('status_user.LIBERADO');
        $this->idConfig_user_bloqueado = config('status_user.BLOQUEADO');
        $this->configHoraAulaInicio = config('hora_aula.INICIO');
        $this->configHoraAulaFim = config('hora_aula.FIM');
        date_default_timezone_set('America/Recife');
    }

    function getBaiaByNum($baia){
        return Baia::where("num_baia", $baia)->first();
    }

    public function getOne($baia) {
        $get_baia = $this->getBaiaByNum($baia);
        return response()->json([
            'code' => 200,
            'num_baia' => $baia,
            'data' => $get_baia
        ]);
    }

    public function getAll() {
        $baia = Baia::all();
        return response()->json([
            'code' => 200,
            'data' => $baia
        ]);
    }

    public function abrir($baia, $rfid) {
        $start_time = microtime(true);
        $start_date = date('Y-m-d H:i:s');

        $get_baia = $this->getBaiaByNum($baia);
        $user = UserAPI::getByRFID($rfid); 
        if($get_baia && $user) {
            if(!$this->horarioDeAula()) {
                $reserva = $this->checkReserva($rfid, $get_baia->id);
                if ($reserva == null) {
                    $this->feedLog($user->matricula, null, "Tentativa de usar a baia fora do horário de aula sem reserva.", $get_baia->id, config('log.USO'));
                    
                    $end_time = microtime(true);
                    $execution_time = ($end_time - $start_time) * 1000;
                    return response()->json([
                        'code' => -1,
                        'num_baia' => $baia,
                        'message' => 'Não existe uma reserva confirmada para esse usuário no dia de hoje. Verifique sua reserva no portal.',
                        'execution_time' => $execution_time, 
                        'start_date' => $start_date
                    ]);
                }
            }
            //echo($get_baia->status_baia_id." - ".$this->idConfig_baia_liberada."; ".$user->status_id." - ".$this->idConfig_user_liberado);
            if($get_baia->status_baia_id == $this->idConfig_baia_liberada && $user->status_id == $this->idConfig_user_liberado) {
                $this->abrirBaia($baia, $get_baia->id, $user);
                
                $end_time = microtime(true);
                $execution_time = ($end_time - $start_time) * 1000;
                return response()->json([
                    'code' => 200,
                    'num_baia' => $baia,
                    'baia' => $get_baia,
                    'aluno' => $user,
                    'status_baia' => $this->idConfig_status_baia_emUso,
                    'status_user' => $this->idConfig_user_bloqueado,
                    'execution_time' => $execution_time, 
                    'start_date' => $start_date
                ]);
            } else if ($get_baia->status_baia_id == $this->idConfig_status_baia_bloqueada) {
                if ($user->permissao_id != config('permissao.ALUNO_ID') || $user->rfid == $get_baia->user_usando) {
                    $this->changeStatusBaia($this->idConfig_status_baia_emChecagem, $baia, $rfid);
                    $this->feedLog($user->matricula, null, "[BAIA BLOQUEADA] Abriu a baia para verificação.", $get_baia->id, config('log.USO'));
                    
                    $end_time = microtime(true);
                    $execution_time = ($end_time - $start_time) * 1000;
                    return response()->json([
                        'code' => 200,
                        'num_baia' => $baia,
                        'baia' => $get_baia,
                        'user' => $user,
                        'status_baia' => $this->idConfig_status_baia_emChecagem,
                        'status_user' => $this->idConfig_user_bloqueado,
                        'execution_time' => $execution_time, 
                        'start_date' => $start_date
                    ]);
                } else {
                    $end_time = microtime(true);
                    $execution_time = ($end_time - $start_time) * 1000;
                    return response()->json([
                        'code' => 403,
                        'num_baia' => $baia,
                        'message' => "Esta baia está bloqueada.",
                        'execution_time' => $execution_time, 
                        'start_date' => $start_date
                    ]);
                }
            } else {
                $message = "";
                if($get_baia->status_baia_id != $this->idConfig_baia_liberada && $user->status_id == $this->idConfig_user_liberado){
                    $message = "Esta baia não está disponível.";
                } else if ($get_baia->status_baia_id == $this->idConfig_baia_liberada && $user->status_id != $this->idConfig_user_liberado){
                    $message = "O usuário possui alguma pendencia ou já está usando alguma baia.";
                } else {
                    $message = "A baia não está disponível e o usuário está bloqueado.";
                }
                $this->feedLog($user->matricula, null, $message, $get_baia->id, config('log.USO'));
                
                $end_time = microtime(true);
                $execution_time = ($end_time - $start_time) * 1000;
                return response()->json([
                    'code' => 402,
                    'num_baia' => $baia,
                    'message' => $message,
                    'status_baia' => $get_baia->status_baia_id,
                    'status_user' => $user->status_id,
                    'execution_time' => $execution_time, 
                    'start_date' => $start_date
                ]);
            }

        } else {
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time) * 1000;
            return response()->json([
                'code' => 204,
                'num_baia' => $baia,
                'message' => 'A baia ou o usuário não foram encontrados no sistema',
                'execution_time' => $execution_time, 
                'start_date' => $start_date
            ]);
        }
    }

    function checkReserva($rfid, $baiaId){
        $day = date("Y-m-d");
        $hour = date("H:i");
        $user = UserAPI::getByRFID($rfid);
        $reserva = ReservaAPI::check($user->matricula, $day, $hour, $baiaId);
        return $reserva;
    }

    function abrirBaia($numberBaia, $idBaia, $user){
        $this->changeStatusBaia($this->idConfig_status_baia_emUso, $numberBaia, $user->rfid);
        UserAPI::changeStatusUser($this->idConfig_user_bloqueado, $user->rfid);
        $this->feedLog($user->matricula, null, "Abriu a baia", $idBaia, config('log.USO'));
    }

    public function fechar($baia, $equipamentos = null) {
        $start_time = microtime(true);
        $start_date = date('Y-m-d H:i:s');
        
        $get_baia = Baia::where("num_baia", $baia)->first();
        $baiaId = $get_baia->id;
        $equipamentosInseridosCorretamente = $this->equipamentosCheck($baiaId, $equipamentos);
        $rfid = $get_baia->user_usando;
        $user = UserAPI::getByRFID($rfid);
        if($get_baia->user_usando == null)  {
            if  ($equipamentosInseridosCorretamente) {

                $end_time = microtime(true);
                $execution_time = ($end_time - $start_time) * 1000;
                return response()->json([
                    'code' => -1,
                    'num_baia' => $baia,
                    'message' => "Esta baia já está fechada.",
                    'execution_time' => $execution_time, 
                    'start_date' => $start_date
                ]);
            } else {
                if($user){
                    $this->feedLog($user->matricula, null, "Um ou mais equipamentos não foram inseridos corretamente, esta baia agora está bloqueada.", $get_baia->id, config('log.USO'));
                    $tipoNotificacao = 403;
                    $this->emailFechamentoIncorreto($baia, "", UserAPI::allAssistents(), "", "", $tipoNotificacao);

                    $end_time = microtime(true);
                    $execution_time = ($end_time - $start_time) * 1000;
                    return response()->json([
                        'code' => 403,
                        'num_baia' => $baia,
                        'message' => "Um ou mais equipamentos não foram inseridos corretamente, esta baia agora está bloqueada.",
                        'execution_time' => $execution_time, 
                        'start_date' => $start_date
                    ]);
                }
            }
        }
        $codigo = 200;
        if($get_baia && $user){
            if($get_baia->status_baia_id == $this->idConfig_status_baia_emUso && $equipamentosInseridosCorretamente) {
                $this->liberarBaia($baia, $this->idConfig_baia_liberada, $this->idConfig_user_liberado, $rfid);
                $this->feedLog($user->matricula, null, "Fechou a baia", $get_baia->id, config('log.USO'));
                $this->emailFechamento($user->email, $user->nome);

                $end_time = microtime(true);
                $execution_time = ($end_time - $start_time) * 1000;
                return response()->json([
                    'code' => 200,
                    'num_baia' => $baia,
                    'baia' => $get_baia,
                    'aluno' => $user,
                    'status_baia' => $this->idConfig_baia_liberada,
                    'status_user' => $this->idConfig_user_liberado,
                    'execution_time' => $execution_time, 
                    'start_date' => $start_date
                ]);
            } else if($get_baia->status_baia_id == $this->idConfig_status_baia_emUso && !$equipamentosInseridosCorretamente){
                $this->feedLog($user->matricula, null, "Um ou mais equipamentos não foram inseridos corretamente, esta baia agora está bloqueada.", $get_baia->id, config('log.USO'));
                $tipoNotificacao = 1;
                $this->emailFechamentoIncorreto($baia, $user->email, UserAPI::allAssistents(), $user->nome, $user->matricula, $tipoNotificacao);

                $end_time = microtime(true);
                $execution_time = ($end_time - $start_time) * 1000;
                return response()->json([
                    'code' => 403,
                    'num_baia' => $baia,
                    'message' => "Um ou mais equipamentos não foram inseridos corretamente, esta baia agora está bloqueada.",
                    'execution_time' => $execution_time, 
                    'start_date' => $start_date
                ]);
            } else if ($get_baia->status_baia_id == $this->idConfig_status_baia_emChecagem) {
                $message = "";
                if($equipamentosInseridosCorretamente){
                    $tipoNotificacao = 2;
                    $codigo = 200;
                    $message = "Foi realizada a checagem e desta vez o(s) equipamento(s) foi inserido corretamente.";
                    $this->emailFechamentoIncorreto($baia, $user->email, UserAPI::allAssistents(), $user->nome, $user->matricula, $tipoNotificacao);
                    $this->liberarBaia($baia, $this->idConfig_baia_liberada, $this->idConfig_user_liberado, $rfid);
                    $this->feedLog($user->matricula, null, $message, $get_baia->id, config('log.USO'));
                } else {
                    $tipoNotificacao = 3;
                    $codigo = 403;
                    $message = "Foi realizada a checagem, mas os equipamentos continuam fora da baia. Em caso de mau funcionamento, alterar o status no portal.";
                    $this->emailFechamentoIncorreto($baia, $user->email, UserAPI::allAssistents(), $user->nome, $user->matricula, $tipoNotificacao);
                    $this->feedLog($user->matricula, null, $message, $get_baia->id, config('log.USO'));
                }
                $end_time = microtime(true);
                $execution_time = ($end_time - $start_time) * 1000;
                return response()->json([
                    'code' => $codigo,
                    'num_baia' => $baia,
                    'message' => $message,
                    'execution_time' => $execution_time, 
                    'start_date' => $start_date
                ]);

            } else {
                $end_time = microtime(true);
                $execution_time = ($end_time - $start_time) * 1000;
                return response()->json([
                    'code' => 200,
                    'num_baia' => $baia,
                    'message' => "A baia não está em uso.",
                    'execution_time' => $execution_time, 
                    'start_date' => $start_date
                ]);
            }
        } else {
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time) * 1000;
            return response()->json([
                'num_baia' => $baia,
                'code' => 204,
                'message' => 'A baia ou o usuário não foram encontrados no sistema',
                'execution_time' => $execution_time, 
                'start_date' => $start_date
            ]);
        }
    }

    function feedLog($user, $monitor, $message, $id, $kindOfLog){
        LogAPI::baiaLog($user, $monitor, $message, $id, $kindOfLog);
    }

    function horarioDeAula() {
        $hora = date("H:i");
        if(!$this->isWeekend()) {
            $inicio = $this->convertStringToTime($this->configHoraAulaInicio);
            $fim = $this->convertStringToTime($this->configHoraAulaFim);
            if($hora >= $inicio && $hora <= $fim){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function convertStringToTime($time){
        return date( "H:i:s", strtotime($time));
    }

    function isWeekend() {
        return in_array(date("l"), ["Saturday", "Sunday"]);
    }

    function changeStatusBaia($status, $baia, $rfid=null){
        if($status == config('status_baia.EM_CHECAGEM')){
            Baia::where("num_baia", $baia)->update(array(
                'status_baia_id'=>$status
            ));
        } else {
            Baia::where("num_baia", $baia)->update(array(
                'status_baia_id'=>$status,
                'user_usando'=>$rfid
            ));
        }
    }

    function bloquearUsuarioBaia($baia){
        Baia::where("id", $baia)->update(array(
            'status_baia_id'=>config('status_baia.BLOQUEADA')
        ));
        $get_baia = Baia::where("id", $baia)->first();
        if ($get_baia->user_usando && $get_baia->user_usando != "") {
            UserAPI::changeStatusUser(config('status_user.BLOQUEADO'), $get_baia->user_usando);
        }
    }

    function liberarBaia($baia, $idConfig_baia_liberada, $idConfig_user_liberado, $rfid) {
        $this->changeStatusBaia($idConfig_baia_liberada, $baia);
        UserAPI::changeStatusUser($idConfig_user_liberado, $rfid);
    }

    function equipamentosCheck($baia, $equipamentos = null) {
        $equipamentosNaBaia = [];
        if($equipamentos != null) {
            $equipamentosNaBaia = explode(";", $equipamentos);
        } 
        $equipamentosCadastrados = EquipamentoAPI::equipamentoPorBaia($baia);
        $equipamentosInseridosCorretamente = true;
        foreach ($equipamentosCadastrados as $value) {
            $rfid = $value->uuid_tag;
            if (!in_array($rfid, $equipamentosNaBaia) || $rfid == "NONE") {
                $equipamentosInseridosCorretamente = false;
                EquipamentoAPI::equipamentoAusente($value->id);
            } else {
                //marcar equipamento como na baia
                EquipamentoAPI::equipamentoPresente($value->id);
            }
        }
        if(!$equipamentosInseridosCorretamente){
            $this->bloquearUsuarioBaia($baia);
        }
        return $equipamentosInseridosCorretamente;
    }
    //https://myaccount.google.com/u/4/security?pmr=1
    function emailFechamento($emailAluno, $nomeAluno){
        $mensagemAssunto = "[LabCin] Registro de uso da Baia";
        Mail::send('mail.avisoFechamento', ["aluno" => $nomeAluno], function($email) use ($emailAluno, $mensagemAssunto) {
            $email->from("no-reply-labcin@cin.ufpe.br", "Portal LabCin");
            $email->to($emailAluno);
            $email->subject($mensagemAssunto);
        });
    }

    function emailFechamentoIncorreto($baia, $emailAluno, $emailsMonitor, $nomeAluno, $matAluno, $tipoNotificacao){
        $mensagemAssunto = "[LabCin - URGENTE] Ausência de equipamento(s)";
        if ($tipoNotificacao == 403) {
            Mail::send('mail.avisoFechamentoIncorreto', ["aluno" => $nomeAluno, "matAluno" => $matAluno, "baia" => $baia, "permissao"=>config('permissao.MONITOR_ID'), "tipoNotificacao" => $tipoNotificacao], function($email) use ($emailsMonitor, $mensagemAssunto) {
                $email->from("no-reply-labcin@cin.ufpe.br", "Portal LabCin");
                $email->to($emailsMonitor);
                $email->subject($mensagemAssunto);
            });
        } else {
            if($tipoNotificacao == 2) {
                $mensagemAssunto = "[LabCin] Baia liberada";
            }
            Mail::send('mail.avisoFechamentoIncorreto', ["aluno" => $nomeAluno, "permissao"=>config('permissao.ALUNO_ID'), "tipoNotificacao" => $tipoNotificacao], function($email) use ($emailAluno, $mensagemAssunto) {
                $email->from("no-reply-labcin@cin.ufpe.br", "Portal LabCin");
                $email->to($emailAluno);
                $email->subject($mensagemAssunto);
            });
            Mail::send('mail.avisoFechamentoIncorreto', ["aluno" => $nomeAluno, "matAluno" => $matAluno, "baia" => $baia, "permissao"=>config('permissao.MONITOR_ID'), "tipoNotificacao" => $tipoNotificacao], function($email) use ($emailsMonitor, $mensagemAssunto) {
                $email->from("no-reply-labcin@cin.ufpe.br", "Portal LabCin");
                $email->to($emailsMonitor);
                $email->subject($mensagemAssunto);
            });
        }
    }
}
