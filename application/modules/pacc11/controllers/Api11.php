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
class Api11 extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('api');
        $this->load->helper('html');
        $this->load->model('bpm/bpm');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';

        /* LOAD MODEL */
        $this->load->model('pacc11');
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
     * Proyectos Presentados
     * 
     * Metas para Empresas - Presentados 
     * URL referencia http://redmine.industria.gob.ar/issues/19373
     * META = Universo PDEs (total de casos del flujo pacc1PDE)
     * cuenta: pacc1PDE - oryx_4EFED47A-7AAD-4728-9577-49F8901AC5B9 (M2 - end message event).
     * 
     * @name Presentados
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @param type $filter
     * 
     * @date Jun 28, 2015     
     */
    function Presentados($filter = null, $mode = 'json') {
        $title = 'Presentados';
        $label = '';
        $idwf = 'pacc1PDE';
        $resourceId = 'oryx_89375AD7-560B-47D4-A71B-80A032D2039C';
        $total = $this->bpm->get_all_cases_count(null, $idwf);
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId
        );
        $cant = $this->bpm->get_tokens_byFilter_count($query);
        $label = "($cant)";
        $porc = @intval(($cant / $total) * 100);
        $data = array(
            'value' => $porc,
            'min' => 0,
            'max' => 100,
            'title' => $title,
            'label' => $label
        );

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Proyectos Presentados PDEs
     * 
     * Proyectos Presentados - Mapa
     * URL referencia http://redmine.industria.gob.ar/issues/19389
     * META = Proyectos Presentados - Mapa
     * cuenta: pacc1PDE - oryx_4EFED47A-7AAD-4728-9577-49F8901AC5B9 (M2 - end message event).
     * provincia : 7227 - td_pacc_1 - 39
     * 

     * 
     * @name Presentados_pde
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @param type $filter
     * 
     * @date Jun 28, 2015  
     */
    function Presentados_pde($filter = null, $mode = 'json') {
        $title = 'Presentados PDE';
        $label = '';
        $idwf = 'pacc1PDE';
        $resourceId = 'oryx_D9F83067-57C8-4A2F-A4AC-37B5DAA3D332';
        $total = $this->bpm->get_all_cases(null, $idwf);
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId
        );
        $tokens = $this->bpm->get_tokens_byFilter($query);



        $data = array_map(function ($token) {
            $case = $this->bpm->get_case($token['case']);
            $data = $this->bpm->load_case_data($case);

            $this->load->model('app');
            $option = $this->app->get_ops(39);
            $status = $option[$data['Proyectos_pacc']['7227'][0]];

            $data['provincia'] = $status;

            return $data['provincia'];
        }, $tokens);

        $rtn = array_count_values($data);

        switch ($mode) {
            case "object":
                return (object) $rtn;
                break;
            case "array":
                return($rtn);
                break;
            case "json":
                output_json($rtn);
                break;
            default:
                return($rtn);
        }
    }

    /**
     * Proyectos Presentados PNs
     * 
     * Proyectos Presentados - Mapa
     * URL referencia http://redmine.industria.gob.ar/issues/19389
     * META = Proyectos Presentados - Mapa
     * cuenta: pacc3PP - oryx_4EFED47A-7AAD-4728-9577-49F8901AC5B9 - (PN presentado - end message event)
     * provincia : idempresa = 8256 -> 4651 - td_empresas - 39
     * 
     * @name Presentados_pn
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function Presentados_pn($filter = null, $mode = 'json') {
        $title = 'Presentados PN';
        $label = '';
        $idwf = 'pacc3PP';
        $resourceId = 'oryx_4EFED47A-7AAD-4728-9577-49F8901AC5B9';
        $total = $this->bpm->get_all_cases(null, $idwf);
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId
        );
        $tokens = $this->bpm->get_tokens_byFilter($query);

        $data = array_map(function ($token) {
            $case = $this->bpm->get_case($token['case']);
            $data = $this->bpm->load_case_data($case);

            $emprendedor = $data['Proyectos_pacc']['8256'];

            $provincia = $this->bpm->get_data('container.empresas', array('id' => $emprendedor));

            $this->load->model('app');
            $option = $this->app->get_ops(39);
            $status = $option[$provincia['4651']];

            $data['provincia'] = $status;

            return $data['provincia'];
        }, $tokens);


        $rtn = array_count_values($data);

        switch ($mode) {
            case "object":
                return (object) $rtn;
                break;
            case "array":
                return($rtn);
                break;
            case "json":
                output_json($rtn);
                break;
            default:
                return($rtn);
        }
    }

    /**
     * Proyectos Presentados PITCHs
     * 
     * Proyectos Presentados - Mapa
     * URL referencia http://redmine.industria.gob.ar/issues/19389
     * META = Proyectos Presentados - Mapa
     * cuenta: pacc3PP - oryx_0B1514DD-8EE4-4AFA-831B-6445A961DDA6 - (Video PN - end message event)
     * provincia : idempresa = 8256 -> 4651 - td_empresas - 39
     * 
     * @name Presentados_pitch
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     *    
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function Presentados_pitch($filter = null, $mode = 'json') {
        $title = 'Presentados PN';
        $label = '';
        $idwf = 'pacc3PP';
        $resourceId = 'oryx_0B1514DD-8EE4-4AFA-831B-6445A961DDA6';
        $total = $this->bpm->get_all_cases(null, $idwf);
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId
        );
        $tokens = $this->bpm->get_tokens_byFilter($query);

        $data = array_map(function ($token) {
            $case = $this->bpm->get_case($token['case']);
            $data = $this->bpm->load_case_data($case);

            $emprendedor = $data['Proyectos_pacc']['8256'];

            $provincia = $this->bpm->get_data('container.empresas', array('id' => $emprendedor));

            $this->load->model('app');
            $option = $this->app->get_ops(39);
            $status = $option[$provincia['4651']];

            $data['provincia'] = $status;

            return $data['provincia'];
        }, $tokens);


        $rtn = array_count_values($data);

        switch ($mode) {
            case "object":
                return (object) $rtn;
                break;
            case "array":
                return($rtn);
                break;
            case "json":
                output_json($rtn);
                break;
            default:
                return($rtn);
        }
    }

    /**
     * Proyectos evaluados Técnicos
     * 
     * Metas para Empresas - Evaluados Técnicos
     * URL referencia http://redmine.industria.gob.ar/issues/19374
     * META = Universo PDEs (total de casos del flujo pacc1PDE)
     * cuenta:
     * pacc1PDE - oryx_B9D1931C-1F5B-4D3D-82F4-9B919750F4A3 (M4 - EME - observado)
     * pacc1PDE - oryx_FF122EC2-566D-4D7F-AC17-1A5B71B35922 - (M7 - EME - rechazo)
     * pacc1PDE - oryx_1FEB7B2E-757D-415D-99EF-27C124FC747B - (M8 - EME - aprobado)
     * 
     * @name Presentados_pitch
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function Evaluados_tecnicos($filter = null, $mode = 'json') {
        $title = 'Evaluados Técnicos';
        $label = '';
        $idwf = 'pacc1PDE';
        $resourceId = 'oryx_C53F382C-E066-4A7F-BF6C-4694F58CB227';
        $total = $this->bpm->get_all_cases_count(null, $idwf);
        $query = array(
            'idwf' => $idwf,
            'resourceId' =>  $resourceId,
        );
        $cant = $this->bpm->get_tokens_byFilter_count($query);
        $label = "($cant)";
        $porc = @ intval(($cant / $total) * 100);
        $data = array(
            'value' => $porc,
            'min' => 0,
            'max' => 100,
            'title' => $title,
            'label' => $label
        );

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Proyectos Presentados
     * 
     * Metas para Empresas - Primer Pago
     * META = Universo PDEs (total de casos del flujo paccPagoSD)
     * cuenta: paccPagoSD - oryx_35CEFC1B-5CA1-4DAF-A5B8-F5A6154577FF (FIN - end event)
     * URL referencia http://redmine.industria.gob.ar/issues/19378
     * 
     * @name Primer_pago
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function Primer_pago($filter = null, $mode = 'json') {
        $title = 'Primer Pago';
        $label = '';
        $idwf = 'paccPagoSD';
        $resourceId = 'oryx_D8C27457-DD43-4A82-B898-3C82C2A84BAE';
        $total = $this->bpm->get_all_cases_count(null, 'pacc1PDE');
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId,
            'case' => new MongoRegex('/PACC11-/'),
        );

        
        $cant = $this->bpm->get_tokens_byFilter_count($query);
        $label = "($cant)";
        $porc = @ intval(($cant / $total) * 100);
        $data = array(
            'value' => $porc,
            'min' => 0,
            'max' => 100,
            'title' => $title,
            'label' => $label
        );

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Proyectos Aprobados
     * 
     * Metas para Empresas - Aprobados
     * META = Universo PDEs (total de casos del flujo pacc1PDE)
     * cuenta: pacc1PDEF - oryx_E30BD322-F07A-4582-AA30-084613B1ACD0 - (Archivo Expte - EME)
     * URL referencia http://redmine.industria.gob.ar/issues/19376
     * 
     * @name Aprobados
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function Aprobados($filter = null, $mode = 'json') {
        $title = 'Aprobados';
        $label = '';
        $idwf = 'pacc1PDEF';
        $resourceId = 'oryx_CA6EB692-056E-49FC-9E47-2391142F6929';
        $total = $this->bpm->get_all_cases_count(null, $idwf);
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId
        );
        $cant = $this->bpm->get_tokens_byFilter_count($query);
        $label = "($cant)";
        $porc = @ intval(($cant / $total) * 100);
        $data = array(
            'value' => $porc,
            'min' => 0,
            'max' => 100,
            'title' => $title,
            'label' => $label
        );

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Proyectos pre-aprobados
     * 
     * Metas para Empresas - Pre Aprobados
     * META = Universo PDEs (total de casos del flujo pacc1PDE)
     * cuenta: pacc1PDE - oryx_8CCDBA55-463A-45B8-9D71-205154539048 - (FIN - end vent)
     * URL referencia http://redmine.industria.gob.ar/issues/19375
     * 
     * @name Pre_aprobados
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function Pre_aprobados($filter = null, $mode = 'json') {
        $title = 'Pre-Aprobados';
        $label = '';
        $idwf = 'pacc1PDE';
        $resourceId = 'oryx_BEB71C63-D63E-4510-82F1-D04118F228B9';
        $total = $this->bpm->get_all_cases_count(null, $idwf);
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId
        );
        $cant = $this->bpm->get_tokens_byFilter_count($query);
        $label = "($cant)";
        $porc = @ intval(($cant / $total) * 100);
        $data = array(
            'value' => $porc,
            'min' => 0,
            'max' => 100,
            'title' => $title,
            'label' => $label
        );

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Proyectos Finalizados
     * 
     * Metas para Empresas - Finalizados control de Gestión
     * META = Universo PDEF
     * 
     * @name Pre_aprobados
     * 
     * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
     * 
     * @date Dec 14, 2015  
     * 
     * @param type $filter
     */
    function Finalizados($filter = null, $mode = 'json') {
        $title = 'Finalizados';
        $label = '';
        $total = 0;
        $idwf='pacc1CG';
        $resourceId = 'oryx_D37BFBAE-40B0-4BBC-8E3D-66A23907B295';
        $total=$this->bpm->get_all_cases_count(null,$idwf); 
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId
        );
        $cant = $this->bpm->get_tokens_byFilter_count($query);
        $label="($cant)";
        $porc=  intval(($cant/$total)*100);
        $porc = 0;
        $data = array(
            'value' => $porc,
            'min' => 0,
            'max' => 100,
            'title' => $title,
            'label' => $label
        );

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Proyectos Presentados SDE
     * 
     * Line chart - SDE
     * unidad de cuenta = SDE (pacc1SDE)
     * eje X = cantidad de dias (mostrar 28)
     * eje Y = cantidad de SDEs
     * PRESENTADO : pacc1SDE - oryx_9C967607-029F-4A57-A9B9-31428EF24D79 (SDE nuveva - EME)
     * sólo contar el primer paso alguna de estas figuras
     * URL referencia http://redmine.industria.gob.ar/issues/19388
     * 
     * @name Presentado_sde
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function Presentado_sde($filter = null, $mode = 'json') {

        $title = 'Presentados SDE';
        $label = '';
        $idwf = 'pacc1SDE';
        $gte_date = get_date_least_28_days();
        $resourceId = 'oryx_C7B67184-0360-4C91-AE98-99DEEFC09423'; //,  'oryx_9C967607-029F-4A57-A9B9-31428EF24D79';
        $total = $this->bpm->get_all_cases_count(null, $idwf);
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId,
            'checkdate' => array(
                '$gte' => $gte_date
            ),
        );

        $cant = $this->bpm->get_tokens_byFilter_count($query);
        $label = "($cant)";
        $porc = @intval(($cant / $total) * 100);
        $data = array(
            'value' => $porc,
            'min' => 0,
            'max' => 100,
            'title' => $title,
            'label' => $label
        );

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Proyectos Evaluado SDE
     * 
     * Line chart - SDE
     * unidad de cuenta = SDE (pacc1SDE)
     * eje X = cantidad de dias (mostrar 28)
     * eje Y = cantidad de SDEs
     * EVALUADO : pacc1SDE - oryx_127A3660-2EAF-402B-816F-E09B073B0F1E (FIN - End Event)
     * sólo contar el primer paso alguna de estas figuras
     * URL referencia http://redmine.industria.gob.ar/issues/19388
     * 
     * @name Evaluado_sde
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function Evaluado_sde($filter = null, $mode = 'json') {

        $title = 'Evaluado SDE';
        $label = '';
        $idwf = 'pacc1SDE';
        $gte_date = get_date_least_28_days();
        $resourceId = 'oryx_8D3AC1F9-3599-4D3E-8855-37371070B1FB';
        $total = $this->bpm->get_all_cases_count(null, $idwf);
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId,
            'checkdate' => array(
                '$gte' => $gte_date
            ),
        );

        $cant = $this->bpm->get_tokens_byFilter_count($query);
        $label = "($cant)";
        $porc = @intval(($cant / $total) * 100);
        $data = array(
            'value' => $porc,
            'min' => 0,
            'max' => 100,
            'title' => $title,
            'label' => $label
        );

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Proyectos Observada SDE
     * 
     * Line chart - SDE
     * unidad de cuenta = SDE (pacc1SDE)
     * eje X = cantidad de dias (mostrar 28)
     * eje Y = cantidad de SDEs
     * pacc1SDE - oryx_DB0CBE7E-4B14-46BF-83E7-C1C2774629FC (SDE observada - EME)
     * sólo contar el primer paso alguna de estas figuras
     * URL referencia http://redmine.industria.gob.ar/issues/19388
     * 
     * @name Observada_sde
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function Observada_sde($filter = null, $mode = 'json') {

        $title = 'Observada SDE';
        $label = '';
        $idwf = 'pacc1SDE';
        $gte_date = get_date_least_28_days();
        $resourceId = 'oryx_DB0CBE7E-4B14-46BF-83E7-C1C2774629FC';
        $total = $this->bpm->get_all_cases_count(null, $idwf);
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId,
            'checkdate' => array(
                '$gte' => $gte_date
            ),
        );

        $cant = $this->bpm->get_tokens_byFilter_count($query);
        $label = "($cant)";
        $porc = @intval(($cant / $total) * 100);
        $data = array(
            'value' => $porc,
            'min' => 0,
            'max' => 100,
            'title' => $title,
            'label' => $label
        );

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Proyectos Observada SDE
     * 
     * Line chart - SDE
     * unidad de cuenta = SDE (pacc1SDE)
     * eje X = cantidad de dias (mostrar 28)
     * eje Y = cantidad de SDEs
     * pacc1PDE - oryx_BA723A66-03CB-46C7-849B-055C2722F422 (CD rechazo - EME)
     * sólo contar el primer paso alguna de estas figuras
     * URL referencia http://redmine.industria.gob.ar/issues/19388
     * 
     * @name Rechazado_pde
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function Rechazado_pde($filter = null, $mode = 'json') {

        $title = 'Rechazado PDE';
        $label = '';
        $idwf = 'pacc1PDE';
        $gte_date = get_date_least_28_days();
        $resourceId = 'oryx_BA723A66-03CB-46C7-849B-055C2722F422';
        $total = $this->bpm->get_all_cases_count(null, $idwf);
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId,
            'checkdate' => array(
                '$gte' => $gte_date
            ),
        );

        $cant = $this->bpm->get_tokens_byFilter_count($query);
        $label = "($cant)";
        $porc = @intval(($cant / $total) * 100);
        $data = array(
            'value' => $porc,
            'min' => 0,
            'max' => 100,
            'title' => $title,
            'label' => $label
        );

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * update DB Pacc11 x dashboard
     * 
     * Funcion para unificar la informacion y hacer mas agil/dinamica la visualizacion de los mapas & graficos
     * en los diferentes Dashboards de la app.
     * 
     * @name recalcular_pacc11_dashboard
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function recalcular_pacc11_dashboard($filter = null) {

        /** LLamamos a Model save_dashboard_subsec_model */
        $this->pacc11->save_dashboard_subsec_model();
    }

    /**
     * Buscador Pacc 1.1
     * 
     * Funcion para obtener las informacion relacionada a los proyectos pacc.
     * 
     * @name buscar
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter id
     */
    function buscar($filter = null, $mode = 'json') {


        $data = $this->pacc11->buscar_model($filter);

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Dashboard SubSecretaría
     * 
     * Se define a continuación la maqueta del dashboard perteneciente al área de SubSecretaría.
     * URL referencia http://redmine.industria.gob.ar/issues/20038
     * 
     * @name dashboard_sub_empresas
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function dashboard_sub_empresas($filter = null, $mode = 'json') {


        /* UPDATE DB */
        $get_data = $this->pacc11->get_dashboard_tmp();
        $data = $get_data[0];

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Dashboard SubSecretaría
     * 
     * URL referencia http://redmine.industria.gob.ar/issues/20038
     * 
     * @name dashboard_sub_empresas_save
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function dashboard_sub_empresas_save($filter = null, $mode = 'json') {


        /* UPDATE DB */
        $get_data = $this->pacc11->get_dashboard_subsec_model($filter);

        $data = array();
        $data_sum = array();
        foreach ($get_data['result'] as $each) {

            $provincia = $each['_id']['provincia'];

            $i = 0;
            foreach ($each['_id']['estado'] as $estado) {

                $filter = array("provincia" => $provincia, "estado" => $estado);
                $qty = $this->pacc11->get_quantity_by_status($filter);

                $data[$provincia][$estado]['provincia'] = $provincia;
                $data[$provincia][$estado]['cantidad'] = $qty;
                $data[$provincia][$estado]['desembolso'] = $each['desembolso'];
            }
            if ($each['desembolso'] > 0)
                $data_sum[$provincia][] = $each['desembolso'];
        }

        foreach ($data_sum as $key => $value) {
            $filter = array("provincia" => $key);
            $proyects = $this->pacc11->get_quantity_by_proyect($filter);

            $totales = array_sum($value);
            $data[$key]['desembolso'] = "$" . number_format($totales, 0, ",", ".");
            $data[$key]['provincia'] = $key;
            $data[$key]['proyectos_desembolsados'] = $proyects;
        }

        $this->pacc11->save_dashboard_tmp($data);
    }

    function dashboard_sub_empresas_totales($filter = null, $mode = 'json') {

        /* UPDATE DB */
        $get_data = $this->pacc11->get_dashboard_tmp();
        $data = $get_data[0];


        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Cumplimiento del Cronograma de Proyectos
     * 
     * Este informe pertenece al Subcomponente 1.1. Apoyo Directo a Empresas
     * Tabla de cantidad de proyectos vencidos por rango de tiempo de vencimiento
     * II Semestre 2013 - I Semestre 2014 - II Semestre 2014
     * Fecha de Vencimiento Actividad Original
     * Fecha de Vencimiento Actividad Vigente
     * Días transcurridos sin presentar documentación desde la fecha de vencimiento
     * Cantidad de proyectos vencidos
     * Fecha de observación
     * Fecha de vencimiento observación
     * Fecha de ingreso Actividad
     * Fecha de reingreso Actividad
     * URL referencia http://redmine.industria.gob.ar/issues/19598
     *
     * @name cumplimiento_crono_proy
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @param type $filter
     * 
     * @date Jun 28, 2015  
     */
    function cumplimiento_crono_proy() {
        /* OBTENER PROYECTOS DESDE JULIO 2013 */
    }

    /**
     * Proyectos desistidos antes de la PreAprobación y la Aprobación (observados técnicos, observados formales y PreAprobados)
     * 
     * Este informe pertenece al Subcomponente 1.1. Apoyo Directo a Empresas
     * Dato
     * I Semestre 2014 - II Semestre 2014
     * URL referencia http://redmine.industria.gob.ar/issues/19596
     *
     * @name proyectos_desistidos_preapro_apro
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @param type $filter
     * 
     * @date Jun 28, 2015  
     */
    function proyectos_desistidos_preapro_apro($filter = null, $mode = 'json') {
        //definir desistidos        
    }

    /**
     * Cantidad de Auditorías PDE realizadas
     * 
     *  Cantidad de Auditorías PDE realizadas
     * Fecha de Auditoría ->8946
     * Dictamen Auditoría ->8947
     * Comentarios Auditoría ->8948
     * Fecha de Dictamen ->9021
     * URL referencia http://redmine.industria.gob.ar/issues/19635
     *
     * @name cant_auditorias_pde_realizadas
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @param type $filter
     * 
     * @date Jun 28, 2015  
     */
    function cant_auditorias_pde_realizadas() {

        /*
          Cantidad de auditorías realizadas
          Cantidad de auditorías favorables
          Cantidad de auditorías no favorables
         */
        ini_set("error_reporting", 0);

        $new_arr_negative = array();
        $new_arr_positive = array();

        $get_today = date('Y-m-j');
        $limit_date = strtotime('-12 month', strtotime($get_today));
        $limit_date = date('Y/m/j', $limit_date);
        $fields = array('8946', '8947', '8948', '9021');

        $query = array(
            '8946' => array(
                '$gte' => $limit_date
            ),
        );

        $data = $this->pacc11->buscar_auditorias($query, $fields);

        foreach ($data as $each) {
            list($yearA, $monthA, $dayA) = explode("/", $each['8946']);

            switch ($each['8947'][0]) {
                /* NEGATIVA */
                case 40:
                    $new_arr_negative[] = $monthA;
                    break;

                /* POSITIVA */
                case 50:
                    $new_arr_positive[] = $monthA;
                    break;
            }
        }

        $count_values_positive = array_count_values($new_arr_positive);
        $count_values_negative = array_count_values($new_arr_negative);

        $result_positive = [["1", $count_values_positive['01']],
            ["2", $count_values_positive['02']],
            ["3", $count_values_positive['03']],
            ["4", $count_values_positive['04']],
            ["5", $count_values_positive['05']],
            ["6", $count_values_positive['06']],
            ["7", $count_values_positive['07']],
            ["8", $count_values_positive['08']],
            ["9", $count_values_positive['09']],
            ["10", $count_values_positive['10']],
            ["11", $count_values_positive['11']],
            ["12", $count_values_positive['12']]
        ];

        $result_negative = [["1", $count_values_negative['01']],
            ["2", $count_values_negative['02']],
            ["3", $count_values_negative['03']],
            ["4", $count_values_negative['04']],
            ["5", $count_values_negative['05']],
            ["6", $count_values_negative['06']],
            ["7", $count_values_negative['07']],
            ["8", $count_values_negative['08']],
            ["9", $count_values_negative['09']],
            ["10", $count_values_negative['10']],
            ["11", $count_values_negative['11']],
            ["12", $count_values_negative['12']]
        ];


        var_dump("POSITIVO", $result_positive);
        var_dump("NEGATIVO", $result_negative);
    }

    /**
     * Desembolsos Previstos vs. Ejecutados
     * 
     * Este informe lista por proyecto los Desembolsos previstos y los Desembolsos solicitados a una fecha determinada. A su vez, se muestra la diferencia entre ambos valores. Se adjunta un ejemplo:
     * Al ejemplo se debe agregar el componente de cada desembolso.
     * Se solicita un filtro de Fecha para traer los datos pertinentes a dicho periodo.
     * Se debe sumarizar el total de todos los valores.
     * URL referencia http://redmine.industria.gob.ar/issues/19463
     *
     * @name desembolsos_previstos_vs_ejecutados
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @param type $filter
     * 
     * @date Jun 28, 2015  
     */
    function desembolsos_previstos_vs_ejecutados($filter = null, $mode = 'json') {

        $data = $this->pacc11->get_desembolsos_previstos_vs_ejecutados_model($filter);

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

    /**
     * Deuda Total / Retribución pagada y no pagada
     * 
     *  Calculo de la Deuda Total teniendo en cuenta las retribuciones, tanto las pagadas como las que quedaron pendientes.
     * Se deben mostrar los últimos 2 años.
     * pacc11/api11/retribuciones_pagadas_empresas
     * pacc13/api13/retribuciones_pagadas_emprendedores
     * URL referencia http://redmine.industria.gob.ar/issues/23660
     *
     * @name retribuciones_pagadas_empresas
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @param type $filter
     * 
     * @date Jun 28, 2015  
     */
    function retribuciones_pagadas_empresas($mode = 'json') {

        /*
         * Facturas de Retribuciones 7042 
         * Orden de Pago 7517
         */

        $filter = null;
        $array_facturadas = array();
        $array_pagadas = array();

        $get_facturadas = $this->pacc11->get_retribuciones_model($filter);

        foreach ($get_facturadas['result'] as $result) {
            $str = substr($result['_id'], 0, 7);
            if (!isset($array_facturadas[$str])) {
                $array_facturadas[$str] = array('facturadas' => 0);
            }
            $array_facturadas[$str]['facturadas'] += $result['cantidad'];
        }

        $filter['pagadas'] = true;
        $get_pagadas = $this->pacc11->get_retribuciones_model($filter);


        foreach ($get_pagadas['result'] as $result) {
            $str = substr($result['_id'], 0, 7);
            if (!isset($array_pagadas[$str])) {
                $array_pagadas[$str] = array('pagadas' => 0, 'no_pagadas' => 0);
            }
            $array_pagadas[$str]['pagadas'] += $result['cantidad'];
            $array_pagadas[$str]['no_pagadas'] = $array_facturadas[$str]['facturadas'] - $array_pagadas[$str]['pagadas'];
        }

        $data = array_merge_recursive($array_pagadas, $array_facturadas);

        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */