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

class Consultas extends MX_Controller {
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

    function process() {

        $data = $this->input->post('cuit');
        
        $data = isset($data)? $this->input->post('cuit') : 30714571725;#30712164707;#30714571725;

        $data = (int)$data;
        
        $rtn = $this->consultas_model->buscar_cuits_registrados($data);
        //--- Remuevo datos sensibles dejo el resto
        if($rtn){
            $b=new stdClass();
            $b->cuit=                   $rtn->cuit;
            $b->status=                 $rtn->status;
            $b->solicitaPagoTrimestral= $rtn->solicitaPagoTrimestral;
            $b->isPyme=                 ($rtn->isPyme)?'Sí':'No';
            $b->sector=                 $this->sector[$rtn->sector];
            $b->categoria=              $rtn->categoria;
            $rtn=$b;
        } else{
          /*Si no esta en queue me fijo si se remitio a SETI*/
          $rtn = $this->consultas_model->buscar_cuits_registrados($data, 'raw_ventanilla');      
            if($rtn->cuit){
              $b=new stdClass();
              $b->cuit=                   $rtn->cuit;
              $b->status=                 'ready';
              $rtn=$b;     
            }                 

      }



      if(!$debug) {
        $this->output->set_content_type('json','utf-8');
        echo json_encode($rtn);

    }else{
        var_dump($rtn);
    }
}    



function queue($parameter=null){
    $this->load->model('afip/eventanilla_model');
    $this->load->model('app');
    $data['base_url'] = $this->base_url;
    $data['module_url'] = $this->module_url;
    $data['title'] = 'Consulta por CUIT';
    $data['logobar'] = $this->ui->render_logobar();   
    $rtn=$this->consultas_model->show_queue_qry($parameter);
    $clae3=$this->app->get_ops(750);
    foreach ($rtn as &$q){
        $process=$this->eventanilla_model->get_process(array('cuit'=>$q['cuit']));
        // var_dump($process);exit;
        $q['denominacion']=$process[0]->denominacion;
        $q['formajuridica']=$process[0]->formajuridica;
        $q['flags']=($process[0]->exentoiva) ?'<span class="badge bg-blue">ExIVA</span>':'';
        $q['flags'].=($process[0]->monotributo) ?'<span class="badge bg-green">Mono</span>':'';
        $q['diferido']=($process[0]->solicitapagotrimestral) ?'<span class="badge bg-green">Sí</span>':'<span class="badge">No</span>';
        $q['result']=$process[0]->result;
        $q['result']['actividad_texto']=$clae3[$q['result']['actividad']];
    }
    
    $data['queue_list'] =$rtn;
    // var_dump($data);exit;
    $this->parser->parse('afip/queue', $data);        
}

function source($parameter){


    $this->load->module('code/code');
    $data['base_url'] = $this->base_url;
    $data['title'] = 'Consulta por CUIT';
    $data['logobar'] = $this->ui->render_logobar();   
    $data['source_raw'] = json_encode($this->show_source($parameter, 'raw_ventanilla'));     
    $data['source_process'] = json_encode($this->show_source($parameter, 'procesos'));          
    $data['source_process'] =$this->code->code_block( json_encode($this->show_source($parameter, 'procesos'),JSON_PRETTY_PRINT), 'json','textmate',40);          
        // $data['source_process'] =$this->code->highlight_block( json_encode($this->show_source($parameter, 'procesos'),JSON_PRETTY_PRINT), 'json','monokai',120);          
    $data['source_queue'] = json_encode($this->show_source($parameter, 'queue'));   

    $this->parser->parse('source', $data);


}

function show_queue($parameter=null){


  $rtn = $this->consultas_model->show_queue_qry($parameter);
  $new_list = array();
  foreach ($rtn as $key => $value) {
             # code...             

      $new_list['cuit'] = $value['cuit'];
      $new_list['status'] = $value['status'];              

      $string .=  "<li>C.U.I.T.: <strong>" . $value['cuit'] . "</strong> Estado: <strong>" . strtoupper($value['status']) . "</strong> <a href='".$this->module_url."consultas/source/".$value['cuit']."' alt='source ".$value['cuit']."' target='_blank'><i class='fa fa-plus'></i></a></li>";               
  }
  return $string;

}

function show_source($parameter, $collection){

    $data = (int)$parameter;
    $rtn = $this->consultas_model->show_source_qry($data, $collection);
    return $rtn;        
}


function has_certificado($cuit){
return !empty($this->consultas_model->cuits_certificados((int) $cuit));
}


function certificado($parameter,$type='pdf'){

    $data = (int)$parameter;
    $rtn = $this->consultas_model->cuits_certificados($data);

    $filename = "sepyme_certificado_" . $rtn->cuit."";

    if($rtn==false){
        show_error("error la C.U.I.T. " . $parameter . " no fue beneficiada con 'IVA – Cancelación trimestral'");
        exit;
    }
    
        /*
        CUIT
        Razón Social
        Actividades
        --------------
        Sector (procesos)
        Clasificación Pyme (procesos)
        Fecha de validez (hasta próximo cierre de ejercicio + 6 meses)

        Firma Funcionario
        Firma organismo
        */
        $new_list = array();
        $new_list['base_url'] = $this->base_url;
        $new_list['cuit'] = $rtn->cuit;
        $new_list['razon_social'] = $rtn->denominacion;

        
        $new_list['fecha_validez'] = $this->fix_fecha_vencimiento($rtn->mesCierre);#$fecha_validez;
        //$new_list['logobar'] = $this->ui->render_logobar();
        foreach ($rtn->result as $key => $value) {
            $new_list[$key] = $value;
        }
        
        
        $new_list['fecha_emision'] = $this->mongodate_to_print($rtn->result['date']);
        $new_list['descripcionActividadPrincipal'] = $rtn->descripcionActividadPrincipal;

                $url = current_url();       
                $url = urlencode(base64_encode($url));
                $new_list['qr_url'] = $url;
       
        switch($type){
            case 'pdf':

                $url = $this->base_url.'qr/gen_url/'.$url;
                $destination_folder = $this->module_url."assets/images/";
                $this->pdf->set_paper('a4', 'portrait');
                $this->pdf->parse('pdf', $new_list);
                $this->pdf->set_base_path($this->base_url.'/afip/assets/css/style.css');       
                $this->pdf->render();
                // var_dump($this->pdf);exit;    
                    header('Content-type: application/pdf');
                    header('Content-Disposition: inline; filename="' . $filename . '"');
                    header('Content-Transfer-Encoding: binary');
                    header('Accept-Ranges: bytes');    
                    header('Content-type: application/pdf');
                    
                    $this->pdf->stream($filename);
                    
                break;
            default:

                $this->parser->parse('pdf2', $new_list);
                break;
        }

   }


   function fix_fecha_vencimiento($parameter){
    $get_data = date('Y-'.$parameter.'-j');
    $rtn_date = strtotime ( '+5 month' , strtotime ( $get_data ) ) ;
    $rtn_date = date ( 'm/Y' , $rtn_date );

    return $rtn_date;

   }

   function mongodate_to_print($date) {
    $check_year = (int) date('Y', $date->sec);
    if ($check_year > 1970)
        return date('d/m/Y', $date->sec);
}


   function vinculadas($parameter=null){

    $data['base_url'] = $this->base_url;
    $data['title'] = 'Consulta por CUIT';
    $data['logobar'] = $this->ui->render_logobar();   
    $data['vinculadas_list'] = $this->browse_vinculadas();     

    echo $this->parser->parse('afip/vinculadas', $data, true, true);        
}


function browse_vinculadas(){
   $rtn = $this->consultas_model->vinculadas();
   $new_list = array();
   foreach ($rtn as $key => $value) {
             # code...             

      $new_list['cuit'] = $value['cuit'];
      $new_list['status'] = $value['status'];              

      $string .=  "<li>C.U.I.T.: <strong>" . $value['cuit'] . "</strong> Estado: <strong>" . strtoupper($value['status']) . "</strong> <a href='".$this->module_url."consultas/source/".$value['cuit']."' alt='source ".$value['cuit']."' target='_blank'><i class='fa fa-plus'></i></a></li>";               
  }
  
  return $string;




}


    function Gen_url($url = null, $size = '9', $level = 'H') {
        if ($url) {
            $url_gen = base64_decode(urldecode($url));
        }

        if ($this->input->post('url')) {
            $url_gen = $this->input->post('url');
            $size = ($this->input->post('size')) ? $this->input->post('size') : 9;
            $level = ($this->input->post('level')) ? $this->input->post('level') : 'H';
        }

        $data = $this->gen($url_gen, $size, $level);
        //echo "<img src='".$this->module_url."gen_url/".base64_encode($url_gen)."' width='100' height='100'/>";
        //echo base64_encode($url_gen);
    }
    
    function Gen($data, $size = '9', $level = 'H') {
        $config['cachedir'] = 'application/modules/qr/cache/';
        if (!is_writable($config['cachedir'])) {
            show_error($config['cachedir'] . ' is not writable');
        }
        $config['errorlog'] = 'application/modules/qr/log/';
        if (!is_writable($config['errorlog'])) {
            show_error($config['errorlog'] . ' is not writable');
        }
        $this->load->library('qr/ciqrcode', $config);
        $params['data'] = $data;
        $params['level'] = $level;
        $params['size'] = $size;
        header("Content-Type: image/png");
        $this->ciqrcode->generate($params);
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
     
     $renderData['data']=$this->api->por_letra('array');
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
 function isPyme($mode=null){
    //  $this->load->module('afip');
     $this->load->module('afip/api');
     
     $renderData['data']=$this->api->isPyme('array');
    $template='afip/table_isPyme';     

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
        $renderData['title'] = "Es Pyme";
        $renderData['id'] = "Es_Pyme";
        $template="dashboard/widgets/box_warning.php";
	   return $this->parser->parse($template, $renderData,true,true);
     break;
    }
 }
/**
 * Consulta CUIT
 * 
 */
  function consulta_cuit(){
      $this->load->view('afip/consulta_cuit');
  }


  /*UPDATE*/
  function update_rventanilla_1273_recursive(){
    $this->seti_model->update_ready_queue_recursive();    
  }
  function chart_F1272xSemana() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Tendencia Semanas";
        $data['json_url'] = $this->base_url . 'afip/api/F1272xSemana/json';
        $data['class'] = "data_lines";
        return $this->parser->parse('afip/charts', $data, true, true);
    }
}//class
