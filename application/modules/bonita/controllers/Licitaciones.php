<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * inventory
 * 
 * Controller para trabajar las licitaciones
 * 
 * @author Martin González 
 * @date    Apr 20, 2015
 */

class licitaciones extends MX_Controller {


    function __construct() {
        parent::__construct();
        $this->load->model('user/user');
        $this->load->model('user/group');
        $this->load->model('model_bonita_licitaciones');
        $this->user->authorize('modules/bonita');
        $this->load->library('parser');
        $this->load->helper('licitaciones/licitaciones');
        $this->base_url = base_url();
        $this->module_url = base_url() . 'bonita/';
        $this->idu = (float) $this->session->userdata('iduser');
        $this->asignacion_actual=0;
    }
    
    /**
     * Redirecciona a Menu Licitaciones
     */
    function Index(){
        $this->user->authorize();
        redirect($this->base_url.'bonita/menu_licitaciones');
    }
    
/**************************************ENTIDADES**************************************/
    /**
     * Muestra el menu con las Entidades Cargadas
     */
    function abm_entidades(){
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/licitaciones/abm_entidades.json');
    }
    
    /**
     * Muestra las Entidades Cargadas
     */
    function mostrar_entidades(){
        $customData['base_url'] = $this->base_url;
        $entidades = $this->model_bonita_licitaciones->listar_entidades();
        $lista = '<table id="table_ent" class="table">';
        foreach($entidades as $ent){
            $lista =  $lista.'<tr><td>RAZÓN SOCIAL:  </td><td><a href="" data-id="'.$ent['_id'].'" data-cmd="editar" data-rsocial="'.$ent['rsocial'].'" data-ent_cuit="'.$ent['ent_cuit'].'" data-obs="'.$ent['obs'].'" title=”EDITAR” name="editar">'.$ent['rsocial'].'</a></td><td> -  CUIT: '.$ent['ent_cuit'].'  -</td><td><a href=""   data-id="'.$ent['_id'].'" data-cmd="borrar" name="borrar"> BORRAR</a></td></tr>';
        }
        $lista =  $lista.'</table>';
        $customData['lista'] = $lista;
        return $this->parser->parse('bonita/views/licitaciones/abm_entidades',$customData,true,true);
    }
    
    /**
     * Muestra el formulario para cargar una nueva entidad
     */
    function form_nueva_entidad(){
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $customData['titulo'] = "Cargar nueva entidad";
        $return['tabla'] = $this->parser->parse('bonita/views/licitaciones/form_nueva_entidad',$customData,true,true);
        echo json_encode($return);
        return $return;
    }
    
    /**
     * Muestra el formulario con los datos de una entidad
     */
    function form_editar_entidad(){
        $customData = $this->input->post();
        $customData['titulo'] = "Editar entidad";
        $customData['base_url'] = $this->base_url;
        $return['tabla'] = $this->parser->parse('bonita/views/licitaciones/form_nueva_entidad',$customData,true,true);
        echo json_encode($return);
        return $return;
    }

    /**
     * Carga una nueva entidad en la base
     */
    function cargar_nueva_entidad(){
        $fields = $this->input->post();
        $headerArr['rsocial'] = $fields['fields'][0]['value'];
        $headerArr['ent_cuit'] = $fields['fields'][1]['value'];
        $headerArr['obs'] = $fields['fields'][2]['value'];
        $headerArr['borrado'] = 0;
        $result = $this->model_bonita_licitaciones->guardar_entidades($headerArr);
        echo $result;
    }
    
    /**
     * Actualiza los datos de una entidad en la base
     */
    function cargar_editar_entidad(){
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
    
    /**
     * Borra una entidad
     */
    function borrar_entidad(){
        $fields = $this->input->post();
        $headerArr = array();
        $headerArr['id_mongo'] = $fields['id_mongo'];
        $result = $headerArr['id_mongo'];
        $this->model_bonita_licitaciones->borrar_entidades($headerArr);
        echo $result;
    }
    
/**************************************LICITACIONES**************************************/
    /**
     * Carga el menu con las Licitaciones Editables
     */
    function abm_licitaciones(){
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/licitaciones/abm_licitaciones.json');
    }

    /**
     * Muestra las Licitaciones Editables
     */
    function mostrar_licitaciones_editables(){
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
        return $this->parser->parse('bonita/views/licitaciones/abm_licitaciones',$customData,true,true);
    }
    
    /**
     * Muestra el formulario para cargar una nueva licitacion
     */
    function form_nueva_licitacion(){
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $customData['titulo'] = "Cargar nueva licitación";
        $return['tabla'] = $this->parser->parse('bonita/views/licitaciones/form_nueva_licitacion',$customData,true,true);
        echo json_encode($return);
        return $return;   
    }
    
    /**
     * Muestra el formulario con los datos de una licitacion
     */
    function form_editar_licitacion(){
        $customData = $this->input->post();
        $customData['titulo'] = "Editar licitación";
        $customData['base_url'] = $this->base_url;
        $return['tabla'] = $this->parser->parse('bonita/views/licitaciones/form_nueva_licitacion',$customData,true,true);
        echo json_encode($return);
        return $return;
    }
    
    /**
     * Carga una nueva licitacion en la base
     */
    function cargar_nueva_licitacion(){
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
    
    /**
     * Actualiza una licitacion en la base
     */
    function cargar_editar_licitacion(){
        $fields = $this->input->post();
        $headerArr['resolucion'] = $fields['fields'][0]['value'];
        $headerArr['fechalic'] = $fields['fields'][1]['value'];
        $headerArr['cmax'] = $fields['fields'][2]['value'];
        $headerArr['maxeeff'] = $fields['fields'][3]['value'];
        $headerArr['obs'] = $fields['fields'][4]['value'];
        $headerArr['id_mongo'] = $fields['id_mongo'];
        $headerArr['borrado'] = 0;
        $headerArr['editable'] = true;
        $result = $this->model_bonita_licitaciones->guardar_licitaciones_editar($headerArr);
        echo $result;
    }
    
    /**
     * Borra una licitacion
     */
    function borrar_licitacion(){
        $headerArr = array();
        $fields = $this->input->post();
        $headerArr['id_mongo'] = $fields['id_mongo'];
        $result = $headerArr['id_mongo'];
        $this->model_bonita_licitaciones->borrar_licitaciones($headerArr);
        echo $result;
    }

/**************************************CARGAR LICITACIONES**************************************/
    /**
     * Muestra el menu de las licitaciones abiertas
     */
    function menu_cargar_montos(){
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/licitaciones/menu_cargar_montos.json');
    }
    
    /**
     * Muestra las licitaciones abiertas
     */
    function mostrar_licitaciones_abiertas(){
        $customData['base_url'] = $this->base_url;
        $licitaciones = $this->model_bonita_licitaciones->listar_licitaciones_no_editables();
        foreach($licitaciones as $lic){
            $lista =  $lista.
            '<tr>
                <td>'.$lic['resolucion'].'</td>
                <td>'.sprintf("%02d", $lic['fechalic']['mday']).'/'.sprintf("%02d", $lic['fechalic']['mon']).'/'.$lic['fechalic']['year'].'</td>
                <td align="center">'.number_format($lic['cmax'], 0, ",", ".").'</td>
                <td align="center">'.number_format($lic['maxeeff'], 0, ",", ".").'</td>
                <td><a href="'.$this->module_url.'licitaciones/cargar_entidades_y_montos?id='.$lic['_id'].'" name="cargar">Continuar carga de la licitación</a></td>
            </tr>';
        }
        $customData['lista'] = $lista;
        return $this->parser->parse('bonita/views/licitaciones/licitaciones_abiertas',$customData,true,true);
    }
    
    function cargar_entidades_y_montos(){
        //Muestra la licitacion seleccionada para cargar las entidades y los montos
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/licitaciones/cargar_entidades_y_montos.json');
    }

    function mostrar_licitacion_para_carga(){
        //Muestra la licitacion seleccionada para cargar las entidades y los montos
        //$this->load->helper('licitaciones/licitaciones');
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
        $total_ofrecido=calcular_total_ofrecido($datos_entidades);
        
        $asignaciones=array();
        $asignacion_primaria=calcular_asignacion_primaria($datos_entidades, $maxeeff, $cmax, $total_ofrecido);
        
        $asignacion_generica=$asignacion_primaria['asignacion'];
        $total_asignacion=array_sum($asignacion_generica);
        
        while(round($total_asignacion)-$cmax!=0){
            $asignacion_generica=calcular_asignacion_generica($cmax, $maxeeff, $total_asignacion, $datos_entidades, $asignacion_generica);
            
            $iguales=false;
            for($x=0;$x<3;$x+=1){
                if(array_sum($asignacion_generica)+$x==$total_asignacion){
                    $iguales=true;
                }
                if(array_sum($asignacion_generica)-$x==$total_asignacion){
                    $iguales=true;
                }
            }
            
            if($iguales){break;}
            $asignaciones[]=$asignacion_generica;
            $total_asignacion=array_sum($asignacion_generica);
        }

        $iguales=false;
        for($x=0;$x<6;$x+=1){
            if($total_asignacion+$x==$cmax){
                $iguales=true;
            }
        }
        
        if(!$iguales){
            $ultima_asgnacion=calcular_ultima_asignacion($cmax, $maxeeff, $total_asignacion, $datos_entidades, $asignacion_generica, $asignaciones);
            $asignaciones[]=$ultima_asgnacion;
            $total_asignacion=array_sum($ultima_asgnacion);
        }
        
        $iguales=false;
        for($x=0;$x<6;$x+=1){
            if($total_asignacion+$x==$cmax){
                $total_asignacion=$cmax;
            }
        }

        //datos_licitacion
        $datos = '<td>'.$datos_licitacion[0]['resolucion'].'</td><td>'.sprintf("%02d", $datos_licitacion[0]['fechalic']['mday']).'/'.sprintf("%02d", $datos_licitacion[0]['fechalic']['mon']).'/'.sprintf("%02d", $datos_licitacion[0]['fechalic']['year']).'</td><td>'.number_format($datos_licitacion[0]['cmax'], 0, ",", ".").'</td><td>'.number_format($datos_licitacion[0]['maxeeff'], 0, ",", ".").'</td><td>'.number_format($total_ofrecido, 0, ",", ".").'</td><td>'.number_format($total_asignacion, 0, ",", ".").'</td>';
        $customData['datos_licitacion'] = $datos;

        $datos_cargados = '<table id="tabla_datos" class="table" data-id='.$id_mongo.'>
            <tr>
                <td>ENTIDAD:</td>
                <td>MONTO:</label></td>
                <td>% SOBRE OFERTA TOTAL:</td>';
                $nombre_asignaciones=get_nombres_asignacion();
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
        return $this->parser->parse('bonita/views/licitaciones/cargar_montos_y_entidades',$customData,true,true);
    }
    
    
    function cargar_nuevo_monto(){
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
    function borrar_monto(){
        $fields = $this->input->post();
        $headerArr = array();
        $headerArr['id_licitacion'] = $fields['id_licitacion'];
        $headerArr['id_entidad'] = $fields['id_entidad'];
        $result=$this->model_bonita_licitaciones->borrar_carga($headerArr);
        echo $result;
    }
    
    function obtener_cmax(){
        //devuelve el cupo maximo
        $fields = $this->input->post();
        $result = $this->model_bonita_licitaciones->get_cmax($fields['id_licitacion']);
        echo $result;
    }

/**************************************CERRAR LICITACION**************************************/
    function cerrar_licitacion(){
        //borrar la carga de una entidad en una licitacion particular
        $fields = $this->input->post();
        
        $datos_licitacion = $this->model_bonita_licitaciones->get_datos_licitacion($fields['id_licitacion']);
        
        //entidades
        $datos_entidades = $this->model_bonita_licitaciones->get_datos_cargados($fields['id_licitacion']);
        
        //calculos
        $maxeeff=$datos_licitacion[0]['maxeeff'];
        $cmax=$datos_licitacion[0]['cmax'];
        $total_ofrecido=calcular_total_ofrecido($datos_entidades);
        
        $asignacion_primaria=calcular_asignacion_primaria($datos_entidades, $maxeeff, $cmax, $total_ofrecido);
        
        $asignacion_generica=$asignacion_primaria['asignacion'];
        $total_asignacion=array_sum($asignacion_generica);
        
        while(round($total_asignacion)-$cmax!=0){
            $asignacion_generica=calcular_asignacion_generica($cmax, $maxeeff, $total_asignacion, $datos_entidades, $asignacion_generica);
            if(array_sum($asignacion_generica)==$total_asignacion){break;}
            $total_asignacion=array_sum($asignacion_generica);
        }
        
        if($total_asignacion!=$cmax){
            $ultima_asgnacion=calcular_ultima_asignacion($cmax, $maxeeff, $total_asignacion, $datos_entidades, $asignacion_generica, $asignaciones);
            $asignacion_generica=$ultima_asgnacion;
            $total_asignacion=array_sum($ultima_asgnacion);
        }
        
        $this->model_bonita_licitaciones->persistir_licitacion_y_cerrar($fields['id_licitacion'], $asignacion_generica);
        echo $result;
    }

/**************************************REPORTES LICITACIONES**************************************/
    /**
     * Muestra el menu de los reportes
     */
    function menu_reportes(){
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/licitaciones/menu_reportes.json');
    }
    
    /**
     * Muestra las licitaciones cerradas
     */
    function mostrar_licitaciones_cerradas(){
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
                <td><a href="'.$this->module_url.'licitaciones/mostrar_anexo1?id='.$lic['_id'].'" name="cargar">Anexo I</a></td>
                <td><a href="'.$this->module_url.'licitaciones/mostrar_anexo2?id='.$lic['_id'].'" name="cargar">Anexo II</a></td>
            </tr>';
        }
        $customData['lista'] = $lista;
       return $this->parser->parse('bonita/views/licitaciones/licitaciones_cerradas',$customData,true,true);
    }
    
    function mostrar_anexo1(){
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/licitaciones/anexo1.json');
    }
    
    function mostrar_anexo2(){
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/licitaciones/anexo2.json');
    }

    function anexo1(){
        $id_mongo=$_GET['id'];
        $content=$this->get_tabla_anexoI($id_mongo);
        $content.="<a class='btn btn-primary btn-xs' target='_blank' method='POST' href='".$this->base_url."bonita/licitaciones/descarga_anexoI?id=$id_mongo'>Exportar</a>";
        echo $content;
    }
    
    function anexo2(){
        $id_mongo=$_GET['id'];
        $content=utf8_encode($this->get_tabla_anexoII($id_mongo));
        $content.="<a class='btn btn-primary btn-xs' target='_blank' method='POST' href='".$this->base_url."bonita/licitaciones/descarga_anexoII?id=$id_mongo'>Exportar</a>";
        echo $content;
    }
    
    function get_tabla_anexoI($id_mongo){
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
        $total_ofrecido=calcular_total_ofrecido($datos_entidades);
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
        return $this->parser->parse('bonita/views/licitaciones/contenido_anexoI',$customData,true,true);
    }

    function get_tabla_anexoII($id_mongo){
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
        $total_ofrecido=calcular_total_ofrecido($datos_entidades);
        $x=1;
        $lista_ofertas="";
        foreach($licitacion['ofertas'] as $oferta){
            if($oferta['borrado']==false){
                $lista_ofertas=$lista_ofertas.
                '<tr>
                    <td>'.$x.'</td>
                    <td>'.utf8_decode($this->model_bonita_licitaciones->get_rsocial($oferta['id_entidad'])).'</td>
                    <td>'.number_format($oferta['asignacion'], 2, ",", ".").'</td>
                    <td>'.number_format($oferta['asignacion']*100/$licitacion['cmax'], 2, ",", ".").'</td>
                </tr>';
                $x+=1;
            }
        }

        $customData['datos_licitacion'] = $lista;
        $customData['lista_ofertas'] = $lista_ofertas;
        
        return $this->parser->parse('bonita/views/licitaciones/contenido_anexoII',$customData,true,true);
    }
    
    function descarga_anexoI(){
        $id_mongo = $_GET['id'];
        $customData['content'] = $this->get_tabla_anexoI($id_mongo);
        $customData['filename'] = get_file_name("AnexoI");
        echo $this->parser->parse('bonita/views/export',$customData,true,true);
    }

    function descarga_anexoII(){
        $id_mongo = $_GET['id'];
        $customData['content'] = $this->get_tabla_anexoII($id_mongo);
        $customData['filename'] = get_file_name("AnexoII");
        echo $this->parser->parse('bonita/views/export',$customData,true,true);
    }
}