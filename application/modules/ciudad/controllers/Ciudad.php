<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ciudad
 *
 * Description of the class ciudad
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 *         @date Jul 18, 2014
 */
class ciudad extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('menu/menu_model');
        $this->load->model('bpm/bpm');
        $this->user->isloggedin();
        // ---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->config('ciudad/config');
        // ----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->library('pagination');

        /* GROUP */
        $user = $this->user->get_user($this->idu);

        $this->id_group = ($user->{'group'});
    }

    function Index() {
        $this->Add_group();
        $this->proyecto();
    }

    function Proyecto($debug=false) {
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'ciudad/json/ciudad_proyectos.json',$debug);
    }

    function Evaluador() {
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'ciudad/json/ciudad_evaluador.json');
    }

    function Admin() {
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'ciudad/json/ciudad_admin.json');
    }

    function Mesa_de_entradas() {
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'ciudad/json/ciudad_mesaentrada.json');
    }

    function tile_proyectos() {
        // ----portable indicators are stored as json files
        $kpi = json_decode($this->load->view("ciudad/kpi/ciudad_proyectos_presentados.json", '', true), true);
        echo Modules::run('bpm/kpi/tile_kpi', $kpi);
    }

    function tile_solicitud() {
        $data ['number'] = 'Solicitud';
        $data ['title'] = 'Crea una nueva solicitud';
        $data ['icon'] = 'ion-document-text';
        $data ['more_info_text'] = 'Comenzar';
        $data ['more_info_link'] = $this->base_url . 'bpm/engine/newcase/model/ciudadGral';
        echo Modules::run('dashboard/tile', 'dashboard/tiles/tile-green', $data);
    }

    function tile_aprobados_condicional() {
        $this->user->authorize();
        $this->load->model('ciudad/ciudad_model');
        $data ['number'] = count($this->ciudad_model->get_cases_byFilter_container('ciudadpde',195,array('8334'=>'87')));
        $data ['title'] = 'Aprobados Condicional';
        $data ['icon'] = 'ion-document-text';
        $data ['more_info_text'] = 'Listar';
        $data ['more_info_class'] = 'load_tiles_after';
        $data ['more_info_link'] = $this->base_url . 'ciudad/listar_aprobados_condicional';

        echo Modules::run('dashboard/tile', 'dashboard/tiles/tile-green', $data);
    }

    function tile_comite() {
        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->model('dna2/dna2old');
        $dna2url = $this->dna2old->get('url');
        // http://www.accionpyme.mecon.gob.ar/dna2/frontcustom/286/sol_ministro_2014.R.php
        $url = $dna2url . "frontcustom/286/sol_ministro_2014.R.php";
        $url = $this->bpm->gateway($url);
        $data ['number'] = 'Comité';
        $data ['title'] = 'Enviar a Comité';
        $data ['icon'] = 'ion-archive';
        $data ['more_info_text'] = 'Descargar';
        $data ['more_info_link'] = $url;
        echo Modules::run('dashboard/tile', 'dashboard/tiles/tile-green', $data);
    }

    function tile_buscar() {
        $this->user->authorize();
        $data = array();
        return $this->parser->parse('ciudad/buscar_proyecto', $data, true);
    }

    function get_token_history($resourceId, $history) {
        $rtnArr = array();
        foreach ($history as $token) {
            if ($token['resourceId'] == $resourceId)
                $rtnArr[] = $token;
        }
        return $rtnArr;
    }


    function listar_pp($action = null) {
        $this->user->authorize();
        $this->load->library('parser');
        $this->load->model('app');
        //----precargo las oc-ciones
        $provincias = $this->app->get_ops(39);
        $Ciudades = $this->app->get_ops(58);
        $estado = $this->app->get_ops(837);
        $template = 'ciudad/listar_proyectos_fechas_pp';
        $filter = array(
            'idwf' => 'ciudadGral',
//            'id' => 'VNSU'
        );
        $cases = $this->bpm->get_cases_byFilter($filter, array(), array('checkdate'));
//        var_dump($cases[0]);exit;
//        var_dump(json_encode($filter),count($tokens));
        $i = 0;
        foreach ($cases as $case) {
            $case_data = $this->bpm->load_case_data($case);
            //---me fijo que tenga 9409 y que sea igual a 3
            if(!isset($case_data['Ciudades']['9409'][0]))
                continue;
            if($case_data['Ciudades']['9409'][0]!='03')
                continue;
                
            /* STATUS */
            $i++;
            $arr = array(
                'case' => $case['id'],
                'Nro' => (isset($case_data ['Ciudades'] ['9410'])) ? $case_data ['Ciudades'] ['9410']: 'N/A',
                'provincia' => (isset($case_data['Ciudades']['9376'])) ? $provincias[$case_data['Ciudades']['9376'][0]] : '',
                'ciudad' => (isset($case_data['Ciudades']['9377'])) ? @$Ciudades[$case_data['Ciudades']['9377'][0]] : '',
                'estado' => (isset($case_data['Ciudades']['9409'])) ? @$estado[$case_data['Ciudades']['9409'][0]] : '',
                'nameInt' => (isset($case_data['Personas_9380']['0'])) ? $case_data['Personas_9380']['0'][1783].' '.$case_data['Personas_9380']['0'][1784]: '',
                'mailInt' => (isset($case_data['Personas_9380']['0'])) ? $case_data['Personas_9380']['0'][1786] : '',
		'telInt' => (isset($case_data['Personas_9380']['0'])) ? $case_data['Personas_9380']['0'][1785] : '',
		'celInt' => (isset($case_data['Personas_9380']['0'])) ? $case_data['Personas_9380']['0'][9383] : '',
                'nameRef' => (isset($case_data['Personas_9381']['0'])) ? $case_data['Personas_9381']['0'][1783].' '.$case_data['Personas_9381']['0'][1784]: '',
                'mailRef' => (isset($case_data['Personas_9381']['0'])) ? $case_data['Personas_9381']['0'][1786] : '',
		'telRef' => (isset($case_data['Personas_9380']['0'])) ? $case_data['Personas_9381']['0'][1785] : '',
		'celRef' => (isset($case_data['Personas_9380']['0'])) ? $case_data['Personas_9381']['0'][9383] : '',
            );
            $arr['user']=(array)$this->user->get_user_safe($case['iduser']);
            

//            $arr['fechapresentacion']=isset($case_data['Proyectos_ciudad']['8340'])?date('d/m/Y', strtotime($case_data['Proyectos_ciudad']['8340'])):'???';
            //---saco fecha presentación
            $token=$this->bpm->get_token($case['idwf'],$case['id'],'oryx_21878381-04CB-4744-8BA7-47A5758F32EC');
            $arr['fechapresentacion'] =  date('d/m/Y', strtotime($token['checkdate']));
            // //---fecha aprobacion / rechazo
            // //---busco solicitud rechazada
            // $rechazada = $this->get_token_history('oryx_FE3863C1-F7F4-40E1-95E7-FF407112C648', $case['history']);
            // if (count($rechazada)) {
            //     $arr['fechafinal'] = date('d/m/Y', strtotime($rechazada[0]['checkdate']));
            //     $arr['estado'] = 'Solicitud No Admisible';
            // }
            // //---busco proyecto rechazado
            // $proy_rechazado = $this->get_token_history('oryx_CE7D350E-FEA2-4BFF-B96D-77B29D249C7D', $case['history']);
            // if (count($proy_rechazado)) {
            //     $arr['fechafinal'] = date('d/m/Y', strtotime($proy_rechazado[0]['checkdate']));
            //     $arr['estado'] = 'Proyecto Rechazado';
            // }
            // //---busco proyecto pre-aprobado
            // $proy_pre = $this->get_token_history('oryx_2882861D-9261-4874-8FA9-12BA72AC43C0', $case['history']);
            // if (count($proy_pre)) {
            //     $arr['fechafinal'] = date('d/m/Y', strtotime($proy_pre[0]['checkdate']));
            //     $arr['estado'] = 'Proyecto Preaprobado';
            // }
            // //---busco proyecto baja
            // $proy_baja = $this->get_token_history('oryx_16600243-7CAC-4EAB-8AF8-0A135CE14FEE', $case['history']);
            // if (count($proy_baja)) {
            //     $arr['fechafinal'] = date('d/m/Y', strtotime($proy_baja[0]['checkdate']));
            //     $arr['estado'] = 'Baja Solicitud Coordinación';
            // }
            $data['proyectos'][] = $arr;
            
        }

        $data ['count'] = $i;
        $data['base_url'] = $this->base_url;
        // var_dump($data);
        // exit;
        if ($action == 'xls') {
            header("Content-Description: File Transfer");
            header("Content-Type: application/x-msexcel;");
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=listado_pp.xls");
            header("Content-Description: PHP Generated XLS Data");
            echo utf8_decode($this->parser->parse('ciudad/listar_proyectos_fechas_pp.xls.php', $data, true, true));
            
        } else {
            $this->parser->parse($template, $data, false, true);
        }
    }

    function listar_aprobados_condicional($action = null) {
        $this->user->authorize();
        $this->load->library('parser');
        $this->load->model('app');
        $this->load->model('ciudad/ciudad_model');
        $option = $this->app->get_ops(772);
        $template = 'ciudad/listar_proyectos_fechas_pde';
        $template = 'ciudad/listar_proyectos';
        $filter = array(
            'idwf' => 'ciudadpde',
        );
        $cases = $this->ciudad_model->get_cases_byFilter_container('ciudadpde',195,array('8334'=>'87'));
//        var_dump($cases[0]);exit;

        $i = 0;
        foreach ($cases as $case) {

            $case_data = $this->bpm->load_case_data($case);
            // var_dump($case_data);exit;
            if (!isset($case_data ['Proyectos_ciudad'] ['8339']))
                continue;
            /* STATUS */
            $status = "N/A";
            if (isset($case_data ['Proyectos_ciudad'] ['8334'])) {
                $status = $option[$case_data ['Proyectos_ciudad'] ['8334'][0]];
            }
            $i++;
            $arr = array(
                'case' => $case['id'],
                'nombre' => (isset($case_data['Ciudades']['1693'])) ? $case_data['Ciudades']['1693'] : '',
                'cuit' => (isset($case_data['Ciudades']['1695'])) ? $case_data['Ciudades']['1695'] : '',
                'Nro' => (isset($case_data ['Proyectos_ciudad'] ['8339'])) ? $case_data ['Proyectos_ciudad'] ['8339'] : 'N/A',
                'estado' => $status,
                'url_reevaluar_pde'=>0
            );

            $url = (isset($case_data ['Proyectos_ciudad']['id'])) ? $this->bpm->gateway('../dna2/frontcustom/284/list_docs_ciudad_eval.php?id=' . $case_data ['Proyectos_ciudad'] ['id']) : '#';
            $arr['link_msg'] ='';
            $arr['link_open'] =$url;

            $arr['url_bpm'] =$this->module_url.'ciudad/escalar_condicional/ciudadpde/'.$case['id'];
            $arr['url_clone'] ='';
            $arr['url_cancelar_pp'] ='';
            $arr['url_cancelar_pde'] ='';
            $data['Ciudades'][] = $arr;
        }

        $this->parser->parse($template, $data, false, true);
    }
    function listar_pde($action = null) {
        $this->user->authorize();
        $this->load->library('parser');
        $this->load->model('app');
                //----precargo las oc-ciones
        $provincias = $this->app->get_ops(39);
        $Ciudades = $this->app->get_ops(58);
        $estado = $this->app->get_ops(837);
        $template = 'ciudad/listar_proyectos_fechas_pp';
        
        $filter = array(
            'idwf' => 'ciudadGral',
        );
        
        $cases = $this->bpm->get_cases_byFilter($filter, array(), array('checkdate'));
//        var_dump($cases[0]);exit;
//        var_dump(json_encode($filter),count($tokens));

        $i = 0;
        foreach ($cases as $case) {

            $case_data = $this->bpm->load_case_data($case);
            //---me fijo que tenga 9409 y que sea igual a 3
            if(!isset($case_data['Ciudades']['9409'][0]))
                continue;
            if($case_data['Ciudades']['9409'][0]!='05' || $case_data['Ciudades']['9409'][0]!='15' )
                continue;
            
            /* STATUS */
            $i++;
            $arr = array(
                'case' => $case['id'],
                'Nro' => (isset($case_data ['Ciudades'] ['9410'])) ? $case_data ['Ciudades'] ['9410']: 'N/A',
                'provincia' => (isset($case_data['Ciudades']['9376'])) ? $provincias[$case_data['Ciudades']['9376'][0]] : '',
                'ciudad' => (isset($case_data['Ciudades']['9377'])) ? @$Ciudades[$case_data['Ciudades']['9377'][0]] : '',
                'estado' => (isset($case_data['Ciudades']['9409'])) ? @$estado[$case_data['Ciudades']['9409'][0]] : '',
                
            );

            $arr['user']=(array)$this->user->get_user_safe($case['iduser']);

            $tokenPres=$this->bpm->get_token($case['idwf'],$case['id'],'oryx_90DC0E41-EAA6-405D-9C87-114D825A2D5C');
            $tokenReing=$this->bpm->get_token($case['idwf'],$case['id'],'oryx_644BCE88-7352-4B89-9F72-55606A1B4803');
            
            $arr['fechapresentacion'] = ($case_data['Ciudades']['9409'][0]!=='05') ? date('d/m/Y', strtotime($tokenPres['checkdate'])) : date('d/m/Y', strtotime($tokenReing['checkdate']));
//            $arr['fechapresentacion']=isset($case_data['Proyectos_ciudad']['8340'])?date('d/m/Y', strtotime($case_data['Proyectos_ciudad']['8340'])):'???';
            //---saco fecha presentación PP
            /*$f0 = $this->bpm->get_token('ciudadGral', $case['id'], 'oryx_4EFED47A-7AAD-4728-9577-49F8901AC5B9');
	    $arr['fechapresentacionPP'] = (count($f0)) ? date('d/m/Y', strtotime($f0['checkdate'])) : '';
            //---saco fecha presentación
            $f1 = $this->bpm->get_token('ciudadpde', $case['id'], 'oryx_A58D5ECD-6899-4F60-856C-CFE89B36FB91');
            $arr['fechapresentacion'] = (count($f1)) ? date('d/m/Y', strtotime($f1['checkdate'])) : '';
            //-----tomo el evaluador
            $iduser = $case_data ['Proyectos_ciudad'] ['8668'][0];
            $evaluador = $this->user->get_user_safe($iduser);
            $arr['evaluador'] = $evaluador->name . ' ' . $evaluador->lastname;
            //---Tomo primera y ultima fecha eval
            $f1 = $this->get_token_history('oryx_9246751E-B435-4359-988B-8E1B84932A50', $case['history']);*/
            /*if (count($f1)) {
                $arr['fechaprimereval'] = (count($f1)) ? date('d/m/Y', strtotime($f1[0]['checkdate'])) : '';
                $arr['fechaultimoeval'] = (count($f1)) ? date('d/m/Y', strtotime($f1[count($f1) - 1]['checkdate'])) : '';
            } else {
                //tomo del token
                $f1 = $this->bpm->get_token('ciudadpde', $case['id'], 'oryx_A58D5ECD-6899-4F60-856C-CFE89B36FB91');
                $arr['fechaprimereval'] = (count($f1)) ? date('d/m/Y', strtotime($f1['checkdate'])) : '';
                $arr['fechaultimoeval'] = (count($f1)) ? date('d/m/Y', strtotime($case['checkdate'].' +'.$f1['interval']['days'].' days')) : '';
                $arr['case'].='*';
            }*/

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

    function buscar($type = null) {
        $this->user->authorize();
        $this->load->library('parser');
        $template = 'ciudad/listar_proyectos_fechas_pp';
        $filter = array(
            'idwf' => 'ciudadGral',
            'resourceId' => 'oryx_2E4A8FFC-FB58-480C-A21C-43D6B525F9F4'
        );
        $data ['querystring'] = $this->input->post('query');
        // -----busco en la provincia
        $filter ['$or'] [] = array(
            'data.provincia' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        // -----busco en el nombre ciudad
        $filter ['$or'] [] = array(
            'data.ciudad' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        // -----busco en el nro proyecto
        $filter ['$or'] [] = array(
            'data.9410' => array(
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
//        var_dump(json_encode($filter),count($tokens));
        $data ['Ciudades'] = array_map(function ($token) {
            // var_dump($token['_id']);
            $case = $this->bpm->get_case($token ['case'], 'ciudadGral');
            //$ciudadpde=$this->bpm->get_case($token ['case'], 'ciudadpde');
            $data = $this->bpm->load_case_data($case);


            $url = (isset($data ['Proyectos_ciudad']['id'])) ? '../dna2/frontcustom/284/list_docs_ciudad_eval.php?id=' . $data ['Proyectos_ciudad'] ['id'] : '#';
            $url_msg = (isset($token ['case'])) ? $this->base_url . 'ciudad/show_msgs/' . $token ['case'] : null;
            /* ciudad/COORDINADOR (134) */
            $hist=$this->bpm->get_token_history('ciudadGral',$token['case']);
            foreach($hist as $t) $keys[$t['resourceId']]=$t['status'];
            $keys = array_keys($case['token_status']);
            $url_clone = (
                    (in_array(134, $this->id_group) or in_array(138, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'closed' and in_array('oryx_3346C091-4A4D-4DCD-8DEC-B23C5FE7F80C', $keys) //---está finalizado pero por esta figura
                    and in_array($data ['Proyectos_ciudad'] ['8334'][0], array(60, 65, 68, 87)) //---checkeo que esté en alguno de esos estados
                    ) ? $this->base_url . 'ciudad/clone_case/ciudadGral/ciudadpde/' . $token ['case'] : null;
            //---link para cancelar solo para coordinador
            $url_cancelar_pp = ((in_array(134, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'open') ? $this->base_url . 'ciudad/cancelar_pp/' . $token ['case'] : null;
            $url_cancelar_pde = (
                    (in_array(134, $this->id_group) or $this->user->isAdmin()) and in_array('oryx_3346C091-4A4D-4DCD-8DEC-B23C5FE7F80C', $keys) //---está finalizado pero por esta figura
                    and $case['status'] == 'closed'
                    ) ? $this->base_url . 'ciudad/cancelar_pde/' . $token ['case'] : null;
            //---link para reevaluar solo para coordinador
            $url_reevaluar_pp = ((in_array(134, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'open') ? $this->base_url . 'ciudad/reevaluar_pp/' . $token ['case'] : null;
            $url_reevaluar_pde = (
                    (in_array(134, $this->id_group) or $this->user->isAdmin()) and in_array('oryx_3346C091-4A4D-4DCD-8DEC-B23C5FE7F80C', $keys) //---está finalizado pero por esta figura
                    and $case['status'] == 'closed'
                    ) ? $this->base_url . 'ciudad/reevaluar_pde/' . $token ['case'] : null;
            //---url para checkear

            $url_bpm = '';
            if(in_array(134, $this->id_group) or in_array(135, $this->id_group) or $this->user->isAdmin()){
            $model='ciudadGral';
            $url_bpm=  $this->base_url . 'bpm/engine/run/model/' . $model . '/' . $token ['case'];

            }

            /* STATUS */
            $status = "N/A";
            if (isset($data ['Ciudades'] ['9409'])) {
                $this->load->model('app');
                $option = $this->app->get_ops(837);
                $status = $option[$data ['Ciudades'] ['9409'][0]];
            }


            return array(
                '_d' => $token ['_id'],
                'case' => $token ['case'],
                'provincia' => (isset($case_data['Ciudades']['9376'])) ? $provincias[$case_data['Ciudades']['9376'][0]] : '',
                'ciudad' => (isset($case_data['Ciudades']['9377'])) ? @$Ciudades[$case_data['Ciudades']['9377'][0]] : '',
                'Nro' => (isset($case_data ['Ciudades'] ['9410'])) ? $case_data ['Ciudades'] ['9410']: 'N/A',
                'estado' => $status,
                'nameInt' => (isset($case_data['Personas_9380']['0'])) ? $case_data['Personas_9380']['0'][1783].' '.$case_data['Personas_9380']['0'][1784]: '',
                'mailInt' => (isset($case_data['Personas_9380']['0'])) ? $case_data['Personas_9380']['0'][1786] : '',
                'nameRef' => (isset($case_data['Personas_9381']['0'])) ? $case_data['Personas_9381']['0'][1783].' '.$case_data['Personas_9381']['0'][1784]: '',
                'mailRef' => (isset($case_data['Personas_9381']['0'])) ? $case_data['Personas_9381']['0'][1786] : '',
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

    function escalar_condicional($idwf,$idcase) {
        $resourceId = 'oryx_10CF34E7-0331-40C0-AE7C-0ABCCE9D015E';
        //---Cargo wf
        $mywf = $this->bpm->load($idwf);
        $wf = $this->bpm->bindArrayToObject($mywf ['data']);
        $shape = $this->bpm->get_shape($resourceId, $wf);
        $wf->idwf = $idwf;
        $wf->case = $idcase;
        /**
         * Cancelo todos los tokens primero
         */
        $active_tokens = $this->bpm->get_pending($wf->idwf, $wf->case, array('user', 'waiting', 'pending'), array());
        foreach($active_tokens as $token){
            $token['status']='canceled';
            $this->bpm->save_token($token);

            }

        $token = $this->bpm->get_token($idwf, $idcase, $resourceId);
        //---creo un token falso
        $token = $this->bpm->token_checkin($token, $wf, $shape);
        $token['status'] = 'pending';
        $this->bpm->save_token($token);
        //---run_post($model, $idwf, $case, $resourceId)
        $url = $this->base_url . "bpm/engine/run_post/model/$idwf/$idcase/$resourceId";
//        echo "<a href='$url'>click aquí</a>";
        redirect($url);
    }

    function cancelar_pp($idcase) {
        $idwf = 'ciudadGral';
        $resourceId = 'oryx_11C3ABEB-C93F-4536-BCD1-B0D006DA5D12';
        //---Cargo wf
        $mywf = $this->bpm->load($idwf);
        $wf = $this->bpm->bindArrayToObject($mywf ['data']);
        $shape = $this->bpm->get_shape($resourceId, $wf);
        $wf->idwf = $idwf;
        $wf->case = $idcase;
        /**
         * Cancelo todos los tokens primero
         */
        $active_tokens = $this->bpm->get_pending($wf->idwf, $wf->case, array('user', 'waiting', 'pending'), array());
        foreach($active_tokens as $token){
            $token['status']='canceled';
            $this->bpm->save_token($token);

            }

        $token = $this->bpm->get_token($idwf, $idcase, $resourceId);
        //---creo un token falso
        $token = $this->bpm->token_checkin($token, $wf, $shape);
        $token['status'] = 'pending';
        $this->bpm->save_token($token);
        //---run_post($model, $idwf, $case, $resourceId)
        $url = $this->base_url . "bpm/engine/run_post/model/$idwf/$idcase/$resourceId";
//        echo "<a href='$url'>click aquí</a>";
        redirect($url);
    }

    function cancelar_pde($idcase) {
        $idwf = 'ciudadpde';
        $resourceId = 'oryx_928C03EE-D8FE-4693-A3AD-3F082FE84485';
        //---Cargo wf
        $mywf = $this->bpm->load($idwf);
        $wf = $this->bpm->bindArrayToObject($mywf ['data']);
        $shape = $this->bpm->get_shape($resourceId, $wf);
        $wf->idwf = $idwf;
        $wf->case = $idcase;
        /**
         * Cancelo todos los tokens primero
         */
        $active_tokens = $this->bpm->get_pending($wf->idwf, $wf->case, array('user', 'waiting', 'pending'), array());
        foreach($active_tokens as $token){
            $token['status']='canceled';
            $this->bpm->save_token($token);

            }

        $token = $this->bpm->get_token($idwf, $idcase, $resourceId);
        //---creo un token falso
        $token = $this->bpm->token_checkin($token, $wf, $shape);
        $token['status'] = 'pending';
        $this->bpm->save_token($token);
        //---run_post($model, $idwf, $case, $resourceId)
        $url = $this->base_url . "bpm/engine/run_post/model/$idwf/$idcase/$resourceId";
//        echo "<a href='$url'>click aquí</a>";
        redirect($url);
    }

    function reevaluar_pp($idcase) {
        $idwf = 'ciudadGral';
        $resourceId = 'oryx_D4FB583E-D562-4036-9E94-BA8D69F3D54F';
        //---Cargo wf
        $mywf = $this->bpm->load($idwf);
        $wf = $this->bpm->bindArrayToObject($mywf ['data']);
        $shape = $this->bpm->get_shape($resourceId, $wf);
        $wf->idwf = $idwf;
        $wf->case = $idcase;
        /**
         * Cancelo todos los tokens primero
         */
        $active_tokens = $this->bpm->get_pending($wf->idwf, $wf->case, array('user', 'waiting', 'pending'), array());
        foreach($active_tokens as $token){
            $token['status']='canceled';
            $this->bpm->save_token($token);

            }

        $token = $this->bpm->get_token($idwf, $idcase, $resourceId);
        //---creo un token falso
        $token = $this->bpm->token_checkin($token, $wf, $shape);
        $token['status'] = 'pending';
        $this->bpm->save_token($token);
        //---run_post($model, $idwf, $case, $resourceId)
        $url = $this->base_url . "bpm/engine/run_post/model/$idwf/$idcase/$resourceId";
//        echo "<a href='$url'>click aquí</a>";
        redirect($url);
    }

    function reevaluar_pde($idcase) {
        $idwf = 'ciudadpde';
        $resourceId = 'oryx_1BE4C8D8-E8A5-4D48-B59E-407E7B8E3F7A';
        //---Cargo wf
        $mywf = $this->bpm->load($idwf);
        $wf = $this->bpm->bindArrayToObject($mywf ['data']);
        $shape = $this->bpm->get_shape($resourceId, $wf);
        $wf->idwf = $idwf;
        $wf->case = $idcase;
        /**
         * Cancelo todos los tokens primero
         */
        $active_tokens = $this->bpm->get_pending($wf->idwf, $wf->case, array('user', 'waiting', 'pending'), array());
        foreach($active_tokens as $token){
            $token['status']='canceled';
            $this->bpm->save_token($token);

            }

        $token = $this->bpm->get_token($idwf, $idcase, $resourceId);
        //---creo un token falso
        $token = $this->bpm->token_checkin($token, $wf, $shape);
        $token['status'] = 'pending';
        $this->bpm->save_token($token);
        //---run_post($model, $idwf, $case, $resourceId)
        $url = $this->base_url . "bpm/engine/run_post/model/$idwf/$idcase/$resourceId";
//        echo "<a href='$url'>click aquí</a>";
        redirect($url);
    }

    function mini_status_resultado($idwf, $resourceId, $status) {

        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->library('parser');
        $template = 'ciudad/listar_proyectos';
        $filter = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId,
            'status' => $status,
        );

        $tokens = $this->bpm->get_tokens_byFilter($filter, array(
            'case',
            'data',
            'checkdate'
                ), array(
            'checkdate' => false
        ));
//        var_dump(json_encode($filter),count($tokens));


        $data ['Ciudades'] = array_map(function ($token) {
            // var_dump($token['_id']);
            $case = $this->bpm->get_case($token ['case']);
            $data = $this->bpm->load_case_data($case);

            $url = (isset($data ['Proyectos_ciudad']['id'])) ? '../dna2/RenderView/printvista.php?idvista=3597&idap=286&id=' . $data ['Proyectos_ciudad'] ['id'] : '#';
            $url_msg = (isset($token ['case'])) ? 'show_msgs/' . $token ['case'] : null;

            /* ciudad/COORDINADOR (134) */
            $url_bpm_check = (in_array(134, $this->id_group)) ? '/bpm/engine/run/model/ciudadGral/' . $token ['case'] : null;

            $url_bpm = 0;
            if (isset($url_bpm_check))
                $url_bpm = $this->bpm->gateway($url_bpm_check);

            /* STATUS */
            $status = "N/A";
            if (isset($data ['Proyectos_ciudad'] ['8334'])) {
                $this->load->model('app');
                $option = $this->app->get_ops(772);
                $status = $option[$data ['Proyectos_ciudad'] ['8334'][0]];
            }

            return array(
                '_d' => $token ['_id'],
                'case' => $token ['case'],
                'nombre' => (isset($data['Ciudades']['1693'])) ? $data['Ciudades']['1693'] : '',
                'cuit' => (isset($data['Ciudades']['1695'])) ? $data['Ciudades']['1695'] : '',
                'Nro' => (isset($data ['Proyectos_ciudad'] ['8339'])) ? $data ['Proyectos_ciudad'] ['8339'] : 'N/A',
                'estado' => $status,
                'url_clone' => null,
                'fechaent' =>($token ['checkdate']) ? date('d/m/Y', strtotime($token ['checkdate'])):'???',
                'link_open' => $this->bpm->gateway($url),
                'link_msg' => $url_msg,
                'url_bpm' => $url_bpm //---url de procesar tarea
            );
        }, $tokens);


        $data ['count'] = count($tokens);
        //---saco título para el resultado
        $mywf = $this->bpm->load($idwf);
        $wf = $this->bpm->bindArrayToObject($mywf ['data']);
        //---tomo el template de la tarea
        $shape = $this->bpm->get_shape($resourceId, $wf);
        $add = ($status == 'user') ? 'En curso' : 'Finalizado';
        $data['querystring'] = $shape->properties->name . ' / ' . $add;



        $this->parser->parse($template, $data, false, true);
    }

    function setup() {
        $this->user->authorize();
        echo Modules::run('bpm/kpi/import_kpi', 'ciudad');
    }
    function eliminar_en_preparacion($process=false){
        $this->user->authorize();
        $filter=array(
            'resourceId'=>'oryx_B5BD09EE-57CF-41BC-A5D5-FAA1410804A5',
            'status'=>'user',
            'idwf'=>'ciudadGral',
            );

        $tokens=$this->bpm->get_tokens_byFilter($filter,array('case'));
        foreach ($tokens as $token){
            echo "Processing:".$token['case'].'<hr/>';
            if($process)
                $this->bpm->delete_case('ciudadGral', $token['case']);
        }
    }
    function ministatus_pp() {
        $this->user->authorize();
        $state = Modules::run('bpm/manager/mini_status', 'ciudadGral', 'array');

        $state = array_filter($state, function ($task) {
            return $task ['type'] == 'Task';
        });
        // ---las aplano un poco
        foreach ($state as $task) {
            $task ['user'] = (isset($task ['status'] ['user'])) ? $task ['status'] ['user'] : 0;
            $task ['finished'] = (isset($task ['status'] ['finished'])) ? $task ['status'] ['finished'] : 0;
            $wfData ['mini'] [] = $task;
        }

        //var_dump($wfData);
        $wfData ['base_url'] = base_url();
        $wf = $this->bpm->load('ciudadGral');
        $wfData += $wf ['data'] ['properties'];
        $wfData ['name'] = 'Mini Status: ' . $wfData ['name'];
        return $this->parser->parse('ciudad/ministatus_pp', $wfData, true, true);
    }

    /**
     * PROYECTS AMOUNT
     *
     * Description Calculate the amount  of money  in projects grouped by status
     * name proyects_amount
     * @author Diego Otero
     */
    function proyects_amount() {
        $this->user->authorize();
        $state = $this->status_amounts();

        foreach ($state as $key => $task) {

            $new_task = array();
            $new_task['status'] = $key;
            $new_task['how_many'] = count($task);
            $new_task['amount'] = "$" . @number_format(array_sum($task), 2, ",", ".");
            $wfData['mini'][] = $new_task;
        }

        // var_dump($wfData);exit;

        $wfData ['base_url'] = base_url();
        $wf = $this->bpm->load('ciudadGral');
        $wfData += $wf ['data'] ['properties'];
        $wfData ['name'] = 'Montos por Estados';

        return $this->parser->parse('ciudad/montos_estados', $wfData, true, true);
    }

    /**
     * STATUS_AMOUNTS
     *
     * Description Calculate the amount  of money  in projects grouped by status
     * name status_amounts
     * @author Diego Otero
     */
    function status_amounts() {
        $filter['idwf'] = 'ciudadGral';
        $querys = $this->get_amount_stats($filter);

        /* OPTIONS */
        $this->load->model('app');
        $option = $this->app->get_ops(772);

        foreach ($querys as $values) {

            $ctrl_value = (isset($values[0][8334][0])) ? $values[0][8334][0] : $values[0][8334];
            $value8326 = (isset($values[0][8326])) ? str_replace(",", ".", str_replace(".", "", $values[0][8326])) : 0;
            $value8573 = (isset($values[0][8573])) ? str_replace(",", ".", str_replace(".", "", $values[0][8573])) : 0;


            $amount = ($ctrl_value >= 30) ? $value8573 : $value8326;

            foreach ($option as $opt => $desc) {
                if ($opt == $ctrl_value)
                    $cases_arr[$desc][] = (float) $amount;
            }
        }

        return $cases_arr;
    }

    function get_amount_stats($filter) {
        $this->load->model('ciudad_model');
        /* get ids */
        $all_ids = array();
        $arr_status = array();


        $allcases = $this->bpm->get_cases_byFilter($filter, array('id', 'idwf', 'data'));

        foreach ($allcases as $case) {
            if (isset($case['data']['Proyectos_ciudad']['query']))
                $all_ids[] = $case['data']['Proyectos_ciudad']['query'];
        }


        $get_value = array_map(function ($all_ids) {
            return $this->ciudad_model->get_amount_stats_by_id($all_ids);
        }, $all_ids);



        return $get_value;
    }

    /**
     * PROYECTS EVALUATOR
     *
     * Description
     * name projects_evaluator
     * @author Diego Otero
     */
    function projects_evaluator() {
        $this->user->authorize();
        $state = $this->evaluator_projects();


        foreach ($state as $key => $task) {
            $new_task = array();
            $project = null;
            foreach ($task as $each) {

                $user = (array) $this->user->get_user_safe($key);
                //$evaluator_info = strtoupper($user['nick']) . " | " . $user['name'] . " " . $user['lastname'];
                $evaluator_info = $user['name'] . " " . $user['lastname'];
                $how_many = count($task);

                $url = '../dna2/RenderView/printvista.php?idvista=3597&idap=286&id=' . $each['project_id'];

                $projData['url'] = $this->bpm->gateway($url);
                $projData['project_value'] = $each['project_ip'];
                $projData['status'] = $each['status'];
                $projData['filing_date'] = $each['filing_date'];
                $projData['cuit'] = $each['cuit'];
                $projData['business_name'] = $each['business_name'];


                $project .= $this->parser->parse('ciudad/proyectos_evaluador_anchor', $projData, true, true);
            }


            $new_task['evaluator'] = $evaluator_info;
            $new_task['toggle_id'] = md5($evaluator_info);
            $new_task['how_many'] = $how_many;
            $new_task['project'] = $project;
            $wfData['mini'][] = $new_task;
        }

        $wfData ['base_url'] = base_url();
        $wf = $this->bpm->load('ciudadGral');
        $wfData += $wf ['data'] ['properties'];
        $wfData ['name'] = 'Evaluadores por proyecto';

        return $this->parser->parse('ciudad/proyectos_evaluador', $wfData, true, true);
    }

    /**
     * EVALUATOR PROJECTS
     *
     * Description
     * name evaluator_projects
     * @author Diego Otero
     */
    function evaluator_projects() {
        $this->load->model('ciudad_model');

        $output = 'array';
        $filter = array();

        $filter['idwf'] = 'ciudadGral';
        $querys = $this->ciudad_model->get_evaluator_by_project($filter);
        //var_dump($querys);exit;

        /* OPTIONS */
        $this->load->model('app');
        $option = $this->app->get_ops(772);


        foreach ($querys[0] as $values) {

            $ctrl_value = (isset($values[8334][0])) ? $values[8334][0] : $values[8334];


            $evaluator_id = $values[8668][0];

            list($filing_year, $filing_month, $filing_day) = explode("/", $values[8340]);
            $filing_date = $filing_day . "/" . $filing_month . "/" . $filing_year;

            $company_id = floatval($values[8325][0]);
            $company = $this->ciudad_model->get_company_by_project_by_id($company_id);

            $proyect_array = array(
                "project_ip" => $values[8339]
                , "project_id" => $values['id']
                , "status" => $option[$ctrl_value]
                , "filing_date" => $filing_date
                , "cuit" => $company[0][1695], "business_name" => $company[0][1693]
            );
            $cases_arr[$evaluator_id][] = $proyect_array;
        }

        return $cases_arr;
    }

    function ver_ficha($idwf, $idcase, $token, $id = null) {

        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->model('dna2/dna2old');
        $dna2url = $this->dna2old->get('url');
        if ($id) {
            $url = $dna2url . "RenderEdit/editnew.php?idvista=3560&origen=V&idap=286&id=$id&idwf=$idwf&case=$idcase&token=$token";
        } else {
            $url = $dna2url . "RenderEdit/editnew.php?idvista=3560&origen=V&idap=286&idwf=$idwf&case=$idcase&token=$token";
        }

        $url = $this->bpm->gateway($url);
        redirect($url);
    }

    function imprimir_proyecto($idwf, $idcase, $token, $id = null) {

        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->model('dna2/dna2old');
        $dna2url = $this->dna2old->get('url');
//         if ($id) {
//             $url = $dna2url . "frontcustom/284/proyecto_ciudad_preA_new.php?id=$id&idwf=$idwf&case=$idcase&token=$token";
//         } else {
//             show_error('El Caso no tiene id de proyecto');
//         }
//         $url = $this->bpm->gateway($url);
//         redirect($url);
        if ($id) {
            $todo = $id . '&idwf=' . $idwf . '&case=' . $idcase . '&token=' . $token;
            echo <<<BLOCK
                <p align='left'>1. <a href="{$dna2url}frontcustom/284/nota_ciudad_preapro.php?id=$id" target="_blank">Nota de Presentac&oacute;n</a></p>
                <p align='left'>2. <a href="{$dna2url}frontcustom/284/print_ciudad_preA.php?id=$todo" target="_blank">Imprimible del Proyecto</a></p>
                <p align='left'>3. <a href="{$dna2url}frontcustom/284/ddjj_docu_ciudad_preA.php?id=$id" target="_blank">Listado de Documentaci&oacute;n a Presentar</a></p>
BLOCK;
        } else {
            echo 'div class="alert alert-success" role="alert">El Caso no tiene id de proyecto</div>';
        }
    }

    function fix_data($case = null) {
        $debug = false;
        $this->load->model('bpm/bpm');
        $resourceId = 'oryx_508C9A17-620B-4A6F-8508-D3D14DAB6DA2';
        $filter = ($case) ? array('idwf' => 'ciudadGral', 'id' => $case) : array('idwf' => 'ciudadGral');
        $rs = $this->bpm->get_cases_byFilter($filter);
        foreach ($rs as $case) {
            if ($debug)
                var_dump($case['id']);
            $token = $this->bpm->consolidate_data('ciudadGral', $case['id'], $resourceId);
        }
    }

    function Landing() {
        $this->Add_group();
        redirect($this->module_url);
    }
    /**
     * Agrega el grupo ciudad a los que entran al panel
     */
    function Add_group() {
        $user =$this->user->get_user($this->idu);
        if (!$this->user->isAdmin($user)) {
            $user=$user;
            $group_add = $this->group->get_byname('Ciudades/CIUDAD');
            array_push($user->group, (int) $group_add ['idgroup']);
            $user->group = array_unique($user->group);
            $this->user->save($user);
        }
    }

    function asignar_evaluador($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador del caso
        $evaluador = $renderData['Proyectos_ciudad']['8668'][0];
        //----token que hay que finalizar
        $src_resourceId = 'oryx_1C122FC6-1C7F-425A-A0A2-E9EA1892177E';
        // ---Token de pp asignado
        $lane_resourceId = 'oryx_295810F2-8C34-4D03-80F8-7B5C371381B8';

        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }

    function asignar_evaluador_pde($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador del caso
        $evaluador = $renderData['Proyectos_ciudad']['8668'][0];
        //----token que hay que finalizar
        $src_resourceId = 'oryx_336D35BD-229C-47FA-9012-3670DDB73937';
        // ---Token de pp asignado
        $lane_resourceId = 'oryx_B59407D5-0805-46F0-871F-7C8634B133E1';

        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }

    function info($tipo, $idcase) {
        $idwf = 'ciudadGral';
        $this->load->model('bpm/bpm');
        $this->load->library('parser');
        $this->load->library('bpm/ui');
        $renderData = array();
        $renderData ['base_url'] = $this->base_url;
        // ---prepare UI
        $renderData ['js'] = array(
            $this->base_url . 'bpm/assets/jscript/modal_window.js' => 'Modal Window Generic JS'
        );
        // ---prepare globals 4 js
        $renderData ['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->base_url . 'bpm'
        );
//        $this->bpm->debug['load_case_data'] = true;
        $user = $this->user->getuser((int) $this->session->userdata('iduser'));
        $case = $this->bpm->get_case($idcase, $idwf);
        $this->user->Initiator = $case['iduser'];
        //---saco título para el resultado
        $mywf = $this->bpm->load($idwf);
        $wf = $this->bpm->bindArrayToObject($mywf ['data']);
        //---tomo el template de la tarea
        //$shape = $this->bpm->get_shape($resourceId, $wf);

        $data = $this->bpm->load_case_data($case, $idwf);
        $data['user'] = (array) $user;

        //$resources = $this->bpm->get_resources($shape, $wf, $case);
        //---if has no messageref and noone is assigned then
        //---fire a message to lane or self
//            if (!count($resources['assign']) and !$shape->properties->messageref) {
//                $lane = $this->bpm->find_parent($shape, 'Lane', $wf);
//                //---try to get resources from lane
//                if ($lane) {
//                    $resources = $this->bpm->get_resources($lane, $wf);
//                }
//                //---if can't get resources from lane then assign it self as destinatary
//                if (!count($resources['assign']))
//                    $resources['assign'][] = $this->user->Initiator;
//            }
        //---process inbox--------------

        $renderData['name'] = 'Ingresar Proyecto';
        $renderData['text'] = '';
        $renderData['text'] .= '<hr/>';
//        $renderData['text'] .=nl2br();
        $this->ui->compose('bpm/modal_msg_little', 'bpm/bootstrap.ui.php', $renderData);
    }

    function set_evaluador($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('bpm/bpm');
        $this->load->library('bpm/ui');

        $group_name = 'ciudad/EVALUADOR TÉCNICO';
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        $mywf = $this->bpm->load($idwf);
        $wf = $this->bpm->bindArrayToObject($mywf ['data']);
        //---tomo el template de la tarea
        $shape = $this->bpm->get_shape('oryx_86F5055B-EF9B-4EB3-A636-F4D8AD782981', $wf);
        //----token que hay que finalizar
        $src_resourceId = 'oryx_86F5055B-EF9B-4EB3-A636-F4D8AD782981';
        // ---Token de pp asignado
        $lane_resourceId = 'oryx_295810F2-8C34-4D03-80F8-7B5C371381B8';
        // ----get evaluadores
        $evaluadores = $this->user->getbygroupname($group_name);
        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId";
        $evaluadores = array_map(function ($user) use($url) {
            // var_dump($user);exit;
            $rtn_arr = array(
                'iduser' => $user->idu,
                'name' => $user->name,
                'lastname' => $user->lastname,
                'nick' => $user->nick,
                'url' => $url . '/' . $user->idu
            );
            return $rtn_arr;
        }, $evaluadores);

        $renderData ['title'] = "ciudad::Assignar Evaluador";
        $renderData ['name'] = "Assignar Evaluador";
        $renderData ['documentation'] = ($shape->properties->documentation <> '') ? nl2br($this->parser->parse_string($shape->properties->documentation, $renderData, true, true)) : '';
        $renderData ['base_url'] = base_url();
        $renderData ['button'] = $evaluadores;
        // ---prepare UI
        $renderData ['js'] = array(
            $this->base_url . 'bpm/assets/jscript/modal_window.js' => 'Modal Window Generic JS'
        );
        $renderData ['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'idwf' => $idwf,
            'idcase' => $idcase,
            'resourceId' => $src_resourceId
        );

        $this->ui->compose('ciudad/get_user', 'bpm/bootstrap.ui.php', $renderData);
    }

    function clone_case($from_idwf, $to_idwf, $idcase) {
        $this->load->model('bpm/bpm');
        $this->load->module('bpm/engine');
        $case = $this->bpm->get_case($idcase, $from_idwf);
        $case_to = $this->bpm->get_case($idcase, $to_idwf);
        if (!$case_to) {

            $this->bpm->gen_case($to_idwf, $idcase);
            $case_to = $this->bpm->get_case($idcase, $to_idwf);
            $case_to['data'] = $case['data'];
            $case_to['iduser'] = $case['iduser'];
            $case_to = $this->bpm->save_case($case_to);
            $this->engine->Startcase('model', $to_idwf, $idcase);
        } else {
            /*
             *   continue case
             */
            $mywf = $this->bpm->load($to_idwf);
            if (!$mywf) {
                show_error("Model referenced:$idwf does not exists");
            }
            $wf = bindArrayToObject($mywf ['data']);
            // ---Get all start points of diagram
            $start_shapes = $this->bpm->get_start_shapes($wf);
            // ----Raise an error if doesn't found any start point
            if (!$start_shapes)
                show_error("The Schema doesn't have an start point");
            // ---Start all StartNoneEvents as possible
            foreach ($start_shapes as $start_shape) {
                $this->bpm->set_token($to_idwf, $idcase, $start_shape->resourceId, $start_shape->stencil->id, 'pending');
            }
            $this->engine->Run('model', $to_idwf, $idcase);
        }

        //----run case
        // Modules::run("bpm/run/model/$to_idwf/$idcase");
    }

    function show_msgs($idcase) {

        $idwfs = array('ciudadGral', 'ciudadpde');
        foreach ($idwfs as $idwf) {
            $filter = array(
                'idwf' => $idwf,
                'case' => $idcase,
            );

            $title = ($idwf == 'ciudadGral') ? "Pre Aprobados" : "Aprobados";

            $cdata = array();
            $cdata['title'] = "Notificaciones (" . $title . "): ";
            echo Modules::run('inbox/show_msgs_by_filter', $filter, $cdata);
        }
    }

    function delegate_case() {

        $this->load->model('ciudad_model');
        $idwf = 'ciudadGral';
        $idcase = 'XYIK';
        $iduser_dest = -2101255759;

        $update = $this->ciudad_model->delegate_case_action($idwf, $idcase, $iduser_dest);
        return $update;
    }

    function widget_2doMe2($chunk = 1, $pagesize = 10) {
        //$data['lang']=$this->lang->language; ==
        $query = array(
            'assign' => $this->idu,
            'status' => 'user',
            'idwf'=>'ciudadGral',
        );
        //var_dump(json_encode($query));exit;
        $data=array();
        $tasks_raw = $this->bpm->get_tasks_byFilter($query, array(), array('checkdate' => 'desc'));
        $parts = array_chunk($tasks_raw, $pagesize, true);

        $tasks=(isset($parts[$chunk-1]))?$parts[$chunk-1]:array();
    	//==== Pagination
    	define("PAGINATION_WIDTH",$this->config->item('pagination_width'));
    	define("PAGINATION_ALWAYS_VISIBLE",$this->config->item('pagination_always_visible'));
    	define("PAGINATION_ITEMS_X_PAGE",$pagesize);
    	$Qmsgs=count($tasks_raw);
    	$config=array('url'=>$this->base_url."ciudad/widget_2doMe2",
    			'current_page'=>$chunk,
    			'items_total'=>$Qmsgs, // Total items
    			'items_x_page'=>PAGINATION_ITEMS_X_PAGE,
    			'pagination_width'=>PAGINATION_WIDTH,
    			'class_ul'=>"pagination-sm",
    			'class_a'=>"reload_widget",
    			'pagination_always_visible'=>PAGINATION_ALWAYS_VISIBLE
    	);
    	$data['pagination']=$this->pagination->index($config);
    	$data['items_total']=$Qmsgs;


       // $data = Modules::run('bpm/bpmui/prepare_tasks', $tasks, $chunk, $pagesize);
        if (!empty($tasks))
             foreach ($tasks as $k => $mytask) {
                $mycase = $this->bpm->get_case($mytask['case']);
                $mycase['data']=$this->bpm->load_case_data($mycase);
                // var_dump($mycase['data']);exit;
                //  continue;
                $tasks[$k]['extra_data']['ip']=false;
                $tasks[$k]['extra_data']['ciudad']=false;
                $tasks[$k]['icon'] = $this->base_url.$this->bpm->get_icon($tasks[$k]['type']);
                $tasks[$k]['label']=$tasks[$k]['checkdate'];
                $tasks[$k]['extra_data']['ciudad']='Sin ciudad';
                $tasks[$k]['extra_data']['ip']='Sin IP';
                $tasks[$k]['label'] = (isset($tasks[$k]['checkdate'])) ? Modules::run('bpm/bpmui/time_elapsed_string', $tasks[$k]['checkdate']) : '';
                $tasks[$k]['label-class'] = 'label-warning';

                // // datos ciudad
                // if (isset($mycase['data']['Ciudades']['query']['id'])) {
                //     $ciudadID = $mycase['data']['Ciudades']['query']['id'];
                //     $ciudad = $this->bpm->get_data('container.Ciudades', array('id' => $ciudadID));
                // }
                $tasks[$k]['extra_data']['ciudad'] = isset($mycase['data']['ciudad'])?$mycase['data']['ciudad']['ciudad']:'???';
                // datos proyectos ciudad
                $tasks[$k]['extra_data']['ip'] =(isset($mycase['data']['Ciudades']['9410']))? $mycase['data']['Ciudades']['9410']:'???';
                // if (isset($mycase['data']['Proyectos_ciudad']['query']['id'])) {
                //     $proyectoID = $mycase['data']['Proyectos_ciudad']['query']['id'];
                //     $proyecto = $this->bpm->get_data('container.proyectos_ciudad', array('id' => $proyectoID));
                //     $tasks[$k]['extra_data']['ip'] = $proyecto[0]['8339'];

                //     $url = (isset($mycase['data'] ['Proyectos_ciudad']['query']['id'])) ? '../dna2/frontcustom/284/list_docs_ciudad_eval.php?id=' . $mycase['data'] ['Proyectos_ciudad']['query'] ['id'] : '#';
                //     $tasks[$k]['link_open'] = $this->bpm->gateway($url);
                // }


             }//foreach

            $data['mytasks']=$tasks;
            $data['title'] = $this->lang->line('Tasks') . ' ' . $this->lang->line('Pending');
            $data['more_info_link'] = $this->base_url . 'bpm/';
            $data['widget_url'] = base_url() . $this->router->fetch_module() . '/' . $this->router->class . '/' . __FUNCTION__;
            $data['widget_url']=$this->module_url.__FUNCTION__;
            $data['showPager']=false;
            $data['base_url']=$this->base_url;
           // var_dump($data);
            echo $this->parser->parse('ciudad/widgets/2doMe2', $data, true, true);

    }

    public function prepare_tasks($tasks, $chunk, $pagesize) {
        $data = array();
        $data['module_url'] = $this->module_url;
        $data['base_url'] = $this->base_url;
        $data['showPager'] = false;
        $data['isAdmin'] = $this->user->isAdmin();
        $trace = debug_backtrace();
        $caller = $trace[1]['function'];

        $total = count($tasks);
        $data['qtty'] = $total;
        $parts = array_chunk($tasks, $pagesize, true);
        $pages = count($parts);
        $data['mytasks']=array();//--prevent parser problems
        if ($pages) {
            $tasks = $parts[$chunk - 1];
            foreach ($tasks as $task) {
                $model = $this->bpm->get_model($task['idwf'], array('data.properties'));
                if ($model) {
                    $title = $model->data['properties']['name'] . ' :: ' . $task['title'];
                } else {
                    $title = '???' . ' :: ' . $task['title']; //---missing model
                }
                $task['title'] = $title;
                $task['label'] = (isset($task['checkdate'])) ? $this->time_elapsed_string($task['checkdate']) : '';
                $task['label-class'] = 'label-warning';
                $task['icon'] = $this->bpm->get_icon($task['type']);
                $data['mytasks'][] = $task;
            }
            //---prepare pages
            $data['showPager'] = ($pages > 1) ? true : false;
            for ($i = 1; $i <= $pages; $i++) {
                $data['pages'][] = array(
                    'title' => $i,
                    'url' => $this->base_url . 'bpm/bpmui/' . $caller . '/' . $i . '/' . $pagesize,
                    'class' => ($i == $chunk) ? 'bg-blue' : '',
                );
            }
            $data['number'] = count($tasks);
        }
        return $data;
    }



}

/* End of file ciudad */
    /* Location: ./system/application/controllers/welcome.php */
