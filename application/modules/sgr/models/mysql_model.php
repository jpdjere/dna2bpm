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




        $files_arr = array('CAPITAL SOCIAL - ACINDAR PYMES S.G.R. - 2011-03-28 01:19:01.xls',
            'CAPITAL SOCIAL - ACINDAR PYMES S.G.R. - 2011-03-28 03:05:32.xls',
            'CAPITAL SOCIAL - ACINDAR PYMES S.G.R. - 2011-04-06 04:47:42.xls',
            'CAPITAL SOCIAL - ACINDAR PYMES S.G.R. - 2011-04-08 12:31:28.xls',
            'CAPITAL SOCIAL - AFFIDAVIT S.G.R. - 2011-03-22 01:36:36.xls',
            'CAPITAL SOCIAL - AFFIDAVIT S.G.R. - 2011-03-23 04:31:27.xls',
            'CAPITAL SOCIAL - AFFIDAVIT S.G.R. - 2011-03-29 04:53:41.xls',
            'CAPITAL SOCIAL - AFFIDAVIT S.G.R. - 2013-05-16 03:54:17.xls',
            'CAPITAL SOCIAL - AFIANZAR S.G.R. - 2011-03-17 05:20:40.xls',
            'CAPITAL SOCIAL - AFIANZAR S.G.R. - 2011-03-17 05:27:03.xls',
            'CAPITAL SOCIAL - AFIANZAR S.G.R. - 2011-03-17 05:30:18.xls',
            'CAPITAL SOCIAL - AGROAVAL S.G.R. - 2011-03-31 01:41:30.xls',
            'CAPITAL SOCIAL - AGROAVAL S.G.R. - 2011-03-31 01:52:40.xls',
            'CAPITAL SOCIAL - AGROAVAL S.G.R. - 2011-03-31 01:59:53.xls',
            'CAPITAL SOCIAL - AGROAVAL S.G.R. - 2011-04-01 06:05:52.xls',
            'CAPITAL SOCIAL - AGROAVAL S.G.R. - 2013-01-10 09:01:20.xls',
            'CAPITAL SOCIAL - AMERICANA DE AVALES S.G.R. - 2013-07-01 03:59:15.xls',
            'CAPITAL SOCIAL - AMERICANA DE AVALES S.G.R. - 2013-09-05 02:57:04.xls',
            'CAPITAL SOCIAL - AVAL RURAL S.G.R. - 2013-11-05 11:06:22.xls',
            'CAPITAL SOCIAL - AVALUAR S.G.R. - 2011-03-18 01:12:39.xls',
            'CAPITAL SOCIAL - AVALUAR S.G.R. - 2011-03-18 01:13:04.xls',
            'CAPITAL SOCIAL - AVALUAR S.G.R. - 2011-03-30 04:40:59.xls',
            'CAPITAL SOCIAL - AVALUAR S.G.R. - 2011-09-27 02:57:31.xls',
            'CAPITAL SOCIAL - AVALUAR S.G.R. - 2012-11-15 02:02:26.xls',
            'CAPITAL SOCIAL - C.A.E.S. S.G.R. - 2011-10-11 02:32:23.xls',
            'CAPITAL SOCIAL - CAMPO AVAL S.G.R. - 2011-03-29 06:33:36.xls',
            'CAPITAL SOCIAL - CAMPO AVAL S.G.R. - 2011-03-29 06:35:22.xls',
            'CAPITAL SOCIAL - CAMPO AVAL S.G.R. - 2012-03-20 01:26:23.xls',
            'CAPITAL SOCIAL - CARDINAL S.G.R. - 2011-03-31 05:06:07.xls',
            'CAPITAL SOCIAL - CARDINAL S.G.R. - 2011-04-01 12:06:05.xls',
            'CAPITAL SOCIAL - CONFIABLES S.G.R. - 2011-03-11 01:11:36.xls',
            'CAPITAL SOCIAL - CONFIABLES S.G.R. - 2011-03-11 01:24:02.xls',
            'CAPITAL SOCIAL - CONFIABLES S.G.R. - 2011-03-11 12:44:48.xls',
            'CAPITAL SOCIAL - CONFIABLES S.G.R. - 2011-03-21 12:25:36.xls',
            'CAPITAL SOCIAL - CUYO AVAL S.G.R. - 2011-04-19 05:00:47.xls',
            'CAPITAL SOCIAL - CUYO AVAL S.G.R. - 2011-04-19 06:15:20.xls',
            'CAPITAL SOCIAL - CUYO AVAL S.G.R. - 2011-04-29 10:46:48.xls',
            'CAPITAL SOCIAL - DON MARIO S.G.R. - 2011-04-25 03:04:50.xls',
            'CAPITAL SOCIAL - DON MARIO S.G.R. - 2011-05-03 12:34:09.xls',
            'CAPITAL SOCIAL - DON MARIO S.G.R. - 2011-05-04 02:10:05.xls',
            'CAPITAL SOCIAL - DON MARIO S.G.R. - 2011-05-04 02:38:45.xls',
            'CAPITAL SOCIAL - DON MARIO S.G.R. - 2011-05-10 04:57:55.xls',
            'CAPITAL SOCIAL - DON MARIO S.G.R. - 2013-03-19 04:09:15.xls',
            'CAPITAL SOCIAL - FIDUS S.G.R. - 2011-03-21 03:03:55.xls',
            'CAPITAL SOCIAL - FIDUS S.G.R. - 2011-03-21 03:07:37.xls',
            'CAPITAL SOCIAL - FIDUS S.G.R. - 2011-03-21 03:08:01.xls',
            'CAPITAL SOCIAL - FOGABA - FONDO DE GARANTIAS DE BUENOS AIRES S.G.R. - 2012-03-29 02:28:33.xls',
            'CAPITAL SOCIAL - GARANTIA DE VALORES S.G.R. - 2011-03-10 02:15:25.xls',
            'CAPITAL SOCIAL - GARANTIA DE VALORES S.G.R. - 2011-03-10 04:09:07.xls',
            'CAPITAL SOCIAL - GARANTIA DE VALORES S.G.R. - 2011-11-02 05:05:01.xls',
            'CAPITAL SOCIAL - GARANTIZAR S.G.R. - 2011-02-17 04:31:22.xls',
            'CAPITAL SOCIAL - GARANTIZAR S.G.R. - 2011-06-27 10:48:06.xls',
            'CAPITAL SOCIAL - GARANTIZAR S.G.R. - 2011-07-15 11:57:32.xls',
            'CAPITAL SOCIAL - GARANTIZAR S.G.R. - 2011-07-18 11:37:31.xls',
            'CAPITAL SOCIAL - GARANTIZAR S.G.R. - 2011-09-05 04:53:00.xls',
            'CAPITAL SOCIAL - GARANTIZAR S.G.R. - 2011-09-06 12:05:02.xls',
            'CAPITAL SOCIAL - GARANTIZAR S.G.R. - 2012-02-02 11:21:34.xls',
            'CAPITAL SOCIAL - INTERGARANTIAS S.G.R. - 2011-04-07 04:07:41.xls',
            'CAPITAL SOCIAL - INTERGARANTIAS S.G.R. - 2011-04-12 02:42:25.xls',
            'CAPITAL SOCIAL - INTERGARANTIAS S.G.R. - 2011-04-13 05:52:54.xls',
            'CAPITAL SOCIAL - LA SOCIEDAD SGR - 2011-09-07 06:22:08.xls',
            'CAPITAL SOCIAL - LA SOCIEDAD SGR - 2011-11-11 12:16:33.xls',
            'CAPITAL SOCIAL - LA SOCIEDAD SGR - 2011-12-14 02:42:14.xls',
            'CAPITAL SOCIAL - LA SOCIEDAD SGR - 2012-04-12 02:47:40.xls',
            'CAPITAL SOCIAL - LA SOCIEDAD SGR - 2012-04-19 01:50:34.xls',
            'CAPITAL SOCIAL - LA SOCIEDAD SGR - 2012-05-08 02:27:19.xls',
            'CAPITAL SOCIAL - LA SOCIEDAD SGR - 2012-05-08 02:33:43.xls',
            'CAPITAL SOCIAL - LA SOCIEDAD SGR - 2012-12-13 12:12:59.xls',
            'CAPITAL SOCIAL - LA SOCIEDAD SGR - 2012-12-13 12:24:52.xls',
            'CAPITAL SOCIAL - LA SOCIEDAD SGR - 2013-01-16 02:36:47.xls',
            'CAPITAL SOCIAL - LA SOCIEDAD SGR - 2013-03-18 12:31:41.xls',
            'CAPITAL SOCIAL - LA SOCIEDAD SGR - 2013-03-19 01:31:28.xls',
            'CAPITAL SOCIAL - LOS GROBO S.G.R. - 2011-03-18 01:07:51.xls',
            'CAPITAL SOCIAL - LOS GROBO S.G.R. - 2011-04-26 03:32:27.xls',
            'CAPITAL SOCIAL - LOS GROBO S.G.R. - 2011-04-27 04:55:39.xls',
            'CAPITAL SOCIAL - LOS GROBO S.G.R. - 2013-05-30 06:18:57.xls',
            'CAPITAL SOCIAL - MACROAVAL S.G.R. - 2011-03-14 03:53:41.xls',
            'CAPITAL SOCIAL - MACROAVAL S.G.R. - 2011-03-14 04:03:14.xls',
            'CAPITAL SOCIAL - MACROAVAL S.G.R. - 2011-03-14 06:11:10.xls',
            'CAPITAL SOCIAL - MACROAVAL S.G.R. - 2011-03-28 03:57:14.xls',
            'CAPITAL SOCIAL - PROPYME S.G.R. - 2011-10-07 03:53:37.xls',
            'CAPITAL SOCIAL - PUENTE HNOS. S.G.R. - 2011-03-15 04:26:15.xls',
            'CAPITAL SOCIAL - PUENTE HNOS. S.G.R. - 2011-03-15 04:32:02.xls',
            'CAPITAL SOCIAL - PUENTE HNOS. S.G.R. - 2011-03-15 04:34:55.xls',
            'CAPITAL SOCIAL - PUENTE HNOS. S.G.R. - 2011-03-15 06:55:54.xls',
            'CAPITAL SOCIAL - PUENTE HNOS. S.G.R. - 2011-03-16 11:41:03.xls',
            'CAPITAL SOCIAL - PUENTE HNOS. S.G.R. - 2012-09-20 11:18:24.xls',
            'CAPITAL SOCIAL - SOLIDUM S.G.R. - 2011-10-11 04:01:09.xls',
            'CAPITAL SOCIAL - VINCULOS S.G.R. - 2011-03-28 04:07:37.xls',
            'CAPITAL SOCIAL - VINCULOS S.G.R. - 2011-03-28 04:12:22.xls',
            'CAPITAL SOCIAL - VINCULOS S.G.R. - 2011-03-28 04:17:37.xls',
            'CAPITAL SOCIAL - VINCULOS S.G.R. - 2011-03-28 04:33:45.xls',
        );


        foreach ($files_arr as $files) {
            $filename = $files;
            $files = explode("-", $files);


            $this->db->select('idu, archivo,anexo');
            $this->db->like('archivo', trim($files[1]));
            $this->db->join('forms2.idsent', 'forms2.idsent.id = forms2.sgr_control_periodos.sgr_id', 'inner');
            $this->db->limit(1);
            $query = $this->db->get('forms2.sgr_control_periodos');
            $each = array();
            $parameter = array();
            foreach ($query->result() as $row) {
                $this->save_tmp($row->idu, $filename);
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
