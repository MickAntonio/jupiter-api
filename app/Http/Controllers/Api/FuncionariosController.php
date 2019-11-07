<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Funcionarios;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FuncionariosController extends Controller
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
                'funcionarios' => Funcionarios::all()
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_trazer_funcionarios'], 500);
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
                'nome'=>'required',
                'nacionalidade'=>'required',
                'genero'=>'required',
                'data_nascimento'=>'required',
                'usuario_id'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{
                
                $funcionario                  = new Funcionarios;
                $funcionario->nome            = $request->nome;
                $funcionario->nacionalidade   = $request->nacionalidade;
                $funcionario->genero          = $request->genero;
                $funcionario->data_nascimento = $request->data_nascimento;
                $funcionario->usuario_id      = $request->usuario_id;
                $funcionario->save();

                return response()->json(['status' => true, 'message' => 'funcionario_adicionado_com_succeso', 'fucionario' => $funcionario], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_funcionario'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Funcionarios  $funcionario id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $funcionario = Funcionarios::find($id);
        
            if($funcionario!=null){
                return response()->json(['status' => true, 'funcionario' => $funcionario], 200);
            }else{
                return response()->json(['message' => 'funcionario_nao_encontrado'], 200);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_procurar_funcionario'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Funcionarios  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'nome'=>'required',
                'nacionalidade'=>'required',
                'genero'=>'required',
                'data_nascimento'=>'required',
                'usuario_id'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{

                $funcionario = Funcionarios::find($id);
        
                if($funcionario!=null){
                
                    $funcionario->nome            = $request->nome;
                    $funcionario->nacionalidade   = $request->nacionalidade;
                    $funcionario->genero          = $request->genero;
                    $funcionario->data_nascimento = $request->data_nascimento;
                    $funcionario->usuario_id      = $request->usuario_id;
                    $funcionario->save();

                    return response()->json(['status' => true, 'message' => 'funcionario_actualizada_com_succeso', 'fucionario' => $funcionario], 200);

                }else{
                    return response()->json(['message' => 'funcionario_nao_encontrado'], 200);
                }
            }
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_funcionario'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Funcionarios  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $funcionario = Funcionarios::find($id);
        
            if($funcionario!=null){
                $funcionario->delete();
                return response()->json(['status' => true, 'funcionario' => 'funcionario_excluido'], 200);
            }else{
                return response()->json(['message' => 'funcionario_nao_encontrada'], 200);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_excluir_funcionario'], 500);
        }
    }
}
