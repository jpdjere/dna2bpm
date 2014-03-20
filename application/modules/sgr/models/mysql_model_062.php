<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mysql_model_062 extends CI_Model {

    function mysql_model_062() {
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
        $query = array("anexo" => "062");
        $delete = $this->mongo->sgr->$container->remove($query);
        /* 062 */
        $container = 'container.sgr_anexo_062_' . $token . '_tmp';
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
            /* LOAD MODEL 062 */
            $model_062 = 'model_062';
            $this->load->Model($model_062);

            $this->save_tmp($each);

            /* ANEXO DATA */
            if ($each->archivo) {
                $this->anexo_data_tmp($anexo_dna2, $each->archivo);
            } else {

                $get_anexo_data = $this->$model_062->get_anexo_data_tmp($anexo, $each['filename']);
                foreach ($get_anexo_data as $each) {

                    $token = $this->idu;
                    $container = 'container.sgr_anexo_' . $anexo . '_' . $token . '_tmp';
                    $id = $this->app->genid_sgr($container);
                    /*MONGO X MONGO*/
                    
                    unset($each['_id']);
                    $result = $this->app->put_array_sgr($id, $container, $each);
                }
            }
        }
    }

    /* SAVE FETCHS ANEXO  DATA */

    function anexo_data_tmp($anexo, $filename) {


        $anexo_field = "save_anexo_062_tmp";

        $this->db->select(
                'CUIT,
                EMPLEADOS'
        );

        if ($filename != 'Sin Movimiento')
            $this->db->where('filename', $filename);


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

    /* SAVE FETCHS ANEXO 062 DATA */

    function save_anexo_062_tmp($parameter) {
        $parameter = (array) $parameter;
        $token = $this->idu;
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_062_' . $token . '_tmp';
        /* TRANSLATE ANEXO NAME */

        /* STRING */
        $parameter['CUIT'] = (string) $parameter['CUIT'];

        /* INTEGER */
        $parameter['EMPLEADOS'] = (int) $parameter['EMPLEADOS'];

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
