<?php

/**
 * CONTROLADOR PARA VER EL DETALLE DE UN PROYECTO
 * 
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Resumen_proyecto extends MX_Controller {

    function __construct() {
        parent::__construct();
        //---base variables
        $this->load->library('parser');
        $this->load->model('pacc11/pacc11');
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $parts = $this->uri->segment_array();
        // $ipstr=$parts[4].'/'.$parts[5];
    }

    /*
     * FUNCION PRINCIPAL
     */

    function Index() {
        $this->dashboard_resumen_proyecto();
    }

    /**
     * Dashboard del COORDINADOR DE descripcionS
     */
    function dashboard_resumen_proyecto() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_resumen_proyecto.json');
    }

    function table_resumen_proyecto() {
        $this->load->module('dashboard');
        $this->load->module('pacc11/api11');

        $renderData['title'] = "Estado de los Proyectos Presentados";
        $renderData['hidden'] = "hidden";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template = "dashboard/widgets/box_info.php";
        $renderData['content'] = $this->parser->parse('reload-table-resumen-proyecto', $renderData, true, true);

        return $this->dashboard->widget($template, $renderData, true, true);
    }

    function tabla_reload($param, $type, $program = null) {


        $this->load->module('dashboard');
        $this->load->module('pacc11/api11');
        $this->load->module('pacc13/api13');
        $template = "dashboard/widgets/box_info.php";
        //var_dump($param);

        /* TYPES
         * nro, cuit, proyecto
         */

        switch ($type) {
            case 'ip':
                $filter['ip'] = (string) $param;
                break;

            case 'cuit':
                $filter['cuit'] = $param;
                break;

            default:
                $filter['id'] = $param;
                break;
        }

        /* PROGRAM */
        switch ($program) {
            case 'pacc11':
                $renderData = $this->api11->buscar($filter, 'array');
                break;

            default:
                $renderData = $this->api13->buscar($filter, 'array');
                break;
        }

        echo $this->parser->parse('reload-table-resumen-proyecto', $renderData, true, true);
    }

    function buscador_resumen_proyecto() {

        $this->load->module('dashboard');
        $this->load->module('pacc11/api');
        $renderData['title'] = "Buscador de los Proyectos Presentados";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template = "dashboard/widgets/box_info.php";
        $renderData['content'] = $this->parser->parse('buscador-resumen-proyecto', $renderData, true, true);

        return $this->dashboard->widget($template, $renderData);
    }

}
