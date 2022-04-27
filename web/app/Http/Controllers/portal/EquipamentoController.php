<?php

namespace App\Http\Controllers\portal;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipamento;
use App\Models\Baia;
use App\Models\TipoEquipamento;
use App\Models\StatusEquipamento;
use App\Models\StatusBaia;

class EquipamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check()){
            return view("portal.equipamento.index", [
                'equipamentos' => Equipamento::all()
            ]); 
        }
        return redirect()->route("login");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("portal.equipamento.form", [
            'baias' => Baia::all(),
            'tipo_equipamento' => TipoEquipamento::all(),
            'status_equipamento' => StatusEquipamento::all(),
            'editing' => false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $equipamento = new Equipamento();
            $equipamento->uuid_tag = $request->tag_rfid;
            $equipamento->tombamento = $request->tombamento;
            $equipamento->marca = $request->marca;
            $equipamento->modelo = $request->modelo;
            $equipamento->estado_conserv = $request->conserv;
            $equipamento->baia_id = $request->baia;
            $equipamento->tipo_id = $request->equipamento;
            $equipamento->status_id = $request->status_id;
            $equipamento->save();
            return redirect()->route("equipamentos");
        } catch (Exception $e) {
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }

    }

    static function tipoEquipamento($id) {
        $tipo = TipoEquipamento::where("id", $id)->first();
        $tipo = "".$tipo['descricao'];
        return $tipo;
    }

    static function statusEquipamento($id) {
        $status = StatusEquipamento::where("id", $id)->first();
        $status = "".$status['descricao'];
        return $status;
    }

    static function baiaEquipamento($id) {
        $baia = Baia::where("id", $id)->first();
        $baia = "Baia ".$baia['num_baia'];
        return $baia;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view("portal.equipamento.form", [
            'equipamento' => Equipamento::where("id", $id)->first(),
            'baias' => Baia::all(),
            'tipo_equipamento' => TipoEquipamento::all(),
            'status_equipamento' => StatusEquipamento::all(),
            'editing' => true
        ]);
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
        Equipamento::where('id',$id)->update(array(
            'uuid_tag'=>$request->tag_rfid,
            'tombamento'=>$request->tombamento,
            'marca'=>$request->marca,
            'modelo'=>$request->modelo,
            'estado_conserv'=>$request->conserv,
            'baia_id'=>$request->baia,
            'tipo_id'=>$request->equipamento,
            'status_id'=>$request->status_id
        ));
        return redirect()->route("equipamentos");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipamento = Equipamento::find($id);
        $equipamento->delete();
        return redirect()->route("equipamentos");
    }
}
