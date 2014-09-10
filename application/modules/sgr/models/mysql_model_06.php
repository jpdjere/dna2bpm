<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mysql_model_06 extends CI_Model {

    function mysql_model_06() {
        parent::__construct();
        // IDU : Chequeo de sesion
        $this->idu = (float) switch_users($this->session->userdata('iduser'));
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


                $is_2014 = explode("_", $row->periodo);
                if ($is_2014[1] != "2014") {

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
    }

    /* UPDATE SIN MOVIMIENTO */

    function active_periods_sm_dna2($anexo, $period) {
        /* TRANSLATE ANEXO NAME */
        $anexo_dna2 = translate_anexos_dna2($anexo);
        $this->db->where('estado', 'activo');
        $this->db->where('archivo', 'Sin Movimiento');
        $this->db->where('anexo', $anexo_dna2);
        $query = $this->db->get('forms2.sgr_control_periodos');

        foreach ($query->result() as $row) {
            $already_period = $this->already_period($row->archivo);
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
                /* UPDATE CTRL PERIOD */
                $this->save_tmp($parameter);
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

        ini_set('error_reporting', E_ALL);


        $this->db->select(
                'id,
                cuit, 
                nombre,
                provincia, 	
                partido,		
                localidad,
                codigo_postal, 
                calle, 
                nro, 
                piso, 
                dto_oficina, 
                telefono1,
                telefono2, 
                email, 
                web,  	
                sector, 
                tipo_socio, 
                tipo_operacion,
                codigo_actividad,
                cantidad_empleados, 
                anio,	
                monto,
                tipo_origen,
                anio2,
                monto2,
                tipo_origen2,
                anio3,
                monto3, 
                tipo_origen3,
                promedio,
                tipo_empresa, 
                regimen_iva, 
                capital_suscripto,
                acciones_suscriptas, 
                capital_integrado,
                acciones_integradas, 
                fecha_efectiva,
                tipo_acta, 
                fecha_acta, 
                acta_nro, 
                filename, 
                cedente_cuit, 
                cedente_nombre, 
                cedente_caracteristica, 
                modalidad,
                idu'
        );

        if ($filename != 'Sin Movimiento')
            $this->db->where('filename', $filename);



        $query = $this->db->get($anexo);
        $parameter = array();
        foreach ($query->result() as $row) {

            $parameter = array();
            $arr = array();

            /* STRING */
            $parameter[1695] = (string) $row->cuit;
            $parameter[1693] = (string) $row->nombre;
            $parameter[5272] = (string) $row->tipo_socio;
            $parameter[5248] = (string) $row->cedente_cuit;

            $parameter[19] = (string) $row->anio;
            $parameter[22] = (string) $row->anio2;
            $parameter[25] = (string) $row->anio3;

            $parameter[21] = (string) $row->tipo_origen;
            $parameter[24] = (string) $row->tipo_origen2;
            $parameter[27] = (string) $row->tipo_origen3;


            $parameter[1693] = (string) $row->nombre;
            $parameter[4651] = (string) $row->provincia;
            $parameter[1699] = (string) $row->partido;
            $parameter[1700] = (string) $row->localidad;

            $parameter[1698] = (string) $row->codigo_postal;
            $parameter[4653] = (string) $row->calle;

            $parameter[4654] = (string) $row->nro;
            $parameter[4655] = (string) $row->piso;
            $parameter[4656] = (string) $row->dto_oficina;
            $parameter[1701] = (string) $row->telefono1 . $row->telefono2;
            $parameter[1703] = (string) $row->email;
            $parameter[1704] = (string) $row->web;


            $parameter[5596] = (string) $row->regimen_iva;
            $parameter[5253] = (string) $row->tipo_acta;

            $parameter[5254] = (string) $row->acta_nro;
            $parameter[5249] = (string) $row->cedente_nombre;

            /* INTEGERS */

            $parameter[5208] = (int) $row->codigo_actividad;
            $parameter['CANTIDAD_DE_EMPLEADOS'] = (int) $row->cantidad_empleados;

            /* FLOAT */
            $parameter[20] = (float) $row->monto;
            $parameter[23] = (float) $row->monto2;
            $parameter[26] = (float) $row->monto3;

            $parameter[5597] = (float) str_replace(",", ".", $row->capital_suscripto);
            $parameter[5598] = (float) str_replace(",", ".", $row->capital_integrado);

            /* DATES */


            if (isset($row->fecha_acta)) {
                list($arr['Y'], $arr['m'], $arr['d']) = explode("-", $row->fecha_acta);
                $parameter[5255] = $arr;
            }

            if ($row->fecha_efectiva != "0000-00-00")
                $parameter['FECHA_DE_TRANSACCION'] = translate_mysql_date($row->fecha_efectiva);
            else
                $parameter['FECHA_DE_TRANSACCION'] = translate_mysql_date($row->fecha_acta);


            /* OPTIONS */
            if (strtoupper(trim($row->tipo_operacion)) == "INCORPORACION")
                $parameter[5779] = "1";
            if (strtoupper(trim($row->tipo_operacion)) == "INCREMENTO DE TENENCIA ACCIONARIA")
                $parameter[5779] = "2";
            if (strtoupper(trim($row->tipo_operacion)) == "INCREMENTO TENENCIA ACCIONARIA")
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
            if (strtoupper(trim($row->cedente_caracteristica)) == "DISMINUCION TENENCIA ACCIONARIA")
                $parameter[5292] = "1";


            $parameter['idu'] = (float) $row->idu;
            $parameter['filename'] = (string) $row->filename;
            $parameter['id'] = (float) $row->id;
            $parameter['origen'] = 'forms2';



            $insert = $this->save_anexo_06_tmp($parameter, $anexo);
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

    function already_id($anexo, $idvalue) {
        $idvalue = (float) $idvalue;

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("id" => $idvalue);
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

        $already_id = $this->already_id("06", $parameter['id']);



        if ($already_id) {
            echo "duplicado" . $parameter['id'];
        } else {
            $id = $this->app->genid_sgr($container);
            $result = $this->app->put_array_sgr($id, $container, $parameter);
            if ($result) {
                $out = array('status' => 'ok');
            } else {
                $out = array('status' => 'error');
            }
        }



        return $out;
    }

}
