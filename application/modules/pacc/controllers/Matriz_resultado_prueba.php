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
class matriz_resultado_prueba extends MX_Controller {

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
	$this->dashboard->dashboard('pacc/json/poa_report.json');
    
    }
    
    
    function Dashboard() {

        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $this->config->load('pacc/config');
        //echo 'Pasa!!';
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

    

    
    function prueba_carga_direct() {
        $Arr_name_impacto = array(
            "INDICADORES",
            "UNIDAD DE MEDIDA",
            "LINEA DE BASE", //1 - Valor / AÑO
            "MEDICIONES INTERMEDIAS", //N - Valor / AÑO
            "METAS AL FINAL DEL PROYECTO",//1 - Valor / AÑO
            "FUENTE",
            "OBSERVACIONES"
        );
        
        $Arr_name_resultado = array(
            "INDICADORES",
            "UNIDAD DE MEDIDA",
            "LINEA DE BASE", //1 - Valor / AÑO
            "MEDICIONES INTERMEDIAS", //N - Valor / AÑO
            "METAS AL FINAL DEL PROYECTO",//1 - Valor / AÑO
            "FUENTE",
            "OBSERVACIONES"
        );
        
        $Arr_name_producto = array(
            "PRODUCTOS",
            "UNIDAD DE MEDIDA",
            "LINEA DE BASE",
            "MEDICIONES INTERMEDIAS", //N mediciones
            "METAS AL FINAL DEL PROYECTO",
            "FUENTE"
        );
        
        $array_impacto= array();
        $array_resultado= array();
        $array_producto= array();
        $lines_impacto = 10;
        $lines_resultado = 10;
        $lines_producto = 10;       
        $cols_impacto =6;
        $cols_resultado =6;
        $cols_producto =5;
        
        
        ///IMPACTO
        
        for ($index_l = 1; $index_l <= $lines_impacto; $index_l++) {
            
            
            for ($index_c = 0; $index_c <= $cols_impacto; $index_c++) {
                $var = $index_c;
                $var = utf8_encode($var);
                
                switch ($Arr_name_impacto[$index_c]) {
                    
                    case "LINEA DE BASE":
                        $valor=1000;
                        $año=2010; 
                        $array_impacto[$index_l][$Arr_name_impacto[$index_c]]['valor'] = $valor;
                        $array_impacto[$index_l][$Arr_name_impacto[$index_c]]['año'] = $año;
                        break;
                    case 'MEDICIONES INTERMEDIAS':
                        $valor=1000;
                        $año=2010; 
                        for($r=0; $r < 3; $r++){
                            $array_impacto[$index_l][$Arr_name_impacto[$index_c]][$r]['valor'] = $valor;
                            $array_impacto[$index_l][$Arr_name_impacto[$index_c]][$r]['año'] = $año;
                            $valor = $valor + 500;
                            $año = $año + 1;
                        }
                        break;
                    case "METAS AL FINAL DEL PROYECTO":
                        $valor=1000;
                        $año=2010; 
                        $array_impacto[$index_l][$Arr_name_impacto[$index_c]]['valor'] = $valor;
                        $array_impacto[$index_l][$Arr_name_impacto[$index_c]]['año'] = $año;
                        break;        
                    default:
                        $array_impacto[$index_l][$Arr_name_impacto[$index_c]] = $var;
                        break;
                }
                
                
            }
        }
        
        
        ///Resultado
        
        for ($index_l = 1; $index_l <= $lines_resultado; $index_l++) {
            
            
            for ($index_c = 0; $index_c <= $cols_resultado; $index_c++) {
                $var = $index_c;
                //echo '$index_l:'.$index_l.'  $index_c:'.$index_c.'  $var:'.$var;
                $var = utf8_encode($var);
                switch ($Arr_name_resultado[$index_c]) {
                    
                    case "LINEA DE BASE":
                        $valor=1000;
                        $año=2010; 
                        $array_resultado[$index_l][$Arr_name_resultado[$index_c]]['valor'] = $valor;
                        $array_resultado[$index_l][$Arr_name_resultado[$index_c]]['año'] = $año;
                        break;
                    case 'MEDICIONES INTERMEDIAS':
                        $valor=1000;
                        $año=2010; 
                        for($r=0; $r < 3; $r++){
                            $array_resultado[$index_l][$Arr_name_resultado[$index_c]][$r]['valor'] = $valor;
                            $array_resultado[$index_l][$Arr_name_resultado[$index_c]][$r]['año'] = $año;
                            $valor = $valor + 500;
                            $año = $año + 1;
                        }
                        break;
                    case "METAS AL FINAL DEL PROYECTO":
                        $valor=1000;
                        $año=2010; 
                        $array_resultado[$index_l][$Arr_name_resultado[$index_c]]['valor'] = $valor;
                        $array_resultado[$index_l][$Arr_name_resultado[$index_c]]['año'] = $año;
                        break;        
                    default:
                        $array_resultado[$index_l][$Arr_name_resultado[$index_c]] = $var;
                        break;
                }
                
            }
        
            //PRODUCTO
        }
        for ($index_l = 1; $index_l <= $lines_producto; $index_l++) {
            
            
            for ($index_c = 0; $index_c <= $cols_producto; $index_c++) {
                $var = $index_c;
                //echo '$index_l:'.$index_l.'  $index_c:'.$index_c.'  $var:'.$var;
                $var = utf8_encode($var);
                
                switch ($Arr_name_producto[$index_c]) {
                    case 'LINEA DE BASE':
                        $valor=1000;
                        $año=2010; 
                        for($r=0; $r < 1; $r++){
                            $array_producto[$index_l][$Arr_name_producto[$index_c]][$r]['valor'] = $valor;
                            $array_producto[$index_l][$Arr_name_producto[$index_c]][$r]['año'] = $año;
                            $valor = $valor + 500;
                            $año = $año + 1;
                        }
                        break;
                    
                    case 'MEDICIONES INTERMEDIAS':
                        $valor=1000;
                        $año=2010; 
                        for($r=0; $r < 3; $r++){
                            $array_producto[$index_l][$Arr_name_producto[$index_c]][$r]['valor'] = $valor;
                            $array_producto[$index_l][$Arr_name_producto[$index_c]][$r]['año'] = $año;
                            $valor = $valor + 500;
                            $año = $año + 1;
                        }
                        break;
                    case "METAS AL FINAL DEL PROYECTO":
                        $valor=1000;
                        $año=2010; 
                        $array_producto[$index_l][$Arr_name_producto[$index_c]]['valor'] = $valor;
                        $array_producto[$index_l][$Arr_name_producto[$index_c]]['año'] = $año;
                        break;        
                    default:
                        $array_producto[$index_l][$Arr_name_producto[($index_c )]] = $var;
                        break;
                }
                
                
                
                
                
                
                
                
                
                

                
            }
        
            //var_dump($headerArr[$index_l][]);
            //$result = $this->put_array_POA($id, $container_POA, $headerArr[$index_l]);
        }
        $model = 'model_matriz_resultado';
        //var_dump($array_impacto);
        //var_dump($array_resultado);
        //var_dump($array_producto);
        $result = $this->load->model($model)->put_array_matriz_resultado($array_impacto ,$lines_impacto ,$array_resultado, $lines_resultado ,$array_producto ,$lines_producto);
        $resultado = $this->load->model($model)->lista_cargados();
        var_dump($resultado);
    }
    function borrar_prueba(){
        $model = 'model_matriz_resultado';
        $id_r = "5612dc22a55170171e73e2f6";
        $result = $this->load->model($model)->borrar_matriz_resultado($id_r);
        
        
    }
    function listar_prueba(){
        $model = 'model_matriz_resultado';
     
        $result = $this->load->model($model)->lista_cargados();
        var_dump($result);
        
    }
    
    
    
    
    function editar_prueba(){ 
        $model = 'model_matriz_resultado';
        
        $Arr_name_impacto = array(
            "INDICADORES",
            "UNIDAD DE MEDIDA",
            "LINEA DE BASE", //1 - Valor / AÑO
            "MEDICIONES INTERMEDIAS", //N - Valor / AÑO
            "METAS AL FINAL DEL PROYECTO",//1 - Valor / AÑO
            "FUENTE",
            "OBSERVACIONES"
        );
        
        $Arr_name_resultado = array(
            "INDICADORES",
            "UNIDAD DE MEDIDA",
            "LINEA DE BASE", //1 - Valor / AÑO
            "MEDICIONES INTERMEDIAS", //N - Valor / AÑO
            "METAS AL FINAL DEL PROYECTO",//1 - Valor / AÑO
            "FUENTE",
            "OBSERVACIONES"
        );
        
        $Arr_name_producto = array(
            "PRODUCTOS",
            "UNIDAD DE MEDIDA",
            "LINEA DE BASE",
            "MEDICIONES INTERMEDIAS", //N mediciones
            "METAS AL FINAL DEL PROYECTO",
            "FUENTE"
        );
        
        $array_impacto= array();
        $array_resultado= array();
        $array_producto= array();
        $lines_impacto = 10;
        $lines_resultado = 10;
        $lines_producto = 10;       
        $cols_impacto =6;
        $cols_resultado =6;
        $cols_producto =5;
        
        
        
        $array_m = array();
        $result = $this->load->model($model)->lista_cargados();
        //var_dump($result[0]['_id']);
        $i = 0;
        $impacto= array();
        $im_c =10;
        $res_c =10;
        $pro_c=10;
        $resultado= array();
        $producto= array();
        foreach($result as $r){
            if($r['id'] = '5615732aa551709f078b4567'){
                //Echo 'Pasa!!!';
                $id_mongo = '5615732aa551709f078b4567';
                $impacto = $r['IMPACTO'];
                $resultado = $r['RESULTADO'];
                $producto = $r['PRODUCTO'];
                
                $impacto[1]['INDICADORES'] = 'PRUEBA'; 
                //TERMINAR FUNCION: TIENE QUE PASAR LOS TRES ARRAY 
                //var_dump($impacto);
                ///exit();
            } 
            $i++;        
        }
        //exit();
        //$id_m = $array[0]['_id'];
        //$array_m =  $array[0];
        //var_dump($id_m);
        
        //$array_m['prueba'] = 'ok';
        //var_dump($array_m);
        $this->load->model($model)->edit_array_matriz_resultado($id_mongo,$impacto, $im_c, $resultado, $res_c, $producto, $pro_c);
    }
    
    
    
}

