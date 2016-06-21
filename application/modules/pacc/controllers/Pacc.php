<?php

/**
 * Description of pacc
 *
 * @author juanb
 * @date   Jan 16, 2015
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class pacc extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->model('bpm/bpm');

        // ----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
        $this->user->authorize();
        /* GROUP */
        $user = $this->user->get_user($this->idu);

        $this->id_group = ($user->{'group'});
    }

    /*
     * Main function if no other invoked
     */

    function Index() {
        echo "<h1>" . $this->router->fetch_module() . '</h1>';
    }

    function listar_pp($action = null) {
        $this->user->authorize();
        $this->load->library('parser');
        $this->load->model('app');
        $option = $this->app->get_ops(772);
        $template = 'pacc/listar_proyectos_fechas_pp';
        $filter = array(
            'idwf' => 'pacc1PDE',
//            'id' => 'VNSU'
        );
        $cases = $this->bpm->get_cases_byFilter($filter, array(), array('checkdate'));
//        var_dump($cases[0]);exit;
//        var_dump(json_encode($filter),count($tokens));
        $i = 0;
        foreach ($cases as $case) {

            $case_data = $this->bpm->load_case_data($case);
            if (!isset($case_data ['Proyectos_pacc'] ['8339']))
                continue;
            /* STATUS */
            $status = "N/A";
            if (isset($case_data ['Proyectos_pacc'] ['8334'])) {
                $status = $option[$case_data ['Proyectos_pacc'] ['8334'][0]];
            }
            $i++;
            $arr = array(
                'case' => $case['id'],
                'nombre' => (isset($case_data['Empresas']['1693'])) ? $case_data['Empresas']['1693'] : '',
                'cuit' => (isset($case_data['Empresas']['1695'])) ? $case_data['Empresas']['1695'] : '',
                'Nro' => (isset($case_data ['Proyectos_pacc'] ['8339'])) ? $case_data ['Proyectos_pacc'] ['8339'] : 'N/A',
            );


//            $arr['fechapresentacion']=isset($case_data['Proyectos_pacc']['8340'])?date('d/m/Y', strtotime($case_data['Proyectos_pacc']['8340'])):'???';
            //---saco fecha presentación
            $date = explode('/', $case_data['Proyectos_pacc']['8340']);
            $arr['fechapresentacion'] = (count($date)) ? $date[2] . '/' . $date[1] . '/' . $date[0] : '???';
            //---fecha aprobacion / rechazo
            //---busco solicitud rechazada
            $rechazada = $this->get_token_history('oryx_FE3863C1-F7F4-40E1-95E7-FF407112C648', $case['history']);
            if (count($rechazada)) {
                $arr['fechafinal'] = date('d/m/Y', strtotime($rechazada[0]['checkdate']));
                $arr['estado'] = 'Solicitud No Admisible';
            }
            //---busco proyecto rechazado
            $proy_rechazado = $this->get_token_history('oryx_CE7D350E-FEA2-4BFF-B96D-77B29D249C7D', $case['history']);
            if (count($proy_rechazado)) {
                $arr['fechafinal'] = date('d/m/Y', strtotime($proy_rechazado[0]['checkdate']));
                $arr['estado'] = 'Proyecto Rechazado';
            }
            //---busco proyecto pre-aprobado
            $proy_pre = $this->get_token_history('oryx_2882861D-9261-4874-8FA9-12BA72AC43C0', $case['history']);
            if (count($proy_pre)) {
                $arr['fechafinal'] = date('d/m/Y', strtotime($proy_pre[0]['checkdate']));
                $arr['estado'] = 'Proyecto Preaprobado';
            }
            //---busco proyecto baja
            $proy_baja = $this->get_token_history('oryx_16600243-7CAC-4EAB-8AF8-0A135CE14FEE', $case['history']);
            if (count($proy_baja)) {
                $arr['fechafinal'] = date('d/m/Y', strtotime($proy_baja[0]['checkdate']));
                $arr['estado'] = 'Baja Solicitud Coordinación';
            }
            $data['proyectos'][] = $arr;
        }

        $data ['count'] = $i;
        $data['base_url'] = $this->base_url;
//        var_dump($data);
//        exit;
        if ($action == 'xls') {
            header("Content-Description: File Transfer");
            header("Content-type: application/x-msexcel");
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=listado_pp.xls");
            header("Content-Description: PHP Generated XLS Data");
        }

        $this->parser->parse($template, $data, false, true);
    }

    function listar_pde($action = null) {
        $this->user->authorize();
        $this->load->library('parser');
        $this->load->model('app');
        $option = $this->app->get_ops(772);
        $template = 'pacc/listar_proyectos_fechas_pde';
        $filter = array(
            'idwf' => 'paccpde',
        );
        $cases = $this->bpm->get_cases_byFilter($filter, array(), array('checkdate'));
//        var_dump($cases[0]);exit;
//        var_dump(json_encode($filter),count($tokens));

        $i = 0;
        foreach ($cases as $case) {

            $case_data = $this->bpm->load_case_data($case);
            if (!isset($case_data ['Proyectos_pacc'] ['8339']))
                continue;
            /* STATUS */
            $status = "N/A";
            if (isset($case_data ['Proyectos_pacc'] ['8334'])) {
                $status = $option[$case_data ['Proyectos_pacc'] ['8334'][0]];
            }
            $i++;
            $arr = array(
                'case' => $case['id'],
                'nombre' => (isset($case_data['Empresas']['1693'])) ? $case_data['Empresas']['1693'] : '',
                'cuit' => (isset($case_data['Empresas']['1695'])) ? $case_data['Empresas']['1695'] : '',
                'Nro' => (isset($case_data ['Proyectos_pacc'] ['8339'])) ? $case_data ['Proyectos_pacc'] ['8339'] : 'N/A',
                'estado' => $status,
            );


//            $arr['fechapresentacion']=isset($case_data['Proyectos_pacc']['8340'])?date('d/m/Y', strtotime($case_data['Proyectos_pacc']['8340'])):'???';
            //---saco fecha presentación PP
            $f0 = $this->bpm->get_token('pacc1PDE', $case['id'], 'oryx_4EFED47A-7AAD-4728-9577-49F8901AC5B9');
            $arr['fechapresentacionPP'] = (count($f0)) ? date('d/m/Y', strtotime($f0['checkdate'])) : '';
            //---saco fecha presentación
            $f1 = $this->bpm->get_token('pacc1PDE', $case['id'], 'oryx_A58D5ECD-6899-4F60-856C-CFE89B36FB91');
            $arr['fechapresentacion'] = (count($f1)) ? date('d/m/Y', strtotime($f1['checkdate'])) : '';
            //-----tomo el evaluador
            $iduser = $case_data ['Proyectos_pacc'] ['8668'][0];
            $evaluador = $this->user->get_user_safe($iduser);
            $arr['evaluador'] = $evaluador->name . ' ' . $evaluador->lastname;
            //---Tomo primera y ultima fecha eval
            $f1 = $this->get_token_history('oryx_9246751E-B435-4359-988B-8E1B84932A50', $case['history']);
            if (count($f1)) {
                $arr['fechaprimereval'] = (count($f1)) ? date('d/m/Y', strtotime($f1[0]['checkdate'])) : '';
                $arr['fechaultimoeval'] = (count($f1)) ? date('d/m/Y', strtotime($f1[count($f1) - 1]['checkdate'])) : '';
            } else {
                //tomo del token
                $f1 = $this->bpm->get_token('paccpde', $case['id'], 'oryx_A58D5ECD-6899-4F60-856C-CFE89B36FB91');
                $arr['fechaprimereval'] = (count($f1)) ? date('d/m/Y', strtotime($f1['checkdate'])) : '';
                $arr['fechaultimoeval'] = (count($f1)) ? date('d/m/Y', strtotime($case['checkdate'] . ' +' . $f1['interval']['days'] . ' days')) : '';
                $arr['case'].='*';
            }

            $data['proyectos'][] = $arr;
        }

        $data ['count'] = $i;
        $data['base_url'] = $this->base_url;
//        var_dump($data);
//        exit;
        if ($action == 'xls') {
            header("Content-Description: File Transfer");
            header("Content-type: application/x-msexcel");
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=listado_pde.xls");
            header("Content-Description: PHP Generated XLS Data");
        }

        $this->parser->parse($template, $data, false, true);
    }

    function tile_buscar_container() {
        $this->load->module('dashboard');
        $this->user->authorize();
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['action'] = $this->base_url . 'pacc/buscar_container';
        $data['content'] = $this->parser->parse('pacc/buscador-resumen-proyecto', $data, true);
        return $this->dashboard->widget($template, $data, true, true);
    }

    function buscar_proyectos($type = null, $query) {

        $filter = array(
            'idwf' => 'pacc1PDE',
            'resourceId' => 'oryx_0962BF68-BBCD-470D-A307-C4453AFA4FBA'
        );
        $data ['querystring'] = $query;
        // -----busco en el cuit
        $filter ['$or'] [] = array(
            'data.1695' => array(
                '$regex' => new MongoRegex('/' . $query . '/i')
            )
        );
        // -----busco en el nombre empresa
        $filter ['$or'] [] = array(
            'data.1693' => array(
                '$regex' => new MongoRegex('/' . $query . '/i')
            )
        );
        // -----busco en el nro proyecto
        $filter ['$or'] [] = array(
            'data.6390' => array(
                '$regex' => new MongoRegex('/' . $query . '/i')
            )
        );
        $filter ['$or'] [] = array(
            'case' => array(
                '$regex' => new MongoRegex('/' . $query . '/i')
            )
        );
        $tokens = $this->bpm->get_tokens_byFilter($filter, array(
            'case',
            'data',
            'checkdate'
                ), array(
            'checkdate' => false
        ));
        return $tokens;
    }

    function buscar($type = null) {
        $this->user->authorize();
        $this->load->library('parser');
        $this->load->model('pacc/model_pacc');
        $template = 'pacc/listar_proyectos';
        $grupo_evaluador = 134;
        $grupo_coordinador = 134;
        $query = $this->input->post('query');
        $tokens = $this->model_pacc->buscar_proyectos($type, $query);

        $data ['empresas'] = array_map(function ($token) use ($grupo_coordinador, $grupo_evaluador) {
            // var_dump($token['_id']);
            $case = $this->bpm->get_case($token ['case'], 'pacc1PDE');
            $paccpde = $this->bpm->get_case($token ['case'], 'pacc11');
            $data = $this->bpm->load_case_data($case);

            $url = (isset($data ['Proyectos_pacc']['id'])) ? '../dna2/frontcustom/284/print_pacc_preA.php?id=' . $data ['Proyectos_pacc'] ['id'] : '#';
            $url_msg = (isset($token ['case'])) ? $this->base_url . 'pacc/show_msgs/' . $token ['case'] : null;
            /* pacc/COORDINADOR (134) */
            $keys = array_keys($case['token_status']);
            // var_dump($keys);
            $url_clone = (
                    (in_array(147, $this->id_group) or in_array(148, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'closed' and in_array('oryx_BEB71C63-D63E-4510-82F1-D04118F228B9', $keys) //---está finalizado pero por esta figura
                    and in_array($data ['Proyectos_pacc'] ['6225'][0], array(60, 90))//---checkeo que esté en alguno de esos estados
                    ) ? $this->base_url . 'pacc11/clone_case/pacc1PDE/pacc1PDEF/' . $token ['case'] : null;
            //---link para cancelar solo para coordinador
            $url_cancelar_pp = ((in_array(147, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'open') ? $this->base_url . 'pacc/cancelar_pp/' . $token ['case'] : null;
            $url_cancelar_pde = (
                    (in_array(147, $this->id_group) or $this->user->isAdmin()) and in_array('oryx_3346C091-4A4D-4DCD-8DEC-B23C5FE7F80C', $keys) //---está finalizado pero por esta figura
                    and $case['status'] == 'closed'
                    ) ? $this->base_url . 'pacc/cancelar_pde/' . $token ['case'] : null;
            //---url para checkear

            $url_bpm = '';
            if (in_array($grupo_evaluador, $this->id_group) or in_array($grupo_evaluador, $this->id_group) or $this->user->isAdmin()) {
                $model = ($paccpde) ? 'pacc1PDE' : 'pacc11';
                $url_bpm = $this->base_url . 'bpm/engine/run/model/' . $model . '/' . $token ['case'];
            }

            /* STATUS */
            $status = "N/A";
            if (isset($data ['Proyectos_pacc'] ['6225'])) {
                $this->load->model('app');
                $option = $this->app->get_ops(648);
                $status = $option[$data ['Proyectos_pacc'] ['6225'][0]];
            }


            return array(
                '_d' => $token ['_id'],
                'case' => $token ['case'],
                'nombre' => (isset($data['Empresas']['1693'])) ? $data['Empresas']['1693'] : '',
                'cuit' => (isset($data['Empresas']['1695'])) ? $data['Empresas']['1695'] : '',
                'Nro' => (isset($data ['Proyectos_pacc'] ['6390'])) ? $data ['Proyectos_pacc'] ['6390'] : 'N/A',
                'estado' => $status,
                'fechaent' => date('d/m/Y', strtotime($token ['checkdate'])),
                'link_open' => $this->bpm->gateway($url),
                'link_msg' => $url_msg,
                'url_clone' => $url_clone,
                'url_bpm' => $url_bpm,
                'url_cancelar_pp' => $url_cancelar_pp,
                'url_cancelar_pde' => $url_cancelar_pde,
            );
        }, $tokens);
        $data ['count'] = count($tokens);
        $data['base_url'] = $this->base_url;
        //   var_dump($data);
        //    exit;


        $this->parser->parse($template, $data, false, true);
    }

    function buscar_container($page = 1) {


        $this->user->authorize();
        $this->load->library('parser');
        $this->load->model('pacc/model_pacc');
        $this->load->model('pacc11/pacc11');
        $this->load->model('pacc13/pacc13');
        $this->load->model('app');
        $this->load->library('pagination');
        //$this->load->library('session');
        $template = 'pacc/listar_proyectos';

        /*FONDYF ???*/
        $grupo_evaluador = 134;
        $grupo_coordinador = 134;


        $grupo_pacc11_me = 150;
        $grupo_pacc13_me = 562;

        define("PAGINATION_ITEMS_X_PAGE", 10);


        //==== query
        $query = $this->input->post('query');
        $type = $this->input->post('type');
        $program = $this->input->post('programa');

        //===== @PAGINADO - Guardo busqueda
        $me = $this->router->fetch_module() . '/' . __FUNCTION__;
        if ($this->input->post()) {
            $this->session->set_userdata(array($me => $this->input->post()));
        } else {
            extract($this->session->userdata($me));
        }
        //==



        $data['querystring'] = $query;
        /**
         * TYPES
         * nro, cuit, proyecto
         */
        switch ($type) {
            case 'ip':
                $filter['ip'] = (string) $query;
                break;

            case 'cuit':
                $filter['cuit'] = $query;
                break;

            default:
                $filter['id'] = $query;
                break;
        }
        
        
      

        /* PROGRAM */
        switch ($program) {
            case 'pacc13':
                $rs = $this->pacc13->buscar_proyectos($filter);
                break;

            default:
                $rs = $this->pacc11->buscar_proyectos($filter, $page, PAGINATION_ITEMS_X_PAGE);
                break;
        }

       // var_dump($type,$query,$program,$rs);exit;
        $data ['count'] = $rs['recordCount'];
        unset($rs['recordCount']);

        /*  $grupo_pacc11_me = 150;
        $grupo_pacc13_me = 562;*/
        $data['empresas'] = array();
        array_map(function ($token) use (&$data,$grupo_coordinador, $grupo_evaluador, $grupo_pacc11_me, $grupo_pacc13_me, $program) {


            $url_msg = '';
            $url_bpm = '';
            $url_cancelar_pp = '';
            $url_cancelar_pde = '';
            $url_clone = '';
            $case_id = '';
            $nombre = '';
            $cuit = '';

            $solicitud = false;

            /* PROGRAM */
            switch ($program) {
                case 'pacc13':
                    //$idwf = "pacc3PP";
                    $fecha_ctrl = isset($token['8632']) ? $token['8632'] : "";
                    $ip_ctrl = $token['5691'];
                    $empresa_ctrl = $token['6065'][0];
                    $status_ctrl = $token['5689'][0];

                    /* SOLICITUDES */
                    if ($status_ctrl == 90 || $status_ctrl == 60) {
                        $solicitud_array = array('6933', '6934', '6935');
                        foreach ($solicitud_array as $s) {
                            if (isset($token[$s][0])) {
                                if ($token[$s][0] == 110 || $token[$s][0] == 210)
                                    $solicitud = true;
                            }
                        }
                    }


                    break;

                default:
                    //$idwf = "pacc1PDE";
                    $fecha_ctrl = $token['6534'];
                    $ip_ctrl = $token['6390'];
                    $empresa_ctrl = $token['6223'][0];
                    $status_ctrl = $token['6225'][0];




                    /* SOLICITUDES */
                    if ($status_ctrl == 90 || $status_ctrl == 60) {
                        $solicitud_array = array('6628', '6630', '6631', '6632', '6633', '6634', '6635', '9099', '9100');
                        foreach ($solicitud_array as $s) {
                            if (isset($token[$s][0])) {
                                if ($token[$s][0] == 110)
                                    $solicitud = true;
                            }
                        }
                    }


                    break;
            }

            //---excluyo flujos segun grupo
             if(in_array(150, $this->id_group)){
                 $query['idwf']=array('$nin'=>array(
                     'pacc1CG',
                     )
                     );
             }

            $query['data.Proyectos_pacc.query.id'] = $token['id'];
            //$query['idwf'] = $idwf;
            $query['status'] = 'open';
            $cases = $this->bpm->get_cases_byFilter($query,array(),array('idwf'=>true));
            // var_dump($cases);
            if (count($cases)) {
                foreach($cases as $case){
                //---recupero la empresa del bpm
                if (isset($case['data']['Empresas']['query']['id'])) {

                }

                //---recupero del container
                $empresa = $this->app->getall($empresa_ctrl, 'container.empresas', array('1693', '1695'));

                if ($empresa) {
                    $nombre = $empresa['1693'];
                    $cuit = $empresa['1695'];
                }

                $url_msg = (isset($case['id'])) ? $this->base_url . 'pacc/show_msgs/' . $case ['id'] : null;

                /* pacc/COORDINADOR (134) */
                if (isset($case['token_status'])) {
                    $keys = array_keys($case['token_status']);
                    // var_dump($keys);
                    $url_clone = (
                            (in_array(147, $this->id_group) or in_array(148, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'closed' and in_array('oryx_BEB71C63-D63E-4510-82F1-D04118F228B9', $keys) //---está finalizado pero por esta figura
                            and in_array($status_ctrl, array(60, 90))//---checkeo que esté en alguno de esos estados
                            ) ? $this->base_url . 'pacc11/clone_case/pacc1PDE/pacc1PDEF/' . $token ['case'] : null;
                    $url_cancelar_pde = (
                            (in_array(147, $this->id_group) or $this->user->isAdmin()) and in_array('oryx_3346C091-4A4D-4DCD-8DEC-B23C5FE7F80C', $keys) //---está finalizado pero por esta figura
                            and $case['status'] == 'closed'
                            ) ? $this->base_url . 'pacc/cancelar_pde/' . $token ['case'] : null;
                    $url_bpm = '';


                    if (in_array($grupo_pacc11_me, $this->id_group) or in_array($grupo_pacc13_me, $this->id_group) or $this->user->isAdmin()) {
                        $url_bpm = $this->base_url . 'bpm/engine/run/model/' . $case['idwf'] . '/' . $case ['id'];
                    }
                    //---link para cancelar solo para coordinador
                    if(isset($token ['case']))
                        $url_cancelar_pp = ((in_array(147, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'open') ? $this->base_url . 'pacc/cancelar_pp/' . $token ['case'] : null;
                    //---url para checkear
                }

                $case_id = $case['id'];
                
                


            $url = (isset($token ['id'])) ? '../dna2/frontcustom/284/print_pacc_preA.php?id=' . $token['id'] : '#';



            /* STATUS */
            $status = "N/A";
            if (isset($status_ctrl)) {
                $this->load->model('app');
                $option = $this->app->get_ops(648);
                $status = @$option[$status_ctrl];
            }

            /*  GET manual Tasks for the user */
            $mwf=array('$in'=>array('pacc1PDEF','pacc1SDE','pacc3PPF'));
            $mtasks=$this->bpm->get_tasks($this->idu,$case ['id'],$mwf,'Manual');
            $data['empresas'][]= array(
                '_id' => $token ['_id'],
                'case' => $case_id,
                'nombre' => $nombre,
                'cuit' => $cuit,
                'Nro' => (isset($ip_ctrl)) ? $ip_ctrl : 'N/A',
                'estado' => $status,
                'fechaent' => (isset($fecha_ctrl)) ? date('d/m/Y', strtotime($fecha_ctrl)) : '',
                'link_open' => $this->bpm->gateway($url),
                'link_msg' => $url_msg,
                'url_clone' => $url_clone,
                'url_bpm' => $url_bpm,
                'url_cancelar_pp' => $url_cancelar_pp,
                'url_cancelar_pde' => $url_cancelar_pde,
                'mtasks' =>$mtasks,
            );
                }
            } else {
                /* DATOS EMPRESA */
                $filter = array('container' => 'container.empresas', 'id' => $empresa_ctrl);
                $datos_empresa = $this->get_by_id($filter);
                $cuit = $datos_empresa[0][1695];
                $nombre = $datos_empresa[0][1693];
                $case_id = "-";
            }
        return true;    
        }, $rs);
        $data['base_url'] = $this->base_url;


        //===== @PAGINADO - Control
        $config = array(
            'url' => $this->module_url . __FUNCTION__,
            'current_page' => $page,
            'items_total' => $data['count'], // Total items in the table
            'items_x_page' => PAGINATION_ITEMS_X_PAGE,
            'pagination_width' => 10,
            'class_a' => "reload_widget",
            'pagination_always_visible' => false
        );
        $data['pagination'] = $this->pagination->index($config);
        //==


        $this->parser->parse($template, $data, false, true);
    }

    function fix_data($case = null) {
        $debug = true;
        $this->load->model('bpm/bpm');
        $resourceId = 'oryx_0962BF68-BBCD-470D-A307-C4453AFA4FBA';
        $filter = ($case) ? array('idwf' => 'pacc1PDE', 'id' => $case) : array('idwf' => 'pacc1PDE');
        $rs = $this->bpm->get_cases_byFilter($filter);
        foreach ($rs as $case) {
            if ($debug)
                var_dump($case['id']);
            $token = $this->bpm->consolidate_data('pacc1PDE', $case['id'], $resourceId);
            // var_dump($token);
        }
    }

    function dump() {

        $this->load->model('pacc/model_poa_list');

        $data = $this->model_poa_list->detalle_poa('2015-04-13-18-46-24-PACC-POA.xls');

        var_dump($data[0]);
    }

    function checkin($idwf, $idcase, $resourceId) {
        echo '<h1>checkin</h1>';
        var_dump($idwf, $idcase, $resourceId);
    }

    /**
     * Obtiene informacion de todos los container utilizando el id como indice
     *
     * @name get_by_id
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param array $filter {
     *     @var int  $id id
     *     @var string $container   nombre del container en la mongoDB database
     * }
     */
    function get_by_id($filter) {


        $rtn = array();
        $container = $filter['container']; //container.empresas';

        $float_id = (float) $filter['id'];
        $filter_id = ($float_id == 0) ? $filter['id'] : $float_id;


        $query = array('id' => $filter_id);

        $rs = $this->mongowrapper->db->$container->find($query);

        foreach ($rs as $list) {
            unset($list['_id']);
            $rtn[] = $list;
        }
        return $rtn;
    }
    //public $consolida_resrourceId='oryx_1E50C0F6-62D4-4E42-8ADE-58CFBD633DFC';
    function buscarAgen($type = null) {
        $this->user->authorize();
        $this->load->library('parser');
        $template = 'pacc/listar_agencias';
        $filter = array(
            'idwf' => 'INCUBAR',
            'resourceId' =>$this->consolida_resrourceId
        );
        $data ['querystring'] = $this->input->post('query');
        // -----busco en el cuit
        $filter ['$or'] [] = array(
            'data.6069' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        // -----busco en el nombre empresa
        $filter ['$or'] [] = array(
            'data.4896' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        // -----busco en el nro Inscripción
        $filter ['$or'] [] = array(
            'data.9985' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        $filter ['$or'] [] = array(
            'case' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        $tokens = $this->bpm->get_tokens_byFilter($filter, array(
            'case',
            'data',
            'checkdate'
                ), array(
            'checkdate' => false
        ));
        
        print_r($filter);
        
        var_dump(json_encode($filter),count($tokens));
        
        
        
        $data ['empresas'] = array_map(function ($token) {
            // var_dump($token['_id']);
            $case = $this->bpm->get_case($token ['case'], 'INCUBAR');
            $INCUBAR = $this->bpm->get_case($token ['case'], 'INCUBAR');
            $data = $this->bpm->load_case_data($case);

            $url = '';
            //(isset($data ['Agencias']['id'])) ? '../dna2/frontcustom/231/list_docs_crefis_eval.php?id=' . $data ['Agencias'] ['id'] : '#';
            $url_msg = '';
            //(isset($token ['case'])) ? $this->base_url . 'crefis/show_msgs/' . $token ['case'] : null;
            /* crefis/COORDINADOR (134) */
            $hist=$this->bpm->get_token_history('INCUBAR',$token['case']);
            foreach($hist as $t) $keys[$t['resourceId']]=$t['status'];
            $keys = array_keys($case['token_status']);
            $url_clone = ''; 
            if ((in_array(584, $this->id_group) or in_array(586, $this->id_group) or $this->user->isAdmin())
                    and $case['status'] == 'open' and in_array($data ['Agencias'] ['9498'][0], array('070', 70)) //---checkeo que esté en alguno de esos estados
                    ){ 
                $model = 'INCUBAR';
                $estado = $data ['Agencias'] ['4970'][0];
		$idResource = 'INCUBAR'; //de arranque del play????????????
/*
                switch($estado){
                    case 40:
                        break;
                    case 60:
                        $idResource = 'oryx_235D6339-A2ED-476B-A570-3233C86548EE';
                        break;
                    case 48:
                    case 59:                    
                        $idResource = 'oryx_FDD40364-9DB8-4090-B569-7D795E380F18';
                        break;
                }
*/

                $url_clone =''; // $this->base_url . 'bpm/engine/run/model/' . $model. '/' .$token['case'] . '/'.$idResource;
                
                } else{
			$url_clone = null;
                }
                
            /*$url_clone = (
                    (in_array(584, $this->id_group) or in_array(586, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'open' and in_array('oryx_05695DC8-1842-49D1-8327-1DAB8C164D35', $keys) //---está finalizado pero por esta figura
                    and in_array($data ['Agencias'] ['4970'][0], array(30, 40, 60)) //---checkeo que esté en alguno de esos estados
                    ) ? $this->base_url . 'bpm/engine/run/model/' . $model. '' .$token['case'] . '/oryx_69057B4E-A899-40F8-8A27-7D8C2A5100CE':null;*/
            //---link para cancelar solo para coordinador
            $url_cancelar_pp = '';//((in_array(134, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'open') ? $this->base_url . 'crefis/cancelar_pp/' . $token ['case'] : null;
            $url_cancelar_pde = ''; //((in_array(134, $this->id_group) or $this->user->isAdmin()) and in_array('oryx_3346C091-4A4D-4DCD-8DEC-B23C5FE7F80C', $keys) //---está finalizado pero por esta figura and $case['status'] == 'closed') ? $this->base_url . 'crefis/cancelar_pde/' . $token ['case'] : null;
            //---link para reevaluar solo para coordinador
            $url_reevaluar_pp = '';//((in_array(134, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'open') ? $this->base_url . 'crefis/reevaluar_pp/' . $token ['case'] : null;
            $url_reevaluar_pde = '';
            //((in_array(134, $this->id_group) or $this->user->isAdmin()) and in_array('oryx_3346C091-4A4D-4DCD-8DEC-B23C5FE7F80C', $keys) //---está finalizado pero por esta figura                  and $case['status'] == 'closed') ? $this->base_url . 'crefis/reevaluar_pde/' . $token ['case'] : null;
            //---url para checkear

            $url_bpm = '';
            if (in_array(145, $this->id_group) or in_array(1001, $this->id_group) or $this->user->isAdmin()) {
                $model = ($INCUBAR) ? 'INCUBAR' : 'INCUBAR';
                $url_bpm = $this->base_url . 'bpm/engine/run/model/' . $model . '/' . $token ['case'];
            }

            /* STATUS */
            $status = "N/A";
            if (isset($data ['Agencias'] ['9498'])) {
                $this->load->model('app');
                $option = $this->app->get_ops(849);
                $status = $option[$data ['Agencias'] ['9498'][0]];
            }


            return array(
                '_d' => $token ['_id'],
                'case' => $token ['case'],
                'nombre' => (isset($data['Agencias']['4896'])) ? $data['Agencias']['4896'] : 'XXXX',
                'cuit' => (isset($data['Agencias']['6069'])) ? $data['Agencias']['6069'] : 'Error//no está cargado',
                'Nro' => (isset($data ['Agencias']['9985'])) ? $data ['Agencias'] ['9985'] : 'N/A',
                'estado' => $status,
                'fechaent' => date('d/m/Y', strtotime($token ['checkdate'])),
                'link_open' => $this->bpm->gateway($url),
                'link_msg' => $url_msg,
                'url_clone' => $url_clone,
                'url_bpm' => $url_bpm,
                'url_cancelar_pp' => $url_cancelar_pp,
                'url_cancelar_pde' => $url_cancelar_pde,
                'url_reevaluar_pp' => $url_reevaluar_pp,
                'url_reevaluar_pde' => $url_reevaluar_pde,

            );
        }, $tokens);
        $data ['count'] = count($tokens);
        $data['base_url'] = $this->base_url;
        // var_dump($keys,$data);exit;


        $this->parser->parse($template, $data, false, true);
    }


}
