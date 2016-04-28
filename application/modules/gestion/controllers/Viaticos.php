<?php

// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');
/**
 * Actualiza los archivos segun la rama configurada
 * 
 * @autor Diego Otero
 * 
 * @version 	1.0 
 * 
 * 
 */
 

 
class Viaticos extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->library('parser');
        $this->load->library('dashboard/ui');
        
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->model('ssl/ssl_model');
        $this->load->model('msg');
        $this->load->library('phpmailer/phpmailer');
        
         /* LOAD MODEL */
        $this->load->model('forms_model');
        $this->load->model('app');
        
        //$this->user->authorize();
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
        //error_reporting(E_ALL);

    }
    
    function Index(){
       $data['base_url']=$this->base_url;
       $data['title']='SOLICITUD DE ANTICIPO DE VIATICOS Y ORDENES DE PASAJE';
       $data['logobar']= $this->ui->render_logobar();
        
        
       /*Agentes*/
        $data_select = NULL;
        $agents_data = $this->forms_model->buscar_agentes_registrados();
        
        foreach ($agents_data as $each) {
           $data_select .= '<option value='.$each['dni'].'>'.$each['apellido'].' '.$each['nombre'].' </option>';
            
        }
        
        $data['groupagents'] = $data_select;
        
        
      echo $this->parser->parse('form_viaticos',$data,true,true);
    

    }
    
    
    
    //=== Create  buttons groups on ajax call
    
    function get_option_button(){
     $sel=$this->input->post('sel');
     
     $ret = NULL;
     $groups = $this->forms_model->buscar_agentes_registrados();
              
     if($sel=='all'){
         foreach($groups as $g){
              $ret.= "<button type='button' data-groupid='{$g['dni']}' class='btn btn-default btn-xs'><i class='fa fa-times-circle'></i> {$g['nombre']}</button>";
         }
     }else{
         // just one
          foreach($groups as $g){
              if($g['dni']==$sel){
              $ret.= "<input name='agentes[]' type='hidden' value='{$g['dni']}'><button type='button' data-groupid='{$g['dni']}' class='btn btn-default btn-xs'><i class='fa fa-times-circle'></i> {$g['nombre'] } {$g['apellido'] }</button>";
              break;
              }
          }
     }
     echo $ret;
    }
    
    
    
    function process(){
         $data=$this->input->post();
         $rtn = $this->forms_model->save($data);
         echo $rtn;//json_encode(array('status'=>'msg_ok_' . $rtn));
        
    }
    
    
    function print_viatico($parameter){
        
     
       $data['base_url']=$this->base_url;
       $data['title']='SOLICITUD DE ANTICIPO DE VIATICOS Y ORDENES DE PASAJE | Imprimible';
       $data['logobar']= $this->ui->render_logobar();
        
        $query = array('id'=>(int)$parameter);
        
        $viatico_data = $this->forms_model->buscar_viaticos($query);
        
        foreach ($viatico_data[0] as $key=>$value) {
                if($key=='agentes'){
                   $table = "";
                   foreach($value as $anyone){
                       if($anyone!=""){
                            $id_agentes = array('dni'=> $anyone);
                            $agentes = $this->forms_model->buscar_un_agente($id_agentes);
                            
                            /*$table .= "<tr><td>".$agentes[0]['nombre'] ." ".  $agentes[0]['apellido']. "</td>
                            <td>" . $anyone."</td><td>".$agentes[0]['modalidad']."</td>
                            <td>". $agentes[0]['nivel_y_grado']."</td>
                            <td>".$agentes[0]['nivel_y_grado']."</td>
                            <td></td><td></td><td></td><td></td></tr>"; */
                            
                            
                            $table .= '<tr>
		<td colspan=2 style="border-left: 2px solid #212121" height="20" align="left">'.$agentes[0]['nombre'] ." ".  $agentes[0]['apellido'].'</td>
		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="left">'. $anyone.'</td>
		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="left">'.$agentes[0]['modalidad'].'</td>
		<td align="left">'.$agentes[0]['nivel_y_grado'].'</td>
		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="left">viaticospymes@produccion.gob.ar</td>
		<td style="border-right: 1px solid #212121; align="left">43350</td>
		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="left"></td>
		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="left">1102</td>
		<td style="border-left: 1px solid #212121; border-right: 2px solid #212121" align="left">1102</td>
		<td align="left"></td>
	</tr>
	<tr>
		<td colspan=2 style="border-left: 2px solid #212121" height="20" align="left"></td>
		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="left"></td>
		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="left"></td>
		<td align="left"></td>
		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="left"></td>
		<td style="border-right: 1px solid #212121; align="left"></td>
		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="left"></td>
		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="left"></td>
		<td style="border-left: 1px solid #212121; border-right: 2px solid #212121" align="left"></td>
		<td align="left"></td>
	</tr>';
                            
                       }
                   }
                    
                }
                
                $data['agentes']=$table;
            
                $data[$key]=$value;
        }
        echo $this->parser->parse('print_viaticos_xls',$data,true,true);
    }
    
    
    
    

    
}//class
