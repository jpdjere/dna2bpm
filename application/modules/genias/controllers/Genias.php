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
        $this->user->autodiscover=false;
        $this->user->authorize('modules/genias/controllers/genias');
        $this->load->config('config');
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('app');
        $this->load->model('user/user');
        $this->load->model('bpm/bpm');
        $this->load->model('user/rbac');
        $this->load->model('app');
        $this->load->model('genias/genias_model');
        $this->load->helper('genias/tools');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'genias/';
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));

        // IDU : Chequeo de sesion
        $this->idu = (int) $this->session->userdata('iduser');
        if (!$this->idu) {
            header("$this->module_url/user/logout");
            exit();
        }

        ini_set('xdebug.var_display_max_depth', 100);
    }

    function test() {
        $customData['base_url'] = $this->base_url;
        $customData['module_url'] = $this->module_url;
        $this->parser->parse('test', $customData);
    }

    function Index($periodo='12') {


        $customData = array();
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'genias/';
        $customData['titulo'] = "";
        $customData['js'] = array(
        $this->module_url . "assets/jscript/dashboard.js" => 'Dashboard JS', 
        $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min_1.js" => 'Validate',
        $this->base_url . "dashboard/assets/bootstrap-wysihtml5/js/plugins/fullcalendar-2.3.1/lib/moment.min.js" => 'Moment'
        );
       
       //== resumenes
       $today=getdate();
       $m= date("m", mktime(0, 0, 0, $today['mon']-$periodo, $today['mday'], $today['year']));
       $Y= date("Y", mktime(0, 0, 0, $today['mon']-$periodo, $today['mday'], $today['year']));
       $myperiodo="$m/[^\/]*/$Y";
       $customData['resumen_periodo']= date("Y, F", mktime(0, 0, 0, $today['mon']-$periodo, $today['mday'], $today['year']));
       $customData['resumen_2']=$this->get_resumen_visitas($myperiodo);
       $customData['resumen_4']=$this->get_resumen_visitas_instituciones($myperiodo);

        $customData['css'] = array($this->module_url . "assets/css/dashboard.css" => 'Dashboard CSS');
        //$customData['goals'] = (array) $this->genias_model->get_goals($this->idu);
        // Projects
        $projects = $this->genias_model->get_config_item('projects');
        $customData['projects'] = $projects['items'];

        $genias = $this->get_genia();

        $rol = $genias['rol'];
        $mygoals = array();
        $customData['goal_cantidad_total_2'] = 0;
        $customData['goal_cumplidas_total_2'] = 0;
        $customData['goal_cantidad_total_4'] = 0;
        $customData['goal_cumplidas_total_4'] = 0;

        // Inicializo contador de metas
        $genias_list = $this->genias_model->get_genias();
        foreach ($genias_list as $mygenia) {
            $customData['goal_cantidad_2'][(string) $mygenia['_id']] = 0;
            $customData['goal_cumplidas_2'][(string) $mygenia['_id']] = 0;
            $customData['goal_cantidad_4'][(string) $mygenia['_id']] = 0;
            $customData['goal_cumplidas_4'][(string) $mygenia['_id']] = 0;

        }

        //=== periodo
        $hasta=date('Y-m-d');
        $d=getdate();
        $periodo=(int)$periodo;
        $desde=date("Y-m-d", mktime(0, 0, 0, $d['mon']-$periodo, $d['mday'], $d['year']));

        $goals = $this->genias_model->get_goals((int) $this->idu,$desde,'2016-01-01');

        foreach ($goals as $goal) {
            // === Nombre del proyecto y select de proyectos para las metas
            $goal['select_project'] = "";
            foreach ($customData['projects'] as $current) {
                if ($current['id'] == $goal['proyecto']) {
                    $goal['proyecto_name'] = $current['name'];
                    $goal['select_project'].="<option  selected='selected' value='{$current['id']}' >{$current['name']}</option>";
                } else {
                    $goal['select_project'].="<option value='{$current['id']}'>{$current['name']}</option>";
                }
            }

            // === STATUS ===
            if (isset($goal['case'])) {
                $case = $this->genias_model->get_case($goal['case']);
                $goal['url_case'] = "{$this->base_url}bpm/engine/run/model/genia_metas/{$goal['case']}";
            }
            $goal['status'] = isset($case['status']) ? ($case['status']) : ('Sin definir');

            if ($case['status'] == 'closed') {

                
                if($goal['proyecto']=='2'){
                $customData['goal_cantidad_2'][$goal['genia']]+=$goal['cantidad'];
                $customData['goal_cumplidas_2'][$goal['genia']]+=count($goal['cumplidas']);
                $customData['goal_cantidad_total_2']+=$goal['cantidad'];
                $customData['goal_cumplidas_total_2']+=count($goal['cumplidas']);

                }
                if($goal['proyecto']=='4'){
                $customData['goal_cantidad_4'][$goal['genia']]+=$goal['cantidad'];
                $customData['goal_cumplidas_4'][$goal['genia']]+=count($goal['cumplidas']);
                $customData['goal_cantidad_total_4']+=$goal['cantidad'];
                $customData['goal_cumplidas_total_4']+=count($goal['cumplidas']);

                }
            }


            // --- 
            $owner = (array) $this->user->get_user($goal['idu']);
            $goal['owner'] = (!empty($owner)) ? ("{$owner['lastname']}, {$owner['name']}") : ("Desconocido");

            $goal['cumplidas_count'] = count($goal['cumplidas']);
            
            //Conteo de metas


            $mygoals[] = $goal;
        }
        
        
        // ==== Clases para Alerts
        foreach($customData['goal_cantidad_2'] as $genia=>$cant)
        	$customData['goal_cantidad_2_alert'][$genia]=$this->dashboard_get_class($customData['goal_cumplidas_2'][$genia],$cant);   

        // Alerts cumplidas Institucional
         foreach($customData['goal_cantidad_4'] as $genia=>$cant)
         	$customData['goal_cantidad_4_alert'][$genia]=$this->dashboard_get_class($customData['goal_cumplidas_4'][$genia],$cant);
            
        // Alert Boxes PYME
       $customData['goal_cantidad_total_2_alert']=$this->dashboard_get_class($customData['goal_cumplidas_total_2'],$customData['goal_cantidad_total_2']);
       $customData['goal_cantidad_total_4_alert']=$this->dashboard_get_class($customData['goal_cumplidas_total_4'],$customData['goal_cantidad_total_2']);



        // ==== Cargo Resumen de las visitas solo para coordinadores
        $customData['periodo']=($periodo==1)?(" 1 mes"):(" $periodo meses");
        $customData['periodo_raw']=$periodo;
        $customData['metas'] = $mygoals;
		//var_dump($customData['goal_cantidad']);

        $this->render('dashboard', $customData);
    }
    
    // ================ funcion que determina la clase de los alerts para el index
    
    private function dashboard_get_class($cumplidas,$cantidad){
    	$ratio =($cantidad!=0)?($cumplidas*100/ $cantidad):(0);

    	if ($ratio >= 90){
    		return 'alert-success';
    	}elseif ($ratio >= 50 && $ratio<90){
    		return 'alert-warning';
    	}else{
    		return 'alert-danger';
    	}

    }
    

    // Carga el Manifiesto y va a dashboard
    function splash() {
        // testeo reemplazo appcache
        $customData = array();
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'genias/';
        $this->parser->parse('splash', $customData);
    }

    // OFFLINE FALLBACK
    function offline() {
        // testeo reemplazo appcache
        $customData = array();
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'genias/';
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

    function render($file, $customData) {
        $this->load->model('user/user');
        $this->load->model('msg');
        $this->load->language('inbox/inbox');
        
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
        $cpData['rol_icono'] = ($cpData['rol'] == 'coordinador') ? ('fa fa-group') : ('fa fa-user');

        // Listado de genias de donde soy user
        $mygenias = $this->get_genia();
        $cpData['genias'] = $mygenias['genias'];
        $cpData = array_replace_recursive($customData, $cpData);

        /* Inbox Count MSgs */
        $mymgs = $this->msg->get_msgs($this->idu);
        $cpData['inbox_count'] = count($mymgs);

        // offline mark
        $cpData['is_offline'] = ($this->uri->segment(3) == 'offline') ? ('offline') : ('');

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
        $thisCase = $this->bpm->get_case($case,$idwf);
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
//        var_dump($projects['items']);
//        exit();
        $customData['titulo'] = "Tareas";
        $customData['tasks'] = array();
//        foreach ($projects['items'] as $k => $item) {
//            $items = $this->get_tasks($k);
//            $customData['tasks'][$k] = array('id' => $item['id'], 'name' => $item['name'], 'items' => $this->get_tasks($k));
//        }
//      var_dump($customData['projects'] );
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

    function remove_goal() {
        $id = $this->input->post('metaid');
        echo $this->genias_model->remove_goal($id);
    }

    function get_tasks($proyecto, $periodo = null) {

        if (empty($proyecto))
            exit();

        // Mapeo proyecto id - > orden de display en fullcalendar
//        $projects = $this->genias_model->get_config_item('projects');
//        $items = $projects['items'];
        // $proyecto = $items[$proyecto]['id'];

        $tasks = $this->genias_model->get_tasks($this->idu, $proyecto, $periodo);
//        var_dump(iterator_to_array($tasks));
//        exit();
        if (!$tasks->count())
            return array();

        $mytasks = array();
        foreach ($tasks as $task) {
            $dia = iso_decode($task['dia']);

            $user = (array) $this->user->get_user($task['idu']);

            if (!$user) {
                $autor = "Desconocido";
            } else {
                $name = (!empty($user['name'])) ? ($user['name']) : ('');
                $lastname = (!empty($user['lastname'])) ? ($user['lastname']) : ('');
                $autor = "$lastname, $name";
            }

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
                'autor' => $autor
            );

            $mytasks[] = $item;
        }

        return $mytasks;
    }

    // Calls get_tasks and print the result in UL
    function print_ul_tasks() {
        if (!$this->uri->segment(3))
            exit();
        $proyecto = $this->uri->segment(3);
        if ($this->uri->segment(4)) {
            $fecha = $this->uri->segment(4);
        } else {
            $fecha = date('Y-m');
        }
        $tasks = $this->get_tasks($proyecto, $fecha);
        echo '<ul class="list-unstyled ">';
        foreach ($tasks as $task) {
            if ($task['finalizada'] == 0) {
                echo "<li ><i class='fa fa-calendar' style='color:#0088CC'></i> {$task['dia']} <i class='fa fa-clock-o' style='color:#0088CC'></i> {$task['hora']}:{$task['minutos']} <i class='fa fa-user' style='color:#0088CC'></i> {$task['autor']} <a href='{module_url}form/{$task["id"]}'>{$task['title']}</a>{$task['detail']}</li>";
            }
        }
        echo '</ul>';
    }

    // tasks for scheduler
    function print_tasks() {
        if (!$this->uri->segment(3))
            exit();
        $proyecto = $this->uri->segment(3);
        if ($this->uri->segment(4)) {
            $fecha = $this->uri->segment(4);
            $tasks = $this->get_tasks($proyecto, $fecha);
        } else {
            $tasks = $this->get_tasks($proyecto);
        }
        echo json_encode($tasks);
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
        $url_genias_2 = $this->module_url . 'empresas_mapa';
        $url_genias_4 = $this->module_url . 'instituciones_mapa';
        //$url = $this->module_url . 'instituciones_mapa';
        $customData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'url_genias_2'=>$url_genias_2,
            'url_genias_4'=>$url_genias_4
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
        $customData['js'] = array($this->module_url . "assets/jscript/scheduler.js" => 'Inicio Scheduler JS', $this->module_url . "assets/jscript/fullcalendar/fullcalendar.min.js" => 'Calendar', $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min.js" => 'Validate');
        $customData['css'] = array($this->module_url . "assets/css/genias.css" => 'Genias CSS');
        $projects = $this->genias_model->get_config_item('projects');
        $customData['projects'] = $projects['items'];
        //print_r($customData['projects']);
        $year = date('Y');
        $month = date('m');
        // Defino agendas / colores
        foreach($projects['items'] as $items){
            $customData['global_js']["proyectos"][]=$items;
        }

//        $customData['global_js']=array("proyectos"=>array(
//            array("id"=>2,"bgcolor"=>"#C6372C","color"=>"#fff","l"=>"form_empresas_alt"),
//            array("id"=>4,"bgcolor"=>"#f60","color"=>"#fff","link_form"=>"form_instituciones")
//            ));
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
            'http://maps.google.com/maps/api/js?sensor=true' => 'Google API',
            $this->base_url . 'jscript/ext/src/ux/GMapPanel3.js' => 'Gmap Api',
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
            $this->base_url . "jscript/ext/src/ux/statusbar/StatusBar.js" => 'Status Bar',
            $this->base_url . "jscript/ext/src/ux/LiveFilterGridPanel.js" => 'Live Filter Panel',
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

    function Form_instituciones($parm = null) {

        //echo $this->idu;   
        //---Libraries
        $this->load->library('parser');
        $this->load->library('ui');

        $cpData = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Escenario Institucional';
        $cpData['titulo'] = "Escenario Institucional";

        $cpData['js'] = array(
            'http://maps.google.com/maps/api/js?sensor=true' => 'Google API',
            $this->base_url . 'jscript/ext/src/ux/GMapPanel3.js' => 'Gmap Api',
            $this->module_url . 'assets/jscript/onlineStatus.js' => 'Online/Offline Status',
            $this->base_url . 'jscript/ext/src/ux/form/SearchField.js' => 'Search Field',
            $this->module_url . 'assets/jscript/ext.settings.js' => 'Ext Settings',
            $this->module_url . 'assets/jscript/empresas.ext.data.js' => 'Base Data',
            $this->module_url . 'assets/jscript/instituciones/ext.data.instituciones.js' => 'Instituciones Data',
            $this->module_url . 'assets/jscript/instituciones/visitas.grid.js' => 'Visitas Instituciones',
            $this->module_url . 'assets/jscript/instituciones/btnSync.js' => 'btnSync',
            $this->module_url . 'assets/jscript/instituciones/form.js' => 'Form Instituciones',
            $this->module_url . 'assets/jscript/instituciones/ext.viewport.instituciones.tab.js' => 'ViewPort',
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

    /*  ==== EMPRESAS 
     */
    

    function Empresas($idgenia = null,$echo=false) {

        $genias = $this->genias_model->get_genia($this->idu);
        $query = array();
        $provincias = array();
        foreach ($genias['genias'] as $thisGenia) {

            if (isset($thisGenia['query_empresas'])) {

                foreach ($thisGenia['query_empresas'] as $key => $value) {
                    //---me guardo provincias para filtrar partidos
                    if ($key == 4651) {
                        $provincias[] = $value;
                    }

                    // Armo Array
                    if (is_array($value)) {
                        foreach ($value as $v) {
                            $query[$key][] = $v;
                        }
                    } else {
                        $query[$key][] = $value;
                    }
                                        
                }
            }
        }

        // Agregos los $in y $or al query

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

        // WRAP para mongo meto $or y $in       
        foreach ($query as $k => $items) {
            if (is_array($items)) {
                $newQ['$or'][] = array($k => array('$in' => $items));
            } else {
                $newQ['$or'][] = array($k => $items);
            }
        }

        $empresas = $this->genias_model->get_empresas($newQ);

        for ($i = 0; $i < count($empresas); $i++) {
            $thisEmpresa = &$empresas[$i];
            //-----partido por texto
            if (isset($thisEmpresa[1699])) {
                $thisEmpresa['partido_txt'] = (isset($partidos[$thisEmpresa[1699][0]])) ? $partidos[$thisEmpresa[1699][0]] : $thisEmpresa[1699][0];
            } else {
                $thisEmpresa['partido_txt'] = '<span class="label label-important"><i class="icon-info-sign"/> COMPLETAR! </span>';
            }
        }
        
//        var_dump($empresas);
//        exit();
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
                if(!$echo){
                    echo json_encode($rtnArr);
                }else{
                    return $rtnArr;
                }
            }
        } else {
            var_dump(json_encode($rtnArr));
        }
    }
    
    // Mapa de empresas
        function empresas_mapa() {
        
        $empresas=$this->empresas(null,true);
        $rtnArr=array();

        foreach ($empresas['rows'] as $empresa) {
            if (isset($empresa['1693']) && !empty($empresa['7819']) && !empty($empresa['7820'])) {
                $desc = (isset($empresa['1715'])) ? print_r($empresa['1715'],true) : '';
                $rtnArr['markers'][] = array(
                    "latitude" => $empresa['7820'],
                    "longitude" => $empresa['7819'],
                    "title" => $empresa['1693'],
                    "tags" => array("genia2"),
                    "icon" => "factory_marker.png",
                    "content" => $empresa['1693'] . '<br/>' . $desc,
                );
            }
        }

        echo json_encode($rtnArr);
    }
    
    /*  ==== INSTITUCIONES 
     */
    
    function Instituciones($idgenia = null, $echo=false) {
        $genias = $this->genias_model->get_genia($this->idu);
        $query = array();
        $provincias = array();
        foreach ($genias['genias'] as $thisGenia) {
            if (isset($thisGenia['query_empresas'])) {

                foreach ($thisGenia['query_empresas'] as $key => $value) {
                    //---me guardo provincias para filtrar partidos
                    if ($key == 4651) {
                        $provincias[] = $value;
                    }
                    // Mapeo a los valores de instituciones
                    if($key==4651)$key=4897;
                    // Armo Array
                    if (is_array($value)) {
                        foreach ($value as $v) {
                            $query[$key][] = $v;
                        }
                    } else {
                        $query[$key][] = $value;
                    }
                }
            }
        }

        // Agregos los $in y $or al query

        $this->load->model('app');
        $debug = false;
        $compress = false;
        /*
         * Hacer un regla para obtener las empresas de la genia sea por partidos o por provincia
         * Basado en el idgenia
         */
        //---cacheo los partidos
//            '4897', //Provincia (39)
//            '8102', // Partido (58)
                            
        $partidos = array();
        if (isset($query['4651'])) {
            foreach ($provincias as $prov) {
                $par = $this->app->get_ops(58, $prov);
                $partidos+=$par;
            }
        }

        // WRAP para mongo meto $or y $in       
        foreach ($query as $k => $items) {
            if (is_array($items)) {
                $newQ['$or'][] = array($k => array('$in' => $items));
            } else {
                $newQ['$or'][] = array($k => $items);
            }
        }

        $instituciones = $this->genias_model->get_instituciones($newQ);
        //---las cargo sin filtrar
        //$instituciones = $this->genias_model->get_instituciones(array());
        for ($i = 0; $i < count($instituciones); $i++) {
            $thisInstitucion = &$instituciones[$i];
            //-----partido por texto
            if (isset($thisInstitucion[8102])) {
                $thisInstitucion['partido_txt'] = (isset($partidos[$thisInstitucion[8102][0]])) ? $partidos[$thisInstitucion[8102][0]] : $thisInstitucion[8102][0];
            } else {
                $thisInstitucion['partido_txt'] = '<span class="label label-important"><i class="icon-info-sign"/> COMPLETAR! </span>';
            }
        }
        //var_dump($empresas);
        $rtnArr = array();
        $rtnArr['totalCount'] = count($instituciones);
        $rtnArr['rows'] = $instituciones;
        if (!$debug) {
            header('Content-type: application/json;charset=UTF-8');
            if ($compress) {
                header('Content-Encoding: gzip');
                print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
                echo gzcompress(json_encode($rtnArr));
            } else {
                if(!$echo){
                    echo json_encode($rtnArr);
                }else{
                    return $rtnArr;
                }
            }
        } else {
            var_dump(json_encode($rtnArr));
        }
    }
    
    // Mapa de instituciones
    function instituciones_mapa() {     
        $instituciones=$this->instituciones(null,true);
        $rtnArr=array();

        foreach ($instituciones['rows'] as $institucion) {

            if (isset($institucion['4896']) && !empty($institucion['8109']) && !empty($institucion['8110'])) {
                $rtnArr['markers'][] = array(
                    "latitude" => $institucion['8109'],
                    "longitude" => $institucion['8110'],
                    "title" => $institucion['4896'],
                    "tags" => array("genia4"),
                    //"icon" => "factory_marker_cyan.png",
                    "icon" => "instituciones_red.png",
                    "content" => $institucion['4896'],
                );
            }
        }
        echo json_encode($rtnArr);
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
    
    function Visita_instituciones($idgenia = null) {
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

    /* ==== RESUMEN DE VISITAS ==== */

    function get_resumen_visitas($periodo) {

        $visitas = $this->genias_model->get_resumen_visitas($periodo);

        $ul= '<ul class="ultree">';
        foreach ($visitas as $k => $provincias) {

            $ul.= "<li>$k<a class='pull-right ul_collapse'><i class='fa fa-chevron-circle-down'></i></a>"; // PROV
            /* ==== provincias==== */
            $ul.= "<ul style='display:none'>";
            $i = 0;
            foreach ($provincias as $k => $empresa) {
        $data_empresa=htmlspecialchars("Razón social:{$empresa['empresa']}<br>Provincia:{$empresa['4651']}<br>Web:{$empresa['empresa_web']}");
       		     	
                $i++;
                $stripe = ($i % 2 == 0) ? ('par') : ('impar');
                $visitas = count($empresa['fechas']);
                $ul.= "<li class='$stripe'><a class='ul_collapse bt_info_empresa' data-info='$data_empresa'><i class='fa fa-question-circle'></i> </a>{$empresa['empresa']} | {$empresa['1703']} <span class='cuit'>($k)</span><span class='cantidad'>($visitas)</span><a class='pull-right ul_collapse'><i class='fa fa-chevron-down'></i></a>"; //CUIT + NOMBRE
//             /*==== Visitas====*/
                $ul.= "<ul style='display:none'>";
                foreach ($empresa['fechas'] as $k => $fecha) {
                    if (($timestamp = strtotime($fecha['fecha'])) === false) {
                        $fecha_visita = '-';
                    } else {
                        $fecha_visita = date('d/m/Y', $timestamp);
                    }
                    $ul.= "<li ><i class='fa fa-calendar'></i> $fecha_visita &nbsp;<i class='fa fa-user'></i> {$fecha['idu']} &nbsp;<a href='#' class='bt_info' data-info='{$fecha['nota']}'><i class='fa fa-comment-o'></i> </a></li>";
                    
                }
                $ul.= "</ul>";
//
                $ul.= "</li>";
            }
            $ul.= "</ul>";
            $ul.= "</li>";
        }
        $ul.= "</ul>";
        return $ul;
    }
    
       /* ==== RESUMEN DE VISITAS ==== */

    function get_resumen_visitas_instituciones($periodo) {


        $visitas = $this->genias_model->get_resumen_visitas_instituciones($periodo);

        $ul= '<ul class="ultree">';
        foreach ($visitas as $k => $provincias) {

            $ul.= "<li>$k<a class='pull-right ul_collapse'><i class='fa fa-chevron-circle-down'></i></a>"; // PROV
            /* ==== provincias==== */
            $ul.= "<ul style='display:none'>";
            $i = 0;
            foreach ($provincias as $k => $empresa) {
                $i++;
                $stripe = ($i % 2 == 0) ? ('par') : ('impar');
                $visitas = count($empresa['fechas']);
  				$data_empresa=htmlspecialchars("Razón social:{$empresa['empresa']}<br>Provincia:{$empresa['4896']}<br>Web:{$empresa['empresa_web']}");
                $ul.= "<li class='$stripe'><a class='ul_collapse bt_info_empresa' data-info='$data_empresa'><i class='fa fa-question-circle'></i> </a> {$empresa['empresa']} | {$empresa['1703']} <span class='cuit'>($k)</span><span class='cantidad'>($visitas)</span><a class='pull-right ul_collapse'><i class='fa fa-chevron-down'></i></a>"; //CUIT + NOMBRE
//             /*==== Visitas====*/
                $ul.= "<ul style='display:none'>";
                foreach ($empresa['fechas'] as $k => $fecha) {
                    if (($timestamp = strtotime($fecha['fecha'])) === false) {
                        $fecha_visita = '-';
                    } else {
                        $fecha_visita = date('d/m/Y', $timestamp);
                    }
					$ul.= "<li ><i class='fa fa-calendar'></i> $fecha_visita &nbsp;<i class='fa fa-user'></i> {$fecha['idu']} &nbsp;<a href='#' class='bt_info' data-info='{$fecha['nota']}'><i class='fa fa-comment-o'></i> </a></li>";
                }
                $ul.= "</ul>";
//
                $ul.= "</li>";
            }
            $ul.= "</ul>";
            $ul.= "</li>";
        }
        $ul.= "</ul>";
return $ul;
    }
    
    
/* ==== ESTADISTICAS ==== */
    
    private function get_data_empresas($cuits) {
     $res=$this->genias_model->estadisticas();

      $rs=$this->app->get_result('container.empresas',array('1695'=>array('$in'=>$cuits)),array('1695','1693','1701','1703'));
      $emp=iterator_to_array($rs);
      $data=array();
      foreach($emp as $emp){
         // $data[$emp['1695']]=$emp['1693'];
          $data[$emp['1695']]=$emp;
      }
      return $data;

    }


    function resumen_empresas($proyecto='2',$periodo='12') {
        //== convierto meses en fecha
        $today=getdate();
        $periodo=date("Y-m-d", mktime(0, 0, 0, $today['mon']-(int)$periodo, 1, $today['year']));
        $res=$this->genias_model->estadisticas($proyecto,$periodo);
        
        $rows[]=array('genia','cuit','razon_social','email','telefono','fecha','idu','nota','id');
        $users=array();
        $cuits=array();
        // genias loop
        foreach($res['genias'] as $genia){
            // Busco las visitas
        
            foreach($genia['data_visitas'] as $id){
                $visita=$this->genias_model->get_visitas(array('id'=>$id));
                
                //== Datos Visitador 
                if(isset($visita[0]['idu'])){
                    $myidu=$visita[0]['idu'];
                    if(!array_key_exists($myidu,$users)){
                      $whoami=$this->user->getbyid($myidu);
                      $users[$myidu]=$whoami->lastname.", ".$whoami->name;
                    }          
                }
              $visitor=(isset($users[$myidu]))?($users[$myidu]):('-'); 
                
                //== datos empresa
                $cuits[]=$visita[0]['cuit'];

                $myrow['genia']=$genia['nombre'];
                $myrow['cuit']=(isset($visita[0]['cuit']))?($visita[0]['cuit']):('-');
                $myrow['razon_social']='-';
                $myrow['email']='-';
                $myrow['phone']='-';
                $myrow['fecha']=(isset($visita[0]['fecha']))?($visita[0]['fecha']):('-');
                $myrow['idu']=(isset($visitor))?($visitor):('-');
                $myrow['nota']=(isset($visita[0]['nota']))?($visita[0]['nota']):('-');
                $myrow['id']=$id;
                
              $rows[]=$myrow;
      
            }
            
          
        }
       
      //=== cargo razon social de las empresas
      if($proyecto=='2'){
             $empresas=$this->get_data_empresas($cuits);
            foreach($rows as &$row){
                  if(array_key_exists($row['cuit'],$empresas))
                      $row['razon_social']=$empresas[$row['cuit']]['1693'];
                      $row['email']=$empresas[$row['cuit']]['1703'];
                      $row['phone']=$empresas[$row['cuit']]['1701'];
                       
            }
      }

    //   var_dump($rows);
        $this->load->library('table');
        $customData['show_table'] = utf8_decode($this->table->generate($rows));
        echo $this->parser->parse('print_to_xls', $customData, true);
        
        

    }
    

    
    

}

// close
