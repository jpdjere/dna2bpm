<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * genias
 *
 */
class Genias extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->config('config');
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('app');
        $this->load->model('user/user');
        $this->load->model('bpm/bpm');
        $this->load->module('bpm/engine');
        $this->load->model('user/rbac');
        $this->load->model('genias/genias_model');
        $this->load->helper('genias/tools');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'genias/';
        $this->user->authorize();
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (float) $this->session->userdata('iduser');

        ini_set('xdebug.var_display_max_depth', 100);
    }

    function Index() {

        $customData = array();
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'genias/';
        $customData['js'] = array($this->module_url . "assets/jscript/dashboard.js" => 'Dashboard JS');
        $customData['goals'] = (array) $this->genias_model->get_goals($this->idu);

        // Projects
        $projects = $this->genias_model->get_config_item('projects');
        $customData['projects'] = $projects['items'];
        $genia=$this->get_genia();
        var_dump($genia);
        $customData['genia']=$genia['nombre'];
        
        // usuario o coordinador? 
        $idus=array($this->idu);
        if(!empty($genia['rol'])&& $genia['rol']=='coordinador' ){
            $idus+=$genia['users'];
        }

        foreach($idus as $idu){
        foreach ($this->genias_model->get_goals($idu) as $goal) {
            foreach ($customData['projects'] as $current) {
                if ($current['id'] == $goal['proyecto'])
                    $goal['proyecto_name'] = $current['name'];
            }
            // get status case
            if (isset($goal['case']))
                $case = $this->genias_model->get_case($goal['case']);
            if (isset($case['status']) && $case['status'] == 'open') {
                $goal['status'] = 'Pendiente aprobación';
                $goal['status_icon_class'] = 'icon-time';
                $goal['status_class'] = 'well status_open';
                $goal['label_class'] = 'label-important';
            } elseif (isset($case['status']) && $case['status'] == 'closed') {
                $goal['status'] = 'Aprobado';
                $goal['status_icon_class'] = 'icon-thumbs-up';
                $goal['status_class'] = 'well status_closed';
                $goal['label_class'] = 'label-success';
            } else {
                $goal['status'] = 'undefined';
                $goal['status_class'] = 'well status_null';
                $goal['label_class'] = '';
            }
            
            $owner = $this->user->get_user((double)$idu);
            $goal['owner']= "{$owner->lastname}, {$owner->name}";
            

//
            $goal['cumplidas'] = 0;
            //$metas_cumplidas = ($goal['cumplidas'] == $goal['cantidad']) ? (true) : (false);
            //$goal['class'] = ($metas_cumplidas) ? ('well') : ('alert alert-info');       
//            $days_back = date('Y-m-d', strtotime("-5 day"));
//            if (($goal['hasta'] < $days_back) && (!$metas_cumplidas))
//            $goal['class'] = 'alert alert-error';

            $customData['goals'][] = $goal;
        }//each
        } //each
        $this->render('dashboard', $customData);

    }

    function render($file, $customData) {

        $this->load->model('user/user');
        $this->user->authorize();
        $cpData = $this->lang->language;
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
        $cpData['username'] = $user->email;
        // Profile 
        //$cpData['profile_img'] = get_gravatar($user->email);
        $cpData['gravatar'] = get_gravatar($user->email);
        $cpData['genia'] = $this->get_genia('nombre');
        $cpData['rol'] = $this->get_genia('rol');
         
        $cpData = array_replace_recursive($customData, $cpData);
        $this->ui->compose($file, 'layout.php', $cpData);
    }

    //* ------ METAS ------ */

    function add_goal() {

        $this->user->authorize();
        $customData = $this->lang->language;
        $data = $this->input->post('data');
        $mydata = array(
            'idu' => $this->idu
            );
        foreach ($data as $k => $v) {
            $mydata[$v['name']] = $v['value'];
        }

        $date = date_create_from_format('d-m-Y', $mydata['desde']);
        $mydata['desde'] = date_format($date, 'Y-m-d');
        $date = date_create_from_format('d-m-Y', $mydata['hasta']);
        $mydata['hasta'] = date_format($date, 'Y-m-d');
        $mydata['id'] = $this->app->genid('container.genias_goals'); // create new ID 
        //@todo  COMPLETAR
        // Busco nombre del proyecto
        $proyectos = $this->genias_model->get_config_item('projects');
        foreach ($proyectos['items'] as $v) {
            if ($mydata['proyecto'] == $v['id'])
                $mydata['proyecto_nombre'] = $v['name'];
        }

        $mydata['genia'] = $this->get_genia('nombre');
        //----genero un caso---------------------------------
        $idwf = 'genia_metas';
        $case = $this->bpm->gen_case($idwf);
        $mydata['case'] = $case;
        $id_goal = $this->genias_model->add_goal($mydata);
        //----------------------------------------------------
        $this->engine->Startcase('model', $idwf, $case, true);
        $thisCase = $this->bpm->get_case($case);
        $user = $this->user->get_user_safe($this->idu);
        $thisCase['data'] = $mydata;
        $thisCase['data']['owner'] = (array) $user;



        //var_dump($case,$thisCase,$user);
        $this->bpm->save_case($thisCase);

        $this->engine->Run('model', $idwf, $case);
        //reviento lo que sea que me devuelva el run
        $this->output->set_output('ok');
        //---la meta deberia estar disponible cuando este caso este en estado: finished
    }

    function programs() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $customData['genia'] = $this->get_genia('nombre');
        $this->render('programs', $customData);
    }

    /* ------ TAREAS ------ */

    // Render page
    function tasks() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $customData['css'] = array($this->module_url . "assets/css/tasks.css" => 'Genias CSS');
        $customData['genia'] = $this->get_genia('nombre');
        $projects = $this->genias_model->get_config_item('projects');
        $customData['projects'] = $projects['items'];

       $customData['tasks'] = array();
        foreach ($projects['items'] as $k => $item) {
            $items = $this->get_tasks($k);
            $customData['tasks'][$k] = array('id' => $item['id'], 'name' => $item['name'], 'items' => $this->get_tasks($k));
        }
;
        //var_dump($projects['items']);
        //$customData['tasks']= print_r($this->get_tasks("1"));

        $this->render('tasks', $customData);
    }

    function add_task() {
        $this->user->authorize();
        $customData = $this->lang->language;

        define('DURACION', 60);

        $serialized = $this->input->post('data');
        $mydata = compact_serialized($serialized);
        list($d, $m, $y) = explode("-", $mydata['dia']);
        $mydata['dia'] = iso_encode($mydata['dia']);
        $mydata['start'] = mktime($mydata['hora'], $mydata['minutos'], '00', $m, $d, $y);
        $mydata['end'] = mktime($mydata['hora'], $mydata['minutos'] + DURACION, '00', $m, $d, $y);
        $mydata['idu'] = $this->idu;
        $mydata['finalizada'] = (isset($mydata['finalizada'])) ? (1) : (0);

        if (!is_numeric($mydata['id'])) {
            $mydata['id'] = $this->app->genid('container.genias_tasks'); // create new ID    
        } else {
            $mydata['id'] = (integer) $mydata['id'];
        }

        $this->genias_model->add_task($mydata);
        echo json_encode($mydata);
    }

    function remove_task() {
        $id = $this->input->post('id');
        $this->genias_model->remove_task($id);
        echo "tasks.{$this->idu}.$id";
    }

    function get_tasks($proyecto) {
        
        // Mapeo proyecto id - > orden de display en fullcalendar
        $projects = $this->genias_model->get_config_item('projects');
        $items = $projects['items'];

        $proyecto = $items[$proyecto]['id'];
   
        $tasks = $this->genias_model->get_tasks($this->idu, $proyecto);
        if (!$tasks->count())
            return array();

        $mytasks = array();
        foreach ($tasks as $task) {
            $dia = iso_decode($task['dia']);
            $item = array(
                'id' => $task['id'],
                'title' => $task['title'],
                'start' => $task['start'],
                'end' => $task['end'],
                'allDay' => false,
                'detail' => $task['detail'],
                'dia' => $dia,
                'hora' => $task['hora'],
                'minutos' => $task['minutos'],
                'proyecto' => $task['proyecto'],
                'finalizada' => $task['finalizada']
                );

            $mytasks[] = $item;
        }

        return $mytasks;
    }

    // Calls get_tasks and print the result
    function print_tasks() {
        if (is_numeric($this->uri->segment(3))) {
            $proyecto = $this->uri->segment(3);
            $tasks = $this->get_tasks($proyecto);
            echo json_encode($tasks);
        }
    }

    /* ------ MAP ------ */

    // Render page
    function map() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $customData['css'] = array(
            $this->base_url . "map/assets/css/map.css" => 'Map CSS'
            );
        $customData['js'] = array(
            'http://maps.google.com/maps/api/js?sensor=true' => 'Google API',
            $this->base_url . 'map/assets/jscript/jquery.ui.map.v3/jquery.ui.map.full.min.js' => 'Jquery.ui.map V3',
            $this->module_url . 'assets/jscript/map/map.json.js' => 'Load Json Map',
            );
        $url = $this->module_url . 'assets/json/empresasGenia.json';
        $customData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'json_url' => $url,
            );
        $this->render('map', $customData);
    }

    /* ------ SCHEDULER ------ */

    // Render page
    function scheduler() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $customData['js'] = array($this->module_url . "assets/jscript/scheduler.js" => 'Inicio Scheduler JS', $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min.js" => 'Validate');
        $customData['css'] = array($this->module_url . "assets/css/genias.css" => 'Genias CSS');
        $projects = $this->genias_model->get_config_item('projects');
        $customData['projects'] = $projects['items'];
        //print_r($customData['projects']);
        $year = date('Y');
        $month = date('m');

        $this->render('scheduler', $customData);
    }

    /* ------ CONTACTS ------ */

    // Render page

    function contacts() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $this->render('contacts', $customData);
    }

    /* ------ ??? ------ */

    function Form($parm = null) {

        //echo $this->idu;   
        //---Libraries
        $this->load->library('parser');
        $this->load->library('ui');

        $cpData = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Escenario Pyme.';


        $cpData['js'] = array(
            $this->module_url . 'assets/jscript/onlineStatus.js' => 'Online/Offline Status',
            $this->module_url . 'assets/jscript/ext.data.js' => 'Base Data',
            $this->module_url . 'assets/jscript/form.js' => 'Objetos Custom D!',
            $this->module_url . 'assets/jscript/ext.viewport.js' => 'ViewPort'
            
            );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            );
        $this->ui->makeui('ext.ui.php', $cpData);
    }

    function Form_empresas($parm = null) {

        //echo $this->idu;   
        //---Libraries
        $this->load->library('parser');
        $this->load->library('ui');

        $cpData = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Escenario Pyme.';


        $cpData['js'] = array(
            $this->module_url . 'assets/jscript/onlineStatus.js' => 'Online/Offline Status',
            $this->base_url . "jscript/ext/src/ux/form/SearchField.js" => 'Search Field',
            //$this->module_url . 'assets/jscript/ext.settings.js' => 'Ext Settings',
            $this->module_url . 'assets/jscript/empresas.ext.data.js' => 'Base Data',
            $this->module_url . 'assets/jscript/empresas.grid.js' => 'Grid Empresas',
            $this->module_url . 'assets/jscript/empresas.form.js' => 'Form Empresas',
            $this->module_url . 'assets/jscript/ext.viewport.empresas.js' => 'ViewPort',
            );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            );
        $this->ui->makeui('ext.ui.php', $cpData);
    }

    function Teststore() {
        //echo $this->idu;   
        //---Libraries
        $this->load->library('parser');
        $this->load->library('ui');

        $cpData = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Test ofline json store<br/> <h3>mirá la consola</h3>';


        $cpData['js'] = array(
            $this->module_url . 'assets/jscript/store-test/ext.data.js' => 'Base Data',
            $this->module_url . 'assets/jscript/store-test/start.js' => 'Start Test',
            );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            );
        $this->ui->makeui('ext.ui.php', $cpData);
    }

    function Geniausers() {
        //echo $this->idu;   
        //---Libraries
        $this->load->library('parser');
        $this->load->library('ui');

        $cpData = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Formulario Usuarios | Genias.';


        $cpData['js'] = array(
            $this->module_url . 'assets/jscript/ext.data.users.js' => 'Base Data',
            $this->module_url . 'assets/jscript/form.users.js' => 'Objetos Custom D!',
            $this->module_url . 'assets/jscript/ext.viewport.users.js' => '',
            );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            );
        $this->ui->makeui('ext.ui.php', $cpData);
    }

    function App() {
        /* REMOTE */
        echo '{"user":"' . $this->idu . '"}';
        echo $this->user->admin->showall($this->idu);
    }

    /* ------ CONFIG ------ */

    // Render page

    function qr($parameter = null) {

        $imgParameter = ($parameter == NULL) ? '5c-FF-35-7C-96-FB' : $this->base_url.'qr/info/tablet/'.$parameter;
        $this->load->module('qr');
        echo $this->qr->gen($imgParameter);
    }

    function config() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $projects = $this->genias_model->get_config_item('projects');
        $customData['js'] = array($this->module_url . "assets/jscript/config.js" => 'Config JS');
        $customData['projects'] = $projects['items'];

        $this->render('config', $customData);
    }

    // Change projects id from here
    function config_set_projects() {
        $this->user->authorize();
        $myProjects = $this->input->post('data');

        // Preparo array para la base
        $items = array();
        for ($i = 0; $i < count($myProjects); $i+=2) {
            $items[] = array('id' => (int) $myProjects[$i + 1]['value'], 'name' => $myProjects[$i]['value']);
        }
        $mydata = array('name' => 'projects', 'items' => $items);

        $error = $this->genias_model->config_set($mydata);
        echo (is_null($error)) ? ("Registro guardado") : ("No se ha podido guardar el registro");
    }

    function empresas_test($idgenia = null) {
        $this->load->model('app');
        $debug = false;
        $prov = 'JUJ';
        $provincias = $this->app->get_ops(39);
        $this->load->library('table');

        $this->table->set_heading(array('Provincia', 'Cantidad', 'Size', 'gziped'));

        foreach ($provincias as $key => $valor) {
            $query = array('4651' => $key);
            $empresas = $this->genias_model->get_empresas($query);
            $rtnArr = array();
            $rtnArr['totalCount'] = count($empresas);
            $rtnArr['rows'] = $empresas;
            $this->table->add_row(
                array(
                    "prov::$key::$valor",
                    count($empresas),
                    number_format(strlen(json_encode($rtnArr)) / 1024, 2) . " Kb",
                    number_format(strlen(gzcompress(json_encode($rtnArr))) / 1024, 2) . " Kb"
                    )
                );
            //echo "prov::$key::$valor::" . count($empresas) . ":: <strong>" . number_format(strlen(json_encode($rtnArr)) / 1024, 2) . " Kb</strong><br/>";
        }
        echo $this->table->generate();
        $this->output->enable_profiler(TRUE);
    }
    
    function empresas($idgenia = null) {
        $this->load->model('app');
        $debug = false;
        $compress = false;
        /*
         * Hacer un regla para obtener las empresas de la genia sea por partidos o por provincia
         * Basado en el idgenia
         */
        $query = array('4651' => 'JUJ');
        $empresas = $this->genias_model->get_empresas($query);
        $rtnArr = array();
        $rtnArr['totalCount'] = count($empresas);
        $rtnArr['rows'] = $empresas;
        if (!$debug) {
            header('Content-type: application/json;charset=UTF-8');
            if ($compress) {
                header('Content-Encoding: gzip');
                print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
                echo gzcompress(json_encode($rtnArr));
            } else {
                echo json_encode($rtnArr);
            }
        } else {
            var_dump(json_encode($rtnArr));
        }
    }
    
    // ======= DATOS GENIAS ======= //
 
    function get_genia($attr=null){
       // nombre, rol, id
       $genia=$this->genias_model->get_genia($this->idu);
       if(isset($attr)){      
           return (!empty($genia[$attr]))?($genia[$attr]):('');
       }else{
         return $genia;
       }
    }


}// close





