<?php

class Uploader {

    var $config;

    public function __construct() {          

        /*Vars*/
        $this->today  = date('Y-m-d h:i:s');
        $this->sgr    = strtoupper($_REQUEST['sgr']);
        $this->anexo  = strtoupper($_REQUEST['anexo']);


        $this->ci = & get_instance();
        $this->config = array(
            'upload_path' => dirname($_SERVER["SCRIPT_FILENAME"]) . "/anexos_sgr/",
            'file_name' => $this->today,
            'upload_url' => base_url(),
            'allowed_types' => "xls",
            'overwrite' => true,
        );
    }

    public function do_upload() {      
        $this->ci->load->library('upload', $this->config);
        if ($this->ci->upload->do_upload()) {
            @$this->ci->data['status']->message = "File Uploaded Successfully";
            $this->ci->data['status']->success = TRUE;
            $this->ci->data["uploaded_file"] = $this->ci->upload->data();
            //echo "<pre>";var_dump($this->ci->data);echo "</pre>";
            
            
        } else {
            $this->ci->data['status']->message = $this->ci->upload->display_errors();
            $this->ci->data['status']->success = FALSE;
        }
    }
}
