<?php

namespace App\Http\Controllers\portal;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChangeLog;
use App\Models\TipoEvento;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check()){
            $equipamentos = ChangeLog::where('equipamento_id', '<>', '', 'and')->get();
            $baias = ChangeLog::where('baia_id', '<>', '', 'and')->get();
            $reservas = ChangeLog::where('reserva_id', '<>', '', 'and')->get();
            //dd($reservas);
            return view("portal.log.index", [
                "equipamentos" => $equipamentos,
                "baias" => $baias,
                "reservas" => $reservas
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view("portal.log.show", ['slug'=>$slug]);
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
        //
    }

    static function statusEvento($id) {
        $status = TipoEvento::where("id", $id)->first();
        $status = "".$status['descricao'];
        return $status;
    }
}
