<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_12 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '12';
        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');
        /* SWITCH TO SGR DB */
        $this->load->library('cimongo/Cimongo.php', '', 'sgr_db');
        $this->sgr_db->switch_db('sgr');

        /*TMP report*/
        $this->collection_out = "collection_out_" . $this->idu;



        /* LOAD MODELS */
        $this->load->model('padfyj_model');
        $this->load->model('model_06');
        $this->load->model('model_062');
        $this->load->model('sgr_model');
        $this->load->model('app');

        /* base variables */
        $this->base_url = base_url();
        $this->module_url_report = base_url() . 'sgr/reports';

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
         * @example NRO	CUIT_PARTICIPE	ORIGEN	TIPO	IMPORTE	MONEDA	LIBRADOR_NOMBRE	LIBRADOR_CUIT	NRO_OPERACION_BOLSA	ACREEDOR	CUIT_ACREEDOR	IMPORTE_CRED_GARANT	MONEDA_CRED_GARANT	TASA	PUNTOS_ADIC_CRED_GARANT	PLAZO	GRACIA	PERIODICIDAD	SISTEMA	DESTINO_CREDITO
         * */
        $defdna = array(
            1 => 5214, //"Nro",
            2 => 5349, //"Cuit_participe",              
            3 => 5215, //"Origen",                      
            4 => 5216, //"Tipo",
            5 => 5218, //"Importe",
            6 => 5219, //"Moneda",
            7 => 5725, //"Librador_nombre",
            8 => 5726, //"Librador_cuit",
            9 => 5727, //"Nro_operacion_bolsa",
            10 => 5350, //"Acreedor",
            11 => 5351, //"Cuit_acreedor",            
            12 => 5221, //"Importe_Cred_Garant",
            13 => 5758, //"Moneda_Cred_Garant",
            14 => 5222, //"Tasa",
            15 => 5223, //"Puntos_adic_Cred_Garantizado",
            16 => 5224, //"Plazo",  
            17 => 5225, //"Gracia",
            18 => 5226, //"Period.",
            19 => 5227, //"Sistema",
            20 => 'DESTINO_CREDITO' //"Tipo_Contragarantia"
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            /* STRING */
            $insertarr[5214] = (string) $insertarr[5214]; //Nro orden


            $insertarr[5349] = (string) $insertarr[5349]; //Cuit_participe
            $insertarr[5726] = (string) $insertarr[5726]; //Librador_cuit           
            $insertarr[5351] = (string) $insertarr[5351]; //Acreedir

            $insertarr[5216] = (string) trim(strtoupper($insertarr[5216])); //Tipo           


            /* FLOAT */
            $insertarr[5218] = (float) $insertarr[5218];
            $insertarr[5221] = (float) $insertarr[5221];
            $insertarr[5223] = (float) $insertarr[5223];

            /* INTEGER */
            $insertarr[5224] = (int) $insertarr[5224];
            $insertarr[5225] = (int) $insertarr[5225];

            /* MONEDA */
            if (strtoupper(trim($insertarr[5219])) == "PESOS ARGENTINOS")
                $insertarr[5219] = "1";
            if (strtoupper(trim($insertarr[5219])) == "DOLARES AMERICANOS")
                $insertarr[5219] = "2";
            if (strtoupper(trim($insertarr[5219])) == "EUROS")
                $insertarr[5219] = "3";
            if (strtoupper(trim($insertarr[5219])) == "REALES")
                $insertarr[5219] = "4";

            if (strtoupper($insertarr[5758]) == "PESOS ARGENTINOS")
                $insertarr[5758] = "1";
            if (strtoupper($insertarr[5758]) == "DOLARES AMERICANOS")
                $insertarr[5758] = "2";
            if (strtoupper($insertarr[5758]) == "EUROS")
                $insertarr[5758] = "3";
            if (strtoupper($insertarr[5758]) == "REALES")
                $insertarr[5758] = "4";



            /* PERIODICIDAD */
            if (strtoupper(trim($insertarr[5226])) == "PAGO UNICO")
                $insertarr[5226] = "1";
            if (strtoupper(trim($insertarr[5226])) == "MENSUAL")
                $insertarr[5226] = "30";
            if (strtoupper(trim($insertarr[5226])) == "BIMESTRAL")
                $insertarr[5226] = "60";
            if (strtoupper(trim($insertarr[5226])) == "TRIMESTRAL")
                $insertarr[5226] = "90";
            if (strtoupper(trim($insertarr[5226])) == "CUATRIMESTRAL")
                $insertarr[5226] = "120";
            if (strtoupper(trim($insertarr[5226])) == "SEMESTRAL")
                $insertarr[5226] = "180";
            if (strtoupper(trim($insertarr[5226])) == "ANUAL")
                $insertarr[5226] = "360";
            if (strtoupper(trim($insertarr[5226])) == "OTRO")
                $insertarr[5226] = "04";

            /* SISTEMA */
            if (strtoupper(trim($insertarr[5227])) == "PAGO UNICO")
                $insertarr[5227] = "01";
            if (strtoupper(trim($insertarr[5227])) == "FRANCES")
                $insertarr[5227] = "02";
            if (strtoupper(trim($insertarr[5227])) == "ALEMAN")
                $insertarr[5227] = "03";
            if (strtoupper(trim($insertarr[5227])) == "OTRO")
                $insertarr[5227] = "04";



            /* TASA */
            if (strtoupper($insertarr[5222]) == "LIBOR")
                $insertarr[5222] = "01";
            if (strtoupper($insertarr[5222]) == "BADLARPU")
                $insertarr[5222] = "02";
            if (strtoupper($insertarr[5222]) == "BADLARPR")
                $insertarr[5222] = "03";
            if (strtoupper($insertarr[5222]) == "FIJA")
                $insertarr[5222] = "04";
            if (strtoupper($insertarr[5222]) == "TEBP")
                $insertarr[5222] = "05";
            if (strtoupper($insertarr[5222]) == "TEC")
                $insertarr[5222] = "06";
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        /* FIX DATE */
        list($arr['Y'], $arr['m'], $arr['d']) = explode("-", strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter[5215], 1900)));
        $parameter[5215] = $arr;

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

    function get_anexo_info($anexo, $parameter) {
        /* HEADER TEMPLATE */
        $header_data = array();

        $header = $this->parser->parse('prints/anexo_' . $anexo . '_header', TRUE);
        $tmpl = array('data' => $header);

        $data = array($tmpl);

        $anexoValues = $this->get_anexo_data($anexo, $parameter);
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
        $fields = array('5214',
            '5349',
            '5215',
            '5216',
            '5218',
            '5219',
            '5726',
            '5727',
            '5351',
            '5221',
            '5758',
            '5222',
            '5223',
            '5224',
            '5225',
            '5226',
            '5227', 'filename', 'period', 'sgr_id', 'origin');
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query, $fields);

        foreach ($result as $list) {
            $rtn[] = $list;
        }

        return $rtn;
    }

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);



        foreach ($result as $list) {


            /* Vars */
            $new_list = array();

            $participate = $this->padfyj_model->search_name($list[5349]);
            $drawer = $this->padfyj_model->search_name((string) $list[5726]);



            /*  CREDITOR NAME */
            $creditor_mv = $this->get_mv_and_comercial_name($list[5351]);
            $creditor_padfyj = $this->padfyj_model->search_name($list[5351]);
            $creditor = ($creditor_mv) ? $creditor_mv : $creditor_padfyj;



            $warranty_type = $this->app->get_ops(525);
            $currency = $this->app->get_ops(549);
            $repayment_system = $this->app->get_ops(527);
            $rate = $this->app->get_ops(526);
            $periodicity = $this->app->get_ops(548);

            /* PONDERACION */
            $get_weighting = $this->sgr_model->get_warranty_type($list[5216][0], $list['period']);
            $warranty_type_value = ($warranty_type[$list[5216][0]]) ? $warranty_type[$list[5216][0]] : $list[5216][0];

            $new_list['NRO'] = $list[5214];
            $new_list['PARTICIPE'] = $participate;
            $new_list['CUIT_PARTICIPE'] = $list[5349];
            $new_list['ORIGEN'] = $list[5215];
            $new_list['TIPO'] = $warranty_type_value;
            $new_list['PONDERACION'] = $get_weighting['weighted'] * 100;
            $new_list['IMPORTE'] = money_format_custom($list[5218]);
            $new_list['MONEDA'] = $currency[$list[5219][0]];
            $new_list['LIBRADOR_NOMBRE'] = $drawer;
            $new_list['LIBRADOR_CUIT'] = $list[5726];
            $new_list['NRO_OPERACION_BOLSA'] = $list[5727];
            $new_list['ACREEDOR'] = $creditor;
            $new_list['CUIT_ACREEDOR'] = $list[5351];
            $new_list['IMPORTE_CRED_GARANT'] = $list[5221];
            $new_list['MONEDA_CRED_GARANT'] = $currency[$list[5758][0]];
            $new_list['TASA'] = $rate[$list[5222][0]];
            $new_list['PUNTOS_ADIC_CRED_GARANT'] = $list[5223];
            $new_list['PLAZO'] = $list[5224];
            $new_list['GRACIA'] = $list[5225];
            $new_list['PERIODICIDAD'] = $periodicity[$list[5226][0]];
            $new_list['SISTEMA'] = $repayment_system[$list[5227][0]];
            $new_list['DESTINO_CREDITO'] = $list['DESTINO_CREDITO'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    //container.sgr_cuits_comerciales_y_mv
    function get_mv_and_comercial_name($cuit) {

        $container = 'container.sgr_cuits_comerciales_y_mv';
        $query = array("cuit" => $cuit);
        $result = $this->mongowrapper->sgr->$container->findOne($query);
        if ($result)
            return $result['name'];
    }

    function get_mv_and_comercial_cuits($cuit, $type) {

        $container = 'container.sgr_cuits_comerciales_y_mv';
        $query = array("cuit" => $cuit);
        $result = $this->mongowrapper->sgr->$container->findOne($query);

        return $result['type'];
    }

    /* GET DATA */

    function get_order_number($nro) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );

            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    function get_period_amount($period_value) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_12';

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_period_data($anexo, $period_value);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename']
            );
            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $each) {
                $return_result[] = $each[5218];
            }
        }
        $average = array_sum($return_result) / count($return_result);
        return $average;
    }

    /* GET DATA */

    function get_order_number_left($nro) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];



        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );
            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    function get_order_number_print($nro, $period_date) {
        $anexo = $this->anexo;

        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period_date);

        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );
            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    function get_order_number_period($nro, $period_date) {
        $anexo = $this->anexo;

        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_one($anexo, $period_date);

        /* FIND ANEXO */
        foreach ($result as $list) {

            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );
            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    function get_order_number_by_sgrid($nro, $sgr_id, $period) {

        $end_date = last_month_date($period);

        /*QUERY*/       
        $querys =array(
            'aggregate'=>'container.sgr_periodos',
            'pipeline'=>
             array(
                    array (
                        '$match' => array (
                            'anexo' => (string)$this->anexo,
                            'sgr_id' =>$sgr_id, 
                            'status'=>'activo',                            
                            'period_date' => array(
                                 '$lte' => $end_date
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
                    array('$unwind' => '$anexo_data'),
                    array (
                        '$match' => array (
                            'anexo_data.5214'=> $nro
                    )                       
                )        
            )     
        );  

        $query=array(
                'aggregate'=>'container.sgr_anexo_' . $this->anexo,
                'pipeline'=>
                  array(  
                        array (
                        '$match' => array (
                            '5214'=> $nro
                        )
                      )   ,                     
                      array (
                        '$lookup' => array (
                            'from' => 'container.sgr_periodos' ,
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'periodo')                        
                      ),
                      array (
                        '$match' => array (
                            'periodo.sgr_id' =>$sgr_id, 
                            'periodo.status'=>'activo' ,                          
                            'periodo.period_date' => array(
                                '$lte' => $end_date
                            )
                        )                        
                    )                 

                ));  

        $get=$this->sgr_db->command($query);

        if($get['result'][0][5349]!=null)
            return $get['result'][0][5349];

    }

    function get_order_number_by_sgrid_ORI($nro, $sgr_id) {


        $anexo = '12';

        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_each_sgrid($anexo, $sgr_id);

        /* FIND ANEXO */
        foreach ($result as $list) {



            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );
            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    /* GET DATA */

    function get_warranty_partner_left($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);
        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );
            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    function get_warranty_partner_print($cuit, $period) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $period;

        /* GET ACTIVE ANEXOS */
        // $result = $this->sgr_model->get_current_period_info($anexo, $period);
        $result = $this->sgr_model->get_active_print($anexo, $period);


        $return_result = array();
        foreach ($result as $list) {

            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );

            $new_results = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_results as $new_result) {
                $return_result[] = $new_result;
            }
        }



        return $return_result;
    }

    function get_warranty_partner_print_check($cuit, $period, $sgr_id) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $period;

        /* GET ACTIVE ANEXOS */
        // $result = $this->sgr_model->get_current_period_info($anexo, $period);
        $result = $this->sgr_model->get_active_print_check($anexo, $period, $sgr_id);


        $return_result = array();
        foreach ($result as $list) {

            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );
            //var_dump($container);
            //var_dump(json_encode($new_query));
            $new_results = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_results as $new_result) {
                $return_result[] = $new_result;
            }
        }



        return $return_result;
    }

    /* GET SHARER PARTNER WARRANTIES */

    function get_sharer($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );

            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $list2) {
                $return_result[] = $list2;
            }
        }

        return $return_result;
    }

    function get_sharer_left($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );

            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $list2) {
                $return_result[] = $list2;
            }
        }

        return $return_result;
    }

    /* SHARED SGR LIST */

    function get_sharer_all($period, $sgr_id) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print_check($anexo, $period, $sgr_id);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename']
            );
            $field = array('5349');
            $new_result = $this->mongowrapper->sgr->$container->find($new_query, $field);
            foreach ($new_result as $all) {

                $return_result[] = $all[5349];
            }
        }
        $new_list = array_unique($return_result);
        return $new_list;
    }

    /* GET CREDITOR */

    function get_creditor($sharer, $cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5351 => $cuit,
                5349 => $sharer
            );
            $get_new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($get_new_result as $new_result) {
                $return_result[] = $new_result['5214'];
            }
        }
        return $return_result;
    }

 

    function get_assisted_pymes($period) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;


        $model_anexo = "model_12";
        $this->load->Model($model_anexo);

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $rtn[] = $list[5349];
        }


        return count(array_unique($rtn));
    }

    function get_amount_granted_qty($period) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $rtn[] = $list[5214];
        }


        return count(array_unique($rtn));
    }

    function get_amount_granted($period) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $rtn[] = $list[5218];
        }
        return array_sum($rtn);
    }


 /**
     * Nuevo Reporte Anexo 12
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
        $custom_report = $this->check_is_custom();
        

        $anexoValues = $this->ui_table_xls();
        if(isset($custom_report))
            $anexoValues = $this->ui_table_xls_custom();

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

        if($this->input->post('order_number')!="")
             $custom_match['anexo_data.5214'] = $this->input->post('order_number');

        if($this->input->post('cuit_sharer')!="")
             $custom_match['anexo_data.5349'] = $this->input->post('cuit_sharer');
             
        if($this->input->post('cuit_creditor')!="")
             $custom_match['anexo_data.5351'] = $this->input->post('cuit_creditor');     

        if($this->input->post('warranty_type')!="")
            $custom_match['anexo_data.5216'] = new MongoRegex('/^' . $this->input->post('warranty_type') . '/i'); 
        
        $custom_report = false; 

        if($this->input->post('custom_report')=="1")
             $custom_report = true; 

       
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
          
            if(isset($custom_report))
                $data['custom']  = 1;

            $this->sgr_db->update($this->collection_out,$data);

            $rtn_msg = array('ok');
        }   
             

        echo json_encode($rtn_msg); 
        exit;
    }

    function check_is_custom(){
        $result =  $this->sgr_db->get($this->collection_out)->row();

        if(isset($result->custom))
            return $result->custom;
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
                /* Vars */
               
                $this->load->model('padfyj_model');
                if(isset($list[5349]))
                    $participate = $this->padfyj_model->search_name($list[5349]);

                if(isset($list[5726]))
                    $drawer = $this->padfyj_model->search_name((string) $list[5726]);

                /*  CREDITOR NAME */
                if(isset($list[5351])){
                    $creditor_mv = $this->get_mv_and_comercial_name($list[5351]);
                    $creditor_padfyj = $this->padfyj_model->search_name($list[5351]);
                    $creditor = ($creditor_mv) ? $creditor_mv : $creditor_padfyj;
                }


                $this->load->model('app');
                $currency = $this->app->get_ops(549);
                $repayment_system = $this->app->get_ops(527);
                $rate = $this->app->get_ops(526);
                $periodicity = $this->app->get_ops(548);

                /* PONDERACION */
                if(isset($list[5216][0]))
                    $get_weighting = $this->sgr_model->get_warranty_type($list[5216][0], $period_info['period']);

                       

                $destino_credito = (isset($list['DESTINO_CREDITO'])) ? $list['DESTINO_CREDITO'] : null;

                /* CURRENCY */
                if (isset($list[5219][0]))
                    $moneda = $currency[$list[5219][0]];


                if (isset($list[5758][0]))
                    $moneda_2 = $currency[$list[5758][0]];


                /* RATE */
                if (isset($list[5222][0]))
                    $tasa = $rate[$list[5222][0]];

                /* PERDIODICITY */
                if (isset($list[5226][0]))
                    $periodicidad = $periodicity[$list[5226][0]];


                /* SYSTEM */
                if (isset($list[5227][0]))
                    $sistema = $repayment_system[$list[5227][0]];
                
                /* FILENAME */
                $sgr_info = array();
                if(isset($list['filename'])){
                    $filename = trim($list['filename']);   
                    $sgr_info = $this->sgr_model->get_sgr_by_id_new($period_info['sgr_id']);
                }
                

                $new_list = array();
                $new_list['col0'] = $sgr_info[1693];
                $new_list['col1'] = $sgr_info[1695];            
                $new_list['col2'] = $list['id'];
                $new_list['col3'] = $list[5214];
                $new_list['col4'] = $participate;
                $new_list['col5'] = $list[5349];
                $new_list['col6'] = $list[5215];
                $new_list['col7'] = $list[5216][0];
                $new_list['col8'] = dot_by_coma($get_weighting['weighted']);
                $new_list['col9'] = dot_by_coma($list[5218]);
                $new_list['col10'] = $moneda;
                $new_list['col11'] = $drawer;
                $new_list['col12'] = $list[5726];
                $new_list['col13'] = $list[5727];
                $new_list['col14'] = $creditor;
                $new_list['col15'] = $list[5351];
                $new_list['col16'] = dot_by_coma($list[5221]);
                $new_list['col17'] = $moneda_2;
                $new_list['col18'] = $tasa;
                $new_list['col19'] = dot_by_coma($list[5223] / 100);
                $new_list['col20'] = $list[5224];
                $new_list['col21'] = $list[5225];
                $new_list['col22'] = $periodicidad;
                $new_list['col23'] = $sistema;
                $new_list['col24'] = $destino_credito;
                $new_list['col25'] = $filename;               
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


    function ui_table_xls_custom(){
        $result =  $this->sgr_db->get($this->collection_out)->result_array();
       
        
        foreach ($result as  $period_info) {

                if(isset($period_info['input_period_from']))
                    $input_period_from = $period_info['input_period_from'];

                if(isset($period_info['input_period_to']))
                    $input_period_to = $period_info['input_period_to'];

                if(isset($period_info['sgr_report']))
                    $sgr_report = $period_info['sgr_report'];
            
                $list = $period_info['anexo_data'];


                /* Vars */               

                $participate = $this->padfyj_model->search_name($list[5349]);


                /* PARTICIPATE DATA */
                $participate_data = $this->model_06->get_partner_stand_alone($list[5349]);

                $drawer = $this->padfyj_model->search_name((string) $list[5726]);



                /*  CREDITOR NAME */
                $creditor_mv = $this->get_mv_and_comercial_name($list[5351]);
                $creditor_padfyj = $this->padfyj_model->search_name($list[5351]);
                $creditor = ($creditor_mv) ? $creditor_mv : $creditor_padfyj;


                /* (AÑO) - (TRIMESTRE) - (AÑO-MES) - (AÑO-TRI) */
                list($year_data, $month_data, $day_data) = explode('-', $list[5215]);
                //$trim=floor(($mes-1) / 3)+1;
                $quarter = floor(($month_data - 1) / 3) + 1;


                $currency = $this->app->get_ops(549);
                $repayment_system = $this->app->get_ops(527);
                $rate = $this->app->get_ops(526);
                $periodicity = $this->app->get_ops(548);

                /* PONDERACION */
                $get_weighting = $this->sgr_model->get_warranty_type($list[5216][0], $list['period']);

                $destino_credito = (isset($list['DESTINO_CREDITO'])) ? $list['DESTINO_CREDITO'] : null;

                /* CURRENCY */
                $moneda = $list[5219][0];
                if (isset($moneda))
                    $moneda = $currency[$moneda];


                $moneda_2 = $list[5758][0];
                if (isset($moneda_2))
                    $moneda_2 = $currency[$moneda_2];


                /* RATE */
                $tasa = $list[5222][0];
                if (isset($tasa))
                    $tasa = $rate[$tasa];

                /* PERDIODICITY */
                $periodicidad = $list[5226][0];
                if (isset($periodicidad))
                    $periodicidad = $periodicity[$periodicidad];


                /* SYSTEM */
                $sistema = $list[5227][0];
                if (isset($sistema))
                    $sistema = $repayment_system[$sistema];

                /* EMPLOYEES QTY */
                $employees_qty = 0;
                if(isset($participate_data[0]['CANTIDAD_DE_EMPLEADOS']))
                    $employees_qty = $participate_data[0]['CANTIDAD_DE_EMPLEADOS'];
                
                #OR
                if(isset($list[5349]) && $employees_qty==0){
                       $employees_qty = $this->model_062->get_count_partner_left($list[5349],$period_info['sgr_id']);  
                }
                

                /* FILENAME */
                $sgr_info = array();
                if(isset($period_info['filename'])){
                    $filename = trim($list['filename']);   
                    
                    $sgr_info = $this->sgr_model->get_sgr_by_id_new($period_info['sgr_id']);
                }

                $new_list = array();
                $new_list['col1'] = $sgr_info[1693];
                $new_list['col2'] = $sgr_info[1695];            
                $new_list['col3'] = $list['id'];
                $new_list['col4'] = $list[5214];
                $new_list['col5'] = $participate;
                $new_list['col6'] = $list[5349];
                $new_list['col7'] = $participate_data[0]['4651'][0];
                $new_list['col8'] = $participate_data[0]['1699'][0];
                $new_list['col9'] = htmlentities($participate_data[0]['1700'], null, "UTF-8");
                $new_list['col10'] = $participate_data[0]['1698'];
                $new_list['col11'] = $employees_qty;
                $new_list['col12'] = $participate_data[0]['5208'];
                $new_list['col13'] = $list[5215];
                $new_list['col14'] = $list[5216][0];
                $new_list['col15'] = dot_by_coma($get_weighting['weighted']);
                $new_list['col16'] = dot_by_coma($list[5218]);
                $new_list['col17'] = $moneda;
                $new_list['col18'] = $drawer;
                $new_list['col19'] = $list[5726];
                $new_list['col20'] = $list[5727];
                $new_list['col21'] = $creditor;
                $new_list['col22'] = $list[5351];
                $new_list['col23'] = dot_by_coma($list[5221]);
                $new_list['col24'] = $moneda_2;
                $new_list['col25'] = $tasa;
                $new_list['col26'] = dot_by_coma($list[5223] / 100);
                $new_list['col27'] = $list[5224];
                $new_list['col28'] = $list[5225];
                $new_list['col29'] = $periodicidad;
                $new_list['col30'] = $sistema;
                $new_list['col31'] = $destino_credito;
                $new_list['col32'] = $year_data;
                $new_list['col33'] = $quarter;
                $new_list['col34'] = $year_data . "-" . $month_data;
                $new_list['col35'] = $year_data . "-" . $quarter;
                $new_list['col36'] = $filename;            
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
    function _ui_table_xls() {

        $list = null;
        
        $this->sgr_model->del_tmp_general();
        $result =  $this->sgr_db->get($this->collection_out)->result_array();

         $new_list = array();

        foreach ($result as $period_info) {


            foreach ($period_info['anexo_data'] as $list) {
               
                /* Vars */
               

                $this->load->model('padfyj_model');
                if(isset($list[5349]))
                    $participate = $this->padfyj_model->search_name($list[5349]);

                if(isset($list[5726]))
                    $drawer = $this->padfyj_model->search_name((string) $list[5726]);

                print_r($list);

                /*  CREDITOR NAME */
                if(isset($list[5351])){
                    $creditor_mv = $this->get_mv_and_comercial_name($list[5351]);
                    $creditor_padfyj = $this->padfyj_model->search_name($list[5351]);
                    $creditor = ($creditor_mv) ? $creditor_mv : $creditor_padfyj;
                }


                $this->load->model('app');
                $currency = $this->app->get_ops(549);
                $repayment_system = $this->app->get_ops(527);
                $rate = $this->app->get_ops(526);
                $periodicity = $this->app->get_ops(548);

                /* PONDERACION */
                if(isset($list[5216][0]))
                    $get_weighting = $this->sgr_model->get_warranty_type($list[5216][0], $period_info['period']);

                       

                $destino_credito = (isset($list['DESTINO_CREDITO'])) ? $list['DESTINO_CREDITO'] : null;

                /* CURRENCY */
                if (isset($list[5219][0]))
                    $moneda = $currency[$list[5219][0]];


                if (isset($list[5758][0]))
                    $moneda_2 = $currency[$list[5758][0]];


                /* RATE */
                if (isset($list[5222][0]))
                    $tasa = $rate[$list[5222][0]];

                /* PERDIODICITY */
                if (isset($list[5226][0]))
                    $periodicidad = $periodicity[$list[5226][0]];


                /* SYSTEM */
                if (isset($list[5227][0]))
                    $sistema = $repayment_system[$list[5227][0]];
                
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
                $new_list['col3'] = $list[5214];
                $new_list['col4'] = $participate;
                $new_list['col5'] = $list[5349];
                $new_list['col6'] = $list[5215];
                $new_list['col7'] = $list[5216][0];
                $new_list['col8'] = dot_by_coma($get_weighting['weighted']);
                $new_list['col9'] = dot_by_coma($list[5218]);
                $new_list['col10'] = $moneda;
                $new_list['col11'] = $drawer;
                $new_list['col12'] = $list[5726];
                $new_list['col13'] = $list[5727];
                $new_list['col14'] = $creditor;
                $new_list['col15'] = $list[5351];
                $new_list['col16'] = dot_by_coma($list[5221]);
                $new_list['col17'] = $moneda_2;
                $new_list['col18'] = $tasa;
                $new_list['col19'] = dot_by_coma($list[5223] / 100);
                $new_list['col20'] = $list[5224];
                $new_list['col21'] = $list[5225];
                $new_list['col22'] = $periodicidad;
                $new_list['col23'] = $sistema;
                $new_list['col24'] = $destino_credito;
                $new_list['col25'] = $filename;
                $new_list['uquery'] = $parameter;
              
                /* SAVE RESULT IN TMP DB COLLECTION */
                $this->sgr_model->save_tmp_general($new_list, $list['id']);
                $rtn_msg = array('ok');
            } 
        }
      # echo json_encode($rtn_msg);
       return $new_list;
       exit;
    }

    

    function _ui_table_xls_custom($result, $anexo = null, $parameter) { 

        $rtn_msg = array('no_record');
        
        $list = null;
        
        $this->sgr_model->del_tmp_general();
        
        foreach ($result as $period_info) {
        
            foreach ($period_info['anexo_data'] as $list) {
                
                /* Vars */
                $new_list = array();
                $parameter['custom'] = true;

                $participate = $this->padfyj_model->search_name($list[5349]);


                /* PARTICIPATE DATA */
                $participate_data = $this->model_06->get_partner_stand_alone($list[5349]);

                $drawer = $this->padfyj_model->search_name((string) $list[5726]);



                /*  CREDITOR NAME */
                $creditor_mv = $this->get_mv_and_comercial_name($list[5351]);
                $creditor_padfyj = $this->padfyj_model->search_name($list[5351]);
                $creditor = ($creditor_mv) ? $creditor_mv : $creditor_padfyj;


                /* (AÑO) - (TRIMESTRE) - (AÑO-MES) - (AÑO-TRI) */
                list($year_data, $month_data, $day_data) = explode('-', $list[5215]);
                //$trim=floor(($mes-1) / 3)+1;
                $quarter = floor(($month_data - 1) / 3) + 1;


                $currency = $this->app->get_ops(549);
                $repayment_system = $this->app->get_ops(527);
                $rate = $this->app->get_ops(526);
                $periodicity = $this->app->get_ops(548);

                /* PONDERACION */
                $get_weighting = $this->sgr_model->get_warranty_type($list[5216][0], $list['period']);

                $destino_credito = (isset($list['DESTINO_CREDITO'])) ? $list['DESTINO_CREDITO'] : null;

                /* CURRENCY */
                $moneda = $list[5219][0];
                if (isset($moneda))
                    $moneda = $currency[$moneda];


                $moneda_2 = $list[5758][0];
                if (isset($moneda_2))
                    $moneda_2 = $currency[$moneda_2];


                /* RATE */
                $tasa = $list[5222][0];
                if (isset($tasa))
                    $tasa = $rate[$tasa];

                /* PERDIODICITY */
                $periodicidad = $list[5226][0];
                if (isset($periodicidad))
                    $periodicidad = $periodicity[$periodicidad];


                /* SYSTEM */
                $sistema = $list[5227][0];
                if (isset($sistema))
                    $sistema = $repayment_system[$sistema];

                /* EMPLOYEES QTY */
                $employees_qty = 0;
                if(isset($participate_data[0]['CANTIDAD_DE_EMPLEADOS']))
                    $employees_qty = $participate_data[0]['CANTIDAD_DE_EMPLEADOS'];
                
                #OR
                if(isset($list[5349]) && $employees_qty==0){
                       $employees_qty = $this->model_062->get_count_partner_left($list[5349],$period_info['sgr_id']);  
                }
                

                /* FILENAME */
                $sgr_info = array();
                if(isset($period_info['filename'])){
                    $filename = trim($list['filename']);   
                    
                    $sgr_info = $this->sgr_model->get_sgr_by_id_new($period_info['sgr_id']);
                }

                $new_list = array();
                $new_list['col1'] = $sgr_info[1693];
                $new_list['col2'] = $sgr_info[1695];            
                $new_list['col3'] = $list['id'];
                $new_list['col4'] = $list[5214];
                $new_list['col5'] = $participate;
                $new_list['col6'] = $list[5349];
                $new_list['col7'] = $participate_data[0]['4651'][0];
                $new_list['col8'] = $participate_data[0]['1699'][0];
                $new_list['col9'] = htmlentities($participate_data[0]['1700'], null, "UTF-8");
                $new_list['col10'] = $participate_data[0]['1698'];
                $new_list['col11'] = $employees_qty;
                $new_list['col12'] = $participate_data[0]['5208'];
                $new_list['col13'] = $list[5215];
                $new_list['col14'] = $list[5216][0];
                $new_list['col15'] = dot_by_coma($get_weighting['weighted']);
                $new_list['col16'] = dot_by_coma($list[5218]);
                $new_list['col17'] = $moneda;
                $new_list['col18'] = $drawer;
                $new_list['col19'] = $list[5726];
                $new_list['col20'] = $list[5727];
                $new_list['col21'] = $creditor;
                $new_list['col22'] = $list[5351];
                $new_list['col23'] = dot_by_coma($list[5221]);
                $new_list['col24'] = $moneda_2;
                $new_list['col25'] = $tasa;
                $new_list['col26'] = dot_by_coma($list[5223] / 100);
                $new_list['col27'] = $list[5224];
                $new_list['col28'] = $list[5225];
                $new_list['col29'] = $periodicidad;
                $new_list['col30'] = $sistema;
                $new_list['col31'] = $destino_credito;
                $new_list['col32'] = $year_data;
                $new_list['col33'] = $quarter;
                $new_list['col34'] = $year_data . "-" . $month_data;
                $new_list['col35'] = $year_data . "-" . $quarter;
                $new_list['col36'] = $filename;
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