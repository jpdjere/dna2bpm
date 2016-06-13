<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * inventory
 * 
 * Description of the class
 * 
 * @author Martin González 
 * @date    Apr 20, 2015
 */
$estado;

class bonita_licitaciones extends MX_Controller {


    function __construct() {
        parent::__construct();
        $this->load->model('user/user');
        $this->load->model('user/group');
        $this->load->model('model_bonita_licitaciones');
        $this->user->authorize('modules/bonita');
        $this->load->library('parser');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'bonita/';
        //----LOAD LANGUAGE
        $this->idu = (float) $this->session->userdata('iduser');
        //---config
        $this->load->config('bonita/config');
        $this->asignacion_actual=0;
    }
    
    function Index(){
        $this->user->authorize();
    	$this->load->module('dashboard');
    	$this->dashboard->dashboard('bonita/json/bonita_licitaciones_abm.json');
    }
    
/**************************************ENTIDADES**************************************/
    function bonita_licitaciones_list(){
        $this->user->authorize();
	    $this->load->module('dashboard');
	    $this->dashboard->dashboard('bonita/json/bonita_licitaciones_list.json');
    }
   
    function bonita_abm_entidades(){
        $this->user->authorize();
	    $this->load->module('dashboard');
	    $this->dashboard->dashboard('bonita/json/bonita_licitaciones_entidades.json');
    }

    function bonita_entidades_mostrar(){
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $entidades=$this->model_bonita_licitaciones->listar_entidades();
        $lista = '<table id="table_ent" class="table">';
        foreach($entidades as $ent){
            $lista =  $lista.'<tr><td>RAZÓN SOCIAL:  </td><td><a href="" data-id="'.$ent['_id'].'" data-cmd="editar" data-rsocial="'.$ent['rsocial'].'" data-ent_cuit="'.$ent['ent_cuit'].'" data-obs="'.$ent['obs'].'" title=”EDITAR” name="editar">'.$ent['rsocial'].'</a></td><td> -  CUIT: '.$ent['ent_cuit'].'  -</td><td><a href=""   data-id="'.$ent['_id'].'" data-cmd="borrar" name="borrar"> BORRAR</a></td></tr>';
        }
        $lista =  $lista.'</table>';
        $customData['lista'] = $lista;
        return $this->parser->parse('bonita/bonita_entidades_view',$customData,true,true);
    }

    function bonita_licitaciones_entidad_nueva(){
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $customData['titulo'] = "CARGAR NUEVA ENTIDAD";
        $return['tabla'] = $this->parser->parse('bonita/bonita_entidades_nuevo_view',$customData,true,true);
        echo json_encode($return);
        return $return;
    }
   
    function bonita_licitaciones_entidad_editar(){
        $customData = $this->input->post();
        $customData['titulo'] = "EDITAR ENTIDAD";
        $customData['base_url'] = $this->base_url;
        $return['tabla'] = $this->parser->parse('bonita/bonita_entidades_nuevo_view',$customData,true,true);
        echo json_encode($return);
        return $return;
    }

    function bonita_licitaciones_entidad_nueva_cargar(){
        $headerArr = array();
        $fields = $this->input->post();
        $headerArr['rsocial'] = $fields['fields'][0]['value'];
        $headerArr['ent_cuit'] = $fields['fields'][1]['value'];
        $headerArr['obs'] = $fields['fields'][2]['value'];
        $headerArr['borrado'] = 0;
        $result = $this->model_bonita_licitaciones->guardar_entidades($headerArr);
        echo $result;
    }
   
    function bonita_licitaciones_entidad_editar_cargar(){
        $headerArr = array();
        $fields = $this->input->post();
        $headerArr['rsocial'] = $fields['fields'][0]['value'];
        $headerArr['ent_cuit'] = $fields['fields'][1]['value'];
        $headerArr['obs'] = $fields['fields'][2]['value'];
        $headerArr['id_mongo'] = $fields['id_mongo'];
        $headerArr['borrado'] = 0;
        $result = $this->model_bonita_licitaciones->guardar_entidades_editar($headerArr);
        echo $result;
    }
    
    function bonita_licitaciones_entidad_borrar(){
        $fields = $this->input->post();
        $headerArr = array();
        $headerArr['id_mongo'] = $fields['id_mongo'];
        $result = $headerArr['id_mongo'];
        $this->model_bonita_licitaciones->borrar_entidades($headerArr);
        echo $result;
    }
    
/**************************************LICITACIONES**************************************/
    function bonita_licitaciones(){
        $entidades = array();
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $licitaciones = $this->model_bonita_licitaciones->listar_licitaciones();
        foreach($licitaciones as $lic){
            $lista =  $lista.
            '<tr>
                <td>
                    '.$lic['resolucion'].'
                </td>
                <td>
                    '.sprintf("%02d", $lic['fechalic']['mday']).'/'.sprintf("%02d", $lic['fechalic']['mon']).'/'.$lic['fechalic']['year'].'
                </td>
                <td align="center">
                    <label>'.number_format($lic['cmax'], 0, ",", ".").'&nbsp;&nbsp;&nbsp;</label>
                </td>
                <td align="center">
                    '.number_format($lic['maxeeff'], 0, ",", ".").'&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                    <a href="" data-id="'.$lic['_id'].'" data-cmd="editar" data-resolucion="'.$lic['resolucion'].'" data-fechalic="'.sprintf("%02d", $lic['fechalic']['mday']).'/'.sprintf("%02d", $lic['fechalic']['mon']).'/'.sprintf("%02d", $lic['fechalic']['year']).'" data-cmax="'.$lic['cmax'].'" data-maxeeff="'.$lic['maxeeff'].'" data-obs="'.$lic['obs'].'" title=”EDITAR” name="editar">EDITAR</a>
                </td>
                <td>
                    <a href="" data-id="'.$lic['_id'].'" data-cmd="borrar" name="borrar">BORRAR</a>
                </td>
            </tr>';
        }
        $customData['lista'] = $lista;
        return $this->parser->parse('bonita/bonita_lista_licitaciones_view',$customData,true,true);
    }
   
    function bonita_licitaciones_nueva(){
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $customData['titulo'] = "CARGAR NUEVA LICITACIÓN";
        $return['tabla'] = $this->parser->parse('bonita/bonita_lista_licitaciones_nuevo_view',$customData,true,true);
        echo json_encode($return);
        return $return;   
    }
   
    function bonita_licitaciones_nueva_licitacion(){
        $headerArr = array();
        $fields = $this->input->post();
        $headerArr['resolucion'] = $fields['fields'][0]['value'];
        $headerArr['fechalic'] = $fields['fields'][1]['value'];
        $headerArr['cmax'] = $fields['fields'][2]['value'];
        $headerArr['maxeeff'] = $fields['fields'][3]['value'];
        $headerArr['obs'] = $fields['fields'][4]['value'];
        $headerArr['id_mongo'] = $fields['id_mongo'];
        $headerArr['borrado'] = 0;
        $headerArr['editable'] = true;
        $result = $this->model_bonita_licitaciones->guardar_licitaciones($headerArr);
        echo $result;
    }

    function bonita_licitaciones_licitaciones_editar(){
        $customData = $this->input->post();
        $customData['titulo'] = "EDITAR LICITACIÓN";
        $customData['base_url'] = $this->base_url;
        $return['tabla'] = $this->parser->parse('bonita/bonita_lista_licitaciones_nuevo_view',$customData,true,true);
        echo json_encode($return);
        return $return;
    }

    function bonita_licitaciones_licitacion_editar_cargar(){
        $headerArr = array();
        $fields = $this->input->post();

        $headerArr['resolucion'] = $fields['fields'][0]['value'];
        $headerArr['fechalic'] = $fields['fields'][1]['value'];
        $headerArr['cmax'] = $fields['fields'][2]['value'];
        $headerArr['maxeeff'] = $fields['fields'][3]['value'];
        $headerArr['obs'] = $fields['fields'][4]['value'];
        $headerArr['id_mongo'] = $fields['id_mongo'];
        $headerArr['borrado'] = 0;
        $headerArr['editable'] = true;

        /*$headerArr['cmax'] = $fields['fields'][0]['value'];
        $headerArr['maxeeff'] = $fields['fields'][1]['value'];
        $headerArr['obs'] = $fields['fields'][2]['value'];
        $headerArr['id_mongo'] = $fields['id_mongo'];
        $headerArr['borrado'] = 0;
        $headerArr['editable'] = true;*/
        $result = $this->model_bonita_licitaciones->guardar_licitaciones_editar($headerArr);
        echo $result;
    }

    function bonita_licitaciones_licitaciones_borrar(){
        $headerArr = array();
        $fields = $this->input->post();
        $headerArr['id_mongo'] = $fields['id_mongo'];
        $result = $headerArr['id_mongo'];
        $this->model_bonita_licitaciones->borrar_licitaciones($headerArr);
        echo $result;
    }

/**************************************CARGA LICITACIONES**************************************/
    function bonita_licitaciones_carga_datos(){
        //Carga las licitaciones abiertas (no se pueden editar)
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/bonita_licitaciones_carga.json');
    }

    function bonita_mostrar_licitaciones(){
        //Muestra las licitaciones abiertas (no se pueden editar)
        $entidades = array();
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $licitaciones = $this->model_bonita_licitaciones->listar_licitaciones_no_editables();
        foreach($licitaciones as $lic){
            $lista =  $lista.
            '<tr>
                <td>'.$lic['resolucion'].'</td>
                <td>'.sprintf("%02d", $lic['fechalic']['mday']).'/'.sprintf("%02d", $lic['fechalic']['mon']).'/'.$lic['fechalic']['year'].'</td>
                <td align="center">'.number_format($lic['cmax'], 0, ",", ".").'</td>
                <td align="center">'.number_format($lic['maxeeff'], 0, ",", ".").'</td>
                <td><a href="'.$this->module_url.'bonita_licitaciones/bonita_licitaciones_carga_entidad_licitacion?id='.$lic['_id'].'" name="cargar">Continuar carga de la licitación</a></td>
            </tr>';
        }
        $customData['lista'] = $lista;
        return $this->parser->parse('bonita/bonita_cargar_licitaciones_view',$customData,true,true);
    }
    
    function bonita_licitaciones_carga_entidad_licitacion(){
        //Muestra la licitacion seleccionada para cargar las entidades y los montos
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/bonita_licitaciones_carga_entidades.json');
    }

    function bonita_mostrar_licitacion(){
        //Muestra la licitacion seleccionada para cargar las entidades y los montos
        $id_mongo = $_GET['id'];
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $datos_licitacion = $this->model_bonita_licitaciones->get_datos_licitacion($id_mongo);
        if(count($datos_licitacion)!=1){
            return "<p>Ha ocurrido un error y no se ha encontrado la licitación pedida. Por favor confirme que la licitacion siga abierta bajo \"ABM licitaciones\".</p>";
        }
        
        //entidades
        $entidades=$this->model_bonita_licitaciones->get_entidades_disponibles($id_mongo);
        foreach($entidades as $entidad){
            $lista_entidades=$lista_entidades.'<option value='.$entidad["_id"].'>'.$entidad["rsocial"].'</option>';
        }
        $datos_entidades = $this->model_bonita_licitaciones->get_datos_cargados($id_mongo);
        $customData['entidades'] = $lista_entidades;

        //calculos
        $maxeeff=$datos_licitacion[0]['maxeeff'];
        $cmax=$datos_licitacion[0]['cmax'];
        $total_ofrecido=$this->calcular_total_ofrecido($datos_entidades);
        
        $asignaciones=array();
        $asignacion_primaria=$this->calcular_asignacion_primaria($datos_entidades, $maxeeff, $cmax, $total_ofrecido);
        
        $asignacion_generica=$asignacion_primaria['asignacion'];
        $total_asignacion=array_sum($asignacion_generica);

        while(round($total_asignacion)-$cmax!=0){
            $asignacion_generica=$this->calcular_asignacion_generica($cmax, $maxeeff, $total_asignacion, $datos_entidades, $asignacion_generica);
            if(array_sum($asignacion_generica)==$total_asignacion){break;}
            $asignaciones[]=$asignacion_generica;
            $total_asignacion=array_sum($asignacion_generica);
        }
        
        //datos_licitacion
        $datos = '<td>'.$datos_licitacion[0]['resolucion'].'</td><td>'.sprintf("%02d", $datos_licitacion[0]['fechalic']['mday']).'/'.sprintf("%02d", $datos_licitacion[0]['fechalic']['mon']).'/'.sprintf("%02d", $datos_licitacion[0]['fechalic']['year']).'</td><td>'.number_format($datos_licitacion[0]['cmax'], 0, ",", ".").'</td><td>'.number_format($datos_licitacion[0]['maxeeff'], 0, ",", ".").'</td><td>'.number_format($total_ofrecido, 0, ",", ".").'</td><td>'.number_format($total_asignacion, 0, ",", ".").'</td>';
        $customData['datos_licitacion'] = $datos;

        $datos_cargados = '<table id="tabla_datos" class="table" data-id='.$id_mongo.'>
            <tr>
                <td>ENTIDAD:</td>
                <td>MONTO:</label></td>
                <td>% SOBRE OFERTA TOTAL:</td>';
                $nombre_asignaciones=$this->get_nombres_asignacion();
                foreach(range(0, sizeof($asignaciones)) as $number){
                    $datos_cargados = $datos_cargados.
                        '<td>'.$nombre_asignaciones[$number].'</td>';
                }
                $datos_cargados = $datos_cargados.
                '<td>ACCIONES:</td>
            </tr>';
        
        $i=0;
        foreach($datos_entidades as $entidad){
            //mostrar calculos
            $datos_cargados =  $datos_cargados.
            '<tr>
                <td align="left">'.$entidad['rsocial'].'</td>
                <td align="left">'.number_format($entidad['monto'], 0, ",", ".").'</td>
                <td align="left">'.number_format($asignacion_primaria['porcentaje'][$i]*100, 2).'</td>
                <td align="left">'.number_format($asignacion_primaria['asignacion'][$i], 0, ",", ".").'</td>';
                
                foreach($asignaciones as $asignacion){
                    $datos_cargados =  $datos_cargados.
                    '<td align="left">'.number_format($asignacion[$i], 0, ",", ".").'</td>';
                }
                
                $datos_cargados =  $datos_cargados.
                '<td><a href="" data-id_licitacion="'.$id_mongo.'" data-id_entidad="'.$entidad['id_entidad'].'" data-cmd="borrar" name="borrar">BORRAR</a></td>
            </tr>';
            $i=$i+1;
        }
        $datos_cargados =  $datos_cargados.'</table>';
        
        $customData['datos_cargados'] = $datos_cargados;
        return $this->parser->parse('bonita/bonita_cargar_licitacion_entidades_view',$customData,true,true);
    }
    
    function calcular_total_ofrecido($datos_entidades){
        $total_ofrecido=0;
        foreach($datos_entidades as $entidad){
            $total_ofrecido=$total_ofrecido+$entidad['monto'];
        }
        return $total_ofrecido;
    }
    
    function calcular_asignacion_primaria($datos_entidades, $maxeeff, $cmax, $total_ofrecido){
        $asignacion_primaria=array();
        $i=0;
        foreach($datos_entidades as $entidad){
            $monto_ofrecido=$entidad['monto'];
            $porc_oferta_total=$entidad['monto']/$total_ofrecido;
            $asignacion_primaria['porcentaje'][$i]=$porc_oferta_total;
            if($porc_oferta_total*$cmax>=$maxeeff){
                $asignacion_primaria['asignacion'][$i]=round($maxeeff);
            }else if($porc_oferta_total*$cmax>$monto_ofrecido){
                $asignacion_primaria['asignacion'][$i]=round($monto_ofrecido);
            }else{
                $asignacion_primaria['asignacion'][$i]=round($porc_oferta_total*$cmax);
            }
            $i+=1;
        }
        return $asignacion_primaria;
    }
    
    function calcular_asignacion_generica($cmax, $maxeeff, $total_asignacion_anterior, $datos_entidades, $asignacion_primaria){
        
        //calculos generales
        $faltante_asignacion=$cmax-$total_asignacion_anterior;
        
        if($faltante_asignacion==0){return;}
        
        //calculos particulares por entidad
        $total_ofertado=0;
        
        $i=0;
        foreach($datos_entidades as $entidad){
            if($asignacion_primaria[$i]==$maxeeff){
                $datos_entidades[$i]['ofertado']=0;
            }else{
                $datos_entidades[$i]['ofertado']=$entidad['monto'];
                $total_ofertado+=$entidad['monto'];
            }
            $i+=1;
        }
        
        $asignacion_generica=array();
        $i=0;
        
        foreach($datos_entidades as $entidad){
            if($asignacion_primaria[$i]==$maxeeff){
                $asignacion_generica[]=round($asignacion_primaria[$i]);
            }else{
                $porc_faltante=$datos_entidades[$i]['ofertado']/$total_ofertado;
                $pot_asignacion=$porc_faltante*$faltante_asignacion;
                
                if($asignacion_primaria[$i]+$pot_asignacion>$entidad['monto']){
                    if($entidad['monto']<$maxeeff){
                        $asignacion_generica[]=round($entidad['monto']);
                    }else{
                        $asignacion_generica[]=round($maxeeff);
                    }
                }else{
                    if($asignacion_primaria[$i]+$pot_asignacion>$maxeeff){
                        $asignacion_generica[]=round($maxeeff);
                    }else{
                        $asignacion_generica[]=round($asignacion_primaria[$i]+$pot_asignacion);
                    }
                }
            }
            $i+=1;
        }
        return $asignacion_generica;
    }
    
    function get_nombres_asignacion(){
        $numeros=explode(" ","PRIMARIA SECUNDARIA TERCIARIA CUATERNARIA QUINARIA");
        $nombres=array();
        foreach($numeros as $numero){
            $nombres[]="ASIGNACIÓN ".$numero;
        }
        return $nombres;
    }
    
    function bonita_licitaciones_nueva_carga(){
        //carga la entidad y el monto como parte de la licitacion
        $fields = $this->input->post();
        $headerArr['id_mongo'] = $fields['id_mongo'];
        $headerArr['id_entidad']=$fields['entidad'];
        $headerArr['monto']=$fields['monto']*1000000;
        $headerArr['editable'] = false;
        $result = $this->model_bonita_licitaciones->guardar_carga($headerArr);
        echo $result;
    }
        //borrar la carga de una entidad en una licitacion particular

    function bonita_licitaciones_carga_borrar(){
        $fields = $this->input->post();
        $headerArr = array();
        $headerArr['id_licitacion'] = $fields['id_licitacion'];
        $headerArr['id_entidad'] = $fields['id_entidad'];
        $result=$this->model_bonita_licitaciones->borrar_carga($headerArr);
        echo $result;
    }
    
    function bonita_licitaciones_get_cmax(){
        //devuelve el cupo maximo
        $fields = $this->input->post();
        $result = $this->model_bonita_licitaciones->get_cmax($fields['id_licitacion']);
        echo $result;
    }

/**************************************CERRAR LICITACION**************************************/
    function bonita_licitaciones_cerrar_licitacion(){
        //borrar la carga de una entidad en una licitacion particular
        $fields = $this->input->post();
        
        $datos_licitacion = $this->model_bonita_licitaciones->get_datos_licitacion($fields['id_licitacion']);

        //entidades
        $datos_entidades = $this->model_bonita_licitaciones->get_datos_cargados($fields['id_licitacion']);

        //calculos
        $maxeeff=$datos_licitacion[0]['maxeeff'];
        $cmax=$datos_licitacion[0]['cmax'];
        $total_ofrecido=$this->calcular_total_ofrecido($datos_entidades);
        
        $asignacion_primaria=$this->calcular_asignacion_primaria($datos_entidades, $maxeeff, $cmax, $total_ofrecido);
        
        $asignacion_generica=$asignacion_primaria['asignacion'];
        $total_asignacion=array_sum($asignacion_generica);

        while(round($total_asignacion)-$cmax!=0){
            $asignacion_generica=$this->calcular_asignacion_generica($cmax, $maxeeff, $total_asignacion, $datos_entidades, $asignacion_generica);
            if(array_sum($asignacion_generica)==$total_asignacion){break;}
            $total_asignacion=array_sum($asignacion_generica);
        }
        
        $this->model_bonita_licitaciones->persistir_licitacion_y_cerrar($fields['id_licitacion'], $asignacion_generica);
        
        //$result=$this->model_bonita_licitaciones->cerrar_licitacion($fields);
        echo $result;
    }

/**************************************REPORTES LICITACIONES**************************************/
    function bonita_licitaciones_reportes_licitaciones(){
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/bonita_licitaciones_reportes_licitaciones.json');
    }

    function bonita_mostrar_licitaciones_cerradas(){
        $entidades = array();
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $licitaciones = $this->model_bonita_licitaciones->listar_licitaciones_cerradas();
        foreach($licitaciones as $lic){
            $lista =  $lista.
            '<tr>
                <td>'.$lic['resolucion'].'</td>
                <td>'.sprintf("%02d", $lic['fechalic']['mday']).'/'.sprintf("%02d", $lic['fechalic']['mon']).'/'.$lic['fechalic']['year'].'</td>
                <td>'.sprintf("%02d", $lic['fecha_cierre']['mday']).'/'.sprintf("%02d", $lic['fecha_cierre']['mon']).'/'.$lic['fecha_cierre']['year'].'</td>
                <td align="center">'.number_format($lic['cmax'], 0, ",", ".").'</td>
                <td align="center">'.number_format($lic['maxeeff'], 0, ",", ".").'</td>
                <td><a href="'.$this->module_url.'bonita_licitaciones/bonita_licitaciones_url_anexo1?id='.$lic['_id'].'" name="cargar">Anexo I</a></td>
                <td><a href="'.$this->module_url.'bonita_licitaciones/bonita_licitaciones_url_anexo2?id='.$lic['_id'].'" name="cargar">Anexo II</a></td>
            </tr>';
        }
        $customData['lista'] = $lista;
       return $this->parser->parse('bonita/bonita_reportes_licitaciones_cerradas',$customData,true,true);
    }
    
    function bonita_licitaciones_url_anexo1(){
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/bonita_licitaciones_anexoI.json');
    }
    
    function bonita_licitaciones_url_anexo2(){
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/bonita_licitaciones_anexoII.json');
    }

    function bonita_licitaciones_anexo1(){
        $id_mongo=$_GET['id'];
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $licitacion = $this->model_bonita_licitaciones->get_datos_licitacion_cerrada($id_mongo);
        if(is_null($licitacion)){
            return "<p>Ha ocurrido un error y no se ha encontrado la licitación pedida. Por favor confirme que la licitacion exista bajo \"Licitaciones Cerradas\".</p>";
        }
        
        $lista =  $lista.
        '<tr>
            <td>'.$licitacion['resolucion'].'</td>
            <td>'.sprintf("%02d", $licitacion['fechalic']['mday']).'/'.sprintf("%02d", $licitacion['fechalic']['mon']).'/'.$licitacion['fechalic']['year'].'</td>
            <td>'.sprintf("%02d", $licitacion['fecha_cierre']['mday']).'/'.sprintf("%02d", $licitacion['fecha_cierre']['mon']).'/'.$licitacion['fecha_cierre']['year'].'</td>
            <td align="center">'.number_format($licitacion['cmax'], 0, ",", ".").'</td>
            <td align="center">'.number_format($licitacion['maxeeff'], 0, ",", ".").'</td>
        </tr>';
        
        $datos_entidades = $this->model_bonita_licitaciones->get_datos_cargados($id_mongo);
        $total_ofrecido=$this->calcular_total_ofrecido($datos_entidades);
        $x=1;
        $lista_ofertas="";
        foreach($licitacion['ofertas'] as $oferta){
            if($oferta['borrado']==false){
                $lista_ofertas=$lista_ofertas.
                '<tr>
                    <td>'.$x.'</td>
                    <td>'.$this->model_bonita_licitaciones->get_rsocial($oferta['id_entidad']).'</td>
                    <td>'.number_format($oferta['monto']/1000000, 2, ",", ".").'</td>
                    <td>'.number_format($oferta['monto'], 0, ",", ".").'</td>
                    <td>'.number_format($oferta['monto']*100/$total_ofrecido, 2, ",", ".").'</td>
                </tr>';
                $x+=1;
            }
        }

        $customData['datos_licitacion'] = $lista;
        $customData['lista_ofertas'] = $lista_ofertas;
       return $this->parser->parse('bonita/bonita_reportes_licitaciones_anexoI',$customData,true,true);
    }

    function get_tabla_anexoI($id_mongo){
        $datos_entidades = $this->model_bonita_licitaciones->get_datos_cargados($id_mongo);
        $total_ofrecido=$this->calcular_total_ofrecido($datos_entidades);
        
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $licitacion = $this->model_bonita_licitaciones->get_datos_licitacion_cerrada($id_mongo);
        
        $lista =  $lista.
        '<tr>
            <td>'.utf8_decode($licitacion['resolucion']).'</td>
            <td>'.sprintf("%02d", $licitacion['fechalic']['mday']).'/'.sprintf("%02d", $licitacion['fechalic']['mon']).'/'.$licitacion['fechalic']['year'].'</td>
            <td>'.sprintf("%02d", $licitacion['fecha_cierre']['mday']).'/'.sprintf("%02d", $licitacion['fecha_cierre']['mon']).'/'.$licitacion['fecha_cierre']['year'].'</td>
            <td align="center">'.number_format($licitacion['cmax'], 0, ",", ".").'</td>
            <td align="center">'.number_format($licitacion['maxeeff'], 0, ",", ".").'</td>
        </tr>';
 
        $x=1;
        $lista_ofertas="";
        foreach($licitacion['ofertas'] as $oferta){
            if($oferta['borrado']==false){
                $lista_ofertas=$lista_ofertas.
                '<tr>
                    <td>'.$x.'</td>
                    <td>'.utf8_decode($this->model_bonita_licitaciones->get_rsocial($oferta['id_entidad'])).'</td>
                    <td>'.number_format($oferta['monto']/1000000, 2, ",", ".").'</td>
                    <td>'.number_format($oferta['monto'], 0, ",", ".").'</td>
                    <td>'.number_format($oferta['monto']*100/$total_ofrecido, 2, ",", ".").'</td>
                </tr>';
                $x+=1;
            }
        }
        $customData['datos_licitacion'] = $lista;
        $customData['lista_ofertas'] = $lista_ofertas;
        return $customData;
    }

    function bonita_licitaciones_anexo2(){
        $id_mongo=$_GET['id'];
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $licitacion = $this->model_bonita_licitaciones->get_datos_licitacion_cerrada($id_mongo);
        if(is_null($licitacion)){
            return "<p>Ha ocurrido un error y no se ha encontrado la licitación pedida. Por favor confirme que la licitacion exista bajo \"Licitaciones Cerradas\".</p>";
        }
        
        $lista =  $lista.
        '<tr>
            <td>'.$licitacion['resolucion'].'</td>
            <td>'.sprintf("%02d", $licitacion['fechalic']['mday']).'/'.sprintf("%02d", $licitacion['fechalic']['mon']).'/'.$licitacion['fechalic']['year'].'</td>
            <td>'.sprintf("%02d", $licitacion['fecha_cierre']['mday']).'/'.sprintf("%02d", $licitacion['fecha_cierre']['mon']).'/'.$licitacion['fecha_cierre']['year'].'</td>
            <td align="center">'.number_format($licitacion['cmax'], 0, ",", ".").'</td>
            <td align="center">'.number_format($licitacion['maxeeff'], 0, ",", ".").'</td>
        </tr>';
        
        $datos_entidades = $this->model_bonita_licitaciones->get_datos_cargados($id_mongo);
        $total_ofrecido=$this->calcular_total_ofrecido($datos_entidades);
        $x=1;
        $lista_ofertas="";
        foreach($licitacion['ofertas'] as $oferta){
            if($oferta['borrado']==false){
                $lista_ofertas=$lista_ofertas.
                '<tr>
                    <td>'.$x.'</td>
                    <td>'.$this->model_bonita_licitaciones->get_rsocial($oferta['id_entidad']).'</td>
                    <td>'.number_format($oferta['asignacion'], 0, ",", ".").'</td>
                    <td>'.number_format($oferta['asignacion']*100/$licitacion['cmax'], 2, ",", ".").'</td>
                </tr>';
                $x+=1;
            }
        }

        $customData['datos_licitacion'] = $lista;
        $customData['lista_ofertas'] = $lista_ofertas;
       return $this->parser->parse('bonita/bonita_reportes_licitaciones_anexoII',$customData,true,true);
    }

    function get_tabla_anexoII($id_mongo){
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $licitacion = $this->model_bonita_licitaciones->get_datos_licitacion_cerrada($id_mongo);
        if(is_null($licitacion)){
            return "<p>Ha ocurrido un error y no se ha encontrado la licitación pedida. Por favor confirme que la licitacion exista bajo \"Licitaciones Cerradas\".</p>";
        }
        
        $lista =  $lista.
        '<tr>
            <td>'.utf8_decode($licitacion['resolucion']).'</td>
            <td>'.sprintf("%02d", $licitacion['fechalic']['mday']).'/'.sprintf("%02d", $licitacion['fechalic']['mon']).'/'.$licitacion['fechalic']['year'].'</td>
            <td>'.sprintf("%02d", $licitacion['fecha_cierre']['mday']).'/'.sprintf("%02d", $licitacion['fecha_cierre']['mon']).'/'.$licitacion['fecha_cierre']['year'].'</td>
            <td align="center">'.number_format($licitacion['cmax'], 0, ",", ".").'</td>
            <td align="center">'.number_format($licitacion['maxeeff'], 0, ",", ".").'</td>
        </tr>';
        
        $datos_entidades = $this->model_bonita_licitaciones->get_datos_cargados($id_mongo);
        $total_ofrecido=$this->calcular_total_ofrecido($datos_entidades);
        $x=1;
        $lista_ofertas="";
        foreach($licitacion['ofertas'] as $oferta){
            if($oferta['borrado']==false){
                $lista_ofertas=$lista_ofertas.
                '<tr>
                    <td>'.$x.'</td>
                    <td>'.utf8_decode($this->model_bonita_licitaciones->get_rsocial($oferta['id_entidad'])).'</td>
                    <td>'.number_format($oferta['asignacion'], 0, ",", ".").'</td>
                    <td>'.number_format($oferta['asignacion']*100/$licitacion['cmax'], 2, ",", ".").'</td>
                </tr>';
                $x+=1;
            }
        }

        $customData['datos_licitacion'] = $lista;
        $customData['lista_ofertas'] = $lista_ofertas;
        
        return $customData;
    }
    
    function descarga_anexoI(){
        $id_mongo = $_GET['id'];
        $customData=$this->get_tabla_anexoI($id_mongo);
        $customData['base_url'] = $this->base_url;
        $customData['new_filename'] = $this->get_file_name("AnexoI");
        echo $this->parser->parse('bonita/bonita_reportes_licitaciones_anexoI_export',$customData,true,true);
    }

    function descarga_anexoII(){
        $id_mongo = $_GET['id'];
        $customData=$this->get_tabla_anexoII($id_mongo);
        $customData['base_url'] = $this->base_url;
        $customData['new_filename'] = $this->get_file_name("AnexoI");
        echo $this->parser->parse('bonita/bonita_reportes_licitaciones_anexoII_export',$customData,true,true);
    }
    
    function get_file_name($nombre){
        return $nombre.'-'.date("y-m-d").rand(1000, 5000) .'.xls';
    }
}