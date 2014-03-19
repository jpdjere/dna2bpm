<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mysql_model_201 extends CI_Model {

    function mysql_model_201() {
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

    function clear_tmp() {
        $token = $this->idu;
        $container = 'container.periodos_' . $token . '_tmp';
        $query = array("anexo" => "201");
        $delete = $this->mongo->sgr->$container->remove($query);
        /* 201 */
        $container = 'container.sgr_anexo_201_' . $token . '_tmp';
        $delete = $this->mongo->sgr->$container->remove();
    }

    /* ACTIVE PERIODS DNA2 */

    function active_periods_dna2($anexo, $period) {

        /* CLEAR TEMP DATA */
        $this->clear_tmp();

        /* TRANSLATE ANEXO NAME */
        $anexo_dna2 = translate_anexos_dna2($anexo);



        $this->db->where('estado', 'activo');
        $this->db->where('archivo !=', 'Sin Movimiento');
        $this->db->where('anexo', $anexo_dna2);
        $this->db->where('sgr_id', $this->sgr_id);
        $query = $this->db->get('forms2.sgr_control_periodos');


        $parameter = array();
        foreach ($query->result() as $row) {
            $parameter[] = $row;
        }

        /* UPDATE MONGO BY MONGO */
        $mongo_periods = $this->sgr_model->get_active($anexo);
        foreach ($mongo_periods as $each) {
            unset($each['_id']);
            $parameter[] = $each;
        }

        foreach ($parameter as $each) {

            /* LOAD MODEL 201 */
            $model_201 = 'model_201';
            $this->load->Model($model_201);

            $this->save_tmp($each);
            /* ANEXO DATA */
            if ($each->archivo) {
                $this->anexo_data_tmp($anexo_dna2, $each->archivo);
            } else {

                $get_anexo_data = $this->$model_201->get_anexo_data_tmp($anexo, $each['filename']);
                foreach ($get_anexo_data as $each) {

                    $token = $this->idu;
                    $container = 'container.sgr_anexo_' . $anexo . '_' . $token . '_tmp';
                    $id = $this->app->genid_sgr($container);

                    unset($each['_id']);
                    $result = $this->app->put_array_sgr($id, $container, $each);
                }
            }
        }
    }

    /* SAVE FETCHS ANEXO  DATA */

    function anexo_data_tmp($anexo, $filename) {
        $anexo_field = "save_anexo_201_tmp";

        $this->db->select('nro_control,fecha_movimiento,
                aporte,
                retiro,
                retiro_de_rendimientos,
                retencion_por_contingente, 
                filename, 
                idu');


        if ($filename != 'Sin Movimiento')
            $this->db->where('filename', $filename);

        $this->db->join('sgr_fdr_integrado_numeracion', 'sgr_fdr_integrado_numeracion.ID = sgr_fdr_integrado.ID');
        $this->db->where('idu', $this->idu);
        $query = $this->db->get($anexo);


        $parameter = array();
        foreach ($query->result() as $row) {
            $parameter[] = $row;
        }

        foreach ($parameter as $each) {
            $this->$anexo_field($each);
        }
    }

    /* SAVE FETCHS ANEXO 201 DATA */

    function save_anexo_201_tmp($parameter) {
        $parameter = (array) $parameter;
        $token = $this->idu;
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_201_' . $token . '_tmp';
        
        /* TRANSLATE ANEXO NAME */
        /* STRING */
        $parameter["NUMERO_DE_APORTE"] = (string) $parameter["nro_control"];
        /* INTEGERS & FLOAT */
        $parameter["APORTE"] = (float) $parameter["aporte"];
        $parameter["RETIRO"] = (float) $parameter["retiro"];
        $parameter["RETENCION_POR_CONTINGENTE"] = (float) $parameter["retencion_por_contingente"];
        $parameter["RETIRO_DE_RENDIMIENTOS"] = (float) $parameter["retiro_de_rendimientos"];


        $parameter['FECHA_MOVIMIENTO'] = translate_mysql_date($parameter['fecha_movimiento']);


        unset($parameter['nro_control']);
        unset($parameter['aporte']);
        unset($parameter['retiro']);
        unset($parameter['retencion_por_contingente']);
        unset($parameter['retiro_de_rendimientos']);
        unset($parameter['fecha_movimiento']);

        $id = $this->app->genid_sgr($container);

        $result = $this->app->put_array_sgr($id, $container, $parameter);
        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    /* SAVE FETCHS PERIODOS */

    function save_tmp($parameter, $mongo = false) {

        $parameter = (array) $parameter;
        $token = $this->idu;
        $period = $this->session->userdata['period'];
        $container = 'container.periodos_' . $token . '_tmp';

        /* TRANSLATE ANEXO NAME */
        if ($parameter['estado']) {
            $parameter['anexo'] = translate_anexos_dna2($parameter['anexo']);
            $parameter['filename'] = $parameter['archivo'];
            $parameter['period_date'] = translate_dna2_period_date($parameter['periodo']);

            unset($parameter['estado']);
        }

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
