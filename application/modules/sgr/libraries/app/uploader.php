<?php

class Uploader {

    var $config;

    public function __construct() {          

        /*Vars*/        
        $this->today  = date('Y-m-d h:i:s');
        $this->sgr    = base64_decode($_REQUEST['sgr']);
        $this->anexo  = $_REQUEST['anexo'];
        
        
        $sgrDIR =  dirname($_SERVER["SCRIPT_FILENAME"]) . "/anexos_sgr/";
        if (!is_dir($sgrDIR)) {
                mkdir($sgrDIR,0777,true);
        }
        
        $this->ci = & get_instance();
        $this->config = array(
            'upload_path' => $sgrDIR,
            'file_name' => $this->sgr ."_".$this->anexo ."_".$this->today,
            'upload_url' => base_url(),
            'allowed_types' => "xls",
            'overwrite' => true,
        );
    }

    public function do_upload() {
        
        $this->ci->load->library('upload', $this->config);
        if ($this->ci->upload->do_upload()) {
            @$this->ci->data['status']->message = "El Archivo subio correctamente";
            $this->ci->data['status']->success = TRUE;
            $this->ci->data["uploaded_file"] = $this->ci->upload->data();
            
        } else {
            @$this->ci->data['status']->message = $this->ci->upload->display_errors();
            $this->ci->data['status']->success = FALSE;
        }
        
        return $this->ci->data['status'];
        
    }
}
