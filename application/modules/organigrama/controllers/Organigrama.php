<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 *
 * Description of the class
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 */
class Organigrama extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->library('organigrama/ui');
        $this->load->library('parser');
        $this->load->model('app');
        $this->load->model('organigrama/org_model');
    }

    function Index() {
        $this->edit();
    }

    function Edit($idorg=0) {
        $this->user->authorize();
        $renderData=array();
        $renderData ['base_url'] = $this->base_url;
        // ---prepare UI
        $renderData ['css'] = array(
            // $this->module_url . 'assets/js/jquery/ui-lightness/jquery-ui-1.10.2.custom.css' => 'Basic Primitives CSS',
            $this->module_url . 'assets/jscript/jquery-ui/themes/base/jquery-ui.min.css' => 'Basic Primitives CSS',
            $this->module_url . 'assets//codemirror/codemirror.css' => 'Basic Primitives CSS',
            $this->module_url . 'assets/css/bporgeditor.latest.css' => 'Basic Primitives CSS',
            $this->module_url . 'assets/css/primitives.latest.css' => 'Basic Primitives CSS',
            $this->module_url . 'assets/css/organigrama.css' => 'Basic Primitives CSS',
            );
        $renderData ['js'] = array(
            // $this->module_url . 'assets/js/jquery/jquery-1.9.1.js' => 'Basic Primitives JS',
            $this->module_url . 'assets/jscript/jquery/dist/jquery.min.js' => 'Basic Primitives JS',
            $this->module_url . 'assets/jscript/jquery-ui/jquery-ui.min.js' => 'Basic Primitives JS',
            $this->module_url . 'assets/jscript/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js' => 'Basic Primitives JS',
            // $this->module_url . 'assets/js/jquery/jquery-ui-1.10.2.custom.js' => 'Basic Primitives JS',
            $this->module_url . 'assets/codemirror/codemirror.js' => 'Basic Primitives JS',
            $this->module_url . 'assets/js/json3.min.js' => 'Basic Primitives JS',
            $this->module_url . 'assets/js/primitives.min.js' => 'Basic Primitives JS',
            $this->module_url . 'assets/js/bporgeditor.latest.js' => 'Basic Primitives JS',
            // $this->module_url . 'assets/js/randomdata.js' => 'Oganigrama custom',
            $this->module_url . 'assets/jscript/organigrama.js' => 'Oganigrama custom',
        );
// ---prepare globals 4 js
        $renderData ['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'idorg'=>$idorg,
            'options'=>array('backdrop'=>'static', 'keyboard'=> false)
        );
         $this->ui->compose('organigrama/organigrama.php','organigrama/bootstrap.ui.php', $renderData);
    }
    /**
     * View organigram
     */ 
    function View($idorg=0) {
        $renderData=array();
        $renderData ['base_url'] = $this->base_url;
        // ---prepare UI
        $renderData ['css'] = array(
            $this->module_url . 'assets/jscript/jquery-ui/themes/base/jquery-ui.min.css' => 'Basic Primitives CSS',
            // $this->module_url . 'assets//codemirror/codemirror.css' => 'Basic Primitives CSS',
            // $this->module_url . 'assets/css/bporgeditor.latest.css' => 'Basic Primitives CSS',
            $this->module_url . 'assets/css/primitives.latest.css' => 'Basic Primitives CSS',
            $this->module_url . 'assets/css/organigrama.css' => 'Basic Primitives CSS',
            );
        $renderData ['js'] = array(
            $this->module_url . 'assets/jscript/jquery/dist/jquery.min.js' => 'Basic Primitives JS',
            $this->module_url . 'assets/jscript/jquery-ui/jquery-ui.min.js' => 'Basic Primitives JS',
            $this->module_url . 'assets/jscript/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js' => 'Basic Primitives JS',
            // $this->module_url . 'assets/codemirror/codemirror.js' => 'Basic Primitives JS',
            $this->module_url . 'assets/js/json3.min.js' => 'Basic Primitives JS',
            $this->module_url . 'assets/js/primitives.min.js' => 'Basic Primitives JS',
            // $this->module_url . 'assets/js/bporgeditor.latest.js' => 'Basic Primitives JS',
            // $this->module_url . 'assets/js/randomdata.js' => 'Oganigrama custom',
            $this->module_url . 'assets/jscript/organigrama_view.js' => 'Oganigrama custom',
        );
// ---prepare globals 4 js
        $renderData ['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'idorg'=>$idorg,
            'options'=>array('backdrop'=>'static', 'keyboard'=> false)
        );
         $this->ui->compose('organigrama/organigrama_view.php','organigrama/bootstrap.ui.php', $renderData);
    }
    function save($idorg=0){
        $this->user->authorize();
        if($this->input->post('data')){
            $data=json_decode($this->input->post('data'));
            $this->org_model->save($data,$idorg);
        }
    }
    function get($idorg=0){
        $rtnArr=$this->org_model->get($idorg);
        $this->output->set_content_type('json','utf-8');
	    echo json_encode((array)$rtnArr[0]);
	   //echo '{"data":{"pageFitMode":3,"verticalAlignment":1,"horizontalAlignment":0,"connectorType":1,"minimalVisibility":2,"selectionPathMode":1,"leavesPlacementType":3,"childrenPlacementType":2,"normalLevelShift":20,"dotLevelShift":10,"lineLevelShift":10,"normalItemsInterval":20,"dotItemsInterval":10,"lineItemsInterval":5,"itemTitleFirstFontColor":"#ffffff","itemTitleSecondFontColor":"#000080","defaultTemplateName":null,"showLabels":0,"labelOrientation":0,"labelPlacement":1,"labelSize":{"width":10,"height":24},"items":[{"id":0,"parent":null,"title":"aNuevo OrganigramaNuevo OrganigramaNuevo Organigrama","description":"VP, Public Sector","image":"http://localhost/dna2bpm/organigrama/assets/images/photos/n.png","context":null,"itemTitleColor":"#4169e1","minimizedItemShapeType":null,"groupTitle":null,"groupTitleColor":"#4169e1","isVisible":true,"isActive":true,"hasSelectorCheckbox":0,"hasButtons":0,"itemType":0,"adviserPlacementType":0,"childrenPlacementType":0,"templateName":null,"showCallout":0,"calloutTemplateName":null,"label":null,"showLabel":0,"labelSize":null,"labelOrientation":3,"labelPlacement":0}]}}';
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */