<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_141 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '141';
        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');
        /* SWITCH TO SGR DB */       
        $this->sgr_db=new $this->cimongo;
        #DB
        $this->sgr_db->switch_db('sgr');


        /* LOAD */
        $this->load->model('padfyj_model');
        $this->load->Model('model_06');
        $this->load->Model('model_12');
        $this->load->Model('model_125');
        $this->load->Model('model_14');


        //ini_set("error_reporting", 0);


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
         * @example .... "CUIT_PARTICIPE","CANT_GTIAS_VIGENTES","HIPOTECARIAS","PRENDARIAS","FIANZA"
         * ,"OTRAS","REAFIANZA","MORA_EN_DIAS","CLASIFICACION_DEUDOR"
         * */
        $defdna = array(
            1 => 'CUIT_PARTICIPE',
            2 => 'CANT_GTIAS_VIGENTES',
            3 => 'HIPOTECARIAS',
            4 => 'PRENDARIAS',
            5 => 'FIANZA',
            6 => 'OTRAS',
            7 => 'REAFIANZA',
            8 => 'MORA_EN_DIAS',
            9 => 'CLASIFICACION_DEUDOR'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            /* STRING */
            $insertarr["CUIT_PARTICIPE"] = (string) $insertarr["CUIT_PARTICIPE"];
            /* INTEGERS & FLOAT */
            $insertarr["CANT_GTIAS_VIGENTES"] = (int) $insertarr["CANT_GTIAS_VIGENTES"];
            $insertarr["HIPOTECARIAS"] = (float) $insertarr["HIPOTECARIAS"];
            $insertarr["PRENDARIAS"] = (float) $insertarr["PRENDARIAS"];
            $insertarr["FIANZA"] = (float) $insertarr["FIANZA"];
            $insertarr["OTRAS"] = (float) $insertarr["OTRAS"];
            $insertarr["REAFIANZA"] = (float) $insertarr["REAFIANZA"];
            $insertarr["MORA_EN_DIAS"] = (int) $insertarr["MORA_EN_DIAS"];
            $insertarr["CLASIFICACION_DEUDOR"] = (int) $insertarr["CLASIFICACION_DEUDOR"];



            /* DYNAMIC INFO */
            $this->load->Model('model_125');
            $this->load->Model('model_12');
            $this->load->Model('model_14');

            /* PARTNER DATA */
            $cuit = $insertarr["CUIT_PARTICIPE"];

            $partner_balance = $this->model_125->get_balance_by_partner($cuit, $this->session->userdata['period']);

            $partner_balance_qty = ($partner_balance['count']) ? $partner_balance['count'] : 0;
            $partner_balance_amount = ($partner_balance['balance']) ? $partner_balance['balance'] : 0;

            $col5 = (float) $insertarr['HIPOTECARIAS'];
            $col6 = (float) $insertarr['PRENDARIAS'];
            $col7 = (float) $insertarr['FIANZA'];
            $col8 = (float) $insertarr['OTRAS'];

            $total = array_sum(array($col5, $col6, $col7, $col8));

            $insertarr["MONTO_ADEUDADO"] = 0;
            $insertarr["CANTIDAD_GARANTIAS_AFRONTADAS"] = 0;

            $insertarr["CANTIDAD_GARANTIAS"] = (int) $partner_balance_qty;
            $insertarr["MONTO_GARANTIAS"] = (float) $partner_balance_amount;
            $insertarr["TOTAL"] = (float) $total;
        }

        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter['period'] = $period;
        $parameter['origen'] = "2013";

        $id = $this->app->genid_sgr($container);


        $already_in = $this->find_already_in($parameter['CUIT_PARTICIPE'], $parameter['filename']);

        if (!$already_in) {
            $result = $this->app->put_array_sgr($id, $container, $parameter);

            if ($result) {
                $out = array('status' => 'ok');
            } else {
                $out = array('status' => 'error');
            }
            return $out;
        }
    }

    function find_already_in($cuit, $file) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $query = array("filename" => $file, "CUIT_PARTICIPE" => $cuit);
        $result = $this->mongowrapper->sgr->$container->findOne($query);

        if ($result)
            return true;
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

    function find_141_balance_cuit($cuit, $period, $sgr_id) {        

        $query=array(
                'aggregate'=>'container.sgr_anexo_141.balance',
                'pipeline'=>
                  array(
                      array (
                        '$match' => array (                                
                                 "CUIT_PARTICIPE" => $cuit, "PERIODO" => $period, "sgr_id" => $sgr_id
                            )                        
                      )
                ));   

          $get=$this->sgr_db->command($query);   
          
          if($get['result'][0])
            return $get['result'][0];
                 
    }

    function find_141_balance_sgr() {
        
        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_141.balance';
        $field = array('CUIT_PARTICIPE');
        
        $query = array("PERIODO" => $this->session->userdata['period'],
            "sgr_id" => $this->sgr_id,
            
            'MONTO_ADEUDADO' => array(
                '$gte' => 2 //#26407
            ),
            
            'CANTIDAD_GARANTIAS_AFRONTADAS' => array(
                '$gte' => 1
            ));

        $result = $this->mongowrapper->sgr->$container->find($query, $field);        
        
       

        foreach ($result as $each)
            $rtn[] = $each['CUIT_PARTICIPE'];
            
        return $rtn;
    }

    function remove_141_balance($sgr_id, $period) {

        $container = 'container.sgr_anexo_141.balance';
        $parameter = array(
            'PERIODO' => $period,
            'sgr_id' => (float) $sgr_id
        );
        $rs = $this->mongowrapper->sgr->$container->remove($parameter);
    }

    function update_141_balance($sgr_id, $cuit, $qty, $amount, $period) {

        $id = $cuit . str_replace("-20", "", $period) . $sgr_id;

        // var_dump($id, (double) $id,  (int) $id);

        $options = array('upsert' => true, 'w' => 1);
        $container = 'container.sgr_anexo_141.balance';
        $query = array('id' => $id);
        $parameter = array(
            'CUIT_PARTICIPE' => $cuit,
            'CANTIDAD_GARANTIAS_AFRONTADAS' => $qty,
            'MONTO_ADEUDADO' => $amount,
            'PERIODO' => $period,
            'sgr_id' => (float) $sgr_id
        );
        $rs = $this->mongowrapper->sgr->$container->update($query, array('$set' => $parameter), $options);


        return $rs['err'];
    }

    function update_partner_balance($filter) {

        $options = array('upsert' => true, 'w' => 1);
        $container_141 = 'container.sgr_anexo_141';
        $query_141 = array(
            'id' => (float) $filter['ID']
        );

        $parameter = array(
            'MONTO_ADEUDADO' => $filter['BALANCE'], 'CANTIDAD_GARANTIAS_AFRONTADAS' => $filter['QTY']
        );

        $rs = $this->mongowrapper->sgr->$container_141->update($query_141, array('$set' => $parameter), $options);

        return $rs['err'];
    }


    function garantias_balance_by_cuit($cuit){       
       
        $query=array(
                'aggregate'=>'container.sgr_anexo_12',
                'pipeline'=>
                  array(
                      array (
                        '$match' => array (
                             
                                 "5349"=>$cuit
                             
                            )                        
                      ),  
                      array (
                        '$lookup' => array (                             
                                  "from" => "container.sgr_periodos",
                                  "localField" => "filename",
                                  "foreignField" => "filename",
                                  "as" => "periodo"                             
                            )                        
                      ) ,
                      array (
                        '$match' => array (                             
                                 "periodo.status"=>"activo","periodo.anexo"=>"12","periodo.sgr_id"=>$this->sgr_id

                             
                            )                        
                      )                                  

                ));          
        
        #var_dump(json_encode($query)); exit;
        $get=$this->sgr_db->command($query);  
        #echo $cuit ."<br>";
        $result = $this->anexo14_balance_by_cuit($get['result']);       
        return $result;
    }

    function anexo14_balance_by_cuit($data){

            $movement_date = first_month_date($this->session->userdata['period']);
            
            $r14 = array();
            
            foreach ($data as $value) {
              
                $r14[] = $value['5214'];
            }


            # code...
             $query=array(
                'aggregate'=>'container.sgr_anexo_14',
                'pipeline'=>
                  array(
                      array (
                        '$match' => array (                                
                                 "NRO_GARANTIA"=>array('$in'=>$r14),
                                 'FECHA_MOVIMIENTO' => array('$lte' => $movement_date),  

                            )                        
                      ),  
                      array (
                        '$lookup' => array (                             
                                  "from" => "container.sgr_periodos",
                                  "localField" => "filename",
                                  "foreignField" => "filename",
                                  "as" => "periodo"                             
                            )                        
                      ) ,
                      array (
                        '$match' => array (                             
                                 "periodo.status"=>"activo","periodo.anexo"=>"14","periodo.sgr_id"=>$this->sgr_id                              
                            )                        
                      ),
                      array (
                        '$group' => array (  
                                  '_id'=> null,                              
                                 'GASTOS_EFECTUADOS_PERIODO'=> array('$sum'=> '$GASTOS_EFECTUADOS_PERIODO' ),
                                 'GASTOS_INCOBRABLES_PERIODO'=> array('$sum'=>  '$GASTOS_INCOBRABLES_PERIODO' ),
                                 'INCOBRABLES_PERIODO'=> array('$sum'=>  '$INCOBRABLES_PERIODO'),
                                 'RECUPERO'=> array('$sum'=>  '$RECUPERO'),
                                 'CAIDA'=> array('$sum'=>  '$CAIDA'),
                                 'RECUPERO_GASTOS_PERIODO'=> array('$sum'=>  '$RECUPERO_GASTOS_PERIODO')                                 
                            )                        
                      )                                  

                ));          
        

        
        $get=$this->sgr_db->command($query);          

        $get_movement_data = $get['result'][0];

        $sum1 = ($get_movement_data['CAIDA'] - $get_movement_data['RECUPERO']) - $get_movement_data['INCOBRABLES_PERIODO'];
        $sum2 = ($get_movement_data['GASTOS_EFECTUADOS_PERIODO'] - $get_movement_data['RECUPERO_GASTOS_PERIODO']) - $get_movement_data['GASTOS_INCOBRABLES_PERIODO'];
        
        return  bccomp($sum1, $sum2);



    }

    function fix_anexo141_balance_model($period, $sgr_id, $debug = null, $get_cuits_array = null) {
        $debug = false;

        /* DYNAMIC INFO */

        $this->load->Model('model_125');
        $this->load->Model('model_12');
        $this->load->Model('model_14');
        $this->load->Model('model_141');


        $container_periodos = 'container.sgr_periodos';
        $status = 'activo';

        $fields = array('anexo', 'period', 'status', 'filename', 'id', 'sgr_id');
        $query = array(
            'anexo' => $this->anexo,
            'period' => $period,
            'status' => $status,
            'origen' => '2013' //???
        );


        if ($sgr_id != '666')
            $query['sgr_id'] = (float) $sgr_id;

        
        
        /* ACTIVE FILENAMES */
    
            
            $period_data = $this->mongowrapper->sgr->$container_periodos->findOne($query, $fields);
            $cuits_array = $this->model_141->get_cuits_by_filename($period_data['filename']);
                
    
        if (isset($debug)) {
            /* ALL OF THEM1 */
            $cuits_array = $this->model_12->get_sharer_all($period, $sgr_id);

        } else {
            //        
            
        }

        //$cuits_array = array('30708717319');

        /* REMOVE LATEST */
        $this->remove_141_balance($sgr_id, $period);


        /* GET ALL WARRANTIES BY PARTNER */
        foreach ($cuits_array as $each_cuit) {

            $sum_all_warranties = array();
            $get_warranty_partner = $this->model_12->get_warranty_partner_print_check($each_cuit, $period, $sgr_id);

            foreach ($get_warranty_partner as $get) {

                $nro_orden = $get[5214];
                $cuit_participe = $get[5349];

                $caida_result_arr = array();
                $recupero_result_arr = array();
                $inc_periodo_arr = array();
                $gasto_efectuado_periodo_arr = array();
                $recupero_gasto_periodo_arr = array();
                $gasto_incobrable_periodo_arr = array();


                $get_movement_data = @$this->model_14->get_movement_data_print_check($nro_orden, $period, $sgr_id);
                if ($get_movement_data) {

                    $all_arr = array(
                        $get_movement_data['CAIDA'],
                        $get_movement_data['RECUPERO'],
                        $get_movement_data['INCOBRABLES_PERIODO']
                    );

                    $sum1 = ($get_movement_data['CAIDA'] - $get_movement_data['RECUPERO']) - $get_movement_data['INCOBRABLES_PERIODO'];
                    $sum2 = ($get_movement_data['GASTOS_EFECTUADOS_PERIODO'] - $get_movement_data['RECUPERO_GASTOS_PERIODO']) - $get_movement_data['GASTOS_INCOBRABLES_PERIODO'];

                    //   var_dump($nro_orden, $get_movement_data['CAIDA'], $get_movement_data['RECUPERO'], $sum1);
                    $sum_all = array_sum(array($sum1, $sum2));

                    if ($sum_all != 0)
                        $sum_all_warranties[] = (int)$sum_all;
                }
            }
            
            //var_dump($period_data);


            $saldo = array_sum($sum_all_warranties);
            $qty = ($saldo > 0) ? count($sum_all_warranties) : 0;

            $update_arr = array("SGR_ID" => $sgr_id,
                "PARTNER" => $cuit_participe,
                "QTY" => $qty,
                "BALANCE" => $saldo,
                "FILENAME" => $period_data['filename']
            );

            /* GET STORED INFO */
            $data = $this->get_data_by_filename($update_arr);


            $update_arr_id = array(
                'ID' => $data['id']
            );

            $merged_arr = array_merge($update_arr, $update_arr_id);

            /* UPDATE */
            $this->update_141_balance($sgr_id, $cuit_participe, $qty, $saldo, $period);

            $this->model_141->update_partner_balance($merged_arr);

            /*if (isset($debug))
                var_dump($merged_arr);*/
        }
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

    function get_last_period_data($period) {

        $container = 'container.sgr_anexo_' . $this->anexo;
        $rs = $this->sgr_model->get_active_last_rec($this->anexo, $period);


        $query = array("filename" => $rs[0]['filename']);
        $results = $this->mongowrapper->sgr->$container->find($query);

        $cuits_array = array();
        foreach ($results as $result) {
            $cuits_array[] = $result['CUIT_PARTICIPE'];
        }
        return $cuits_array;
    }

    function get_data_by_filename($parameter) {

        $container = 'container.sgr_anexo_' . $this->anexo;
        $query = array("filename" => $parameter['FILENAME'], "CUIT_PARTICIPE" => $parameter['PARTNER']);
        $result = $this->mongowrapper->sgr->$container->findOne($query);

        return $result;
    }

    function get_cuits_by_filename($parameter) {

        $container = 'container.sgr_anexo_' . $this->anexo;
        $field = array('CUIT_PARTICIPE', 'MONTO_ADEUDADO', 'filename', 'id');
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query, $field);

        $arr_cuits = array();
        foreach ($result as $cuit) {
            $arr_cuits[] = $cuit['CUIT_PARTICIPE'];
        }


        $arr_cuits = array_unique($arr_cuits);

        return $arr_cuits;
    }

    function get_anexo_data($anexo, $parameter, $xls = false) {


        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);

        foreach ($result as $list) {

            /*
             * Vars                                 
             */
            $this->load->model('padfyj_model');

            /* PARTNER DATA */
            $cuit = $list["CUIT_PARTICIPE"];
            $brand_name = $this->padfyj_model->search_name($list["CUIT_PARTICIPE"]);

            /* DEUDA SOCIO */
            if($list['sgr_id'])
                $balance_data = $this->find_141_balance_cuit($cuit, $list['period'], $list['sgr_id']);


            $col3 = $list['CANT_GTIAS_VIGENTES'];
            $col4 = $list['MONTO_GARANTIAS'];

            $col5 = $list['HIPOTECARIAS'];
            $col6 = $list['PRENDARIAS'];
            $col7 = $list['FIANZA'];
            $col8 = $list['OTRAS'];
            $col9 = $list['TOTAL'];
            $col10 = $list['REAFIANZA'];
            $col11 = $balance_data['MONTO_ADEUDADO'];
            $col12 = $balance_data['CANTIDAD_GARANTIAS_AFRONTADAS'];

            $new_list = array();
            $new_list['col1'] = $cuit;
            $new_list['col2'] = $brand_name;
            if ($xls) {
                $new_list['col3'] = $col3;
                $new_list['col4'] = money_format_xls($col4);
                $new_list['col5'] = money_format_xls($col5);
                $new_list['col6'] = money_format_xls($col6);
                $new_list['col7'] = money_format_xls($col7);
                $new_list['col8'] = money_format_xls($col8);
                $new_list['col9'] = money_format_xls($col9);
                $new_list['col10'] = money_format_xls($col10);
                $new_list['col11'] = money_format_xls($col11);
                $new_list['col12'] = $col12;
            } else {
                $new_list['col3'] = $col3;
                $new_list['col4'] = money_format_custom($col4);
                $new_list['col5'] = money_format_custom($col5);
                $new_list['col6'] = money_format_custom($col6);
                $new_list['col7'] = money_format_custom($col7);
                $new_list['col8'] = money_format_custom($col8);
                $new_list['col9'] = money_format_custom($col9);
                $new_list['col10'] = money_format_custom($col10);
                $new_list['col11'] = money_format_custom($col11);
                $new_list['col12'] = $col12;
            }
            $new_list['col13'] = $list['MORA_EN_DIAS'];
            $new_list['col14'] = $list['CLASIFICACION_DEUDOR'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_anexo_ddjj($period) {


        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongowrapper->sgr->$container->find($query);

        $partners_arr = array();

        foreach ($result as $list) {

            if ($list['CANT_GTIAS_VIGENTES'])
                $partners_arr[] = $list['CUIT_PARTICIPE'];
        }


        //debug(count(($partners_arr)));
        return $rtn;
    }

    function get_anexo_data_clean($anexo, $parameter, $xls = false) {

        $rtn = array();
        $col3 = array();
        $col4 = array();
        $col5 = array();
        $col6 = array();
        $col7 = array();
        $col8 = array();
        $col10 = array();
        $col11 = array();
        $col12 = array();

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {

            /* DEUDA SOCIO */
            $balance_data = $this->find_141_balance_cuit($list['CUIT_PARTICIPE'], $list['period'], $list['sgr_id']);

            $col3_val = $list['CANT_GTIAS_VIGENTES'];
            $col4_val = $list['MONTO_GARANTIAS'];

            $col5_val = $list['HIPOTECARIAS'];
            $col6_val = $list['PRENDARIAS'];
            $col7_val = $list['FIANZA'];
            $col8_val = $list['OTRAS'];
            $col9_val = $list['TOTAL'];
            $col10_val = $list['REAFIANZA'];
            $col11_val = $balance_data['MONTO_ADEUDADO'];
            $col12_val = $balance_data['CANTIDAD_GARANTIAS_AFRONTADAS'];

            $col3[] = $col3_val;
            $col4[] = (float) $col4_val;
            $col5[] = (float) $col5_val;
            $col6[] = (float) $col6_val;
            $col7[] = (float) $col7_val;
            $col8[] = (float) $col8_val;
            $col9[] = (float) $col9_val;
            $col10[] = (float) $col10_val;
            $col11[] = (float) $col11_val;
            $col12[] = (float) $col12_val;
        }


        $new_list = array();

        $new_list['col1'] = "<strong>TOTALES</strong>";
        $new_list['col2'] = "-";
        if ($xls) {
            $new_list['col3'] = array_sum($col3);
            $new_list['col4'] = money_format_xls(array_sum($col4));
            $new_list['col5'] = money_format_xls(array_sum($col5));
            $new_list['col6'] = money_format_xls(array_sum($col6));
            $new_list['col7'] = money_format_xls(array_sum($col7));
            $new_list['col8'] = money_format_xls(array_sum($col8));
            $new_list['col9'] = money_format_xls(array_sum($col9));
            $new_list['col10'] = money_format_xls(array_sum($col10));
            $new_list['col11'] = money_format_xls(array_sum($col11));
            $new_list['col12'] = array_sum($col12);
        } else {
            $new_list['col3'] = array_sum($col3);
            $new_list['col4'] = money_format_custom(array_sum($col4));
            $new_list['col5'] = money_format_custom(array_sum($col5));
            $new_list['col6'] = money_format_custom(array_sum($col6));
            $new_list['col7'] = money_format_custom(array_sum($col7));
            $new_list['col8'] = money_format_custom(array_sum($col8));
            $new_list['col9'] = money_format_custom(array_sum($col9));
            $new_list['col10'] = money_format_custom(array_sum($col10));
            $new_list['col11'] = money_format_custom(array_sum($col11));
            $new_list['col12'] = array_sum($col12);
        }


        $new_list['col13'] = "-";
        $new_list['col14'] = "-";
        $rtn[] = $new_list;


        return $rtn;
    }

    function partners_debtors_to_top($period) {
        $anexo = $this->anexo;
        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;


        $result = $this->sgr_model->get_active_one($anexo, period_before($period)); //exclude actual


        $rtn = array();
        foreach ($result as $each) {

            $new_query = array(
                'filename' => $each['filename']
            );


            $partners = $this->mongowrapper->sgr->$container->find($new_query);

            foreach ($partners as $partner) {
                if ($partner['MORA_EN_DIAS'])
                    $rtn[] = $partner['CUIT_PARTICIPE'];
            }
        }
        return (count(array_unique($rtn)));
    }

    function partners_debtors_to_end($period) {
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


            $partners = $this->mongowrapper->sgr->$container->find($new_query);

            foreach ($partners as $partner) {
                if ($partner['MORA_EN_DIAS'])
                    $rtn[] = $partner['CUIT_PARTICIPE'];
            }
        }
        return (count(array_unique($rtn)));
    }
   

    /* REPORT */

    function get_anexo_debtors($parameter) {


        /* HEADER TEMPLATE */
        $header_data = array();
        $header = $this->parser->parse('central/form_' . $this->anexo . '_debtors_header', $header_data, TRUE);
        $tmpl = array('data' => $header);

        $data = array($tmpl);

        $anexoValues = $this->get_debtors_data($this->anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table_custom');
        $newTable = $this->table_custom->generate($data);

        return $newTable;
    }

    function get_debtors_data($anexo, $parameter) {

        if (!isset($parameter)) {
            return false;
            exit();
        }

        $container = 'container.sgr_anexo_' . $anexo;

        $cuit_socio = (isset($parameter['cuit_sharer'])) ? $parameter['cuit_sharer'] : null;

        if (isset($cuit_socio))
            $query = array('CUIT_PARTICIPE' => $cuit_socio);

        $result_arr = $this->mongowrapper->sgr->$container->find($query);

        /* TABLE DATA */
        return $this->ui_table_debtors($result_arr, $anexo, $input_period_to, $parameter);
    }

    function ui_table_debtors($result, $anexo = null, $end_date, $parameter) {

        /* CSS 4 CENTRAL */
        css_central_fn();

        $list = null;
        $this->sgr_model->del_tmp_general();

        $e = 0;
        $f = 0;
        $g = 0;
        $h = 0;
        $i = 0;


        foreach ($result as $list) {

            /* CHECK FOR RECTIFICATIONS */
            $is_active = $this->sgr_model->check_if_active($list['filename'], $list['sgr_id']);

            if ($is_active) {
                /* Vars */
                $this->load->model('padfyj_model');
                $this->load->Model('model_06');
                $cuit = $list['CUIT_PARTICIPE'];

                /* Amount of Guarantees */
                $check_14 = $this->sgr_model->get_active_each_sgrid_with_limit('14', $list['sgr_id'], $end_date);

                $partner_balance = $this->model_125->get_balance_by_partner($cuit, $end_date);

                $brand_name = $this->padfyj_model->search_name($cuit);
                if (!isset($brand_name)) {
                    $brand_name_get = $this->model_06->get_partner_name($cuit);
                    $brand_name = $brand_name_get;
                }

                $get_period_filename = $this->sgr_model->get_period_filename($list['filename']);

                $sgr_info = $this->sgr_model->get_sgr_by_id_new($get_period_filename['sgr_id']);

                /* DEUDA SOCIO */
                $balance_data = $this->find_141_balance_cuit($cuit, $get_period_filename['period'], $list['sgr_id']);

                $CANTIDAD_GARANTIAS_AFRONTADAS = isset($balance_data['CANTIDAD_GARANTIAS_AFRONTADAS']) ? $balance_data['CANTIDAD_GARANTIAS_AFRONTADAS'] : 0;

                $new_list = array();               
                $new_list['c'] = $sgr_info[1693];
                $new_list['d'] = period_print_format($get_period_filename['period']);
                $new_list['e'] = money_format_custom($list['MONTO_GARANTIAS']);
                $new_list['f'] = money_format_custom($balance_data['MONTO_ADEUDADO']);
                $new_list['g'] = $CANTIDAD_GARANTIAS_AFRONTADAS;
                $new_list['h'] = $list['CLASIFICACION_DEUDOR'];
                $new_list['i'] = $list['MORA_EN_DIAS'];
                /* ARRAY FOR RENDER */
                if (!empty($new_list))
                    $rtn[] = $new_list;


                /* TOTALES */
                $e += $list['MONTO_GARANTIAS'];
                $f += $balance_data['MONTO_ADEUDADO'];
                $g += $CANTIDAD_GARANTIAS_AFRONTADAS;

                if ($list['CLASIFICACION_DEUDOR'] > $h)
                    $h = $list['CLASIFICACION_DEUDOR'];

                if ($list['MORA_EN_DIAS'] > $i)
                    $i = $list['MORA_EN_DIAS'];
            }
        }

        $rtn['1000'] = array("a" => "<strong>TOTAL</strong>",            
            "d" => null,            
            "e" => "<strong>" . money_format_custom($e) . "</strong>",
            "f" => "<strong>" . money_format_custom($f) . "</strong>",
            "g" => "<strong>" . $g . "</strong>",
            "h" => "<strong>" . $h . "</strong>",
            "i" => "<strong>" . $i . "</strong>",
        );

        return $rtn;
    }

     /**
     * Nuevo Reporte Anexo 06
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
        $query=array(
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
                            'as' => 'anexo_data')                        
                    ),
                    array (
                        '$match' => array (
                            'anexo_data.CUIT_PARTICIPE'=> $cuit                          
                    )                       
                )        
            )     
        );    

        $get=$this->sgr_db->command($query);        
        $this->ui_table_xls($get['result'], $this->anexo, $parameter, $end_date);
    }

    function ui_table_xls($result, $anexo = null, $parameter, $end_date) { 

        #custom
        $rtn_msg = array('no_record');
        
        $list = null;
        
        $this->sgr_model->del_tmp_general();
        
        foreach ($result as $period_info) {
        
            foreach ($period_info['anexo_data'] as $list) {
                
                /* Vars */
                $new_list = array();

                $this->load->model('padfyj_model');
                $this->load->Model('model_06');
                $cuit = $list['CUIT_PARTICIPE'];

                /* Amount of Guarantees */
                $check_14 = $this->sgr_model->get_active_each_sgrid_with_limit('14', $list['sgr_id'], $end_date);

                $partner_balance = $this->model_125->get_balance_by_partner($cuit, $end_date);

                $brand_name = $this->padfyj_model->search_name($cuit);
                if (!isset($brand_name)) {
                    $brand_name_get = $this->model_06->get_partner_name($cuit);
                    $brand_name = $brand_name_get;
                }

                //$existing_guarantees_amount 
                $total = array_sum(array($list['HIPOTECARIAS'], $list['PRENDARIAS'], $list['FIANZA'], $list['OTRAS']));
                

                /* DEUDA SOCIO */
                $balance_data = $this->find_141_balance_cuit($cuit, $period_info['period'], $list['sgr_id']);   
                
                /* FILENAME */
                $sgr_info = array();
                if(isset($period_info['filename'])){
                    $filename = trim($list['filename']);   
                    
                    $sgr_info = $this->sgr_model->get_sgr_by_id_new($period_info['sgr_id']);
                }

                $new_list = array();
                $new_list['col0'] = $sgr_info[1693];
                $new_list['col1'] = $sgr_info[1695];            
                $new_list['col2'] = $list['id'];
                $new_list['col3'] = period_print_format($period_info['period']);
                $new_list['col4'] = $cuit;
                $new_list['col5'] = $brand_name;
                $new_list['col6'] = $list['CANT_GTIAS_VIGENTES'];
                $new_list['col7'] = dot_by_coma($list['MONTO_GARANTIAS']);
                $new_list['col8'] = dot_by_coma($list['HIPOTECARIAS']);
                $new_list['col9'] = dot_by_coma($list['PRENDARIAS']);
                $new_list['col10'] = dot_by_coma($list['FIANZA']);
                $new_list['col11'] = dot_by_coma($list['OTRAS']);
                $new_list['col12'] = dot_by_coma($total);
                $new_list['col13'] = dot_by_coma($list['REAFIANZA']);
                $new_list['col14'] = $list['MORA_EN_DIAS'];
                $new_list['col15'] = $list['CLASIFICACION_DEUDOR'];
                $new_list['col16'] = dot_by_coma($balance_data['MONTO_ADEUDADO']);
                $new_list['col17'] = $balance_data['CANTIDAD_GARANTIAS_AFRONTADAS'];
                $new_list['col18'] = $list['CANTIDAD_GARANTIAS'];
                $new_list['col19'] = $filename;
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