<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * Description of the class
 * 
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 */
class Consulta extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->model('model_reportes');
        $this->load->library('parser');
        $this->load->module('financiamiento/reportes');
    }
    
    function Index() {
        $this->user->authorize();
    	$this->load->module('dashboard');
    	$this->dashboard->dashboard('financiamiento/json/formulario_consulta.json');
    }
    
    /**
     * Muestra elformulario de consulta
     */
    function mostrar_formulario(){
        return $this->load->view('financiamiento/consulta/form', '', true);
    }
    
    /**
     * Funcion para la busqueda de casos en la base
     */
    function search(){
        $input = $this->input->post();
        $query[$input['param']] = $input['value'];
        $customData['results'] = $this->model_reportes->get_data_by_query($query);
        $this->reportes->parse_values($customData['results']);
        $this->reportes->parse_subarrays($customData['results']);
        $this->parse_keys($customData['results']);
        $this->parser->parse('financiamiento/consulta/resultado', $customData);
    }
    
    function parse_keys(&$cases){
        $tabla_maestra=array(
            'razon_social'=>'Razon Social',
            'tipo_sociedad'=>'Tipo de Sociedad',
            'cuit'=>'Cuit',
            'provincia'=>'Provincia',
            'nombre_contacto'=>'Nombre de Contacto',
            'cargo'=>'Cargo',
            'telefono'=>'Telefono',
            'mail'=>'Email',
            'compartir_efis'=>'Compartir Informacion con Efis',
            'bancos_otros'=>'Bancos con los que trabaja',
            'sector_actividad'=>'Sector de la Actividad Principal',
            'cat_pyme'=>'Categoria',
            'destino_prestamo_gran'=>'Destino del Prestamo',
            'sectores_proyecto_gran'=>'Sector del Proyecto',
            'monto_prestamo_gran'=>'Monto del Prestamo',
            'situacion_2_gran'=>'Situacion 2 o mas',
            'deuda_afip_gran'=>'Deuda con Afip',
            'signo_negativo_gran'=>'Signo Negativo',
            'endeudamiento_gran'=>'Endeudamiento',
            'liquidez_gran'=>'Liquidez',
            'capital_trabajo'=>'Capital de Trabajo',
            'idcase'=>'Caso',
            'tiene_prestamos'=>'Tiene Prestamos',
            'sectores_proyecto_nobanc'=>'Sectores del Proyecto',
            'destino_prestamo_nobanc'=>'Destino del Prestamo',
            'monto_solicitado'=>'Monto Solicitado',
            'clasificacion_deudores'=>'Clasificacion de deudores',
            'tiene_tramite'=>'Consurso en tramite',
            'concurso_homologado'=>'Consurso Homologado',
            'monto_solicitado_otros'=>'Monto Solicitado',
            'destino_prestamo'=>'Destino del Prestamo',
            'sectores_proyecto'=>'Sectores del Proyecto',
            'parque_industria'=>'Situado en un Parque Industrial',
            'monto_prestamo'=>'Monto del Prestamo',
            'programa'=>'Programas'
        );
        
        foreach($cases as &$case){
            foreach($case as $key=>&$value){
                if(array_key_exists($key, $tabla_maestra)){
                    $case[$tabla_maestra[$key]]=$value;
                    unset($case[$key]);
                }
            }
        }
    }
}



