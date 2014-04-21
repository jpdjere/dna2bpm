<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mysql_model_dollar extends CI_Model {

    function mysql_model_06() {
        parent::__construct();
        // IDU : Chequeo de sesion
        $this->idu = (float) $this->session->userdata('iduser');
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
        $anexo_dna2 = translate_anexos_dna2();
        $query = $this->db->get('sgr_vp');


        foreach ($query->result() as $row) {

            $parameter = array();
            $parameter['date'] = translate_mysql_date($row->fecha);
            $parameter['currency'] = $row->nombre;
            $parameter['amount'] = (float) $row->valor;

            $this->save_tmp($parameter);
        }
    }

    function save_tmp($parameter) {
        $parameter = (array) $parameter;
        $container = 'container.sgr_cotizacion_dolar';

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
