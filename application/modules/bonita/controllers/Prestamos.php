<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * inventory
 * 
 * Da de alta los prestamos que informan los bancos
 * 
 * @author Sebastian Blazquez
 * @date    Jul 13, 2016
 */
class prestamos extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user/user');
        $this->load->model('user/group');
        $this->load->model('model_prestamos');
        $this->load->module('dashboard');
        $this->load->library('parser');
        // $this->user->authorize('modules/bonita');
        $this->base_url = base_url();
        //$this->module_url = base_url() . 'bonita/';
    }

    /**
     * Redirecciona al Menu de los Prestamos
     */
    function Index(){
        redirect($this->base_url.'bonita/menu_prestamos');
    }
    
    
    
    /**
     * Inserta una entidad en la base
     */
    function insertar_entidad(){
        return $this->model_prestamos->insertar_entidad($this->input->post());
    }
    
    /**
     * Actualiza una entidad en la base
     */
    function actualizar_entidad(){
        return $this->model_prestamos->actualizar_entidad($this->input->post());
    }
    
    /**
     * Borra una entidad en la base
     */
    function borrar_entidad(){
        return $this->model_prestamos->borrar_entidad($this->input->post());
    }
    
    
    
    /**
     * Inserta un destino en la base
     */
    function insertar_destino(){
        return $this->model_prestamos->insertar_destino($this->input->post());
    }
    
    /**
     * Actualiza un destino en la base
     */
    function actualizar_destino(){
        return $this->model_prestamos->actualizar_destino($this->input->post());
    }
    
    /**
     * Borra un destino en la base
     */
    function borrar_destino(){
        return $this->model_prestamos->borrar_destino($this->input->post());
    }
    
    
    
    /**
     * Inserta una resolucion en la base
     */
    function insertar_resolucion(){
        return $this->model_prestamos->insertar_resolucion($this->input->post());
    }
    
    /**
     * Actualiza una resolucion en la base
     */
    function actualizar_resolucion(){
        return $this->model_prestamos->actualizar_resolucion($this->input->post());
    }
    
    /**
     * Borra una resolucion en la base
     */
    function borrar_resolucion(){
        return $this->model_prestamos->borrar_resolucion($this->input->post());
    }
    
    
    
    /**
     * Inserta un monto en la base
     */
    function insertar_monto(){
        return $this->model_prestamos->insertar_monto($this->input->post());
    }
    
    /**
     * Actualiza un monto en la base
     */
    function actualizar_monto(){
        return $this->model_prestamos->actualizar_monto($this->input->post());
    }
    
    /**
     * Borra un monto en la base
     */
    function borrar_monto(){
        echo $this->model_prestamos->borrar_monto($this->input->post());
    }



    /**
     * Muestra el abm de entidades
     */
    function AbmEntidades(){
        $this->dashboard->dashboard('bonita/json/prestamos/abm_entidades.json');
    }
    
    function contenido_abm_entidades(){
        $customData['content'] = $this->model_prestamos->listar_entidades();
        $customData['base_url'] = $this->base_url;
        echo $this->parser->parse('bonita/views/prestamos/abm/crud_entidades', $customData, true);
    }
    
    /**
     * Muestra el abm de los destinos
     */
    function AbmDestinos(){
        $this->dashboard->dashboard('bonita/json/prestamos/abm_destinos.json');
    }
    
    function contenido_abm_destinos(){
        $customData['content'] = $this->model_prestamos->listar_destinos();
        $customData['base_url'] = $this->base_url;
        $this->capitalize_array($customData);
        echo $this->parser->parse('bonita/views/prestamos/abm/crud_destinos', $customData, true);
    }
    
    /**
     * Muestra el abm de las resoluciones
     */
    function AbmResoluciones(){
        $this->dashboard->dashboard('bonita/json/prestamos/abm_resoluciones.json');
    }
    
    function contenido_abm_resoluciones(){
        $this->load->helper('prestamos/prestamos');
        $customData['content'] = $this->model_prestamos->listar_resoluciones();
        $categorias_pyme = $this->model_prestamos->listar_categorias_pyme();
        categorias_parser($categorias_pyme);
        foreach($customData['content'] as &$reso){
            foreach($categorias_pyme as $entrada){
                if($entrada['id_resolucion'] == $reso['id']){
                    $reso['tamano'][] = ['tamano'=>$entrada['tamano'], 'monto'=>$entrada['monto'], 'tamano_parseado'=>$entrada['tamano_parseado']];
                }
            }
        }
        // var_dump($customData['content']);
        // exit;
        $customData['base_url'] = $this->base_url;
        $this->capitalize_array($customData);
        echo $this->parser->parse('bonita/views/prestamos/abm/crud_resoluciones', $customData, true);
    }
    
    /**
     * Muestra el abm de los montos por destino
     */
    function AbmMontos(){
        $this->dashboard->dashboard('bonita/json/prestamos/abm_montos.json');
    }
    
    function contenido_abm_montos(){
        $customData['content'] = $this->model_prestamos->listar_montos();
        $customData['base_url'] = $this->base_url;
        $customData['resoluciones'] = $this->model_prestamos->listar_resoluciones();
        $customData['destinos'] = $this->model_prestamos->listar_destinos();
        $this->capitalize_array($customData);
        //var_dump($customData);exit;
        echo $this->parser->parse('bonita/views/prestamos/abm/crud_montos', $customData, true);
    }
    
    function capitalize_array(&$array){
        array_walk_recursive($array, function(&$value){$value=ucwords($value);});
    }
    
    function parse_array($array){
        foreach($array as $key => $value){
            $new_array[]=array('clave'=>$key, 'valor'=>$value);
        }
        return $new_array;
    }
    
    /**
     * Muestra el formulario de carga de prestamos
     */
    function AltaPrestamosManual(){
        $this->dashboard->dashboard('bonita/json/prestamos/alta_prestamos_manual.json');
    }
    
    function contenido_alta_prestamos_manual(){
        $customData['partidos'] = $this->parse_array($this->app->get_ops(58));
        $customData['provincias'] = $this->parse_array($this->app->get_ops(39));
        $customData['sectores'] = $this->parse_array($this->app->get_ops(494));
        $customData['entidades']=$this->model_prestamos->listar_entidades();
        $customData['destinos'] = $this->model_prestamos->listar_destinos();
        $customData['sis_amortizacion'] = $this->model_prestamos->listar_sis_amortizacion();
        $customData['resoluciones'] = $this->model_prestamos->listar_resoluciones();
        
        $this->capitalize_array($customData);
        
        $muni = $this->app->get_ops(741);
        //var_dump($muni);exit;
        echo $this->parser->parse('bonita/views/prestamos/altaprestamos/formulario_carga', $customData, true);
    }

    /**
     * Muestra el formulario para subir los prestamos importandolos desde un Excel
     */
    function AltaPrestamosImport($error = ''){
        $extraData['alerts'] = '<p>'.urldecode($error).'</p>';
        $this->dashboard->dashboard('bonita/json/prestamos/importar_excel.json', false, $extraData);
    }

    function contenido_importar_excel(){
        $this->load->helper(['form', 'url']);
        $this->load->view('bonita/views/prestamos/altaprestamos/importar_excel',false, $error);
    }
    
    /**
     * Sube el excel con los datos de los prestamos
     */
    function upload_excel(){
        $this->load->library('upload', $config);
        if(!$this->upload->do_upload('userfile')){
            redirect('bonita/prestamos/AltaPrestamosImport/'.$this->upload->display_errors(''   , ''));
        }

        $full_path = $this->upload->data('full_path');
        if(!chmod($full_path, 0774)){
            redirect('bonita/prestamos/AltaPrestamosImport/'.$this->upload->display_errors('', ''));
        }

        echo "Full path: ".$full_path;
        $this->load->library('bonita/Excel');
        $excel_file = $this->excel->load($full_path);
        
        var_dump($excel_file);
    }


    /**
     * Inserta el prestamo en la base
     */
    function insertar_prestamo(){
        $this->load->helper('prestamos');
        $data = $this->input->post();
        
        $this->model_prestamos->insertar_tabla_temp_prestamos($this->input->post(), $this->user->idu);
    }
    
    function test(){
        phpinfo();
    }
}