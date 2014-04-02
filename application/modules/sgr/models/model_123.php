<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_123 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '123';
        $this->idu = (float) $this->session->userdata('iduser');
        /* SWITCH TO SGR DB */
        $this->load->library('cimongo/cimongo', '', 'sgr_db');
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
        $parameter = array_map('floatval', $parameter);

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
         * @example .... NRO_GARANTIA	NUMERO_CUOTA_CUYO_VENC_MODIFICA	FECHA_VENC_CUOTA	FECHA_VENC_CUOTA_NUEVA	MONTO_CUOTA	SALDO_AL_VENCIMIENTO


         * */
        $defdna = array(
            1 => 'NRO_ORDEN',
            2 => 'DIA1',
            3 => 'DIA2',
            4 => 'DIA3',
            5 => 'DIA4',
            6 => 'DIA5',
            7 => 'DIA6',
            8 => 'DIA7',
            9 => 'DIA8',
            10 => 'DIA9',
            11 => 'DIA10',
            12 => 'DIA11',
            13 => 'DIA12',
            14 => 'DIA13',
            15 => 'DIA14',
            16 => 'DIA15',
            17 => 'DIA16',
            18 => 'DIA17',
            19 => 'DIA18',
            20 => 'DIA19',
            21 => 'DIA20',
            22 => 'DIA21',
            23 => 'DIA22',
            24 => 'DIA23',
            25 => 'DIA24',
            26 => 'DIA25',
            27 => 'DIA26',
            28 => 'DIA27',
            29 => 'DIA28',
            30 => 'DIA29',
            31 => 'DIA30',
            32 => 'DIA31',
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            /* STRING */
            $insertarr['NRO_ORDEN'] = (string) $insertarr['NRO_ORDEN']; //Nro orden          
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter['period'] = $period;
        $parameter['origin'] = "2013";

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
        $get_period = $this->sgr_model->get_period_info($this->anexo, $this->sgr_id, $period);
        $this->update_period($get_period['id'], $get_period['status']);

        $result = $this->app->put_array_sgr($id, $container, $parameter);

        if ($result) {
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
        $options = array('upsert' => true, 'safe' => true);
        $container = 'container.sgr_periodos';
        $query = array('id' => (float) $id);
        $parameter = array(
            'status' => 'rectificado',
            'rectified_on' => date('Y-m-d h:i:s'),
            'others' => $this->session->userdata['others'],
            'reason' => $this->session->userdata['rectify']
        );
        $rs = $this->mongo->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_info($anexo, $parameter) {

        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $period_value = $values['period'];
        }

        $date_header = str_replace("-", "/", "/" . $period_value);

        $headerArr = array();
        
        $headerArr[] = "Fecha/N° de Orden de la Garantía";        
            for ($i = 1; $i <= 31; $i++) {
                
                if($i<10)
                    $i = "0". $i;
                
               $headerArr[] = $i . $date_header;
            }
         $headerArr[] = "PROMEDIO";    
        
        $data = array($headerArr);

        foreach ($anexoValues as $values) {
            unset($values['period']);
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
        $result = $this->mongo->sgr->$container->find($query);

        foreach ($result as $list) {
            /* Vars */

            $array_sum = array();
            for ($i = 1; $i <= 31; $i++) {
                if ($list['DIA' . $i] != 0)
                    $array_sum[] = $list['DIA' . $i];
            }
            
            $average = array_sum($array_sum);

            $result_average = $average / count($array_sum);
            $new_list = array();
            $new_list['col1'] = $list['NRO_ORDEN'];

            for ($i = 1; $i <= 31; $i++) {
                $col = $i + 1;
                $new_list[$col] = money_format_custom($list['DIA' . $i]);
            }



            $new_list['promedio'] = money_format_custom($result_average);
            $new_list['period'] = $list['period'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
