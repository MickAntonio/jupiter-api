<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Papeis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PapeisController extends Controller
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
                'papels' => Papeis::all()
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_trazer_papels'], 500);
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
                'designacao'=>'required',
                'descricao'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{
                
                $papel = new Papeis;
                $papel->designacao = $request->designacao;
                $papel->descricao = $request->descricao;
                $papel->save();

                return response()->json(['status' => true, 'message' => 'papel_adicionado_com_succeso', 'papel' => $papel], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_papel'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Papeis  $papel id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $papel = Papeis::find($id);
        
            if($papel!=null){
                return response()->json(['status' => true, 'papel' => $papel], 200);
            }else{
                return response()->json(['message' => 'papel_nao_encontrado'], 200);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_procurar_papel'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Papeis  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'designacao'=>'required',
                'descricao'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{

                $papel = Papeis::find($id);
        
                if($papel!=null){
                
                    $papel->designacao = $request->designacao;
                    $papel->descricao = $request->descricao;
                    $papel->save();

                    return response()->json(['status' => true, 'message' => 'papel_actualizada_com_succeso', 'papel' => $papel], 200);

                }else{
                    return response()->json(['message' => 'papel_nao_encontrado'], 200);
                }
            }
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_papel'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Papeis  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $papel = Papeis::find($id);
        
            if($papel!=null){
                $papel->delete();
                return response()->json(['status' => true, 'papel' => 'papel_excluido'], 200);
            }else{
                return response()->json(['message' => 'papel_nao_encontrada'], 200);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_excluir_papel'], 500);
        }
    }
}
