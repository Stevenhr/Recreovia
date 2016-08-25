<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use App\Persona;
use App\ActividadGestor;
use App\Calificacion_servicio;
use App\TipoEntidad;
use App\TipoPersona;
use App\Condicion;
use App\Situacion;
use App\Localidad;
use App\ListaNovedad;



class mis_actividades_promotores extends Controller
{
    //
   protected $Usuario;
   
   public function __construct(){
           if (isset($_SESSION['Usuario']))
           $Usuario = $_SESSION['Usuario'];
   }


    public function Mis_Actividad(){
    	$PersonaActividad = new Persona;
        $TipoEntidad = new TipoEntidad;
        $TipoPersona = new TipoPersona;
        $Condicion = new Condicion;
        $Situacion = new Situacion;
        $Localidad = new Localidad;
        $ListaNovedad = new ListaNovedad;

    	$datos = [
            'TipoEntidad' => $TipoEntidad->all(),
            'TipoPersona' => $TipoPersona->all(),
            'Condicion' => $Condicion->all(),
            'Situacion' => $Situacion->all(),
            'Localidad' => $Localidad->all(),
            'ListaNovedad' => $ListaNovedad->all()
		];
	    return view('mis_actividades_promotor', $datos);
    }


     public function procesarValidacionGestor(Request $request)
	{
		$validator = Validator::make($request->all(),
		    [
				'Fecha_Inicio' => 'required',
				'Fecha_Fin' => 'required'
        	]
        );

        if ($validator->fails()){
            return response()->json(array('status' => 'error', 'errors' => $validator->errors()));
        }else{
        	$inf_model= $this->buscar($request->all());
        }
        return response()->json($inf_model);
	}

	public function buscar($input)
	{
		$id=$_SESSION['Usuario'][0];
       
		$id_act=$input['Id_Actividad_Promo'];
		$Fecha_Inicio=$input['Fecha_Inicio'];
		$Fecha_Fin=$input['Fecha_Fin'];
    	//echo "Id-> ".$id;
    	if(empty($id_act)){
    		$consulta=ActividadGestor::with('localidad','persona','parque')->where('Id_Responsable',$id)->whereBetween('Fecha_Ejecucion',array($Fecha_Inicio, $Fecha_Fin))->get();
    	}else{
    		$consulta=ActividadGestor::with('localidad','persona','parque')->where('Id_Actividad_Gestor',$id_act)->whereBetween('Fecha_Ejecucion',array($Fecha_Inicio, $Fecha_Fin))->get();
    	}
    	return $consulta;
	}

	public function obtenerActividad(Request $request, $id_actividad){

		$datosActividad = ActividadGestor::with('localidad','persona','parque','ejecucion')->find($id_actividad);
		$datos = ['datosActividad' => $datosActividad];
    	return  $datos;
    }

    public function procesarValidacionDatosEjecucion(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'Inst_grupo_comu' => 'required',
                'Localidad_eje' => 'required',
                'Tipo_entidad' => 'required',
                'Tipo_eje' => 'required',
                'Condicion' => 'required',
                'Situacion' => 'required',
                'M_0_5' => 'numeric',
                'F_0_5' => 'numeric',
                'M_6_12' => 'numeric',
                'F_6_12' => 'numeric',
                'M_13_17' => 'numeric',
                'F_13_17' => 'numeric',
                'M_18_26' => 'numeric',
                'F_18_26' => 'numeric',
                'M_27_59' => 'numeric',
                'F_27_59' => 'numeric',
                'M_60' => 'numeric',
                'F_60' => 'numeric'
            ]
        );

        if ($validator->fails()){
            return response()->json(array('status' => 'error', 'errors' => $validator->errors()));
        }else{
            return response()->json(array('status' => 'ok'));
        }
    }

    public function procesarValidacionDatosNovedades(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'Id_Requisito' => 'required',
                'causa' => 'required:alpha',
                'accion' => 'required:alpha'
            ]
        );
        if ($validator->fails()){
            return response()->json(array('status' => 'error', 'errors' => $validator->errors()));
        }else{
            return response()->json(array('status' => 'ok'));
        }
    }

    public function procesarValidacionRegistroEjecucion(Request $request)
    {
     //   var_dump($request->all());


        $validator = Validator::make($request->all(),
            [
                'puntualidad' => 'required',
                'divulgacion' => 'required',
                'escenarioMontaje' => 'required',
                'nombreRepresentante' => 'required',
                'telefonoRepresentante' => 'required',
                'imagen1' => 'required|mimes:jpeg,jpg,png,bmp',
                'imagen2' => 'required|mimes:jpeg,jpg,png,bmp',
                'imagen3' => 'mimes:jpeg,jpg,png,bmp',
                'imagen4' => 'mimes:jpeg,jpg,png,bmp',
                'listaAsistencia' => 'required|mimes:pdf'

            ]
        );


        if ($validator->fails()){
            return response()->json(array('status' => 'error', 'errors' => $validator->errors()));
        }else{
            
            $id_act = $request->input('Id_Actividad_E');
            
            $file1=$request->file('imagen1');
            $extension1=$file1->getClientOriginalExtension();
            $Nom_imagen1 = date('Y-m-d-H:i:s')."-imagen1-".$id_act;
            $file1->move(public_path().'/Img/EvidenciaFotografica/', $Nom_imagen1.'.'.$extension1);

            $file2=$request->file('imagen2');
            $extension2=$file2->getClientOriginalExtension();
            $Nom_imagen2 = date('Y-m-d-H:i:s')."-imagen2-".$id_act;
            $file2->move(public_path().'/Img/EvidenciaFotografica/', $Nom_imagen2.'.'.$extension2);


            if ($request->hasFile('imagen3')) {
                $file3=$request->file('imagen3');
                $extension3=$file3->getClientOriginalExtension();
                $Nom_imagen3 = date('Y-m-d-H:i:s')."-imagen3-".$id_act;
                $file3->move(public_path().'/Img/EvidenciaFotografica/', $Nom_imagen3.'.'.$extension3);

            }else{
                $Nom_imagen3="";
            }

            if ($request->hasFile('imagen4')) {
                $file4=$request->file('imagen4');
                $extension4=$file4->getClientOriginalExtension();
                $Nom_imagen4 = date('Y-m-d-H:i:s')."-imagen4-".$id_act;
                $file4->move(public_path().'/Img/EvidenciaFotografica/', $Nom_imagen4.'.'.$extension4);

            }else{
                $Nom_imagen4="";
            }
            $file_listaAsistencia=$request->file('listaAsistencia');
            $extensionFile1=$file_listaAsistencia->getClientOriginalExtension();
            $Nom_listaAsistencia = date('Y-m-d-H:i:s')."-listaAsistencia-".$id_act;
            $file_listaAsistencia->move(public_path().'/Img/EvidenciaArchivo/', $Nom_listaAsistencia.'.'.$extensionFile1);


            if ($request->hasFile('acta')) {
                $file_acta=$request->file('acta');
                $extensionFile2=$file_acta->getClientOriginalExtension();
                $Nom_Acta = date('Y-m-d-H:i:s')."-"."-acta-".$id_act;
                $file_acta->move(public_path().'/Img/EvidenciaArchivo/',$Nom_Acta.'.'.$extensionFile2);
            }else{
                $Nom_Acta="";
            }
            


            $nomarchivos= array(
                'imagen1'=> $Nom_imagen1,
                'imagen2'=> $Nom_imagen2,
                'imagen3'=> $Nom_imagen3,
                'imagen4'=> $Nom_imagen4,
                'listaAsistencia'=> $Nom_listaAsistencia ,
                'acta'=> $Nom_Acta);


         //   dd($request->all());
            $this->guardar($request->all(),$nomarchivos);
        }


        return response()->json(array('status' => 'ok'));
    }


    public function guardar($input,$nomarchivos)
    {
        $model_A = new Calificacion_servicio;
        return $this->crear_ejecucion($model_A, $input,$nomarchivos);
    }

    public function crear_ejecucion($model, $input,$nomarchivos)
    {
        $model['Id_Actividad_Gestor'] = $input['Id_Actividad_E'];
       


        $model['Id_Puntualidad'] = $input['puntualidad'];
        $model['Id_Divulgacion'] = $input['divulgacion'];
        $model['Id_Montaje'] = $input['escenarioMontaje'];
        $model['Id_Cumplimiento'] = $input['cumplimiento'];
        $model['Id_Variedad'] = $input['variedadCreatividad'];
        $model['Id_Seguridad'] = $input['seguridad'];
        $model['Nombre_Representante'] = $input['nombreRepresentante'];
        $model['Telefono'] = $input['telefonoRepresentante'];
        $model['Url_Imagen1'] = $nomarchivos['imagen1'];
        $model['Url_Imagen2'] = $nomarchivos['imagen2'];
        $model['Url_Imagen3'] = $nomarchivos['imagen3'];
        $model['Url_Imagen4'] = $nomarchivos['imagen4'];
        $model['Url_Asistencia'] = $nomarchivos['listaAsistencia'];
        $model['Url_Acta'] = $nomarchivos['acta'];
        $model->save();
        
        //dd($input['vector_novedades']);
        
        $id_Activi= $input['Id_Actividad_E'];
        $model_A = ActividadGestor::find($id_Activi);

        
        //  $model_P->calificaciomServicio()->attach($id_localidad);
       $data0 = json_decode($input['vector_novedades']);
       foreach($data0 as $obj){
                 $model_A->novedad()->attach($model_A->Id_Actividad_Gestor,['Id_novedad'=>2,
                'Causa'=>$obj->causa,
                'Accion'=>$obj->accion]);
        }


        $data01 = json_decode($input['vector_datos_ejecucion']);

    
        foreach($data01 as $obj1){
                 $model_A->ejecucion()->attach($model_A->Id_Actividad_Gestor,[
                'Comunidad'=>$obj1->Inst_grupo_comu,
                'Localidad'=>$obj1->Localidad_eje,
                'TipoEntidad'=>$obj1->Tipo_entidad,
                'Tipo'=>$obj1->Tipo_eje,
                'Condicion'=>$obj1->Condicion,
                'Situacion'=>$obj1->Situacion,
                'F_0a5'=>$obj1->F_0_5,
                'M_0a5'=>$obj1->M_0_5,
                'F_6a12'=>$obj1->F_6_12,
                'M_6a12'=>$obj1->M_6_12,
                'F_13a17'=>$obj1->F_13_17,
                'M_13a17'=>$obj1->M_13_17,
                'F_18a26'=>$obj1->F_18_26,
                'M_18a26'=>$obj1->M_18_26,
                'F_27a59'=>$obj1->F_27_59,
                'M_27a59'=>$obj1->M_27_59,
                'F_60'=>$obj1->M_60,
                'M_60'=>$obj1->F_60
                ]);
        }

        $hoy = date("Y-m-d");  
        $model_A['Estado_Ejecucion'] = 2;
        $model_A['Fecha_Registro_Ejecución'] = $hoy;
        $model_A->save();


        return $model;
    }


}
