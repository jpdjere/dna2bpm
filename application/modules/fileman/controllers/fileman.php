<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * fileman
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    May 15, 2013
 */
class Fileman extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user');
        $this->user->authorize();
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('filemodel');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'fileman/';
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (float) $this->session->userdata('iduser');
        $this->base_dir = '/var/www/test';
        //---config
        $this->load->config('config');
    }

    /*
     * Index
     */

    function Index() {
        $this->fileMan1();
    }

    function fileMan1() {
        $this->load->library('ui');
        //$this->parser->parse('bpm/ext.browser.php', $wfData);
        //    var_dump(base_url()); exit;
        //---only allow admins and Groups/Users enabled
        $this->user->authorize();
        $cpData = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['nolayout'] = (in_array('nolayout', $segments)) ? '1' : '0';
        //var_dump($level);
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'FileMan';
        $cpData['ext-locale'] = 'ext-lang-es';
        //---define files to viewport
        $cpData['css'] = array(
            $this->base_url . "jscript/ext/src/ux/css/CheckHeader.css" => 'checkHeader',
            $this->module_url . "assets/css/load_mask.css" => 'loadingmask',
            $this->module_url . "assets/css/fileman.css" => 'fileman',
            $this->module_url . "assets/css/extra-icons.css" => 'Extra Icons',
        );

        $cpData['js'] = array(
            $this->module_url . "assets/jscript/ext.settings.js" => 'Settings',
            $this->module_url . "assets/jscript/browser/data.js" => 'Data Objects',
            $this->module_url . "assets/jscript/browser/tree.js" => 'Module Tree',
            $this->module_url . "assets/jscript/browser/center_panel.js" => 'Center Panel',
            $this->module_url . "assets/jscript/browser/viewport.js" => 'Viewport',
        );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );

        $this->ui->makeui('ext.ui.php', $cpData);
    }

    function get_tree2() {
        //$this->load->helper('ext');
        $debug = false;
        //---get models
        $order = 'data.properties.name';
        $models = $this->bpm->get_models();
        foreach ($models as $bpm) {
            $folder = (property_exists($bpm, 'folder')) ? $bpm->folder . '/' : '';
            $m_arr[$folder . $bpm->idwf] = $bpm->data['properties']['name'] . ' [' . $bpm->idwf . ']';
        }
        $tree = $this->explodeTree($m_arr, $delimiter = '/');

        $full_tree = $this->convert_to_ext($tree, 0);

//        $full_tree = array((object) array(
//            "id" => 'root',
//            "text" => "Object Repository",
//            "cls" => "folder",
//            "expanded" => true,
//            "checked" => false,
//            "children"=>$rtnArr
//            ));

        if (!$debug) {
            header('Content-type: application/json');
            echo json_encode($full_tree);
        } else {
            var_dump($full_tree);
        }
    }

    function convert_to_ext($array) {
        $rtn_arr = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                asort($value);
                $pathArr = explode('/', $key);
                $rtn_arr[] = array_filter(
                        array(
                            'id' => $key,
                            'text' => end($pathArr),
                            'leaf' => false,
                            'cls' => 'folder',
                            'checked' => ($this->config->item('browser_tree_checkable_folders')) ? false : null,
                            'expanded' => ($this->config->item('browser_tree_expanded')) ? true : false,
                            'children' => array_filter($this->convert_to_ext($value))
                        )
                );
                //$id++;
            } else {
                $rtn_arr[] = array_filter(
                        array(
                            'id' => $key,
                            'text' => $value,
                            'leaf' => true,
                            'checked' => ($this->config->item('browser_tree_checkable_models')) ? false : null,
                            //data' => $value,
                            'iconCls' => ($this->config->item('tree_icon_model')) ? $this->config->item('tree_icon_model') : null
                        )
                );
            }
        }
        return array_filter($rtn_arr);
    }

    function explodeTree($array, $delimiter = '_', $baseval = false) {
        if (!is_array($array))
            return false;
        $splitRE = '/' . preg_quote($delimiter, '/') . '/';
        $returnArr = array();
        foreach ($array as $key => $val) {
            // Get parent parts and the current leaf
            $parts = preg_split($splitRE, $key, -1, PREG_SPLIT_NO_EMPTY);
            $leafPart = array_pop($parts);

            // Build parent structure
            // Might be slow for really deep and large structures
            $parentArr = &$returnArr;
            foreach ($parts as $part) {
                if (!isset($parentArr[$part])) {
                    $parentArr[$part] = array();
                } elseif (!is_array($parentArr[$part])) {
                    if ($baseval) {
                        $parentArr[$part] = array('__base_val' => $parentArr[$part]);
                    } else {
                        $parentArr[$part] = array();
                    }
                }
                $parentArr = &$parentArr[$part];
            }

            // Add the final part to the structure
            if (empty($parentArr[$leafPart])) {
                $parentArr[$leafPart] = $val;
            } elseif ($baseval && is_array($parentArr[$leafPart])) {
                $parentArr[$leafPart]['__base_val'] = $val;
            }
        }
        //---order by name
        asort($returnArr);
        return $returnArr;
    }

    function get_tree() {
        $node = $_POST['node'];
        $pathArr[] = $this->base_dir;
        $path = ($node <> 'root') ? $node : $this->base_dir;
        $debug = false;
        $full_tree = $this->convert_to_ext($this->dirToArray($path));
        if (!$debug) {
            header('Content-type: application/json');
            echo json_encode($full_tree);
        } else {
            var_dump($full_tree);
        }
    }

    function dirToArray($dir) {

        $result = array();

        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    //----check 4 ajax config
                    if ($this->config->item('browser_tree_ajax')) {
                        $result[$dir . DIRECTORY_SEPARATOR . $value] = array();
                    } else {
                        $result[$dir . DIRECTORY_SEPARATOR . $value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                    }
                    //$result+=$this->dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    $result[$dir . DIRECTORY_SEPARATOR . $value] = $value;
                }
            }
        }

        return $result;
    }

}