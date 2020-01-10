<?php

namespace App\Http\Controllers\Api;

use App\User;
use Validator;
use App\Models\Moradas;
use App\Models\Funcionarios;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FuncionarioContactos;
use App\Http\Controllers\Api\FileUploadController;

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
                'data'=>[
                    'funcionarios' => Funcionarios::where('id', '>', 0)->with(['usuario', 'contactos', 'morada'])->orderBy('id', 'desc')->get()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_trazer_funcionarios', 'errors'=>$e], 500);
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
                'nr_bi'=>'required',
                'nif'=>'sometimes'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{

                /**
                 * adiciona o usuario do funcionario
                 */
                
                if($request->usuario){

                    $validator = Validator::make($request->usuario, [
                        'name' => 'required',
                        'email' => 'required|email|unique:users',
                        'password' => 'required',
                    ]);
        
                    if($validator->fails()){
                        return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
                    }else{

                        $usuario = new User;
                        $usuario->name  = $request->usuario['name'];
                        $usuario->email = $request->usuario['email'];
                        $usuario->password = bcrypt($request['password']);
                        $usuario->save();

                        $usuario_id = $usuario->id;
                    }

                }else if($request->usuario_id){

                    $usuario_id = $request->usuario_id;

                }else{
                    return response()->json(['status' => false, 'message' => 'informe_os_dados_de_usuario_ou_usuario_id'], 200);
                }

                /**
                 * adiciona um novo funcionario
                 */

                $funcionario                  = new Funcionarios;
                $funcionario->nome            = $request->nome;
                $funcionario->nacionalidade   = $request->nacionalidade;
                $funcionario->genero          = $request->genero;
                $funcionario->data_nascimento = $request->data_nascimento;
                $funcionario->nr_bi           = $request->nr_bi;
                $funcionario->nif             = $request->nif;
                $funcionario->usuario_id      = $usuario_id;

                if (isset($request->imagem)) {
                    $file = $request->imagem;
                    $funcionario->imagem = (new FileUploadController)->fileUploadBase64($request->imagem, 'funcionarios');
                }else{
                    $funcionario->imagem  = 'default.jpg';
                }

                $funcionario->save();

                /**
                 * adiciona os contactos do funcionario
                 */

                if($request->contactos){

                    foreach ($request->contactos as $contacto) {

                        $validator = Validator::make($contacto, [
                            'contacto'=>'required',
                            'tipo'=>'required'
                        ]);

                        if($validator->fails()){
                            return response()->json(['status' => false, 'message' => $validator->errors()->all()]);
                        }else{
                            $funcionario_contacto = new FuncionarioContactos;
                            $funcionario_contacto->contacto = $contacto['contacto'];
                            $funcionario_contacto->tipo     = $contacto['tipo'];
                            $funcionario_contacto->funcionario_id = $funcionario->id;
                            $funcionario_contacto->save();

                        }
                    }
                    
                }

                /**
                 * adiciona morada do funcionario
                 */

                if($request->morada){

                    $validator = Validator::make($request->morada, [
                        'municipio_id'=>'required',
                    ]);

                    if($validator->fails()){
                        return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
                    }else{
                        $morada = new Moradas;
                        $morada->rua    = $request->morada['rua'];
                        $morada->bairro = $request->morada['bairro'];
                        $morada->numero_casa    = $request->morada['numero_casa'];
                        $morada->municipio_id   = $request->morada['municipio_id'];
                        $morada->funcionario_id = $funcionario->id;
                        $morada->save();
                    }
                    
                }

                return response()->json(['status' => true, 'message' => 'funcionario_adicionado_com_succeso', 'data'=>[
                    'fucionario' => Funcionarios::where('id', $funcionario->id)->with(['usuario', 'contactos', 'morada'])->get() 
                    ]
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_adicionar_funcionario', 'errors'=>$e], 500);
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

            $funcionario = Funcionarios::where('id', $id)->with(['usuario', 'contactos', 'morada'])->get();

            if($funcionario!=null){
                return response()->json(['status' => true, 'data'=>['funcionario' => $funcionario]], 200);
            }else{
                return response()->json(['message' => 'funcionario_nao_encontrado'], 200);
            }

        } catch (\Throwable $e) {
            return response()->json(['message' => 'nao_foi_possivel_procurar_funcionario', "erros"=>$e], 500);
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
                'nr_bi'=>'required',
                'nif'=>'sometimes'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{

                /**
                 * adiciona o usuario do funcionario
                 */

                $usuario_not_found = ['message' => 'usuario_nao_encontrado'];

                if($request->usuario){

                    $usuario = User::find($request->usuario['id']);
        
                    if($usuario!=null){

                        $validator = Validator::make($request->usuario, [
                            'id' => 'required',
                            'name' => 'required',
                            'email' => 'required|email|unique:users,email,'.$request->usuario['id'],
                            'password' => 'required',
                        ]);
            
                        if($validator->fails()){
                            return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
                        }else{

                            $usuario->name  = $request->usuario['name'];
                            $usuario->email = $request->usuario['email'];
                            $usuario->password = bcrypt($request['password']);
                            $usuario->save();

                            $usuario_id = $usuario->id;
                        }

                    }else{
                        return response()->json($usuario_not_found, 404);
                    }

                }else if($request->usuario_id){

                    $usuario = User::find($request->usuario_id);
        
                    if($usuario!=null){
                        $usuario_id = $request->usuario_id;
                    }else{
                        return response()->json($usuario_not_found, 404);
                    }

                }

                /**
                 * adiciona um novo funcionario
                 */
                $funcionario = Funcionarios::find($id);
        
                if($funcionario!=null){
                
                    $funcionario->nome            = $request->nome;
                    $funcionario->nacionalidade   = $request->nacionalidade;
                    $funcionario->genero          = $request->genero;
                    $funcionario->data_nascimento = $request->data_nascimento;
                    $funcionario->nr_bi           = $request->nr_bi;
                    $funcionario->nif             = $request->nif;
                    
                    if (isset($request->imagem)) {
                        $funcionario->imagem = (new FileUploadController)->fileUploadBase64($request->imagem, 'funcionarios');
                    }
                    
                    $funcionario->save();

                }else{
                    return response()->json(['message' => 'funcionario_nao_encontrado'], 404);
                }

                /**
                 * adiciona os contactos do funcionario
                 */
                if($request->contactos){

                    foreach ($request->contactos as $contacto) {

                        $validator = Validator::make($contacto, [
                            'contacto'=>'required',
                            'tipo'=>'required'
                        ]);

                        if($validator->fails()){
                            return response()->json(['status' => false, 'message' => $validator->errors()->all()]);
                        }else{

                            if(isset($contacto['id'])){
                                $funcionario_contacto =  FuncionarioContactos::find($contacto['id']);
                            }else{
                                $funcionario_contacto = new FuncionarioContactos;
                            }

                            $funcionario_contacto->contacto = $contacto['contacto'];
                            $funcionario_contacto->tipo     = $contacto['tipo'];
                            $funcionario_contacto->funcionario_id =  $funcionario->id;
                            $funcionario_contacto->save();
                        }
                    }
                    
                }

                /**
                 * adiciona morada do funcionario
                 */
                if($request->morada){

                    $validator = Validator::make($request->morada, [
                        'municipio_id'=>'required',
                    ]);

                    if($validator->fails()){
                        return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
                    }else{

                        $morada = Moradas::where('funcionario_id', $funcionario->id)->first();

                        if($morada==null){
                            $morada = new Moradas;
                        }

                        $morada->rua    = $request->morada['rua'];
                        $morada->bairro = $request->morada['bairro'];
                        $morada->numero_casa    = $request->morada['numero_casa'];
                        $morada->municipio_id   = $request->morada['municipio_id'];
                        $morada->funcionario_id = $funcionario->id;
                        $morada->save();
                    }
                }

                return response()->json(['status' => true, 'message' => 'funcionario_actualizada_com_succeso', 
                'data'=>[
                    'fucionario' => Funcionarios::where('id', $funcionario->id)->with(['usuario', 'contactos', 'morada'])->get() 
                    ]
                ], 201);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_actualizar_funcionario', 'erros'=>$e], 500);
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
                return response()->json(['status' => true, 'message' => 'funcionario_excluido'], 200);
            }else{
                return response()->json(['message' => 'funcionario_nao_encontrada'], 404);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['message' => 'nao_foi_possivel_excluir_funcionario'], 500);
        }
    }


    /**
     * Others respources
     */

    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function motoristas()
    {
        try {
            
            $usuarios = User::whereRoleIs('Motorista')->pluck('id');

            return response()->json([
                'status'    => true,
                'data'=>[
                    'funcionarios' => Funcionarios::whereIn('usuario_id', $usuarios)->with(['usuario', 'contactos', 'morada'])->orderBy('id', 'desc')->get()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'nao_foi_possivel_trazer_funcionarios', 'errors'=>$e], 500);
        }
    }
}