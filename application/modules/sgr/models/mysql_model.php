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




        $files_arr = array('ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-05 03:34:49.xls',
            'ANEXO 12 - GARANTIA DE VALORES S.G.R. - 2011-03-15 04:32:00.xls',
            'ANEXO 12 - AFIANZAR S.G.R. - 2011-03-17 05:34:22.xls',
            'ANEXO 12 - CARDINAL S.G.R. - 2011-04-01 01:29:52.xls',
            'ANEXO 12 - MACROAVAL S.G.R. - 2011-03-14 06:37:55.xls',
            'ANEXO 12 - DON MARIO S.G.R. - 2011-05-11 12:47:45.xls',
            'ANEXO 12 - PUENTE HNOS. S.G.R. - 2011-03-16 11:42:00.xls',
            'ANEXO 12 - ACINDAR PYMES S.G.R. - 2011-04-06 04:52:20.xls',
            'ANEXO 12 - ACINDAR PYMES S.G.R. - 2011-04-06 04:57:51.xls',
            'ANEXO 12 - ACINDAR PYMES S.G.R. - 2011-04-07 12:25:29.xls',
            'ANEXO 12 - ACINDAR PYMES S.G.R. - 2011-04-06 04:23:47.xls',
            'ANEXO 12 - AFIANZAR S.G.R. - 2012-03-07 01:51:51.xls',
            'ANEXO 12 - AFIANZAR S.G.R. - 2011-03-18 11:11:00.xls',
            'ANEXO 12 - FIDUS S.G.R. - 2011-03-21 03:08:30.xls',
            'ANEXO 12 - AVALUAR S.G.R. - 2011-03-18 06:37:52.xls',
            'ANEXO 12 - FIDUS S.G.R. - 2011-03-21 03:51:08.xls',
            'ANEXO 12 - LOS GROBO S.G.R. - 2011-03-22 09:47:51.xls',
            'ANEXO 12 - LOS GROBO S.G.R. - 2011-03-22 10:05:24.xls',
            'ANEXO 12 - LOS GROBO S.G.R. - 2011-03-22 11:48:58.xls',
            'ANEXO 12 - MACROAVAL S.G.R. - 2011-03-22 12:27:56.xls',
            'ANEXO 12 - VINCULOS S.G.R. - 2011-03-28 04:51:05.xls',
            'ANEXO 12 - VINCULOS S.G.R. - 2011-03-28 05:13:42.xls',
            'ANEXO 12 - GARANTIA DE VALORES S.G.R. - 2011-03-29 12:09:33.xls',
            'ANEXO 12 - AFFIDAVIT S.G.R. - 2011-03-29 05:07:09.xls',
            'ANEXO 12 - CAMPO AVAL S.G.R. - 2011-03-29 06:47:03.xls',
            'ANEXO 12 - CAMPO AVAL S.G.R. - 2011-03-29 06:50:38.xls',
            'ANEXO 12 - AVALUAR S.G.R. - 2011-03-31 05:03:06.xls',
            'ANEXO 12 - AGROAVAL S.G.R. - 2011-03-31 02:00:54.xls',
            'ANEXO 12 - AGROAVAL S.G.R. - 2011-03-31 02:20:20.xls',
            'ANEXO 12 - CARDINAL S.G.R. - 2011-04-01 12:07:30.xls',
            'ANEXO 12 - ACINDAR PYMES S.G.R. - 2011-04-07 11:14:10.xls',
            'ANEXO 12 - ACINDAR PYMES S.G.R. - 2011-04-07 12:00:02.xls',
            'ANEXO 12 - INTERGARANTIAS S.G.R. - 2013-10-30 04:57:09.xls',
            'ANEXO 12 - INTERGARANTIAS S.G.R. - 2013-10-30 03:27:35.xls',
            'ANEXO 12 - INTERGARANTIAS S.G.R. - 2013-10-30 03:18:59.xls',
            'ANEXO 12 - CUYO AVAL S.G.R. - 2011-04-19 05:18:11.xls',
            'ANEXO 12 - INTERGARANTIAS S.G.R. - 2011-04-12 03:48:03.xls',
            'ANEXO 12 - INTERGARANTIAS S.G.R. - 2013-10-30 03:04:56.xls',
            'ANEXO 12 - INTERGARANTIAS S.G.R. - 2011-04-14 03:03:37.xls',
            'ANEXO 12 - INTERGARANTIAS S.G.R. - 2011-05-31 03:58:03.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-05 02:58:28.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-05 02:24:00.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-05 01:13:11.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-05 10:27:04.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-06 08:16:18.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-04 02:30:44.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-04 01:38:09.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-04 12:41:47.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-04 09:08:47.xls',
            'ANEXO 12 - DON MARIO S.G.R. - 2011-05-11 03:03:26.xls',
            'ANEXO 12 - DON MARIO S.G.R. - 2011-05-11 05:07:16.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-01 02:51:50.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-01 01:52:20.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-05-20 02:19:37.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-04 11:06:33.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-04 10:16:15.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-04 11:00:36.xls',
            'ANEXO 12 - GARANTIZAR S.G.R. - 2013-02-05 08:57:11.xls',
            'ANEXO 12 - AVAL RURAL S.G.R. - 2011-05-17 04:28:56.xls',
            'ANEXO 12 - PUENTE HNOS. S.G.R. - 2011-05-26 07:39:57.XLS',
            'ANEXO 12 - AFIANZAR S.G.R. - 2011-11-16 10:20:10.xls',
            'ANEXO 12 - FONDO ESPECIFICO DE RIESGO FIDUCIARIO YAGUAR - 2011-11-15 04:53:09.xls',
            'ANEXO 12 - FONDO ESPECIFICO DE RIESGO FIDUCIARIO SOCO RIL - 2011-11-15 10:28:55.xls',
            'ANEXO 12 - FONDO ESPECIFICO DE RIESGO FIDUCIARIO PMSA I - 2011-12-01 10:20:49.xls',
            'ANEXO 12 - FRE FIDUCIARIO PARA GARANTIZAR PYMES NO SUJETAS DE CREDITO - 2011-09-05 02:31:10.xls',
            'ANEXO 12 - FRE FIDUCIARIO PARA GARANTIZAR PYMES NO SUJETAS DE CREDITO - 2011-09-05 03:13:05.xls',
            'ANEXO 12 - FONDO ESPECIFICO DE RIESGO FIDUCIARIO PROVINCIA DE SANTA FE - 2011-09-06 04:22:07.xls',
            'ANEXO 12 - LA SOCIEDAD SGR - 2013-06-27 01:52:14.xls',
            'ANEXO 12 - PROPYME S.G.R. - 2011-10-07 04:20:58.xls',
            'ANEXO 12 - C.A.E.S. S.G.R. - 2011-10-11 04:00:40.xls',
            'ANEXO 12 - SOLIDUM S.G.R. - 2011-10-12 11:20:54.xls',
            'ANEXO 12 - CARDINAL S.G.R. - 2012-08-15 04:48:43.xls',
            'ANEXO 12 - LA SOCIEDAD SGR - 2012-03-15 11:17:44.xls',
            'ANEXO 12 - GARANTIA DE VALORES S.G.R. - 2012-11-30 03:46:51.xls',
            'ANEXO 12 -  COMPANIA GENERAL DE AVALES S.G.R. - 2013-07-02 01:16:56.xls',
            'ANEXO 12 - CONFIABLES S.G.R. - 2013-12-17 05:49:30.xls',
        );


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
                //$this->save_tmp($row->idu, $filename);

                $data = array();

                $data['sgr_id'] = $row->sgr_id;
                $data['anexo'] = 'sgr_garantias';
                $data['periodo'] = '01_2011';
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
        $query = $this->db->get('sgr_garantias');
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
