<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class file_connector extends CI_Model {

    function File_connector() {
        parent::__construct();
        $this->load->helper('file');
    }

    function get_data($resource, $shape, $wf) {
        $path = 'images/user_files/' . $wf->idwf . '/' . $wf->case . '/' . str_replace("\n",'_', $shape->properties->name);
        $dirinfo = get_dir_file_info($path);
        return $dirinfo;
    }

    function get_ui($resource, $shape, $wf) {
        $this->load->library('parser');
        $path = 'images/user_files/' . $wf->idwf . '/' . $wf->case . '/' . str_replace("\n",'_', $shape->properties->name);
        $dirinfo = array();
        $info = get_dir_file_info($path);
        if ($info) {
            array_map(function($arr) use (&$dirinfo) {
                $dirinfo['files'][] = $arr;
            }, $info);
        }
        $collection = $shape->properties->iscollection;
        $dirinfo['properties']=(array)$shape->properties;
        $dirinfo['dropClass'] = ($collection) ? 'multipleDrop' : 'singleDrop';
        $str = $this->parser->parse('bpm/file_connector', $dirinfo, true);
        return $str;
    }

}