<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * inventory
 * 
 * Description of the class
 * 
 * @author Sebastian Blazquez
 * @date    Jul 11, 2016
 */
class bonita extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user/user');
        $this->load->model('user/group');
        $this->user->authorize('modules/bonita');
        $this->load->library('parser');
        $this->base_url = base_url();
        $this->module_url = base_url() . 'bonita/';
        $this->idu = (float) $this->session->userdata('iduser');
    }

    /**
     * Muestra un link a los Reportes y las Licitaciones
     */
    function Index(){
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/menu/bonita.json');
    }
    
    /**
     * View de los Reportes
     */
    function bonita_modulos_list() {
        $customData['title'] = 'Menu';
        $customData['tabla'] =
        '<tr><a href="'.$this->module_url.'menu_reportes/">Menu Reportes</a></tr></br>'.
        '<tr><a href="'.$this->module_url.'menu_licitaciones/">Menu Licitaciones</a></tr></br>'.
        '<tr><a href="'.$this->module_url.'menu_resoluciones/">Menu Resoluciones</a></tr></br>';
        return $this->parser->parse('bonita/views/menu/menu',$customData,true,true);    
    }

    /**
     * View de los Reportes
     */
    function bonita_reportes_list() {
        $customData['title'] = "Reportes";
        $customData['tabla'] =
        '<tr><a href="'.$this->module_url.'bonita_reportes/bonita_reporte_provincias/" target="_blank">Reportes por Provincia</a></tr></br>'.
        '<tr><a href="'.$this->module_url.'bonita_reportes/bonita_reporte_regiones/" target="_blank">Reportes por Región</a></tr></br>'.
        '<tr><a href="'.$this->module_url.'bonita_reportes/bonita_reporte_sectores/" target="_blank">Reportes por Sector</a></tr></br>'.
        '<tr><a href="'.$this->module_url.'bonita_reportes/bonita_reporte_sectores_tam/" target="_blank">Reportes por Sector y Tamaño</a></tr></br>';
        return $this->parser->parse('bonita/views/menu/menu',$customData,true,true);    
    }
    
    /**
     * View de las Licitaciones
     */
    function bonita_licitaciones_list() {
        $customData['title'] = 'Licitaciones';
        $customData['tabla'] =
        '<tr><a href="'.$this->module_url.'licitaciones/abm_entidades/" target="_blank">ABM Entidades</a></tr></br>'.
        '<tr><a href="'.$this->module_url.'licitaciones/abm_licitaciones/" target="_blank">ABM Licitaciones</a></tr></br>'.
        '<tr><a href="'.$this->module_url.'licitaciones/menu_cargar_montos/" target="_blank">Cargar Montos</a></tr></br>'.
        '<tr><a href="'.$this->module_url.'licitaciones/menu_reportes/" target="_blank">Reportes Licitaciones Cerradas</a></tr></br>';
        return $this->parser->parse('bonita/views/menu/menu',$customData,true,true);    
    }

    /**
     * Muestra el Menu Reportes
     */
    function menu_reportes(){
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/menu/reportes.json');
    }
    
    /**
     * Muestra el Menu Licitaciones
     */
    function menu_licitaciones(){
        $this->user->authorize();
        $this->load->module('dashboard');
        $this->dashboard->dashboard('bonita/json/menu/licitaciones.json');
    }
}

/*function Query() {
    $cpData['base_url'] = $this->base_url;
    $cpData['module_url'] = $this->module_url;
    $cpData['title'] = 'QR Code';
    $cpData['reader_title'] = $cpData['title'];
    $cpData['reader_subtitle'] = 'Read QR Codes from any HTML5 enabled device';
    echo $this->parser->parse('query', $cpData, true,true);  
}*/