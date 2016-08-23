<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_062 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '062';
        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');
        /* SWITCH TO SGR DB */
        $this->load->library('cimongo/Cimongo.php', '', 'sgr_db');
        $this->sgr_db->switch_db('sgr');


        if (!$this->idu) {
            header("$this->module_url/user/logout");
        }

        /* DATOS SGR */
        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
        }
    }

    function sanitize($parameter) {
        /* FIX INFORMATION */
        $parameter = (array) $parameter;
        $parameter = array_map('trim', $parameter);
        $parameter = array_map('addSlashes', $parameter);

        return $parameter;
    }

    function check($parameter) {
        /**
         *   Funcion ...
         * 
         * @param 
         * @type PHP
         * @name ...
         * @author Diego
         *
         * @example .... 

         * */
        $defdna = array(
            1 => 'CUIT', //CUIT
            2 => 'ANIO_MES', //ANIO_MES
            3 => 'FACTURACION', //FACTURACION
            4 => 'EMPLEADOS', //EMPLEADOS
            5 => 'TIPO_ORIGEN', //TIPO_ORIGEN
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            $insertarr["CUIT"] = (string) $insertarr["CUIT"];
            /* FLOAT */
            $insertarr["FACTURACION"] = (float) $insertarr["FACTURACION"];
            /* INT */
            $insertarr['EMPLEADOS'] = (int) $insertarr['EMPLEADOS'];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter['period'] = $period;
        $parameter['origen'] = "2013";

        $id = $this->app->genid_sgr($container);

        $result = $this->app->put_array_sgr($id, $container, $parameter);

        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    function save_period($parameter) {
        /* ADD PERIOD */
        $container = 'container.sgr_periodos';
        $period = $this->session->userdata['period'];
        $id = $this->app->genid_sgr($container);
        $parameter['period'] = $period;
        $parameter['period_date'] = translate_period_date($period);
        $parameter['status'] = 'activo';
        $parameter['idu'] = (float) $this->idu;
        $parameter['origen'] = "2013";

        /*
         * VERIFICO PENDIENTE           
         */
        $get_period = $this->sgr_model->get_current_period_info($this->anexo, $period);

        /* UPDATE */
        if (isset($get_period['status']))
            $this->update_period($get_period['id'], $get_period['status']);

        $result = $this->app->put_array_sgr($id, $container, $parameter);

        if (isset($result)) {
            /* BORRO SESSION RECTIFY */
            $this->session->unset_userdata('rectify');
            $this->session->unset_userdata('others');
            $this->session->unset_userdata('period');
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    function update_period($id, $status) {

        $options = array('upsert' => true, 'w' => 1);
        $container = 'container.sgr_periodos';
        $query = array('id' => (float) $id);
        $parameter = array(
            'status' => 'rectificado',
            'rectified_on' => date('Y-m-d h:i:s'),
            'others' => $this->session->userdata['others'],
            'reason' => $this->session->userdata['rectify']
        );
        $rs = $this->mongowrapper->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_data_tmp($anexo, $parameter) {

        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $fields = array('CUIT',
            'EMPLEADOS', 'filename', 'period', 'sgr_id', 'origin');
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr_tmp->$container->find($query, $fields);

        foreach ($result as $list) {
            $rtn[] = $list;
        }

        return $rtn;
    }

    function get_anexo_info($anexo, $parameter) {


        $headerArr = array("C.U.I.T",
            "Apellido y Nombre o Razón Social",
            "Fecha de Cierre del ejercicio<br> (Mes/Año)",
            "Cantidad de Empleados al último día del ejercicio",
            "Ingresos",
            "Origen de los Datos"
        );
        $data = array($headerArr);
        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table');
        return $this->table->generate($data);
    }

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);

        foreach ($result as $list) {

            /* Vars */
            $this->load->model('app');
            $this->load->model('padfyj_model');

            $parner = $this->padfyj_model->search_name($list['CUIT']);
            list($year, $month) = explode("/", $list['ANIO_MES']);

            $new_list = array();
            $new_list['col1'] = $list['CUIT'];
            $new_list['col2'] = $parner;
            $new_list['col3'] = $month . "/" . $year;
            $new_list['col4'] = $list['EMPLEADOS'];
            $new_list['col5'] = $list['FACTURACION'];
            $new_list['col6'] = $list['TIPO_ORIGEN'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }
    
    /*COUNT PARTNERS*/
    function get_count_partner_left($cuit, $sgr_id=null) {

        if(isset($this->sgr_id))
            $sgr_id = $this->sgr_id;

        if(!$sgr_id)
            $sgr_id = array('$exists'  => true);

        $query=array(
                'aggregate'=>'container.sgr_periodos',
                'pipeline'=>
                  array(
                      array ('$match' => array (
                        'status'=>'activo',
                        'sgr_id' =>$sgr_id, 
                        'anexo' => $this->anexo,
                        "filename" => array('$ne' => 'SIN MOVIMIENTOS')
                       )),  
                      array (
                        '$lookup' => array (
                            'from' => 'container.sgr_anexo_' . $this->anexo,
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'anexo_data')                        
                      ),                     
                      array ('$match' => array (
                        'anexo_data.CUIT'  => $cuit,
                        "anexo_data.EMPLEADOS" => array('$ne' => 'SIN MOVIMIENTOS')
                       )),
                       array('$project'=>array(
                            'EMPLEADOS'=>'$anexo_data.EMPLEADOS',"_id"=>0
                            )),
                        array('$sort'=>array(
                            "period_date"=>-1
                            )),
                        ));


    
         $rs=$this->sgr_db->command($query);
         $rtn = (isset($rs['result'][0]['EMPLEADOS'][0])) ? $rs['result'][0]['EMPLEADOS'][0] : 0; 
        
         return $rtn;

    }
    function get_count_partner_left_ORI($cuit) {
        $anexo = $this->anexo;
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $anexo;
        if(isset($this->session->userdata['period']))
            $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'CUIT' => $cuit
            );            
            
            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $each)
                $return_result[] = $each['EMPLEADOS'];
        }

        return array_sum($return_result);
    }

    function get_count_partner_left_fre($cuit) {
        $anexo = $this->anexo;
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_fre($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'CUIT' => $cuit
            );
            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $each)
                $return_result[] = $each['EMPLEADOS'];
        }

        return array_sum($return_result);
    }

    

    function get_partner_left($cuit) {
        $anexo = $this->anexo;
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'CUIT' => $cuit
            );            
            
            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $each)
                $return_result[] = $each;
        }

        return array_sum($return_result);
    }

    function get_partner_left_fre($cuit) {
        $anexo = $this->anexo;
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_fre($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'CUIT' => $cuit
            );
            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $each)
                $return_result[] = $each;
        }

        return array_sum($return_result);
    }
 /**
     * Nuevo Reporte Anexo 062
     *
     * @name generate_report
     *
     * @see SGR()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */

     

     function get_link_report($anexo) {

        $headerArr = header_arr($anexo);
        $title_report = $this->sgr_model->get_anexo($anexo);
        
        $data[] = array($headerArr);
        $anexoValues = $this->sgr_model->last_report_general();

        if (!$anexoValues) {
            return false;
        } else {
            foreach ($anexoValues as $values) {
                $header = '<h2>Reporte '.$anexo.' - '.strtoupper($title_report['title']).' </h2><h3>PERIODO/S: ' . $values['uquery']['input_period_from'] . ' a ' . $values['uquery']['input_period_to'] . '</h3>';

                unset($values['_id']);
                unset($values['id']);
                $data[] = array_values($values);
            }
            $this->load->library('table');
            return $header . $this->table->generate($data);
        }
    }

    function generate_report($parameter=array()) {
        

        /*REPORT POST VALUES*/        


        # STANDARD 
        $report_name = $this->input->post('report_name');
        $start_date = first_month_date($this->input->post('input_period_from'));       
        $end_date = last_month_date($this->input->post('input_period_to'));      

        
        $cuit = method_exists($this->input, 'post') ? $this->input->post('cuit_socio') : array('$exists'  => true);
      
        switch ($this->input->post('sgr')) {
            case '666':
                $sgr_id = array('$exists'  => true);
            break;

            case '777':
                $sgr_id = array('$in'=>$sgr_id_array);
            break;

            default:
                $sgr_id = (float)$this->input->post('sgr');
            break;
        }

        /*QUERY*/       
        $query =array(
            'aggregate'=>'container.sgr_periodos',
            'pipeline'=>
             array(
                    array (
                        '$match' => array (
                            'anexo' => (string)$this->anexo,
                            'sgr_id' =>$sgr_id, 
                            'status'=>'activo',                            
                            'period_date' => array(
                                '$gte' => $start_date, '$lte' => $end_date
                            )
                        )                        
                    ),                         
                    array (
                        '$lookup' => array (
                            'from' => 'container.sgr_anexo_' . $this->anexo,
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'anexo_data'
                        )
                                                  
                    ),
                    array (
                        '$match' => array (
                            'anexo_data.CUIT' => $cuit
                        )                        
                    ),       
            )     
        );    



        $get=$this->sgr_db->command($query);        
        $this->ui_table_xls($get['result'], $this->anexo, $parameter, $end_date);       
    }


    function ui_table_xls($result, $anexo = null, $parameter) { 

        #custom
        $rtn_msg = array('no_record');
        
        $list = null;
        
        $this->sgr_model->del_tmp_general();
        
        foreach ($result as $period_info) {
        
            foreach ($period_info['anexo_data'] as $list) {
                
                /* Vars */
                $new_list = array();

                $this->load->model('app');
                $currency = $this->app->get_ops(549);


                $cuit_name = null;
                if(isset($list['CUIT'])){
                    $this->load->model('padfyj_model');
                    $cuit_name = $this->padfyj_model->search_name($list['CUIT']);
                }
                
                /* FILENAME */
                $sgr_info = array();
                $period_month = null;
                $period_year = null;

                if(isset($period_info['filename'])){
                    $filename = trim($list['filename']);   
                    
                    $sgr_info = $this->sgr_model->get_sgr_by_id_new($period_info['sgr_id']);
                    list($period_month, $period_year) = explode("-", $period_info['period']);
                }

                $new_list = array();
                $new_list['col0'] = $sgr_info[1693];
                $new_list['col1'] = $sgr_info[1695];            
                $new_list['col2'] = $list['id'];
                $new_list['col3'] = $period_year;
                $new_list['col4'] = $period_info['period'];
                $new_list['col5'] = $list['CUIT'];
                $new_list['col6'] = $cuit_name;
                $new_list['col7'] = $list['EMPLEADOS'];            
                $new_list['col8'] = dot_by_coma($list['FACTURACION']);
                $new_list['col9'] = $list['TIPO_ORIGEN'];
                $new_list['col10'] = $list['ANIO_MES'];
                $new_list['col11'] = $filename;
                $new_list['uquery'] = $parameter;
              
                /* SAVE RESULT IN TMP DB COLLECTION */
                $this->sgr_model->save_tmp_general($new_list, $list['id']);
                $rtn_msg = array('ok');
            } 
        }
       echo json_encode($rtn_msg);
       exit;
    }
}