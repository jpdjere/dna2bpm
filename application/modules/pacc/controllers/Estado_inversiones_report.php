<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * inventory
 * 
 * Description of the class
 * 
 * @author Martin González 
 * @date    Apr 20, 2015
 */
class estado_inversiones_report extends MX_Controller {

    function __construct() {
        parent::__construct();
         $this->load->model('user/user');
         $this->load->model('user/group');
         //$this->load->model('inventory_model');
         $this->user->authorize('modules/pacc');
         $this->load->library('parser');
        //         $this->load->library('ui');
        // //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'pacc/';
// ;
// //----LOAD LANGUAGE
         $this->idu = (float) $this->session->userdata('iduser');
// //---config
         $this->load->config('pacc/config');
// //---QR
         //$this->load->module('qr');
    }

    /*
     * Presentamos menu de acciones: info Checkin
     */

    function Index(){

     	//Modules::run('dashboard/dashboard','inventory/json/inventory.json');
	$this->user->authorize();
	$this->load->module('dashboard');
	$this->dashboard->dashboard('pacc/json/estado_inversion_report.json');
    
    }
    
    
    function Dashboard() {

        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $this->config->load('pacc/config');
        echo 'Pasa!!';
        //$cpData['module_url_encoded'] = $this->qr->encode($this->module_url);
        $cpData['module_url_encoded'] = Modules::run('qr/qr/encode',$this->module_url);
        $cpData['title'] = 'Mesa de Entradas Digital';
        //---Users & Groups
        $groups = $this->config->item('groups_allowed');

        $cpData['groups'][] = array(
            'idgroup' => '',
            'name' => $this->config->item('select_group'),
        );
        foreach ($groups as $idgroup) {
            $group = $this->group->get($idgroup);
            $cpData['groups'][] = (array) $group;
        }
//----select 1st group and load
        $users = $this->user->getbygroup($groups[0]);
//var_dump($users);exit;

        $cpData['users'] = array();

        return $this->parser->parse('index', $cpData, true,true);

        //$this->ui->compose('index', 'bootstrap.ui.php', $cpData);

    }
    
    
    
    
    
    
    function estadodeinversiones_principal(){
        
        
        $model = 'estadodeinversiones_model';
        
        $customData['base_url'] = $this->base_url.'pacc/';
        
        
        return $this->parser->parse('pacc/EstadoDeInversiones_view',$customData,true,true); 
        
        
        
        
    }
    
     function estadodeinversiones_list(){
        $model = 'estadodeinversiones_model';
        $datefin = $this->input->post('fechafin');
        $dateinit = $this->input->post('fechainicio');
        $datefin = str_replace("/","-",$datefin);
        $dateinit = str_replace("/","-",$dateinit);
        $datefin = date("Y-m-d", strtotime($datefin));
        $dateinit = date("Y-m-d", strtotime($dateinit));
        $datefin = $datefin." 23:59:59";
        $dateinit = $dateinit." 00:00:00";
        //var_dump($datefin);
        //var_dump($dateinit);
        
        $ap = $this->load->model($model)->inversion_list_ap();
        $periodo = array();
        $i =0;
        foreach ($ap as $prestamo){
            $periodo[$i]['AP'] = $prestamo['AP'];
            $periodo[$i]['Descripcion'] = $prestamo['Descripcion'];
            $periodo[$i]['PO_BID'] = '';
            $periodo[$i]['PO_AL']= '';
            $periodo[$i]['PV_BID']= '';
            $periodo[$i]['PV_AL']= '';
            
            //echo 'ap='.$prestamo['AP'].'</br>';
            $categoria = $prestamo['AP'];
            //echo 'Descripcion='.$prestamo['Descripcion'].'</br>';
            $fuente = '11';
            $result = $this->load->model($model)->inversion_calc_monto($dateinit,$datefin,$categoria,$fuente);
            $result_prev = $this->load->model($model)->inversion_calc_monto_prev($datefin, $categoria,$fuente);
            $periodo[$i]['Actual_AL'] =$result;
            $periodo[$i]['Anterior_AL'] =$result_prev;
            //var_dump($result);
            $fuente = '22';
            $result = $this->load->model($model)->inversion_calc_monto($dateinit,$datefin,$categoria,$fuente);
            $result_prev = $this->load->model($model)->inversion_calc_monto_prev($datefin, $categoria,$fuente);
            $periodo[$i]['Actual_BID'] =$result;
            $periodo[$i]['Anterior_BID'] =$result_prev;
            //var_dump($result);
            $i++;
        }
        $periodo['borrado']=0;
        
        
        
        
        //Agregar llamado a la nueva vista!!!
        var_dump($periodo);
        
        //Esta función graba el reporte en mongo.
        $status = $this->load->model($model)->put_array_inversion($periodo);
        
        
     }
     
     function estinv_list() {
        
        $model='estadodeinversiones_model';
        
        $customData = array();
        $default_dashboard = 'poa_list_view';
        // $default_dashboard = 'reports';
        $lista = array();
        $lista = $this->load->model($model)->lista_cargados();
        
        $count = $this->load->model($model)->count_cargados();
        
        //$customData= $lista;
        $customData['tabla'] = $this->gen_table($lista);
        $customData['count'] = $count;
        
        return $this->parser->parse('pacc/poa_list_view',$customData,true,true);    
    }
    
    function gen_table($array){
        
        $rtn = '';
        
        foreach ($array as $list) {
            
            $rtn = $rtn.'<tr><a target="_blank">'.$list.'</a>|--|<a href="'.$this->module_url.'estado_inversiones_report/borrar/'.$list.'" class="btn btn-primary btn-xs" role="button">Borrar</a></tr></br>';
        }
        return $rtn;
    }
    function borrar($filename){
        //echo 'Filename:'.$filename;
        $model = 'estadodeinversiones_model';
        $result = $this->load->model($model)->borrar_db($filename);   
        $this->user->authorize();
	$this->load->module('dashboard');
        $this->dashboard->dashboard('pacc/json/estado_inversion_report.json');
    }
    
    
    
    
}
?>