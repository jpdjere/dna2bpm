<?php
/**
* CONTROLADOR DEl DASHBOARD DE PLAN DE ADQUISICIONES
* 
* ESTE CONTROLADOR MANEJA TODOS LOS EVENTES DE ALTA/BAJA/MODIFICACION
* QUE SE PUEDEN HACER SOBRE UN CONTRATO ENTERO, O SOBRE ALGUNO DE SUS ATRIBUTOS
* 
* 
* * LOS SCRIPTS DE MANEJO DE LA UI ESTÁN INVOCADOS EN EL DASHBOARD plan_de_adquisiciones.json
* 
* @author Luciano Menez <lucianomenez1212@gmail.com>
* @date Oct 23, 2015
*/
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Plan_de_adquisiciones extends MX_Controller {

    function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->model('model_plan_adquisiciones');
        $this->load->helper('url');
        $this->load->module('dashboard');
    }
    /*
     * FUNCION PRINCIPAL
     */
    function Index() {
        $this->dashboard_plan_de_adquisiciones();
    }
    /**
     * LLAMADA AL DASHBOARD
     */
    function dashboard_plan_de_adquisiciones() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_plan_de_adquisiciones.json');
    }

     //WIDGET DE PRESENTACION
    function plan_de_adquisiciones(){
        
        $this->load->module('dashboard');
        
        $renderData['contratos'] = $this->model_plan_adquisiciones->lista_cargados();
       
       //FALTA IMPLEMENTAR EL TOTAL PARA QUE RECALCULE EN CADA RELOAD.
       
        // foreach  ($renderData['contratos'] as $contrato){ 
        // $renderData['TOTAL_GENERAL'] += $contrato['COSTO_EST_PESOS'];
        // $renderData['APORTE_LOCAL'] += $contrato;
        // $renderData['BID_TOTAL'] += $contrato;
        // }
        
        // var_dump($renderData['contratos']);
           
        
        $renderData['title'] = "Titulo";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template="dashboard/widgets/box_info.php";
        $renderData['content']= $this->parser->parse('plan-de-adquisiciones', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
    }
    
    //AGREGA UN CONTRATO
    function agregar_contrato(){ 
        $data = $this->input->post();
        $this->model_plan_adquisiciones->insert_plan_adquisiciones($data);
        redirect($this->module_url .'plan_de_adquisiciones');
        
    }
    
    //ELIMINA UN CONTRATO
    function eliminar_contrato($id){ 
        $this->model_plan_adquisiciones->borrar_plan_adquisiciones_db($id);
        redirect($this->module_url .'plan_de_adquisiciones');
    }
    
    //DETALLE DE LOS PAGOS DE UN CONTRATO
    function detalles_pagos($id){ 
        $pagos = $this->model_plan_adquisiciones->detalle_pagos($id);
        echo $this->parser->parse('modal_detalle_pagos', $pagos[0], true, true);
    }
    
    //DETALLE COMPLETO DE UN CONTRATO
    function detalles_contrato($id){ 
        $contratos = $this->model_plan_adquisiciones->detalle_plan_adquisiciones($id);
        echo $this->parser->parse('modal_detalle_contratos', $contratos[0], true, true);
    }
    
    //EDITA LAS FECHAS DE PAGO DE UN CONTRATO
    function editar_fechas_pago($id, $fecha_fin_cont, $fecha_public_aea){ 
        $data['REAL_FIN_CONT'] = $fecha_fin_cont;
        $data['REAL_PUBLIC_AEA'] = $fecha_public_aea;
        $this->model_plan_adquisiciones->edit_array_fecha_real($id, $data);
    }
    
    //EDITA UN CONTRATO
    function editar_contrato($id){ 
        $data = $this->input->post();
        $this->model_plan_adquisiciones->edit_plan_adquisiciones($id, $data);
    }
    
    //AGREGA PAGOS A UN CONTRATO
    function agregar_pago($id, $porcentaje, $dias, $fecha_de_pago, $monto){ 
        $data['PORCENTAJE']=$porcentaje;
        $data['DIAS']=$dias;
        $data['FECHA_DE_PAGO']= $fecha_de_pago;
        $data['MONTO']= $monto;
        $this->model_plan_adquisiciones->cargar_pagos($id, $data);
        
    }
        
    
}
