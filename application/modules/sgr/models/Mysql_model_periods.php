<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mysql_model_periods extends CI_Model {

    function mysql_model_periods() {
        parent::__construct();
        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');
        if (!$this->idu) {
            header("$this->module_url/user/logout");
            exit();
        }

        /* DATOS SGR */
        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];
        }

        $dbconnect = $this->load->database('dna2', $this->db);
    }

    /* ACTIVE PERIODS DNA2 */

    function active_periods_dna2() {
        /* CHECK A VALID SGR ID */
        if (isset($this->sgr_id)) {
            $this->checked_sgr_id();
        }
    }

    function checked_sgr_id() {
        /* TRANSLATE ANEXO NAME */

        $this->db->where('estado', 'rectificado');
        $this->db->where('archivo !=', 'Sin Movimiento');
        $this->db->where('sgr_id', $this->sgr_id);
        $query = $this->db->get('forms2.sgr_control_periodos');
        foreach ($query->result() as $row) {
            $parameter = array();
            $parameter['anexo'] = translate_anexos_dna2($row->anexo);
            $parameter['filename'] = $row->archivo;
            $parameter['period_date'] = translate_dna2_period_date($row->periodo);
            $parameter['sgr_id'] = (float) $row->sgr_id;
            $parameter['status'] = 'activo';
            $parameter['origen'] = 'forms2';
            $parameter['period'] = str_replace("_", "-", $row->periodo);


            $is_2014 = explode("_", $row->periodo);
            if ($is_2014[1] != "2014") {

                /* ANEXOS */
                if (translate_anexos_dna2($row->anexo))
                    $get_period = $this->sgr_model->get_if_is_rectified($row->archivo);

                if (isset($get_period['id'])) {

                    $get_period_id = $get_period['id'];
                    $get_period_status = $get_period['status'];

                    $this->update_period($get_period_id, $get_period_status);

                    if ($this->session->userdata('iduser') == 10)
                        var_dump($row->archivo);
                }
            }
        }
    }

    function update_period($id, $status) {


        /* if (!isset($this->session->userdata['rectify']))
          exit(); */


        $options = array('upsert' => true, 'w' => 1);
        $container = 'container.sgr_periodos';
        $query = array('id' => (float) $id);
        $parameter = array(
            'status' => 'rectificado',
            'rectified_on' => date('Y-m-d h:i:s'),
            'reason' => "rectificado Origen forms2"
        );
        $rs = $this->mongowrapper->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function save_tmp($parameter) {


        $parameter = (array) $parameter;
        $container = 'container.sgr_periodos';

        $id = $this->app->genid_sgr($container);
        $result = $this->app->put_array_sgr($id, $container, $parameter);
        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    /* SAVE FETCHS ANEXO  DATA */

    function anexo_data_tmp($anexo, $filename) {




        $this->db->select(
                'id,
                cuit, 
                tipo_socio, 
                tipo_operacion,
                cedente_cuit,
                codigo_actividad,
                cantidad_empleados, 
                monto,
                monto2,
                monto3, 
                capital_suscripto,
                capital_integrado,
                fecha_efectiva, 
                filename, cedente_cuit, cedente_caracteristica, modalidad,
                idu'
        );

        if ($filename != 'Sin Movimiento')
            $this->db->where('filename', $filename);



        $query = $this->db->get($anexo);
        $parameter = array();
        foreach ($query->result() as $row) {

            $parameter = array();

            /* STRING */
            $parameter[1695] = (string) $row->cuit;
            $parameter[5272] = (string) $row->tipo_socio;
            $parameter[5248] = (string) $row->cedente_cuit;



            /* INTEGERS */

            $parameter[5208] = (int) $row->codigo_actividad;
            $parameter['CANTIDAD_DE_EMPLEADOS'] = (int) $row->cantidad_empleados;

            /* FLOAT */
            $parameter[20] = (float) $row->monto;
            $parameter[23] = (float) $row->monto2;
            $parameter[26] = (float) $row->monto3;

            $parameter[5597] = (float) str_replace(",", ".", $row->capital_suscripto);
            $parameter[5598] = (float) str_replace(",", ".", $row->capital_integrado);

            $parameter['FECHA_DE_TRANSACCION'] = translate_mysql_date($row->fecha_efectiva);


            if (strtoupper(trim($row->tipo_operacion)) == "INCORPORACION")
                $parameter[5779] = "1";
            if (strtoupper(trim($row->tipo_operacion)) == "INCREMENTO DE TENENCIA ACCIONARIA")
                $parameter[5779] = "2";
            if (strtoupper(trim($row->tipo_operacion)) == "DISMINUCION DE CAPITAL SOCIAL")
                $parameter[5779] = "3";


            if (strtoupper(trim($row->modalidad)) == "TRANSFERENCIA")
                $parameter[5252] = "1";
            if (strtoupper(trim($row->modalidad)) == "SUSCRIPCION")
                $parameter[5252] = "2";

            if (strtoupper(trim($row->cedente_caracteristica)) == "DESVINCULACION")
                $parameter[5292] = "2";
            if (strtoupper(trim($row->cedente_caracteristica)) == "DISMINUCION DE TENENCIA ACCIONARIA")
                $parameter[5292] = "1";


            $parameter['idu'] = (float) $row->idu;
            $parameter['filename'] = (string) $row->filename;
            $parameter['id'] = (float) $row->id;
            $parameter['origen'] = 'forms2';

            debug($parameter);

            $this->save_anexo_06_tmp($parameter, $anexo);
        }
    }

    /* SAVE FETCHS ANEXO 06 DATA */

    function already_period($filename) {

        $container = 'container.sgr_periodos';
        $query = array("filename" => $filename);
        $result = $this->mongowrapper->sgr->$container->findOne($query);
        if ($result)
            return true;
    }

    function already_updated($anexo, $nro_orden, $filename) {

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $filename);
        $result = $this->mongowrapper->sgr->$container->findOne($query);

        if ($result)
            return true;
    }

    function save_anexo_06_tmp($parameter, $anexo) {
        $parameter = (array) $parameter;
        $token = $this->idu;
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_06';
        /* TRANSLATE ANEXO NAME */

        $id = $this->app->genid_sgr($container);
        $result = $this->app->put_array_sgr($id, $container, $parameter);
        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    function Call_every_one() {

         $anexo = method_exists($this->session, 'userdata') ? $this->session->userdata['anexo_code'] : '06';
        $this->Update_anexo($anexo);
    }

    function Update_anexo($anexo) {

        $mysql_model = "mysql_model_" . $anexo;
        $this->load->Model($mysql_model);

        $result = $this->$mysql_model->active_periods_dna2($anexo, $this->period);
    }

}
