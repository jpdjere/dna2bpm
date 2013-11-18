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

        $this->anexo = ($_REQUEST['anexo']) ? $_REQUEST['anexo'] : "06";
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
        //$customData['goals'] = (array) $this->sgr_model->get_goals($this->idu);
        // Projects
        $projects = $this->sgr_model->get_config_item('projects');
        $customData['projects'] = $projects['items'];

        $customData['anexo'] = $this->anexo;
        $customData['anexoList'] = $this->AnexosDB();
        $customData['anexoTitle'] = $this->oneAnexoDB($this->anexo);
        $customData['anexoTitleCap'] = strtoupper($this->oneAnexoDB($this->anexo));

        //SET PERIOD
        $this->set_period();
        $customData['sgr_period'] = $this->period;

        // UPLOAD ANEXO
        $upload = $this->upload_file();

        if ($upload['success']) {
            $customData['message'] = $upload['message'];
        } else {
            $customData['message'] = $upload['message'];
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

    function AnexosDB() {
        $anexosArr = $this->sgr_model->get_anexos();
        $result = "";
        foreach ($anexosArr as $anexo) {
            $result .= '<li><a href="?anexo=' . $anexo['number'] . '">' . $anexo['title'] . '</a></li>';
        }
        return $result;
    }

    function oneAnexoDB() {
        $anexoValues = $this->sgr_model->get_anexo($this->anexo);
        return $anexoValues['title'];
    }

    function Anexo($filename = null) {

        if (!$filename) {
            exit();
        }

        $filename = $filename . ".xls";
        list($sgr, $anexo, $date) = explode("_", $filename);
        $user_id = -315924963; //(int) ($this->idu);        
        if ($sgr != $this->sgr_id) {
            var_dump($sgr, $this->sgr_id);
            exit();
        }


        //echo dirname(__FILE__); //$this->module_url;

        $uploadpath = getcwd() . '/anexos_sgr/' . $filename;

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
        $headerArr = array();
        $valuesArr = array();
        for ($index = 1; $index <= $data->sheets[0]['numCols']; $index++) {
            // echo strtolower($data->sheets[0]['cells'][1][$index]). ",";
            $headerArr[] = $data->sheets[0]['cells'][1][$index];
        }

        $header = "lib_" . $anexo . "_header";
        $result_head = (array) $this->load->library("validators/" . $header, $headerArr);

        if (!$result_head['result']) {
            for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {

                for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
                    $count = $data->rowcount();
                    
                    $valuesSql .= "<p>" . trim($data->sheets[0]['cells'][$i][$j]) . "</p>";
                    $stack = array('fieldValue' => trim($data->sheets[0]['cells'][$i][$j]), "row" => $i, "col" => $j, "count"=>$count);
                    array_push($valuesArr, $stack);
                }
                //echo $valuesSql;
            }


            /* VALIDATIONS */

            $data = "lib_" . $anexo . "_data";






            $result_data = (array) $this->load->library("validators/" . $data, $valuesArr);

            echo "<pre>";
            var_dump($result_data);
            echo "</pre>";
        } else {
            //ERROR            
            var_dump("HEAD ERROR ", $result_head['result']);
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

    function set_period() {
        if ($this->input->post("input_period")) {
            $newdata = array('period' => $this->input->post("input_period"));
            $this->session->set_userdata($newdata);
        }
    }

    function unset_period() {
        if ($this->input->post("input_unset_period")) {
            $this->session->unset_userdata('period');
        }
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

    // OFFLINE FALLBACK
    function offline() {
        // testeo reemplazo appcache
        $customData = array();
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'sgr/';
        $this->render('offline', $customData);
    }

    function Inbox() {
        $this->load->model('msg');

        $customData['lang'] = (array) $this->user->get_user($this->idu);
        $customData['user'] = (array) $this->user->get_user($this->idu);
        $customData['inbox_icon'] = 'icon-envelope';
        $customData['inbox_title'] = $this->lang->line('Inbox');
        $customData['js'] = array($this->base_url . "dna2/assets/jscript/inbox.js" => 'Inbox JS');
        $customData['css'] = array($this->base_url . "dna2/assets/css/dashboard.css" => 'Dashboard CSS');
        //debug


        $mymgs = $this->msg->get_msgs($this->idu);

        foreach ($mymgs as $msg) {
            $msg['msgid'] = $msg['_id'];
            $msg['date'] = substr($msg['checkdate'], 0, 10);
            $msg['icon_star'] = (isset($msg['star']) && $msg['star'] == true) ? ('icon-star') : ('icon-star-empty');
            $msg['read'] = (isset($msg['read']) && $msg['read'] == true) ? ('muted') : ('');
            if (isset($msg['from'])) {
                $userdata = $this->user->get_user($msg['from']);
                if (!is_null($userdata))
                    $msg['sender'] = $userdata->nick;
                else
                    $msg['sender'] = "No sender";
            }else {
                $msg['sender'] = "System";
            }
            $customData['mymsgs'][] = $msg;
        }

        $this->render('inbox', $customData);
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

                $files_list .= anchor($prefix . $dir['name'], $dir['name']) . '<br>';
            }
        }


        if (!empty($customData['files'])) {
            foreach ($customData['files'] as $file) {
                //echo anchor($prefix.$file['name'], $file['name']).'<br>';
                list($sgr, $anexo, $filedate) = explode("_", $file['name']);

                if ($anexo == $this->anexo && (float) $sgr == $this->sgr_id) {
                    list($filename, $extension) = explode(".", $file['name']);
                    $files_list .= '<li><a href="anexo/' . $filename . '">' . $file['name'] . '</a></li>';
                }
            }
        }
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
        $cpData['username'] = $user->lastname . ", " . $user->name;
        $cpData['usermail'] = $user->email;
        // Profile 
        //$cpData['profile_img'] = get_gravatar($user->email);

        $cpData['gravatar'] = (isset($user->avatar)) ? $this->base_url . $user->avatar : get_gravatar($user->email);

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
