<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * api
 * 
 * esta clase provee servicios para componentes externos ya sea en formato JSON 
 * u otros necesarios dependiendo del cliente.
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jan 28, 2015
 */
class Updater extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';

        /* LOAD MODEL */
        $this->load->helper('html');
        $this->load->model('updaterdb');
        $this->load->model('pacc13');
        ini_set("error_reporting", 0);
        
    }

    function Index() {
        $ignore_arr = array('Index', '__construct', '__get');
        $methods = array_diff(get_class_methods(get_class($this)), $ignore_arr);
        asort($methods);
        $links = array_map(function($item) {
            return '<a href="' . $this->module_url . strtolower(get_class()) . '/' . strtolower($item) . '">' . $item . '</a>';
        }, $methods);
        $attributes = array('class' => 'api_endpoint');
        echo ul($links, $attributes);
    }
    
    /**
     * update DB Pacc13 x dashboard
     * 
     * Funcion para unificar la informacion y hacer mas agil/dinamica la visualizacion de los mapas & graficos
     * en los diferentes Dashboards de la app.
     * 
     * @name recalcular_pacc13_dashboard
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    
    function recalcular_pacc13_dashboard($filter = null) {

        $this->updaterdb->save_dashboard_subsec_model();
    }

    /**
     * Dashboard SubSecretaría Emprendedores
     * 
     * Se define a continuación la maqueta del dashboard perteneciente al área de SubSecretaría.
     * URL referencia http://redmine.industria.gob.ar/issues/20038
     * 
     * @name dashboard_sub_emprendedores_save
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    
    function dashboard_sub_emprendedores_save($filter = null, $mode = 'json') {

        /* UPDATE DB */
        //$this->recalcular_pacc11_dashboard();
        $get_data = $this->updaterdb->get_dashboard_subsec_model($filter);

        $data = array();
        $data_sum = array();
        foreach ($get_data['result'] as $each) {

            $provincia = $each['_id']['provincia'];
            if (isset($provincia) || $provincia != "") {
                foreach ($each['_id']['estado'] as $estado) {

                    $filter = array("provincia" => $provincia, "estado" => $estado);
                    $qty = $this->updaterdb->get_quantity_by_status($filter);

                    $data[$provincia][$estado]['provincia'] = $provincia;
                    $data[$provincia][$estado]['cantidad'] = $qty;
                    $data[$provincia][$estado]['desembolso'] = $each['desembolso'];
                }
                if ($each['desembolso'] > 0)
                    $data_sum[$provincia][] = $each['desembolso'];
            }
        }

        foreach ($data_sum as $key => $value) {
            $filter = array("provincia" => $key);
            $proyects = $this->updaterdb->get_quantity_by_proyect($filter);

            $totales = array_sum($value);
            $data[$key]['desembolso'] = api_money_format($totales);
            $data[$key]['provincia'] = $key;
            $data[$key]['proyectos_desembolsados'] = $proyects;
        }
        //var_dump($data);
        $this->updaterdb->save_dashboard_tmp($data);

        //---------------->
        $this->incubadoras_por_provincia_save();
    }
    
    /**
     * Incubadoras por provincia
     * 
     * @name incubadoras_por_provincia_save
     * 
     * * @see Api13::dashboard_sub_emprendedores_save()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */

    function incubadoras_por_provincia_save($filter = null, $mode = 'json') {

        $get_data = $this->updaterdb->get_incubadoras_por_provincia_model($filter);

        $data = array();
        $data_sum = array();
        foreach ($get_data['result'] as $each) {

            $provincia = $each['_id']['incubadora_provincia'];
            if (isset($provincia) || $provincia != "") {
                foreach ($each['_id']['estado'] as $estado) {
                    $nombre_incubadora = str_replace(".", "", $this->incubadora_nombre($each['_id']['incubadora']));
                    if ($nombre_incubadora) {
                        $filter = array("provincia" => $provincia, "estado" => $estado, "incubadora" => $each['_id']['incubadora']);
                        $qty = $this->updaterdb->get_quantity_by_status_incubadora($filter);
                        $get_desembolso = $this->updaterdb->incubadoras_desembolso_model($each['_id']['incubadora'], $provincia);


                        $desembolso = 0;
                        $cantidad = 0;

                        foreach ($get_desembolso['result'] as $each_incubadora) {
                            $desembolso = $each_incubadora['desembolso'];
                            $cantidad = $each_incubadora['cantidad'];
                        }

                        $data[$provincia][$nombre_incubadora][$estado] = $qty;
                        $data[$provincia][$nombre_incubadora]['desembolso'] = api_money_format($desembolso);
                        $data[$provincia][$nombre_incubadora]['proyectos_desembolsados'] = $cantidad;
                    }
                }
            }
        }
        $this->updaterdb->save_dashboard_tmp($data, '_incu');
        // var_dump($data);
    }
    
    
     /**
     * Devolver incubadora por id
     * 
     * Una vez seleccionada una incubadora, necesito una función que al mandarle un id devuelva esto.
     * 
     * URL referencia http://redmine.industria.gob.ar/issues/22998
     * 
     * @name incubadora_nombre
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type id
     * 
     * @return array $options{
     *       @var provincia int provincia de la incubadora elegida",
     *       @var proyectos_presentados  int
     *       @var proyectos_pre_aprobados  int
     *       @var proyectos_aprobados int
     *       @var proyectos_rechazados int
     *       @var proyectos_finalizados int
     *       @var desembolsos_realizados int    
     * }
     */
    function incubadora_nombre($id = null) {

        //$query = array('4899' => 'INCUBA', 'id' => $id);
        $query = array('id' => (float) $id);
        $container = 'container.agencias';
        $data = $this->pacc13->incubadora_nombre_model($query, $container);

        return $data;
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */