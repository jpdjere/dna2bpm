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
        $this->load->model('user');
        $this->load->model('inventory_model');
        $this->user->authorize('modules/inventory');
        $this->load->library('parser');
        $this->load->library('ui');
//---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'inventory/';
//----LOAD LANGUAGE
        $this->idu = (float) $this->session->userdata('iduser');
//---config
        $this->load->config('config');
//---QR
        $this->load->module('qr');
    }

    /*
     * Presentamos menu de acciones: info Checkin
     */

    function Index() {
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['module_url_encoded'] = $this->qr->encode($this->module_url);
        $cpData['title'] = 'Mesa de Entradas Digital';
        $cpData['css'] = array(
            $this->module_url . 'assets/css/inventory.css' => "Mesa Entrada css",
        );
        $cpData['js'] = array(
            $this->base_url . "inventory/assets/jscript/bootbox.min.js" => 'BootBox',
            $this->base_url . "inventory/assets/jscript/actions.js" => 'Main Search',
        );
        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        $this->ui->compose('index', 'bootstrap.ui.php', $cpData);
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
        $cpData['css'] = array(
            $this->base_url . "inventory/assets/css/inventory.css" => 'custom css',
        );
        $cpData['js'] = array(
            $this->base_url . "qr/assets/jscript/html5-qrcode.min.js" => 'HTML5 qrcode',
            $this->base_url . "qr/assets/jscript/jquery.animate-colors-min.js" => 'Color Animation',
            $this->base_url . "inventory/assets/jscript/qr.js" => 'Main functions',
        );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'redir' => $this->module_url . 'info'
        );
        $this->ui->compose('query', 'bootstrap.ui.php', $cpData);
    }

    function gencode() {
        if ($this->input->post('code')) {
            $code = $this->input->post('code');
            $type = $this->input->post('type');
            $data = $this->module_url . "info/$type/$code";
            $encoded_url = $this->qr->encode($data);
            $cpData['base_url'] = $this->base_url;
            $cpData['module_url'] = $this->module_url;
            $cpData['code'] = $code;
            $cpData['type'] = $code;
            $cpData['src'] = $this->base_url . "qr/gen_url/$encoded_url/6/L";
            $this->parser->parse('code', $cpData);
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
        $cpData['module_url_encoded'] = $this->qr->encode($this->module_url);
        $cpData['title'] = ' <i class="icon-qrcode"></i> Mesa de Entradas Digital:: Info Expediente';
        $mode = 'direct';
        $mode = ($this->input->post('data')) ? 'postData' : $mode;
        $mode = ($this->input->post('code')) ? 'postCode' : $mode;
        switch ($mode) {
            case 'postData':
                //var_dump($this->input->post('data'));
                $parts = explode('/', str_replace($this->base_url, '', $this->input->post('data')));
                $type = $parts[2];
                $code = implode('/', array_slice($parts, 3));
                if ($type)
                    $cpData['type'] = $type;
                if ($code)
                    $cpData['code'] = $code;
                $proyecto = $this->app->get_result('container.proyectos_pacc', array('6390' => $code), array());
                //var_dump($proyecto);exit;
                $cpData['title'] = '';
                $result = $this->prepare($this->inventory_model->get($type, $code));
                if ($result) {
                    unset($result['_id']);
                    $cpData['estado'] = $proyecto['6225'];
                    $cpData['6404'] = $proyecto['6404'];
                    $cpData['result'] = $result;
                    $this->parser->parse('info_table', $cpData);
                } else {
                    $cpData['msg'] = "No se encontraron resultados para: $type::$code";
                    $this->parser->parse('error', $cpData);
                }
                break;

            case 'postCode';
                $code = $this->input->post('code');
                $type = $this->input->post('type');
                if ($type)
                    $cpData['type'] = $type;
                if ($code)
                    $cpData['code'] = $code;
                //var_dump($proyecto);
//                exit;
                $result = $this->prepare($this->inventory_model->get($type, $code));
                //if ($result) {
                unset($result['_id']);

                $cpData['pacc_data']=$this->get_pacc_data($code);
                        
                $cpData['result'] = $result;
                $this->parser->parse('info_table', $cpData);
                break;

            default:
                $parts = explode('/', $this->uri->uri_string());
                $code = implode('/', array_slice($parts, 3));
                if ($type)
                    $cpData['type'] = $type;
                if ($code)
                    $cpData['code'] = $code;
                //var_dump($type,$code);
                //---tomo valores de 
                $result = $this->prepare($this->inventory_model->get($type, $code));
                if ($result) {
                    unset($result['_id']);
                    $cpData['result'] = $result;
                    $this->ui->compose('info', 'bootstrap.ui.php', $cpData, false, false);
                } else {
                    $cpData['msg'] = " No se encontraron resultados para: $type::$code";
                    $this->ui->compose('error', 'bootstrap.ui.php', $cpData, false, false);
                }
                break;
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
            $rtnArr[] = $val;
        }
        return $rtnArr;
    }

    /*
     * Esta funcion realiza el checkin para el usuario actual o user
     */

    function Assign_to() {
        $this->load->model('user/group');
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

    function get_users($idgroup) {
        $debug = false;
        $result = array();
        if ($idgroup) {
//----select 1st group and load
            $users = $this->user->getbygroup($idgroup);
//var_dump($users);exit;
            foreach ($users as $thisUser)
                $result[] = array(
                    'idu' => $thisUser->idu,
                    'name' => (property_exists($thisUser, 'name')) ? $thisUser->name : '???',
                    'lastname' => (property_exists($thisUser, 'lastname')) ? $thisUser->lastname : '???',
                );
        }
        if (!$debug) {
            header('Content-type: application/json;charset=UTF-8');
            echo json_encode($result);
        } else {
            var_dump($result);
        }
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

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */