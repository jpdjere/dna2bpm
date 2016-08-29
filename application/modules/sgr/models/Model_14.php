<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_14 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->idu = (int) $this->session->userdata('iduser');
        $this->sgr_db_tmp=new $this->cimongo;
        #DB
        $this->sgr_db_tmp->switch_db('sgr_tmp');

        /*TMP report*/
        $this->collection_out = "collection_out_" . $this->idu;

        $this->load->helper('sgr/tools');

        $this->anexo = '14';
        /* Additional SGR users */
        #$this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');

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
         * @example .... FECHA_MOVIMIENTO   NRO_GARANTIA    CAIDA   RECUPERO    INCOBRABLES_PERIODO GASTOS_EFECTUADOS_PERIODO   
         * RECUPERO_GASTOS_PERIODO  GASTOS_INCOBRABLES_PERIODO
         * */
        $defdna = array(
            1 => 'FECHA_MOVIMIENTO',
            2 => 'NRO_GARANTIA',
            3 => 'CAIDA',
            4 => 'RECUPERO',
            5 => 'INCOBRABLES_PERIODO',
            6 => 'GASTOS_EFECTUADOS_PERIODO',
            7 => 'RECUPERO_GASTOS_PERIODO',
            8 => 'GASTOS_INCOBRABLES_PERIODO'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            /* STRING */
            $insertarr["NRO_GARANTIA"] = (string) trim($insertarr["NRO_GARANTIA"]); //Nro orden


            /* INTEGERS & FLOAT */
            $insertarr["CAIDA"] = (float) $insertarr["CAIDA"];
            $insertarr["RECUPERO"] = (float) $insertarr["RECUPERO"];
            $insertarr["INCOBRABLES_PERIODO"] = (float) $insertarr["INCOBRABLES_PERIODO"];
            $insertarr["GASTOS_EFECTUADOS_PERIODO"] = (float) $insertarr["GASTOS_EFECTUADOS_PERIODO"];
            $insertarr["RECUPERO_GASTOS_PERIODO"] = (float) $insertarr["RECUPERO_GASTOS_PERIODO"];
            $insertarr["GASTOS_INCOBRABLES_PERIODO"] = (float) $insertarr["GASTOS_INCOBRABLES_PERIODO"];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter['FECHA_MOVIMIENTO'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_MOVIMIENTO'])));

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

    function clear_tmp($parameter) {
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $token . '_tmp';
        $delete = $this->mongowrapper->sgr_tmp->$container->remove();
    }

    function save_tmp($parameter) {

        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';

        $parameter['TOKEN'] = $this->idu;
        $parameter['FECHA_MOVIMIENTO'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_MOVIMIENTO'])));

        $criteria = array('id' => $parameter['ID']);
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


            /* FIX DEUDA */
            $this->load->Model('model_141');
            $this->model_141->fix_anexo141_balance_model($period, $this->sgr_id);

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

    function get_anexo_infox($anexo, $parameter) {


        $headerArr = array("Fecha",
            "N° de Orden de la Garantía Otorgada",
            "CAIDA", "RECUPERO", "INCOBRABLES_PERIODO", "GASTOS_EFECTUADOS_PERIODO", "RECUPERO_GASTOS_PERIODO", "GASTOS_INCOBRABLES_PERIODO");
        $data = array($headerArr);
        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table');
        return $this->table->generate($data);
    }

    function get_anexo_info($anexo, $parameter, $xls = false) {
        /* HEADER TEMPLATE */
        $header_data = array();

        $header = $this->parser->parse('prints/anexo_' . $anexo . '_header', TRUE);
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

    function get_anexo_data($anexo, $parameter, $xls = false) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);

        foreach ($result as $list) {
            /* Vars */
            $this->load->model('padfyj_model');
            $model_12 = 'model_12';
            $this->load->Model($model_12);

            $list_NRO_GARANTIA = trim($list['NRO_GARANTIA']);


            /* "12585/10" */
            $get_movement_data_qry = $this->$model_12->get_order_number_print($list_NRO_GARANTIA, $list['period']);



            if (!empty($get_movement_data_qry)) {
                foreach ($get_movement_data_qry as $warrant) {
                    $cuit = $warrant[5349];
                    $brand_name = $this->padfyj_model->search_name($warrant[5349]);
                }
            }

            $new_list = array();
            $new_list['col1'] = mongodate_to_print($list['FECHA_MOVIMIENTO']);
            $new_list['col2'] = $list_NRO_GARANTIA;
            $new_list['col3'] = $brand_name;
            $new_list['col4'] = $cuit;
            $new_list['col5'] = money_format_custom($list['CAIDA']);
            $new_list['col6'] = money_format_custom($list['RECUPERO']);
            $new_list['col7'] = money_format_custom($list['INCOBRABLES_PERIODO']);
            $new_list['col8'] = money_format_custom($list['GASTOS_EFECTUADOS_PERIODO']);
            $new_list['col9'] = money_format_custom($list['RECUPERO_GASTOS_PERIODO']);
            $new_list['col10'] = money_format_custom($list['GASTOS_INCOBRABLES_PERIODO']);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_anexo_data_clean($anexo, $parameter, $xls = false) {

        $rtn = array();
        $col5 = array();
        $col6 = array();
        $col7 = array();
        $col8 = array();
        $col10 = array();

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);
        foreach ($result as $list) {


            $col5[] = (float) ($list['CAIDA']);
            $col6[] = (float) ($list['RECUPERO']);
            $col7[] = (float) ($list['INCOBRABLES_PERIODO']);
            $col8[] = (float) ($list['GASTOS_EFECTUADOS_PERIODO']);
            $col9[] = (float) ($list['RECUPERO_GASTOS_PERIODO']);
            $col10[] = (float) ($list['GASTOS_INCOBRABLES_PERIODO']);
        }


        $new_list = array();

        $new_list['col1'] = "<strong>TOTALES</strong>";
        $new_list['col2'] = "-";
        $new_list['col3'] = "-";
        $new_list['col4'] = "-";
        $new_list['col5'] = money_format_custom(array_sum($col5));
        $new_list['col6'] = money_format_custom(array_sum($col6));
        $new_list['col7'] = money_format_custom(array_sum($col7));
        $new_list['col8'] = money_format_custom(array_sum($col8));
        $new_list['col9'] = money_format_custom(array_sum($col9));
        $new_list['col10'] = money_format_custom(array_sum($col10));
        $rtn[] = $new_list;

        return $rtn;
    }

    function get_anexo_data_tmp($anexo, $parameter) {

        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $fields = array('FECHA_MOVIMIENTO',
            'NRO_GARANTIA',
            'RECUPERO',
            'INCOBRABLES_PERIODO',
            'GASTOS_EFECTUADOS_PERIODO',
            'RECUPERO_GASTOS_PERIODO',
            'GASTOS_INCOBRABLES_PERIODO', 'filename', 'period', 'sgr_id', 'origin');
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr_tmp->$container->find($query, $fields);

        foreach ($result as $list) {
            $rtn[] = $list;
        }

        return $rtn;
    }

    function get_movement_data($filter) {


        $mongo_date = new MongoDate(strtotime(translate_for_mongo($filter['date'])));
        $endDate = last_month_date($this->session->userdata['period']);


         $suma_query=array(
                'aggregate'=>'container.sgr_anexo_14',
                'pipeline'=>
                  array(
                    array (
                        '$match' => array (
                            'NRO_GARANTIA'  => $filter['warranty'],  
                            'FECHA_MOVIMIENTO' => array(
                                    '$lt' => $mongo_date
                            )
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
                          '_id' => null,
                          'CAIDA' => array('$sum' => '$CAIDA'), 
                          'RECUPERO' => array('$sum' => '$RECUPERO'), 
                          'GASTOS_EFECTUADOS_PERIODO' => array('$sum' => '$GASTOS_EFECTUADOS_PERIODO'), 
                          'GASTOS_INCOBRABLES_PERIODO' => array('$sum' => '$GASTOS_INCOBRABLES_PERIODO'), 
                          'INCOBRABLES_PERIODO' => array('$sum' => '$INCOBRABLES_PERIODO'), 
                          'RECUPERO_GASTOS_PERIODO' => array('$sum' => '$RECUPERO_GASTOS_PERIODO')
                    )),
                ));
        
    
         $suma=$this->sgr_db->command($suma_query); 
         return $suma['result'][0];
    } 

    function get_movement_data_print($nro, $period) {


        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $caida_result_arr = array();
        $recupero_result_arr = array();
        $inc_periodo_arr = array();
        $gasto_efectuado_periodo_arr = array();
        $recupero_gasto_periodo_arr = array();
        $gasto_incobrable_periodo_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period);


        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'NRO_GARANTIA' => $nro
            );

            $movement_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($movement_result as $movement) {
                $caida_result_arr[] = $movement['CAIDA'];
                $recupero_result_arr[] = $movement['RECUPERO'];
                $inc_periodo_arr[] = $movement['INCOBRABLES_PERIODO'];
                $gasto_efectuado_periodo_arr[] = $movement['GASTOS_EFECTUADOS_PERIODO'];
                $recupero_gasto_periodo_arr[] = $movement['RECUPERO_GASTOS_PERIODO'];
                $gasto_incobrable_periodo_arr[] = $movement['GASTOS_INCOBRABLES_PERIODO'];
            }
        }


        $caida_sum = array_sum($caida_result_arr);
        $recupero_sum = array_sum($recupero_result_arr);
        $inc_periodo_sum = array_sum($inc_periodo_arr);
        $gasto_efectuado_periodo_sum = array_sum($gasto_efectuado_periodo_arr);
        $recupero_gasto_periodo_sum = array_sum($recupero_gasto_periodo_arr);
        $gasto_incobrable_periodo_sum = array_sum($gasto_incobrable_periodo_arr);



        $return_arr = array(
            'CAIDA' => $caida_sum,
            'RECUPERO' => $recupero_sum,
            'INCOBRABLES_PERIODO' => $inc_periodo_sum,
            'GASTOS_EFECTUADOS_PERIODO' => $gasto_efectuado_periodo_sum,
            'RECUPERO_GASTOS_PERIODO' => $recupero_gasto_periodo_sum,
            'GASTOS_INCOBRABLES_PERIODO' => $gasto_incobrable_periodo_sum
        );
        return $return_arr;
    }

    function get_movement_data_print_check($nro, $period, $sgr_id) {


        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $caida_result_arr = array();
        $recupero_result_arr = array();
        $inc_periodo_arr = array();
        $gasto_efectuado_periodo_arr = array();
        $recupero_gasto_periodo_arr = array();
        $gasto_incobrable_periodo_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print_check($anexo, $period, $sgr_id);


        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'NRO_GARANTIA' => $nro
            );

            $movement_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($movement_result as $movement) {
                $caida_result_arr[] = $movement['CAIDA'];
                $recupero_result_arr[] = $movement['RECUPERO'];
                $inc_periodo_arr[] = $movement['INCOBRABLES_PERIODO'];
                $gasto_efectuado_periodo_arr[] = $movement['GASTOS_EFECTUADOS_PERIODO'];
                $recupero_gasto_periodo_arr[] = $movement['RECUPERO_GASTOS_PERIODO'];
                $gasto_incobrable_periodo_arr[] = $movement['GASTOS_INCOBRABLES_PERIODO'];
            }
        }


        $caida_sum = array_sum($caida_result_arr);
        $recupero_sum = array_sum($recupero_result_arr);
        $inc_periodo_sum = array_sum($inc_periodo_arr);
        $gasto_efectuado_periodo_sum = array_sum($gasto_efectuado_periodo_arr);
        $recupero_gasto_periodo_sum = array_sum($recupero_gasto_periodo_arr);
        $gasto_incobrable_periodo_sum = array_sum($gasto_incobrable_periodo_arr);



        $return_arr = array(
            'CAIDA' => $caida_sum,
            'RECUPERO' => $recupero_sum,
            'INCOBRABLES_PERIODO' => $inc_periodo_sum,
            'GASTOS_EFECTUADOS_PERIODO' => $gasto_efectuado_periodo_sum,
            'RECUPERO_GASTOS_PERIODO' => $recupero_gasto_periodo_sum,
            'GASTOS_INCOBRABLES_PERIODO' => $gasto_incobrable_periodo_sum
        );


        $sum_arr_values = array_sum($return_arr);
        if ($sum_arr_values != 0)
            return $return_arr;
    }

    function get_tmp_movement_data($filter) {

        
        $container = 'container.sgr_anexo_'.$this->idu.'_tmp';  
        $token = $this->idu;      

         $suma_query=array(
                'aggregate'=>$container,
                'pipeline'=>
                  array(                                          
                    array ('$match' => array (
                        'TOKEN' => $token,
                        'NRO_GARANTIA' => $filter['warranty']
                    )),
                    array('$group' => array(
                          '_id' => null,
                          'CAIDA' => array('$sum' => '$CAIDA'), 
                          'RECUPERO' => array('$sum' => '$RECUPERO'), 
                          'GASTOS_EFECTUADOS_PERIODO' => array('$sum' => '$GASTOS_EFECTUADOS_PERIODO'), 
                          'GASTOS_INCOBRABLES_PERIODO' => array('$sum' => '$GASTOS_INCOBRABLES_PERIODO'), 
                          'INCOBRABLES_PERIODO' => array('$sum' => '$INCOBRABLES_PERIODO'), 
                          'RECUPERO_GASTOS_PERIODO' => array('$sum' => '$RECUPERO_GASTOS_PERIODO')
                    )),
                ));

    
        $suma=$this->sgr_db_tmp->command($suma_query); 
    
        return $suma['result'][0];
        
    } 

    function get_recuperos_tmp($filter, $type) {

        $mongo_date = new MongoDate(strtotime(translate_for_mongo($filter['date'])));

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';
        $token = $this->idu;
        $new_query = array(
            'TOKEN' => $token,
            'NRO_GARANTIA' => $filter['warranty'],
            'FECHA_MOVIMIENTO' => array(
                '$lt' => $mongo_date
            )
        );

        $date_movement_arr = array();

        $movement_result = $this->mongowrapper->sgr_tmp->$container->find($new_query);

        foreach ($movement_result as $movement) {
            if (isset($movement[$type]))
                $date_movement_arr[] = $movement['FECHA_MOVIMIENTO'];
        }
        return $date_movement_arr;
    }

    function get_gastos_tmp($filter, $query_param) {

        $mongo_date = new MongoDate(strtotime(translate_for_mongo($filter['date'])));


        $gasto_efectuado_periodo_arr = array();
        $recupero_gasto_periodo_arr = array();
        $gasto_incobrable_periodo_arr = array();


        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';
        $token = $this->idu;
        $new_query = array(
            'TOKEN' => $token,
            'NRO_GARANTIA' => $filter['warranty'],
            'FECHA_MOVIMIENTO' => array(
                '$lt' => $mongo_date
            )
        );

        if ($query_param == 'RECUPERO_GASTOS_PERIODO')
            $new_query['GASTOS_EFECTUADOS_PERIODO'] = array('$exists' => true);

        $date_movement_arr = array();

        $movement_result = $this->mongowrapper->sgr_tmp->$container->find($new_query);

        foreach ($movement_result as $movement) {
            $gasto_efectuado_periodo_arr[] = $movement['GASTOS_EFECTUADOS_PERIODO'];
            $recupero_gasto_periodo_arr[] = $movement['RECUPERO_GASTOS_PERIODO'];
            $gasto_incobrable_periodo_arr[] = $movement['GASTOS_INCOBRABLES_PERIODO'];
        }

        $gasto_efectuado_periodo_sum = array_sum($gasto_efectuado_periodo_arr);
        $recupero_gasto_periodo_sum = array_sum($recupero_gasto_periodo_arr);
        $gasto_incobrable_periodo_sum = array_sum($gasto_incobrable_periodo_arr);

        $return_arr = array(
            'GASTOS_EFECTUADOS_PERIODO' => $gasto_efectuado_periodo_sum,
            'RECUPERO_GASTOS_PERIODO' => $recupero_gasto_periodo_sum,
            'GASTOS_INCOBRABLES_PERIODO' => $gasto_incobrable_periodo_sum
        );
        return $return_arr;
    }

    function get_caida_tmp($filter) {

        $mongo_date = new MongoDate(strtotime(translate_for_mongo($filter['date'])));

        $caida_result_arr = array();
        $recupero_result_arr = array();
        $inc_periodo_arr = array();


        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';
        $token = $this->idu;
        $new_query = array(
            'TOKEN' => $token,
            'NRO_GARANTIA' => $filter['warranty'],
            'FECHA_MOVIMIENTO' => array(
                '$lt' => $mongo_date
            )
        );

        $date_movement_arr = array();

        $movement_result = $this->mongowrapper->sgr_tmp->$container->find($new_query);

        foreach ($movement_result as $movement) {
            $caida_result_arr[] = (isset($movement['CAIDA'])) ? $movement['CAIDA'] : 0;
            $recupero_result_arr[] = (isset($movement['RECUPERO'])) ? $movement['RECUPERO'] : 0;
            $inc_periodo_arr[] = (isset($movement['INCOBRABLES_PERIODO'])) ? $movement['INCOBRABLES_PERIODO'] : 0;
        }

        $caida_sum = array_sum($caida_result_arr);
        $recupero_sum = array_sum($recupero_result_arr);
        $inc_periodo_sum = array_sum($inc_periodo_arr);

        $return_arr = array(
            'CAIDA' => $caida_sum,
            'RECUPERO' => $recupero_sum,
            'INCOBRABLES_PERIODO' => $inc_periodo_sum
        );
        return $return_arr;
    }

    function nums_guarantees_faced($period, $col) {
        $anexo = $this->anexo;
        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;


        $result = $this->sgr_model->get_active_one($anexo, $period); //exclude actual


        $rtn = array();
        foreach ($result as $each) {

            $new_query = array(
                'filename' => $each['filename']
            );


            $warrants = $this->mongowrapper->sgr->$container->find($new_query);

            foreach ($warrants as $warrant) {
                if ($warrant[$col])
                    $rtn[] = $warrant['NRO_GARANTIA'];
            }
        }
        return (count(array_unique($rtn)));
    }

    function amount_guarantees_faced($period, $col) {
        $anexo = $this->anexo;
        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $period = (isset($period)) ? $period : "01-2014";

        $result = $this->sgr_model->get_active_one($anexo, $period); //exclude actual


        $rtn = array();
        foreach ($result as $each) {

            $new_query = array(
                'filename' => $each['filename']
            );


            $warrants = $this->mongowrapper->sgr->$container->find($new_query);

            foreach ($warrants as $warrant) {
                if ($warrant[$col])
                    $rtn[] = $warrant[$col];
            }
        }

        $sum = array_sum($rtn);
        return $sum;
    }

    /**
     * Nuevo Reporte Anexo 14
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
        $is_custom = false;
        $anexoValues = $this->ui_table_xls();
        
        $headerArr = header_arr($anexo, $is_custom);
        $title_report = $this->sgr_model->get_anexo($anexo);

        
        $data[] = array($headerArr);
        $anexoValues = $this->ui_table_xls();

        if (!$anexoValues) {
            return false;
        } else {
            foreach ($anexoValues['result'] as $values) {               
                $header = '<h2>Reporte '.$anexo.' - '.strtoupper($title_report['title']).' </h2>                
                <h3>SGR: '. $anexoValues['sgr_report'].  '<br>Fecha del Reporte: ' . date("Y/m/d") .'</h3>
                <h4>PERIODO/S: ' . $anexoValues['input_period_from'] . ' a ' . $anexoValues['input_period_to']  . '</h4>';
               
                unset($values['_id']);
                unset($values['id']);
                $data[] = array_values($values);
            }
            $this->load->library('table');
            return $header . $this->table->generate($data);
        }
    }

    function generate_report($parameter=array()) {
        
         if($this->input->post('sgr_checkbox')!==null)
            $sgr_id_array = array_map('intval', $this->input->post('sgr_checkbox'));

        /*REPORT POST VALUES*/        
         switch ($this->input->post('sgr')) {
            case '666':
                $sgr_id = array('$exists'  => true);
                $sgr_report = 'Todas';
            break;

            case '777':
                $sgr_id = array('$in'=>$sgr_id_array);
                $sgr_report = 'Seleccionadas de una lista';
            break;

            default:
                $sgr_id = (float)$this->input->post('sgr');
                $sgr_info = $this->sgr_model->get_sgr_by_id_new($sgr_id );
                $sgr_report = $sgr_info[1693] ." " . $sgr_info[1695];
            break;
        }

        # STANDARD
        $standard_match = array();

        $report_name = $this->input->post('report_name');
        $start_date = first_month_date($this->input->post('input_period_from'));       
        $end_date = last_month_date($this->input->post('input_period_to'));


        $standard_match['sgr_id'] = $sgr_id;   
        $standard_match['status'] = 'activo';
        $standard_match['filename'] = array('$ne'=>'SIN MOVIMIENTOS'); 
        $standard_match['period_date'] = array('$gte' => $start_date, '$lte' => $end_date );
        

        # CUSTOM     
        $custom_match = array('id'=>array('$exists'=> true));
       
        $query = reports_default_query($this->anexo, $this->idu , $standard_match, $custom_match);
        
        $get=$this->sgr_db->command($query); 

        if($this->sgr_model->getReportCount()==0)
             $rtn_msg = array('no_record');  
        else {
            $data=array('input_period_from'=>$this->input->post('input_period_from')
                    , 'input_period_to'=>$this->input->post('input_period_to')
                    , 'anexo'=>$this->anexo
                    , 'sgr_report'=>$sgr_report
                );

            $this->sgr_db->update($this->collection_out,$data);

            $rtn_msg = array('ok');
        }   
             

        echo json_encode($rtn_msg); 
        exit;
    }

     function ui_table_xls(){
        $result =  $this->sgr_db->get($this->collection_out)->result_array();
        foreach ($result as  $period_info) {

            if(isset($period_info['input_period_from']))
                $input_period_from = $period_info['input_period_from'];

            if(isset($period_info['input_period_to']))
                $input_period_to = $period_info['input_period_to'];

            if(isset($period_info['sgr_report']))
                $sgr_report = $period_info['sgr_report'];   

            $list = $period_info['anexo_data'];

            $nro_garantia = trim($list['NRO_GARANTIA']);
            $get_movement_data_qry = $this->model_12->get_order_number_by_sgrid($nro_garantia, $period_info['sgr_id'], $list['period']);
            
            $brand_name = null;
            $cuit = $get_movement_data_qry;
            if(isset($cuit))
                $brand_name = $this->padfyj_model->search_name($cuit);

            $GASTOS_EFECTUADOS_PERIODO = (isset($list['GASTOS_EFECTUADOS_PERIODO'])) ? $list['GASTOS_EFECTUADOS_PERIODO'] : 0;
            $RECUPERO_GASTOS_PERIODO = (isset($list['RECUPERO_GASTOS_PERIODO'])) ? $list['RECUPERO_GASTOS_PERIODO'] : 0;
            $GASTOS_INCOBRABLES_PERIODO = (isset($list['GASTOS_INCOBRABLES_PERIODO'])) ? $list['GASTOS_INCOBRABLES_PERIODO'] : 0;

                
            /* FILENAME */
            $sgr_info = array();
            if(isset($list['filename'])){
                $filename = trim($list['filename']);   
                $sgr_info = $this->sgr_model->get_sgr_by_id_new($period_info['sgr_id']);
            }


            $new_list = array();
            $new_list['col1'] = $sgr_info[1693];
            $new_list['col2'] = $sgr_info[1695];            
            $new_list['col3'] = $list['id'];
            $new_list['col4'] = $list['period'];
            $new_list['col5'] = mongodate_to_print($list['FECHA_MOVIMIENTO']);
            $new_list['col6'] = $list['NRO_GARANTIA'];
            $new_list['col7'] = $brand_name;
            $new_list['col8'] = $cuit;
            $new_list['col9'] = dot_by_coma($list['CAIDA']);
            $new_list['col10'] = dot_by_coma($list['RECUPERO']);
            $new_list['col11'] = dot_by_coma($list['INCOBRABLES_PERIODO']);
            $new_list['col12'] = dot_by_coma($GASTOS_EFECTUADOS_PERIODO);
            $new_list['col13'] = dot_by_coma($RECUPERO_GASTOS_PERIODO);
            $new_list['col14'] = dot_by_coma($GASTOS_INCOBRABLES_PERIODO);
            $new_list['col15'] = $filename;              
            $rtn[] = $new_list;    

        }

        $rtn_array = array(
                'result' => $rtn,
                'input_period_from'=>$input_period_from,
                'input_period_to' =>$input_period_to, 
                'sgr_report' => $sgr_report
            );
        return $rtn_array;
    }

}
