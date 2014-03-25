<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mysql_model_12 extends CI_Model {

    function mysql_model_12() {
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
        $query = array("anexo" => "12");
        $delete = $this->mongo->sgr->$container->remove($query);
        /* 12 */
        $container = 'container.sgr_anexo_12_' . $token . '_tmp';
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
        $this->db->where('anexo', 'sgr_garantias');
        $query = $this->db->get('forms2.sgr_control_periodos');

        $parameter = array();
        foreach ($query->result() as $row) {
            $parameter[] = $row;
        }
        foreach ($parameter as $each) {

            /* LOAD MODEL 12 */
            $model_12 = 'model_12';
            $this->load->Model($model_12);

            $this->save_tmp($each);
            /* ANEXO DATA */
            if ($each->archivo) {
                echo $each->archivo . "...<br>";
                $this->anexo_data_tmp($anexo_dna2, $each->archivo);
                }
            }
        }

    /* SAVE FETCHS ANEXO  DATA */

    function anexo_data_tmp($anexo, $filename) {


        $anexo_field = "save_anexo_12_tmp";

        $this->db->select(
                'nro_orden,
                cuit_socio_participe,
                fecha_alta,
                tipo_garantia,
                monto,
                moneda,
                librador_cuit,
                nro_operacion_bolsa,
                cuit_acreedor,
                importe_Cred_Garant,
                moneda_Cred_Garant,
                tasa,
                puntos_adicionales,
                plazo,
                gracia,
                periodicidad,
                sistema,filename,idu'
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

    /* SAVE FETCHS ANEXO 12 DATA */

    function save_anexo_12_tmp($parameter) {
        $parameter = (array) $parameter;
        $token = $this->idu;
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_12';
        /* TRANSLATE ANEXO NAME */

        /* STRING */
        $parameter[5214] = (string) $parameter['nro_orden']; //Nro orden


        $parameter[5349] = (string) $parameter['cuit_socio_participe']; //Cuit_participe
        $parameter[5726] = (string) $parameter['librador_cuit']; //Librador_cuit           
        $parameter[5351] = (string) $parameter['cuit_acreedor']; //Acreedir

        /* FLOAT */
        $parameter[5218] = (float) $parameter['monto'];
        $parameter[5221] = (float) $parameter['importe_Cred_Garant'];
        $parameter[5223] = (float) $parameter['puntos_adicionales'];

        /* INTEGER */
        $parameter[5224] = (int) $parameter['plazo'];
        $parameter[5225] = (int) $parameter['gracia'];


        unset($parameter['nro_orden']);
        unset($parameter['cuit_socio_participe']);
        unset($parameter['librador_cuit']);
        unset($parameter['cuit_acreedor']);
        unset($parameter['monto']);
        unset($parameter['importe_Cred_Garant']);

        unset($parameter['plazo']);
        unset($parameter['gracia']);
        unset($parameter['fecha_alta']);

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

    function save_tmp($parameter) {

        
        $parameter = (array) $parameter;
        $container = 'container.sgr_periodos';

        /* TRANSLATE ANEXO NAME */
        $sgr_id  = (float)$parameter['sgr_id'];
        var_dump($parameter['sgr_id'],$sgr_id);
        
            $parameter['anexo'] = translate_anexos_dna2($parameter['anexo']);
            $parameter['filename'] = $parameter['archivo'];
            $parameter['period_date'] = translate_dna2_period_date($parameter['periodo']);
        $parameter['sgr_id'] = $sgr_id;
        $parameter['status'] = 'activo';

            unset($parameter['estado']);
        unset($parameter['archivo']);

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
