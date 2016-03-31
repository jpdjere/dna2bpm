<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * map
 * 
 * This class provides map services an geolocation
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date   Jun 6, 2013
 */
class Map extends MX_Controller {

    function __construct() {
        
       
        
        parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . 'map/';
        $this->load->library('parser');
        
  
    }

    function Index(){
        

        $this->map_dashboard();
    }
    
    function map_dashboard(){
        
        Modules::run('dashboard/dashboard', 'map/json/dashboard.json');
        
    }
    
    function Index2() {
        $this->load->helper('url');
        echo anchor('map/demo1', 'Demo 1') . '<hr/>';
        echo anchor('map/demo_json', 'Demo Json') . '<hr/>';
        echo anchor('map/pickup', 'PcickUp');
    }

    function pickup() {
        $this->load->library('ui');
        //---prepare globals 4 js
        $renderData['title'] = "Pick Up on Click";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['css'] = array(
            $this->module_url . 'assets/css/map.css' => 'MAP CSS'
        );
        $renderData['js'] = array(
            $this->module_url . 'assets/jscript/demo/pickup.js' => 'Pick-Up JS'
        );
        $renderData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        $this->ui->compose('pickup', 'map/bootstrap.ui.map.php', $renderData);
    }

    function demo_json() {
        $this->json($this->module_url . 'assets/json/demo.json');
    }
    
    
    function reload_table($id, $title){
        
        $title = urldecode($title);
        $renderData['provincia'] = $title;
        
     
        
        $renderData['data'] = $this->data_table3($data);
        $this->parser->parse('reload-table', $renderData); 
     
    }

    function reload_map($id, $title){
        
        $this->load->module('dashboard');
        $renderData['title'] = "Mapa De Buenos Aires";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template="dashboard/widgets/box_info.php";
        echo $this->parser->parse('map-svg-bs', $renderData, true, true);
    }

    function map_table($id, $title=""){
        
        $this->load->module('dashboard');
        $renderData['title'] = "Mapa con Tablas onClick";
        $renderData['table'] = $title;
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template="dashboard/widgets/box_info.php";
        $renderData['class']="map-table"; 
        $renderData['json_url'] = $this->base_url.'map/data_test_json';
        $renderData['data'] = $this->data_table3($data);
        $renderData['provincia'] = $title;
        $renderData['content']= $this->parser->parse('map-table', $renderData, true, true);
        
        return $this->dashboard->widget($template, $renderData);
        
    }
 
 
    function map_svg(){
        
    $this->load->module('dashboard');
    $template="dashboard/widgets/box_info.php"; 
    $renderData['content']= $this->parser->parse('map-svg-bs', $renderData, true, true);
    
    return $this->dashboard->widget($template, $renderData);
        
    }
 
 
 
 
 
 
    function full_table_emprendedores(){
        
        $this->load->module('dashboard');
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = 'Proyectos Emprendedores';
        $renderData['proyectos'] = $this->full_table_data($data);
        $template="dashboard/widgets/box_info.php";
        $renderData['content']= $this->parser->parse('map-full-table', $renderData, true, true);
        
        return $this->dashboard->widget($template, $renderData);
        
    }
    
    function full_table_empresas(){
        
        $this->load->module('dashboard');
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = 'Proyectos Empresas';
        $renderData['proyectos'] = $this->full_table_data($data);
        $template="dashboard/widgets/box_info.php";
        $renderData['content']= $this->parser->parse('map-full-table', $renderData, true, true);
        
        return $this->dashboard->widget($template, $renderData);
        
    }
    
    function reload_map_full_table(){
        
        $this->load->module('dashboard');
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = 'Proyectos Empresas';
        $renderData['json_url'] = "";
        $template="dashboard/widgets/box_info.php";
        $renderData['content']= $this->parser->parse('map-full-table', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
        
    }
    
    
    
    
    
    
    function demo1bis() {
        $this->load->library('ui');
        //---prepare globals 4 js
        $renderData['title'] = "Demo 1 Harcoded all";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['css'] = array(
            $this->module_url . 'assets/css/map.css' => 'MAP CSS'
        );
        $renderData['js'] = array(
            'http://maps.google.com/maps/api/js?sensor=true' => 'Google API',
            $this->module_url . 'assets/jscript/jquery.ui.map.v3/jquery.ui.map.full.min.js' => 'Jquery.ui.map V3',
            $this->module_url . 'assets/jscript/demo/demo1.js' => 'DEMO1 JS'
        );
        $renderData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        $this->ui->compose('demo1', 'map/bootstrap.ui.php', $renderData);
    }

    private function json($url = null) {
        $this->load->library('ui');
        //---prepare globals 4 js

        $renderData['title'] = "Demo JSON url:$url";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['css'] = array(
            $this->module_url . 'assets/css/map.css' => 'MAP CSS'
        );
        $renderData['js'] = array(
            'http://maps.google.com/maps/api/js?sensor=true' => 'Google API',
            //$this->module_url . 'assets/jscript/jquery.ui.map.v3/jquery.ui.map.full.min.js' => 'Jquery.ui.map V3',
            $this->module_url . 'assets/jscript/jquery.ui.map.v3/jquery.ui.map.js' => 'Jquery.ui.map V3',
            $this->module_url . 'assets/jscript/demo/demo.json.js' => 'DEMO JSON'
        );
        $renderData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'json_url' => $url,
        );
        $this->ui->compose('demo1', 'map/bootstrap.ui.php', $renderData);
    }
    
    
    function data_table1($data) {

         
        $data = array(1, 2, 3, 4);
       
        $data[0] = array(
            'seccion' => '1° Sección Electoral',
            'cantidad' => '24'
        );
        $data[1] = array(
            'seccion' => '2° Sección Electoral',
            'cantidad' => '34'
      
        );
        $data[2] = array(
            'seccion' => '3° Sección Electoral',
            'cantidad' => '47'
        );
        $data[3] = array(
            'seccion' => '4° Sección Electoral',
            'cantidad' => '87'
        );
        
       return $data;
    }

        function data_table2($data) {

         
        $data = array(1, 2, 3, 4);
      
        $data[0] = array(
            'seccion' => '1° Sección Electoral',
            'cantidad' => '11'
        );
        $data[1] = array(
            'seccion' => '2° Sección Electoral',
            'cantidad' => '23'
        );
        $data[2] = array(
            'seccion' => '3° Sección Electoral',
            'cantidad' => '32'
        );
        $data[3] = array(
            'seccion' => '4° Sección Electoral',
            'cantidad' => '54'
        );
        
       return $data;
    }

    
        
    function data_table3($data, $prov) {

         
        $data = array(1, 2, 3, 4);
       
        $data[0] = array(
            'seccion' => '1° Sección Electoral',
            'cantidad' => '35'
        );
        $data[1] = array(
            'seccion' => '2° Sección Electoral',
            'cantidad' => '53'
      
        );
        $data[2] = array(
            'seccion' => '3° Sección Electoral',
            'cantidad' => '47'
        );
        $data[3] = array(
            'seccion' => '4° Sección Electoral',
            'cantidad' => '74'
        );
        
        return $data;
    }
    
    function data_test_json(){
            
        $data[0]=$this->data_table1();
        $data[1]=$this->data_table2();
        $data[2]=$this->data_table3();
         
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);

    }
    
    function full_table_data($data){
        
        $data[0] = array(
            'prov' => 'Buenos Aires',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table1(), 
            'realizados' => '35'
        );
        $data[1] = array(
            'prov' => 'Catamarca',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
              'data' => $this->data_table2(),
            'realizados' => '35'
        );
        $data[2] = array(
            'prov' => 'Chaco',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table3(),
            'realizados' => '35'
        );
        $data[3] = array(
            'prov' => 'Chubut',
            'presentados' => '5',
            'seccion' => '1° Sección Electoral',
            'seccion' => '1° Sección Electoral',
            'seccion' => '1° Sección Electoral',
            'seccion' => '1° Sección Electoral',
            'data' => $this->data_table1(),
            'cantidad' => '35'
        );
        $data[4] = array(
            'prov' => 'Ciudad Autónoma de Buenos Aires',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table2(),
            'realizados' => '35'
        );
        $data[5] = array(
            'prov' => 'Córdoba',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table3(),
            'realizados' => '35'
        );
        $data[6] = array(
             'prov' => 'Corrientes',
             'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table1(),
            'realizados' => '35'
        );
        $data[7] = array(
            'prov' => 'Formosa',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table2(),
            'realizados' => '35'
        );       
        $data[8] = array(
            'prov' => 'Jujuy',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table3(),
            'realizados' => '35'
        );
        $data[9] = array(
           'prov'=> 'La Pampa',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table1(),
            'realizados' => '35'
        );
        $data[10] = array(
            'prov' => 'La Rioja',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table2(),
            'realizados' => '35'
        );
        $data[11] = array(
            'prov' => 'Mendoza',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table3(),
            'realizados' => '35'
        );
        $data[12] = array(
            'prov' => 'Misiones',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table1(),
            'realizados' => '35'
        );
        $data[13] = array(
           'prov' => 'Neuquén',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table2(),
            'realizados' => '35'
        );
        $data[14] = array(
            'prov' => 'Río Negro',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'data' => $this->data_table3(),
            'realizados' => '35'
        );
        $data[15] = array(
            'prov' => 'Salta',
             'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'realizados' => '35'
        );        
        $data[16] = array(
            'prov' => 'San Juan',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'realizados' => '35'
        );
        $data[17] = array(
           'prov' => 'San Luis',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'realizados' => '35'
        );
        $data[18] = array(
            'prov' => 'Santa Cruz',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'realizados' => '35'
        );
        $data[19] = array(
            'prov' => 'Santa Fe',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'realizados' => '35'
        );        
        $data[20] = array(
            'prov' => 'Santiago del Estero',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'realizados' => '35'
        );
        $data[21] = array(
            'prov' => 'Tierra del Fuego',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'realizados' => '35'
        );
        $data[22] = array(
            'prov' => 'Tucumán',
            'presentados' => '5',
            'preaprobados' => '4',
            'aprobados' => '3',
            'rechazados' => '1° Sección Electoral',
            'finalizados' => '1° Sección Electoral',
            'realizados' => '35'
        );

        return $data;
    }
    
    
    
    
}

/* End of file map */
/* Location: ./system/application/controllers/welcome.php */