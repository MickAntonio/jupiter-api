<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Boleias;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BoleiasController extends Controller
{
    /**
     * Verify if user us authorization with JWT-AUTH
     *
     */

    public function __construct() {
        $this->middleware('jwt-auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $boleias = Boleias::where('id', '>', 0);

            if(request('id')){
                $boleias->where('id', request('id'));
            }

            if(request('solicitante_id')){
                $boleias->where('solicitante_id', request('solicitante_id'));
            }

            if(request('motorista_id')){
                $boleias->where('motorista_id', request('motorista_id'));
            }

            if(request('tipo')){
                $boleias->where('tipo', strtoupper(request('tipo')));
            }

            if(request('estado')){
                $boleias->where('estado', strtoupper(request('estado')));
            }

            if(request('fl_completo') && strtoupper(request('fl_completo'))=='S'){
                $boleias = $boleias->with(['solicitante', 'motorista']);
            }

            
            return response()->json([
                'data'=>[
                    'boleias' => $boleias->orderBy('id', 'desc')->get()
                ]
                ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_trazer_boleias', 'errors'=>$e], 500);
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
        try {

            if($request->tipo=='BOLEIA'){
                $validator = Validator::make($request->all(), [
                    'solicitante_id'=>'required',
                    'motorista_id'=>'required',
                    'tipo'=>'required'
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'solicitante_id'=>'required',
                    'tipo'=>'required'
                ]);
            }

            if($validator->fails()){
                return response()->json(['message' => $validator->errors()->all()], 400);
            }else{
                
                $boleia = new Boleias;
                $boleia->solicitante_id = $request->solicitante_id;
                $boleia->motorista_id   = $request->motorista_id;
                $boleia->tipo           = $request->tipo;
                $boleia->motivo         = $request->motivo;
                $boleia->horario        = $request->horario;
                $boleia->estado         = $request->estado;
                $boleia->save();

                return response()->json(['message' => 'boleia_adicionado_com_succeso', 
                    'data'=>['boleia' => $boleia->where('id', $boleia->id)->get() ]
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_boleia', 'errors'=>$e], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Boleias  $boleia id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $boleia = Boleias::find($id);
        
            if($boleia!=null){
                return response()->json(['data'=>[
                    'boleia' =>  $boleia->where('id', $id)->get()
                    ]
                ], 200);
            }else{
                return response()->json(['message' => 'boleia_nao_encontrado'], 200);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_procurar_boleia'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Boleias  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if($request->tipo=='BOLEIA'){
                $validator = Validator::make($request->all(), [
                    'solicitante_id'=>'required',
                    'motorista_id'=>'required',
                    'tipo'=>'required'
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'solicitante_id'=>'required',
                    'tipo'=>'required'
                ]);
            }

            if($validator->fails()){
                return response()->json(['message' => $validator->errors()->all()], 400);
            }else{

                $boleia = Boleias::find($id);
        
                if($boleia!=null){
                
                    $boleia->solicitante_id = $request->solicitante_id;
                    $boleia->motorista_id   = $request->motorista_id;
                    $boleia->tipo           = $request->tipo;
                    $boleia->motivo         = $request->motivo;
                    $boleia->horario        = $request->horario;
                    $boleia->estado         = $request->estado;
                    $boleia->save();

                    return response()->json(['message' => 'boleia_actualizada_com_succeso', 
                    'data'=>[
                        'boleia' =>  $boleia->where('id', $boleia->id)->get() 
                        ]
                    ], 200);

                }else{
                    return response()->json(['message' => 'boleia_nao_encontrado'], 200);
                }
            }
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_actualizar_boleia', 'errors'=>$e], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Boleias  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $boleia = Boleias::find($id);
        
            if($boleia!=null){
                $boleia->delete();
                return response()->json(['message' => 'boleia_excluido'], 200);
            }else{
                return response()->json(['message' => 'boleia_nao_encontrada'], 200);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_excluir_boleia'], 500);
        }
    }
}
