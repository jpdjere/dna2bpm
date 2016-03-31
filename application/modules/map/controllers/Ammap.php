<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * map
 * 
 * This class provides map services an geolocation
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date   Dec 15, 2014
 */
class Ammap extends MX_Controller {

    function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

    function Index(){
        

        $this->map_dashboard();
    }
    
    function map_dashboard(){
        
                
        Modules::run('dashboard/dashboard', 'map/json/dashboard.json');
        
        
    }

    function demo() {
        $this->load->library('parser');
        $this->load->library('ui');
        //---prepare globals 4 js
        $renderData['title'] = "Demo 1 Harcoded all";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['css'] = array(
            $this->module_url . 'assets/jscript/ammap/ammap.css' => 'AMMAP CSS'
        );
        $renderData['js'] = array(
            $this->module_url . 'assets/jscript/ammap/ammap.js' => 'amMaps core',
            $this->module_url . 'assets/jscript/ammap/maps/js/argentinaHigh.js' => 'Argentina High',
            $this->module_url . 'assets/jscript/ammaps_demo.js' => 'Init'
        );
        $renderData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        //---define de id for the div
        $renderData['mapdiv']='mapdiv';
        $this->ui->compose('ammap_div', 'map/bootstrap.ui.php', $renderData);
    }

    
        function demo2() {
        $this->load->library('parser');
        $this->load->library('ui');
        //---prepare globals 4 js
        $renderData['title'] = "Demo 2 Harcoded all";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['css'] = array(
            $this->module_url . 'assets/jscript/ammap/ammap.css' => 'AMMAP CSS'
        );
        $renderData['js'] = array(
            $this->module_url . 'assets/jscript/ammap/ammap.js' => 'amMaps core',
            $this->module_url . 'assets/jscript/ammap/themes/black.js' => 'Init',
            $this->module_url . 'assets/jscript/ammap/maps/js/argentinaHigh.js' => 'Argentina High',
            $this->module_url . 'assets/jscript/ammaps_demo2.js' => 'Init'
        );
        $renderData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        //---define de id for the div
        
        $renderData['mapdiv']='mapdiv2';
        $this->ui->compose('ammap_div', 'map/bootstrap.ui.php', $renderData);
    }
        function demo4() {
        $this->load->helper('file');            
        $this->load->module('dashboard'); 
        
        $renderData['title'] = "Mapa SVG Demo 4";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template="dashboard/widgets/box_info.php";    
        /* estoy probando con el svg inline */
        $renderData['svg']=read_file(APPPATH.'modules/map/assets/img/lace.svg');
        $renderData['content'] = $this->parser->parse('argprueba', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
        
    }
    
    
    
    
    function demo1_hardcoded() {
        //---prepare globals 4 js
        $this->load->module('dashboard');
        $renderData['title'] = "Mapa de Proyectos Presentados";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['json_url'] = $this->base_url.'map/assets/json/crefis2010.json';
        $renderData['map_id']= 'mapdiv-'.microtime();
        $renderData['map_class']= 'map_heat';
        $template="dashboard/widgets/box_info.php";
        $renderData['content']=$this->parser->parse('ammap_div', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
       
    }
        function demo3_hardcoded() {
        //---prepare globals 4 js
          $this->load->module('dashboard');
        $renderData['title'] = "Mapa SVG Demo 2";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['json_url'] = "";
        $renderData['map_id']= 'mapdiv2';
        $renderData['map_class']= 'map_bubble';
        $template="dashboard/widgets/box_info.php";
        $renderData['content']= $this->parser->parse('ammap_div', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
       
    }
    
        function demo3() {
        //---prepare globals 4 js
        $this->load->module('dashboard');
        $renderData['title'] = "Mapa SVG Demo 2 - Full Width";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['json_url'] = "";
        $renderData['mapdiv']= 'mapdiv1';
        $renderData['map_class']= 'map_bubble';
        $template="dashboard/widgets/box_info.php";
        $renderData['content']= $this->parser->parse('ammap_bubble', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
       
    }
    
    function data_test(){
        $data=array(
            'areas'=> array ("id"=> "AR-K","value"=> 4447100),
            'ColorSteps' => 0,
            'ValueLegendmin'=> 0,
            'ValueLegendmax'=> 100
            );
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }
    
        function demo2_hardcoded() {
        //---prepare globals 4 js
        $renderData['title'] = "Demo 2 Harcoded all";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['json_url'] = $this->base_url.'map/assets/json/demo2.json';
        //---define de id for the div
        $renderData['map_id']= 'mapdiv2';
        $renderData['map_class']= 'map_heat';
        return $this->parser->parse('ammap_div', $renderData, true, true);
    }
    
        function map_table($id, $title="chubut"){
        
        $this->load->module('dashboard');
        $renderData['title'] = "Mapa con Tablas onClick";
        $renderData['table'] = $title;
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['json_url'] = "";
        $template="dashboard/widgets/box_info.php";
        $renderData['data'] = $this->data_table($data);
        
        $data['json_url'] = $this->base_url.'map/data/'.$id;
        
        $renderData['content']= $this->parser->parse('map-table', $renderData, true, true);
        
 
        return $this->dashboard->widget($template, $renderData);
        
    }
}

/* End of file map */
/* Location: ./system/application/controllers/welcome.php */