<?php

/**
 * @class genia
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sgr_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');
        $this->idu = (int) $this->session->userdata('iduser');
        if (!$this->idu)
            header("$this->module_url/user/logout");
        /* Set locale to Spansih */
    }

    /* RETURN ANEXOS */

    function get_anexos() {
        $container = 'container.sgr_anexos';
        $result = $this->mongo->db->$container->find();
        return $result;
    }

    function get_anexo($anexo) {
        $container = 'container.sgr_anexos';
        $query['number'] = $anexo;
        $result = $this->mongo->db->$container->findOne($query);
        return $result;
    }

    function get_period_info($anexo, $sgr_id, $period) {
        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id');
        $query = array("status" => 'activo', "anexo" => $anexo, "sgr_id" => $sgr_id, "period" => $period);
        $result = $this->mongo->db->$container->findOne($query, $fields);
        return $result;
    }

    function get_period_count($anexo, $sgr_id, $period) {
        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id');
        $query = array('status' => 'rectificado', 'anexo' => $anexo, 'sgr_id' => $sgr_id, 'period' => $period);
        $result = $this->mongo->db->$container->find($query, $fields);
        return $result->count();
    }

    function get_period_filename($filename) {
        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id', 'idu');
        $query = array("filename" => $filename);
        $result = $this->mongo->db->$container->findOne($query, $fields);
        return $result;
    }

    //processes
    function get_processed($anexo, $sgr_id, $year = null) {
        $rtn = array();
        $regex = new MongoRegex('/' . $year . '/');
        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename');
        $query = array("status" => 'activo', "anexo" => $anexo, "sgr_id" => $sgr_id, 'period' => $regex);
        $result = $this->mongo->db->$container->find($query, $fields);

        foreach ($result as $list) {
            $rtn[] = $list;
        }
        return $rtn;
    }

    function get_sgr() {
        $rtn = array();
        $this->load->model('user/user');
        $idu = (int) $this->idu;
        $data = array();
        // Listado de empresas
        $container = 'container.empresas';
        $fields = array('id', '1695', '4651', '1693', '1703');
        $query = array("owner" => $idu, "6026" => '30', "status" => 'activa');
        $result = $this->mongo->db->$container->find($query, $fields);

        foreach ($result as $empresa) {
            unset($empresa['_id']);
            $rtn[] = $empresa;
        }
        return $rtn;
    }

    /* RETURN EMPRESAS */

    function get_empresas($query) {
        $rtn = array();
        //$query['status'] = 'activa';
        $fields = array('id',
            'status'
            , '1693'  //     Nombre de la empresa
            , '1695'  //     CUIT
            , '7819' // 	Longitud
            , '7820' // 	Latitud
            , '4651' // 	Provincia
            , '4653' //     Calle Ruta
            , '4654' //     Nro /km
            , '4655' //     Piso
            , '4656' //     Dto Oficina
            , '1699' // 	Partido
            , '1694'    // Tipo de empresa
            , '1698'    //cod postal
            , '1701'    //telefonos
            , '1703'    //email
            , '1704'    //web
            , '1711'    //Cantidad de Empleados actual  
            /* contacto */
            , '7876'    // Apellido y Nombre del Contacto
            , '7877'    // E-mail del Contacto
            , '7878'    // Rubro de la Empresa                
            /* PLANTA */
            , '7879'    // Superficie Cubierta
            , '7880'    // Posesi�n (idopcion = 729)
            , '1715'    // Productos o servicios que Ofrece
            /* PRODUCCION */
            , '7881'    // Tiene componentes importados (idopcion = 15)
            , '7882'    // Pueden ser reemplazados? (idopcion = 15)
            , '7883'    // Tiene capacidad para exportar? (idopcion = 15)
            , '1716'    // Mercado destino (idopcion = 88)
            , '7884'    // Proveedores
            , 'C7663'    // La empresa ha realizado o realiza acciones vinculadas a la Responsabilidad Social (idopcion = 716)
            , '7665'    // Registro �nico de Organizaciones de Responsabilidad Social (idopcion = 715)
            , 'origenGenia'    // usuario que tocó la empresa
        );
        $container = 'container.empresas';
        $sort = array('origenGenia' => -1);
        $result = $this->mongo->db->$container->find($query, $fields);
        $result->limit(2500)->sort($sort);
        foreach ($result as $empresa) {
            unset($empresa['_id']);
            $rtn[] = $empresa;
        }
        return $rtn;
    }

    /* RETURN VISITAS */

    function get_empresas_raw($query) {
        $rtn = array();
        //$query['status'] = 'activa';
        $fields = array('id',
            'status'
            , '1693'  //     Nombre de la empresa
            , '1695'  //     CUIT
            , '7819' // 	Longitud
            , '7820' // 	Latitud
            , '4651' // 	Provincia
            , '4653' //     Calle Ruta
            , '4654' //     Nro /km
            , '4655' //     Piso
            , '4656' //     Dto Oficina
            , '1699' // 	Partido
            , '1694'    // Tipo de empresa
            , '1698'    //cod postal
            , '1701'    //telefonos
            , '1703'    //email
            , '1704'    //web
            , '1711'    //Cantidad de Empleados actual  
            /* contacto */
            , '7876'    // Apellido y Nombre del Contacto
            , '7877'    // E-mail del Contacto
            , '7878'    // Rubro de la Empresa                
            /* PLANTA */
            , '7879'    // Superficie Cubierta
            , '7880'    // Posesi�n (idopcion = 729)
            , '1715'    // Productos o servicios que Ofrece
            /* PRODUCCION */
            , '7881'    // Tiene componentes importados (idopcion = 15)
            , '7882'    // Pueden ser reemplazados? (idopcion = 15)
            , '7883'    // Tiene capacidad para exportar? (idopcion = 15)
            , '1716'    // Mercado destino (idopcion = 88)
            , '7884'    // Proveedores
            , 'C7663'    // La empresa ha realizado o realiza acciones vinculadas a la Responsabilidad Social (idopcion = 716)
            , '7665'    // Registro �nico de Organizaciones de Responsabilidad Social (idopcion = 715)
            , 'origenGenia'    // usuario que tocó la empresa
        );
        $container = 'container.empresas';
        $sort = array('origenGenia' => -1);
        $result = $this->mongo->db->$container->find($query, $fields);
        $result->sort($sort);
        foreach ($result as $empresa) {
            unset($empresa['_id']);
            $rtn[] = $empresa;
        }
        return $rtn;
    }

    function get_empresas_id($query) {
        $rtn = array();
        //$query['status'] = 'activa';


        $container = 'container.empresas';
        $sort = array('id' => 1);
        $result = $this->mongo->db->$container->find($query);
        $result->sort($sort);
        foreach ($result as $empresa) {
            $empresaRtn['id'] = $empresa['id'];
            $empresaRtn['checksum'] = md5(json_encode($empresa));
            $rtn[] = $empresaRtn;
        }
        return $rtn;
    }

    function array_delete($value, $array) {
        $array = array_diff($array, array($value));
        return $array;
    }

}
