<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * sgr
 *
 */
class Sgr extends MX_Controller {

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
        $this->load->model('sgr/sgr_model');
        $this->load->helper('sgr/tools');
        $this->load->library('session');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'sgr/';
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));


        // IDU : Chequeo de sesion
        $this->idu = (int) $this->session->userdata('iduser');
        if (!$this->idu) {
            header("$this->module_url/user/logout");
            exit();
        }

        /* DATOS SGR */
        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
        }


        $this->anexo = ($this->session->userdata['anexo_code']) ? $this->session->userdata['anexo_code'] : "06";
        $this->period = $this->session->userdata['period'];
    }

    function Index() {

        $customData = array();
        $customData['sgr_nombre'] = $this->sgr_nombre;
        $customData['sgr_id'] = $this->sgr_id;
        $customData['sgr_id_encode'] = base64_encode($this->sgr_id);
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'sgr/';
        $customData['titulo'] = "";
        $customData['js'] = array($this->module_url . "assets/jscript/dashboard.js" => 'Dashboard JS', $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min_1.js" => 'Validate');
        $customData['css'] = array($this->module_url . "assets/css/dashboard.css" => 'Dashboard CSS');

        // Projects
        $projects = $this->sgr_model->get_config_item('projects');
        $customData['projects'] = $projects['items'];

        $customData['anexo'] = $this->anexo;
        $customData['anexo_list'] = $this->AnexosDB();
        $customData['anexo_title'] = $this->oneAnexoDB($this->anexo);
        $customData['anexo_title_cap'] = strtoupper($this->oneAnexoDB($this->anexo));

        // UPLOAD ANEXO
        $upload = $this->upload_file();
        $customData['processed_tab'] = $this->get_processed_tab($this->anexo);
        $customData['processed_list'] = $this->get_processed($this->anexo);



        if ($upload['success']) {
            $customData['message'] = $upload['message'];
            $customData['success'] = "success";
        } else {
            $customData['message'] = $upload['message'];
            $customData['success'] = "error";
        }

        /*
         * SET PERIOD
         */
        if (!$upload) {
            if (!$this->session->userdata['period']) {
                $customData['message'] = '<i class="fa fa-bookmark"></i> Para procesar debe seleccionar el periodo a informar';
            }
        }
        $error_set_period = $this->set_period();
        
        $customData['sgr_period'] = $this->period;
        if ($error_set_period) {
            switch ($error_set_period) {
                case '1':                    
                    $error_msg = '<i class="fa fa-bookmark"></i> El Periodo seleccionado es Invalido...';
                    break;

                default:
                    $new_period = anchor('sgr', 'Volver <i class="fa fa-external-link" alt="Volver"></i>');
                    $get_period = $this->sgr_model->get_period_info($this->anexo, $this->sgr_id, $error_set_period);
                    $error_msg = '<i class="fa fa-bookmark"></i> El periodo del ' . str_replace('-', '/', $error_set_period) . ' ya fue informado [ ' . $get_period['filename'] . ' ] | ' . $new_period;
                    $customData['post_period'] = $error_set_period;
                    $customData['rectifica'] = true;
                    break;
            }
            $customData['message'] = $error_msg;
            $customData['success'] = "error";
        }

        //RECTIFY
        if ($this->session->userdata['rectify']) {
            $customData['rectify_message'] = '<i class="fa fa-bookmark"></i> Para terminar la rectificación deberá asociar el perido ' . $this->session->userdata['period'] . ' a un Archivo o a "SIN MOVIMIENTO"';
        }


        // FILE BROWSER
        $fileBrowserData = $this->file_browser();

        if (!empty($fileBrowserData)) {
            $resultRender = array_replace_recursive($customData, $fileBrowserData);
            $this->render('dashboard', $resultRender);
        } else {
            $this->render('dashboard', $customData);
        }
        //RENDER
    }

    function Anexo_code($parameter) {
        /* BORRO SESSION RECTIFY */
        $this->session->unset_userdata('rectify');
        $this->session->unset_userdata('others');
        $this->session->unset_userdata('period');

        $newdata = array('anexo_code' => $parameter);
        $this->session->set_userdata($newdata);
        redirect('/sgr');
    }

    function AnexosDB() {
        $anexosArr = $this->sgr_model->get_anexos();
        $result = "";
        foreach ($anexosArr as $anexo) {
            $result .= '<li><a href="sgr/anexo_code/' . $anexo['number'] . '">' . $anexo['title'] . '</a></li>';
        }
        return $result;
    }

    function oneAnexoDB() {
        $anexoValues = $this->sgr_model->get_anexo($this->anexo);
        return $anexoValues['title'];
    }

    function oneAnexoDB_short() {
        $anexoValues = $this->sgr_model->get_anexo($this->anexo);
        return $anexoValues['short'];
    }

    function Anexo($filename = null) {
        $customData = array();
        $customData['sgr_nombre'] = $this->sgr_nombre;
        $customData['sgr_id'] = $this->sgr_id;


        $get_period = $this->sgr_model->get_processed($this->anexo, $this->sgr_id);


        if ($get_period) {

            //V????????????????
        }


        if (!$filename) {
            exit();
        }

        $filename = $filename . ".xls";
        list($sgr, $anexo, $date) = explode("_", $filename);
        $user_id = (int) ($this->idu);
        if ($sgr != $this->sgr_id) {
            var_dump($sgr, $this->sgr_id);
            exit();
        }

        //echo dirname(__FILE__); //$this->module_url;

        $original = array($this->sgr_id . '_', $this->anexo . '_', '_');
        $replaced = array($this->oneAnexoDB_short($this->anexo) . ' - ', strtoupper($this->sgr_nombre) . ' - ', ' ');
        $new_filename = str_replace($original, $replaced, $filename);


        $uploadpath = getcwd() . '/anexos_sgr/' . $filename;
        $movepath = getcwd() . '/anexos_sgr/' . $anexo . '/' . $new_filename;



        //  $uploadpath = base_url() . 'anexos_sgr/' . $filename;

        $this->load->library('excel_reader2');
        $data = new Excel_reader2($uploadpath);

        /*
          Example Usage:

          $data = new Spreadsheet_Excel_Reader("test.xls");

          Retrieve formatted value of cell (first or only sheet):

          $data->val($row,$col)

          Or using column names:

          $data->val(10,'AZ')

          From a sheet other than the first:

          $data->val($row,$col,$sheet_index)

          Retrieve cell info:

          $data->type($row,$col);
          $data->raw($row,$col);
          $data->format($row,$col);
          $data->formatIndex($row,$col);

          Get sheet size:
          $data->rowcount();
          $data->colcount();

          $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

          $data->sheets[0]['numRows'] - count rows
          $data->sheets[0]['numCols'] - count columns

          $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell
          $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
          $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format
          $data->sheets[0]['cellsInfo'][$i][$j]['format'] = Excel-style Format string of cell
          $data->sheets[0]['cellsInfo'][$i][$j]['formatIndex'] = The internal Excel index of format

          $data->sheets[0]['cellsInfo'][$i][$j]['colspan']
          $data->sheets[0]['cellsInfo'][$i][$j]['rowspan']

         */
        $stack = array();
        $fields = "";
        $result = "";
        $result_header = "";
        $error = false;
        $headerArr = array();
        $valuesArr = array();
        for ($index = 1; $index <= $data->sheets[0]['numCols']; $index++) {
            $headerArr[] = $data->sheets[0]['cells'][1][$index];
        }

        $header = "lib_" . $anexo . "_header";
        $result_head = (array) $this->load->library("validators/" . $header, $headerArr);

        if (!$result_head['result']) {
            for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
                for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {

                    if (!empty($data->sheets[0]['cells'][$i][1])) {
                        $count = $data->rowcount();
                        $fields = trim($data->sheets[0]['cells'][$i][$j]);
                        $stack = array('fieldValue' => $fields, "row" => $i, "col" => $j, "count" => $count);
                        array_push($valuesArr, $stack);
                    }
                }
            }


            /* VALIDATIONS */

            if (!$count) {

                $result_header = '<li><i class="fa fa-bookmark"></i> Error archivo no tiene la informacion necesaria</li>';
                $error = true;
            }


            $data_values = "lib_" . $anexo . "_data";
            $lib_error = "lib_" . $anexo . "_error_legend";
            $this->load->library("validators/" . $lib_error);
            $result_data = (array) $this->load->library("validators/" . $data_values, $valuesArr);

            foreach ($result_data['data'] as $result_data) {
                if (!empty($result_data['error_code'])) {
                    $result .= "<li>" . $this->$lib_error->return_legend($result_data['error_code'], $result_data['error_row'], $result_data['error_input_value']) . "</li>";
                    $error = true;
                }
            }
        } else {
            //ERROR    
            $result_header_desc = "<h3>El modelo de Anexo es incorrecto, restan las siguientes columnas</h3>";
            foreach ($result_head['result'] as $error_head) {

                $result_header .= "<li>" . $error_head . "</li>";
                $error = true;
            }
            $result_header = $result_header_desc . $result_header;

            $error = true;
        }



        if (!$error) {
            $model = "model_" . $anexo;
            $this->load->Model($model);
            $array = array();
            //Check Duplicates
            for ($i = 2; $i <= $data->rowcount(); $i++) {
                $result_data_ = (array) $this->$model->check($data->sheets[0]['cells'][$i]);
                if ($result_data_[5779] == 1) {
                    if ($result_data_[1695] != NULL) {
                        $array[] = $result_data_[1695];
                    }
                }
            }

            if (count(array_unique($array)) < count($array)) {
                $duplicated = true;
                $customData['message'] = 'Se esta intentando INCORPORAR el mismo CUIT mas de una vez dentro de el excel.';
                $this->render('errors', $customData);
                unlink($uploadpath);
            } else {
                $duplicated = false;
            }


            //INSERT UPDATE
            if (!$duplicated) {
                for ($i = 2; $i <= $data->rowcount(); $i++) {
                    $result = (array) $this->$model->check($data->sheets[0]['cells'][$i]);
                    $result['filename'] = $new_filename;
                    $result['sgr_id'] = (int) $this->sgr_id;
                    $save = (array) $this->$model->save($result);
                    //success
                }

                /* SET PERIOD */
                if ($save) {
                    $result = array();
                    $result['filename'] = $new_filename;
                    $result['sgr_id'] = (int) $this->sgr_id;
                    $result['anexo'] = $this->anexo;
                    $save_period = (array) $this->$model->save_period($result);

                    if ($save_period['status'] == "ok") {

                        copy($uploadpath, $movepath) or die("Unable to copy $uploadpath to $movepath.");
                        unlink($uploadpath);

                        /* RENDER */
                        $customData['anexo_title_cap'] = strtoupper($this->oneAnexoDB($this->anexo));
                        $customData['sgr_period'] = $this->period;
                        $customData['anexo_list'] = $this->AnexosDB();
                        $customData['message'] = 'El Archivo fue importado con exito';
                        $this->render('success', $customData);
                    } else {

                        $error = true;
                    }
                }
            }
        }

        /* ERROR CASE */
        if ($error) {
            $customData['anexo_title_cap'] = strtoupper($this->oneAnexoDB($this->anexo));
            $customData['sgr_period'] = $this->period;
            $customData['anexo_list'] = $this->AnexosDB();
            $customData['message_header'] = $result_header;
            $customData['message'] = $result;
            $this->render('errors', $customData);

            unlink($uploadpath);
        }



        /*
          /* consulta
          $config['hostname'] = "localhost";
          $config['username'] = "root";
          $config['password'] = "root";
          $config['database'] = "forms2";
          $config['dbdriver'] = "mysql";
          $config['dbprefix'] = "";
          $config['pconnect'] = FALSE;
          $config['db_debug'] = TRUE;
          $config['cache_on'] = FALSE;
          $config['cachedir'] = "";
          $config['char_set'] = "utf8";
          $config['dbcollat'] = "utf8_general_ci";
          $db = $this->load->database($config, true, false);
         */
        /*
         * Mysql Query & Insert
         * 
         * $SQL = "SELECT * FROM `" . $this->oneAnexoDB($this->anexo) . "` WHERE `cuit_sgr` = '30-70937729-5'";
          $DB_forms2 = $db;
          $query = $DB_forms2->query($SQL);

          if ($query->num_rows() > 0) {
          foreach ($query->result() as $row) {
          //  var_dump($row);
          }
          }


          $sql = "INSERT INTO $table (";
          for ($index = 1; $index <= $data->sheets[0]['numCols']; $index++) {
          $sql.= strtolower($data->sheets[0]['cells'][1][$index]) . ", ";
          }

          $sql = rtrim($sql, ", ") . " ) VALUES ( ";
          for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
          $valuesSQL = '';
          for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
          $valuesSql .= "\"" . $data->sheets[0]['cells'][$i][$j] . "\", ";
          }
          echo $sql . rtrim($valuesSql, ", ") . " ) <br>";
          } */
    }

    function print_anexo($parameter = null) {

        if (!$parameter) {
            exit();
        }
        $parameter = urldecode($parameter);
        $anexo = ($this->session->userdata['anexo_code']) ? $this->session->userdata['anexo_code'] : '06';
        $model = "model_" . $anexo;
        $this->load->model($model);


        echo "<style>
            #header {
    background: none no-repeat scroll 0 0 #FFFFFF;
    height: 100px;
    position: relative;
}
#header-dna {
    background: url('http://www.accionpyme.mecon.gob.ar/dna2/style/img/orgullo.jpg') no-repeat scroll 0 0 rgba(0, 0, 0, 0);
    height: 100px;
    width: 580px;
}
#header-logos {
    background: url('http://www.accionpyme.mecon.gob.ar/dna2/style/img/orgullo.jpg') no-repeat scroll -750px 0 rgba(0, 0, 0, 0);
    height: 100px;
    position: absolute;
    right: 0;
    top: 0;
    width: 210px;
}
        table { border-collapse: collapse;
        font-family: Futura, Arial, sans-serif; font-size:9px; text-align: center}
        caption { font-size: larger; margin: 1em auto; } 
        th, td { padding: .65em; }
        th, thead { background: #000; color: #fff; border: 1px solid #000; }
        td { border: 1px solid #777; }
        </style>";
        echo '<div id="header">
            <div id="header-dna"></div>
            <div id="header-logos"></div>
        </div>';

        echo "
ACINDAR PYMES S.G.R. | C.U.I.T.: 30-70937729-5
[Anexo 06]<br> SGR Movimientos de Capital Social Importado por: [ADMINISTRADOR]<br>
Archivo Procesado: $parameter<br>
Información correspondiente al período 11/2013 | IMPRIMIR | <a href='javascript:window.close();'>Cerrar Anexo</a>";

        $get_anexo = $this->$model->get_anexo_info($this->anexo, $parameter);
        echo $get_anexo;
        // echo $parameter;
    }

    function set_period() {
        $rectify = $this->input->post("rectify");
        $period = $this->input->post("input_period");
        $others = $this->input->post("others");
        $anexo = $this->input->post("anexo");

        if ($period) {

            $this->session->unset_userdata('period');
            $this->session->unset_userdata('rectify');
            $this->session->unset_userdata('others');

            $date_string = date('Y-m', strtotime('-1 month', strtotime(date('Y-m-01'))));
            list($month, $year) = explode("-", $period);
            $limit_month = strtotime('-1 month', strtotime(date('Y-m-01')));
            $set_month = strtotime(date($year . '-' . $month . '-01'));

            if ($rectify) {
                $newdata = array('period' => $period, 'rectify' => $rectify, 'others' => $others);
                /* PERIOD SESSION */
                $this->session->set_userdata($newdata);
                redirect('/sgr');
            } else {
                if ($limit_month <= $set_month) {
                    return 1; // Posterior al mes actual
                } else {
                    $get_period = $this->sgr_model->get_period_info($this->anexo, $this->sgr_id, $period);
                    if ($get_period) {
                        return $this->input->post("input_period"); //Ya fue informado                    
                    } else {
                        $newdata = array('period' => $period);
                        $this->session->set_userdata($newdata);
                        redirect('/sgr');
                    }
                }
            }
        }
    }

    function unset_period() {
        $this->session->unset_userdata('rectify');
        $this->session->unset_userdata('others');
        $this->session->unset_userdata('period');
        redirect('/sgr');
    }

    function upload_file() {
        try {
            if ($this->input->post("submit")) {
                $this->load->library("app/uploader");
                $result = (array) $this->uploader->do_upload();

                return $result;
            }
            //to render ->
        } catch (Exception $err) {
            log_message("error", $err->getMessage());
            return show_error($err->getMessage());
        }
    }

    function set_no_movement() {

        $data = $this->input->post('data');
        $period = $data['no_movement'];
        $anexo = ($this->session->userdata['anexo_code']) ? $this->session->userdata['anexo_code'] : '06';
        $model = "model_" . $anexo;
        $this->load->model($model);

        if (!$this->session->userdata['rectify']) {
            $get_period = $this->sgr_model->get_period_info($anexo, $this->sgr_id, $period);
        }


        if (!$get_period) {
            $result = array();
            $result['period'] = $period;
            $result['filename'] = "SIN MOVIMIENTOS";
            $result['sgr_id'] = (int) $this->sgr_id;
            $result['anexo'] = $anexo;
            $save_period = (array) $this->$model->save_period($result);
            echo "ok";
        }
    }

    // OFFLINE FALLBACK
    function offline() {
        // testeo reemplazo appcache
        $customData = array();
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'sgr/';
        $this->render('offline', $customData);
    }

    function get_processed_tab($anexo) {
        $list_files = "";
        for ($i = 2011; $i <= date(Y); $i++) {
            $processed = $this->sgr_model->get_processed($anexo, $this->sgr_id, $i);
            $processed = array($processed);
            foreach ($processed as $file) {
                if ($file)
                    $list_files .= '<li><a href="#tab_processed' . $i . '" data-toggle="tab">Procesados ' . $i . '</a></li>';
            }
        }
        return $list_files;
    }

    function get_processed($anexo) {
        $list_files = '';
        for ($i = 2011; $i <= date(Y); $i++) {
            $list_files .= '<div id="tab_processed' . $i . '" class="tab-pane">             
            <div class="alert {resumen_class}" id="' . $i . '"><ul>';
            $processed = $this->sgr_model->get_processed($anexo, $this->sgr_id, $i);
            foreach ($processed as $file) {
                $download = anchor('anexos_sgr/' . $file['name'], ' <i class="fa fa-download" alt="Descargar"> DESCARGAR</i>', array('class' => 'btn btn-success'));
                $print_filename = ($file['filename'] == "SIN MOVIMIENTOS") ? $file['filename'] : substr($file['filename'], 0, -25);
                $print_file = anchor('/sgr/print_anexo/' . $file['filename'], ' <i class="fa fa-external-link" alt="Imprimir"> IMPRIMIR</i>', array('target' => '_blank', 'class' => 'btn btn-success'));

                $rectifica_link_class = ($this->session->userdata['period']) ? 'rectifica-warning' : 'rectifica-link';
                $rectify = anchor($file['period'] . "/" . $anexo, '<i class="fa fa-undo" alt="Rectificar"> RECTIFICAR</i>', array('class' => $rectifica_link_class . 'btn btn-success' ));

                if ($file['filename'] == "SIN MOVIMIENTOS") {
                    $list_files .= '<li><i class="fa fa-download" alt="Descargar"> DESCARGAR</i> | <i class="fa fa-external-link" alt="Imprimir"> IMPRIMIR</i>  | ' . $rectify . ' | ' . $print_filename . ' [' . $file['period'] . '][' . $file['status'] . '] </li>';
                } else {
                    $list_files .= "<li>" . $download . " | " . $print_file . " | " . $rectify . " | " . $print_filename . "  [" . $file['period'] . "][" . $file['status'] . "] </li>";
                }
            }
            $list_files .= '</ul></div>
        </div>';
        }
        return $list_files;
    }

    function file_browser() {
        $segment_array = $this->uri->segment_array();

        // first and second segments are the controller and method
        $controller = array_shift($segment_array);
        $method = array_shift($segment_array);

        // absolute path using additional segments
        $path_in_url = 'anexos_sgr';
        foreach ($segment_array as $segment)
            $path_in_url.= $segment . '/';
        $absolute_path = getcwd() . '/' . $path_in_url;
        $absolute_path = rtrim($absolute_path, '/');


        if (is_dir($absolute_path)) {

            // link generation helper
            $this->load->helper('url');

            $dirs = array();
            $files = array();
            // fetching directory
            if ($handle = @opendir($absolute_path)) {
                while (false !== ($file = readdir($handle))) {
                    if (( $file != "." AND $file != "..")) {
                        if (is_dir($absolute_path . '/' . $file)) {
                            $dirs[]['name'] = $file;
                        } else {
                            $files[]['name'] = $file;
                        }
                    }
                }
                closedir($handle);
                sort($dirs);
                sort($files);
            }
            // parent folder
            // ensure it exists and is the first in array
            if ($path_in_url != '')
                array_unshift($dirs, array('name' => ' '));

            // view data
            $fileData = array(
                'controller' => $controller,
                'method' => $method,
                'virtual_root' => getcwd(),
                'path_in_url' => $path_in_url,
                'dirs' => $dirs,
                'files' => $files,
            );
            //$this->render('dashboard', $customData);
            //           

            /* CALL RENDER */
            $files_list = $this->render_file_browser($fileData);
            $customData['files_list'] = $files_list;
            return $customData;
        }
        else {
            // is it a file?
            if (is_file($absolute_path)) {
                // open it
                header('Cache-Control: no-store, no-cache, must-revalidate');
                header('Cache-Control: pre-check=0, post-check=0, max-age=0');
                header('Pragma: no-cache');

                $text_types = array(
                    'xls'
                );
                $ext = explode('.', $absolute_path);
                // download necessary ?
                if (in_array($ext[count($ext) - 1], $text_types)) {
                    header('Content-Type: text/plain');
                } else {
                    header('Content-Description: File Transfer');
                    header('Content-Length: ' . filesize($absolute_path));
                    header('Content-Disposition: attachment; filename=' . basename($absolute_path));
                }

                @readfile($absolute_path);
            } else {
                //@show_404();
                return "";
            }
        }
    }

    function render_file_browser($customData) {

        $files_list = "";
        $prefix = $customData['controller'] . '/' . $customData['method'] . '/' . $customData['path_in_url'];
        if (!empty($customData['dirs'])) {
            foreach ($customData['dirs'] as $dir) {
                //PRINT DIRECTORIES
                //$files_list .= anchor($prefix . $dir['name'], $dir['name']) . '<br>';
            }
        }


        if (!empty($customData['files'])) {
            foreach ($customData['files'] as $file) {
                list($sgr, $anexo, $filedate, $filetime) = explode("_", $file['name']);

                if ($anexo == $this->anexo && (float) $sgr == $this->sgr_id) {
                    list($filename, $extension) = explode(".", $file['name']);

                    /* Vars */
                    $process_file = anchor('/sgr/anexo/' . $filename, '<i class="fa fa-external-link" alt="Procesar"> PROCESAR</i>');
                    $process_file_disabled = '<i class="fa fa-external-link" alt="Procesar"> PROCESAR</i>';
                    $download = anchor('anexos_sgr/' . $file['name'], '<i class="fa fa-download" alt="Descargar"> DESCARGAR</i>');


                    /* check if file exist */
                    if ($this->session->userdata['period']) {
                        //Process
                        $files_list .= '<li> ' . $download . " | " . $process_file . ' | PENDIENTE ' . $filedate . ' ' . $filetime . ' </li>';
                    } else {
                        //Just Download 
                        $files_list .= '<li> ' . $download . " | " . $process_file_disabled . ' | PENDIENTE ' . $filedate . ' ' . $filetime . '</li>';
                    }
                }
            }
        }

        $files_list = ($files_list != "") ? $files_list : "<h5>No hay Archivos Pendientes</h5>";
        return $files_list;
    }

    function render($file, $customData) {
        $this->load->model('user/user');
        $this->load->model('msg');
        $this->load->language('inbox');
        $cpData['lang'] = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['nolayout'] = (in_array('nolayout', $segments)) ? '1' : '0';
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'idu' => $this->idu
        );
        $user = $this->user->get_user($this->idu);
        $cpData['user'] = (array) $user;
        $cpData['isAdmin'] = $this->user->isAdmin($user);
        $cpData['username'] = strtoupper($user->lastname . ", " . $user->name);
        $cpData['usermail'] = $user->email;
        // Profile 
        //$cpData['profile_img'] = get_gravatar($user->email);

        $cpData['gravatar'] = (isset($user->avatar)) ? $this->base_url . $user->avatar : get_gravatar($user->email);
        $cpData['rol'] = "Usuarios";
        $cpData['rol_icono'] = ($cpData['rol'] == 'coordinador') ? ('icon-group') : ('icon-user');

        $cpData = array_replace_recursive($customData, $cpData);

        /* Inbox Count MSgs */
        $mymgs = $this->msg->get_msgs($this->idu);
        $cpData['inbox_count'] = $mymgs->count();

        // offline mark
        $cpData['is_offline'] = ($this->uri->segment(3) == 'offline') ? ('offline') : ('');

        $this->ui->compose($file, 'layout.php', $cpData);
    }

}
