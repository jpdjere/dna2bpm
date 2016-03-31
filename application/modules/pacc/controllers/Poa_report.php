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
class poa_report extends MX_Controller {

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

    /*
     * Esta funcion da informaci�n sobre el movimiento del Expediente / C�digo
     */

    function Query() {
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'QR Code';
        $cpData['reader_title'] = $cpData['title'];
        $cpData['reader_subtitle'] = 'Read QR Codes from any HTML5 enabled device';
//         $cpData['css'] = array(
//             $this->base_url . "inventory/assets/css/inventory.css" => 'custom css',
//         );
//         $cpData['js'] = array(
//             $this->base_url . "qr/assets/jscript/html5-qrcode.min.js" => 'HTML5 qrcode',
//             $this->base_url . "qr/assets/jscript/jquery.animate-colors-min.js" => 'Color Animation',
//             $this->base_url . "inventory/assets/jscript/qr.js" => 'Main functions'
//         );

//         $cpData['global_js'] = array(
//             'base_url' => $this->base_url,
//             'module_url' => $this->module_url,
//             'redir' => $this->module_url . 'info'
//         );
       
//        $cpData['myjs']='<script type="text/javascript" src="'.$this->base_url.'qr/assets/jscript/html5-qrcode.min.js"></script>';
//        $cpData['myjs'].='<script type="text/javascript" src="'.$this->base_url.'qr/assets/jscript/jquery.animate-colors-min.js"></script>';
//        $cpData['myjs'].='<script type="text/javascript" src="'.$this->base_url.'inventory/assets/jscript/qr.js"></script>';
        echo $this->parser->parse('query', $cpData, true,true);  
//               $this->load->library('ui');
//               echo $this->ui->compose('query', 'bootstrap.ui.php', $cpData);


    }

    
    function borrar_poa($id_poa){
        //echo 'Filename:'.$filename;
        $model = 'model_poa_list';
        $result = $this->load->model($model)->borrar_poa_db($id_poa);   
        $this->user->authorize();
	$this->load->module('dashboard');
        $this->dashboard->dashboard('pacc/json/poa_report.json');
    }
    
    
    function gen_table($array){
        
        $rtn = '';
        
        foreach ($array as $list) {
            
            $rtn = $rtn.'<tr><a href="'.$this->module_url.'poa_report/poa_detail/'.$list['id'].'" target="_blank">'.$list['filename'].'</a>|--|<a href="'.$this->module_url.'poa_report/borrar_poa/'.$list['id'].'" class="btn btn-primary btn-xs" role="button">Borrar</a></tr></br>';
        }
        return $rtn;
    }
    
    function gen_table_poa_detail($result){
        $rtn ='';
        //var_dump($result[0]);
        
        //for($i = 3;$i<11; $i++){    //fila
        $i = 0;
        foreach($result[0] as $array){  
            
            if(is_array($array) && $i > 2){
                $rtn = $rtn.'<tr>';
                foreach($array as $campo){
                    //var_dump($campo);
                    $rtn = $rtn.'<td>'.utf8_decode($campo).'</td>';
                }
                $rtn = $rtn.'</tr>';
            }
            
            
            $i++;
            //var_dump($result[0][$i]);
            
        }
        //var_dump($rtn);
        return $rtn;
    } 
                 
             
            
            
            
            //foreach ($info as $info1) {
                //$rtn = $rtn.'<td>';
                
                //$rtn = $rtn.'</tr>';
                //var_dump($info1);
                
                /*if ($i > 2){
                $info1 = $list1;
                $rtn = $rtn.'<tr>';
                $rtn = $rtn.'<td></td>';
                if($info1 !=null){
                foreach ($info1 as $list2) {
                    $info2 = $list2;
                    
                    $rtn = $rtn.'<td>'.utf8_decode($info2).'</td>';
                    
                }
                }
                $rtn = $rtn.'</tr>';}
               // $i++;
                
            //}
            $rtn = $rtn.'</tr>';
            //var_dump($info);
        }*/
        //var_dump($rtn);
        //echo $rtn;
        //exit();
        
    
    function poa_detail($list){
        
        
        $id_poa = $list;
        //var_dump($id_poa);
       
        $model = 'model_poa_list';
        $result = $this->load->model($model)->detalle_poa($id_poa);
        //var_dump($result);
        /*foreach ($result as $list) {
            $info = $list;
            echo 'Info:</br>';
            var_dump($info);
        }*/
        $filename=$result[0]['filename'];
        $table = $this->gen_table_poa_detail($result);
        //var_dump($table);
        //exit;
        $customData = array();
        //$customData['file'] = $table; 
        $customData['file'] = $table;
        $customData['filename'] = $filename.'.xls'; 
        $default_dashboard = 'pacc/poa_detail_view';
                
        echo $this->parser->parse($default_dashboard,$customData,true,true);
        
    }
    
    
    function fpoa_list() {
        
        $model='model_poa_list';
        
        $customData = array();
        $default_dashboard = 'poa_list_view';
        // $default_dashboard = 'reports';
        $lista = array();
        $rtn= array();
        $lista = $this->load->model($model)->lista_cargados_poa();
        
        $i =0;
        foreach ($lista as $list) {
            if($list['borrado'] == 0){
            $rtn[$i]['id'] = $list['_id'];
            $rtn[$i]['filename'] = $list['filename'];
            $i++;
            }
        }
        //var_dump($rtn);
        $count = $this->load->model($model)->count_cargados();
        
        //$customData= $lista;
        $customData['tabla'] = $this->gen_table($rtn);
        $customData['count'] = $count;
        
        return $this->parser->parse('pacc/poa_list_view',$customData,true,true);    
    }
   
    
    
    function fpoa_load() {
        $customData = array();
        $customData['base_url'] = $this->module_url;
        $customData['lang SelectFile'] = 'Seleccionar Archivo';
        $customData['lang UploadFile'] = 'Subir Archivo';
        echo $this->parser->parse('pacc/poa_load_view',$customData,true,true);
        
        
        
    }
    function fpoa_upload() {
        // $filename = $_FILES[0];
        //var_dump($file);
        //$filename = getcwd() . '/anexos_pacc/PACC1.xls';
        //echo $this->anexo($filename);
        $filename = $_FILES["file"]["tmp_name"];
        //$this->anexo($filename);
        
        if (!$filename) {
            
            exit();
            
        }
        
        $this->process($filename);
        
        
    }
    
    
    
    //////////////////////////Carga a la Base Mongo ////////////////////////////
    

    function process($filename) {

        //echo 'Process'.$filename.'</br>';

        $customData = array();
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'pacc/';
       
        //$customData['js'] = array($this->module_url . "assets/jscript/dashboard.js" => 'Dashboard JS', $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min_1.js" => 'Validate');
        //$customData['css'] = array($this->module_url . "assets/css/dashboard.css" => 'Dashboard CSS');


        $filename_ext = ($this->anexo == '09') ? ".pdf" : ".xls";

        $hoy = getdate();

        //var_dump($hoy);
        
        $new_filename = $hoy['year'] . '-';
        if ($hoy['mon'] < 10)
            $new_filename = $new_filename . '0' . $hoy['mon'] . '-';
        else
            $new_filename = $new_filename . $hoy['mon'] . '-';

        if ($hoy['mday'] < 10)
            $new_filename = $new_filename . '0' . $hoy['mday'] . '-';
        else
            $new_filename = $new_filename . $hoy['mday'] . '-';

        if ($hoy['hours'] < 10)
            $new_filename = $new_filename . '0' . $hoy['hours'] . '-';
        else
            $new_filename = $new_filename . $hoy['hours'] . '-';

        if ($hoy['minutes'] < 10)
            $new_filename = $new_filename . '0' . $hoy['minutes'] . '-';
        else
            $new_filename = $new_filename . $hoy['minutes'] . '-';

        if ($hoy['seconds'] < 10)
            $new_filename = $new_filename . '0' . $hoy['seconds'] . '-PACC-POA-';
        else
            $new_filename = $new_filename . $hoy['seconds'] . '-PACC-POA-';
        
        $new_filename = $new_filename . rand(1000, 5000) .'.xls';
        
        
        //var_dump($new_filename);
        //$uploadpath = getcwd() . '/anexos_pacc/' . $filename;
        $uploadpath = $filename;
        $movepath = getcwd() . '/anexos_pacc/POA/' . $new_filename;


        //var_dump($uploadpath);
        //var_dump($movepath);



        $this->load->library('excel_reader2');
        $data = new Excel_reader2($uploadpath);
        //var_dump($data->sheets[0]);       
        copy($uploadpath, $movepath);

        $stack = array();
        $fields = "";
        $result = "";
        $result_header = "";
        $error = false;
        $headerArr = array();
        $valuesArr = array();
        $cols = $data->sheets[0]['numCols']; //51;
        //// Chequeo de formato del Archivo
        $model = 'model_poa_list';
        if ($cols > 71) {
            echo 'Error en cantidad de Columnas!!';
            return;
        }
        
        
        
        $lines = $data->sheets[0]['numRows'];
        

        $Arr_name = array(
            //"COMP_SCOMP",
            "AREA",
            "SCOMP",
            "COMP",
            "CODIGO",
            "DESCRIP",
            "CONTRATADO",
            "IP_UNIDAD",
            "IP_TI",
            "IP_TII",
            "IP_TIII",
            "IP_TIV",
            "IP_TOTAL",
            "COSTO_UNI",
            "COSTO_UNI_USD",//nuevo
            "Inciso_ONP",
            "FUENTE_22",
            "FUENTE_11",
            "FUENTE_PYME",
            "PESO_TI_BID",
            "PESO_TI_BNA",
            "PESO_TI_PYME",
            "PESO_TII_BID",
            "PESO_TII_BNA",
            "PESO_TII_PYME",
            "PESO_TIII_BID",
            "PESO_TIII_BNA",
            "PESO_TIII_PYME",
            "PESO_TIV_BID",
            "PESO_TIV_BNA",
            "PESO_TIV_PYME",
            "PESO_TOTFUE_BID",
            "PESO_TOTFUE_BNA",
            "PESO_TOTFUE_PYME",
            "PESO_TOTAL",
            "USD_TI_BID",
            "USD_TI_BNA",
            "USD_TI_PYME",
            "USD_TII_BID",
            "USD_TII_BNA",
            "USD_TII_PYME",
            "USD_TIII_BID",
            "USD_TIII_BNA",
            "USD_TIII_PYME",
            "USD_TIV_BID",
            "USD_TIV_BNA",
            "USD_TIV_PYME",
            "USD_TOTFUE_BID",
            "USD_TOTFUE_BNA",
            "USD_TOTFUE_PYME",
            "USD_TOTAL",
            
            //NUEVOS
            "IP_TI_REAL",//nuevo
            "IP_TII_REAL",//nuevo
            "IP_TIII_REAL",//nuevo
            "IP_TIV_REAL",//nuevo
            "IP_TOTAL_REAL",//nuevo
            "PESO_TI_BID_REAL",//nuevo
            "PESO_TI_BNA_REAL",//nuevo
            "PESO_TI_PYME_REAL",//nuevo
            "PESO_TII_BID_REAL",//nuevo
            "PESO_TII_BNA_REAL",//nuevo
            "PESO_TII_PYME_REAL",//nuevo
            "PESO_TIII_BID_REAL",//nuevo
            "PESO_TIII_BNA_REAL",//nuevo
            "PESO_TIII_PYME_REAL",//nuevo
            "PESO_TIV_BID_REAL",//nuevo
            "PESO_TIV_BNA_REAL",//nuevo
            "PESO_TIV_PYME_REAL",//nuevo
            "PESO_TOTFUE_BID_REAL",//nuevo
            "PESO_TOTFUE_BNA_REAL",//nuevo
            "PESO_TOTFUE_PYME_REAL",//nuevo
            "PESO_TOTAL_REAL",//nuevo
            
            
            
            
        ); //71 campos




        $id = 1;
        $headerArr['filename'] = $new_filename;
        $headerArr['date'] = $hoy;
        $index_l = (0);
        $index_c = (0);
        for ($index_l = 1; $index_l <= ($lines); $index_l++) {
            for ($index_c = 1; $index_c <= ($cols); $index_c++) {
                $var = $data->sheets[0]['cells'][$index_l][$index_c];
                //echo '$index_l:'.$index_l.'  $index_c:'.$index_c.'  $var:'.$var;
                $var = utf8_encode($var);

                $headerArr[$index_l][$Arr_name[($index_c - 1)]] = $var;
            }
        
            //var_dump($headerArr[$index_l][]);
            //$result = $this->put_array_POA($id, $container_POA, $headerArr[$index_l]);
        }
        //var_dump($headerArr);
        $PESO_TI_BID = $data->sheets[0]['cells'][1][19];
        $PESO_TI_BNA = $data->sheets[0]['cells'][1][20];
        $headerArr['borrado'] = 0;

        
        //var_dump($headerArr);
        
        $result = $this->load->model($model)->put_array_POA_arch($new_filename, $headerArr,$lines,$PESO_TI_BID, $PESO_TI_BNA);

        
    }

    
    function process_direct($array, $lines, $PESO_TI_BID, $PESO_TI_BNA) {

        //echo 'Process'.$filename.'</br>';

        $customData = array();
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'pacc/';
       
        //$customData['js'] = array($this->module_url . "assets/jscript/dashboard.js" => 'Dashboard JS', $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min_1.js" => 'Validate');
        //$customData['css'] = array($this->module_url . "assets/css/dashboard.css" => 'Dashboard CSS');


        $filename_ext = ($this->anexo == '09') ? ".pdf" : ".xls";

        $hoy = getdate();

        //var_dump($hoy);
        //nuevo
        $new_filename = $hoy['year'] . '-';
        if ($hoy['mon'] < 10)
            $new_filename = $new_filename . '0' . $hoy['mon'] . '-';
        else
            $new_filename = $new_filename . $hoy['mon'] . '-';

        if ($hoy['mday'] < 10)
            $new_filename = $new_filename . '0' . $hoy['mday'] . '-';
        else
            $new_filename = $new_filename . $hoy['mday'] . '-';

        if ($hoy['hours'] < 10)
            $new_filename = $new_filename . '0' . $hoy['hours'] . '-';
        else
            $new_filename = $new_filename . $hoy['hours'] . '-';

        if ($hoy['minutes'] < 10)
            $new_filename = $new_filename . '0' . $hoy['minutes'] . '-';
        else
            $new_filename = $new_filename . $hoy['minutes'] . '-';

        if ($hoy['seconds'] < 10)
            $new_filename = $new_filename . '0' . $hoy['seconds'] . '-PACC-POA-';
        else
            $new_filename = $new_filename . $hoy['seconds'] . '-PACC-POA-';
        
        $new_filename = $new_filename . rand(1000, 5000) .'.xls';
        
        
        //var_dump($new_filename);
        //$uploadpath = getcwd() . '/anexos_pacc/' . $filename;
        //$uploadpath = $filename;
        //$movepath = getcwd() . '/anexos_pacc/POA/' . $new_filename;


        //var_dump($uploadpath);
        //var_dump($movepath);



        //$this->load->library('excel_reader2');
        //$data = new Excel_reader2($uploadpath);
        //var_dump($data->sheets[0]);       
        //copy($uploadpath, $movepath);

        $stack = array();
        $fields = "";
        $result = "";
        $result_header = "";
        $error = false;
        $headerArr = array();
        $valuesArr = array();
        $cols= 51;
        /*
        $cols = $data->sheets[0]['numCols']; //51;
        //// Chequeo de formato del Archivo

        if ($cols > 51) {
            echo 'Error en cantidad de Columnas!!';
            return;
        }
        if ($data->sheets[0]['cells'][2][2] != 'COMP / SUBCOMP') {
            echo 'Error de posicionamiento!!';
            return;
        }
        $lines = $data->sheets[0]['numRows'];
        */

        $Arr_name = array(
            "AREA",
            "SCOMP",
            "COMP",
            "CODIGO",
            "DESCRIP",
            "CONTRATADO",
            "IP_UNIDAD",
            "IP_TI",
            "IP_TII",
            "IP_TIII",
            "IP_TIV",
            "IP_TOTAL",
            "COSTO_UNI",
            "COSTO_UNI_USD",//nuevo
            "Inciso_ONP",
            "FUENTE_22",
            "FUENTE_11",
            "FUENTE_PYME",
            "PESO_TI_BID",
            "PESO_TI_BNA",
            "PESO_TI_PYME",
            "PESO_TII_BID",
            "PESO_TII_BNA",
            "PESO_TII_PYME",
            "PESO_TIII_BID",
            "PESO_TIII_BNA",
            "PESO_TIII_PYME",
            "PESO_TIV_BID",
            "PESO_TIV_BNA",
            "PESO_TIV_PYME",
            "PESO_TOTFUE_BID",
            "PESO_TOTFUE_BNA",
            "PESO_TOTFUE_PYME",
            "PESO_TOTAL",
            "USD_TI_BID",
            "USD_TI_BNA",
            "USD_TI_PYME",
            "USD_TII_BID",
            "USD_TII_BNA",
            "USD_TII_PYME",
            "USD_TIII_BID",
            "USD_TIII_BNA",
            "USD_TIII_PYME",
            "USD_TIV_BID",
            "USD_TIV_BNA",
            "USD_TIV_PYME",
            "USD_TOTFUE_BID",
            "USD_TOTFUE_BNA",
            "USD_TOTFUE_PYME",
            "USD_TOTAL",
            
            //NUEVOS
            "IP_TI_REAL",//nuevo
            "IP_TII_REAL",//nuevo
            "IP_TIII_REAL",//nuevo
            "IP_TIV_REAL",//nuevo
            "IP_TOTAL_REAL",//nuevo
            "PESO_TI_BID_REAL",//nuevo
            "PESO_TI_BNA_REAL",//nuevo
            "PESO_TI_PYME_REAL",//nuevo
            "PESO_TII_BID_REAL",//nuevo
            "PESO_TII_BNA_REAL",//nuevo
            "PESO_TII_PYME_REAL",//nuevo
            "PESO_TIII_BID_REAL",//nuevo
            "PESO_TIII_BNA_REAL",//nuevo
            "PESO_TIII_PYME_REAL",//nuevo
            "PESO_TIV_BID_REAL",//nuevo
            "PESO_TIV_BNA_REAL",//nuevo
            "PESO_TIV_PYME_REAL",//nuevo
            "PESO_TOTFUE_BID_REAL",//nuevo
            "PESO_TOTFUE_BNA_REAL",//nuevo
            "PESO_TOTFUE_PYME_REAL",//nuevo
            "PESO_TOTAL_REAL"//nuevo
            
            
            
            
        );
        



        $container_POA = 'container.pacc_POA';
        $id = 1;
        $headerArr['filename'] = $new_filename;
        $headerArr['date'] = $hoy;
        //$headerArr[1] = $array[1];
        $index_l = (0);
        $index_c = (0);
        
        $headerArr[1] = array(
            //"COMP_SCOMP" => "1",
            "AREA" => "",
            "SCOMP" => "",
            "COMP" => "",
            "CODIGO" => "",
            "DESCRIP" => "",
            "CONTRATADO" => "",
            "IP_UNIDAD" => "",
            "IP_TI" => "",
            "IP_TII" => "",
            "IP_TIII" => "",
            "IP_TIV" => "",
            "IP_TOTAL" => "",
            "COSTO_UNI" => "",
            "COSTO_UNI_USD"=> "",//nuevo
            "Inciso_ONP" => "Cotización planificación",
            "FUENTE_22" => "",
            "FUENTE_11" => "",
            "FUENTE_PYME" => "",
            "PESO_TI_BID" => $PESO_TI_BID,
            "PESO_TI_BNA" => $PESO_TI_BNA,
            "PESO_TI_PYME" => "",
            "PESO_TII_BID" => "",
            "PESO_TII_BNA" => "",
            "PESO_TII_PYME" => "",
            "PESO_TIII_BID" => "",
            "PESO_TIII_BNA" => "",
            "PESO_TIII_PYME" => "",
            "PESO_TIV_BID" => "",
            "PESO_TIV_BNA" => "",
            "PESO_TIV_PYME" => "",
            "PESO_TOTFUE_BID" => "",
            "PESO_TOTFUE_BNA" => "",
            "PESO_TOTFUE_PYME" => "",
            "PESO_TOTAL" => "",
            "USD_TI_BID" => "",
            "USD_TI_BNA" => "",
            "USD_TI_PYME" => "",
            "USD_TII_BID" => "",
            "USD_TII_BNA" => "",
            "USD_TII_PYME" => "",
            "USD_TIII_BID" => "",
            "USD_TIII_BNA" => "",
            "USD_TIII_PYME" => "",
            "USD_TIV_BID" => "",
            "USD_TIV_BNA" => "",
            "USD_TIV_PYME" => "",
            "USD_TOTFUE_BID" => "",
            "USD_TOTFUE_BNA" => "",
            "USD_TOTFUE_PYME" => "",
            "USD_TOTAL" => "",
            
            //NUEVOS
            "IP_TI_REAL" => "",//nuevo
            "IP_TII_REAL" => "",//nuevo
            "IP_TIII_REAL" => "",//nuevo
            "IP_TIV_REAL" => "",//nuevo
            "IP_TOTAL_REAL" => "",//nuevo
            "PESO_TI_BID_REAL" => "",//nuevo
            "PESO_TI_BNA_REAL" => "",//nuevo
            "PESO_TI_PYME_REAL" => "",//nuevo
            "PESO_TII_BID_REAL" => "",//nuevo
            "PESO_TII_BNA_REAL" => "",//nuevo
            "PESO_TII_PYME_REAL" => "",//nuevo
            "PESO_TIII_BID_REAL" => "",//nuevo
            "PESO_TIII_BNA_REAL" => "",//nuevo
            "PESO_TIII_PYME_REAL" => "",//nuevo
            "PESO_TIV_BID_REAL" => "",//nuevo
            "PESO_TIV_BNA_REAL" => "",//nuevo
            "PESO_TIV_PYME_REAL" => "",//nuevo
            "PESO_TOTFUE_BID_REAL" => "",//nuevo
            "PESO_TOTFUE_BNA_REAL" => "",//nuevo
            "PESO_TOTFUE_PYME_REAL" => "",//nuevo
            "PESO_TOTAL_REAL" => ""//nuevo
        );
        
        
        
        
        
        $headerArr[2] = array(        
        //"COMP_SCOMP" => "COMP / SUBCOMP",
        "AREA" => "ÁREA RESPONSABLE",
        "SCOMP" => "PRODUCTOS (BIEN, SERVICIO, PROYECTO O NORMA)",
        "COMP" => "",
        "CODIGO" => "",
        "DESCRIP" => "",
        "CONTRATADO" => "",
        "IP_UNIDAD" => "",
        "IP_TI" => "",
        "IP_TII" => "",
        "IP_TIII" => "",
        "IP_TIV" => "",
        "IP_TOTAL" => "",
        "COSTO_UNI" => "",
        "COSTO_UNI_USD"=> "",//nuevo
        "Inciso_ONP" => "",
        "FUENTE_22" => "",
        "FUENTE_11" => "",
        "FUENTE_PYME" => "",
        "PESO_TI_BID" => "INDICADOR PRESUPUESTARIO",
        "PESO_TI_BNA" => "",
        "PESO_TI_PYME" => "",
        "PESO_TII_BID" => "",
        "PESO_TII_BNA" => "",
        "PESO_TII_PYME" => "",
        "PESO_TIII_BID" => "",
        "PESO_TIII_BNA" => "",
        "PESO_TIII_PYME" => "",
        "PESO_TIV_BID" => "",
        "PESO_TIV_BNA" => "",
        "PESO_TIV_PYME" => "",
        "PESO_TOTFUE_BID" => "",
        "PESO_TOTFUE_BNA" => "",
        "PESO_TOTFUE_PYME" => "",
        "PESO_TOTAL" => "",
        "USD_TI_BID" => "INDICADOR PRESUPUESTARIO",
        "USD_TI_BNA" => "",
        "USD_TI_PYME" => "",
        "USD_TII_BID" => "",
        "USD_TII_BNA" => "",
        "USD_TII_PYME" => "",
        "USD_TIII_BID" => "",
        "USD_TIII_BNA" => "",
        "USD_TIII_PYME" => "",
        "USD_TIV_BID" => "",
        "USD_TIV_BNA" => "",
        "USD_TIV_PYME" => "",
        "USD_TOTFUE_BID" => "",
        "USD_TOTFUE_BNA" => "",
        "USD_TOTFUE_PYME" => "",
        "USD_TOTAL" => "",
        
        //NUEVOS
        "IP_TI_REAL" => "",//nuevo
        "IP_TII_REAL" => "",//nuevo
        "IP_TIII_REAL" => "",//nuevo
        "IP_TIV_REAL" => "",//nuevo
        "IP_TOTAL_REAL" => "",//nuevo
        "PESO_TI_BID_REAL" => "",//nuevo
        "PESO_TI_BNA_REAL" => "",//nuevo
        "PESO_TI_PYME_REAL" => "",//nuevo
        "PESO_TII_BID_REAL" => "",//nuevo
        "PESO_TII_BNA_REAL" => "",//nuevo
        "PESO_TII_PYME_REAL" => "",//nuevo
        "PESO_TIII_BID_REAL" => "",//nuevo
        "PESO_TIII_BNA_REAL" => "",//nuevo
        "PESO_TIII_PYME_REAL" => "",//nuevo
        "PESO_TIV_BID_REAL" => "",//nuevo
        "PESO_TIV_BNA_REAL" => "",//nuevo
        "PESO_TIV_PYME_REAL" => "",//nuevo
        "PESO_TOTFUE_BID_REAL" => "",//nuevo
        "PESO_TOTFUE_BNA_REAL" => "",//nuevo
        "PESO_TOTFUE_PYME_REAL" => "",//nuevo
        "PESO_TOTAL_REAL" => ""//nuevo    
    );
        
    $headerArr[3] = array(    
        //"COMP_SCOMP" => "",
        "AREA" => "",
        "SCOMP" => "SCOMP",
        "COMP" => "COMP",
        "CODIGO" => "CÓDIGO",
        "DESCRIP" => "DESCRIPCIÓN",
        "CONTRATADO" => "CONTRATADO",
        "IP_UNIDAD" => "INDICADOR PRODUCTO",
        "IP_TI" => "",
        "IP_TII" => "",
        "IP_TIII" => "",
        "IP_TIV" => "",
        "IP_TOTAL" => "",
        "COSTO_UNI" => "COSTO UNITARIO",
        "COSTO_UNI_USD"=> "COSTO UNITARIO USD",//nuevo
        "Inciso_ONP" => "Inciso ONP",
        "FUENTE_22" => "FUENTE",
        "FUENTE_11" => "",
        "FUENTE_PYME" => "",
        "PESO_TI_BID" => "T I ($)",
        "PESO_TI_BNA" => "",
        "PESO_TI_PYME" => "",
        "PESO_TII_BID" => "T II ($)",
        "PESO_TII_BNA" => "",
        "PESO_TII_PYME" => "",
        "PESO_TIII_BID" => "T III ($)",
        "PESO_TIII_BNA" => "",
        "PESO_TIII_PYME" => "",
        "PESO_TIV_BID" => "T IV ($)",
        "PESO_TIV_BNA" => "",
        "PESO_TIV_PYME" => "",
        "PESO_TOTFUE_BID" => "TOTAL POR FUENTE",
        "PESO_TOTFUE_BNA" => "",
        "PESO_TOTFUE_PYME" => "",
        "PESO_TOTAL" => "TOTAL (S)",
        "USD_TI_BID" => "T I (USS)",
        "USD_TI_BNA" => "",
        "USD_TI_PYME" => "",
        "USD_TII_BID" => "T II (USS)",
        "USD_TII_BNA" => "",
        "USD_TII_PYME" => "",
        "USD_TIII_BID" => "T III (USS)",
        "USD_TIII_BNA" => "",
        "USD_TIII_PYME" => "",
        "USD_TIV_BID" => "T IV (USS)",
        "USD_TIV_BNA" => "",
        "USD_TIV_PYME" => "",
        "USD_TOTFUE_BID" => "TOTAL POR FUENTE (USS)",
        "USD_TOTFUE_BNA" => "",
        "USD_TOTFUE_PYME" => "",
        "USD_TOTAL" => 'TOTAL (USS)',
        //NUEVOS
        "IP_TI_REAL" => "",//nuevo
        "IP_TII_REAL" => "",//nuevo
        "IP_TIII_REAL" => "",//nuevo
        "IP_TIV_REAL" => "",//nuevo
        "IP_TOTAL_REAL" => "",//nuevo
        "PESO_TI_BID_REAL" => "",//nuevo
        "PESO_TI_BNA_REAL" => "",//nuevo
        "PESO_TI_PYME_REAL" => "",//nuevo
        "PESO_TII_BID_REAL" => "",//nuevo
        "PESO_TII_BNA_REAL" => "",//nuevo
        "PESO_TII_PYME_REAL" => "",//nuevo
        "PESO_TIII_BID_REAL" => "",//nuevo
        "PESO_TIII_BNA_REAL" => "",//nuevo
        "PESO_TIII_PYME_REAL" => "",//nuevo
        "PESO_TIV_BID_REAL" => "",//nuevo
        "PESO_TIV_BNA_REAL" => "",//nuevo
        "PESO_TIV_PYME_REAL" => "",//nuevo
        "PESO_TOTFUE_BID_REAL" => "",//nuevo
        "PESO_TOTFUE_BNA_REAL" => "",//nuevo
        "PESO_TOTFUE_PYME_REAL" => "",//nuevo
        "PESO_TOTAL_REAL" => ""//nuevo 
        
        
        );
    
    $headerArr[4] = array(
        //"COMP_SCOMP" => "",
        "AREA" => "",
        "SCOMP" => "",
        "COMP" => "",
        "CODIGO" => "",
        "DESCRIP" => "",
        "CONTRATADO" => "",
        "IP_UNIDAD" => "UNIDAD MEDIDA",
        "IP_TI" => "T I",
        "IP_TII" => "T II",
        "IP_TIII" => "T III",
        "IP_TIV" => "T IV",
        "IP_TOTAL" => "TOTAL",
        "COSTO_UNI" => "",
        "COSTO_UNI_USD"=> "",//nuevo
        "Inciso_ONP" => "",
        "FUENTE_22" => "22",
        "FUENTE_11" => "11",
        "FUENTE_PYME" => "PYME",
        "PESO_TI_BID" => "BID",
        "PESO_TI_BNA" => "NACIÓN",
        "PESO_TI_PYME" => "APORTE PYME",
        "PESO_TII_BID" => "BID",
        "PESO_TII_BNA" => "NACIÓN",
        "PESO_TII_PYME" => "APORTE PYME",
        "PESO_TIII_BID" => "BID",
        "PESO_TIII_BNA" => "NACIÓN",
        "PESO_TIII_PYME" => "APORTE PYME",
        "PESO_TIV_BID" => "BID",
        "PESO_TIV_BNA" => "NACIÓN",
        "PESO_TIV_PYME" => "APORTE PYME",
        "PESO_TOTFUE_BID" => "BID",
        "PESO_TOTFUE_BNA" => "NACIÓN",
        "PESO_TOTFUE_PYME" => "APORTE PYME",
        "PESO_TOTAL" => "",
        "USD_TI_BID" => "BID",
        "USD_TI_BNA" => "NACIÓN",
        "USD_TI_PYME" => "APORTE PYME",
        "USD_TII_BID" => "BID",
        "USD_TII_BNA" => "NACIÓN",
        "USD_TII_PYME" => "APORTE PYME",
        "USD_TIII_BID" => "BID",
        "USD_TIII_BNA" => "NACIÓN",
        "USD_TIII_PYME" => "APORTE PYME",
        "USD_TIV_BID" => "BID",
        "USD_TIV_BNA" => "NACIÓN",
        "USD_TIV_PYME" => "APORTE PYME",
        "USD_TOTFUE_BID" => "BID",
        "USD_TOTFUE_BNA" => "NACIÓN",
        "USD_TOTFUE_PYME" => "APORTE PYME",
        "USD_TOTAL" => "",
        //NUEVOS
        "IP_TI_REAL" => "",//nuevo
        "IP_TII_REAL" => "",//nuevo
        "IP_TIII_REAL" => "",//nuevo
        "IP_TIV_REAL" => "",//nuevo
        "IP_TOTAL_REAL" => "",//nuevo
        "PESO_TI_BID_REAL" => "",//nuevo
        "PESO_TI_BNA_REAL" => "",//nuevo
        "PESO_TI_PYME_REAL" => "",//nuevo
        "PESO_TII_BID_REAL" => "",//nuevo
        "PESO_TII_BNA_REAL" => "",//nuevo
        "PESO_TII_PYME_REAL" => "",//nuevo
        "PESO_TIII_BID_REAL" => "",//nuevo
        "PESO_TIII_BNA_REAL" => "",//nuevo
        "PESO_TIII_PYME_REAL" => "",//nuevo
        "PESO_TIV_BID_REAL" => "",//nuevo
        "PESO_TIV_BNA_REAL" => "",//nuevo
        "PESO_TIV_PYME_REAL" => "",//nuevo
        "PESO_TOTFUE_BID_REAL" => "",//nuevo
        "PESO_TOTFUE_BNA_REAL" => "",//nuevo
        "PESO_TOTFUE_PYME_REAL" => "",//nuevo
        "PESO_TOTAL_REAL" => ""//nuevo 
        
        
        );
    
    $headerArr[5] = array(
        //"COMP_SCOMP" => "",
        "AREA" => "",
        "SCOMP" => "",
        "COMP" => "",
        "CODIGO" => "",
        "DESCRIP" => "",
        "CONTRATADO" => "",
        "IP_UNIDAD" => "",
        "IP_TI" => "ESTIM",
        "IP_TII" => "ESTIM",
        "IP_TIII" => "ESTIM",
        "IP_TIV" => "ESTIM",
        "IP_TOTAL" => "ESTIM",
        "COSTO_UNI" => "",
        "COSTO_UNI_USD"=> "",//nuevo
        "Inciso_ONP" => "",
        "FUENTE_22" => "",
        "FUENTE_11" => "",
        "FUENTE_PYME" => "",
        "PESO_TI_BID" => "ESTIMADO",
        "PESO_TI_BNA" => "ESTIMADO",
        "PESO_TI_PYME" => "ESTIMADO",
        "PESO_TII_BID" => "ESTIMADO",
        "PESO_TII_BNA" => "ESTIMADO",
        "PESO_TII_PYME" => "ESTIMADO",
        "PESO_TIII_BID" => "ESTIMADO",
        "PESO_TIII_BNA" => "ESTIMADO",
        "PESO_TIII_PYME" => "ESTIMADO",
        "PESO_TIV_BID" => "ESTIMADO",
        "PESO_TIV_BNA" => "ESTIMADO",
        "PESO_TIV_PYME" => "ESTIMADO",
        "PESO_TOTFUE_BID" => "",
        "PESO_TOTFUE_BNA" => "",
        "PESO_TOTFUE_PYME" => "",
        "PESO_TOTAL" => "",
        "USD_TI_BID" => "ESTIMADO",
        "USD_TI_BNA" => "ESTIMADO",
        "USD_TI_PYME" => "ESTIMADO",
        "USD_TII_BID" => "ESTIMADO",
        "USD_TII_BNA" => "ESTIMADO",
        "USD_TII_PYME" => "ESTIMADO",
        "USD_TIII_BID" => "",
        "USD_TIII_BNA" => "",
        "USD_TIII_PYME" => "",
        "USD_TIV_BID" => "",
        "USD_TIV_BNA" => "",
        "USD_TIV_PYME" => "",
        "USD_TOTFUE_BID" => "",
        "USD_TOTFUE_BNA" => "",
        "USD_TOTFUE_PYME" => "",
        "USD_TOTAL" => "",
        //NUEVOS
        "IP_TI_REAL" => "",//nuevo
        "IP_TII_REAL" => "",//nuevo
        "IP_TIII_REAL" => "",//nuevo
        "IP_TIV_REAL" => "",//nuevo
        "IP_TOTAL_REAL" => "",//nuevo
        "PESO_TI_BID_REAL" => "",//nuevo
        "PESO_TI_BNA_REAL" => "",//nuevo
        "PESO_TI_PYME_REAL" => "",//nuevo
        "PESO_TII_BID_REAL" => "",//nuevo
        "PESO_TII_BNA_REAL" => "",//nuevo
        "PESO_TII_PYME_REAL" => "",//nuevo
        "PESO_TIII_BID_REAL" => "",//nuevo
        "PESO_TIII_BNA_REAL" => "",//nuevo
        "PESO_TIII_PYME_REAL" => "",//nuevo
        "PESO_TIV_BID_REAL" => "",//nuevo
        "PESO_TIV_BNA_REAL" => "",//nuevo
        "PESO_TIV_PYME_REAL" => "",//nuevo
        "PESO_TOTFUE_BID_REAL" => "",//nuevo
        "PESO_TOTFUE_BNA_REAL" => "",//nuevo
        "PESO_TOTFUE_PYME_REAL" => "",//nuevo
        "PESO_TOTAL_REAL" => ""//nuevo 
        );
        
        
        
        
        
        for ($index_l = 6; $index_l <= $lines; $index_l++) {
            
            $headerArr[$index_l]  = $array[$index_l]; 
            /*
            for ($index_c = 2; $index_c <= $cols; $index_c++) {
                $var = $data->sheets[0]['cells'][$index_l][$index_c];
                //echo '$index_l:'.$index_l.'  $index_c:'.$index_c.'  $var:'.$var;
                $var = utf8_encode($var);

                $headerArr[$index_l][$Arr_name[($index_c - 2)]] = $var;
            }*/
        
            //var_dump($headerArr[$index_l][]);
            //$result = $this->put_array_POA($id, $container_POA, $headerArr[$index_l]);
        }
        //var_dump($headerArr);
        $headerArr['PESO_TI_BID'] = $PESO_TI_BID;
        $headerArr['PESO_TI_BNA'] = $PESO_TI_BNA;
        $headerArr['borrado'] = 0;
        
        //var_dump($headerArr);
        //exit();
        $container_POA = 'container.pacc_POA';
        $model = 'model_poa_list';
        $result = $this->load->model($model)->put_array_POA($new_filename, $container_POA, $headerArr);

        
    }

    
    function prueba_carga_direct() {
        $Arr_name = array(
            "AREA",
            "SCOMP",
            "COMP",
            "CODIGO",
            "DESCRIP",
            "CONTRATADO",
            "IP_UNIDAD",
            "IP_TI",
            "IP_TII",
            "IP_TIII",
            "IP_TIV",
            "IP_TOTAL",
            "COSTO_UNI",
            "COSTO_UNI_USD",//nuevo
            "Inciso_ONP",
            "FUENTE_22",
            "FUENTE_11",
            "FUENTE_PYME",
            "PESO_TI_BID",
            "PESO_TI_BNA",
            "PESO_TI_PYME",
            "PESO_TII_BID",
            "PESO_TII_BNA",
            "PESO_TII_PYME",
            "PESO_TIII_BID",
            "PESO_TIII_BNA",
            "PESO_TIII_PYME",
            "PESO_TIV_BID",
            "PESO_TIV_BNA",
            "PESO_TIV_PYME",
            "PESO_TOTFUE_BID",
            "PESO_TOTFUE_BNA",
            "PESO_TOTFUE_PYME",
            "PESO_TOTAL",
            "USD_TI_BID",
            "USD_TI_BNA",
            "USD_TI_PYME",
            "USD_TII_BID",
            "USD_TII_BNA",
            "USD_TII_PYME",
            "USD_TIII_BID",
            "USD_TIII_BNA",
            "USD_TIII_PYME",
            "USD_TIV_BID",
            "USD_TIV_BNA",
            "USD_TIV_PYME",
            "USD_TOTFUE_BID",
            "USD_TOTFUE_BNA",
            "USD_TOTFUE_PYME",
            "USD_TOTAL",
            //NUEVOS
            "IP_TI_REAL",//nuevo
            "IP_TII_REAL",//nuevo
            "IP_TIII_REAL",//nuevo
            "IP_TIV_REAL",//nuevo
            "IP_TOTAL_REAL",//nuevo
            "PESO_TI_BID_REAL",//nuevo
            "PESO_TI_BNA_REAL",//nuevo
            "PESO_TI_PYME_REAL",//nuevo
            "PESO_TII_BID_REAL",//nuevo
            "PESO_TII_BNA_REAL",//nuevo
            "PESO_TII_PYME_REAL",//nuevo
            "PESO_TIII_BID_REAL",//nuevo
            "PESO_TIII_BNA_REAL",//nuevo
            "PESO_TIII_PYME_REAL",//nuevo
            "PESO_TIV_BID_REAL",//nuevo
            "PESO_TIV_BNA_REAL",//nuevo
            "PESO_TIV_PYME_REAL",//nuevo
            "PESO_TOTFUE_BID_REAL",//nuevo
            "PESO_TOTFUE_BNA_REAL",//nuevo
            "PESO_TOTFUE_PYME_REAL",//nuevo
            "PESO_TOTAL_REAL"//nuevo
        );
        $array= array();
        $lines = 10;
        $cols =70;
        for ($index_l = 1; $index_l <= $lines; $index_l++) {
            
            
            for ($index_c = 0; $index_c <= $cols; $index_c++) {
                $var = $index_c;
                //echo '$index_l:'.$index_l.'  $index_c:'.$index_c.'  $var:'.$var;
                $var = utf8_encode($var);

                $array[$index_l][$Arr_name[($index_c )]] = $var;
            }
        
            //var_dump($headerArr[$index_l][]);
            //$result = $this->put_array_POA($id, $container_POA, $headerArr[$index_l]);
        }
        ///var_dump($array);
        $PESO_TI_BID = '10';
        $PESO_TI_BNA = '11'; 
        $model = 'model_poa_list';
        $result = $this->load->model($model)->put_array_POA($array, $lines, $PESO_TI_BID, $PESO_TI_BNA);
        
        
    }
    
    
    
    
    
}

