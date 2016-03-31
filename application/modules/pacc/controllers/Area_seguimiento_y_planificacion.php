<?php

/**
 * Fake Wiring
 *
 * @author juanb
 * @date   Jan 16, 2015
 * 
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Area_seguimiento_y_planificacion extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';

        /* LOAD */
        $this->load->model('model_seguimiento_planificacion');
        $this->load->module('dashboard');
    }

    function Index() {
        $this->dashboard_area_seguimiento_y_planificacion();
    }

    function dashboard_area_seguimiento_y_planificacion() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_area_seguimiento_y_planificacion.json');
    }

    function cant_pde_sin_evaluacion() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_5_30_mas30/cant_pde_sin_evaluacion';
        //  $data['json_url'] = $this->base_url.'/api/cant_proyectos_reclam_mail/
        $data['class'] = "data_bars";
        $data['title'] = "Cantidad de Proyectos PDE presentados sin evaluación (PreAprobación, observación técnica o rechazo)";

        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_pn_sin_evaluacion() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_5_30_mas30/cant_pn_sin_evaluacion';

        //  $data['json_url'] = $this->base_url.'/api/cant_proyectos_reclam_cd/;
        $data['class'] = "data_bars";
        $data['title'] = "Cantidad de Proyectos PN presentados sin evaluación (PreAprobación, observación técnica o rechazo)";

        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_pde_preaprobados() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_10_30_mas30/cant_pde_preaprobados';
        // $data['json_url'] = $this->base_url.'/api/proyectos_desistidos_preapro_apro/
        $data['class'] = "data_bars";
        $data['title'] = "Cantidad de Proyectos PDE PreAprobados sin presentar documentación formal";

        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_pn_preaprobados() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_10_30_mas30/cant_pn_preaprobados';
        //$data['json_url'] = $this->base_url.'/api/proyectos_pde_baja/;
        $data['class'] = "data_bars";
        $data['title'] = "Cantidad de Proyectos PN PreAprobados sin presentar documentación formal ";

        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_pde_observados_sin_respuesta() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_10_30_mas30/cant_pde_observados_sin_respuesta';
        //  $data['json_url'] = $this->base_url.'/api/proyectos_pp_baja/';
        $data['class'] = "data_bars";
        $data['title'] = "Cantidad de Proyectos PDE Observados Técnicos sin presentar respuesta";

        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_pn_observados_sin_respuesta() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_10_30_mas30/cant_pn_observados_sin_respuesta';
        //$data['json_url'] = $this->base_url.'/api/peticiones_redmine_pde/
        $data['class'] = "data_bars";
        $data['title'] = "Cantidad de Proyectos PN Observados Técnicos sin presentar respuesta";

        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_pde_con_documentacion_sin_evaluacion() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_10_30_mas30/cant_pde_con_documentacion_sin_evaluacion';
        //$data['json_url'] = $this->base_url.'/api/peticiones_redmine_pp/';
        $data['class'] = "data_bars";
        $data['title'] = "Cantidad de Proyectos PDE con ingreso de Documentación Formal sin evaluación (Aprobación, formalización, observación formal o rechazo).";

        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_pn_con_documentacion_sin_evaluacion() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_10_30_mas30/cant_pn_con_documentacion_sin_evaluacion';
        //$data['json_url'] = $this->base_url.'/api/peticiones_redmine_pp/';
        $data['class'] = "data_bars";
        $data['title'] = "Cantidad de Proyectos PN con ingreso de Documentación Formal sin evaluación (Aprobación, formalización, observación formal o rechazo).";

        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_pde_observados_formal_sin_respuesta() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_10_30_mas30/cant_pde_observados_formal_sin_respuesta';
        //$data['json_url'] = $this->base_url.'/api/cd_otros_temas/;
        $data['class'] = "data_bars";
        $data['title'] = "Cantidad de Proyectos PDE Observados Formal sin presentar respuesta ";
        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_pn_observados_formal_sin_respuesta() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_10_30_mas30/cant_pn_observados_formal_sin_respuesta';
        $data['json_url'] = $this->base_url . '/api/cd_otros_temas/';
        $data['class'] = "data_bars";
        $data['title'] = "Cantidad de Proyectos PN Observados Formal sin presentar respuesta ";
        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_pn_aprobados_sin_ejecucion() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Cantidad de Proyectos PN aprobados sin ejecución (con modalidad de anticipo sin presentar SDA)";
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_90_120_mas120/cant_pn_aprobados_sin_ejecucion';
        $data['class'] = 'data_bars';
        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_sde_observadas() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Cantidad de Proyectos Rechazados";
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_10_30_mas30/cant_sde_observadas';
        $data['class'] = 'data_bars';
        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_rendiciones_observadas() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Cantidad de rendiciones observadas ";
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_10_30_mas30/cant_rendiciones_observadas';
        $data['class'] = 'data_bars';
        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    function cant_pde_1sde_sin_respuesta() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Cantidad de Proyectos PDE con al menos una SDE observada sin presentar respuesta";
        $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_15_30_mas30/cant_pde_1sde_sin_respuesta';
        $data['class'] = 'data_bars';
        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    //HASTA ACÁ CABLEADO POR DIEGO
    // function cant_pnpp_1sda_sin_respuesta() {
    //     
    //     $template = "dashboard/widgets/box_info.php";
    //     $data = array();
    //     $data['title'] = "Cantidad de Proyectos PN/PP con al menos una SDA ó rendición observada sin presentar respuesta";
    //     $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_15_30_mas30';
    //     $data['class'] = 'data_bars';
    //     return $this->parser->parse('pacc/bars', $data, true, true);
    // }
    // function cant_pde_sde_sin_pagar() {
    //     
    //     $template = "dashboard/widgets/box_info.php";
    //     $data = array();
    //     $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_5_30_mas30';
    //     //$data['json_url'] = $this->base_url.'/api/peticiones_redmine_pde/
    //     $data['class'] = "data_bars";
    //     $data['title'] = "Cantidad de Proyectos PDE con dictámenes SDE aprobados sin pagar";
    //     return $this->parser->parse('pacc/bars', $data, true, true);
    // }
    // //HASTA ACÁ TE PUSE EL TIPO DE GRAPH SON 8 MAS
    // function cant_pnpp_sda_sin_pagar() {
    //     
    //     $template = "dashboard/widgets/box_info.php";
    //     $data = array();
    //     $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_5_30_mas30';
    //     //$data['json_url'] = $this->base_url.'/api/peticiones_redmine_pde/
    //     $data['class'] = "data_bars";
    //     $data['title'] = "Cantidad de Proyectos PP/PN con dictámenes SDA/Rendiciones aprobados sin pagar";
    //     return $this->parser->parse('pacc/bars', $data, true, true);
    // }
    // function cant_pde_cronograma_vencido() {
    //     
    //     $template = "dashboard/widgets/box_info.php";
    //     $data = array();
    //     $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_5_30_mas30';
    //     //$data['json_url'] = $this->base_url.'/api/peticiones_redmine_pde/
    //     $data['class'] = "data_bars";
    //     $data['title'] = "Cantidad de Proyectos PDE con cronograma vencido ";
    //     return $this->parser->parse('pacc/bars', $data, true, true);
    // }
    // function cant_pn_cronograma_vencido() {
    //     
    //     $template = "dashboard/widgets/box_info.php";
    //     $data = array();
    //     $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_5_30_mas30';
    //     //$data['json_url'] = $this->base_url.'/api/peticiones_redmine_pde/
    //     $data['class'] = "data_bars";
    //     $data['title'] = "Cantidad de Proyectos PN con cronograma vencido ";
    //     return $this->parser->parse('pacc/bars', $data, true, true);
    // }
    // function cant_pn_con_deuda() {
    //     
    //     $template = "dashboard/widgets/box_info.php";
    //     $data = array();
    //     $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_90_120_mas120';
    //     //$data['json_url'] = $this->base_url.'/api/peticiones_redmine_pde/
    //     $data['class'] = "data_bars";
    //     $data['title'] = "Cantidad de Proyectos PN con deuda  ";
    //     return $this->parser->parse('pacc/bars', $data, true, true);
    // }
    // function cant_pde_reclamados_mail() {
    //     
    //     $template = "dashboard/widgets/box_info.php";
    //     $data = array();
    //     $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_15_30_mas30';
    //     //$data['json_url'] = $this->base_url.'/api/peticiones_redmine_pde/
    //     $data['class'] = "data_bars";
    //     $data['title'] = "Cantidad de Proyectos PDE vencidos reclamados por mail";
    //     return $this->parser->parse('pacc/bars', $data, true, true);
    // }
    // function cant_pnpp_reclamados_mail() {
    //     
    //     $template = "dashboard/widgets/box_info.php";
    //     $data = array();
    //     $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_15_30_mas30';
    //     //$data['json_url'] = $this->base_url.'/api/peticiones_redmine_pde/
    //     $data['class'] = "data_bars";
    //     $data['title'] = "Cantidad de Proyectos PN/pp vencidos reclamados por mail";
    //     return $this->parser->parse('pacc/bars', $data, true, true);
    // }
    // function cant_pnpp_sin_respuesta() {
    //     
    //     $template = "dashboard/widgets/box_info.php";
    //     $data = array();
    //     $data['json_url'] = $this->base_url . 'pacc/area_seguimiento_y_planificacion/data_19639';
    //     //$data['json_url'] = $this->base_url.'/api/peticiones_redmine_pde/
    //     $data['class'] = "data_bars";
    //     $data['title'] = "Cantidad de Proyectos PP/PN intimados sin respuesta ";
    //     return $this->parser->parse('pacc/bars', $data, true, true);
    // }




    function data_5_30_mas30($graph_Fn = null) {



        $porc = rand(0, 100);

        $val_1 = 0;
        $val_2 = 0;
        $val_3 = 0;

        $in_for_array = array("60", "40", "50");
        $graph_range = array(range(0, 5), range(6, 30));

        switch ($graph_Fn) {
            case 'cant_pde_sin_evaluacion':

                $result = $this->model_seguimiento_planificacion->proyectos_PDE_presentados($in_for_array, $graph_range);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;

                break;

            case 'cant_pn_sin_evaluacion':
                $result = $this->model_seguimiento_planificacion->proyectos_PN_presentados($in_for_array, $graph_range);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;

                break;
        }

        $arr = [["0-5 Días ", $val_1], ["6-30 Días", $val_2], ["Más de 30 Días", $val_3]];



        $bar = new stdClass;
        $bar->label = 'Presentados';
        $bar->hoverable = true;
        $bar->clickable = true;
        $bar->data = $arr;
        $bar->bars = (object) array('show' => true, 'order' => 1, 'barWidth' => 0.2);
        $bar->color = "#3c8dbc";

        $data['data'][] = $bar;


        $data['config'] = array();

        $data['config']['grid'] = array(
            'borderWidth' => 1,
            'borderColor' => "#f3f3f3",
            'tickColor' => "#f3f3f3"
        );

        $data['config']['xaxis'] = array(
            'mode' => "categories",
            'tickLength' => 0
        );
        $data['config']['series']['bars'] = array(
            'show' => "true",
            'barWidth' => 0.5,
            'align' => "center"
        );


        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }

    function data_10_30_mas30($graph_Fn = null) {


        $porc = rand(0, 100);


        $val_1 = 0;
        $val_2 = 0;
        $val_3 = 0;

        $label = "Presentados"; //default


        $graph_range = array(range(0, 10), range(11, 30));

        switch ($graph_Fn) {



            case 'cant_rendiciones_observadas':
                $label = "Rendiciones Observadas";
                $in_for_array = array(40, 90);
                $result = $this->model_seguimiento_planificacion->proyectos_PN_presentados($in_for_array, $graph_range);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;

                break;


            case 'cant_pn_observados_formal_sin_respuesta':

                $label = "Observados Formal sin Respuesta";

                $in_for_array = array(90);
                $result = $this->model_seguimiento_planificacion->proyectos_PN_obs_sin_respuesta($in_for_array, $graph_range);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;

                break;

            case 'cant_sde_observadas':
                $label = "Observadas SDE";
                //falta el sin respuesta

                $in_for_array = array(300);
                $result = $this->model_seguimiento_planificacion->proyectos_PDE_obs($in_for_array, $graph_range, 6628);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;
                break;

            case 'cant_pde_observados_formal_sin_respuesta':

                $label = "Observados Formal sin Respuesta";
                //falta el sin respuesta

                $in_for_array = array(90);
                $result = $this->model_seguimiento_planificacion->proyectos_PDE_obs($in_for_array, $graph_range);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;

                break;




            case 'cant_pde_preaprobados':

                $label = "Pre Aprobados";
                $in_for_array = array(60);
                $result = $this->model_seguimiento_planificacion->proyectos_PDE_presentados($in_for_array, $graph_range);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;

                break;

            case 'cant_pn_preaprobados':

                $label = "Pre Aprobados";

                $in_for_array = array(60);
                $result = $this->model_seguimiento_planificacion->proyectos_PN_presentados($in_for_array, $graph_range);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;

                break;

            case 'cant_pde_observados_sin_respuesta':

                $label = "Observados sin Respuesta";

                $in_for_array = array(40);
                $result = $this->model_seguimiento_planificacion->proyectos_PN_presentados($in_for_array, $graph_range);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;

                break;

            case 'cant_pn_observados_sin_respuesta':

                $label = "Observados sin Respuesta";

                $in_for_array = array(40);
                $result = $this->model_seguimiento_planificacion->proyectos_PN_presentados($in_for_array, $graph_range);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;

                break;

            case 'cant_pde_con_documentacion_sin_evaluacion':

                $label = "Evaluación sin documentación";

                $in_for_array = array(50);
                $result = $this->model_seguimiento_planificacion->proyectos_PN_presentados($in_for_array, $graph_range);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;

                break;

            case 'cant_pn_con_documentacion_sin_evaluacion':

                $label = "Evaluación con documentación";

                $in_for_array = array(50);
                $result = $this->model_seguimiento_planificacion->proyectos_PN_presentados($in_for_array, $graph_range);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;

                break;
        }

        $arr = [["0-10 Días ", $val_1], ["11-30 Días", $val_2], ["Más de 30 Días", $val_3]];


        $bar = new stdClass;
        $bar->label = "&nbsp;" . $label;
        $bar->hoverable = true;
        $bar->clickable = true;
        $bar->data = $arr;
        $bar->bars = (object) array('show' => true, 'order' => 1, 'barWidth' => 0.2);
        $bar->color = "#3c8dbc";

        $data['data'][] = $bar;


        $data['config'] = array();

        $data['config']['grid'] = array(
            'borderWidth' => 1,
            'borderColor' => "#f3f3f3",
            'tickColor' => "#f3f3f3"
        );

        $data['config']['xaxis'] = array(
            'mode' => "categories",
            'tickLength' => 0
        );
        $data['config']['series']['bars'] = array(
            'show' => "true",
            'barWidth' => 0.5,
            'align' => "center"
        );


        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }

    function data_15_30_mas30($graph_Fn = null) {

        $porc = rand(0, 100);


        $val_1 = 0;
        $val_2 = 0;
        $val_3 = 0;

        $label = "Presentados"; //default


        $graph_range = array(range(0, 15), range(16, 30));


        switch ($graph_Fn) {
            case 'cant_pde_1sde_sin_respuesta':
                $label = "PDE con SDE Observada sin respuesta";

                $in_for_array = array(300);
                $result = $this->model_seguimiento_planificacion->proyectos_PDE_obs($in_for_array, $graph_range, 6628);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;
                break;
        }

        $arr = [["0-10 Días", 5], ["11-30 Días", 2], ["Más de 30 Días", 2]];
        $bar = new stdClass;
        $bar->label = $label;
        $bar->hoverable = true;
        $bar->clickable = true;
        $bar->data = $arr;
        $bar->bars = (object) array('show' => true, 'order' => 1, 'barWidth' => 0.2);
        $bar->color = "#3c8dbc";

        $data['data'][] = $bar;


        $data['config'] = array();

        $data['config']['grid'] = array(
            'borderWidth' => 1,
            'borderColor' => "#f3f3f3",
            'tickColor' => "#f3f3f3"
        );

        $data['config']['xaxis'] = array(
            'mode' => "categories",
            'tickLength' => 0
        );
        $data['config']['series']['bars'] = array(
            'show' => "true",
            'barWidth' => 0.5,
            'align' => "center"
        );


        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }

    function data_90_120_mas120($graph_Fn = null) {


        $porc = rand(0, 100);

        $val_1 = 0;
        $val_2 = 0;
        $val_3 = 0;

        $label = "Presentados"; //default


        $graph_range = array(range(0, 30), range(31, 90));


        switch ($graph_Fn) {


            case 'cant_pn_aprobados_sin_ejecucion':

                $label = "Aprobados sin Ejecución";

                $in_for_array = array(100, 110);
                $result = $this->model_seguimiento_planificacion->proyectos_PN_presentados($in_for_array, $graph_range);

                $val_1 = isset($result['1']) ? count($result['1']) : 0;
                $val_2 = isset($result['2']) ? count($result['2']) : 0;
                $val_3 = isset($result['3']) ? count($result['3']) : 0;

                break;
        }


        $arr = [["0-90 Días", 5], ["120 Días", 2], ["Más de 120 Días", 2]];
        $bar = new stdClass;
        $bar->label = $label;
        $bar->clickable = true;
        $bar->data = $arr;
        $bar->bars = (object) array('show' => true, 'order' => 1, 'barWidth' => 0.2);
        $bar->color = "#3c8dbc";

        $data['data'][] = $bar;


        $data['config'] = array();

        $data['config']['grid'] = array(
            'borderWidth' => 1,
            'borderColor' => "#f3f3f3",
            'tickColor' => "#f3f3f3"
        );

        $data['config']['xaxis'] = array(
            'hoverable' => true,
            'mode' => "categories",
            'tickLength' => 0
        );
        $data['config']['series']['bars'] = array(
            'show' => "true",
            'barWidth' => 1.0,
            'align' => "center"
        );


        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }

    function data_19601($filter = null) {

        ini_set("error_reporting", 0);


        $regex = (isset($filter['year'])) ? $filter['year'] : new MongoRegex('/201/');
        $this->load->model('model_pacc');

        /* PACC 1.1 */
        $new_arr = array();

        $query = array('8313' => $regex);
        $rs11 = $this->model_pacc->buscar_actividades_pacc_1($query);

        /* PACC 1.3 */
        $new_arr13 = array();
        $query = array('8550' => $regex);
        $rs13 = $this->model_pacc->buscar_proyectos_pacc($query);


        foreach ($rs11 as $each) {
            list($yearA, $monthA, $dayA) = explode("/", $each['8313']);
            $new_arr[] = $monthA;
        }

        foreach ($rs13 as $each) {
            if (isset($each['8550'])) {
                list($yearA, $monthA, $dayA) = explode("-", $each['8550']);
                $new_arr13[] = $monthA;
            }
        }

        $merge_arrays = array_merge($new_arr, $new_arr13);
        $count_values = array_count_values($merge_arrays);


        $porc = rand(0, 100);
        $arr = [["Ene", $count_values['01']],
            ["Feb", $count_values['02']],
            ["Mar", $count_values['03']],
            ["Abr", $count_values['04']],
            ["May", $count_values['05']],
            ["Jun", $count_values['06']],
            ["Jul", $count_values['07']],
            ["Ago", $count_values['08']],
            ["Sep", $count_values['09']],
            ["Oct", $count_values['10']],
            ["Nov", $count_values['11']],
            ["Dic", $count_values['12']]
        ];

        $bar = new stdClass;
        $bar->label = 'Presentados';
        $bar->hoverable = true;
        $bar->clickable = true;
        $bar->data = $arr;
        $bar->bars = (object) array('show' => true, 'order' => 1, 'barWidth' => 0.2);
        $bar->color = "#3c8dbc";

        $data['data'][] = $bar;


        $data['config'] = array();

        $data['config']['grid'] = array(
            'borderWidth' => 1,
            'borderColor' => "#f3f3f3",
            'tickColor' => "#f3f3f3"
        );

        $data['config']['xaxis'] = array(
            'mode' => "categories",
            'tickLength' => 0
        );
        $data['config']['series']['bars'] = array(
            'show' => "true",
            'barWidth' => 0.5,
            'align' => "center"
        );


        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }

    function data_19600() {


        ini_set("error_reporting", 0);
        /*
          Fecha Respuesta Mail Seguimiento
          idpreg: 8313
         */


        $regex = (isset($filter['year'])) ? $filter['year'] : new MongoRegex('/201/');
        $this->load->model('model_pacc');

        /* PACC 1.1 */
        $new_arr = array();

        $query = array('8309' => $regex);
        $rs11 = $this->model_pacc->buscar_actividades_pacc_1($query);

        /* PACC 1.3 */
        $new_arr13 = array();
        $query = array('8552' => $regex);
        $rs13 = $this->model_pacc->buscar_proyectos_pacc($query);


        foreach ($rs11 as $each) {
            list($yearA, $monthA, $dayA) = explode("/", $each['8309']);
            $new_arr[] = $monthA;
        }

        foreach ($rs13 as $each) {
            list($yearA, $monthA, $dayA) = explode("-", $each['8552']);
            $new_arr13[] = $monthA;
        }

        $merge_arrays = array_merge($new_arr, $new_arr13);
        $count_values = array_count_values($merge_arrays);


        $porc = rand(0, 100);
        $arr = [["Ene", $count_values['01']],
            ["Feb", $count_values['02']],
            ["Mar", $count_values['03']],
            ["Abr", $count_values['04']],
            ["May", $count_values['05']],
            ["Jun", $count_values['06']],
            ["Jul", $count_values['07']],
            ["Ago", $count_values['08']],
            ["Sep", $count_values['09']],
            ["Oct", $count_values['10']],
            ["Nov", $count_values['11']],
            ["Dic", $count_values['12']]
        ];

        $bar = new stdClass;
        $bar->label = 'Cantidad de Proyectos';
        $bar->hoverable = true;
        $bar->clickable = true;
        $bar->data = $arr;
        $bar->bars = (object) array('show' => true, 'order' => 1, 'barWidth' => 0.2);
        $bar->color = "#3c8dbc";

        $data['data'][] = $bar;


        $data['config'] = array();

        $data['config']['grid'] = array(
            'borderWidth' => 1,
            'borderColor' => "#f3f3f3",
            'tickColor' => "#f3f3f3"
        );

        $data['config']['xaxis'] = array(
            'mode' => "categories",
            'tickLength' => 0
        );
        $data['config']['series']['bars'] = array(
            'show' => "true",
            'barWidth' => 0.5,
            'align' => "center"
        );


        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }

    function data_19638() {


        $porc = rand(0, 100);
        $arr = [["1er Semestre", 4.5], ["2do Semestre", 2], ["3er Semestre", 2]];
        $bar = new stdClass;
        $bar->label = 'Presentados';
        $bar->hoverable = true;
        $bar->clickable = true;
        $bar->data = $arr;
        $bar->bars = (object) array('show' => true, 'order' => 1, 'barWidth' => 0.2);
        $bar->color = "#3c8dbc";

        $data['data'][] = $bar;


        $data['config'] = array();

        $data['config']['grid'] = array(
            'borderWidth' => 1,
            'borderColor' => "#f3f3f3",
            'tickColor' => "#f3f3f3"
        );

        $data['config']['xaxis'] = array(
            'mode' => "categories",
            'tickLength' => 0
        );
        $data['config']['series']['bars'] = array(
            'show' => "true",
            'barWidth' => 0.5,
            'align' => "center"
        );


        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }

    function data_19639() {


        $porc = rand(0, 100);
        $arr = [["ENE", 4.5], ["FEB", 2], ["MAR", 4], ["ABR", 5], ["MAY", 4.5], ["JUN", 2], ["JUL", 4], ["AGO", 5], ["SEP", 4.5], ["OCT", 2], ["NOV", 4], ["DIC", 5]];
        $bar = new stdClass;
        $bar->label = 'Presentados';
        $bar->hoverable = true;
        $bar->clickable = true;
        $bar->data = $arr;
        $bar->bars = (object) array('show' => true, 'order' => 1, 'barWidth' => 0.2);
        $bar->color = "#3c8dbc";

        $data['data'][] = $bar;


        $data['config'] = array();

        $data['config']['grid'] = array(
            'borderWidth' => 1,
            'borderColor' => "#f3f3f3",
            'tickColor' => "#f3f3f3"
        );

        $data['config']['xaxis'] = array(
            'mode' => "categories",
            'tickLength' => 0
        );
        $data['config']['series']['bars'] = array(
            'show' => "true",
            'barWidth' => 0.5,
            'align' => "center"
        );


        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }

    function data_19640() {


        $porc = rand(0, 100);
        $arr = [["ENE", 4.5], ["FEB", 2], ["MAR", 4], ["ABR", 5], ["MAY", 4.5], ["JUN", 2], ["JUL", 4], ["AGO", 5], ["SEP", 4.5], ["OCT", 2], ["NOV", 4], ["DIC", 5]];
        $bar = new stdClass;
        $bar->label = 'Presentados';
        $bar->hoverable = true;
        $bar->clickable = true;
        $bar->data = $arr;
        $bar->bars = (object) array('show' => true, 'order' => 1, 'barWidth' => 0.2);
        $bar->color = "#3c8dbc";

        $data['data'][] = $bar;


        $data['config'] = array();

        $data['config']['grid'] = array(
            'borderWidth' => 1,
            'borderColor' => "#f3f3f3",
            'tickColor' => "#f3f3f3"
        );

        $data['config']['xaxis'] = array(
            'mode' => "categories",
            'tickLength' => 0
        );
        $data['config']['series']['bars'] = array(
            'show' => "true",
            'barWidth' => 0.5,
            'align' => "center"
        );


        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }

    function data_19642() {


        $porc = rand(0, 100);
        $arr = [["ENE", 4.5], ["FEB", 3], ["MAR", 2], ["ABR", 3], ["MAY", 4.5], ["JUN", 1], ["JUL", 2.5], ["AGO", 3], ["SEP", 4.5], ["OCT", 5], ["NOV", 1], ["DIC", 1.5]];
        $bar = new stdClass;
        $bar->label = 'Presentados';
        $bar->hoverable = true;
        $bar->clickable = true;
        $bar->data = $arr;
        $bar->bars = (object) array('show' => true, 'order' => 1, 'barWidth' => 0.2);
        $bar->color = "#3c8dbc";

        $data['data'][] = $bar;


        $data['config'] = array();

        $data['config']['grid'] = array(
            'borderWidth' => 1,
            'borderColor' => "#f3f3f3",
            'tickColor' => "#f3f3f3"
        );

        $data['config']['xaxis'] = array(
            'mode' => "categories",
            'tickLength' => 0
        );
        $data['config']['series']['bars'] = array(
            'show' => "true",
            'barWidth' => 0.5,
            'align' => "center"
        );


        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }

    function lines_19636() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Cantidad de Auditorías PP realizadas";
        //$data['json_url'] = $this->base_url.'/api/cant_auditorias_pp_realizadas/';
        return $this->parser->parse('pacc/charts', $data, true, true);
    }

    function lines_19635() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Cantidad de Auditorías PDE realizadas";
        //$data['json_url'] = $this->base_url.'/api/cant_auditorias_pde_realizadas/';
        return $this->parser->parse('pacc/fake-charts', $data, true, true);
    }

    // widget dias preaprobacion /';

    function knobs_preaprobacion() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Dato promedio de días desde Preaprobación";
        $data['base_url'] = $this->base_url;
        return $this->parser->parse('pacc/knobs-pitchs', $data, true, true);
    }

    function knobs_aprobacion() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Dato promedio de días desde Aprobación SDE";
        $data['base_url'] = $this->base_url;
        return $this->parser->parse('pacc/knobs-pitchs', $data, true, true);
    }

    // widget tabla cantidad_proyectos_vencidos /';
    function cantidad_proyectos_vencidos() {


        $renderData['title'] = "Cantidad proyectos vencidos";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template = "dashboard/widgets/box_info.php";

        $renderData['content'] = $this->parser->parse('incubadoras-pp', $renderData, true, true);

        return $this->dashboard->widget($template, $renderData);
    }

    // widget 19598 /';



    function deuda_total_devolucion_total() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Deuda Total / Devolución Total";
        $data['json_url'] = $this->base_url . 'demo/data_bars';
        return $this->parser->parse('area-charts', $data, true, true);
    }

    function proyecto_reclamados_respuesta() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Proyectos Reclamados / Presentaron Respuesta";
        //$data['json_url'] = $this->base_url.'/api/cant_auditorias_pp_realizadas/';
        return $this->parser->parse('pacc/charts', $data, true, true);
    }

    function proyecto_recupero_deuda() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Del total de proyectos reclamados por CD";
        $data['base_url'] = $this->base_url;
        return $this->parser->parse('pacc/knobs-pitchs', $data, true, true);
    }

    // widget 19634 /';

    function proyecto_vencimiento_cronograma() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Proyecto vencimiento cronograma";
        $data['base_url'] = $this->base_url;
        return $this->parser->parse('pacc/knobs-pitchs', $data, true, true);
    }

    function resultado_revision_pagos() {

        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Resultados de revisión de pagos";
        //$data['json_url'] = $this->base_url.'/api/cant_auditorias_pp_realizadas/';
        return $this->parser->parse('pacc/charts', $data, true, true);
    }

}
