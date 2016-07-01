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
class Lite extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->config('dashboard/config');
        $this->load->library('parser');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->user->authorize();
        //----LOAD LANGUAGE
       // $this->lang->load('library', $this->config->item('language'));
        $this->lang->load('dashboard/dashboard', $this->config->item('language'));
        $this->idu = $this->user->idu;
    }

    function Index() {
        Modules::run('dashboard/dashboard', 'dashboard/json/lite.json');
    }
    

    
function lite(){

    $this->load->model('bpm/bpm');
     $this->load->model('msg');
     $this->lang->language;

     $data['base_url'] = $this->base_url;
     
     // Inbox
     $data['inbox_count']=true;
     $data['inbox_count_qtty']=count($this->msg->get_msgs_by_filter(array('to'=>$this->idu,'folder'=>'inbox','read'=>false)));
     $data['inbox_count_label_class']='success';
     
     // Tramites
     $data['tramites_count']=true;
     $data['tramites_count_label_class']='success';


     // menu
        $this->load->model('menu/menu_model');
        $query = array('repoId' => 'tramites');
        $repo = $this->menu_model->get_repository($query, $check);
        $tree = Modules::run('menu/explodeExtTree',$repo,'/');
        $menu = $this->get_ul($tree[0]->children,'list-unstyled');
    
    $data['tramites_extra']=(empty($tree[0]->children))?($this->lang->line('no_cases')):($menu); ;
     
    // Mis tramites
     $cases = $this->bpm->get_cases_byFilter(
                array(
            'iduser' => $this->idu,
            'status' => 'open',
                ), array(), array('checkdate' => 'desc')
        );
    $data['mistramites_count']=true;
    $data['mistramites_count_label_class']='success';
    $data['mistramites_count_qtty']=count($cases);

    $data['mistramites_extra']="---- Extra ";
    
    // tasks 
    $data['tareas_count']=true;
    $data['tareas_count_label_class']='danger';
    $data['tareas_count_qtty']=count($cases);
     
    $data['tareas_extra']=Modules::run('bpm/bpmui/widget_cases');

    // Parse    
     echo $this->parser->parse('lite', $data, true, true);
}
    
    
    function mis_tramites(){
        echo "---- Mis tramites";
    }

    function tramites(){
        echo "---- Tramites";
    }
    
    

    function get_ul($menu, $ulClass = 'list-unstyled') {

         $returnStr = '';
         $returnStr.='<ul class="' . $ulClass . ' ">';
         foreach ($menu as $path => $node) {

             $icon= "<i class='ion $node->iconCls'></i>";
             $anchor="<a href='$node->target' title='$node->title' class='$node->cls' >";
            
            if(!$node->leaf && count($node->children)){
                 //submenu
                 $returnStr.="<li class=''><a href='#'> $icon <span>$node->text</span>";
                 $returnStr.=$this->get_ul($node->children, 'treeview-menu');
                 $returnStr.="</a></li>";
            }else{
                // li 
                 $returnStr.="<li>$anchor  $icon $node->text";
                 $returnStr.="</a></li>";
            } 
            
         }
        $returnStr.='</ul>';
        return $returnStr;

    }


}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */