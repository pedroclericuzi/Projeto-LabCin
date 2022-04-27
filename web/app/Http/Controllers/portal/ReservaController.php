<?php

namespace App\Http\Controllers\portal;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\StatusReserva;
use App\Models\Equipamento;
use App\Models\Baia;
use App\Models\User;
use App\Models\ChangeLog;
use Illuminate\Support\Facades\Mail;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservas = Reserva::all();
        if(!PermisionsController::notIsAluno()){
            $user = Auth::user();
            $matricula = $user->matricula;
            $reservas = Reserva::where("cpf", $matricula)->get();   
        }
        return view("portal.reserva.index", [
            'reservas' => $reservas
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $naoAluno = (Auth::user()->id == config('permissao.ALUNO_ID')) ? false : true;
        return view("portal.reserva.form", [
            'editing' => false,
            'naoAluno' => $naoAluno
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $reserva = Reserva::where('id', $slug)->first();
        $naoAluno = (Auth::user()->permissao_id == config('permissao.ALUNO_ID')) ? false : true;
        $pendente = ($reserva->status_id == config('status_reserva.PENDENTE')) ? true : false;
        $cancelado = ($reserva->status_id == config('status_reserva.CANCELADA')) ? true : false;
        $solicitante = $this->checarSolicitante($reserva->cpf);

        return view("portal.reserva.form", [
            'reserva' => $reserva,
            'pendente' => $pendente,
            'cancelado' => $cancelado,
            'baias' => Baia::all(),
            'editing' => true,
            'solicitante' => $solicitante,
            'naoAluno' => $naoAluno
        ]);

    }

    private function checarSolicitante($solicitante) {
        $usuarioAtual = Auth::user()->matricula;
        if($usuarioAtual === $solicitante){
            return true;
        }
        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $originalDate = $request->data;
        $dataFormatoCorreto = date("Y-m-d", strtotime($originalDate));
        try {
            $user = Auth::user();
            $matricula = $user->matricula;
            $reserva = new Reserva();
            $reserva->cpf = $matricula;
            $reserva->justificativa = $request->justificativa;
            $reserva->observacoes = $request->observacoes;
            $reserva->data = $dataFormatoCorreto;
            $reserva->hora = $request->hora;
            $reserva->baia_id = $request->baia;
            $reserva->status_id = config('status_reserva.PENDENTE');
            $reserva->save();
            return redirect()->route("reservas");
        } catch (Exception $e) {
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $originalDate = $request->data;
        $dataFormatoCorreto = date("Y-m-d", strtotime($originalDate));
        $user = Auth::user();
        $matricula = $user->matricula;
        Reserva::where('id',$id)->update(array(
            'cpf'=> $matricula,
            'justificativa'=>$request->justificativa,
            'observacoes'=>$request->observacoes,
            'data'=>$dataFormatoCorreto,
            'hora'=>$request->hora,
            'baia_id'=>$request->baia,
            'status_id'=>config('status_reserva.PENDENTE')
        ));
        return redirect()->route("reservas");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reserva = Reserva::find($id);
        $reserva->delete();
        return redirect()->route("reservas");
    }

    public function cancelar($id)
    {
        $status = 'status_reserva.CANCELADA';
        $this->atualizarStatusReserva($id, $status, "Solicitação cancelada");
        return redirect()->route("reservas");
    }

    static function EncontrarAluno($cpf){
        $user = User::where("matricula", $cpf)->first();
        $user = MonitorController::formattedName("".$user['nome']);
        return $user;
    }

    static function Login($cpf){
        $user = User::where("matricula", $cpf)->first();
        $login = explode("@", "".$user['email']);
        return $login[0];
    }

    static function EncontrarBaia($id){
        $baia = Baia::where("id", $id)->first();
        return "Baia ".$baia['num_baia'];
    }

    static function EncontrarStatus($id){
        $status = StatusReserva::where("id", $id)->first();
        return "".$status['descricao'];
    }

    static function verificarBaias(Request $request) {
        $date = $request->date;
        $reserva = Reserva::where("data", $date)->where("status_id", "<>", config('status_reserva.CANCELADA'))->get();
        $allID = array();
        foreach($reserva as $dadosReserva){
            array_push($allID, $dadosReserva["baia_id"]);
        }
        $models = Baia::whereNotIn('id', $allID)->get();
        return $models;
    }

    static function formatarData($data)
    {
        $dataFormatoCorreto = date("d-m-Y", strtotime($data));
        return $dataFormatoCorreto;
    }

    static function formatarHora($hora)
    {
        $horaFormatoCorreto = date("H:i", strtotime($hora));
        return $horaFormatoCorreto;
    }

    function solicitanteReserva($id) {
        $reserva = Reserva::where('id', $id)->first();
        $user = User::where("matricula", $reserva->cpf)->first();
        return $user['email'];
    }

    function rejeitar(Request $request)
    {
        $status = 'status_reserva.CANCELADA';
        $this->atualizarStatusReserva($request->id, $status, "Solicitação negada", $request->observacoes);
        $this->emailResposta(false, $request->id);
    }

    function aceitar(Request $request)
    {
        $status = 'status_reserva.LIBERADA';
        $this->atualizarStatusReserva($request->id, $status, "Solicitação aceita", $request->observacoes);
        $this->emailResposta(true, $request->id);
    }

    function emailResposta($aceita, $idReserva){
        $enviarPara = $this->solicitanteReserva($idReserva);
        if($aceita) {
            $mensagemAssunto = "[LabCin] Sua reserva foi aceita";
            Mail::send('mail.avisoReservaAceita', [], function($email) use ($enviarPara, $mensagemAssunto) {
                $email->from("no-reply-labcin@cin.ufpe.br", "Portal LabCin");
                $email->to($enviarPara);
                $email->subject($mensagemAssunto);
            });
        } else {
            $mensagemAssunto = "[LabCin] Sua reserva precisou ser recusada";
            Mail::send('mail.avisoReservaRecusada', [], function($email) use ($enviarPara, $mensagemAssunto) {
                $email->from("no-reply-labcin@cin.ufpe.br", "Portal LabCin");
                $email->to($enviarPara);
                $email->subject($mensagemAssunto);
            });
        }
    }

    function atualizarStatusReserva($id, $status, $mensagem, $obs = null){
        Reserva::where('id',$id)->update(array(
            'observacoes' => $obs,
            'status_id'=>config($status)
        ));
        $this->atualizarLog($id,$mensagem);
        return response()->json(['url'=>url('/reservas')]);
    }

    function atualizarLog($id,$mensagem)
    {
        try {
            $user = Auth::user();
            $matricula = $user->matricula;
            $log = new ChangeLog();
            $log->mat_monitor = $matricula;
            $log->mensagem = "Ação efetuada por um responsável - ".$mensagem;
            $log->reserva_id = $id;
            $log->evento_id = config('log.ATUALIZACAO');
            $log->save();
        } catch (Exception $e) {
            //echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }
}
