<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * inventory
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jun 12, 2013
 */
class Inventory extends MX_Controller {

    function __construct() {
        parent::__construct();
         $this->load->model('user/user');
         $this->load->model('user/group');
         $this->load->model('inventory_model');
         $this->user->authorize('modules/inventory');
         $this->load->library('parser');
//         $this->load->library('ui');
// //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'inventory/';
// ;
// //----LOAD LANGUAGE
         $this->idu = (float) $this->session->userdata('iduser');
// //---config
         $this->load->config('inventory/config');
// //---QR
         //$this->load->module('qr');
    }

    /*
     * Presentamos menu de acciones: info Checkin
     */

    function Index(){

     	//Modules::run('dashboard/dashboard','inventory/json/inventory.json');
	$this->user->authorize();
	$this->load->module('dashboard');
	$this->dashboard->dashboard('inventory/json/inventory.json');
    
    }
    
    
    function Dashboard() {

        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $this->config->load('inventory/config');

        //$cpData['module_url_encoded'] = $this->qr->encode($this->module_url);
        $cpData['module_url_encoded'] = Modules::run('qr/qr/encode',$this->module_url);
        $cpData['title'] = 'Mesa de Entradas Digital';
        //---Users & Groups
        $groups = $this->config->item('groups_allowed');

        $cpData['groups'][] = array(
            'idgroup' => '',
            'name' => $this->config->item('select_group'),
        );
        foreach ($groups as $idgroup) {
            $group = $this->group->get($idgroup);
            $cpData['groups'][] = (array) $group;
        }
//----select 1st group and load
        $users = $this->user->getbygroup($groups[0]);
//var_dump($users);exit;

        $cpData['users'] = array();

        return $this->parser->parse('index', $cpData, true,true);

        //$this->ui->compose('index', 'bootstrap.ui.php', $cpData);

    }

    /*
     * Esta funcion da informaci�n sobre el movimiento del Expediente / C�digo
     */

    function Query() {
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'QR Code';
        $cpData['reader_title'] = $cpData['title'];
        $cpData['reader_subtitle'] = 'Read QR Codes from any HTML5 enabled device';
//         $cpData['css'] = array(
//             $this->base_url . "inventory/assets/css/inventory.css" => 'custom css',
//         );
//         $cpData['js'] = array(
//             $this->base_url . "qr/assets/jscript/html5-qrcode.min.js" => 'HTML5 qrcode',
//             $this->base_url . "qr/assets/jscript/jquery.animate-colors-min.js" => 'Color Animation',
//             $this->base_url . "inventory/assets/jscript/qr.js" => 'Main functions'
//         );

//         $cpData['global_js'] = array(
//             'base_url' => $this->base_url,
//             'module_url' => $this->module_url,
//             'redir' => $this->module_url . 'info'
//         );
       
//        $cpData['myjs']='<script type="text/javascript" src="'.$this->base_url.'qr/assets/jscript/html5-qrcode.min.js"></script>';
//        $cpData['myjs'].='<script type="text/javascript" src="'.$this->base_url.'qr/assets/jscript/jquery.animate-colors-min.js"></script>';
//        $cpData['myjs'].='<script type="text/javascript" src="'.$this->base_url.'inventory/assets/jscript/qr.js"></script>';
        echo $this->parser->parse('query', $cpData, true,true);  
//               $this->load->library('ui');
//               echo $this->ui->compose('query', 'bootstrap.ui.php', $cpData);


    }

    function gencode() {
        if ($this->input->post('code')) {
            $code = $this->input->post('code');
            $type = $this->input->post('type');
            $data = $this->module_url . "info/$type/$code";
            //$encoded_url = $this->qr->encode($data);
            $encoded_url=Modules::run('qr/qr/encode',$data);
            $cpData['base_url'] = $this->base_url;
            $cpData['module_url'] = $this->module_url;
            $cpData['code'] = $code;
            $cpData['type'] = $type;
            $cpData['src'] = $this->base_url . "qr/gen_url/$encoded_url/6/L";

            echo "<img src='{$cpData['src']}'/>";
        }
    }

    function Info($type = null, $code = null) {

//----get url as array
        /*
          Nro Proyecto: 6390
          PDE:
          ESTADO 6225
          EVALUADOR TECNICO: 6404
          EBALUADOR ADMINISTRATIVO: 6743

          PP:
          ESTADO:5689
          EVALUADOR TECNICO: 6096
          EBALUADOR ADMINISTRATIVO: 7106
         */
        $this->load->model('app');
        $segments = $this->uri->segment_array();
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        //$cpData['module_url_encoded'] = $this->qr->encode($this->module_url);
        $cpData['module_url_encoded'] =Modules::run('qr/qr/encode',$this->module_url);
        $cpData['title'] = '';
        $mode = 'direct';
        $mode = ($this->input->post('data')) ? 'postData' : $mode;
        $mode = ($this->input->post('code')) ? 'postCode' : $mode;

        switch ($mode) {
            // Camera feed
            case 'postData':
          
                $parts = explode('/', str_replace($this->base_url, '', $this->input->post('data')));
                $type = $parts[2];
                $code = implode('/', array_slice($parts, 3));

                if ($type)
                    $cpData['type'] = $type;
                if ($code)
                    $cpData['code'] = $code;
                break;
            
            // Manual feed
            case 'postCode';
                $code = $this->input->post('code');
                $type = $this->input->post('type');
                if ($type)
                    $cpData['type'] = $type;
                if ($code)
                    $cpData['code'] = $code;
                break;
            
            // URL feed
            default:
                // Direct access
                $parts = explode('/', $this->uri->uri_string());
                $code = implode('/', array_slice($parts, 3));
                if ($type)
                    $cpData['type'] = $type;
                if ($code)
                    $cpData['code'] = $code;
                break;
        }
        
        if(!empty($code)&&!empty($type)){
                $proyecto_m = $this->app->get_result('container.proyectos_pacc', array('6390' => $code), array());
                $proyecto = $proyecto_m->GetNext();
                $cpData['title'] = '';
                $cpData['pacc_data'] = $this->get_pacc_data($code);
                $result = $this->prepare($this->inventory_model->get($type, $code));
                if ($result) {
                    unset($result['_id']);
                    if ($proyecto_m->count()) {
                        $cpData['estado'] = $proyecto['6225'];
                        $cpData['_6404'] = $proyecto['6404'];

                    }
                    $cpData['result'] = $result;

                    $this->parser->parse('info', $cpData);
                } else {
                    $cpData['msg'] = '<i class="fa fa-exclamation-triangle"></i>'." No se encontraron resultados para: $type::$code";
                    $this->parser->parse('error', $cpData);
                }

        }

        
        
    }

    function get_pacc_data($code) {
        $proyectos = $this->app->get_result('container.proyectos_pacc', array('6390' => $code), array());
        $proyecto = $proyectos->getNext();
        if (isset($proyecto['6225'])) {
            if (isset($proyecto['6225'][0])) {
                //tomaopciones estado
                $ops = $this->app->get_ops(648);
                $cpData['estado'] = isset($ops[$proyecto['6225'][0]]) ? $ops[$proyecto['6225'][0]] : $proyecto['6225'][0] . ' -> ???';
            }
        }
        //----evaluador admin
        if (isset($proyecto['6473'])) {
            if (isset($proyecto['6473'][0])) {
                $this_user = $this->user->get_user($proyecto['6473'][0]);
                $cpData['e_admin'] = $this_user->name . ' ' . $this_user->lastname;
            }
        } else {
            $cpData['e_admin'] = '';
        }
        //----Evaluador técnico
        if (isset($proyecto['6404'])) {
            if (isset($proyecto['6404'][0])) {
                $this_user = $this->user->get_user($proyecto['6404'][0]);
                $cpData['e_tecnico'] = $this_user->name . ' ' . $this_user->lastname;
            }
        }
        return $cpData;
    }

    function prepare_user($arr) {
        $rtnArr = array();
        $cant = count($arr);
        for ($i = 0; $i < $cant; $i++) {
            $val = $arr[$i];
///-----calculate days

            $date1 = new DateTime();
            $date2 = new DateTime($arr[$i]['date']);

            $interval = $date2->diff($date1);
            $val['days'] = $interval->format('%a');
            $val['user_data'] = $this->user->get_user_array((double) $val['user']);

            $group = $this->group->get($val['user_data']['idgroup']);
            $val['group'] = $group['name'];
            $val['date'] = date('d/m/Y H:i', strtotime($val['date']));
            $rtnArr[] = $val;
        }
        return $rtnArr;
    }

    function prepare($arr) {
        $rtnArr = array();
        $cant = count($arr);
        for ($i = 0; $i < $cant; $i++) {
            $val = $arr[$i];
///-----calculate days
            if (isset($arr[$i + 1])) {
                $date1 = new DateTime($arr[$i + 1]['date']);
                $date2 = new DateTime($arr[$i]['date']);
            } else {
                $date1 = new DateTime();
                $date2 = new DateTime($arr[$i]['date']);
            }
            $interval = $date2->diff($date1);
            $val['days'] = $interval->format('%a');
            $val['user_data'] = $this->user->get_user_array((double) $val['user']);

            $group = $this->group->get($val['user_data']['idgroup']);
            $val['group'] = $group['name'];
            $val['date'] = date('d/m/Y H:i', strtotime($val['date']));
            $rtnArr[] = $val;
        }
        return $rtnArr;
    }

    function Assign() {

        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Assign';
        $cpData['reader_title'] = $cpData['title'];
        $cpData['reader_subtitle'] = 'Read QR Codes from any HTML5 enabled device';
        $cpData['css'] = array(
            $this->base_url . "inventory/assets/css/inventory.css" => 'custom css',
        );
        $cpData['js'] = array(
            $this->base_url . "qr/assets/jscript/html5-qrcode.min.js" => 'HTML5 qrcode',
            $this->base_url . "qr/assets/jscript/jquery.animate-colors-min.js" => 'Color Animation',
            $this->base_url . "inventory/assets/jscript/reader_post.js" => 'Reader functions',
            $this->base_url . "inventory/assets/jscript/assign.js" => 'Assign functions',
        );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'redir' => $this->module_url . 'assign_to',
            'idu' => $this->idu
        );
        $this->ui->compose('query', 'bootstrap.ui.php', $cpData);
    }

    /*
     * Esta funcion realiza el checkin para el usuario actual o user
     */

    function Assign_to() {
        $this->load->model('user/group');
        //$this->module_url = 'http://www.accionpyme.mecon.gov.ar/dna2bpm/inventory/';
        //var_dump($this->input->post('data'));
        if ($this->input->post('data')) {
            $groups = $this->config->item('groups_allowed');
            foreach ($groups as $idgroup) {
                $group = $this->group->get($idgroup);
                $cpData['groups'][] = (array) $group;
            }
//----select 1st group and load
            $users = $this->user->getbygroup($groups[0]);
//var_dump($users);exit;
            foreach ($users as $thisUser)
                $cpData['users'][] = array(
                    'idu' => $thisUser->idu,
                    'name' => (property_exists($thisUser, 'name')) ? $thisUser->name : '???',
                    'lastname' => (property_exists($thisUser, 'lastname')) ? $thisUser->lastname : '???',
                );
            $parts = explode('/', str_replace($this->module_url, '', $this->input->post('data')));
            $type = $parts[1];
            $code = implode('/', array_slice($parts, 2));
            if ($type)
                $cpData['type'] = $type;
            if ($code)
                $cpData['code'] = $code;

            $result = $this->prepare($this->inventory_model->get($type, $code));
            unset($result['_id']);
            $cpData['result'] = $result;

            $cpData['title'] = '';
            $cpData['data'] = $this->input->post('data');
            $this->parser->parse('assign', $cpData);
        }
    }

    function show_objects() {

        $idu = $this->input->post('idu');
        $idgroup = $this->input->post('idgroup');
        $theuser=$this->user->get_user($idu);
        $cpData['title']="Expedientes de {$theuser->lastname}, {$theuser->name}";
        if ($idu) {
            $result = $this->prepare_user($this->inventory_model->getbyuser($idu));
        } else {
            $result = $this->prepare_user($this->inventory_model->getbygroup($idgroup));
        }
        if ($result) {
            unset($result['_id']);
            $cpData['result'] = $result;
            $this->parser->parse('info_table_user', $cpData);
        } else {
            $cpData['msg'] = " No se encontraron resultados.";
            $this->parser->parse('error', $cpData);
        }
    }

    function Claim() {
        //----get url as array
        $segments = $this->uri->segment_array();
        $pure = in_array('pure', $segments);
        $cpData['show_header'] = ($pure) ? false : true;
        $type = null;
        $code = null;
        //--come from data
        if ($this->input->post('data')) {
            $parts = explode('/', str_replace($this->base_url, '', $this->input->post('data')));
            $type = $parts[2];
            $code = implode('/', array_slice($parts, 3));
        }
        //--come from btn
        if ($this->input->post('type') and $this->input->post('code')) {
            $type = $this->input->post('type');
            $code = $this->input->post('code');
        }

        if ($type)
            $cpData['type'] = $type;
        if ($code)
            $cpData['code'] = $code;
        if ($type and $code) {

            $iduser = ($this->input->post('idu')) ? $this->input->post('idu') : $this->idu;
            $this->inventory_model->claim($type, $code, $iduser);
            $result = $this->prepare($this->inventory_model->get($type, $code));
            unset($result['_id']);
            $cpData['result'] = $result;
            $cpData['title'] = '';
            $this->parser->parse('info', $cpData);
        }
    }

    function Checkin() {
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Check In';
        $cpData['reader_title'] = $cpData['title'];
        $cpData['reader_subtitle'] = 'Read QR Codes from any HTML5 enabled device';
        $cpData['css'] = array(
            $this->base_url . "inventory/assets/css/inventory.css" => 'custom css',
        );
        $cpData['js'] = array(
//$this->base_url . "qr/assets/jscript/html5-qrcode.min.js" => 'HTML5 qrcode',
            $this->module_url . "assets/jscript/html5-qrcode.js" => 'HTML5 qrcode',
            $this->base_url . "qr/assets/jscript/jquery.animate-colors-min.js" => 'Color Animation',
            $this->base_url . "inventory/assets/jscript/reader_post.js" => 'Main functions',
        );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'redir' => $this->module_url . 'claim',
            'idu' => $this->idu
        );
        $this->ui->compose('query', 'bootstrap.ui.php', $cpData);
    }

    function get_users($idgroup = null) {
        $debug = false;
        $result = array();
        if ($idgroup) {
//----select 1st group and load
            $users = $this->user->getbygroup($idgroup);
// var_dump($users);exit;
            $result[] = array(
                'idu' => '',
                'name' => $this->config->item('select_user'),
                'lastname' => "",
            );
            foreach ($users as $thisUser)
                $result[] = array(
                    'idu' => $thisUser['idu'],
                    'name' => (isset($thisUser['name'])) ? $thisUser['name'] : '???',
                    'lastname' => (isset($thisUser['lastname'])) ? $thisUser['lastname'] : '???',
                );
        }
        if (!$debug) {
            header('Content-type: application/json;charset=UTF-8');
            echo json_encode($result);
        } else {
            var_dump($result);
        }
    }
    
    
    function notify(){
        $data=$this->input->post('data'); 
        $idu=(int)$this->input->post('idu'); 
        if(empty($idu)||empty($data))exit("misssing data");
        $this->load->model('msg');
        $body="El expediente -<a href='$data' class='ajax'>".$data.'</a>- le ha sido asignado.';
         $msg=array(
        'subject'=>'Expediente asignado',
        'body'=>$body,
        'from'=>666
        );
    
        $this->msg->send($msg,$idu);  
          
    }
    

}

/* End of file welcome.php */
    /* Location: ./system/application/controllers/welcome.php */