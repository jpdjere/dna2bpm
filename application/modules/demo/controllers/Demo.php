<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Actualiza los archivos segun la rama configurada
 * 
 * @autor Borda Juan Ignacio
 * 
 * @version 	1.13 (2012-06-14)
 * 
 * @file-salida   update-git.log
 * 
 */
class Demo extends MX_Controller {

    function __construct() {
        parent::__construct();

        $this->user->authorize();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->library('parser');
        $this->idu = (int) $this->session->userdata('iduser');
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
    }
    function Index(){
        $this->demo_dashboard();
    }

    function demo_dashboard(){
        Modules::run('dashboard/dashboard', 'demo/json/dashboard.json');
    }

    
    function full_table(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['title']= "Prioridades";
        $data['content']= $this->data_table($data);
        
        return $this->parser->parse('demo/widgets/full-table',$data,true,true);
    }
    function half_table($json_url = null){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
    //  $json_url = $this->base_url.'demo/data_test_json';
    //  $data['json_url'] = $this->base_url.'demo/data_test_json';
        
        $data['module_url'] = $this->module_url;
        $data['title']= "Desempeño Analistas";
        $data['users'] = $this->data_half_table($users);
        $data['class']=(isset($json_url)) ? "team_ranking_json":"team_ranking";
        
        return $this->parser->parse('demo/widgets/half-table',$data,true,true);
    }
    function map(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php"; 
        $data=array();
        $data['title']= "Mapa de Proyectos Presentados";
        return $this->parser->parse('demo/widgets/map',$data,true,true);
    }
    function charts(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['title']= "SDE - Últimos 28 días";
        $data['json_url'] = $this->base_url.'demo/data_lines_json';
        $data['class']= "data_lines";
        return $this->parser->parse('demo/widgets/charts',$data,true,true);
    }
    function bars(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['json_url'] = $this->base_url.'demo/data_bars';
        $data['class']= "data_bars";
        $data['title']= "Fuerza Laboral Disponible";
        
        return $this->parser->parse('demo/widgets/bars',$data,true,true);
    }
    function knobs_empresas(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['title']= "Metas para Empresas";
        return $this->parser->parse('demo/widgets/knobs-empresas',$data,true,true);
    }
    function knobs_emprendedores(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['title']= "Metas para Emprendedores";
        return $this->parser->parse('demo/widgets/knobs-emprendedores',$data,true,true);
    }
   function knobs_emprendedores_pitchs(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['title']= "PITCHs";
        return $this->parser->parse('demo/widgets/knobs-pitchs',$data,true,true);
    }
    
    function knob_info($params){
        $params['fgColor']="#337ab7";
        return $this->knob($params);   
    }
    
    function widget_knob_info($params){
        $this->load->module('progress');
        $params['content']=$this->knob_info($params);
        return $this->parser->parse('demo/widgets/simple_knob',$params,true,true);
    }
    function knob($params){
        $default=array(
            'value'=>$params['value'],
            'min'=>$params['min'],
            'max'=>$params['max'],
            'title'=>$params['title'],
            'label'=>$params['label']
            );
        $params['json_url']= $this->base_url.'pacc11/api/presentados';    
        $renderData=array_merge($default,$params);
        $renderData['class']=(isset($params['json_url'])) ? "knob json_knob":"knob";
        
        return $this->parser->parse('demo/widgets/knob',$renderData,true);
    }    

    function data_bars(){
        $this->load->module('dashboard');
    
        $porc=rand(0,100);
               $arr=[["SEMANA 1",4.5],["SEMANA 2",2],["SEMANA 3",4],["SEMANA 4",5]];
               $bar=new stdClass;
               $bar->label='Presentados';
               $bar->hoverable=true;
               $bar->clickable=true;
               $bar->data=$arr;
               $bar->bars=(object) array('show' => true,'order'=>1, 'barWidth'=>0.2 );
               $bar->color = "#3c8dbc";
               
               $data['data'][]=$bar;
               
               $bar1=new stdClass;
               $bar1->hoverable=true;
               $bar1->clickable=true;
               $bar1->label='Aprobados';
               $bar1->data=[["SEMANA 1",0.5],["SEMANA 2",1],["SEMANA 3",3],["SEMANA 4",2]];
               $bar1->bars=(object) array('show' => true,'order'=>2, 'barWidth'=>0.2);
               $bar1->color = "#FF7F50";
               $data['data'][]=$bar1;
    
        $data['config']=array();
        
        $data['config']['grid'] = array(
            'borderWidth'=>1,
            'borderColor'=>"#f3f3f3",
            'tickColor'=>"#f3f3f3"
            );
            
        $data['config']['xaxis'] = array(
            'mode'=>"categories",
            'tickLength'=>0
            );
        $data['config']['series']['bars'] = array(
            'show'=>"true",
            'barWidth'=> 0.5,
            'align' => "center"
            );    
            
            
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    } 
    
    function data_table($data){
        $this->load->module('dashboard');
        $data= array(1,2,3,4);
        $data[0]= array(
            'id' => 001,
            'name' => 'John Doe',
            'class'=> 'success',
            'delay'=> 8,
            'status'=> 'Aprobado',
            'comments'=> 'Comentarios sobre la presentación del proyecto'
                );
        $data[1]= array(
            'id' => 002,
            'name' => 'Jane Doe',
            'class'=> 'success',
            'delay'=> 4,
            'status'=> 'Aprobado',
            'comments'=> 'Comentarios sobre la presentación del proyecto'
                );
        $data[2]= array(
            'id' => 003,
            'name' => 'Bob Doe',
            'class'=> 'warning',
            'delay'=> 10,
            'status'=> 'En Revisión',
            'comments'=> 'Comentarios sobre la presentación del proyecto'
                );
        $data[3]= array(
            'id' => 004,
            'name' => 'Mike Doe',
            'class'=> 'danger',
            'delay'=> 0,
            'status'=> 'Rechazado',
            'comments'=> 'Comentarios sobre la presentación del proyecto'
                );
        return $data;        
    }
    
    
    function data_half_table(){
        $users=$this->user->getbygroup(1);
        $arr_class=array("success","warning","danger");
        foreach($users as $user){
            $porc=rand(0,100);
            
            $arr=array(
            'id' => $user->idu,
            'name' => $user->name.' '.$user->lastname,
            'color'=> '',
            'avatar'=> $this->user->get_avatar($user->idu),
            'value' => $porc
                );
                
            if ($porc < 34){ $arr['class'] = 'danger';}
            elseif ($porc > 66){ $arr['class'] = 'success';}
            else { $arr['class'] = 'warning';}
                
            $data[]=$arr;
        }

    return $data;

    }
    
        function data_test_json(){
            
        $data=$this->data_half_table();
         
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);

    }
    
        function data_lines_json(){
        
        $arr_presentados = [[0,0],[2,2],[4,4],[6,6],[8,8],[10,10],[12,12],[14,14],[16,16],[18,18],[20,20],[22,22],[24,24],[26,26],[28,28]];
        $arr_evaluados = [[0,0],[2,0],[4,1],[6,1],[8,1],[10,1],[12,1],[14,1],[16,1],[18,1],[20,1],[22,1],[24,1],[26,1],[28,1]];
            
        $obj=array('presentados'=>$arr_presentados,
                    'evaluados'=>$arr_evaluados);    
            
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($obj);

    }
    
     function map_demo() {
        //---prepare globals 4 js
        $this->load->module('dashboard');
        $renderData['title'] = "Mapa de Proyectos Presentados";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['json_url'] = $this->base_url.'demo/assets/json/crefis2010.json';
        $renderData['map_id']= 'mapdiv-'.microtime();
        $renderData['map_class']= 'map_heat';
        $template="dashboard/widgets/box_info.php";
        $renderData['content']=$this->parser->parse('ammap_div', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
     }
     
    function full_table_sortable(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['title']= "Prioridades";
        $data['content']= $this->data_table($data);
        
        return $this->parser->parse('demo/widgets/full-table-sortable',$data,true,true);
    }
    
    
    
}