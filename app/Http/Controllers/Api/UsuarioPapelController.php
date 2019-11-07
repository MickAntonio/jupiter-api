<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\UsuarioPapel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsuarioPapelController extends Controller
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
                'usuarios_papeis' => UsuarioPapel::all()
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_trazer_usuarios_papeis'], 500);
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
                'papel_id'=>'required',
                'usuario_id'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{
                
                $usuario_papel = new UsuarioPapel;
                $usuario_papel->usuario_id = $request->usuario_id;
                $usuario_papel->papel_id = $request->papel_id;
                $usuario_papel->save();

                return response()->json(['status' => true, 'message' => 'usuario_papel_adicionado_com_succeso', 'usuario_papel' => $usuario_papel], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_usuario_papel'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UsuarioPapel  $usuario_papel id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $usuario_papel = UsuarioPapel::find($id);
        
            if($usuario_papel!=null){
                return response()->json(['status' => true, 'usuario_papel' => $usuario_papel], 200);
            }else{
                return response()->json(['message' => 'usuario_papel_nao_encontrado'], 200);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_procurar_usuario_papel'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UsuarioPapel  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'papel_id'=>'required',
                'usuario_id'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{

                $usuario_papel = UsuarioPapel::find($id);
        
                if($usuario_papel!=null){
                
                    $usuario_papel->usuario_id = $request->usuario_id;
                    $usuario_papel->papel_id = $request->papel_id;
                    $usuario_papel->save();

                    return response()->json(['status' => true, 'message' => 'usuario_papel_actualizada_com_succeso', 'usuario_papel' => $usuario_papel], 200);

                }else{
                    return response()->json(['message' => 'usuario_papel_nao_encontrado'], 200);
                }
            }
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_usuario_papel'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UsuarioPapel  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $usuario_papel = UsuarioPapel::find($id);
        
            if($usuario_papel!=null){
                $usuario_papel->delete();
                return response()->json(['status' => true, 'usuario_papel' => 'usuario_papel_excluido'], 200);
            }else{
                return response()->json(['message' => 'usuario_papel_nao_encontrada'], 200);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_excluir_usuario_papel'], 500);
        }
    }
}
