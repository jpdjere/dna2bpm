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
        $lista = '<table id="table_lic" class="table">';
        foreach($licitaciones as $lic){
            $lista =  $lista.
            '<tr>
                <td>
                    CUPO MÁXIMO:
                </td>
                <td align="center">
                    <label>'.number_format($lic['cmax'], 0, ",", ".").'&nbsp;&nbsp;&nbsp;</label>
                </td>
                <td>
                    MÁXIMO POR EEFF:
                </td>
                <td align="center">
                    '.number_format($lic['maxeeff'], 0, ",", ".").'&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                    <a href="" data-id="'.$lic['_id'].'" data-cmd="editar" data-cmax="'.$lic['cmax'].'" data-maxeeff="'.$lic['maxeeff'].'" data-obs="'.$lic['obs'].'" title=”EDITAR” name="editar">EDITAR</a>
                </td>
                <td>
                    <a href="" data-id="'.$lic['_id'].'" data-cmd="borrar" name="borrar">BORRAR</a>
                </td>
            </tr>';
        }
        $lista =  $lista.'</table>';
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
        $headerArr['cmax'] = $fields['fields'][0]['value'];
        $headerArr['maxeeff'] = $fields['fields'][1]['value'];
        $headerArr['obs'] = $fields['fields'][2]['value'];
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
        $headerArr['cmax'] = $fields['fields'][0]['value'];
        $headerArr['maxeeff'] = $fields['fields'][1]['value'];
        $headerArr['obs'] = $fields['fields'][2]['value'];
        $headerArr['id_mongo'] = $fields['id_mongo'];
        $headerArr['borrado'] = 0;
        $headerArr['editable'] = true;
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
        $lista = '<table id="table_lic" class="table">';
        foreach($licitaciones as $lic){
            $lista =  $lista.
            '<tr>
                <td>
                    CUPO MÁXIMO:
                </td>
                <td align="center">
                    <label>'.number_format($lic['cmax'], 0, ",", ".").'&nbsp;&nbsp;&nbsp;</label>
                </td>
                <td>
                    MÁXIMO POR EEFF:
                </td>
                <td align="center">
                    '.number_format($lic['maxeeff'], 0, ",", ".").'&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                    <a href="'.$this->module_url.'bonita_licitaciones/bonita_licitaciones_carga_entidad_licitacion?id='.$lic['_id'].'" name="cargar">Continuar carga de la licitación</a>
                </td>
            </tr>';
        }
        $lista =  $lista.'</table>';
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
        //datos_licitacion
        $datos = '<table id="table_lic" class="table" data-id='.$id_mongo.'>'.
        '<tr><td>CUPO MÁXIMO:</td><td>'.number_format($datos_licitacion[0]['cmax'], 0, ",", ".").'</td><td>MÁXIMO POR EEFF:</td><td>'.number_format($datos_licitacion[0]['maxeeff'], 0, ",", ".").'</td></tr>'.'</table>';
        
        //entidades
        $entidades=$this->model_bonita_licitaciones->get_entidades_disponibles($id_mongo);
        foreach($entidades as $entidad){
            $lista_entidades=$lista_entidades.'<option value='.$entidad["_id"].'>'.$entidad["rsocial"].'</option>';
        }
        $datos_entidades = $this->model_bonita_licitaciones->get_datos_cargados($id_mongo);
        
        //calculos
        $maxeeff=$datos_licitacion[0]['maxeeff'];
        $cmax=$datos_licitacion[0]['cmax'];
        $total_ofrecido=$this->calcular_total_ofrecido($datos_entidades);
        
        $asignaciones=array();
        $asignacion_primaria=$this->calcular_asignacion_primaria($datos_entidades, $maxeeff, $cmax, $total_ofrecido);
        
        $asignacion_generica=$asignacion_primaria['asignacion'];
        $total_asignacion=array_sum($asignacion_generica);
        
        while($total_asignacion-$cmax!=0){
            $asignacion_generica=$this->calcular_asignacion_generica($cmax, $maxeeff, $total_asignacion, $datos_entidades, $asignacion_generica);
            if(array_sum($asignacion_generica)==$total_asignacion){break;}
            $asignaciones[]=$asignacion_generica;
            $total_asignacion=array_sum($asignacion_generica);
        }
        //$asignacion_generica=$this->calcular_asignacion_generica($cmax, $maxeeff, $total_asignacion_anterior, $datos_entidades, $asignacion_generica);

        $datos_cargados = '<table id="tabla_datos" class="table" data-id='.$id_mongo.'>
            <tr>
                <td><label>ENTIDAD:</label></td>
                <td><label>MONTO:</label>
                </td><td><label>% SOBRE OFERTA TOTAL:</label></td>';
                $nombre_asignaciones=$this->get_nombres_asignacion();
                foreach(range(0, sizeof($asignaciones)) as $number){
                    $datos_cargados = $datos_cargados.
                        '<td><label>'.$nombre_asignaciones[$number].'</label></td>';
                }
                $datos_cargados = $datos_cargados.
                '<td><label>ACCIONES:</label></td>
            </tr>'.$nombre_asignaciones[1];
        
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
        
        $customData['datos_licitacion'] = $datos;
        $customData['entidades'] = $lista_entidades;
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
                $asignacion_primaria['asignacion'][$i]=$maxeeff;
            }else if($porc_oferta_total*$cmax>$monto_ofrecido){
                $asignacion_primaria['asignacion'][$i]=$monto_ofrecido;
            }else{
                $asignacion_primaria['asignacion'][$i]=$porc_oferta_total*$cmax;
            }
            $i+=1;
        }
        return $asignacion_primaria;
    }
    
    function calcular_asignacion_generica($cmax, $maxeeff, $total_asignacion_anterior, $datos_entidades, $asignacion_primaria){
        
        //calculos generales
        $faltante_asignacion=$cmax-$total_asignacion_anterior;
        
        if($faltante_asignacion==0){return 'lala';}
        
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
            if($entidad['monto']==$maxeeff){
                $asignacion_generica[]=$entidad['monto'];
            }else{
                $porc_faltante=$datos_entidades[$i]['ofertado']/$total_ofertado;
                $pot_asignacion=$porc_faltante*$faltante_asignacion;
                
                if($asignacion_primaria[$i]+$pot_asignacion>$entidad['monto']){
                    if($entidad['monto']<$maxeeff){
                        $asignacion_generica[]=$entidad['monto'];
                    }else{
                        $asignacion_generica[]=$maxeeff;
                    }
                }else{
                    if($asignacion_primaria[$i]+$pot_asignacion>$maxeeff){
                        $asignacion_generica[]=$maxeeff;
                    }else{
                        $asignacion_generica[]=$asignacion_primaria[$i]+$pot_asignacion;
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

    function bonita_licitaciones_carga_borrar(){
        //borrar la carga de una entidad en una licitacion particular
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
}