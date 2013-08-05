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
        //----habilita acceso a todo los metodos de este controlador
        $this->user->authorize('modules/genias/controllers/genias');
        $this->load->config('config');
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('app');
        $this->load->model('user/user');
        $this->load->model('bpm/bpm');
        $this->load->model('user/rbac');
        $this->load->model('genias/genias_model');
        $this->load->helper('genias/tools');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'genias/';
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (float) $this->session->userdata('iduser');

        ini_set('xdebug.var_display_max_depth', 100);
    }

    function Index() {
        $customData = array();
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'genias/';
        $customData['titulo'] = "";
        $customData['js'] = array($this->module_url . "assets/jscript/dashboard.js" => 'Dashboard JS', $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min.js" => 'Validate');
        $customData['css'] = array($this->module_url . "assets/css/dashboard.css" => 'Dashboard CSS');
        $customData['goals'] = (array) $this->genias_model->get_goals($this->idu);

        // Projects
        $projects = $this->genias_model->get_config_item('projects');
        $customData['projects'] = $projects['items'];

        $genias = $this->get_genia();
        $rol = $genias['rol'];
        $mygoals = array();
        $customData['goal_cantidad_total'] = 0;
        $customData['goal_cumplidas_total'] = 0;

        // Inicializo contador de metas
        $genias_list = $this->genias_model->get_genias();
        foreach ($genias_list as $mygenia) {
            $customData['goal_cantidad'][(string) $mygenia['_id']] = 0;
            $customData['goal_cumplidas'][(string) $mygenia['_id']] = 0;
        }

//           if($this->idu==-1108639299){//
//            var_dump($customData['goal_cumplidas']);
//            exit();
//           } 

        $goals = $this->genias_model->get_goals((int) $this->idu);

        foreach ($goals as $goal) {

            // === Nombre del proyecto y select de proyectos para las metas
            $goal['select_project'] = "";
            foreach ($customData['projects'] as $current) {
                if ($current['id'] == $goal['proyecto']) {
                    $goal['proyecto_name'] = $current['name'];
                    $goal['select_project'].="<option  selected='selected' value='{$current['id']}' >{$current['name']}</option>";
                } else {
                    $goal['select_project'].="<option  value='{$current['id']}' >{$current['name']}</option>";
                }
            }

            // === get status case ===
            if (isset($goal['case'])) {
                $case = $this->genias_model->get_case($goal['case']);
                $goal['url_case'] = "{$this->base_url}bpm/engine/run/model/genia_metas/{$goal['case']}";
            }

            if (isset($case['status']) && $case['status'] == 'open') {
                $goal['status'] = 'Pendiente aprobación';
                $goal['status_icon_class'] = 'icon-time';
                $goal['status_class'] = 'well status_open';
                $goal['label_class'] = 'btn-danger';
            } elseif (isset($case['status']) && $case['status'] == 'closed') {
                $goal['status'] = 'Aprobado';
                $goal['status_icon_class'] = 'icon-thumbs-up';
                $goal['status_class'] = 'well status_closed';
                $goal['label_class'] = '';
                //----computo calculos para metas activas de este mes
                //if ($goal['desde'] >= date('Y-m-01') and $goal['hasta'] <= date('Y-m-t')) {
                if (true) {
                    $customData['goal_cantidad_total']+=$goal['cantidad'];
                    $customData['goal_cumplidas_total']+=count($goal['cumplidas']);

                    $customData['goal_cantidad'][$goal['genia']]+=$goal['cantidad'];
                    $customData['goal_cumplidas'][$goal['genia']]+=count($goal['cumplidas']);
                }
            } else {
                $goal['status'] = 'undefined';
                $goal['status_class'] = 'well status_null';
                $goal['label_class'] = '';
            }


            // --- 
            $owner = (array) $this->user->get_user($goal['idu']);
            $goal['owner'] = (!empty($owner)) ? ("{$owner['lastname']}, {$owner['name']}") : ("Desconocido");
            $goal['cumplidas_count'] = count($goal['cumplidas']);

            //Conteo de metas


            $mygoals[] = $goal;
            //var_dump($goal);
        }
        $ratio = $customData['goal_cantidad_total'] - $customData['goal_cumplidas_total'];
        if ($ratio >= ($customData['goal_cantidad_total'] * .7))
            $customData['resumen_class'] = 'alert-success';
        if ($ratio >= ($customData['goal_cantidad_total'] * .3) and $ratio <= ($customData['goal_cantidad_total'] * .7))
            $customData['resumen_class'] = 'alert-block';
        if ($ratio <= ($customData['goal_cantidad_total'] * .3))
            $customData['resumen_class'] = 'alert-error';
        //var_dump($customData);
        // Tabs Genias Counter



        $customData['metas'] = $mygoals;
        $this->render('dashboard', $customData);
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
        $cpData['genia'] = $this->get_genia('nombre');
        $cpData['rol'] = $this->get_genia('rol');
        $cpData['rol_icono'] = ($cpData['rol'] == 'coordinador') ? ('icon-group') : ('icon-user');

        // Listado de genias de donde soy user
        $mygenias = $this->get_genia();
        $cpData['genias'] = $mygenias['genias'];
        $cpData = array_replace_recursive($customData, $cpData);

        /* Inbox Count MSgs */
        $mymgs = $this->msg->get_msgs($this->idu);
        $cpData['inbox_count'] = $mymgs->count();

        $this->ui->compose($file, 'layout.php', $cpData);
    }

    //* ------ METAS ------ */

    function add_goal() {

        $this->load->module('bpm/engine');
        $customData = $this->lang->language;
        $data = $this->input->post('data');
        $mydata = array(
            'idu' => $this->idu
        );
        foreach ($data as $k => $v) {
            $mydata[$v['name']] = $v['value'];
        }


        $date = date_create_from_format('d-m-Y', '01-' . $mydata['desde']);
        $month = $date->format('m');
        $year = $date->format('Y');
        $daycount = cal_days_in_month(CAL_GREGORIAN, $month, $year); // 31

        $mydata['desde'] = "$year-$month-01";
        $mydata['hasta'] = "$year-$month-$daycount";


        $mydata['id'] = $this->app->genid('container.genias_goals'); // create new ID 
        $mydata['cumplidas'] = array();
        //@todo  COMPLETAR
        // Busco nombre del proyecto
        $proyectos = $this->genias_model->get_config_item('projects');
        foreach ($proyectos['items'] as $v) {
            if ($mydata['proyecto'] == $v['id'])
                $mydata['proyecto_nombre'] = $v['name'];
        }

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
        $this->output->set_output(1);
        //---la meta deberia estar disponible cuando este caso este en estado: finished
        // Todo 
        //echo $id_goal;
    }

    function update_goal() {
        $this->genias_model->update_goal();
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
        $customData['js'] = array($this->module_url . "assets/jscript/tasks.js" => 'Tasks JS');
        $customData['genia'] = $this->get_genia('nombre');
        $projects = $this->genias_model->get_config_item('projects');
        $customData['projects'] = $projects['items'];
        $customData['titulo'] = "Tareas";
        $customData['tasks'] = array();
        foreach ($projects['items'] as $k => $item) {
            $items = $this->get_tasks($k);
            $customData['tasks'][$k] = array('id' => $item['id'], 'name' => $item['name'], 'items' => $this->get_tasks($k));
        }
        // var_dump($customData);
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
            $user = $this->user->get_user($task['idu']);

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
                'finalizada' => $task['finalizada'],
                'autor' => $user->lastname . ", " . $user->name
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
        $url = $this->module_url . 'empresas_mapa';
        $customData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'json_url' => $url,
        );
        $customData['titulo'] = "Mapa";
        $this->render('map', $customData);
    }

    /* ------ SCHEDULER ------ */

    // Render page
    function scheduler() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $customData['titulo'] = "Agenda";
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
        $customData['titulo'] = "Contactos";
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
            $this->base_url . "jscript/ext/src/ux/form/SearchField.js" => 'Search Field',
            $this->module_url . 'assets/jscript/ext.settings.js' => 'Ext Settings',
            $this->module_url . 'assets/jscript/empresas.ext.data.js' => 'Base Data',
            $this->module_url . 'assets/jscript/empresasAlt/btnSync.js' => 'btnSync',
            $this->module_url . 'assets/jscript/empresasAlt/visitas.grid.js' => 'Visitas Empresas',
            $this->module_url . 'assets/jscript/empresasAlt/empresas.form.js' => 'Form Empresas',
            $this->module_url . 'assets/jscript/empresasAlt/ext.viewport.empresas.tab.js' => 'ViewPort',
        );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        $this->ui->makeui('ext.ui.testcache.php', $cpData);
    }

    function Form_empresas_alt($parm = null) {

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
        $cpData['titulo'] = "Escenario Pyme";

        $cpData['js'] = array(
            $this->module_url . 'assets/jscript/onlineStatus.js' => 'Online/Offline Status',
            $this->base_url . "jscript/ext/src/ux/form/SearchField.js" => 'Search Field',
            $this->module_url . 'assets/jscript/ext.settings.js' => 'Ext Settings',
            $this->module_url . 'assets/jscript/empresas.ext.data.js' => 'Base Data',
            $this->module_url . 'assets/jscript/empresasAlt/btnSync.js' => 'btnSync',
            $this->module_url . 'assets/jscript/empresasAlt/visitas.grid.js' => 'Visitas Empresas',
            $this->module_url . 'assets/jscript/empresasAlt/empresas.form.js' => 'Form Empresas',
            $this->module_url . 'assets/jscript/empresasAlt/ext.viewport.empresas.tab.js' => 'ViewPort',
        );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        $this->ui->makeui('ext.ui.php', $cpData);
    }

    function Listado_empresas($parm = null) {

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
            $this->module_url . 'assets/jscript/empresasAlt/btnSync.js' => 'btnSync',
            $this->module_url . 'assets/jscript/empresasAlt/empresas.grid.js' => 'Grid Empresas',
            $this->module_url . 'assets/jscript/empresasAlt/ext.viewport.empresas.table.js' => 'ViewPort',
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

        $imgParameter = ($parameter == NULL) ? '5c-FF-35-7C-96-FB' : $this->base_url . 'qr/info/tablet/' . $parameter;
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

    function Empresas_mapa($idgenia = null) {
        $genias = $this->genias_model->get_genia($this->idu);
        $query = array();
        $provincias = array();
        foreach ($genias['genias'] as $thisGenia) {
            if (isset($thisGenia['query_empresas'])) {
                foreach ($thisGenia['query_empresas'] as $key => $value) {
                    //---me gurado provincias para filtrar partidos
                    if ($key == 4651) {
                        $provincias[] = $value;
                    }
//                    if (isset($query[$key])) {
//                        if (is_array($query[$key])) {
//                            array_push($query[$key]['$in'], $value);
//                        } else {
//                            $original = $query[$key];
//                            $query[$key] = array();
//                            $query[$key]['$in'] = array($original, $value);
//                        }
//                    } else {
//                        $query[$key] = $value;
//                    }
                    if (isset($query[$key])) {
                        if (is_array($query[$key])) {
                            array_push($query[$key]['$in'], $value);
                        } else {
                            $original = $query[$key];
                            $query[$key] = array();
                            $query[$key]['$in'] = array($original, $value);
                        }
                    } else {
                        if (is_array($value)) {
                            $query[$key]['$in'] = $value;
                        } else {
                            $query[$key] = $value;
                        }
                    }
                }
            }
        }
        $query = array(); //----dps lo sacamos
        $query['$and'] = array(
            array('7819'=>array('$exists' => true)),
            array('7819'=>array('$ne' => '')),
        );

        //echo json_encode($query);exit;
        $this->load->model('app');
        $debug = false;
        $compress = false;


        $empresas = $this->genias_model->get_empresas($query);
        $rtnArr = array();
        foreach ($empresas as $empresa) {
            $rtnArr['markers'][] = array(
                "latitude" => $empresa['7820'],
                "longitude" => $empresa['7819'],
                "title" => $empresa['1693'],
                "tags" => array("genia"),
                "icon" => "factory_marker.png",
                "content" =>$empresa['1693'].'<br/>'. $empresa['1715']
            );
        }
        //var_dump($empresas);
        //$rtnArr['totalCount'] = count($empresas);
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

    function Empresas($idgenia = null) {
        $genias = $this->genias_model->get_genia($this->idu);
        $query = array();
        $provincias = array();
        foreach ($genias['genias'] as $thisGenia) {
            if (isset($thisGenia['query_empresas'])) {
                foreach ($thisGenia['query_empresas'] as $key => $value) {
                    //---me gurado provincias para filtrar partidos
                    if ($key == 4651) {
                        $provincias[] = $value;
                    }

                    if (isset($query[$key])) {
                        if (is_array($query[$key])) {
                            array_push($query[$key]['$in'], $value);
                        } else {
                            $original = $query[$key];
                            $query[$key] = array();
                            $query[$key]['$in'] = array($original, $value);
                        }
                    } else {
                        if (is_array($value)) {
                            $query[$key]['$in'] = $value;
                        } else {
                            $query[$key] = $value;
                        }
                    }
                }
            }
        }

        $this->load->model('app');
        $debug = false;
        $compress = false;
        /*
         * Hacer un regla para obtener las empresas de la genia sea por partidos o por provincia
         * Basado en el idgenia
         */
        //---cacheo los partidos
        $partidos = array();
        if (isset($query['4651'])) {
            foreach ($provincias as $prov) {
                $par = $this->app->get_ops(58, $prov);
                $partidos+=$par;
            }
        }

        $empresas = $this->genias_model->get_empresas($query);
        for ($i = 0; $i < count($empresas); $i++) {
            $thisEmpresa = &$empresas[$i];
            if (isset($thisEmpresa[1699])) {
                $thisEmpresa['partido_txt'] = (isset($partidos[$thisEmpresa[1699][0]])) ? $partidos[$thisEmpresa[1699][0]] : $thisEmpresa[1699][0];
            } else {
                $thisEmpresa['partido_txt'] = '<span class="label label-important"><i class="icon-info-sign"/> COMPLETAR! </span>';
            }
        }
        //var_dump($empresas);
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

    function Visitas($idgenia = null) {
        $genias = $this->genias_model->get_genia($this->idu);
        $query = array();

        $this->load->model('app');

        $debug = false;
        $compress = false;

        $visitas = $this->genias_model->get_visitas($query, $this->idu);
        $rtnArr = array();
        $rtnArr['totalCount'] = count($visitas);
        $rtnArr['rows'] = $visitas;
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

    function Encuestas($idgenia = null) {
        $genias = $this->genias_model->get_genia($this->idu);
        $query = array();

        $this->load->model('app');

        $debug = false;
        $compress = false;

        $result = $this->genias_model->get_encuestas($query, $this->idu);
        $rtnArr = array();
        $rtnArr['totalCount'] = count($result);
        $rtnArr['rows'] = $result;
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

    function make_partidos() {
        $debug = false;

        $partidos = $this->app->get_option(58);
        $rtnArr['totalCount'] = count($partidos['data']);
        $rtnArr['rows'] = $partidos['data'];
        if (!$debug) {
            header('Content-type: application/json;charset=UTF-8');
            echo json_encode($rtnArr);
        } else {
            var_dump(json_encode($rtnArr));
        }
    }

    function get_genia($attr = null) {

        $genia = $this->genias_model->get_genia($this->idu);
        if ($attr == 'rol') {
            return $genia['rol'];
        } else {
            return $genia;
        }
    }
    
     function Empresas2($idgenia = null) {
        $genias = $this->genias_model->get_genia($this->idu);
        $query = array();
        $provincias = array();
        foreach ($genias['genias'] as $thisGenia) {
            if (isset($thisGenia['query_empresas'])) {
                foreach ($thisGenia['query_empresas'] as $key => $value) {
                    //---me gurado provincias para filtrar partidos
                    if ($key == 4651) {
                        $provincias[] = $value;
                    }

                    if (isset($query[$key])) {
                        if (is_array($query[$key])) {
                            array_push($query[$key]['$in'], $value);
                        } else {
                            $original = $query[$key];
                            $query[$key] = array();
                            $query[$key]['$in'] = array($original, $value);
                        }
                    } else {
                        if (is_array($value)) {
                            $query[$key]['$in'] = $value;
                        } else {
                            $query[$key] = $value;
                        }
                    }
                }
            }
        }

        $this->load->model('app');
        $debug = false;
        $compress = false;
        /*
         * Hacer un regla para obtener las empresas de la genia sea por partidos o por provincia
         * Basado en el idgenia
         */
        //---cacheo los partidos
        $partidos = array();
        if (isset($query['4651'])) {
            foreach ($provincias as $prov) {
                $par = $this->app->get_ops(58, $prov);
                $partidos+=$par;
            }
        }

        $empresas = $this->genias_model->get_empresas2($query);
        

        exit();
        for ($i = 0; $i < count($empresas); $i++) {
            $thisEmpresa = &$empresas[$i];
            if (isset($thisEmpresa[1699])) {
                $thisEmpresa['partido_txt'] = (isset($partidos[$thisEmpresa[1699][0]])) ? $partidos[$thisEmpresa[1699][0]] : $thisEmpresa[1699][0];
            } else {
                $thisEmpresa['partido_txt'] = '<span class="label label-important"><i class="icon-info-sign"/> COMPLETAR! </span>';
            }
            echo $thisEmpresa[1695];
            echo "<br>";
        }
        //var_dump($empresas);
        exit();
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
    

}

// close
