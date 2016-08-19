<?php

// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');
/**
 * "ventanilla electrónica" de la AFIP
 * 
 * @autor Diego Otero
 * 
 * @version 	1.0 
 * 
 * 
 */

class Consultas_padron extends MX_Controller {
    public $sector=array(
        1=>'Agropecuario',
        2=>'Industria',
        3=>'Minería',
        4=>'Servicios',
        5=>'Construcción',
        6=>'Comercio');
    /**
     *  $montos[1]=array('micro'=> 2000000,'peq'=>13000000,'tramo1'=>100000000,'tramo2'=>160000000); // Agro
    $montos[2]=array('micro'=> 7500000,'peq'=>45500000,'tramo1'=>360000000,'tramo2'=>540000000); // Ind
    $montos[3]=array('micro'=> 7500000,'peq'=>45500000,'tramo1'=>360000000,'tramo2'=>540000000); // Min
    $montos[4]=array('micro'=> 2500000,'peq'=>15000000,'tramo1'=>125000000,'tramo2'=>180000000); // Servicios
    $montos[5]=array('micro'=> 3500000,'peq'=>22500000,'tramo1'=>180000000,'tramo2'=>270000000); // Construccion
    $montos[6]=array('micro'=> 9000000,'peq'=>55000000,'tramo1'=>450000000,'tramo2'=>650000000); // Comercio

     */
    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';

        
        #LIBRARIES          
        $this->load->library('parser');
        $this->load->library('dashboard/ui');
        $this->load->library('pdf/pdf');
        
        #HELPERS
        $this->load->helper('url');
              
        #MODELS
        $this->load->model('consultas_model');
         $this->load->model('seti_model');

        
        #CREDENTIALS
        $this->idu = (int) $this->session->userdata('iduser');
      //  $this->user->authorize();
        


    }
    
    function Index() {
        $data['base_url'] = $this->base_url;
        $data['title'] = 'Consulta por CUIT';
        $data['logobar'] = $this->ui->render_logobar();
        

        $data_select = NULL;        

        echo $this->parser->parse('form_consulta_cuit', $data, true, true);
    }

    /**
     * widget Empresas por sector
     */
    function por_sector($mode=null){
        //  $this->load->module('afip');
         $this->load->module('afip/api');
         
        $template='afip/table_por_sector';     
         $renderData['data']=$this->api->por_sector('array');
         
        //  array_walk($renderData['data'],function(&$item1){
        //      $item1=array_values($item1);
        //  });
        //  var_dump($renderData);
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        switch($mode){
        case 'str':
         return $this->parser->parse($template,$renderData,true,true);
            break;
        
        case 'xls':
        header("Content-Description: File Transfer");
        header("Content-type: application/x-msexcel");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=".__FUNCTION__ .".xls");
        header("Content-Description: PHP Generated XLS Data");
        $this->parser->parse($template, $renderData);
            break;    
        case 'table':
         $this->parser->parse($template,$renderData);
            break;
        default:
            $renderData['content']= $this->parser->parse($template,$renderData,true);    
            $renderData['title'] = "Por Sector";
            $renderData['id'] = "Por_Sector";
            $template="dashboard/widgets/box_info.php";
    	   return $this->parser->parse($template, $renderData,true,true);
         break;
        }
     }

    /**
     * widget Empresas por categoria
     */
    function por_categoria($mode=null,$provincia=null){
    //  $this->load->module('afip');
     $this->load->module('afip/api');
     
    $template='afip/table_por_categoria';     
     $renderData['data']=$this->api->por_categoria('array',$provincia);
     
    //  array_walk($renderData['data'],function(&$item1){
    //      $item1=array_values($item1);
    //  });
    //  var_dump($renderData);
    $renderData['base_url'] = $this->base_url;
    $renderData['module_url'] = $this->module_url;
    switch($mode){
    case 'str':
     return $this->parser->parse($template,$renderData,true,true);
        break;
    
    case 'xls':
    header("Content-Description: File Transfer");
    header("Content-type: application/x-msexcel");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".__FUNCTION__ .".xls");
    header("Content-Description: PHP Generated XLS Data");
    $this->parser->parse($template, $renderData);
        break;    
    case 'table':
     $this->parser->parse($template,$renderData);
        break;
    default:
        $renderData['content']= $this->parser->parse($template,$renderData,true);    
        $renderData['title'] = "Por Categoria";
        $renderData['id'] = "Por_categoria";
        $template="dashboard/widgets/box_info.php";
	   return $this->parser->parse($template, $renderData,true,true);
     break;
    }
 }
 
    /**
 * widget Empresas por provincia
 */
    function por_provincia($mode=null){
    //  $this->load->module('afip');
     $this->load->module('afip/api');
     
     $renderData['data']=$this->api->por_provincia('array');
    $template='afip/table_por_provincia';     

    $renderData['base_url'] = $this->base_url;
    $renderData['module_url'] = $this->module_url;
    switch($mode){
    case 'str':
     return $this->parser->parse($template,$renderData,true,true);
        break;
    
    case 'xls':
    header("Content-Description: File Transfer");
    header("Content-type: application/x-msexcel");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".__FUNCTION__ .".xls");
    header("Content-Description: PHP Generated XLS Data");
    $this->parser->parse($template, $renderData);
        break;    
    case 'table':
     $this->parser->parse($template,$renderData);
        break;
    default:
        $renderData['content']= $this->parser->parse($template,$renderData,true);    
        $renderData['title'] = "Por Provincia";
        $renderData['id'] = "Por_Provincia";
        $template="dashboard/widgets/box_info.php";
	   return $this->parser->parse($template, $renderData,true,true);
     break;
    }
 }
    /**
 * widget Empresas por Letra
 */
    function por_letra($mode=null){
    //  $this->load->module('afip');
     $this->load->module('afip/api');
     
    $renderData['data']=$this->api->por_letra_padron('array');
    $template='afip/table_por_letra';     

    $renderData['base_url'] = $this->base_url;
    $renderData['module_url'] = $this->module_url;
    switch($mode){
    case 'str':
     return $this->parser->parse($template,$renderData,true,true);
        break;
    
    case 'xls':
    header("Content-Description: File Transfer");
    header("Content-type: application/x-msexcel");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".__FUNCTION__ .".xls");
    header("Content-Description: PHP Generated XLS Data");
    $this->parser->parse($template, $renderData);
        break;    
    case 'table':
     $this->parser->parse($template,$renderData);
        break;
    default:
        $renderData['content']= $this->parser->parse($template,$renderData,true);    
        $renderData['title'] = "Por Letra";
        $renderData['id'] = "Por_Letra";
        $template="dashboard/widgets/box_success.php";
	   return $this->parser->parse($template, $renderData,true,true);
     break;
    }
 }

}//class
