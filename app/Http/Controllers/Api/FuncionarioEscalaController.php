<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\FuncionarioEscala;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FuncionarioEscalaController extends Controller
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
                'funcionarios_escalas' => FuncionarioEscala::all()
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_trazer_funcionarios_escalas'], 500);
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
                'escala_id'=>'required',
                'funcionario_id'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{
                
                $funcionario_escala = new FuncionarioEscala;
                $funcionario_escala->funcionario_id = $request->funcionario_id;
                $funcionario_escala->escala_id = $request->escala_id;
                $funcionario_escala->dia = $request->dia;
                $funcionario_escala->save();

                return response()->json(['status' => true, 'message' => 'funcionario_escala_adicionado_com_succeso', 'funcionario_escala' => $funcionario_escala], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_funcionario_escala'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FuncionarioEscala  $funcionario_escala id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $funcionario_escala = FuncionarioEscala::find($id);
        
            if($funcionario_escala!=null){
                return response()->json(['status' => true, 'funcionario_escala' => $funcionario_escala], 200);
            }else{
                return response()->json(['message' => 'funcionario_escala_nao_encontrado'], 200);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_procurar_funcionario_escala'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FuncionarioEscala  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'escala_id'=>'required',
                'funcionario_id'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{

                $funcionario_escala = FuncionarioEscala::find($id);
        
                if($funcionario_escala!=null){
                
                    $funcionario_escala->funcionario_id = $request->funcionario_id;
                    $funcionario_escala->escala_id = $request->escala_id;
                    $funcionario_escala->dia = $request->dia;
                    $funcionario_escala->save();

                    return response()->json(['status' => true, 'message' => 'funcionario_escala_actualizada_com_succeso', 'funcionario_escala' => $funcionario_escala], 200);

                }else{
                    return response()->json(['message' => 'funcionario_escala_nao_encontrado'], 200);
                }
            }
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_funcionario_escala'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FuncionarioEscala  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $funcionario_escala = FuncionarioEscala::find($id);
        
            if($funcionario_escala!=null){
                $funcionario_escala->delete();
                return response()->json(['status' => true, 'funcionario_escala' => 'funcionario_escala_excluido'], 200);
            }else{
                return response()->json(['message' => 'funcionario_escala_nao_encontrada'], 200);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_excluir_funcionario_escala'], 500);
        }
    }
}
