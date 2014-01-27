<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_061 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '061';
        $this->idu = (int) $this->session->userdata('iduser');
        /*SWITCH TO SGR DB*/
        $this->load->library('cimongo/cimongo','','sgr_db');
        $this->sgr_db->switch_db('sgr');
        
        
        if (!$this->idu) {
            header("$this->module_url/user/logout");
        }

        /* DATOS SGR */
        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
        }
    }

    function check($parameter) {
        /**
         *   Funcion ...
         * 
         * @param 
         * @type PHP
         * @name ...
         * @author Diego
         *
         * @example .... CUIT_SOCIO_INCORPORADO 	TIENE_VINCULACION	CUIT_VINCULADO	RAZON_SOCIAL_VINCULADO	TIPO_RELACION_VINCULACION	PORCENTAJE_ACCIONES
         * */
        $defdna = array(
            1 => 'CUIT_SOCIO_INCORPORADO', //CUIT_SOCIO_INCORPORADO
            2 => 'TIENE_VINCULACION', //TIENE_VINCULACION
            3 => 'CUIT_VINCULADO', //CUIT_VINCULADO
            4 => 'RAZON_SOCIAL_VINCULADO', //RAZON_SOCIAL_VINCULADO
            5 => 'TIPO_RELACION_VINCULACION', //TIPO_RELACION_VINCULACION
            6 => 'PORCENTAJE_ACCIONES', //"PORCENTAJE_ACCIONES"
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter = array_map('trim', $parameter);
        $parameter = array_map('addSlashes', $parameter);

        $parameter['period'] = $period;

        $parameter['origin'] = 2013;
        $id = $this->app->genid_sgr($container);

        $result = $this->app->put_array_sgr($id, $container, $parameter);

        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    function save_period($parameter) {
        /* ADD PERIOD */
        $container = 'container.sgr_periodos';
        $period = $this->session->userdata['period'];
        $id = $this->app->genid_sgr($container);
        $parameter['period'] = $period;
        $parameter['status'] = 'activo';
        $parameter['idu'] = $this->idu;
        /*
         * VERIFICO PENDIENTE           
         */
        $get_period = $this->sgr_model->get_period_info($this->anexo, $this->sgr_id, $period);
        $this->update_period($get_period['id'], $get_period['status']);

        $result = $this->app->put_array_sgr($id, $container, $parameter);

        if ($result) {
            /* BORRO SESSION RECTIFY */
            $this->session->unset_userdata('rectify');
            $this->session->unset_userdata('others');
            $this->session->unset_userdata('period');
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    function update_period($id, $status) {
        $options = array('upsert' => true, 'safe' => true);
        $container = 'container.sgr_periodos';
        $query = array('id' => (integer) $id);
        $status = 'rectificado';
        $parameter = array('status' => $status);
        $rs = $this->mongo->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_info($anexo, $parameter) {

        $headerArr = array("CUIT_SOCIO_INCORPORADO", "TIENE_VINCULACION", "CUIT_VINCULADO", "RAZON_SOCIAL_VINCULADO", "TIPO_RELACION_VINCULACION", "PORCENTAJE_ACCIONES");
        $data = array($headerArr);
        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table');
        return $this->table->generate($data);
    }

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);

        foreach ($result as $list) {

            /* Vars */

            $cuit = str_replace("-", "", $list['1695']);
            $brand_name = $list['1693'];


            $this->load->model('app');

            $new_list = array();
            $new_list['TIPO_OPERACION'] = $tipo_operacion[$list['5779'][0]];
            $new_list['SOCIO'] = "(" . $list['5272'][0] . ") " . $tipo_socio[$list['5272'][0]] . "</br>" . $cuit . "</br>" . $brand_name;
            $new_list['LOCALIDAD'] = $list['1700'] . "</br>" . $partido[$list['1699'][0]] . "</br>" . $provincia[$list['4651'][0]] . "</br>[" . $list['1698'] . "]";
            $new_list['DIRECCION'] = $list['4653'] . "</br>" . "Nro." . $list['4654'] . "</br>Piso/Dto/Of." . $list['4655'] . " " . $list['4656'];
            $new_list['TELEFONO'] = "(" . $list['CODIGO_AREA'] . ") " . $list['1701'];
            $new_list['EMAIL'] = $list['1703'] . "</br>" . $list['1704'];
            $new_list['CODIGO_ACTIVIDAD'] = $list['5208'] . "<br>[SECTOR]";
            $new_list['"ANIO"'] = $inner_table;
            $new_list['CONDICION_INSCRIPCION_AFIP'] = $promedio . "<br/>" . $inscripcion_iva[$list['5596'][0]];
            $new_list['EMPLEADOS'] = $list['CANTIDAD_DE_EMPLEADOS'];
            $new_list['ACTA'] = "Tipo: " . $acta_tipo[$list['5253'][0]] . "<br/>Acta: " . $list['5255'] . "<br/>Nro." . $list['5254'] . "<br/>Efectiva:" . $list['FECHA_DE_TRANSACCION'];
            $new_list['MODALIDAD'] = "Modalidad " . $tipo_transaccion[$list['5252'][0]] . "<br/>Capital Suscripto:" . $list['5597'] . "<br/>Acciones Suscriptas: " . $list['5250'] . "<br/>Capital Integrado: " . $list['5598'] . "<br/>Acciones Integradas:" . $list['5251'];
            $new_list['CEDENTE_CUIT'] = $list['5248'] . "<br/>" . $caract_transferencia[$list['5292'][0]];

            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
