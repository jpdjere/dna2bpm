<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_201 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '201';
        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');
        
        $this->sgr_db=new $this->cimongo;
        #DB
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
         * @example
         * NUMERO_DE_APORTE 
         * FECHA_MOVIMIENTO 
         * CUIT_PROTECTOR   
         * APORTE   
         * RETIRO   
         * RETENCION_POR_CONTINGENTE    
         * RETIRO_DE_RENDIMIENTOS   
         * ESPECIE  
         * TITULAR_ORIG 
         * NRO_CTA_OR   
         * ENTIDAD_OR   
         * ENT_DEP_OR   
         * TITULAR_DEST 
         * NRO_DEST 
         * ENTIDAD_DEST 
         * ENT_DEP_DEST 
         * FECHA_ACTA   
         * NRO_ACTA
         * */
        $defdna = array(
            1 => 'NUMERO_DE_APORTE',
            2 => 'FECHA_MOVIMIENTO',
            3 => 'CUIT_PROTECTOR',
            4 => 'APORTE',
            5 => 'RETIRO',
            6 => 'RETENCION_POR_CONTINGENTE',
            7 => 'RETIRO_DE_RENDIMIENTOS',
            8 => 'ESPECIE',
            9 => 'TITULAR_ORIG',
            10 => 'NRO_CTA_OR',
            11 => 'ENTIDAD_OR',
            12 => 'ENT_DEP_OR',
            13 => 'TITULAR_DEST',
            14 => 'NRO_DEST',
            15 => 'ENTIDAD_DEST',
            16 => 'ENT_DEP_DEST',
            17 => 'FECHA_ACTA',
            18 => 'NRO_ACTA'
        );

        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            /* STRING */
            $insertarr["CUIT_PROTECTOR"] = (string) $insertarr["CUIT_PROTECTOR"];

            /* INTEGERS  & FLOATS */
            $insertarr["APORTE"] = (float) $insertarr["APORTE"];
            $insertarr["RETIRO"] = (float) $insertarr["RETIRO"];
            $insertarr["RETENCION_POR_CONTINGENTE"] = (float) $insertarr["RETENCION_POR_CONTINGENTE"];
            $insertarr["RETIRO_DE_RENDIMIENTOS"] = (float) $insertarr["RETIRO_DE_RENDIMIENTOS"];

            $insertarr["NRO_ACTA"] = (int) $insertarr["NRO_ACTA"];
            $insertarr["NUMERO_DE_APORTE"] = (int) $insertarr["NUMERO_DE_APORTE"];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;
        $id = $this->app->genid_sgr($container);

        /* FIX DATE */
        $parameter['FECHA_MOVIMIENTO'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_MOVIMIENTO'])));
        $parameter['FECHA_ACTA'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_ACTA'])));

        $parameter['period'] = $period;
        $parameter['origen'] = "2013";

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

    function get_anexo_info($anexo, $parameter, $xls = false) {
        /* HEADER TEMPLATE */
        $header_data = array();
        $template = array();

        if ($xls)
            $template['xls'] = true;

        $header = $this->parser->parse('prints/anexo_' . $anexo . '_header', $template, TRUE);
        $tmpl = array('data' => $header);

        $data = array($tmpl);

        $anexoValues = $this->get_anexo_data($anexo, $parameter, $xls);
        $anexoValues2 = $this->get_anexo_data_clean($anexo, $parameter, $xls);
        $anexoValues = array_merge($anexoValues, $anexoValues2);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }

        $this->load->library('table_custom');
        $newTable = $this->table_custom->generate($data);
        return $newTable;
    }

    function get_anexo_data_tmp($anexo, $parameter) {

        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $fields = array('NUMERO_DE_APORTE',
            'FECHA_MOVIMIENTO',
            'CUIT_PROTECTOR',
            'APORTE',
            'RETIRO',
            'RETENCION_POR_CONTINGENTE',
            'RETIRO_DE_RENDIMIENTOS', 'filename', 'period', 'sgr_id', 'origin');
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr_tmp->$container->find($query, $fields);

        foreach ($result as $list) {
            $rtn[] = $list;
        }

        return $rtn;
    }

    function get_anexo_data($anexo, $parameter, $xls = false) {


        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query)->sort(array('NUMERO_DE_APORTE' => 1));


        foreach ($result as $list) {
            /*
             * Vars                                 
             */
            $this->load->model('padfyj_model');
            $this->load->Model('model_201');


            $get_movement_data = $this->model_201->get_original_aporte_print($list['NUMERO_DE_APORTE'], $list['period']);

            $partener_info = $this->model_201->get_input_number_print($list['NUMERO_DE_APORTE'], $list['period']);

            foreach ($partener_info as $partner) {
                $cuit = $partner["CUIT_PROTECTOR"];
                $brand_name = $this->padfyj_model->search_name($partner["CUIT_PROTECTOR"]);
            }

            $new_list = array();
            $new_list['NUMERO_DE_APORTE'] = $list['NUMERO_DE_APORTE'];
            $new_list['FECHA_MOVIMIENTO'] = mongodate_to_print($list['FECHA_MOVIMIENTO']);
            $new_list['RAZON_SOCIAL'] = $brand_name;
            $new_list['CUIT_PROTECTOR'] = $cuit;
            $new_list['APORTE'] = money_format_custom($list['APORTE']);
            $new_list['RETIRO'] = money_format_custom($list['RETIRO']);
            $new_list['FECHA_APORTE_ORIGINAL'] = mongodate_to_print($get_movement_data['FECHA_MOVIMIENTO']);
            $new_list['APORTE_ORIGINAL'] = money_format_custom($get_movement_data['APORTE']);

            $new_list['RETENCION_POR_CONTINGENTE'] = money_format_custom($list['RETENCION_POR_CONTINGENTE']);
            $new_list['RETIRO_DE_RENDIMIENTOS'] = money_format_custom($list['RETIRO_DE_RENDIMIENTOS']);
            $new_list['ESPECIE'] = $list['ESPECIE'];
            $new_list['TITULAR_ORIG'] = $list['TITULAR_ORIG'];
            $new_list['NRO_CTA_OR'] = $list['NRO_CTA_OR'];
            $new_list['ENTIDAD_OR'] = $list['ENTIDAD_OR'];
            $new_list['ENT_DEP_OR'] = $list['ENT_DEP_OR'];
            $new_list['TITULAR_DEST'] = $list['TITULAR_DEST'];
            $new_list['NRO_DEST'] = $list['NRO_DEST'];
            $new_list['ENTIDAD_DEST'] = $list['ENTIDAD_DEST'];
            $new_list['ENT_DEP_DEST'] = $list['ENT_DEP_DEST'];
            $new_list['FECHA_ACTA'] = mongodate_to_print($list['FECHA_ACTA']);
            $new_list['NRO_ACTA'] = $list['NRO_ACTA'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_anexo_data_clean($anexo, $parameter, $xls = false) {

        $rtn = array();

        $col5 = array();
        $col6 = array();
        $col8 = array();
        $col10 = array();

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {

            $model_201 = 'model_201';
            $this->load->Model($model_201);
            $get_movement_data = $this->$model_201->get_original_aporte_print($list['NUMERO_DE_APORTE'], $list['period']);

            $col5[] = (float) $list['APORTE'];
            $col6[] = (float) $list['RETIRO'];
            $col8[] = (float) $get_movement_data['APORTE'];
            $col10[] = (float) $list['RETIRO_DE_RENDIMIENTOS'];
        }


        $new_list = array();
        $new_list['NUMERO_DE_APORTE'] = "<strong>TOTALES</strong>";
        $new_list['FECHA_MOVIMIENTO'] = "-";
        $new_list['RAZON_SOCIAL'] = "-";
        $new_list['CUIT_PROTECTOR'] = "-";
        $new_list['APORTE'] = money_format_custom(array_sum($col5));
        $new_list['RETIRO'] = money_format_custom(array_sum($col6));
        $new_list['APORTE_ORIGINAL'] = "-";
        $new_list['FECHA_APORTE_ORIGINAL'] = money_format_custom(array_sum($col8));
        $new_list['RETENCION_POR_CONTINGENTE'] = "-";
        $new_list['RETIRO_DE_RENDIMIENTOS'] = money_format_custom(array_sum($col10));
        $new_list['ESPECIE'] = "-";
        $new_list['TITULAR_ORIG'] = "-";
        $new_list['NRO_CTA_OR'] = "-";
        $new_list['ENTIDAD_OR'] = "-";
        $new_list['ENT_DEP_OR'] = "-";
        $new_list['TITULAR_DEST'] = "-";
        $new_list['NRO_DEST'] = "-";
        $new_list['ENTIDAD_DEST'] = "-";
        $new_list['ENT_DEP_DEST'] = "-";
        $new_list['FECHA_ACTA'] = "-";
        $new_list['NRO_ACTA'] = "-";

        $rtn[] = $new_list;


        return $rtn;
    }


    function exist_input_number_left($code){
        $endDate = last_month_date($this->session->userdata['period']);
        $code = (int)$code;

         $suma_query=array(
                'aggregate'=>'container.sgr_anexo_' .$this->anexo,
                'pipeline'=>
                  array(
                    array (
                        '$match' => array (
                            "NUMERO_DE_APORTE" =>$code,
                        )                        
                      ), 
                      array (
                        '$lookup' => array (
                            'from' => 'container.sgr_periodos',
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'periodo')                        
                      ),                    
                    array ('$match' => array (                                      
                        'periodo.status'=>'activo' , 
                        'periodo.anexo'=>$this->anexo,
                        'periodo.filename' => array('$ne' => 'SIN MOVIMIENTOS'),            
                        'periodo.period_date' => array(
                            '$lte' => $endDate
                         ),
                        'periodo.sgr_id' => (float) $this->sgr_id,
                    ))
                ));
        
    
         $rtn=$this->sgr_db->command($suma_query); 

         if(!empty($rtn['result'])) 
            return true;
    }

 function exist_input_number_left_ORI($code) {

        $anexo = $this->anexo;
        $period_value = $this->session->userdata['period'];
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $input_result_arr = array();
        $output_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        /* FIND ANEXO */
        foreach ($result as $list) {
            /* APORTE */
            $new_query = array(
                'NUMERO_DE_APORTE' => (int) $code,
                'filename' => $list['filename']
            );


            $io_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($io_result as $data) {
                if ($data) {
                    return true;
                }
            }
        }
    }   
    
    function get_anexo_data_left($period) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $rtn = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        /* FIND ANEXO */
        foreach ($result as $list) {
            /* APORTE */
            $new_query = array(
                'filename' => $list['filename']
            );
            $io_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($io_result as $data) {
                $rtn[] = $data;
            }
        }


        return $rtn;
    }

    function exist_input_number($code) {

        $anexo = $this->anexo;
        $period_value = $this->session->userdata['period'];
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $input_result_arr = array();
        $output_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        /* FIND ANEXO */
        foreach ($result as $list) {
            /* APORTE */
            $new_query = array(
                'NUMERO_DE_APORTE' => (int) $code,
                'filename' => $list['filename']
            );


            $io_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($io_result as $data) {
                if ($data) {
                    return true;
                }
            }
        }
    }


    function exist_input_all($validation=1) {
        $endDate = last_month_date($this->session->userdata['period']);


         $suma_query=array(
                'aggregate'=>'container.sgr_anexo_' .$this->anexo,
                'pipeline'=>
                  array(
                    array (
                        '$match' => array (
                            'APORTE' => array('$ne' => null),
                        )                        
                      ), 
                      array (
                        '$lookup' => array (
                            'from' => 'container.sgr_periodos',
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'periodo')                        
                      ),                    
                    array ('$match' => array (                                      
                        'periodo.status'=>'activo' , 
                        'periodo.anexo'=>$this->anexo,
                        'periodo.filename' => array('$ne' => 'SIN MOVIMIENTOS'),            
                        'periodo.period_date' => array(
                            '$lte' => $endDate
                         ),
                        'periodo.sgr_id' => (float) $this->sgr_id,
                    )),
                    array('$group' => array(
                          '_id' => '$NUMERO_DE_APORTE',                          
                          'APORTE' => array('$sum' => '$APORTE'), 
                          'RETIRO' => array('$sum' => '$RETIRO')
                    )),
                ));
        
    
         $rtn=$this->sgr_db->command($suma_query);    

         $rtn_array =array();      

         foreach ($rtn['result'] as $value) {

            $diff = bccomp( $value['APORTE'],  $value['RETIRO']);
            if($diff==$validation)                
                $rtn_array[] = $value['_id'];
          } 

         return $rtn_array;
    }


    function exist_input_all_ORI() {

        $anexo = $this->anexo;
        $period_value = $this->session->userdata['period'];
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $input_result_arr = array();
        $output_result_arr = array();
        $rtn = array();
        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);


        foreach ($result as $each) {

            /* APORTE */
            $new_query = array(
                'APORTE' => array('$ne' => null),
                'filename' => $each['filename']
            );

            $io_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($io_result as $data) {
                if ($data) {

                    $rtn[] = $data['NUMERO_DE_APORTE'];
                }
            }
        }

        return $rtn;
    }
   

    function get_input_number($code) {

        $anexo = $this->anexo;
        $period_value = $this->session->userdata['period'];
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $input_result_arr = array();
        $output_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        /* FIND ANEXO */
        foreach ($result as $list) {
            /* APORTE */
            $new_query = array(
                'NUMERO_DE_APORTE' => (int) $code,
                'filename' => $list['filename']
            );


            $io_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($io_result as $data) {
                if ($data['APORTE']) {
                    $input_result_arr[] = (float) $data['APORTE'];
                }
                if ($data['RETIRO']) {
                    $output_result_arr[] = (float) $data['RETIRO'];
                }
            }
        }

        $input_sum = array_sum($input_result_arr);
        $output_sum = array_sum($output_result_arr);
        $balance = $input_sum - $output_sum;
        return $balance;
    }

    function get_input_number_left($code) {
        $code = (int)$code;


        $suma_query=array(
                'aggregate'=>'container.sgr_anexo_' . $this->anexo,
                'pipeline'=>
                  array(
                    array (
                        '$match' => array (
                            'NUMERO_DE_APORTE'  => $code
                        )                        
                      ), 
                      array (
                        '$lookup' => array (
                            'from' => 'container.sgr_periodos',
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'periodo')                        
                      ),                    
                    array ('$match' => array (                                      
                        'periodo.status'=>'activo' , 
                        'periodo.anexo'=>$this->anexo,
                        'periodo.filename' => array('$ne' => 'SIN MOVIMIENTOS'),
                        'periodo.sgr_id' => (float) $this->sgr_id,
                    )),                    
                    array('$group' => array(
                          '_id' => null,
                          'APORTE' => array('$sum' => '$APORTE'), 
                          'RETIRO' => array('$sum' => '$RETIRO')
                    )),
                ));
        
    
         $suma=$this->sgr_db->command($suma_query); 
         $result = bccomp($suma['result'][0]['APORTE'], $suma['result'][0]['RETIRO']);
         
         return $result;

    }

    function get_input_number_left_ORI($code) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $input_result_arr = array();
        $output_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        /* FIND ANEXO */
        foreach ($result as $list) {
            /* APORTE */
            $new_query = array(
                'NUMERO_DE_APORTE' => (int) $code,
                'filename' => $list['filename']
            );


            $io_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($io_result as $data) {

                $nro_aporte = $data['NUMERO_DE_APORTE'];

                if ($data['APORTE']) {
                    // var_dump($code, $input_result['APORTE']);
                    $input_result_arr[] = (float) $data['APORTE'];
                }
                if ($data['RETIRO']) {
                    //var_dump($code, $input_result['RETIRO']);
                    $output_result_arr[] = (float) $data['RETIRO'];
                }
            }
        }

        $input_sum = array_sum($input_result_arr);
        $output_sum = array_sum($output_result_arr);
        $balance = $input_sum - $output_sum;

        $rtn = ($nro_aporte) ? $balance : false;

        return $rtn;
    }

    function get_input_number_print($code, $period_date) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $rtn = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period_date);
        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'NUMERO_DE_APORTE' => (int) $code,
                'filename' => $list['filename'],
                "APORTE" => array('$ne' => 0),
            );
            $io_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($io_result as $data) {
                $rtn[] = $data;
            }
        }
        return $rtn;
    }

    function get_input_number_report($code, $sgr_id) {


        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $rtn = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_each_sgrid($anexo, $sgr_id);
        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'NUMERO_DE_APORTE' => (int) $code,
                'filename' => $list['filename'],
                "APORTE" => array('$ne' => 0),
            );


            $io_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($io_result as $data) {
                $rtn[] = $data;
            }
        }
        return $rtn;
    }

    function get_last_input_number($code) {
        $anexo = $this->anexo;
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_last_rec($anexo, $period_value);


        /* FIND ANEXO */
        foreach ($result as $list) {

            $new_query = array(
                'filename' => $list['filename']
            );

            $new_result = $this->mongowrapper->sgr->$container->find($new_query)->sort(array('NUMERO_DE_APORTE' => -1))->limit(1);
            foreach ($new_result as $new_list) {
                return $new_list['NUMERO_DE_APORTE'];
            }
        }

        $result = array_sum($nresult_arr);
        return $result;
    }

    function clear_tmp($parameter) {
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $token . '_tmp';
        $delete = $this->mongowrapper->sgr_tmp->$container->remove();
    }

    function save_tmp($parameter) {

        $container = 'container.sgr_anexo_report_' . $this->idu . '_tmp';

        $parameter['TOKEN'] = $this->idu;
        $parameter['FECHA_MOVIMIENTO'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_MOVIMIENTO'])));

        $criteria = array('id' => $id);
        $update = array('$set' => $parameter);
        $options = array('upsert' => true, 'w' => 1);
        $result = $this->mongowrapper->sgr_tmp->selectCollection($container)->update($criteria, $update, $options);

        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    function get_last_input() {
        $anexo = $this->anexo;
        $period_value = $this->session->userdata['period'];
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $rtn = array();
        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        /* FIND ANEXO */

        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename']
            );
            $new_result = $this->mongowrapper->sgr->$container->find($new_query)->sort(array('NUMERO_DE_APORTE' => -1))->limit(1);


            foreach ($new_result as $new_list) {
                $rtn[] = $new_list['NUMERO_DE_APORTE'];
            }
        }

        return max($rtn);
    }

    function get_movement_data($nro) {
        $anexo = $this->anexo;
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];


        $aporte_result_arr = array();
        $retiro_result_arr = array();
        $rendimientos_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);



        /* FIND ANEXO */
        foreach ($result as $list) {



            $new_query = array(
                'filename' => $list['filename'],
                'NUMERO_DE_APORTE' => $nro
            );

            $movement_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($movement_result as $movement) {
                $aporte_result_arr[] = $movement['APORTE'];
                $retiro_result_arr[] = $movement['RETIRO'];
                $rendimientos_result_arr[] = $movement['RETIRO_DE_RENDIMIENTOS'];
            }
        }


        $aporte_sum = array_sum($aporte_result_arr);
        $retiro_sum = array_sum($retiro_result_arr);
        $rendimientos_sum = array_sum($rendimientos_result_arr);

        $return_arr = array(
            'APORTE' => $aporte_sum,
            'RETIRO' => $retiro_sum,
            'RETIRO_DE_RENDIMIENTOS' => $rendimientos_sum
        );
        return $return_arr;
    }

    function get_movement_recursive($nro) {
        $anexo = $this->anexo;
        $period_value = $this->session->userdata['period'];
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;


        $aporte_result_arr = array();
        $retiro_result_arr = array();
        $rendimientos_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_exclude_this($anexo, $period_value);

        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'NUMERO_DE_APORTE' => $nro
            );

            $movement_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($movement_result as $movement) {
                $aporte_result_arr[] = $movement['APORTE'];
                $retiro_result_arr[] = $movement['RETIRO'];
                $rendimientos_result_arr[] = $movement['RETIRO_DE_RENDIMIENTOS'];
            }
        }


        $aporte_sum = array_sum($aporte_result_arr);
        $retiro_sum = array_sum($retiro_result_arr);
        $rendimientos_sum = array_sum($rendimientos_result_arr);

        $return_arr = array(
            'APORTE' => $aporte_sum,
            'RETIRO' => $retiro_sum,
            'RETIRO_DE_RENDIMIENTOS' => $rendimientos_sum
        );
        return $return_arr;
    }

    function get_movement_data_print($nro, $period_date) {



        $anexo = $this->anexo;

        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $nro = (int) $nro;

        $aporte_result_arr = array();
        $retiro_result_arr = array();
        $rendimientos_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period_date);



        /* FIND ANEXO */
        $new_query = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'NUMERO_DE_APORTE' => $nro
            );


//             if ($nro == 13) {                
//                debug($list['filename']);                
//            }

            $movement = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($movement as $x) {
                $aporte_result_arr[] = $x['APORTE'];
                $retiro_result_arr[] = $x['RETIRO'];
                $rendimientos_result_arr[] = $x['RETIRO_DE_RENDIMIENTOS'];
            }
        }




        $aporte_sum = array_sum($aporte_result_arr);
        $retiro_sum = array_sum($retiro_result_arr);
        $rendimientos_sum = array_sum($rendimientos_result_arr);

        $return_arr = array(
            'APORTE' => $aporte_sum,
            'RETIRO' => $retiro_sum,
            'RETIRO_DE_RENDIMIENTOS' => $rendimientos_sum
        );
        return $return_arr;
    }

    function get_original_aporte_print($nro, $period_date) {
        $anexo = $this->anexo;

        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $nro = (int) $nro;




        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period_date);

        /* FIND ANEXO */
        foreach ($result as $list) {

            $new_query = array(
                'filename' => $list['filename'],
                'NUMERO_DE_APORTE' => $nro,
                'APORTE' => array('$ne' => 0),
            );

            $movement_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($movement_result['APORTE'])
                return $movement_result;
        }
    }

    function get_tmp_movement_data($nro) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';

        $number_result_arr = array();
        $aporte_result_arr = array();
        $retiro_result_arr = array();
        $rendimientos_result_arr = array();

        $token = $this->idu;
        $new_query = array(
            'NUMERO_DE_APORTE' => $nro,
            'TOKEN' => $token
        );

        $movement_result = $this->mongowrapper->sgr->$container->find($new_query);
        foreach ($movement_result as $movement) {

            if ($movement['APORTE']) {
                $number_result_arr[] = 1;
            }
            $aporte_result_arr[] = $movement['APORTE'];
            $retiro_result_arr[] = $movement['RETIRO'];
            $rendimientos_result_arr[] = $movement['RETIRO_DE_RENDIMIENTOS'];
        }

        $number_sum = array_sum($number_result_arr);
        $aporte_sum = array_sum($aporte_result_arr);
        $retiro_sum = array_sum($retiro_result_arr);
        $rendimientos_sum = array_sum($rendimientos_result_arr);

        $return_arr = array(
            'TOTAL' => $number_sum,
            'APORTE' => $aporte_sum,
            'RETIRO' => $retiro_sum,
            'RETIRO_DE_RENDIMIENTOS' => $rendimientos_sum
        );
        return $return_arr;
    }

    function get_retiros_tmp($nro, $type) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';
        $token = $this->idu;
        $new_query = array(
            'NUMERO_DE_APORTE' => $nro,
            'TOKEN' => $token
        );
        $date_movement_arr = array();

        $movement_result = $this->mongowrapper->sgr_tmp->$container->find($new_query);

        foreach ($movement_result as $movement) {
            if ($movement[$type])
                $date_movement_arr[] = $movement['FECHA_MOVIMIENTO'];
        }
        return $date_movement_arr;
    }

    function get_aporte_tmp($nro, $date) {

        $aporte_result_arr = array();
        $retiro_result_arr = array();
        $rendimientos_result_arr = array();


        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';
        $token = $this->idu;
        $new_query = array(
            'NUMERO_DE_APORTE' => $nro,
            'TOKEN' => $token,
            'FECHA_MOVIMIENTO' => array(
                '$lte' => $date
            )
        );

        $date_movement_arr = array();

        $movement_result = $this->mongowrapper->sgr_tmp->$container->find($new_query);

        foreach ($movement_result as $movement) {
            $aporte_result_arr[] = $movement['APORTE'];
            $retiro_result_arr[] = $movement['RETIRO'];
            $rendimientos_result_arr[] = $movement['RETIRO_DE_RENDIMIENTOS'];
        }

        $aporte_sum = array_sum($aporte_result_arr);
        $retiro_sum = array_sum($retiro_result_arr);
        $rendimientos_sum = array_sum($rendimientos_result_arr);

        $return_arr = array(
            'APORTE' => $aporte_sum,
            'RETIRO' => $retiro_sum,
            'RETIRO_DE_RENDIMIENTOS' => $rendimientos_sum
        );
        return $return_arr;
    }

    

    /**
     * Nuevo Reporte Anexo 201
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

              
                $this->load->model('padfyj_model');

                $this->load->Model('model_12');
                $cuit = null;
                $brand_name = null;

                $each_sgr_id = $this->sgr_model->get_sgr_by_filename($list['filename']);


                $get_movement_data = $this->model_201->get_input_number_report($list['NUMERO_DE_APORTE'], $each_sgr_id);


                if (!empty($get_movement_data)) {
                    foreach ($get_movement_data as $warrant) {
                        $cuit = $warrant['CUIT_PROTECTOR'];
                        $brand_name = $this->padfyj_model->search_name($cuit);
                        $fecha_aporte_original = mongodate_to_print($warrant['FECHA_MOVIMIENTO']);
                        $aporte_original = dot_by_coma($warrant['APORTE']);
                    }
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
                $new_list['col3'] = period_print_format($period_info['period']);
                $new_list['col4'] = $list['NUMERO_DE_APORTE'];
                $new_list['col5'] = mongodate_to_print($list['FECHA_MOVIMIENTO']);
                $new_list['col6'] = $brand_name;
                $new_list['col7'] = $cuit;
                $new_list['col8'] = dot_by_coma($list['APORTE']);
                $new_list['col9'] = dot_by_coma($list['RETIRO']);
                $new_list['col10'] = $fecha_aporte_original;
                $new_list['col11'] = $aporte_original;
                $new_list['col12'] = dot_by_coma($list['RETENCION_POR_CONTINGENTE']);
                $new_list['col13'] = dot_by_coma($list['RETIRO_DE_RENDIMIENTOS']);
                $new_list['col14'] = $list['ESPECIE'];
                $new_list['col15'] = $list['TITULAR_ORIG'];
                $new_list['col16'] = $list['NRO_CTA_OR'];
                $new_list['col17'] = $list['ENTIDAD_OR'];
                $new_list['col18'] = $list['ENT_DEP_OR'];
                $new_list['col19'] = $list['TITULAR_DEST'];
                $new_list['col20'] = $list['NRO_DEST'];
                $new_list['col21'] = $list['ENTIDAD_DEST'];
                $new_list['col22'] = $list['ENT_DEP_DEST'];
                $new_list['col23'] = mongodate_to_print($list['FECHA_ACTA']);
                $new_list['col24'] = $list['NRO_ACTA'];
                $new_list['col25'] = $filename;
                $new_list['uquery'] = $parameter;
              
                /* SAVE RESULT IN TMP DB COLLECTION */
                $this->sgr_model->save_tmp_general($new_list, $list['id']);
                $rtn_msg = array('ok');
            } 
        }
       echo json_encode($rtn_msg);
       exit;
    }

   
    
    function ui_table_xls_ORI($result, $anexo = null) {

        foreach ($result as $list) {

            /* Vars */
            $this->load->model('padfyj_model');

            $this->load->Model(model_12);
            $cuit = null;
            $brand_name = null;

            $each_sgr_id = $this->sgr_model->get_sgr_by_filename($list['filename']);


            $get_movement_data = $this->model_201->get_input_number_report($list['NUMERO_DE_APORTE'], $each_sgr_id);


            if (!empty($get_movement_data)) {
                foreach ($get_movement_data as $warrant) {
                    $cuit = $warrant['CUIT_PROTECTOR'];
                    $brand_name = $this->padfyj_model->search_name($cuit);
                    $fecha_aporte_original = mongodate_to_print($warrant['FECHA_MOVIMIENTO']);
                    $aporte_original = dot_by_coma($warrant['APORTE']);
                }
            }



            $get_period_filename = $this->sgr_model->get_period_filename($list['filename']);
            $sgr_info = $this->sgr_model->get_sgr_by_id_new($get_period_filename['sgr_id']);
            



            $new_list = array();
            $new_list['col1'] = $sgr_info['1693'];
            $new_list['col2'] = $list['id'];
            $new_list['col3'] = period_print_format($get_period_filename['period']);
            $new_list['col4'] = $list['NUMERO_DE_APORTE'];
            $new_list['col5'] = mongodate_to_print($list['FECHA_MOVIMIENTO']);
            $new_list['col6'] = $brand_name;
            $new_list['col7'] = $cuit;
            $new_list['col8'] = dot_by_coma($list['APORTE']);
            $new_list['col9'] = dot_by_coma($list['RETIRO']);
            $new_list['col10'] = $fecha_aporte_original;
            $new_list['col11'] = $aporte_original;
            $new_list['col12'] = dot_by_coma($list['RETENCION_POR_CONTINGENTE']);
            $new_list['col13'] = dot_by_coma($list['RETIRO_DE_RENDIMIENTOS']);
            $new_list['col14'] = $list['ESPECIE'];
            $new_list['col15'] = $list['TITULAR_ORIG'];
            $new_list['col16'] = $list['NRO_CTA_OR'];
            $new_list['col17'] = $list['ENTIDAD_OR'];
            $new_list['col18'] = $list['ENT_DEP_OR'];
            $new_list['col19'] = $list['TITULAR_DEST'];
            $new_list['col20'] = $list['NRO_DEST'];
            $new_list['col21'] = $list['ENTIDAD_DEST'];
            $new_list['col22'] = $list['ENT_DEP_DEST'];
            $new_list['col23'] = mongodate_to_print($list['FECHA_ACTA']);
            $new_list['col24'] = $list['NRO_ACTA'];
            $new_list['col25'] = $list['filename'];
            $rtn[] = $new_list;
        }

        return $rtn;
    }
}