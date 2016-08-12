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
class Api13 extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('api');
        $this->load->helper('html');
        $this->load->model('bpm/bpm');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->idu = $this->session->userdata('iduser'); //Id user

        /* LOAD MODEL */
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
     * Proyectos Presentados
     *
     * META = Universo PNs (total de casos del flujo pacc3PP)
     * cuenta: pacc3PP - oryx_4EFED47A-7AAD-4728-9577-49F8901AC5B9 - (PN presentado - end message event)
     * URL de referencia http://redmine.industria.gob.ar/issues/19382
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
        $idwf = 'pacc3PP';
        $resourceId = 'oryx_45D08137-08A3-4174-812A-E7599187F781';
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
     * Proyectos evaluados Técnicos
     *
     * META = Universo PDEs (total de casos del flujo pacc3PP)
     *  cuenta:
     * pacc1PDE - oryx_B9D1931C-1F5B-4D3D-82F4-9B919750F4A3 (M4 - EME - observado)
     * pacc1PDE - oryx_FF122EC2-566D-4D7F-AC17-1A5B71B35922 - (M7 - EME - rechazo)
     * pacc1PDE - oryx_1FEB7B2E-757D-415D-99EF-27C124FC747B - (M8 - EME - aprobado)
     * URL referencia http://redmine.industria.gob.ar/issues/19383
     *
     * @name Evaluados_tecnicos
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @param type $filter
     *
     * @date Jun 28, 2015
     */
    function Evaluados_tecnicos($filter = null, $mode = 'json') {
        $title = 'Evaluados Técnicos';
        $label = '';
        $idwf = 'pacc3PP';
        $resourceId ='oryx_1B4304D0-5EB1-4962-8DD7-8B96FF224475';
        $total = $this->bpm->get_all_cases_count(null, $idwf);
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId,
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
     * META = Universo PDEs (total de casos del flujo paccPagoSD)
     * cuenta: paccPagoSD - oryx_35CEFC1B-5CA1-4DAF-A5B8-F5A6154577FF (FIN - end event)
     * URL referencia http://redmine.industria.gob.ar/issues/19378
     *
     * @name Primer_pago
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @param type $filter
     *
     * @date Jun 28, 2015
     */
    function Primer_pago($filter = null, $mode = 'json') {
        $title = 'Primer Pago';
        $label = '';
        $idwf = 'paccPagoSD';
        $resourceId = 'oryx_35CEFC1B-5CA1-4DAF-A5B8-F5A6154577FF';
        $total = $this->bpm->get_all_cases_count(null, 'pacc3PP');
        $query = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId,
            'case' => new MongoRegex('/PACC13-/'),
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
     * META = Universo PDEs (total de casos del flujo pacc1PDE)
     * cuenta: pacc1PDEF - oryx_E30BD322-F07A-4582-AA30-084613B1ACD0 - (Archivo Expte - EME)
     * URL referencia http://redmine.industria.gob.ar/issues/19376
     *
     * @name Aprobados
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @param type $filter
     *
     * @date Jun 28, 2015
     */
    function Aprobados($filter = null, $mode = 'json') {
        $title = 'Aprobados';
        $label = '';
        $idwf = 'pacc3PPF';
        $resourceId = 'oryx_C845FE55-9CF5-4D1F-8982-504731566C56';
        $total = $this->bpm->get_all_cases_count(null, 'pacc3PP');
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
     * META = Universo PDEs (total de casos del flujo pacc1PDE)
     * cuenta: pacc1PDE - oryx_8CCDBA55-463A-45B8-9D71-205154539048 - (FIN - end vent)
     * URL referencia http://redmine.industria.gob.ar/issues/19384
     *
     * @name Pre_aprobados
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @param type $filter
     *
     * @date Jun 28, 2015
     */
    function Pre_aprobados($filter = null, $mode = 'json') {
        $title = 'Pre-Aprobados';
        $label = '';
        $idwf = 'pacc3PP';
        $resourceId = 'oryx_DDDE32E8-22E4-4CEE-9E3A-7B5E71862E32';
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
     * PITCHS presentados
     *
     * META = Universo PNs (total de casos del flujo pacc3PP)
     * cuenta: pacc3PP - oryx_0B1514DD-8EE4-4AFA-831B-6445A961DDA6 - (video PN - end message event)
     * URL referencia http://redmine.industria.gob.ar/issues/19380
     *
     * @name pitchs_presentados
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @param type $filter
     *
     * @date Jun 28, 2015
     */
    function pitchs_presentados($filter = null, $mode = 'json') {
        $title = 'PITCHS Presentados';
        $label = '';
        $idwf = 'pacc3PP';
        $resourceId = 'oryx_619E288D-77A4-4483-9123-38407F73EF2E';
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
     * PITCHS Evaluados
     *
     * META = Universo PNs (total de casos del flujo pacc3PP)
     * cuenta: pacc3PP - oryx_5D8FB24B-C582-4B17-9326-D9B445353921 - (video PN - end message event)
     * URL referencia http://redmine.industria.gob.ar/issues/19380
     *
     * @name pitchs_evaluados
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @param type $filter
     *
     * @date Jun 28, 2015
     */
    function pitchs_evaluados($filter = null, $mode = 'json') {
        $title = 'PITCHS Evaluados';
        $label = '';
        $idwf = 'pacc3PP';
        $resourceId = 'oryx_0B1514DD-8EE4-4AFA-831B-6445A961DDA6';
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
     * PITCHS Aprobados
     *
     * META = Universo PNs (total de casos del flujo pacc3PP)
     * cuenta: pacc3PP - oryx_4E95C71F-A219-4088-A208-00775D2294D - (video PN - end message event)
     * URL referencia http://redmine.industria.gob.ar/issues/19381
     *
     * @name pitchs_aprobados
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @param type $filter
     *
     * @date Jun 28, 2015
     */
    function pitchs_aprobados($filter = null, $mode = 'json') {
        $title = 'PITCHS-Aprobados';
        $label = '';
        $idwf = 'pacc3PP';
        $resourceId = 'oryx_4E95C71F-A219-4088-A208-00775D2294D';
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
     * META = Universo PDEs (total de casos del flujo paccPagoSD)
     * cuenta: esta cuenta se realiza a partir de estados 7227 - td_pacc_1, restan definir los valos q cuenta.
     * URL referencia http://redmine.industria.gob.ar/issues/19379
     *
     * @name Finalizados
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @param type $filter
     *
     * @date Jun 28, 2015
     */
    function Finalizados($filter = null, $mode = 'json') {
        $title = 'Finalizados';
        $label = '(incompleto)';
        $total = 0;
        //$total=$this->bpm->get_all_cases_count(null,$idwf);
        // $query = array(
        //     'idwf' => $idwf,
        //     'resourceId' => $resourceId
        // );
        // $cant = $this->bpm->get_tokens_byFilter_count($query);
        // $label="($cant)";
        //$porc=  intval(($cant/$total)*100);
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
     * Buscador Pacc 1.3
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

        /* LOAD MODEL */
        $this->load->model('pacc13');
        $data = $this->pacc13->buscar_model($filter);

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
     * Buscador Pacc x CUIT 1.3
     *
     * Funcion para obtener las informacion relacionada a los proyectos pacc.
     *
     * @name buscar_by_cuit
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $parameter cuit
     */
    function buscar_by_cuit($parameter, $mode = 'json') {

        /* LOAD MODEL */
        $this->load->model('pacc13');
        $data = $this->pacc13->buscar_empresa_model($parameter);

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
     * Dashoards Incubadoras: Agregar los endpoints
     *
     * Nombre Incubadora x id en td_pacc
     * Incubadora (2013 y 2014) id en 7949
     * incubadora (2011 y 2012) id en 6071
     * URL referencia http://redmine.industria.gob.ar/issues/20570
     *
     * @example https://dna2-lucianomenez.c9.io/c9/pacc/incubadoras
     *
     * @name incubadoras_proy_presentados
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     */
    function incubadoras_proy_presentados($mode = 'json') {

        $idu = $this->idu;
        //var_dump($idu);
        $id = $this->incubadoras_usuarios_grupo($idu);
        if (is_null($id)) {
            $id = 'null';
        }
        //var_dump($id);
        //$id = '1207465625';
        $query = array('status' => 'activa', '5689' => array('$exists' => true), '7356' => array('$exists' => true), '7949' => array($id));
        //var_dump($query);
        $container = 'container.proyectos_pacc';

        $data = $this->pacc13->incubadoras_model($query, $container);

        if (!isset($data['result'][0]['cantidad'])) {
            $data['result'][0]['cantidad'] = 0;
        }
        //var_dump($data);
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
     * Dashoards Incubadoras: Agregar los endpoints
     *
     * Nombre Incubadora x id en td_pacc
     * Incubadora (2013 y 2014) id en 7949
     * incubadora (2011 y 2012) id en 6071
     * URL referencia http://redmine.industria.gob.ar/issues/20570
     *
     * @example https://dna2-lucianomenez.c9.io/c9/pacc/incubadoras
     *
     * @name incubadoras_proy_aprobados
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     */
    function incubadoras_proy_aprobados($mode = 'json') {

        $idu = $this->idu;
        $id = $this->incubadoras_usuarios_grupo($idu);
        //var_dump($id);
        if (is_null($id)) {
            $id = 'null';
        }



        $query = array('status' => 'activa', '7356' => array('$exists' => true), '5689' => array('$gte' => "120"), '7949' => $id);


        $container = 'container.proyectos_pacc';

        $data = $this->pacc13->incubadoras_model($query, $container);

        if (!isset($data['result'][0]['cantidad'])) {
            $data['result'][0]['cantidad'] = 0;
        }
        //var_dump($data);
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
     * Dashoards Incubadoras: Agregar los endpoints
     *
     * Nombre Incubadora x id en td_pacc
     * Incubadora (2013 y 2014) id en 7949
     * Incubadora (2011 y 2012) id en 6071
     * Facturas Aprobadas Estado 6783
     * URL referencia http://redmine.industria.gob.ar/issues/20570
     *
     * @example https://dna2-lucianomenez.c9.io/c9/pacc/incubadoras
     *
     * @name incubadoras_facturas_aprobadas
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     */
    function incubadoras_facturas_aprobadas($mode = 'json') {

        /*
         */

        $idu = $this->idu;
        $id = $this->incubadoras_usuarios_grupo($idu);
        if (is_null($id)) {
            $id = 'null';
        }
        //var_dump($id);
        $query_proy = array('status' => 'activa', '7356' => array('$exists' => true), '5689' => array('$gte' => "120"), '7949' => $id);
        //$query_proy = array('status' => 'activa', '4837' => array('$exists' => true),'6071' => $id);
        $container_proy = 'container.proyectos_pacc';
        $data_proy = $this->pacc13->incubadoras_user_proy_model($query_proy, $container_proy);
        //var_dump($data_proy);
        $num = 0;
        foreach ($data_proy as $proyectos) {
            $query = array('status' => 'activa', '6783' => '50', 'parent' => array('container.proyectos_pacc' => array(0 => $proyectos['id'])));
            //var_dump($query);
            $container = 'container.facturas';
            $data = $this->pacc13->incubadoras_model($query, $container);
            //var_dump($data);
            if (isset($data)) {
                $num = $num + ($data['result'][0]['cantidad']);
            }
        }

        $data['result'][0]['cantidad'] = $num;
        //var_dump($data);

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

    function incubadoras_usuarios_grupo($id) {
        $query = array('idu' => $id);
        $container = 'users';
        $data = $this->pacc13->incubadoras_user_model($query, $container);
        return $data;
    }

    /**
     * Dashoards Incubadoras: Agregar los endpoints
     *
     * Retribuciones Presentadas R
     * Retribución (1 (10) o 2 (20)) en 6701
     * URL referencia http://redmine.industria.gob.ar/issues/20570
     *
     * @example https://dna2-lucianomenez.c9.io/c9/pacc/incubadoras
     *
     * @name incubadoras_retibuciones_presentadas
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     */
    function incubadoras_retibuciones_presentadas($mode = 'json') {

        $idu = $this->idu;
        $id = $this->incubadoras_usuarios_grupo($idu);

        if (is_null($id)) {
            $id = 'null';
        }

        $query_proy = array('status' => 'activa', '7356' => array('$exists' => true), '5689' => array('$gte' => "120"), '7949' => $id);

        //$query_proy = array('status' => 'activa', '4837' => array('$exists' => true),'6071' => $id);
        $container_proy = 'container.proyectos_pacc';
        $data_proy = $this->pacc13->incubadoras_user_proy_model($query_proy, $container_proy);
        //var_dump($data_proy);
        $num = 0;
        foreach ($data_proy as $proyectos) {
            //var_dump($proyectos);
            if (isset($proyectos[6933])) {
                if ($proyectos[6933][0] >= 0) {
                    $num ++;
                }
            }
            if (isset($proyectos[6934])) {
                if ($proyectos[6934][0] >= 0) {
                    $num ++;
                }
            }
            if (isset($proyectos[6935])) {
                if ($proyectos[6935][0] >= 0) {
                    $num ++;
                }
            }
            if (isset($proyectos[6936])) {
                if ($proyectos[6936][0] >= 0) {
                    $num ++;
                }
            }
            //var_dump($num);
            /* $query = array('status' => 'activa', '6701' => array('$in' => array('10', '20')),'parent' => array('container.proyectos_pacc' => array(0=>$proyectos['id'])));
              //var_dump($query);
              $container = 'container.facturas';
              $data = $this->pacc13->incubadoras_model($query, $container);
              if(isset($data)){
              $num = $num + ($data['result'][0]['cantidad']);
              } */
        }

        $data['result'][0]['cantidad'] = $num;


        //$query = array('status' => 'activa', '6701' => array('$in' => array('10', '20')));
        // $container = 'container.facturas';
        // $data = $this->pacc13->incubadoras_model($query, $container);

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
     * Dashoards Incubadoras: Agregar los endpoints
     *
     * Estado 6783
     * $ Aprobado Retrib 6785
     * Transferencia 6859 (Si está en blanco no está transferido)
     * URL referencia http://redmine.industria.gob.ar/issues/20570
     *
     * @example https://dna2-lucianomenez.c9.io/c9/pacc/incubadoras
     *
     * @name incubadoras_retibuciones_pagadas
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     */
    function incubadoras_retibuciones_pagadas($mode = 'json') {

        $idu = $this->idu;
        $id = $this->incubadoras_usuarios_grupo($idu);

        if (is_null($id)) {
            $id = 'null';
        }
        $query_proy = array('status' => 'activa', '7356' => array('$exists' => true), '5689' => array('$gte' => "120"), '7949' => $id);

        //$query_proy = array('status' => 'activa', '4837' => array('$exists' => true),'6071' => $id);
        $container_proy = 'container.proyectos_pacc';
        $data_proy = $this->pacc13->incubadoras_user_proy_model($query_proy, $container_proy);
        //var_dump($data_proy);
        $num = 0;
        foreach ($data_proy as $proyectos) {
            $query = array('status' => 'activa', '6859' => array('$exists' => true), 'parent' => array('container.proyectos_pacc' => array(0 => $proyectos['id'])));
            //var_dump($query);
            $container = 'container.facturas';
            $data = $this->pacc13->incubadoras_model($query, $container);
            if (isset($data)) {
                $num = $num + ($data['result'][0]['cantidad']);
            }
        }

        $data['result'][0]['cantidad'] = $num;

        //$query = array('status' => 'activa', '6859' => array('$exists' => true));
        //$container = 'container.facturas';
        //$data = $this->pacc13->incubadoras_model($query, $container);

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
     * Dashoards Incubadoras: Agregar los endpoints
     *
     * URL referencia http://redmine.industria.gob.ar/issues/20570
     *
     * @example https://dna2-lucianomenez.c9.io/c9/pacc/incubadoras
     *
     * @name incubadoras_ranking
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function incubadoras_ranking($filter = null, $mode = 'json') {

        $data = $this->pacc13->get_ranking_incubadoras_model($filter);

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

    /*
     * Cantidad de proyectos reclamados por CD (vencimiento de Cronograma), con presentación o acción/respuesta del beneficiario
     *
     * Este informe pertenece al Subcomponente 1.3. Apoyo a la Actividad Emprendedora
     * Gráfico de barras  Cantidad de proyectos reclamados por CD (vencimiento del cronograma)
     * Cantidad de proyectos que presentaron
     * Cantidad de proyectos que no presentaron
     * Cantidad de proyectos que desistieron"
     *
     * URL referencia http://redmine.industria.gob.ar/issues/19634
     *
     * @example https://dna2-lucianomenez.c9.io/c9/pacc/incubadoras
     *
     * @name cant_proyectos_reclam_cd_venc_crono
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */

    function cant_proyectos_reclam_cd_venc_crono($filter = null, $mode = 'json') {
        //   Control 8180 -> (opcion)760  Valor 120

        $filter = array();
        $filter['container'] = 'container.proyectos_pacc';

        $data = $this->pacc13->get_cant_proyectos_reclam_cd_venc_crono_model($filter);

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

    /*
     * Cantidad de proyectos reclamados por CD (Recupero de Deuda), con presentación o acción/respuesta del beneficiario
     *
     * Este informe pertenece al Subcomponente 1.3. Apoyo a la Actividad Emprendedora
     * Gráfico de Barras
     * Deuda Total/Devolución Total
     * Proyectos reclamados/Proyectos que presentaron respuesta
     *
     * Deuda Total 2014
     * Devolución Total 2014
     * Cantidad de Proyectos reclamados CD
     * Cantidad de Proyectos que realizaron devolución
     * Cantidad de Proyectos que no presentaron respuesta
     * Cantidad de Proyectos que presentaron documentación
     * URL referencia http://redmine.industria.gob.ar/issues/19633
     *
     * @example https://dna2-lucianomenez.c9.io/c9/pacc/incubadoras
     *
     * @name cant_proyectos_reclam_cd_deuda
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     *
     */

    function cant_proyectos_reclam_cd_deuda($filter = null) {

        //8553 en el "RECUPERO" {8553:/RECUPERO/i}

        $filter = array();
        $filter['container'] = 'container.proyectos_pacc';

        $data = $this->pacc13->get_cant_proyectos_reclam_cd_deuda_model($filter);

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
     * Devolver incubadora por id
     *
     * Una vez seleccionada una incubadora, necesito una función que al mandarle un id devuelva esto.
     *

     * URL referencia http://redmine.industria.gob.ar/issues/22998
     *
     * @name incubadoras_listado
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
    function incubadoras_listado($mode = 'json') {
        $query = array(
                          9498 => '120',
                          9969 => '20'
        );
        $container = 'container.agencias';
        $get_data = $this->pacc13->get_incubadoras_model($query, $container);

        $data = array();
        
        foreach ($get_data as $each) {
            $each = (array) $each;

            if (isset($each['4896'])) {
                $data[$each['owner']]['nombre'] = $each['4896'];
                $data[$each['owner']]['owner'] = $each['owner'];
                // $data[$each['id']]['id'] = $each['id'];
            }
        }

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
     * Devolver incubadora por id
     *
     * Una vez seleccionada una incubadora, necesito una función que al mandarle un id devuelva esto.
     *

     * URL referencia http://redmine.industria.gob.ar/issues/22998
     *
     * @name incubadora_por_id
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
    function incubadora_por_id($id = null, $mode = 'json') {

        $query = array('4899' => 'INCUBA', 'id' => (float) $id);

        $container = 'container.agencias';
        $data = $this->pacc13->incubadora_por_id($id);

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
        /* SOLO INCUBADORAS */
        $query = array('4899' => 'INCUBA', 'id' => (float) $id);

        $container = 'container.agencias';
        $data = $this->pacc13->incubadora_nombre_model($query, $container);

        return $data;
    }

    /**
     * Devolver incubadora por id
     *
     * Una vez seleccionada una incubadora, necesito una función que al mandarle un id devuelva esto.
     *

     * URL referencia http://redmine.industria.gob.ar/issues/22998
     *
     * @name incubadoras_por_id
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $parameter
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
    function incubadoras_por_id($parameter = null, $mode = "json") {

        $filter = array();
        $filter['incubadora'] = $parameter;
        $get_data = $this->pacc13->get_incubadoras_por_provincia_model($filter);


        $data = array();
        $data_sum = array();
        foreach ($get_data['result'] as $each) {


            $provincia = $each['_id']['provincia'];
            if (isset($provincia) || $provincia != "") {
                foreach ($each['_id']['estado'] as $estado) {
                    $nombre_incubadora = str_replace(".", "", $this->incubadora_nombre($each['_id']['incubadora']));


                    if ($nombre_incubadora) {
                        $filter = array("provincia" => $provincia, "estado" => $estado, "incubadora" => $each['_id']['incubadora']);
                        $qty = $this->pacc13->get_quantity_by_status_incubadora($filter);
                        $get_desembolso = $this->pacc13->incubadoras_desembolso_model($each['_id']['incubadora'], $provincia);


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
     * Listado de todas las incubadoras
     *
     * Necesito una función que me devuelta todas las incubadoras en formato de array de arrays
     * URL referencia http://redmine.industria.gob.ar/issues/22997
     *
     * @name incubadoras_por_provincia
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function incubadoras_por_provincia($filter = null, $mode = 'json') {
        /* UPDATE DB */
        $get_data = $this->pacc13->get_dashboard_tmp($var = "_incu");
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
     * Widget - Incubadoras según Provincia y Localidad
     *
     * Necesito una función que me devuelta todas las incubadoras en formato de array de arrays
     * URL referencia http://redmine.industria.gob.ar/issues/23079
     *
     * @name incubadoras_por_partido
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function incubadoras_por_partido($filter = null, $mode = 'json') {

        $get_data = $this->pacc13->get_incubadoras_por_provincia_model($filter);

        $data = array();
        foreach ($get_data['result'] as $each) {
            if (isset($each['_id']['partido'])) {

                foreach ($each['_id']['estado'] as $estado) {

                    $nombre_incubadora = $this->incubadora_nombre($each['_id']['incubadora']);
                    $incubadora = isset($nombre_incubadora) ? $nombre_incubadora : $each['_id']['incubadora'];
                    $partido = $each['_id']['partido'];

                    $data[$partido][$incubadora][$estado] = $each['cantidad'];
                }
            }
        }

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
     * Incubadoras por sección electoral
     *
     * Al clickear en cada provincia se imprime una tabla dinámica que carga un listado de las secciones electorales y sus respectivas incubadoras.
     * Necesito una función que al mandarle un numero de provincia devuelva un array de arrays con las N-secciones y sus incubadoras.
     * URL referencia http://redmine.industria.gob.ar/issues/22999
     *
     * @name incubadoras_por_seccion_electoral
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function incubadoras_por_seccion_electoral($filter = null, $mode = 'json') {



        $get_data = $this->pacc13->get_incubadoras_por_provincia_model($filter);

        $data = array();

        foreach ($get_data['result'] as $each) {
            if (isset($each['_id']['partido'])) {

                /* SECCION ELECTORAL */
                $filter_seccion = array('container' => 'container.partidos', 'id' => $each['_id']['partido_id']);
                $seccion_electoral = $this->pacc13->get_by_id($filter_seccion);

                if (isset($seccion_electoral[0]['seccion_electoral'])) {
                    $seccion_electoral_nro = $seccion_electoral[0]['seccion_electoral'];
                    $nombre_incubadora = $this->incubadora_nombre($each['_id']['incubadora']);
                    $incubadora = isset($nombre_incubadora) ? $nombre_incubadora : $each['_id']['incubadora'];

                    $data[$seccion_electoral_nro]['cantidad'] = $each['cantidad'];
                }
            }
        }

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
     * Incubadoras por sección electoral
     *
     * Al clickear en cada provincia se imprime una tabla dinámica que carga un listado de las secciones electorales y sus respectivas incubadoras.
     * Necesito una función que al mandarle un numero de provincia devuelva un array de arrays con las N-secciones y sus incubadoras.
     * URL referencia http://redmine.industria.gob.ar/issues/22999
     *
     * @name seccion_electoral_by_id
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param $partido
     */
    function seccion_electoral_by_id($partido) {

        $filter = array('container' => 'container.partidos', 'id' => $partido);
        $data = $this->pacc13->get_by_id($filter);

        return $data;
    }

    /**
     * Incubadoras por sección electoral
     *
     * Al clickear en cada provincia se imprime una tabla dinámica que carga un listado de las secciones electorales y sus respectivas incubadoras.
     * Necesito una función que al mandarle un numero de provincia devuelva un array de arrays con las N-secciones y sus incubadoras.
     * URL referencia http://redmine.industria.gob.ar/issues/22999
     *
     * @name filtrar_partidos
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function filtrar_partidos($filter = null, $mode = 'json') {

        $get_data = $this->pacc13->get_option_info($opt = null, $filter);

        $data = array();

        foreach ($get_data as $key => $value) {
            $data[$key]['codigo'] = $key;
            $data[$key]['nombre'] = $value;
        }


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
     * dashboard
     *
     * Funcion para unificar la informacion y hacer mas agil/dinamica la visualizacion de los mapas & graficos
     * en los diferentes Dashboards de la app.
     *
     * @name dashboard_sub_emprendedores_totales
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function dashboard_sub_emprendedores_totales($filter = null, $mode = 'json') {

        /* UPDATE DB */
        $get_data = $this->pacc13->get_dashboard_tmp();
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
     * dashboard
     *
     * Funcion para unificar la informacion y hacer mas agil/dinamica la visualizacion de los mapas & graficos
     * en los diferentes Dashboards de la app.
     *
     * @name dashboard_sub_emprendedores
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function dashboard_sub_emprendedores($filter = null, $mode = 'json') {


        /* UPDATE DB */
        $get_data = $this->pacc13->get_dashboard_tmp();
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
     * Deuda Total / Retribución pagada y no pagada.
     *
     * URL referencia http://redmine.industria.gob.ar/issues/23660
     *
     * @name retribuciones_pagadas_emprendedores
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     */
    function retribuciones_pagadas_emprendedores($mode = 'json') {
        /*
         * Facturas de Retribuciones 8187  //Fecha de Pago 6903
         * Orden de Pago 6904
         */
        $filter = null;
        $array_facturadas = array();
        $array_pagadas = array();

        $get_facturadas = $this->pacc13->get_retribuciones_model($filter);

        foreach ($get_facturadas['result'] as $result) {
            $str = substr($result['_id'], 0, 7);
            if (!isset($array_facturadas[$str])) {
                $array_facturadas[$str] = array('facturadas' => 0);
            }
            $array_facturadas[$str]['facturadas'] += $result['cantidad'];
        }

        $filter['pagadas'] = true;
        $get_pagadas = $this->pacc13->get_retribuciones_model($filter);


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

    /**
     * Funcion para interoperabilidad
     * devuelve un JSON del proyecto
     */
    function get_estructura() {
        $this->load->model('pacc/model_pacc');
        $this->load->model('app');
        $parts = $this->uri->segment_array();
        $ipstr = $parts[4] . '/' . $parts[5];
        $rs = $this->model_pacc->get_proyecto($ipstr);
        $rtn = array();
        if ($rs) {
            //var_dump($rs[0]);
            $ops = $this->app->get_ops(622);
            $rtn['check'] = true;
            $rtn['timestamp'] = date('Y-m-d H:i:s');
            $rtn['titulo'] = (isset($rs[0][5673])) ? $rs[0][5673] : '';
            $rtn['estado'] = (isset($rs[0][6225])) ? $ops[$rs[0][6225][0]] : '';
            $rtn['id'] = $rs[0]['id'];
            //---Tomo datos de la empresa
            if (isset($rs[0][6065])) {
                $idemp = $rs[0][6065][0];
                // var_dump($idemp);exit;
                $rtn['CUIT'] = $this->app->getvalue($idemp, 1695);
                $rtn['nombre'] = $this->app->getvalue($idemp, 1693);
            }
            //----Actividades
            if (isset($rs[0]['5729'])) {
                foreach ($rs[0]['5729'] as $id_act) {
                    $act = array();
                    $act['id'] = (int) $id_act;
                    $titulo = $this->app->getvalue($id_act, 5730);
                    $act['titulo'] = $ops[$titulo[0]];
                    $tareas = $this->app->getvalue($id_act, 5742);
                    if ($tareas) {
                        foreach ($tareas as $id_tarea) {
                            $tarea = array();
                            $tarea['id'] = $id_tarea;
                            $tarea['titulo'] = $this->app->getvalue($id_tarea, 6056);
                            $tarea['valor'] = $this->app->getvalue($id_tarea, 5738);
                            $tarea['orden'] = $this->app->getvalue($id_tarea, 5735);

                            $act['Tareas'][] = $tarea;
                        }
                    }
                    $rtn['Actividades'][] = $act;
                }
            }
        }
        //----para cors
        header("Access-Control-Allow-Origin: *");
        $this->output->set_content_type('json');
        echo json_encode($rtn);
    }

    function set_estructura() {

        $input = json_decode(file_get_contents('php://input'));
        //$input=(is_array($input)) ? $input:array();
        $input->error = json_last_error_msg();
        //  switch(json_last_error()) {
        //     case JSON_ERROR_NONE:
        //         $input['error']=' - Sin errores';
        //     break;
        //     case JSON_ERROR_DEPTH:
        //         $input['error'] =' - Excedido tamaño máximo de la pila';
        //     break;
        //     case JSON_ERROR_STATE_MISMATCH:
        //         $input['error'] =' - Desbordamiento de buffer o los modos no coinciden';
        //     break;
        //     case JSON_ERROR_CTRL_CHAR:
        //         $input['error']=' - Encontrado carácter de control no esperado';
        //     break;
        //     case JSON_ERROR_SYNTAX:
        //         $input['error']=' - Error de sintaxis, JSON mal formado';
        //     break;
        //     case JSON_ERROR_UTF8:
        //         $input['error']=' - Caracteres UTF-8 malformados, posiblemente están mal codificados';
        //     break;
        //     default:
        //         $input['error']=' - Error desconocido';
        //     break;
        // }
        //----para cors
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: json");
        echo json_encode($input);
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */