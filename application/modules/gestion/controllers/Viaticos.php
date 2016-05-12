<?php

// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');
/**
 * Actualiza los archivos segun la rama configurada
 * 
 * @autor Diego Otero
 * 
 * @version 	1.0 
 * 
 * 
 */
class Viaticos extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->library('parser');
        $this->load->library('dashboard/ui');

        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->model('ssl/ssl_model');
        $this->load->model('msg');
        $this->load->library('phpmailer/phpmailer');

        /* LOAD MODEL */
        $this->load->model('forms_model');
        $this->load->model('app');

        //$this->user->authorize();
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
        //error_reporting(E_ALL);
    }

    function Index() {
        $data['base_url'] = $this->base_url;
        $data['title'] = 'SOLICITUD DE ANTICIPO DE VIATICOS Y ORDENES DE PASAJE';
        $data['logobar'] = $this->ui->render_logobar();


        /* Agentes */
        $data_select = NULL;
        $agents_data = $this->forms_model->buscar_agentes_registrados();

        foreach ($agents_data as $each) {
            $data_select .= '<option value=' . $each['dni'] . '>' . $each['apellido'] . ' ' . $each['nombre'] . ' </option>';
        }

        $data['groupagents'] = $data_select;


        echo $this->parser->parse('form_viaticos', $data, true, true);
    }

    //=== Create  buttons groups on ajax call

    function get_option_button() {
        $sel = $this->input->post('sel');

        $ret = NULL;
        $groups = $this->forms_model->buscar_agentes_registrados();

        if ($sel == 'all') {
            foreach ($groups as $g) {
                $ret.= "<button type='button' data-groupid='{$g['dni']}' class='btn btn-default btn-xs'><i class='fa fa-times-circle'></i> {$g['nombre']}</button>";
            }
        } else {
            // just one
            foreach ($groups as $g) {
                if ($g['dni'] == $sel) {
                    $ret.= "<input name='agentes[]' type='hidden' value='{$g['dni']}'><button type='button' data-groupid='{$g['dni']}' class='btn btn-default btn-xs'><i class='fa fa-times-circle'></i>{$g['nombre'] } {$g['apellido'] }</button>";
                    break;
                }
            }
        }
        echo $ret;
    }

    function process() {
        $data = $this->input->post();
        $rtn = $this->forms_model->save($data);
        echo $rtn; //json_encode(array('status'=>'msg_ok_' . $rtn));
    }
    
    
    function manage_viaticos(){
        
        
        $header_data = array(); 
        $data = array();
        
        $header_data['base_url'] = $this->base_url;
        $header_data['title'] = 'SOLICITUD DE ANTICIPO DE VIATICOS Y ORDENES DE PASAJE | ADMINISTRACION';
        $header_data['logobar'] = $this->ui->render_logobar();
        
        $rtn_data = array_merge($header_data, $data);
        
         $query = array('expte' => array('$exists'=>true));
         $viatico_data = $this->forms_model->buscar_viaticos($query);
        
        $table_interior = null;
        $table_exterior = null;
            
        foreach($viatico_data as $key=>$value){
            
            /* TABLE */
            $customData = array();
            $customData['expte'] = $value['expte'];
            $customData['print'] = $value['id'];
            
            switch($value['destino']){
                case "interior":
                    $table_interior .= $this->parser->parse('table_rows_admin_interior', $customData, true);
                    break;
                    
                default:
                    $table_exterior .= $this->parser->parse('table_rows_admin_exterior', $customData, true);
                    break;
                
            }

            
            
            
        }
        
        $data['table_interior'] = $table_interior;
        $data['table_exterior'] = $table_exterior;
        //$data = array_map('strtoupper', $data);
        $rtn_data = array_merge($header_data, $data);
        
        
        echo $this->parser->parse('manage_viaticos', $rtn_data, true, true);
        
        
        
    }

    function print_viatico($parameter) {
        
        $header_data = array(); 
        $data = array();

        $header_data['base_url'] = $this->base_url;
        $header_data['title'] = 'SOLICITUD DE ANTICIPO DE VIATICOS Y ORDENES DE PASAJE | Imprimible';
        $header_data['logobar'] = $this->ui->render_logobar();

        $query = array('id' => (int) $parameter);
        $viatico_data = $this->forms_model->buscar_viaticos($query);
            
        foreach ($viatico_data[0] as $key => $value) {

            /* TEMPLATE */
            $tmpl = $viatico_data[0]['destino'];
            
            list($desde, $hasta) = explode("-", trim($viatico_data[0]['event-interval']));
            

            $desde = $this->rtn_date_format($desde);
            $hasta = $this->rtn_date_format($hasta);

            list($data['desde'], $data['desde_hora']) = explode(" ", $desde);
            list($data['hasta'], $data['hasta_hora']) = explode(" ", $hasta);

            
            $data['duracion'] = $this->diff_dates_fn($desde, $hasta);
            

            /* Sum Gastos */
            $gatos_eventuales = (float) $viatico_data[0]['gastos_eventuales'];
            $dolar_valor = (float)  $viatico_data[0]['tipo_de_cambio'];


            if ($diff == 0)
                $diff = 1;

            /* AGENTS */
            if ($key == 'agentes') {
                $sum_gatos_agentes = 0;
                /* INIT TABLE ROWS */
                
                $table = NULL;
                
                foreach ($value as $anyone) {

                    if ($anyone != "") {
                        $id_agentes = array('dni' => $anyone);
                        $agentes = $this->forms_model->buscar_un_agente($id_agentes);


                        $importe = (float) $this->escalas($viatico_data[0]['provincia']);
                        $importe_total = $importe * $diff;


                        $print_nombre = $agentes[0]['nombre'] . " " . $agentes[0]['apellido'];
                        /*EXTERIOR*/
                        $data['nombre_apellido'] = strtoupper($print_nombre);
                        $data['cuil'] = $anyone;
                        $data['categoria_cargo'] = $agentes[0]['nivel_y_grado'];
                        $data['permanente_o_contratado'] = ($agentes[0]['modalidad']=="PP") ? "PLANTA PERMANENTE" : $agentes[0]['modalidad'];
                        
                        
                        
                        if (strlen($print_nombre) > 25) {
                            $print_nombre = $agentes[0]['apellido'];
                        }

                        $sum_gatos_agentes+=$importe_total;
                        
                        /* TABLE AGENTES */
                        $customData = array();
                        $customData['nombre'] = strtoupper($print_nombre);
                        $customData['cuil'] = $anyone;
                        $customData['modalidad'] = $agentes[0]['modalidad'];
                        $customData['nivel_y_grado'] = $agentes[0]['nivel_y_grado'];
                        $customData['telefono'] = '43350';
                        $customData['email'] = 'viaticospymes@produccion';
                        $customData['importe'] = number_format($importe);
                        $customData['importe_total'] = number_format($importe_total);

                        $table .= $this->parser->parse('table_rows', $customData, true);
                    }
                }
            }

            $data[$key] = $value;
        }


        /* EVENTUAL DATA */
        if ($gatos_eventuales !== 0) {
            $customData = array();
            $customData['nombre'] = 'EVENTUALES';
            $customData['cuil'] = null;
            $customData['modalidad'] = null;
            $customData['nivel_y_grado'] = null;
            $customData['telefono'] = null;
            $customData['email'] = null;
            $customData['importe'] = null;
            $customData['importe_total'] = number_format($gatos_eventuales);

            $table .= $this->parser->parse('table_rows', $customData, true);
        }

        /* TOTALES */
        $customData = array();
        $customData['nombre'] = 'TOTAL';
        $customData['cuil'] = null;
        $customData['modalidad'] = null;
        $customData['nivel_y_grado'] = null;
        $customData['telefono'] = null;
        $customData['email'] = null;
        $customData['importe'] = null;
        $customData['importe_total'] = number_format($gatos_eventuales + $sum_gatos_agentes);
    

        $table .= $this->parser->parse('table_rows', $customData, true);
        $data['agentes'] = $table;
        
        $data['importe_pasaje'] = number_format($viatico_data[0]['importe_pasaje']);
        $data['transporte_exterior'] =  number_format($gatos_eventuales*$dolar_valor);
        $data['costo_moneda_extrangera'] =  number_format($viatico_data[0]['viaticos_diarios']*$diff);
        $data['gastos_eventuales_pesos'] = number_format($gatos_eventuales);
        
        
        $data = array_map('strtoupper', $data);
        $rtn_data = array_merge($header_data, $data);

        echo $this->parser->parse('print_viaticos_' . strtolower($tmpl), $rtn_data, true, true);
    }
    
            
  

    function escalas($provincia) {

        $valor = 0;

        $arr_noroeste = array("Jujuy", "Salta", "Tucuman", "Catamarca", "La Rioja");
        $arr_noreste = array("Misiones", "Corrientes", "Entre Rios", "Formosa", "Chaco");
        $arr_cuyo = array("San Juan", "Mendoza", "San Luis");
        $arr_centro = array("Cordoba", "Santiago del Estero", "Santa Fe", "La Pampa");
        $arr_sur = array("Neuquen", "Rio Negro", "Chubut", "Santa Cruz", "Tierra del Fuego");
        $arr_metro = array("Buenos Aires", "CABA");

        if (in_array($provincia, $arr_noroeste)) {
            $valor = 998;
        }
        if (in_array($provincia, $arr_noreste)) {
            $valor = 698;
        }
        if (in_array($provincia, $arr_cuyo)) {
            $valor = 998;
        }
        if (in_array($provincia, $arr_centro)) {
            $valor = 833;
        }
        if (in_array($provincia, $arr_sur)) {
            $valor = 1222;
        }
        if (in_array($provincia, $arr_metro)) {
            $valor = 698;
        }

        return $valor;
    }

    function rtn_date_format($param) {

        list($fecha, $hora) = explode(" ", trim($param));
        $date = str_replace('/', '-', $param);

        return trim($date);
    }
    
    function diff_dates_fn($desde, $hasta){
          
        $datetime1 = new DateTime($desde);
        $datetime2 = new DateTime(($hasta));
        $interval = $datetime1->diff($datetime2);
        $diff = (int) $interval->format('%R%a');
            
        return $diff;
                
    }

}

//class
