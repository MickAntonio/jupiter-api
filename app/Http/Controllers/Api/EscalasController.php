<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Escalas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EscalasController extends Controller
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
            
            return response()->json([
                'status'    => true,
                'escalas' => Escalas::all()
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_trazer_escalas'], 500);
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

            $validator = Validator::make($request->all(), [
                'mes'=>'required',
                'ano'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{
                
                $escala = new Escalas;
                $escala->mes = $request->mes;
                $escala->ano = $request->ano;
                $escala->save();

                return response()->json(['status' => true, 'message' => 'escala_adicionado_com_succeso', 'escala' => $escala], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_escala'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Escalas  $escala id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $escala = Escalas::find($id);
        
            if($escala!=null){
                return response()->json(['status' => true, 'escala' => $escala], 200);
            }else{
                return response()->json(['message' => 'escala_nao_encontrado'], 200);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_procurar_escala'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Escalas  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'mes'=>'required',
                'ano'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{

                $escala = Escalas::find($id);
        
                if($escala!=null){
                
                    $escala->mes = $request->mes;
                    $escala->ano = $request->ano;
                    $escala->save();

                    return response()->json(['status' => true, 'message' => 'escala_actualizada_com_succeso', 'escala' => $escala], 200);

                }else{
                    return response()->json(['message' => 'escala_nao_encontrado'], 200);
                }
            }
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_escala'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Escalas  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $escala = Escalas::find($id);
        
            if($escala!=null){
                $escala->delete();
                return response()->json(['status' => true, 'escala' => 'escala_excluido'], 200);
            }else{
                return response()->json(['message' => 'escala_nao_encontrada'], 200);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_excluir_escala'], 500);
        }
    }
}
