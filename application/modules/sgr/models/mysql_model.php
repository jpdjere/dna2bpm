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

        $dbconnect = $this->load->database('dna2');
    }

    function clear_tmp() {
        $token = $this->idu;
        $container = 'container.periodos_' . $token . '_tmp';
        $delete = $this->mongo->sgr->$container->remove();
        /* 06 */
        $container = 'container.sgr_anexo_06_' . $token . '_tmp';
        $delete = $this->mongo->sgr->$container->remove();
    }

    /* ACTIVE PERIODS DNA2 */

    function active_periods_dna2() {

        $table_to_export = 'export_sgr_socios_4';
        $anexo_to_export = 'sgr_socios_4';

        $files_arr = array();


        $query = $this->db->get($table_to_export);
        $each = array();
        $parameter = array();
        foreach ($query->result() as $row) {
            
            echo $row->filename;
            $files_arr[] = $row->filename;
        }


        foreach ($files_arr as $files) {
            $filename = $files;
            $files = explode("-", $files);


            $this->db->select('sgr_id');
            $this->db->like('archivo', trim($files[1]));
            $this->db->join('forms2.idsent', 'forms2.idsent.id = forms2.sgr_control_periodos.sgr_id', 'inner');
            $this->db->limit(1);
            $query = $this->db->get('forms2.sgr_control_periodos');
            $each = array();
            $parameter = array();
            foreach ($query->result() as $row) {
                $data = array();

                $data['sgr_id'] = $row->sgr_id;
                $data['anexo'] = $anexo_to_export;
                $data['periodo'] = '12_2010';
                $data['estado'] = 'activo';
                $data['archivo'] = $filename;
                $data['fecha'] = "2011-01-01 01:01:01";


                $result = $this->db->insert('forms2.sgr_control_periodos', $data);
                var_dump($result);
            }
        }



        exit();
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
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

            /* LOAD MODEL 06 */
            $model_06 = 'model_06';
            $this->load->Model($model_06);

            $this->save_tmp($each);
            /* ANEXO DATA */
            if ($each->archivo) {
                $this->anexo_data_tmp($anexo_dna2, $each->archivo);
            } else {

                $get_anexo_data = $this->$model_06->get_anexo_data_tmp($anexo, $each['filename']);
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

    function anexo_data_tmp($filename) {


        $anexo_field = "save_anexo_06_tmp";

        $this->db->select('cuit, 
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
                        filename,
                        cedente_cuit,
                        idu');

        if ($filename != 'Sin Movimiento')
            $this->db->where('filename', $filename);


        $this->db->where('idu', $this->idu);
        $query = $this->db->get('sgr_socios');
        $parameter = array();
        foreach ($query->result() as $row) {
            $parameter[] = $row;
        }

        foreach ($parameter as $each) {
            $this->$anexo_field($each);
        }
    }

    /* SAVE FETCHS ANEXO 06 DATA */

    function save_anexo_06_tmp($parameter) {
        $parameter = (array) $parameter;
        $token = $this->idu;
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_06_test_tmp';
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

        $parameter['FECHA_DE_TRANSACCION'] = translate_mysql_date($parameter['fecha_efectiva']);


        unset($parameter['capital_suscripto']);
        unset($parameter['capital_suscripto']);
        unset($parameter['cantidad_empleados']);
        unset($parameter['monto']);
        unset($parameter['monto2']);
        unset($parameter['monto3']);


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

    function save_tmp($idu, $filename) {

        $parameter = array();
        $token = $this->idu;
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_periodos_test';

        /* TRANSLATE ANEXO NAME */

        $parameter['idu'] = (int) $idu;
        $parameter['anexo'] = '06';
        $parameter['period'] = '01-2011';
        $parameter['status'] = 'activo';
        $parameter['filename'] = $filename;
        $parameter['period_date'] = translate_dna2_period_date('01_2011');

        $id = $this->app->genid_sgr($container);
        $result = $this->app->put_array_sgr($id, $container, $parameter);


        if ($result) {
            $out = array('status' => 'ok');
            /* fetch socios */
            $this->anexo_data_tmp($filename);
        } else {
            $out = array('status' => 'error');
        }


        return $out;
    }

}
