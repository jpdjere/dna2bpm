<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * test
 * 
 * Description of the class --
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    May 28, 2014
 */
class highcharts extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->config('dashboard/config');
        $this->load->library('parser');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->user->authorize();
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = $this->user->idu;
    }

    function Index($json="",$height='400') {
    	$data['lang'] = $this->lang->language;
    	$data['base_url'] = $this->base_url;
    	$data['module_url'] = $this->module_url;
    	$myid=$this->generateRandomString();
        
    	$return['content']="<div id='$myid' style='width:100%; height:{$height}px'></div>";
    	$json=<<<BLOCK
{
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Fruit Consumption'
        },
        xAxis: {
            categories: ['Apples', 'Bananas', 'Bananas']
        },
        yAxis: {
            title: {
                text: 'Fruit eaten'
            }
        },
        series: [{
            name: 'Jane',
            data: [1, 0, 4]
        }, {
            name: 'John',
            data: [5, 7, 3]
        }]
}
BLOCK;
    	$return['inlineJS']=<<<BLOCK
    	//------- Highcharts
    	$('#$myid').highcharts($json);
BLOCK;

    	return $return;
    }
    
    //=== Pie
    
    function pie($config=array()){
       var_dump($config);
    }
    
    //=== Bar
    
    function bar($config=array(),$height='400'){
        $myid=$this->generateRandomString();
       	$data['lang'] = $this->lang->language;
    	$data['base_url'] = $this->base_url;
    	$data['module_url'] = $this->module_url;
    

     //== default	 
       $default['chart']=array('type'=>'bar');
       $default['title']=array('text'=>'Title');
       $default['subtitle']=array('text'=>'Subtitle');
       $default['xAxis']=array('Categories'=>array('Apples','Bananas','Bananas'));
       $default['yAxis']=array('title'=>array('text'=>'yAxis'));
       $default['series']=array(array('name'=>'Jane','data'=>array(1,0,4)),array('name'=>'John','data'=>array(5,7,3)));
       

    //== Mix
        $myconfig = array_merge($default,$config);

       $json=json_encode($myconfig);
       $return['content']="<div id='$myid' style='width:100%; height:{$height}px'></div>";
       $return['inlineJS']=<<<BLOCK
    	//------- Highcharts
    	$('#$myid').highcharts(
        $json
        );
BLOCK;

return $return;
    }
    
    
    private function generateRandomString($length = 15) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
    }
    


}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */