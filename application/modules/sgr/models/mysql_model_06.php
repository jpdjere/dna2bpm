<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mysql_model_06 extends CI_Model {

    function mysql_model_06() {
        parent::__construct();
        // IDU : Chequeo de sesion
        $this->idu = (int) $this->session->userdata('iduser');
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

        $dbconnect = $this->load->database('dna2');
    }

    /* ACTIVE PERIODS DNA2 */

    function active_periods_dna2($anexo, $period) {



        /* TRANSLATE ANEXO NAME */
        $anexo_dna2 = translate_anexos_dna2($anexo);
        $this->db->where('estado', 'activo');
        $this->db->where('archivo !=', 'Sin Movimiento');
        $this->db->where('anexo', $anexo_dna2);
        $query = $this->db->get('forms2.sgr_control_periodos');


        foreach ($query->result() as $row) {



            $already_period = $this->already_period($row->archivo);
            if (!$already_period) {

                $parameter = array();

                $parameter['anexo'] = translate_anexos_dna2($row->anexo);
                $parameter['filename'] = $row->archivo;
                $parameter['period_date'] = translate_dna2_period_date($row->periodo);
                $parameter['sgr_id'] = (float) $row->sgr_id;
                $parameter['status'] = 'activo';
                $parameter['origen'] = 'forms2';
                $parameter['period'] = str_replace("_", "-", $row->periodo);


                /* UPDATE CTRL PERIOD */
                $this->save_tmp($parameter);

                /* UPDATE ANEXO */
                if ($row->archivo) {
                    $already_update = $this->already_updated($row->anexo, $nro_orden, $filename);
                    if (!$already_update)
                        $this->anexo_data_tmp($anexo_dna2, $row->archivo);
                }
            }
        }
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
                filename, cedente_cuit, modalidad,
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
        $result = $this->mongo->sgr->$container->findOne($query);
        if ($result)
            return true;
    }

    function already_updated($anexo, $nro_orden, $filename) {

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $filename);
        $result = $this->mongo->sgr->$container->findOne($query);

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

}
