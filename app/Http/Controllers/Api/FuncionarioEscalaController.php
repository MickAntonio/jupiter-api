<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Escalas;
use Illuminate\Http\Request;
use App\Models\FuncionarioEscala;
use App\Http\Controllers\Controller;

class FuncionarioEscalaController extends Controller
{

    private $dia=null;
    private $semana=null;
    private $mes=null;
    private $ano=null;

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
                'funcionario_escala' => FuncionarioEscala::where('id', '>', 0)->with(['funcionario.contactos', 'escala'])->orderBy('dia', 'asc')->get()
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
    public function store_escala_automatica(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'ano'=>'required',
                'mes_id'=>'required',
                'motoristas_por_dia'=>'required',
                'funcionarios'=>'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->all()]);
            }else{

                //motoristas por dia - 2
                $motoristas_por_dia = $request->motoristas_por_dia;
                // total de motoristas para este mes - 6
                $total_motoristas = count($request->funcionarios);

                if($motoristas_por_dia>$total_motoristas){
                    return response()->json(['status' => 'fail', 'message' => 'motoristas_por_dia_maior_que_motoristas_selecionados']);
                }

                $escala = Escalas::where('ano', $request->ano)->where('mes_id', $request->mes_id)->first();

                if(is_null($escala)){
                    $escala = new Escalas;
                    $escala->mes_id = $request->mes_id;
                    $escala->ano    = $request->ano;
                    $escala->save();
                }

                $t = 0;
                $dia_inicio = 1;

                foreach ($request->funcionarios as $id) {

                    if($t < $motoristas_por_dia){

                    }else{
                        $t = 0;
                        $dia_inicio++;
                    }
                
                    $dia = $dia_inicio;

                    while ($dia <= 31) {

                        $funcionario_escala = new FuncionarioEscala;
                        $funcionario_escala->funcionario_id = $id;
                        $funcionario_escala->escala_id = $escala->id;
                        $funcionario_escala->dia = $dia;
                        $funcionario_escala->save();


                        $dia = $dia + round( $total_motoristas / $motoristas_por_dia );

                    }

                    $t++;

                }

                return response()->json(
                    ['status' => true, 'message' => 'funcionario_escala_adicionado_com_succeso', 'funcionario_escala' => 
                        FuncionarioEscala::where('escala_id', $escala->id)->with(['funcionario.contactos', 'escala'])->get()
                    ], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'nao_foi_possivel_adicionar_funcionario_escala', 'errors'=>$e], 500);
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
    public function escala_do_dia($dia = null)
    {
        try {

            if(!is_null($dia)){
                $this->dia = $dia;
            }else{
                $this->dia = date('d');
            }

            $funcionario_escala = FuncionarioEscala::whereHas('escala', function ($query) {
                $query->where('mes_id', date('m'));
                $query->where('ano', date('Year'));
            })->where('dia',  $this->dia )->with(['funcionario.contactos', 'escala'])->orderBy('id', 'asc')->get();

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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FuncionarioEscala  $funcionario_escala id
     * @return \Illuminate\Http\Response
     */
    public function escala_semanal($date=null)
    {
        try {

            if(!is_null($date)){
                $this->mes = date('m', strtotime($date));
                $this->ano = date('Y', strtotime($date));
            }else{
                $this->mes = date('m');
                $this->ano = date('Y');
            }

            $funcionario_escala = FuncionarioEscala::whereHas('escala', function ($query) {
                $query->where('mes_id', date('m'));
                $query->where('ano', date('Year'));
            })->whereBetween('dia',  $this->days_of_week($date) )->with(['funcionario.contactos', 'escala'])->orderBy('dia', 'asc')->get();

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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FuncionarioEscala  $funcionario_escala id
     * @return \Illuminate\Http\Response
     */
    public function escala_mensal($mes=null)
    {
        try {

            if(!is_null($mes)){
                $this->mes = $mes;
            }else{
                $this->mes = date('m');
            }

            $funcionario_escala = FuncionarioEscala::whereHas('escala', function ($query) {
                $query->where('mes_id', $this->mes);
                $query->where('ano', date('Year'));
            })->with(['funcionario.contactos', 'escala'])->orderBy('dia', 'asc')->get();

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



    /**
     * Help methods
     */

    public function days_of_week($date=null){

        if(!is_null($date)){
            $dia = date('d', strtotime($date))+1;
            $mes = date('m', strtotime($date));
            $ano = date('Y', strtotime($date));
        }else{
            $dia = date('d')+1;
            $mes = date('m');
            $ano = date('Y');
        }

        $date_week = $ano.'-'.$mes.'-'.$dia;

        $week = date('W', strtotime($date_week));
        $year = date('Y');

        $from = date("Y-m-d", strtotime("{$year}-W{$week}+1")); 
        $to = date("Y-m-d", strtotime("{$year}-W{$week}-6"));  

        return [date('d', strtotime($from)), date('d', strtotime($to))];

    }

}
