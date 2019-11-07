<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Vans;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VansController extends Controller
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
                'vans' => Vans::all()
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_trazer_vans'], 500);
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
                'matricula'=>'required',
                'descricao'=>'sometimes',
                'modelo'=>'required',
                'marca'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{
                
                $van = new Vans;
                $van->matricula = $request->matricula;
                $van->descricao = $request->descricao;
                $van->modelo    = $request->modelo;
                $van->marca     = $request->marca;
                $van->save();

                return response()->json(['status' => true, 'message' => 'van_adicionado_com_succeso', 'van' => $van], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_van'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vans  $van id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $van = Vans::find($id);
        
            if($van!=null){
                return response()->json(['status' => true, 'van' => $van], 200);
            }else{
                return response()->json(['message' => 'van_nao_encontrado'], 200);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_procurar_van'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vans  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'matricula'=>'required',
                'descricao'=>'sometimes',
                'modelo'=>'required',
                'marca'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{

                $van = Vans::find($id);
        
                if($van!=null){
                
                    $van->mes = $request->mes;
                    $van->ano = $request->ano;
                    $van->save();

                    return response()->json(['status' => true, 'message' => 'van_actualizada_com_succeso', 'van' => $van], 200);

                }else{
                    return response()->json(['message' => 'van_nao_encontrado'], 200);
                }
            }
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_van'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vans  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $van = Vans::find($id);
        
            if($van!=null){
                $van->delete();
                return response()->json(['status' => true, 'van' => 'van_excluido'], 200);
            }else{
                return response()->json(['message' => 'van_nao_encontrada'], 200);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_excluir_van'], 500);
        }
    }
}
