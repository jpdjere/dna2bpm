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
         
         list($desde, $hasta) = explode("-", trim($viatico_data[0]['event-interval']));
         
        $desde =  $this->rtn_date_format($desde);
        $hasta =  $this->rtn_date_format($hasta);
        
       
        $datetime1 = new DateTime($desde);
        $datetime2 = new DateTime($hasta);
        $interval = $datetime1->diff($datetime2);
        $diff =  (int)$interval->format('%R%a');
        
        /*Sum Gastos*/
        $sum_gatos = (float)$viatico_data[0]['gastos_eventuales'];
        
        
        if($diff==0)
         $diff = 1;
        
                
                if($key=='agentes'){
                 $sum_gatos_agentes = 0;
                   $table = "";
                   foreach($value as $anyone){
                      
                       if($anyone!=""){
                            $id_agentes = array('dni'=> $anyone);
                            $agentes = $this->forms_model->buscar_un_agente($id_agentes);
                            
                           
                            $importe = (float)$this->escalas($viatico_data[0]['provincia']); 
                            $importe_total= $importe*$diff;
                           
                            
                            $print_nombre = $agentes[0]['nombre'] ." ".  $agentes[0]['apellido'];
                            if(strlen($print_nombre)>25){
                             $print_nombre =  $agentes[0]['apellido'];
                            }
                            
                            
                            $sum_gatos_agentes+=$importe_total;
                            
                            
            
                            
                            $table .= '<tr>
                        		<td colspan=2 style="border-left: 2px solid #212121" height="20 align="center"><font size="2">'.strtoupper($print_nombre).'</font></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"><font size="2">'. $anyone.'</font></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"><font size="2">'.$agentes[0]['modalidad'].'</font></td>
                        		<td align="center"><font size="2">'.$agentes[0]['nivel_y_grado'].'</font></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"><font size="2">viaticospymes@produccion</font></td>
                        		<td style="border-right: 1px solid #212121; align="center"><font size="2">43350</font></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"><font size="2">$'.number_format($importe).'.00</font></td>
                        		<td style="border-left: 1px solid #212121; border-right: 2px solid #212121" align="center"><font size="2">$'.number_format($importe_total).'.00</font></td>
                        		<td align="center"></td>
                        	</tr>
                        	<tr>
                        		<td colspan=2 style="border-left: 2px solid #212121" height="10"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td style="border-right: 1px solid #212121; align="left"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 2px solid #212121"></td>
                        		<td></td>
                        	</tr>';
                            
                       }
                   }
                    
                }
                
                
                
              
               $sum_gatos = $sum_gatos+$sum_gatos_agentes;
                
               $data['desde'] = $this->rtn_date_format($desde, "dia") ;
               $data['hasta'] = $this->rtn_date_format($hasta, "dia") ;
               $data['desde_hora'] = $this->rtn_date_format($desde, "hora") ;
               $data['hasta_hora'] = $this->rtn_date_format($hasta, "hora") ;
               $data['duracion'] = $diff;
               $data['gastos_eventuales'] = number_format($viatico_data[0]['gastos_eventuales']);
               $data['importe_pasaje'] = number_format($viatico_data[0]['importe_pasaje']);
               
                
                $data['agentes']=$table;
            
                $data[$key]=$value;
        }
        
        
        if($viatico_data[0]['gastos_eventuales']){
                  $table .= '<tr>
                        		<td colspan=2 style="border-left: 2px solid #212121" height="20 align="center"><font size="2">EVENTUALES</font></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"></td>
                        		<td align="center"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"></td>
                        		<td style="border-right: 1px solid #212121; align="center"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 2px solid #212121" align="center"><font size="2">$'.number_format($viatico_data[0]['gastos_eventuales']).'.00</font></td>
                        		<td align="center"></td>
                        	</tr>
                        	<tr>
                        		<td colspan=2 style="border-left: 2px solid #212121" height="10"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td style="border-right: 1px solid #212121; align="left"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 2px solid #212121"></td>
                        		<td></td>
                        	</tr>';
                }
                
        /*TOTALES*/
        
         $table .= '<tr>
                        		<td colspan=2 style="border-left: 2px solid #212121" height="20 align="center"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"></td>
                        		<td align="center"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"></td>
                        		<td style="border-right: 1px solid #212121; align="center"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121" align="center"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 2px solid #212121" align="center"><font size="2">$'.number_format($sum_gatos).'.00</font></td>
                        		<td align="center"></td>
                        	</tr>
                        	<tr>
                        		<td colspan=2 style="border-left: 2px solid #212121" height="10"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td style="border-right: 1px solid #212121; align="left"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 1px solid #212121"></td>
                        		<td style="border-left: 1px solid #212121; border-right: 2px solid #212121"></td>
                        		<td></td>
                        	</tr>';
                
               
        $data['agentes']=$table;
        echo $this->parser->parse('print_viaticos_xls',$data,true,true);
    }
    
    
    function escalas($provincia){
     
      
    $valor = 0;
     
    $arr_noroeste = array("Jujuy", "Salta", "Tucuman", "Catamarca","La Rioja");
    $arr_noreste = array("Misiones", "Corrientes", "Entre Rios", "Formosa", "Chaco");
    $arr_cuyo = array("San Juan", "Mendoza", "San Luis");
    $arr_centro = array("Cordoba", "Santiago del Estero", "Santa Fe" , "La Pampa");
    $arr_sur = array("Neuquen", "Rio Negro", "Chubut", "Santa Cruz" , "Tierra del Fuego");
    $arr_metro = array("Buenos Aires", "CABA"); 
    
    if (in_array($provincia, $arr_noroeste)){$valor = 998;}
    if (in_array($provincia, $arr_noreste)){$valor = 698;}
    if (in_array($provincia, $arr_cuyo)){$valor = 998;}
    if (in_array($provincia, $arr_centro)){$valor = 833;}
    if (in_array($provincia, $arr_sur)){$valor = 1222;}
    if (in_array($provincia, $arr_metro)){$valor = 698;}
    
    return $valor;
     
    }
    
    function rtn_date_format($param, $param2=null){
     
     
      list($fecha, $hora) = explode(" ", trim($param));
         $date = str_replace('/', '-', $param); 
        
        $rtn = $date;
         
         if($param2=="dia")
          $rtn =  $fecha;
          
          if($param2=="hora")
          $rtn =  $hora;
         
         return $rtn;
     
    }
    
    
    
    
    

    
}//class
