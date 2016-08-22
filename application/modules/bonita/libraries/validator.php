<?php

/**
 * Valida los datos del excel de carga
 * 
 * @author Sebastian Blazquez
 * @date    Jul 13, 2016
 */
class Validator extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    function altaprestamos($data){
		$this->load->config('bonita/validator', true);

    	$this->form_validation->set_data($data);
		$this->form_validation->set_rules($this->config->item('validator'));
		if (!$this->form_validation->run()) {
			return validation_errors();
		}
		return true;
    }
}