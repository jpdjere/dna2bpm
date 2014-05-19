<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class admin extends MX_Controller {

    public $template = array(
        'id' => 'string',
        'title' => 'string',
        'target' => 'string',
        'text' => 'string',
        'cls' => 'string',
        'iconCls' => 'string',
        'priority' => 'int',
        'info' => 'string',
        'hidden' => 'boolean',
        'callBack' => 'string',
    );

    function __construct() {
        parent::__construct();
        $this->load->library('parser');

        $this->load->model('index/menu');
        $this->user->authorize();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
    }

    function Menu($repoId = 0) {
        //    var_dump(base_url()); exit;
        //---only allow admins and Groups/Users enabled
        $this->load->library('ui');
        $this->user->authorize();
        $cpData = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['nolayout'] = (in_array('nolayout', $segments)) ? '1' : '0';
        //var_dump($level);
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Menu Admin';
        $cpData['ext-locale'] = 'ext-lang-es';
        //---define files to viewport
        $cpData['css'] = array(
            $this->base_url . "jscript/ext/src/ux/css/CheckHeader.css" => 'checkHeader',
            $this->module_url . "assets/css/admin.css" => 'Admin css',
            $this->module_url . "assets/css/groups.css" => 'Groups css',
            $this->module_url . "assets/css/load_mask.css" => 'loadingmask',
        );

        $cpData['js'] = array(
            $this->module_url . "assets/jscript/ext.settings.js" => 'Settings',
            $this->module_url . "assets/jscript/data.js" => 'Data Objects',
            $this->module_url . "assets/jscript/tree_menu.js" => 'Menu Tree',
            $this->module_url . "assets/jscript/ext.load_props.js" => 'Properties Loader',
            $this->module_url . "assets/jscript/propertyGrid.js" => 'Menu Properties Editor',
            $this->module_url . "assets/jscript/app.js" => 'Viewport',
        );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'repoId' => $repoId,
        );

        $this->ui->makeui('user/ext.ui.php', $cpData);
    }

    function getpaths($repoId = 0) {
        $this->user->authorize();
        $segments = $this->uri->segments;
        $debug = (in_array('debug', $segments)) ? true : false;
        
        //--get paths from db
        $rtnArr['paths'] = $this->menu->get_paths();

        if (!$debug) {
            header('Content-type: application/json;charset=UTF-8');
            echo json_encode($rtnArr);
        } else {
            var_dump($rtnArr);
        }
    }

    function get_properties() {
        $debug = false;
        $data['id'] = $this->input->post('id');
        $repoId = ($this->input->post('repoId')) ? $this->input->post('repoId') : '0';

        //$data = $this->menu->get_path($repoId, $this->input->post('id'));

        $this->load->helper('dbframe');
        $menu_item = new dbframe();
        //$properties=(isset($data['properties'])) ? $data['properties'] : array();
        $menu_item->load(array(), $this->template);
        if (!$debug) {
            header('Content-type: application/json;charset=UTF-8');
            echo json_encode($menu_item->toShow());
        } else {
            var_dump('Obj', $menu_item, 'Save:', $menu_item->toSave(), 'Show', $menu_item->toShow());
        }
    }

    function repository($repoId = 0, $action) {
        $repoId = $repoId;
        $this->user->authorize();
        $this->load->helper('ext');
        $segments = $this->uri->segments;
        $debug = (in_array('debug', $segments)) ? true : false;
        $i = 0;
        $rtnArr = array();

        switch ($action) {
            case 'update'://----Path added
                $post = json_decode(file_get_contents('php://input'));
                //----remove root from path
                foreach ($post as $menuItem) {
                    $path_arr = explode('/', $menuItem->id);
                    array_shift($path_arr);
                    $path = implode('/', $path_arr);
                    $properties = array(
                        "source" => "User",
                        "checkdate" => date('Y-m-d H:i:s'),
                        "idu" => $this->idu
                    );
                    $result = $this->menu->put_path($repoId, $path, array_merge($properties, (array) $menuItem));
                }
                $rtnArr['success'] = true;
                break;
            case 'read':
                $node = ($this->input->post('node')) ? $this->input->post('node') : 'root';
                $repo = $this->menu->get_repository(array('repoId' => $repoId));
                $rtnArr = explodeExtTree($repo, '/');
                //var_dump($repo);
                $rtnArr = (property_exists($rtnArr[0], 'children')) ? $rtnArr[0]->children : array();
                break;
            case 'sync':
                $rtnArr['success'] = false;
                $paths = $this->input->post('paths');
                                
                if ($paths) {
                    $i=0;
                    foreach ($paths as $path) {
                        $item=$this->menu->get_path($repoId, $path);
                        $item->properties['priority']=$i++;
                        $this->menu->put_path($path, $item->properties);
                   
                    }
                }
                $rtnArr['success'] = true;
                break;
            case 'delete':

                $rtnArr['success'] = false;
                $path = $this->input->post('path');
                //----remove root from path
                $path_arr = explode('/', $path);
                array_shift($path_arr);
                $path = implode('/', $path_arr);
                if ($path)
                    $rtnArr['success'] = $this->menu->remove_path($repoId, $path);
                break;
        }
        if (!$debug) {
            header('Content-type: application/json;charset=UTF-8');
            echo json_encode($rtnArr);
        } else {
            var_dump($rtnArr);
        }
    }

    function save_properties() {
        $debug = false;
        $post = json_decode(file_get_contents('php://input'));
        $this->load->helper('dbframe');
        $rtnArr['success'] = false;
        $repoId=$post->repoId;
        $path = $post->path;
        $data = $post->data;
        //---strip root
        $path_arr = explode('/', $path);
        array_shift($path_arr);
        $path = implode('/', $path_arr);
        //---Convert Group string to array;
        $menu_item = new dbframe($data, $this->template);
        $properties = array(
            "source" => "User",
            "checkdate" => date('Y-m-d H:i:s'),
            "idu" => $this->idu
        );
        $result = $this->menu->put_path($repoId,$path, array_merge($properties, $menu_item->toSave()));


        if (!$debug) {
            header('Content-type: application/json;charset=UTF-8');
            echo json_encode($menu_item->toShow());
        } else {
            var_dump($menu_item->toShow());
        }
    }
    
    function get_menu($repoId){
        //---return HTML menu
    }

}
