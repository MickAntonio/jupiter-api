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
                'funcionario_escala' => FuncionarioEscala::where('id', '>', 0)->with(['funcionario.contactos', 'escala'])->orderBy('id', 'desc')->get()
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_trazer_funcionarios_escalas', 'errors'=>$e], 500);
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

                return response()->json(
                    ['status' => true, 'message' => 'funcionario_escala_adicionado_com_succeso', 'funcionario_escala' => 
                        FuncionarioEscala::where('id', $funcionario_escala->id)->with(['funcionario.contactos', 'escala'])->get()
                    ], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'nao_foi_possivel_adicionar_funcionario_escala', 'errors'=>$e], 500);
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
                return response()->json(['status' => true, 'funcionario_escala' => FuncionarioEscala::where('id', $id)->with(['funcionario.contactos', 'escala'])->get()], 200);
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

                    return response()->json(
                        ['status' => true, 'message' => 'funcionario_escala_actualizada_com_succeso', 'data'=>
                            [ 'funcionario_escala' => 
                                FuncionarioEscala::where('id', $id)->with(['funcionario.contactos', 'escala'])->get()
                            ]
                        ], 200);

                }else{
                    return response()->json(['status' => true, 'message' => 'funcionario_escala_nao_encontrado'], 404);
                }
            }
            
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'nao_foi_possivel_adicionar_funcionario_escala'], 500);
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
                return response()->json(['status' => true, 'message' => 'funcionario_escala_nao_encontrada'], 404);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'nao_foi_possivel_excluir_funcionario_escala'], 500);
        }
    }

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FuncionarioEscala  $funcionario_escala id
     * @return \Illuminate\Http\Response
     */
    public function escala_do_dia()
    {
        try {

            $funcionario_escala = FuncionarioEscala::whereHas('escala', function ($query) {
                $query->where('mes_id', date('m'));
                $query->where('ano', date('Year'));
            })->where('dia',  date('d') )->with(['funcionario.contactos', 'escala'])->orderBy('id', 'desc')->get();

            if($funcionario_escala!=null){
                return response()->json(['status' => true, 'data'=> [
                    'funcionario_escala' => $funcionario_escala
                    ]
                ]);
            }else{
                return response()->json(['status' => true, 'message' => 'nao_existe_escala_para_o_dia_'. date('d').'_'. date('m').'_'. date('Y')], 404);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_procurar_escala_de_hoje'], 500);
        }
    }

}
