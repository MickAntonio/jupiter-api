<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Contactos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactosController extends Controller
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
                'data'=>[
                    'contactos' => Contactos::all()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_trazer_contactos'], 500);
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
                'telefone'=>'sometimes',
                'telemovel'=>'sometimes'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{
                
                $contacto = new Contactos;
                $contacto->telefone = $request->telefone;
                $contacto->telemovel = $request->telemovel;
                $contacto->save();

                return response()->json(['status' => true, 'message' => 'contacto_adicionado_com_succeso', 
                    'data'=>['contacto' => $contacto]
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_contacto', 'errors'=>$e], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contactos  $contacto
     * @return \Illuminate\Http\Response
     */
    public function show(Contactos $contacto)
    {
        try {

            $contacto = Contactos::find($id);
        
            if($contacto!=null){
                return response()->json(['status' => true, 'data'=>['contacto' => $contacto]], 200);
            }else{
                return response()->json(['message' => 'contacto_nao_encontrado'], 200);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_procurar_contacto'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contactos  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contactos $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'telefone'=>'sometimes',
                'telemovel'=>'sometimes'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{

                $contacto = Contactos::find($id);
        
                if($contacto!=null){
                
                    $contacto->telefone  = $request->telefone;
                    $contacto->telemovel = $request->telemovel;
                    $contacto->save();

                    return response()->json(['status' => true, 'message' => 'contacto_actualizado_com_succeso', 'data'=>['contacto' => $contacto]], 200);

                }else{
                    return response()->json(['message' => 'contacto_nao_encontrado'], 200);
                }
            }
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_contacto'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contactos  $contacto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contactos $contacto)
    {
        try {

            $contacto = Contactos::find($id);
        
            if($contacto!=null){
                $contacto->delete();
                return response()->json(['status' => true, 'message' => 'contacto_excluido'], 200);
            }else{
                return response()->json(['message' => 'contacto_nao_encontrado'], 200);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_excluir_contacto'], 500);
        }
    }
}
