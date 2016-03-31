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
class Gauge extends MX_Controller {

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
        $this->gauge_dashboard();
    }
    function codes(){
        $this->gauge_dashboard2();
    }
    function gauge_dashboard(){
        Modules::run('dashboard/dashboard', 'gauge/json/dashboard.json');
    }
        function gauge_dashboard2(){
        Modules::run('dashboard/dashboard', 'gauge/json/dashboard2.json');
    }
    /*
    *   Draws a simple gage with data loaded from $json_url
    *   @param $json_url string Url for data source
    *   @param $echo boolean return or echo resulting widget
    */

    function widget_gage_semaphore($params){
        
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['json_url'] =$params['json_url'];
        $data['gauge_id']='gage_'.md5(microtime());
        $data['title']='Gauge';
        $data['color']="semaphore";
        $data['content']=$this->parser->parse('gauge/widgets/simple_gage',$data,true,true);
        return $this->dashboard->widget($template, $data);
    }
        function widget_gage_success($params){
        
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['json_url'] =$params['json_url'];
        $data['gauge_id']='gage_'.md5(microtime());
        $data['title']='Gauge';
        $data['color']="success";
        $data['content']=$this->parser->parse('gauge/widgets/simple_gage',$data,true,true);
        return $this->dashboard->widget($template, $data);
    }
        function widget_gage_warning($params){
        
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['json_url'] =$params['json_url'];
        $data['gauge_id']='gage_'.md5(microtime());
        $data['title']='Gauge';
        $data['color']="warning";
        $data['content']=$this->parser->parse('gauge/widgets/simple_gage',$data,true,true);
        return $this->dashboard->widget($template, $data);
    }
        function widget_gage_info($params){
        
        $this->load->module('dashboard');
        $this->load->module('progress');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['json_url'] =$params['json_url'];
        $data['gauge_id']='gage_'.md5(microtime());
        $data['title']='Gauge';
        $data['color']="info";
        $data['content']=$this->parser->parse('gauge/widgets/simple_gage',$data,true,true);
        return $this->dashboard->widget($template, $data);
    }
        function widget_gage_danger($params){
        
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['json_url'] =$params['json_url'];
        $data['gauge_id']='gage_'.md5(microtime());
        $data['title']='Gauge';
        $data['color']="danger";
        $data['content']=$this->parser->parse('gauge/widgets/simple_gage',$data,true,true);
        return $this->dashboard->widget($template, $data);
    }

    function knob($params){
        $default=array(
            'value'=>$params['value'],
            'min'=>$params['min'],
            'max'=>$params['max'],
            'title'=>$params['title'],
            'label'=>$params['label']
            );
        $renderData=array_merge($default,$params);
        $renderData['class']=(isset($params['json_url'])) ? "knob json_knob":"knob";
        
   
        return $this->parser->parse('gauge/knob',$renderData,true);
    }
        function complex_gages($params){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
 
        $data=array();
        $data['json_url_1'] = $this->base_url.'gauge/data_test1';
        $data['json_url_2'] = $this->base_url.'gauge/data_test2';
        $data['json_url_3'] = $this->base_url.'gauge/data_test3';
        $data['gauge_id1']='gage_'.md5(microtime());
        $data['gauge_id2']='gage_'.md5(microtime());
        $data['gauge_id3']='gage_'.md5(microtime());
        $data['gauges']= [$this->base_url.'gauge/data_test1',$this->base_url.'gauge/data_test2',$this->base_url.'gauge/data_test3'];
        $data['content']=$this->parser->parse('gauge/widgets/gauges',$data,true,true);
        $data['title']= "Widget Gauges con Tablas";
        return $this->dashboard->widget($template, $data);
    }
    function data_test(){
        $data=array(
            'value'=> 32,
             'min'=> 0,
             'max'=> 100,
             'title'=> "Gauge Semaphore",
             'label'=> "Indicador"
            );
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }
        function data_test1(){
        $data=array(
            'value'=> 25,
            'min'=> 0,
            'max'=> 100,
            'title'=> "Gauge Success",
            'label'=> "Indicador"
            
            );
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }
        function data_test2(){
        $data=array(
            'value'=> 50,
             'min'=> 0,
             'max'=> 100,
             'title'=> "Gauge Warning",
             'label'=> "Indicador"
            
            );
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }
        function data_test3(){
        $data=array(
            'value'=> 75,
             'min'=> 0,
             'max'=> 100,
             'title'=> "Gauge Alert",
             'label'=> "Indicador"
            );
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }
    
    function complex_knob($data = "",$config="") {
// 		First parameter brings data for each knob, second parameter is for general settings
	
	$data_ST = str_replace("'", "\"", $data);
	$config_ST = str_replace("'", "\"", $config);
	$data=(json_decode($data_ST,true));
	$config=(json_decode($config_ST,true));


    // Global Settings
    $default=array(
    	'data-width'=>'90',
    	'data-height'=>'90',
    	'data-min'=>0,
    	'data-max'=>100,
    	//'data-step'=>1,//step size 
    	'data-angleOffset'=>270,  //starting angle in degrees  
    	'data-angleArc'=>180,//arc size in degrees
    	'data-readOnly'=>true,
    	//'data-fgColor'=>'#f56954',
    	//'data-font'=>'arial',
    	//'data-inputColor'=>'#0f0', // number color
    	//'data-linecap'=>'butt', // butt|round    	  		
    	'data-thickness'=>0.3,
    	//'data-displayInput'=>true,
    	//'data-displayPrevious'=>false, // show/hide shadow when moving the knob
    	'title'=>'-',
    	'col-md'=>3,
    	'col-sm'=>6,
    	'col-xs'=>6
    );
    $config=array_merge($default,$config); // Join user params with default 
    // get params for parser
    $customData['title']=$config['title'];
    $customData['col-md']=$config['col-md'];
    $customData['col-sm']=$config['col-sm'];
    $customData['col-xs']=$config['col-xs'];

        foreach($data as $item){
        	$myconfig=array_merge($config,$item);
        	
        	$input=" ";
        	// individual settings
        	foreach($myconfig as $attr=>$val){
        		$input.="$attr='$val' ";
        	}
    		$label=(isset($myconfig['data-label']))?($myconfig['data-label']):('');
        	$customData['knobs'][]=array(
        			'input'=>"<input type='text' $input class='knob' >",
        			'label'=>$label,
        			'value'=>$myconfig['value'],
        			'colorvalue'=>$myconfig['data-fgColor']
        	);
        }
  
    return $this->parser->parse('gauge/widgets/knob', $customData, true, true);
    }
    
    function views($filetype, $type, $file){
        $this->load->helper('file');
        $this->load->module('dashboard');
        $this->load->module('code');
        $filename=FCPATH . APPPATH . 'modules/gauge/views/'.$type.'/'.$file.'.php';
        $code=read_file($filename);
        $data['content']=$this->code->code_block($code,'html','monokai');
        $data['title']='Demo: '.$filetype.' - '.$file;
        $template="gauge/widgets/tab_codes";
        echo $this->dashboard->widget($template, $data);
    }
        function demo($filetype, $file){
        $this->load->helper('file');
        $this->load->module('dashboard');
        $this->load->module('code');
        $filename=FCPATH . APPPATH . 'modules/gauge/controllers/gauge.php';
        $code=read_file($filename);
        $data['content']=$this->code->code_block($code,'php','monokai');
        $data['title']='Demo: '.$filetype.' - '.$file;
        $template="gauge/widgets/tab_codes";
        echo $this->dashboard->widget($template, $data);
    }
    function file($file,$lang,$theme='monokai'){
        $this->load->helper('file');
        $this->load->module('dashboard');
        $filename=FCPATH . APPPATH . $file;
        $code=read_file($filename);
        $data['content']=$this->code_block($code,$lang,$theme);
        $data['title']="File: ".$filename;
        $template="dashboard/widgets/box_info_solid";
        echo $this->dashboard->widget($template, $data);
    }
    

    
    function knob_info($params){
        $params['fgColor']="#337ab7";
        return $this->knob($params);   
    }
    
    function knob_warning($params){
        $params['fgColor']="#f0ad4e";
        return $this->knob($params);   
    }
    
    function knob_success($params){
        $params['fgColor']="#5cb85c";
        return $this->knob($params); 
    }
    
    function knob_danger($params){
        $params['fgColor']="#d9534f";
        return $this->knob($params);
    }
    function tile_gauge_semaphore($params) {
        $data['lang_url'] = $this->lang->language;
        $data['base_url'] = $this->base_url;
        $data['module_url'] = $this->module_url;
        $data['json_url'] = $params['json_url'];
        $data['gauge_id']='gage_'.md5(microtime());
        $data['color']="semaphore";
        return $this->parser->parse('gauge/tiles/tile_gauge', $data, true, true);
    }
    function tile_gauge_warning($params){
        $data['lang_url'] = $this->lang->language;
        $data['base_url'] = $this->base_url;
        $data['module_url'] = $this->module_url;
        $data['gauge_id']='gage_'.md5(microtime());
        $data['color']="warning";
        $data['json_url']= $params['json_url'];
        
       return $this->parser->parse('gauge/tiles/tile_gauge', $data, true, true);
    }
    
    function tile_gauge_success($params){
        $data['lang_url'] = $this->lang->language;
        $data['base_url'] = $this->base_url;
        $data['module_url'] = $this->module_url;
        $data['gauge_id']='gage_'.md5(microtime());
        $data['color']="success";
        $data['json_url']= $params['json_url'];
        
        return $this->parser->parse('gauge/tiles/tile_gauge', $data, true, true);
    }
    
    function tile_gauge_alert($params){
        $data['lang_url'] = $this->lang->language;
        $data['base_url'] = $this->base_url;
        $data['module_url'] = $this->module_url;
        $data['gauge_id']='gage_'.md5(microtime());
        $data['color']="danger";
        $data['json_url']= $params['json_url'];
        
       return $this->parser->parse('gauge/tiles/tile_gauge', $data, true, true);
       
    }
    function tile_knob_info($params){
        $params['content']=$this->knob_info($params);
        return $this->parser->parse('gauge/tiles/tile_clean',$params,true,true);
    }
    
    function tile_knob_success($params){
        $params['content']=$this->knob_success($params);
        return $this->parser->parse('gauge/tiles/tile_clean',$params,true,true);
    }
    
    function tile_knob_warning($params){
        $params['content']=$this->knob_warning($params);
        return $this->parser->parse('gauge/tiles/tile_clean',$params,true,true);
    }
    
    function tile_knob_danger($params){
        $params['content']=$this->knob_danger($params);
        return $this->parser->parse('gauge/tiles/tile_clean',$params,true,true);
    }    
    
    function widget_knob_info($params){
        $this->load->module('progress');
        $params['content']=$this->knob_info($params);
        return $this->parser->parse('gauge/widgets/simple_knob',$params,true,true);
    }
    
    function widget_knob_success($params){
        $params['content']=$this->knob_success($params);
        return $this->parser->parse('gauge/widgets/simple_knob',$params,true,true);
    }
    
    function widget_knob_warning($params){
        $params['content']=$this->knob_warning($params);
        return $this->parser->parse('gauge/widgets/simple_knob',$params,true,true);
    }
    
    function widget_knob_danger($params){
        $params['content']=$this->knob_danger($params);
        return $this->parser->parse('gauge/widgets/simple_knob',$params,true,true);
    } 

}

