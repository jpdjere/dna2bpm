<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * pacc
 *
 */
class Import_xls extends MX_Controller {

    function __construct() {
        parent::__construct();
//----habilita acceso a todo los metodos de este controlador
        $this->user->authorize('modules/sgr/controllers/sgr');
        $this->load->config('config');
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('app');
        $this->load->model('user/user');
        $this->load->model('bpm/bpm');
        $this->load->model('user/rbac');
        /*
        $this->load->model('sgr/sgr_model');
        $this->load->model('sgr/model_organos_sociales');
        $this->load->helper('sgr/tools');
        $this->load->library('session');
        
        */


        /* update db */
        //$this->load->Model("mysql_model_periods");
        //$this->mysql_model_periods->active_periods_dna2();
//---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'pacc/';

//----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));

// IDU : Chequeo de sesion        



        //$this->idu = (float) switch_users($this->session->userdata('iduser'));

        /* bypass session */
        //session_start();

       // $_SESSION['idu'] = $this->idu;


        /*
        if (!$this->idu) {
            header("$this->module_url/user/logout");
            exit();
        }
        */
        /* DATOS SGR 
          $sgrArr = $this->sgr_model->get_sgr();
          foreach ($sgrArr as $sgr) {
          $this->sgr_id = (float) $sgr['id'];
          $this->sgr_nombre = $sgr['1693'];
          $this->sgr_cuit = $sgr['1695'];
          }
         */

        //$this->anexo = (isset($this->session->userdata['anexo_code'])) ? $this->session->userdata['anexo_code'] : "06";
        //if (isset($this->session->userdata['period']))
        //    $this->period = $this->session->userdata['period'];

        /* TIME LIMIT */
        set_time_limit(28800);
        ini_set("error_reporting", "E_ALL");
    }

    function Anexo($filename = null) {

        $customData = array();
        $customData['base_url'] = base_url();
        /*
          $customData['module_url'] = base_url() . 'sgr/';

          $customData['sgr_nombre'] = $this->sgr_nombre;
          $customData['sgr_id'] = $this->sgr_id;

          $customData['js'] = array($this->module_url . "assets/jscript/dashboard.js" => 'Dashboard JS', $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min_1.js" => 'Validate');
          $customData['css'] = array($this->module_url . "assets/css/dashboard.css" => 'Dashboard CSS');
         */
        $nombre_fichero = './anexos_pacc';
        /*
          if (file_exists($nombre_fichero)) {
          //echo "El fichero $nombre_fichero existe";
          } else {
          echo "El fichero $nombre_fichero no existe";
          if(!mkdir('./anexos_pacc',0777))
          echo '</br>No lo abrio!!';
          }
         */
         //ini_set("error_reporting", E_ALL);
        //echo '</br>Filename:'.$filename; 
        
        
        if (!$filename) {
            
            exit();
            
        }
        $process_filename = $filename;

        
        //$filename_ext = ($this->anexo == '09') ? ".pdf" : ".xls";
        //echo 'Ext:'.$filename_ext.'</br>';
        //$filename = $process_filename . $filename_ext;
        //list($sgr, $anexo, $date) = explode("_", $filename);
        //echo 'Process'.$process_filename.'</br>'; 
        //echo $filename.'</br>';
        $this->process($process_filename);
    }

    function Process($filename) {

        //echo 'Process'.$filename.'</br>';

        $customData = array();
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'pacc/';
        //$customData['sgr_nombre'] = $this->sgr_nombre;
        //$customData['sgr_id'] = $this->sgr_id;

        $customData['js'] = array($this->module_url . "assets/jscript/dashboard.js" => 'Dashboard JS', $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min_1.js" => 'Validate');
        $customData['css'] = array($this->module_url . "assets/css/dashboard.css" => 'Dashboard CSS');


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
            $new_filename = $new_filename . '0' . $hoy['seconds'] . '-PACC-POA.xls';
        else
            $new_filename = $new_filename . $hoy['seconds'] . '-PACC-POA.xls';

        //var_dump($new_filename);
        $uploadpath = getcwd() . '/anexos_pacc/' . $filename;
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

        if ($cols > 51) {
            echo 'Error en cantidad de Columnas!!';
            return;
        }
        if ($data->sheets[0]['cells'][2][2] != 'COMP / SUBCOMP') {
            echo 'Error de posicionamiento!!';
            return;
        }
        $lines = $data->sheets[0]['numRows'];


        $Arr_name = array(
            "COMP_SCOMP",
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
            "USD_TOTAL"
        );




        $container_POA = 'container.pacc_POA';
        $id = 1;
        $headerArr['filename'] = $new_filename;
        $headerArr['date'] = $hoy;
        for ($index_l = 1; $index_l <= $lines; $index_l++) {
            for ($index_c = 2; $index_c <= $cols; $index_c++) {
                $var = $data->sheets[0]['cells'][$index_l][$index_c];
                //echo '$index_l:'.$index_l.'  $index_c:'.$index_c.'  $var:'.$var;
                $var = utf8_encode($var);

                $headerArr[$index_l][$Arr_name[($index_c - 2)]] = $var;
            }
        
            //var_dump($headerArr[$index_l][]);
            //$result = $this->put_array_POA($id, $container_POA, $headerArr[$index_l]);
        }
        //var_dump($headerArr);

        $headerArr['borrado'] = 0;

        $container_POA = 'container.pacc_POA';


        $result = $this->put_array_POA($new_filename, $container_POA, $headerArr);
    }

    function put_array_POA($id, $container, $val_arr = array()) {

        $thisArr = array();

        foreach ($val_arr as $idframe => $value) {
            $thisFrame = $this->get_frame($idframe, array('type', 'container'));
            $thisArr[$idframe] = $this->cast_type($value, $thisFrame['type']);
        }
        //var_dump($thisArr);
        //----check 4 id
        if (!is_numeric($id)) {
            $id = $this->genid($container);
        }

        $criteria = array('id' => $id);
        $update = array('$set' => $thisArr);
        $options = array('upsert' => true, 'w' => true);

        //var_dump($container, json_encode($criteria), json_encode($val_arr));
        $result = $this->mongowrapper->pacc->$container->update($criteria, $val_arr, $options);
        $thisArr['id'] = $id;
        return $thisArr;
    }

    function get_frame($idframe = '') {
        $fields = array();
        if (func_num_args() > 1) {
            $fields = func_get_arg(1);
            $fields[] = 'cname';
            $fields[] = 'idframe';
        }

        $query = array('idframe' => (int) $idframe);
        //var_dump(json_encode($query),$fields);
        $thisObj = $this->mongowrapper->db->frames->findOne($query, $fields);
        //var_dump($thisObj);
        return $thisObj;
    }

    function cast_type($input, $type) {
        $retval = '';

        /* PARCHE */
        if (is_string($input))
            $input = (htmlentities($input, ENT_QUOTES, 'UTF-8'));

        switch ($type) {
            case 'checklist':
                $retval = (array) $input;
                break;
            case 'combo':
                $retval = (array) $input;
                break;
            case 'combodb':
                $retval = (array) $input;
                break;
            case 'radio':
                $retval = (array) $input;
                break;
            case 'subform':
                $retval = (array) $input;
                break;
            case 'date':
                $retval = $input['Y'] . '-' . $input['m'] . '-' . $input['d'];
                break;
            case 'datetime':
                $retval = $input['Y'] . '-' . $input['m'] . '-' . $input['d'] . ' ' . $input['h'] . ':' . $input['i'];
                break;
            default:
                $retval = $input;
                break;
        }
        //var_dump($input,$type,$retval);
        return $retval;
    }

    function genid($container) {
        $insert = array();
        $id = mt_rand();
        $trys = 10;
        $i = 0;
        //---if passed specific id
        if (func_num_args() > 1) {
            $id = (double) func_get_arg(1);
            $passed = true;
            //echo "passed: $id<br>";
        }
        $hasone = false;

        while (!$hasone and $i <= $trys) {//---search until found or $trys iterations
            //while (!$hasone) {//---search until found or 1000 iterations
            $query = array('id' => $id);
            $result = $this->mongowrapper->db->selectCollection($container)->findOne($query);
            $i++;
            if ($result) {
                if ($passed) {
                    show_error("id:$id already Exists in $container");
                    $hasone = true;
                    break;
                } else {//---continue search for free id
                    $id = mt_rand();
                }
            } else {//---result is null
                $hasone = true;
            }
        }
        if (!$hasone) {//-----cant allocate free id
            show_error("Can't allocate an id in $container after $trys attempts");
        }
        //-----make basic object
        $insert['id'] = $id;
        //----Allocate id in the collection (may result in empty docs)
        $this->mongowrapper->db->selectCollection($container)->save($insert);
        return $id;
    }

    function limpiar($String) {

        $String = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", $String);
        $String = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "A", $String);
        $String = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "I", $String);
        $String = str_replace(array('í', 'ì', 'î', 'ï'), "i", $String);
        $String = str_replace(array('é', 'è', 'ê', 'ë'), "e", $String);
        $String = str_replace(array('É', 'È', 'Ê', 'Ë'), "E", $String);
        $String = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $String);
        $String = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "O", $String);
        $String = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $String);
        $String = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "U", $String);
        $String = str_replace(array('[', '^', '´', '`', '¨', '~', ']'), "", $String);
        $String = str_replace("ç", "c", $String);
        $String = str_replace("Ç", "C", $String);
        $String = str_replace("ñ", "n", $String);
        $String = str_replace("Ñ", "N", $String);
        $String = str_replace("Ý", "Y", $String);
        $String = str_replace("ý", "y", $String);

        $String = str_replace("&aacute;", "a", $String);
        $String = str_replace("&Aacute;", "A", $String);
        $String = str_replace("&eacute;", "e", $String);
        $String = str_replace("&Eacute;", "E", $String);
        $String = str_replace("&iacute;", "i", $String);
        $String = str_replace("&Iacute;", "I", $String);
        $String = str_replace("&oacute;", "o", $String);
        $String = str_replace("&Oacute;", "O", $String);
        $String = str_replace("&uacute;", "u", $String);
        $String = str_replace("&Uacute;", "U", $String);
        return $String;
    }

    function empty_xls_advice($anexo) {
        switch ($anexo) {
            case 06:
                $msg = "El campo no puede estar vacío, y debe contener alguno de los siguientes parámetros : INCORPORACION, INCREMENTO TENENCIA ACCIONARIA o DISMINUCION DE CAPITAL SOCIAL";
                break;
            case 061:
                $msg = "El campo no puede estar vacío y debe tener 11 caracteres sin guiones.";
                break;
            case 062:
                $msg = "Debe tener 11 caracteres numéricos sin guiones.";
                break;
        }

        $legend = '<li><i class="fa fa-info-circle"></i> Error archivo no tiene la informacion necesaria</li><li>' . $msg . '</li>';
        return $legend;
    }

}
