<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manager extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->model('user');
        $this->load->model('bpm');
        $this->load->model('msg');
        $this->load->model('user/group');
        $this->user->authorize();
        //----LOAD LANGUAGE
        $this->types_path = 'application/modules/bpm/assets/types/';
        $this->lang->load('library', $this->config->item('language'));
        $this->lang->load('bpm', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

    function Index() {
        $debug = FALSE;
        $wfData = array();
        $wfData = $this->lang->language;
        $wfData['theme'] = $this->config->item('theme');
        $wfData['base_url'] = base_url();
        //@todo make a flexible manager for cases
        //$this->parser->parse('bpm/manager', $wfData);
    }

    function get_kpi($model) {
        
    }

    function get_models() {
        //var_dump($wfData);
        $wfData = array();
        $wfData = $this->lang->language;
        $wfData['theme'] = $this->config->item('theme');
        $wfData['base_url'] = base_url();
        $wfs = $this->bpm->get_models();
        foreach ($wfs as $wf) {
            $array2 = $this->bpm->load($wf);
            $array1 = $this->bpm->get_properties($wf);
            $result = array_merge($array1, $array2);
            $name[] = $result['name'];
            //var_dump($name,$result);echo '<hr/>';
            $wfData['workflows'][] = $result;
        }
        array_multisort($name, SORT_ASC, $wfData['workflows']);
        $this->parser->parse('bpm/workflows.php', $wfData);
    }

    /*
     * Get tasks of current user or
     */

    function get_tasks($user = null, $filter_status = '') {
        //$this->load->helper('bpm');
        $cpData = array();

        //---allow asking 4 other users only if admin
        if (($this->user->has("root/ADM") OR $this->user->has("root/ADMWF"))) {

            $iduser = (isset($user)) ? (int) $user : $this->idu;
        } else {
            $iduser = $this->idu;
        }

        $tasks = $this->bpm->get_tasks($iduser);
        $user = $this->user->get_user_array($iduser);

        $tarr = array();
        $sort_date = array();
        $tstatus = array(
            'pending' => 0,
            'manual' => 0,
            'user' => 0,
            'waiting' => 0,
            'stoped' => 0,
            'finished' => 0,
            'canceled' => 0,
        );
        $tgroup = array();

        foreach ($tasks as $task) {
            //---check if the task has to appear
            $isassigned = false;
            if (isset($task['assign'])) {
                if (!in_array($iduser, $task['assign'])) {
                    continue;
                } else {
                    $isassigned = true;
                }
            } else {
                
            }//---end if isset assign

            $tstatus[$task['status']] = (!isset($tstatus[$task['status']])) ? 1 : $tstatus[$task['status']] + 1;
            $task2user = false;
            if (isset($task['idgroup'])) {
                foreach ($task['idgroup'] as $idgroup)
                    $group = $this->group->get($idgroup);
                $tgroup[$group['name']] = (!isset($tgroup[$group['name']])) ? 1 : $tgroup[$group['name']] + 1;
                if (in_array($idgroup, $user['group']))
                    $task2user = true;
            }
            //--compute if the task is assigned or not
            $task['claimable'] = (!isset($task['assign']) and $task2user) ? true : false;
            //---check if the task can be refused
            $task['refusable'] = ($isassigned and $task['status'] == 'user' and isset($task['idgroup'])) ? true : false;
            //---get Icon
            $task['icon'] = $this->bpm->get_icon($task['type']);
            //---get Icon 4 status
            $task['icon_status'] = $this->bpm->get_status_icon($task['status']);
            //---alias status key,parsing work-around
            $task['tstatus'] = $task['status'];
            //---Run url
            $task['run_url'] = $this->module_url .
                    'engine/do_pending/model/' .
                    $task['idwf'] . '/' .
                    $task['case'] . '/' .
                    $task['resourceId'];


            //----filter by status
            if ($filter_status <> '') {
                if ($task['status'] == $filter_status)
                    $tarr[$task['case']][] = $task;
            } else {
                $tarr[$task['case']][] = $task;
            }
        }
        //---add qtty to groups
        $cpData['groups'] = array();
        foreach ($tgroup as $group => $qtty) {
            $cpData['groups'][] = array('title' => $group, 'qtty' => $qtty);
        }

        $cpData['brief'] = $tstatus;
        $cpData['SumTasks'] = array_sum($tstatus);

        $cpData['cases'] = array();

        foreach ($tarr as $idcase => $tasks) {
            $case = $this->bpm->get_case($idcase);
            if ($case) {
                $mybpm = $this->bpm->load($case['idwf'], true);
                $sort_date[] = $case['checkdate'];
                $case['name'] = $mybpm['data']['properties']['name'];
                $case['documentation'] = $mybpm['data']['properties']['documentation'];
                $case['mytasks'] = $tasks;
                $case['task_count'] = count($tasks);
                //---Parse title with case data
                foreach ($case['mytasks'] as $key => &$value) {
                    if (isset($case['data'])) {
                        $value['title'] = $this->parser->parse_string($value['title'], $case['data'], true, true);
                    }
                }
                //$case['mytasks']=array(array('task_title'=>'titulo1'),array('task_title'=>'titulo2'));
                //
            $case['date'] = date($this->lang->line('dateFmt'), strtotime($case['checkdate']));
                $cpData['cases'][] = $case; // + $tasks;
            }
        };
        $cpData['cases_count'] = count($tarr);
        if (is_array($cpData['cases']) and is_array($sort_date))
            array_multisort($sort_date, SORT_DESC, $cpData['cases']);
        // var_dump($cpData['cases']);
        //----------------------------------------------------striptags
        return $cpData;
    }

    function show_tasks($user = null, $filter_status = '') {
        $cpData = array();
        //$cpData = $this->lang->language;
        $cpData['lang'] = $this->lang->language;
        $cpData['base_url'] = base_url();
        //---4 aditional icons and actions
        $cpData['wfadmin'] = ($this->user->has("root/ADM") OR $this->user->has("root/ADMWF")) ? true : false;
        //$mycases = $this->bpm->getStartedCases($this->idu);
        $cpData+=$this->get_tasks($user, $filter_status);
        $this->parser->parse('cp_tasks', $cpData, false, false);
    }

    function view_model($idwf) {
        $wfData = array();
        $wfData = $this->lang->language;
        $wfData['theme'] = $this->config->item('theme');
        $wfData['base_url'] = base_url();
        $wfData['idwf'] = $idwf;
        $wf = $this->bpm->load($idwf);
        $wfData+=$wf['data']['properties'];
        //var_dump($wfData);
        $this->parser->parse('bpm/view-diagram.php', $wfData);
    }

    /*
     * This function will generate a minireport of a case it will accept 2 parameters
     * The case id and the output format
     * 
     */

    function mini_report($idwf, $idcase, $output = 'array') {
        //@todo set idwf
        $case = $this->bpm->get_case($idcase, $idwf);
        $tokens = $this->bpm->get_tokens($idwf, $idcase, array());
        $task2users = array();
        //---create array for each user
        foreach ($tokens as $token) {
            if (isset($token['assign'])) {
                foreach ($token['assign'] as $assigned) {
                    $task2users[$assigned][] = $token;
                }
            }
        }
        switch ($output) {

            /*
             * TEXT 
             */
            case 'text':
                foreach ($task2users as $iduser => $tasks) {
                    $user = $this->user->get_user($iduser);
                    //---load cards here
                    echo $user->name . '<br>';
                    foreach ($tasks as $task) {
                        echo $task['title'] . '<br/>';
                        echo $task['status'] . '<br/>';
                    }
                    echo '<hr/>';
                }
                break;

            /*
             * HTML
             */
            case 'html':
                $cpData = array();
                $cpData['lang'] = $this->lang->language;
                $cpData['base_url'] = base_url();
                foreach ($task2users as $iduser => $tasks) {
//                    var_dump($tasks);
//                    exit;
//                    $user = $this->user->get_user($iduser);
//                    //---load cards here
//                    echo $user->name . '<br>';
//                    echo '<hr/>';
                    $user = (array) $this->user->get_user($iduser);
                    foreach ($tasks as $task) {
                        $task['date'] = date($this->lang->line('dateFmt'), strtotime($task['checkdate']));

                        $user['tasks'][] = $task;
                    }
                    $cpData['participant'][] = $user;
                }
                $this->parser->parse('bpm/mini_report', $cpData);
                break;
            /*
             * JSON
             */
            case 'json':
                $rtnArr = array();
                foreach ($task2users as $iduser => $tasks) {
                    $user = (array) $this->user->get_user_safe($iduser);

                    $task['idu'] = $user['idu'];
                    $task['nick'] = $user['nick'];
                    $task['name'] = $user['name'];
                    $task['lastname'] = $user['lastname'];
                    $task['email'] = $user['email'];
                    $task['tasks'] = $tasks;
                    $rtnArr[] = $task;
                }
                header('Content-type: application/json;charset=UTF-8');
                echo json_encode($rtnArr);
                break;

            /*
             * ARRAY
             */
            default:
                return $task2users;
                break;
        }
    }

    /*
     * This function will generate a minireport of statuses from all cases it will accept 2 parameters
     * The case id and the output format
     * 
     */

    function mini_status($idwf, $output = 'array', $filter = array()) {



        $filter['idwf'] = $idwf;
        $tokens = $this->bpm->get_cases_stats($filter);


        switch ($output) {

            /*
             * TEXT 
             */
            case 'text':
                foreach ($tokens as $id => $tasks) {
                    //$user = $this->user->get_user($id);
                    //---load cards here
                    echo $user->name . '<br>';
                    foreach ($tasks as $task) {
                        echo $task['title'] . '<br/>';
                        echo $task['status'] . '<br/>';
                    }
                    echo '<hr/>';
                }
                break;

            /*
             * HTML
             */
            case 'html':
                foreach ($tokens as $id => $tasks) {
                    //$user = $this->user->get_user($id);
                    //---load cards here
                    //echo $user->name . '<br>';
                    foreach ($tasks as $task) {
                        echo $task['title'] . '<br/>';
                        echo $task['status'] . '<br/>';
                    }
                    echo '<hr/>';
                }
                break;
            /*
             * JSON
             */
            case 'json':

                header('Content-type: application/json;charset=UTF-8');
                echo json_encode($tokens);
                break;

            /*
             * ARRAY
             */
            default:

                return $tokens;
                break;
        }
    }

    /**
     * STATUS_AMOUNTS 
     * 
     * Description Calculate the amount  of money  in projects grouped by status 
     * name status_amounts
     * @author Diego Otero 
     */
    function status_amounts($idwf, $output = 'array', $filter = array()) {
        $filter['idwf'] = $idwf;
        $querys = $this->bpm->get_amount_stats($filter);

        /* OPTIONS */
        $this->load->model('app');
        $option = $this->app->get_ops(772);

        foreach ($querys as $values) {

            $ctrl_value = (isset($values[0][8334][0])) ? $values[0][8334][0] : $values[0][8334];
            $value8326 = (isset($values[0][8326])) ? str_replace(",", ".", str_replace(".", "", $values[0][8326])) : 0;
            $value8573 = (isset($values[0][8573])) ? str_replace(",", ".", str_replace(".", "", $values[0][8573])) : 0;


            $amount = ($ctrl_value >= 30) ? $value8573 : $value8326;

            foreach ($option as $opt => $desc) {
                if ($opt == $ctrl_value)
                    $cases_arr[$desc][] = (float) $amount;
            }
        }

        return $cases_arr;
    }

}
