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
class kitchensink extends MX_Controller {

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

    function Index() {
    
    }
    
    // ============ highcharts demo
    
    function highcharts($args=array()) {
    	
    	$data['lang'] = $this->lang->language;
    	$data['base_url'] = $this->base_url;
    	$data['module_url'] = $this->module_url;


        $return['content']=<<<_EOF_
        <div id="highcharts1" style="width:100%; height:400px;"></div>
        <div class="callout callout-warning">
            <h4>Adding Highcharts</h4>
            <p>Just add the handler to the JS parameter // www.highcharts.com</p>
        
        <p>i.e.:</p>
        <ul class="list-unstyled">
            <li>"js":[{"0":"highCharts"}]</li>
        </ul>
        
        </div> 
_EOF_;


    	$return['inlineJS']=<<<BLOCK
    	//------- Highcharts
    	$('#highcharts1').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Fruit Consumption'
        },
        xAxis: {
            categories: ['Apples', 'Bananas', 'Oranges']
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
    });
BLOCK;
    	return $return;
    }


    //=== Boxes
    function boxes(){
        return $this->parser->parse('widgets/kitchensink_boxes', array(), true, true);

    }
    
  // ============ Knob
  
    function knob($config=array(),$data=array()) {

// 		JSON data example
//  	"params":[
//  	"[{'value':50,'data-label':'Hey','data-fgColor':'#f60'},{'value':90,'data-label':'Hey2'},{'value':20,'data-label':'Hey3'}]",
//  	"{'title':'mytitle','col-md':4,'col-sm':6,'col-xs':6}"
//  	]
// 		First parameter brings data for each knob, second parameter is for general settings
    			


        // Global Settings
        $default=array(
        	'data-width'=>'90',
        	'data-height'=>'90',
        	'data-min'=>0,
        	'data-max'=>100,
        	//'data-step'=>1,//step size 
        	//'data-angleOffset'=>0,  //starting angle in degrees  
        	//'data-angleArc'=>360,//arc size in degrees
        	//'data-readOnly'=>true,
        	//'data-fgColor'=>'#f56954',
        	//'data-font'=>'arial',
        	//'data-inputColor'=>'#0f0', // number color
        	//'data-linecap'=>'butt', // butt|round    	  		
        	'data-thickness'=>.3,
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


        //== DEBUG
          $data[]=array('value'=>50,'data-label'=>'Demo');
          $data[]=array('value'=>10,'data-fgColor'=>'#f60','data-label'=>'Demo');
		//==

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
        			'label'=>$label
        	);
        }
        


    $customData['extra']=<<<_EOF_
    <div class="callout callout-warning">
        <h4>Adding Knob</h4>
        <p>Just add the handler to the JS parameter // https://github.com/aterrien/jQuery-Knob</p>
    
        <p>i.e.:</p>
        <ul class="list-unstyled">
            <li>"js":[{"0":"knob"}]</li>
        </ul>
    
    </div> 
_EOF_;
        return $this->parser->parse('widgets/knob', $customData, true, true);
        
    }
    
    
    
    
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */