<?php

// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');
/**
 * "ventanilla electrónica" de la AFIP
 * 
 * @autor Diego Otero
 * 
 * @version     1.0 
 * 
 * 
 */

class Consultas extends MX_Controller {
   
    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';

        
        #LIBRARIES          
        $this->load->library('parser');
        $this->load->library('dashboard/ui');
        $this->load->library('pdf/pdf');
        
        #MODELS
        $this->load->model('consultas_model');

        
        #CREDENTIALS
        $this->idu = (int) $this->session->userdata('iduser');
        $this->user->authorize();
        


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
        
        $data = isset($data)? $this->input->post('cuit') : '20180826123';#30712164707;#30714571725;

        #$data = (int)$data;


        $rtn = $this->consultas_model->buscar_cuits_registrados($data);      


        if(empty($rtn)){
            $rtn['tipo_socio'] = 0;
        } else {
          $vinculados = $this->vinculados($data); 
          
          $rtn['vinculados'] = " <table width='100%' class='table table-hover'>" . $vinculados. "</table>";
          
         }

      if(!$debug) {
        $this->output->set_content_type('json','utf-8');
        echo json_encode($rtn);

    }else{
        var_dump($rtn);
    }
}    

function vinculados($parameter) {


        
        $vinculados_info = $this->consultas_model->buscar_cuits_vinculados($parameter);

        $rtn = $this->parser->parse('consultas/vinculados_table_head_view', $parameter, true, true);  

        foreach ($vinculados_info as $key => $value) {          

          $data = array();
          $data['CUIT_VINCULADO'] = $value['anexo']['CUIT_VINCULADO'];
          $data['RAZON_SOCIAL_VINCULADO'] = $value['anexo']['RAZON_SOCIAL_VINCULADO'];
          $data['TIPO_RELACION_VINCULACION']= $value['anexo']['TIPO_RELACION_VINCULACION'];
          $data['PORCENTAJE_ACCIONES'] = ($value['anexo']['PORCENTAJE_ACCIONES']) ? $value['anexo']['PORCENTAJE_ACCIONES']*100:0;          
          
          $rtn .= $this->parser->parse('consultas/vinculados_table_view', $data, true, true); 

         }
         
        if(empty($vinculados_info))
          $rtn = '<tr><td>NO TIENE VINCULADOS</td></tr>';

        return $rtn;
}    



function queue($parameter=null){

    $data['base_url'] = $this->base_url;
    $data['title'] = 'Consulta por CUIT';
    $data['logobar'] = $this->ui->render_logobar();   
    $data['queue_list'] = $this->show_queue($parameter);     

    echo $this->parser->parse('afip/queue', $data, true, true);        
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

    echo $this->parser->parse('source', $data, true, true);


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

function certificado($parameter){

    $data = (int)$parameter;
    $rtn = $this->consultas_model->cuits_certificados($data);

        #   var_dump($rtn);

    $filename = "sepyme_certificado_" . $rtn->cuit.".pdf";

    if(!$rtn->cuit){
        echo "error la C.U.I.T. " . $parameter . " no fue beneficiada con 'IVA – Cancelación trimestral'";
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
        $new_list['url'] = $this->base_url;
        $new_list['cuit'] = $rtn->cuit;
        $new_list['razon_social'] = $rtn->denominacion;

        
        $new_list['fecha_validez'] = $this->fix_fecha_vencimiento($rtn->mesCierre);#$fecha_validez;
        $new_list['fecha_emision'] = $this->mongodate_to_print($rtn->result['date']);

        //$new_list['logobar'] = $this->ui->render_logobar();
        
        foreach ($rtn->result as $key => $value) {
            $new_list[$key] = $value;
        }
        $new_list['descripcionActividadPrincipal'] = $rtn->descripcionActividadPrincipal;

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);  
        
        $this->pdf->set_paper('a4', 'portrait');
        $this->pdf->parse('pdf', $new_list);
        $this->pdf->render();
        $this->pdf->set_base_path($this->base_url.'/afip/assets/css/style.css');       
        $this->pdf->stream($filename);


        if ($this->pdf->stream) {
           $this->pdf->stream($filename);
       } else {
           return $this->pdf->output();
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
}//class
