<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Vans;
use App\Models\VanContactos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\FileUploadController;

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
                'data'=>[
                    'vans' => Vans::where('id', '>', 0)->with(['contactos', 'modelo.marca'])->orderBy('id', 'desc')->get()
                ]
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
                'modelo_id'=>'required',
                'cor_id'=>'required',
                'imagem'=>'required',
                // 'nr_ocupantes'=>'sometimes'
            ]);

            if($validator->fails()){
                return response()->json(['status' => true, 'message' => $validator->errors()->all()]);
            }else{
                
                $van = new Vans;
                $van->matricula = $request->matricula;
                $van->descricao = $request->descricao;
                $van->modelo_id    = $request->modelo_id;
                $van->cor_id       = $request->cor_id;
                $van->imagem       = $request->imagem;
                // $van->nr_ocupantes = $request->nr_ocupantes;

                if (isset($request->imagem)) {
                    $file = $request->imagem;
                    $van->imagem = (new FileUploadController)->fileUpload($file, 'uploads/vans');
                }else{
                    $van->imagem  = 'default.jpg';
                }


                $van->save();

                /**
                 * adiciona os contactos da van
                 */

                if($request->contactos){

                    foreach ($request->contactos as $contacto) {

                        $validator = Validator::make($contacto, [
                            'contacto'=>'required'
                        ]);

                        if($validator->fails()){
                            return response()->json(['status' => false, 'message' => $validator->errors()->all()]);
                        }else{

                            $van_contacto = new VanContactos;
                            $van_contacto->contacto = $contacto['contacto'];
                            $van_contacto->van_id   = $van->id;
                            $van_contacto->save();

                        }
                    }
                    
                }

                return response()->json(['status' => true, 'message' => 'van_adicionado_com_succeso', 
                    'data'=>[
                        'van' => $van::where('id', $van->id)->with(['contactos', 'modelo.marca'])->get()
                        ]
                    ], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_van', 'ERROS'=>$e], 500);
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
                return response()->json(['status' => true, 'van' => $van::where('id', $van->id)->with(['contactos', 'modelo.marca'])->get()], 200);
            }else{
                return response()->json(['status' => true, 'message' => 'van_nao_encontrado'], 404);
            }

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'nao_foi_possivel_procurar_van'], 500);
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
                'modelo_id'=>'required',
                'cor_id'=>'required',
                'imagem'=>'required',
                'nr_ocupantes'=>'sometimes'
            ]);

            if($validator->fails()){
                return response()->json(['status' => false, 'message' => $validator->errors()->all()]);
            }else{

                $van = Vans::find($id);
        
                if($van!=null){
                
                    $van->matricula = $request->matricula;
                    $van->descricao = $request->descricao;
                    $van->modelo_id    = $request->modelo_id;
                    $van->cor_id       = $request->cor_id;
                    if (isset($request->imagem)) {
                        $file = $request->imagem;
                        $van->imagem = (new FileUploadController)->fileUpload($file, 'uploads/vans');
                    }
                    // $van->nr_ocupantes = $request->nr_ocupantes;
                    $van->save();

                    /**
                     * adiciona os contactos da van
                     */

                    if($request->contactos){

                        foreach ($request->contactos as $contacto) {

                            $validator = Validator::make($contacto, [
                                'contacto'=>'required'
                            ]);

                            if($validator->fails()){
                                return response()->json(['status' => false, 'message' => $validator->errors()->all()]);
                            }else{

                                if(isset($contacto['id']) && VanContactos::find($contacto['id'])!=null ){
                                    $van_contacto =  VanContactos::find($contacto['id']);
                                }else{
                                    $van_contacto = new VanContactos;
                                }
    
                                $van_contacto->contacto = $contacto['contacto'];
                                $van_contacto->van_id   = $van->id;
                                $van_contacto->save();

                            }
                        }
                        
                    }

                    return response()->json(['status' => true, 'message' => 'van_actualizada_com_succeso', 
                        'data'=>[
                            'van' => $van::where('id', $van->id)->with(['contactos'])->get()
                        ]
                    ], 200);

                }else{
                    return response()->json(['status' => true, 'message' => 'van_nao_encontrado'], 404);
                }
            }
            
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'nao_foi_possivel_actualizar_van', 'errors'=>$e], 500);
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
                return response()->json(['status' => true, 'message' => 'van_excluido'], 200);
            }else{
                return response()->json(['message' => 'van_nao_encontrada'], 404);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_excluir_van'], 500);
        }
    }
}
