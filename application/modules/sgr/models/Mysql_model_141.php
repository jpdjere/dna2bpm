<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mysql_model_141 extends CI_Model {

    function mysql_model_141() {
        parent::__construct();
        // IDU : Chequeo de sesion
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



        if ($filename != 'Sin Movimiento')
            $this->db->where('filename', $filename);



        $query = $this->db->get($anexo);
        $parameter = array();
        foreach ($query->result() as $row) {

            $parameter = array();
            $arr = array();

                 /*
            1 => 'CUIT_PARTICIPE',
            2 => 'CANT_GTIAS_VIGENTES',
            3 => 'HIPOTECARIAS',
            4 => 'PRENDARIAS',
            5 => 'FIANZA',
            6 => 'OTRAS',
            7 => 'REAFIANZA',
            8 => 'MORA_EN_DIAS',
            9 => 'CLASIFICACION_DEUDOR'
            */

            
           /* STRING */
            $parameter["CUIT_PARTICIPE"] = (string) $row->cuit_participe;
            /* INTEGERS & FLOAT */
            $parameter["CANT_GTIAS_VIGENTES"] = (int) $row->garantias_afrontadas;          
            $parameter["OTRAS"] = (float) $row->monto_adeudado;           
            $parameter["MORA_EN_DIAS"] = (int) $row->mora_en_dias;
            $parameter["CLASIFICACION_DEUDOR"] = (int) $row->clasificacion_deudor;            


            $parameter['idu'] = (float) $row->idu;
            $parameter['filename'] = (string) $row->filename;
            $parameter['id'] = (float) $row->id;
            $parameter['origen'] = 'forms2';



            $insert = $this->save_anexo_141_tmp($parameter, $anexo);
        }
    }

    /* SAVE FETCHS ANEXO 141 DATA */

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

    function already_id($anexo, $idvalue) {
        $idvalue = (float) $idvalue;

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("id" => $idvalue);
        $result = $this->mongowrapper->sgr->$container->findOne($query);
        if ($result)
            return true;
    }

    function save_anexo_141_tmp($parameter, $anexo) {
        $parameter = (array) $parameter;
        $token = $this->idu;

        $container = 'container.sgr_anexo_141';
        /* TRANSLATE ANEXO NAME */
        $already_id = $this->already_id("141", $parameter['id']);

        if (isset($already_id)) {
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
