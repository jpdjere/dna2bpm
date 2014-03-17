<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mysql_model extends CI_Model {

    function mysql_model() {
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

        $this->load->database('dna2');
        
  
    }

    /* ACTIVE PERIODS DNA2 */

    function active_periods_dna2($anexo, $period) {


        var_dump($this->sgr_id, $this->idu);
        $this->clear_tmp();

        /* TRANSLATE ANEXO NAME */
        $anexo = translate_anexos_dna2($anexo);

        $this->db->where('estado', 'activo');
        $this->db->where('anexo', $anexo);
        $this->db->where('sgr_id', $this->sgr_id);
        $query = $this->db->get('sgr_control_periodos');
        $parameter = array();
        foreach ($query->result() as $row) {
            $parameter[] = $row;
        }

        foreach ($parameter as $each) {
            $this->save_tmp($each);
            /* ANEXO DATA */
            $this->anexo_data_tmp($anexo, $each->archivo);
        }
    }

    function anexo_data_tmp($anexo, $filename) {

        switch ($anexo) {
            case "sgr_socios":
                $anexo_field = "save_anexo_06_tmp";
                break;
        }


        if ($filename != 'Sin Movimiento')
            $this->db->where('filename', $filename);

        $this->db->select(
                'cuit, 
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
                fecha_efectiva'
        );
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

    function save_anexo_06_tmp($parameter) {


        $parameter = (array) $parameter;
        $token = $this->idu;
        $period = $this->session->userdata['period'];
        $container = 'container.anexo_06_' . $token . '_tmp';
        /* TRANSLATE ANEXO NAME */

        /* STRING */
        $parameter[1695] = (string) $parameter['cuit'];
        $parameter[5272] = (string) $parameter['tipo_socio'];
        $parameter[5779] = (string) $parameter['tipo_operacion'];
        $parameter[5248] = (string) $parameter['cedente_cuit'];

        /* INTEGERS */

        $parameter[5208] = (int) $parameter['codigo_actividad'];

        $parameter['CANTIDAD_DE_EMPLEADOS'] = (int) $parameter['cantidad_empleados'];


        if ($parameter[5779] == "INCORPORACION")
            $parameter[5779] = "1";
        if ($parameter[5779] == "INCREMENTO DE TENENCIA ACCIONARIA")
            $parameter[5779] = "2";
        if ($parameter[5779] == "DISMINUCION DE CAPITAL SOCIAL")
            $parameter[5779] = "3";


        /* FLOAT */
        $parameter[20] = (float) $parameter['monto'];
        $parameter[23] = (float) $parameter['monto2'];
        $parameter[26] = (float) $parameter['monto3'];

        $parameter[5597] = (int) str_replace(",", ".", $parameter['capital_suscripto']);
        $parameter[5598] = (int) str_replace(",", ".", $parameter['capital_integrado']);

        $parameter['FECHA_DE_TRANSACCION'] = translate_dna2_period_date($parameter['fecha_efectiva']);



        unset($parameter['capital_suscripto']);
        unset($parameter['capital_integrado']);


        $id = $this->app->genid_sgr($container);
        $result = $this->app->put_array_sgr($id, $container, $parameter);

        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    function clear_tmp() {
        $token = $this->idu;
        $container = 'container.periodos_' . $token . '_tmp';
        $delete = $this->mongo->sgr->$container->remove();
        /* 06 */
        $container = 'container.anexo_06_' . $token . '_tmp';
        $delete = $this->mongo->sgr->$container->remove();
    }

    function save_tmp($parameter) {
        $parameter = (array) $parameter;
        $token = $this->idu;
        $period = $this->session->userdata['period'];
        $container = 'container.periodos_' . $token . '_tmp';

        /* TRANSLATE ANEXO NAME */
        $parameter['anexo'] = translate_anexos_dna2($parameter['anexo']);
        $parameter['filename'] = $parameter['archivo'];

        $parameter['period_date'] = translate_dna2_period_date($parameter['periodo']);

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