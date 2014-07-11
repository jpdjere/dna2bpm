<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mysql_model_12 extends CI_Model {

    function mysql_model_12() {
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

    function active_periods_dna2_custom($anexo, $period) {


        $period = 'container.sgr_periodos';
        $anexo_12 = 'container.sgr_anexo_12';

        $files_arr = array();
        $fields = array('filename');



        echo $var;
        $anexo_query = array(
            'anexo' => '12',
            "status" => "activo",
            "origen" => "forms2",
            "filename" => "ANEXO 12 - ACINDAR PYMES S.G.R. - 2013-07-05 06:11:37.xls"
        );

        $result = $this->mongo->sgr->$period->find($anexo_query);
        foreach ($result as $file) {
            $files_arr[] = $file['filename'];
        }

        debug($files_arr);

        $ids_arr = array();

        foreach ($files_arr as $eachFile) {
            $query = array("filename" => $eachFile);
            $result2 = $this->mongo->sgr->$anexo_12->find($query);
            //debug($result2.count());
            foreach ($result2 as $each) {
                $ids_arr[] = $each['id'];
            }
        }

        $eachid = "";

        foreach ($ids_arr as $eachid) {
            $count_arr = array();
            $id_query = array('id' => (int) $eachid);
            $id_query_one = array('id' => (int) $eachid, "justOne" => true);

            $result3 = $this->mongo->sgr->$anexo_12->find($id_query);

            foreach ($result3 as $count)
                $count_arr[] = $count['id'];

            $count_result = count($count_arr);
            if ($count_result == 2) {
                $result4 = $this->mongo->sgr->$anexo_12->findOne($id_query);
                debug($result4['_id']);


                $delete_qry = array('_id', new MongoId($result4[$id]));
                $x = $this->mongo->sgr->$anexo_12->remove(array('_id' => new MongoId($result4['_id'])));
                var_dump($x, $result4['_id']);
            }
        }

        //var_dump(count($check), count($files_arr));
        echo "fin";
        exit();



        /* CLEAR TEMP DATA */
        $this->clear_tmp();

        foreach ($check as $file_name) {
            /* TRANSLATE ANEXO NAME */
            $anexo_dna2 = translate_anexos_dna2($anexo);
            $this->db->where('estado', 'activo');
            $this->db->where('archivo', $file_name);
            $this->db->where('anexo', $anexo_dna2);
            //$this->db->where('sgr_id', 1676213769);


            $query = $this->db->get('forms2.sgr_control_periodos');

            foreach ($query->result() as $row) {

                debug($row);

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
//                    if ($row->archivo) {
//                        $already_update = $this->already_updated($row->anexo, $nro_orden, $row->archivo);
//                        if (!$already_update)
//                            $this->anexo_data_tmp($anexo_dna2, $row->archivo);
//                    }
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

    function active_periods_dna2_one($filename) {





        /* CLEAR TEMP DATA */
        $this->clear_tmp();

        /* TRANSLATE ANEXO NAME */
        $anexo_dna2 = translate_anexos_dna2($anexo);
        $this->db->where('estado', 'activo');
        $this->db->where('archivo', urldecode($filename));
        $this->db->where('anexo', $anexo_dna2);
        $query = $this->db->get('forms2.sgr_control_periodos');

        debug($query->result());

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

        $this->db->select(
                'id,nro_orden,
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



        $query = $this->db->get($anexo);
        $parameter = array();
        foreach ($query->result() as $row) {

            $parameter = array();

            $parameter['id'] = (float) $row->id;
            $parameter['origen'] = 'forms2';

            $parameter[5214] = (string) $row->nro_orden;
            $parameter[5216] = (string) $row->tipo_garantia;
            $parameter[5222] = (string) $row->tasa;
            $parameter[5727] = (string) $row->nro_operacion_bolsa;


            list($arr['Y'], $arr['m'], $arr['d']) = explode("-", $row->fecha_alta);
            $parameter[5215] = $arr;

            $parameter[5349] = (string) str_replace("-", "", $row->cuit_socio_participe);
            $parameter[5726] = (string) str_replace("-", "", $row->librador_cuit);
            $parameter[5351] = (string) str_replace("-", "", $row->cuit_acreedor);

            /* FLOAT */
            $parameter[5218] = (float) $row->monto;
            $parameter[5221] = (float) $row->importe_Cred_Garant;
            $parameter[5223] = (float) $row->puntos_adicionales;

            /* INTEGER */
            $parameter[5224] = (int) $row->plazo;
            $parameter[5225] = (int) $row->gracia;

            $parameter[5219] = (string) $row->moneda;
            $parameter[5758] = (string) $row->moneda_Cred_Garant;

            $parameter[5226] = (string) $row->periodicidad;
            $parameter[5227] = (string) $row->sistema;

            $parameter[5222] = (string) $row->tasa;


            $parameter['idu'] = (float) $row->idu;
            $parameter['filename'] = (string) $row->filename;
            $this->save_anexo_12_tmp($parameter, $anexo);
        }
    }

    /* SAVE FETCHS ANEXO 12 DATA */

    function already_period($filename) {

        $container = 'container.sgr_periodos';
        $query = array("filename" => $filename);
        $result = $this->mongo->sgr->$container->findOne($query);
        if ($result)
            return true;
    }

    function already_updated($anexo, $nro_orden, $filename) {

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $filename, "nro_orden" => $nro_orden);
        $result = $this->mongo->sgr->$container->findOne($query);

        if ($result)
            return true;
    }

    function save_anexo_12_tmp($parameter, $anexo) {
        $parameter = (array) $parameter;
        $token = $this->idu;
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_12';
        /* TRANSLATE ANEXO NAME */

        $id = $this->app->genid_sgr($container);
        $result = $this->app->put_array_sgr($id, $container, $parameter);
        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    function update() {

        $array = array(131475,
            127594,
            704257
        );

        foreach ($array as $each) {
            $data = array();
            $data['5219'] = "2";
            $options = array('upsert' => true, 'safe' => true);
            $container = 'container.sgr_anexo_12';
            $query = array('id' => 127594);
            return $this->mongo->db->$container->update($query, $data, $options);
        }
    }

}
