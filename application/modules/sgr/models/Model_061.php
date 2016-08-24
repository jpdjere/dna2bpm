<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_061 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '061';
        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');
        /* SWITCH TO SGR DB */        
        $this->sgr_db=new $this->cimongo;
        #DB
        $this->sgr_db->switch_db('sgr');


        /* MODELS */

        $this->load->model('app');
        $this->load->model('padfyj_model');
        $this->load->model('model_06');
        $this->load->Model('model_12');

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
            1 => 'CUIT_SOCIO_INCORPORADO', //CUIT_SOCIO_INCORPORADO
            2 => 'TIENE_VINCULACION', //TIENE_VINCULACION
            3 => 'CUIT_VINCULADO', //CUIT_VINCULADO
            4 => 'RAZON_SOCIAL_VINCULADO', //RAZON_SOCIAL_VINCULADO
            5 => 'TIPO_RELACION_VINCULACION', //TIPO_RELACION_VINCULACION
            6 => 'PORCENTAJE_ACCIONES', //"PORCENTAJE_ACCIONES"
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            $insertarr['CUIT_SOCIO_INCORPORADO'] = (string) $insertarr['CUIT_SOCIO_INCORPORADO'];
            $insertarr['CUIT_VINCULADO'] = (string) $insertarr['CUIT_VINCULADO'];

            /* FLOAT */
            $insertarr['PORCENTAJE_ACCIONES'] = (float) $insertarr['PORCENTAJE_ACCIONES'];
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
        $this->update_period($get_period['id'], $get_period['status']);

        $result = $this->app->put_array_sgr($id, $container, $parameter);

        if ($result) {
            /* ACTUALIZO PENDIND DEL ANEXO 06 */
            $get_pending = $this->sgr_model->get_current_period_info("06", $period);
            $this->update_pending($get_pending['id']);


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

    function save_period_pending($parameter) {
        /* ADD PERIOD */
        $container = 'container.sgr_periodos';
        $period = $this->session->userdata['period'];

        $id = $this->app->genid_sgr($container);
        $parameter['period'] = $period;
        $parameter['period_date'] = translate_period_date($period);
        $parameter['status'] = 'activo';
        $parameter['idu'] = (float) $this->idu;
        $parameter['activated_on'] = date('Y-m-d h:i:s');


        /*
         * VERIFICO PENDIENTE           
         */
        $get_period = $this->sgr_model->get_current_period_info($this->anexo, $period);

        $this->update_period($get_period['id'], $get_period['status']);
        $result = $this->app->put_array_sgr($id, $container, $parameter);
        if ($result) {
            /* ACTUALIZO PENDIND DEL ANEXO 06 */
            $get_pending = $this->sgr_model->get_current_period_info("06", $period);
            /* UPDATE */
            if (isset($get_period['status']))
                $this->update_period($get_period['id'], $get_period['status']);

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

    /* UPDATE ANEXO 06 */

    function update_pending($id) {
        $options = array('upsert' => true, 'w' => 1);
        $container = 'container.sgr_periodos';
        $query = array('id' => $id);
        $parameter = array(
            'status' => 'activo',
            'activated_on' => date('Y-m-d h:i:s')
        );

        $rs = $this->mongowrapper->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_info($anexo, $parameter) {

        $headerArr = array("Tipo<br/>de<br/>Socio",
            "C.U.I.T Socio<br/>Incorporado",
            "Socio<br/>Incorporado",
            "Tiene<br/>Vinculacion",
            "C.U.I.T<br/>Vinculado",
            "Razón<br/>Social<br/>Vinculado",
            "Tipo<br/>Relación<br/>Vinculación",
            "Porcentaje<br/>Acciones",
            "Es<br/>Participe",
            "Es<br/>Protector"
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
            if (isset($list['1695']))
                $cuit = str_replace("-", "", $list['1695']);

            $brand_name = $list['1693'];

            $partner_type = $this->app->get_ops(532);

            $parner_inc = $this->padfyj_model->search_name($list['CUIT_SOCIO_INCORPORADO']);
            $parner_linked = $this->padfyj_model->search_name((string) $list['CUIT_VINCULADO']);

            $type_partner_inc_value = false;

            
            $type_partner = $this->model_06->partner_type($list['CUIT_SOCIO_INCORPORADO'], $this->sgr_id);

            /* SHARER */
            $type_partner_inc_sharer = $this->model_06->partner_type_linked_sharer((string) $list['CUIT_VINCULADO']);
            if (isset($type_partner_inc_sharer)) {
                foreach ($type_partner_inc_sharer as $partner_inc_sharer)
                    $type_partner_inc_value_sharer = $partner_inc_sharer[5272];
            }

            /* PROTECTOR */
            $type_partner_inc = $this->model_06->partner_type_linked((string) $list['CUIT_VINCULADO']);
            if (isset($type_partner_inc)) {
                foreach ($type_partner_inc as $partner_inc)
                    $type_partner_inc_value = $partner_inc[5272];
            }

            $parner_linked = ($parner_linked) ? $parner_linked : $list['RAZON_SOCIAL_VINCULADO'];

            $es_participe = "-";
            $es_protector = "-";

            if (!empty($list['CUIT_VINCULADO'])) {
                $es_participe = ($type_partner_inc_value_sharer[0] == "A") ? "SI" : "NO";
                $es_protector = ($type_partner_inc_value[0] == "B") ? "SI" : "NO";
            }

            $new_list = array();
            $new_list['TIPO_SOCIO'] = $type_partner;
            $new_list['CUIT_SOCIO_INCORPORADO'] = $list['CUIT_SOCIO_INCORPORADO'];
            $new_list['SOCIO_INCORPORADO'] = $parner_inc;
            $new_list['"TIENE_VINCULACION"'] = $list['TIENE_VINCULACION'];
            $new_list['"CUIT_VINCULADO"'] = $list['CUIT_VINCULADO'];
            $new_list['"RAZON_SOCIAL_VINCULADO"'] = $parner_linked;
            $new_list['"TIPO_RELACION_VINCULACION"'] = $list['TIPO_RELACION_VINCULACION'];
            $new_list['"PORCENTAJE_ACCIONES"'] = percent_format_custom($list['PORCENTAJE_ACCIONES'] * 100);
            $new_list['"PARTICIPE"'] = $es_participe;
            $new_list['"PROTECTOR"'] = $es_protector;



            $rtn[] = $new_list;
        }
        return $rtn;
    }

   
    /**
     * Nuevo Reporte Anexo 061
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
                )      
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

                if(isset($list['1693']))
                    $brand_name = $list['1693'];


                $partner_type = $this->app->get_ops(532);

                $parner_inc = $this->padfyj_model->search_name($list['CUIT_SOCIO_INCORPORADO']);
                $parner_linked = $this->padfyj_model->search_name((string) $list['CUIT_VINCULADO']);

                $type_partner_inc_value = false;

                $type_partner = $this->model_06->partner_type($list['CUIT_SOCIO_INCORPORADO'], $list['sgr_id']);

                /* SHARER */
                $type_partner_inc_value_sharer = null;
                $type_partner_inc_value = null;

                $type_partner_inc_sharer = $this->model_06->partner_type_linked_sharer((string) $list['CUIT_VINCULADO']);
                if (isset($type_partner_inc_sharer)) {
                    foreach ($type_partner_inc_sharer as $partner_inc_sharer)
                        $type_partner_inc_value_sharer = $partner_inc_sharer[5272];
                }

                /* PROTECTOR */
                $type_partner_inc = $this->model_06->partner_type_linked((string) $list['CUIT_VINCULADO']);
                if (isset($type_partner_inc)) {
                    foreach ($type_partner_inc as $partner_inc)
                        $type_partner_inc_value = $partner_inc[5272];
                }

                $parner_linked = ($parner_linked) ? $parner_linked : $list['RAZON_SOCIAL_VINCULADO'];

                $es_participe = "-";
                $es_protector = "-";

                if (!empty($list['CUIT_VINCULADO'])) {
                    $es_participe = ($type_partner_inc_value_sharer[0] == "A") ? "SI" : "NO";
                    $es_protector = ($type_partner_inc_value[0] == "B") ? "SI" : "NO";
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
                $new_list['col4'] = $period_year . "-" . $period_month;
                $new_list['col5'] = $type_partner;
                $new_list['col6'] = $list['CUIT_SOCIO_INCORPORADO'];
                $new_list['col7'] = $parner_inc;
                $new_list['col8'] = $list['TIENE_VINCULACION'];
                $new_list['col9'] = $list['CUIT_VINCULADO'];
                $new_list['col10'] = $parner_linked;
                $new_list['col11'] = $list['TIPO_RELACION_VINCULACION'];
                $new_list['col12'] = percent_format_custom($list['PORCENTAJE_ACCIONES'] * 100);
                $new_list['col13'] = $es_participe;
                $new_list['col14'] = $es_protector;
                $new_list['col15'] = $filename;
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
