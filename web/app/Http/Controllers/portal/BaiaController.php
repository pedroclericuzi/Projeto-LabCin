<?php

namespace App\Http\Controllers\portal;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Baia;
use App\Models\StatusBaia;

class BaiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(Auth::check()){
            $desc = StatusBaia::all();
            $arrayObj = [];
            foreach ($desc as $status){
                $obj = (object)[$status->id => $status->descricao];
                array_push($arrayObj, $obj);
            }
            return view("portal.baia.index", [
                'baias' => Baia::all()
            ]);
        } 
        return redirect()->route("login");
    }

    static function getDescricao($id){
        $desc = Baia::where('status_baia_id', $id)->with("status")->first();
        $descricao = "".$desc['status']['descricao'];
        return $descricao;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("portal.baia.form", [
            'status_baia' => StatusBaia::all(),
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
        //dd($request->status_id);
        $baia = new Baia();
        $baia->num_baia = $request->num_baia;
        $baia->status_baia_id = $request->status_id;
        $baia->save();
        return redirect()->route("baias");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //return view("portal.baia.edit", ['slug'=>$slug]);
        return view("portal.baia.form", [
            'baia' => Baia::where('id', $slug)->first(),
            'status_baia' => StatusBaia::all(),
            'editing' => true
        ]);
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
        Baia::where('id',$id)->update(array(
            'num_baia'=>$request->num_baia,
            'status_baia_id'=>$request->status_id
        ));
        return redirect()->route("baias");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $baia = Baia::find($id);
        $baia->delete();
        return redirect()->route("baias");
    }
}
