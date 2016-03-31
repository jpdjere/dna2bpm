<?php
/**
* CONTROLADOR DEL MODULO POA
* 
* POSEE 4 FUNCIONALIDADES.
* 
* -> PARAMETRIZACION Y SETEO DE LAS AREAS, COMPONENTES Y SUBCOMPONENTES
* -> VER EL DETALLE DE UN POA CARGADO
* -> CARGAR UN POA SUBIENDO UN ARCHIVO EXCEL
* -> CARGAR UN POA LINEA POR LINEA A TRAVES DEL FORMULARIO.
* 
* 
* LOS SCRIPTS DE MANEJO DE LA UI ESTÁN INVOCADOS EN EL DASHBOARD
* 
* @author Luciano Menez <lucianomenez1212@gmail.com>
* @date May 10, 2015
*/
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Poa extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->model('model_poa_list');
        $this->load->model('model_pacc');
        session_start();
    }

    //MAIN FUNCTION
    function Index() {
        $this->dashboard_navegacion_poa();
    }
    
    function form() {
        $this->dashboard_form_poa();
    }
    function carga_componentes() {
        $this->dashboard_parametrizacion();
    }
  
    function dashboard_poa() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_poa.json');
    }
    //DASHBOARD DE CARGA LINEA POR LINEA
    function dashboard_form_poa() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_form_poa.json');
    }
    //DASHBOARD DEL SETEO COMPONENTES Y SUBCOMPONENTES
    function dashboard_parametrizacion() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_parametrizacion.json');
    }
    //DASHBOARD DE NAVEGACION
    function dashboard_navegacion_poa() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_navegacion_poa.json');
    }
   
    function cargar_poa(){
      
   // $this->session->set_userdata('poa_array', array());
    $_SESSION['poa_array'] = array();
    $this->forms();
    
  }

    //WIDGET DE NAVEGACION
    function navegacion_poa(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = "Navegación POA";
        $renderData['reportes'] = $this->model_poa_list->lista_cargados_poa();
        $renderData['content']= $this->parser->parse('navegacion-poa', $renderData, true, true);

        return $this->dashboard->widget($template, $renderData);
    }
  
    //PROCESAMIENTO DEL FORMULARIO POSTEADO
    function save(){
      
       $poa_array = $_SESSION['poa_array'];
       $data = array();
       $data = $this->input->post();
       
       $data['CODIGO'] = "nuevo";
       $data['DESCRIP'] = "nuevo";
       
       $data['CONTRATADO'] = "nuevo";
       $data['COSTO_UNI_USD'] = "nuevo";
      
       $data['USD_TI_BID'] = $data['PESO_TI_BID'] / $data['COTIZACION'];
       $data['USD_TI_BNA'] = $data['PESO_TI_BNA']/ $data['COTIZACION']; 
       $data['USD_TI_PYME'] =  $data['PESO_TI_PYME'] / $data['COTIZACION']; 
       $data['USD_TII_BID'] =  $data['PESO_TII_BID'] / $data['COTIZACION']; 
       $data['USD_TII_BNA'] =  $data['PESO_TII_BNA'] / $data['COTIZACION']; 
       $data['USD_TII_PYME'] = $data['PESO_TII_PYME']/ $data['COTIZACION']; 
       $data['USD_TIII_BID'] = $data['PESO_TIII_BID']/ $data['COTIZACION']; 
       $data['USD_TIII_BNA'] = $data['PESO_TIII_BNA']/ $data['COTIZACION']; 
       $data['USD_TIII_PYME'] = $data['PESO_TIII_PYME']/ $data['COTIZACION']; 
       $data['USD_TIV_BID'] = $data['PESO_TIV_BID'] / $data['COTIZACION']; 
       $data['USD_TIV_BNA'] =  $data['PESO_TIV_BNA']/ $data['COTIZACION']; 
       $data['USD_TIV_PYME'] =   $data['PESO_TIV_PYME']/ $data['COTIZACION'];
       $data['USD_TOTFUE_BID'] =  $data['PESO_TOTFUE_BID'] / $data['COTIZACION']; 
       $data['USD_TOTFUE_BNA'] =   $data['PESO_TOTFUE_BNA'] / $data['COTIZACION']; 
       $data['USD_TOTFUE_PYME'] = $data['PESO_TOTFUE_PYME'] / $data['COTIZACION']; 
       $data['USD_TOTAL'] =  $data['PESO_TOTAL'] / $data['COTIZACION'];
       
       $poa_array[]= $data;
       
       $_SESSION['poa_array'] = $poa_array;
       
       if (isset($data['finish'])){
           
        $lines = count($_SESSION['poa_array']);   

        foreach ($_SESSION['poa_array'] as &$line){
            unset($line['finish']);
        }
  
        $this->model_poa_list->put_array_POA($_SESSION['poa_array'], $lines, $data['COTIZACION'], $data['COTIZACION']);   
        redirect($this->module_url. 'poa'); 
       }else{
       redirect($this->module_url. 'poa/form');
       } 
    }
  
    //FUNCION PARA VER UN REPORTE, DESDE UN DASHBOARD LIMPIO
    function ver_poa($id = null){
        $data =$this->input->post();
        $this->load->module('dashboard');
        $renderData['title']='Resultado de la carga';
        $renderData['base_url']=$this->base_url;
        $reporte = $this->model_poa_list->detalle_poa($data['id']);
        $reporte =($reporte[0]);
        unset($reporte['filename']);
        unset($reporte['date']);
        unset($reporte['_id']);
        unset($reporte[0]);
        unset($reporte[1]);
        unset($reporte[2]);
        unset($reporte[3]);
        unset($reporte[4]);
        unset($reporte[5]);
        
        $renderData['section'] = $this->data_table($reporte);
        $renderData['reporte'] = $reporte;
        
        $customData['css'] = array(
            $this->base_url . 'ui/assets/css/demo.css' => 'MAP CSS'
        );
        
        $customData['js'] = array(
            $this->base_url . 'pacc/assets/jscript/app.js' => 'MAP CSS',
            $this->base_url . 'pacc/assets/jscript/jquery.colorize-1.7.0.js' => 'Script'
        );
        
        $customData['tiles']=$this->parser->parse('fixed-table', $renderData, true, true);
        $customData['tiles_after']=$this->parser->parse('fixed-table-dos', $renderData, true, true);
        Modules::run('dashboard/dashboard','pacc/json/layout_2cols_collapsed_vacio.json',false,$customData);
}
  
    function cargar_elemento($area, $comp = null, $codigo, $descripcion, $elemento = "1"){
      
        $this->load->module('dashboard');
        
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;

        $renderData['areas'] = $this->model_pacc->get_areas();
    //    $this->model_pacc->setup_componentes();
       // $this->model_pacc->setup_subcomponentes();
       
       
       $descripcion = urldecode($descripcion);
    
        if ($elemento == "1"){
            $param['idrel'] =$area;
            $param['comp'] =$codigo;
            $param['descripcion_comp'] = $descripcion;
            $this->model_pacc->add_componente($param);
        }else{
            $param['idrel'] =$comp;
            $param['scomp'] =$codigo;
            $param['descripcion_scomp'] = $descripcion;
            $this->model_pacc->add_subcomponente($param);
        }
        
        foreach ($renderData['areas'] as &$area){
            $area['componentes'] = $this->model_pacc->get_componentes($area['id']);
            foreach ($area['componentes'] as &$componente){
               $componente['subcomponentes'] = $this->model_pacc->get_subcomponentes($componente['comp']);
            }
              
        }
        echo $this->parser->parse('carga-componentes', $renderData, true, true);
       
  }
  
    function eliminar_elemento($elemento = "1", $comp = null, $scomp =null){
      
        $this->load->module('dashboard');
        
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        
        if ($elemento == "1"){
            $this->model_pacc->delete_componente($comp);
        }else{
            $this->model_pacc->delete_subcomponente($scomp);
        }
        
        $renderData['areas'] = $this->model_pacc->get_areas();
        foreach ($renderData['areas'] as &$area){
            $area['componentes'] = $this->model_pacc->get_componentes($area['id']);
            foreach ($area['componentes'] as &$componente){
               $componente['subcomponentes'] = $this->model_pacc->get_subcomponentes($componente['comp']);
            }
              
        }
        echo $this->parser->parse('carga-componentes', $renderData, true, true);
       
  }
  
    //PRESENTACION DEL FORMULARIO
    function forms($id = NULL){
      
        $this->load->module('dashboard');
        $renderData['title'] = "Test POA";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['areas'] = $this->select_areas();
        $renderData['componentes'] = $this->select_componentes();
        $renderData['subcomponentes'] = $this->select_subcomponentes();
        
        return $this->parser->parse('forms-poa', $renderData, true, true);
      
    }

    //TABLA DE ARRIBA DEL REPORTE
    function fixed_table(){
      
        $this->load->module('dashboard');
        $renderData['title'] = "Test POA";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template="dashboard/widgets/box_info.php";
        
        $renderData['listado'] = $this->model_poa_list->lista_cargados_poa();
        
        $reporte = $this->model_poa_list->detalle_poa('5626960395bc9d3761ad3977');
        $reporte =($reporte[0]);
        unset($reporte['filename']);
        unset($reporte['date']);
        unset($reporte['_id']);
        unset($reporte[0]);
        unset($reporte[1]);
        unset($reporte[2]);
        unset($reporte[3]);
        unset($reporte[4]);
        unset($reporte[5]);
        
        $renderData['section'] = $this->data_table($reporte);
        $renderData['content']= $this->parser->parse('fixed-table', $renderData, true, true);
            
        return $this->dashboard->widget($template, $renderData);
  }
  
    //TABLA DE DATOS, DIVIDIDA EN 4 TABS
    function fixed_table_dos(){
      
        $this->load->module('dashboard');
        $renderData['title'] = "Test POA";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template="dashboard/widgets/box_info.php";
        
        $reporte = $this->model_poa_list->detalle_poa('5626960395bc9d3761ad3977');
        $reporte =($reporte[0]);
        
        unset($reporte['filename']);
        unset($reporte['date']);
        unset($reporte['_id']);
        unset($reporte[0]);
        unset($reporte[1]);
        unset($reporte[2]);
        unset($reporte[3]);
        unset($reporte[4]);
        unset($reporte[5]);
     
        $renderData['reporte'] = $reporte; 

        return $this->parser->parse('fixed-table-dos', $renderData, true, true);
 
  }

    //FORMATEO DE LA INFORMACION PARA SEPARAR POR AREAS
    function data_table($data = null) {
        
        $rtnArr=array();
        $section=array();
        $item=array();
        $length=1;
        $first=array_shift($data);
        $area=$first['AREA'];
        $section=$first;
        //----recorremos para sacar los items
        foreach ($data as $arr){
            if($arr['AREA']<>''){
                if($area<>$arr['AREA']){
                    $section['length']=$length;
                    $section['item']=$item;
                    //---agregamos al main array
                    $rtnArr[]=$section;
                    //---reseteamos los arrays y valores
                    $area=$arr['AREA'];
                    $section=$arr;
                    $item=array();
                    $length=1;
                }
            }
            //---incrementamos el length y agregamos al array
            $length++;
            $item[]=array(
                'aCOMP'=>$arr['COMP'],
                'aSCOMP'=>$arr['SCOMP'],
                'aDESCRIP'=>$arr['DESCRIP'],
                'aCONTRATADO'=>$arr['CONTRATADO'],
                );
        }
        //---agrega el último
        $section['length']=$length;
        $section['item']=$item;
        //---agregamos al main array
        $rtnArr[]=$section;

        ini_set('xdebug.var_display_max_depth', 5);
        // var_dump($rtnArr);exit;
        return $rtnArr;
        $data[0] = array(
            'area_responsable'=>'Apoyo Directo a Empresas',
            'item_nombre'=>'1.1',
            'item'=> $this->data_table0($data),
            'length' => count($this->data_table0($data))
        );
        $data[1] = array(
            'area_responsable'=>'Apoyo a la actividad emprendedora',
            'item_nombre'=>'1.3',
            'item'=> $this->data_table1($data),
            'length' => count($this->data_table1($data))
        );
        $data[2] = array(
            'area_responsable'=>'Plataforma de apoyo a las Mipymes',
            'item_nombre'=>'2.1',
            'item'=> $this->data_table2($data),
            'length' => count($this->data_table2($data))
        );       
        $data[3] = array(
            'area_responsable'=>'Planificación, Monitoreo y Evaluación',
            'item_nombre'=>'2.2',
            'item'=> $this->data_table3($data),
            'length' => count($this->data_table3($data))
        );
        $data[4] = array(
            'area_responsable'=>'A Definir',
            'item_nombre'=>'2.3',
            'item'=> $this->data_table4($data),
            'length' => count($this->data_table4($data))
        );
          return $data;
        }  

    //ELEMENTOS QUE SE RECARGAN DINAMICAMENTE DE LA UI
    function select_componentes($area_id = null){
         $this->load->module('dashboard');
         $renderData['data'] = $this->model_pacc->get_componentes($area_id);
         return $this->parser->parse('select_componentes', $renderData, true, true);
    }
    function reload_componentes($area_id = null){
         $this->load->module('dashboard');
         $renderData['data'] = $this->model_pacc->get_componentes($area_id);
         echo $this->parser->parse('select_componentes', $renderData, true, true);
    }
    function select_subcomponentes($comp = null){
         $this->load->module('dashboard');
         $renderData['data'] = $this->model_pacc->get_subcomponentes($comp);
         return $this->parser->parse('select_subcomponentes', $renderData, true, true);
    }
    function reload_subcomponentes($comp = null){
         $this->load->module('dashboard');
         $renderData['data'] = $this->model_pacc->get_subcomponentes($comp);
         echo $this->parser->parse('select_subcomponentes', $renderData, true, true);
    }
    function select_areas(){
         $this->load->module('dashboard');
         $renderData['data'] = $this->model_pacc->get_areas();
         return $this->parser->parse('select_areas', $renderData, true, true);
    }


    function parametrizacion_componentes(){
      
        $this->load->module('dashboard');
        $renderData['title'] = "Parametrizacion de Componentes";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template="dashboard/widgets/box_info.php";
    
         
        $renderData['areas'] = $this->model_pacc->setup_areas();
        $this->model_pacc->setup_componentes();
        $this->model_pacc->setup_subcomponentes();
        
        $renderData['componentes_all'] = $this->model_pacc->get_componentes(); 
        $renderData['subcomponentes_all'] =$this->model_pacc->get_subcomponentes();

        
        foreach ($renderData['areas'] as &$area){
            $area['componentes'] = $this->model_pacc->get_componentes($area['id']);
            $area['select_areas'] = $this->model_pacc->get_areas();
            foreach ($area['componentes'] as &$componente){
               $componente['subcomponentes'] = $this->model_pacc->get_subcomponentes($componente['idrel']);
            }
                
        }
        
        $renderData['content']= $this->parser->parse('carga-componentes', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
  }
  
 
}
