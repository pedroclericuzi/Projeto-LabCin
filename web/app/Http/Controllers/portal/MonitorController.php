<?php

namespace App\Http\Controllers\portal;

use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class MonitorController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $const = "".config('constants.MONITOR_ID');
        return view("portal.monitor.index", [
            'monitores' => User::where("permissao_id", $const)->get()
        ]);
    }

    public static function formattedName($nome){
        $partes = explode(" ", $nome);
        $lastIndex = count($partes) - 1;
        return $partes[0]." ".$partes[$lastIndex];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $matricula = "";
        return view("portal.monitor.form", [
            'editing' => false,
            'matricula' => $matricula
        ]);
    }

    public function searchMonitor(Request $request)
    {
        $monitor = User::where('matricula',$request->matricula)->first();
        if($monitor != null){
            Alert::success('Aluno encontrado', 'Encontramos e salvamos o monitor encontrado');
            $matricula = "";
            User::where('matricula',$request->matricula)->update(array(
                'permissao_id'=>config('permissao.MONITOR_ID'),
                'disciplina_monitor'=>$request->disciplina
            ));
            return redirect()->route("monitores");
        } else {
            Alert::error('Aluno não identificado', 'Certifique-se de que o CPF foi digitado corretamente e tente de novo.'); //success('Success Title', 'Success Message');
            $matricula = "";
            return view("portal.monitor.form", [
                'editing' => false,
                'matricula' => ""
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::where('id',$id)->update(array(
            'permissao_id'=>config('permissao.ALUNO_ID'),
            'disciplina_monitor'=>""
        ));
        return redirect()->route("monitores");
    }
}
