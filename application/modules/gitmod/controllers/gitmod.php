<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Actualiza los archivos segun la rama configurada
 * 
 * Este controlador permite actualizar autumaticamente los archivos contenidos en la aplicacion
 * ejecutando el comando GIT: git pull y registrando la salida a un archivo de registro.
 * Este archivo funciona en conjunto con el web-hook de gitorious y no puede ser invocado manualmente
 * 
 * @autor Borda Juan Ignacio
 * 
 * @version 	1.13 (2012-06-14)
 * 
 * @file-salida   update-git.log
 * 
 */
class Gitmod extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->library('gitmod/git');
        $this->stageInculde=array('A','D','R');
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
    }
    function Index(){
        $this->git_dashboard();
    }
    function git_dashboard(){
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'gitmod/json/dashboard.json');
    }
    function update() {
        echo "<h1>Update from GIT server V1.15.log</h1>";

//----log to file
        $logtofile = true;
//----whether to include payload or not into logfile for debuging prouposes
        $include_payload = false;
//---get raw input
        //$request = file_get_contents('php://input');
        //$request_body = json_decode($request);
        $request_body = json_decode($this->input->post('payload'));
        $who = 'nobody';
        $result = 'Unauthorized access';
        if ($this->input->post('payload')|| true) {
            $who = $request_body->pushed_by;
            if ($who) {
                $result = shell_exec('git pull 2>&1');
            } else {
                $result = "Error can't process update request";
            }
        }
        if ($logtofile) {
            if ($fp = @fopen('update-git.log', 'a')) {
                if ($include_payload)
                    fwrite($fp, date('Y-m-d H:i:s') . ' ' . urldecode($request) . "\n");
                fwrite($fp, date('Y-m-d H:i:s') . ' Pushed by:' . $who . "\n");
                fwrite($fp, date('Y-m-d H:i:s') . ' Result: ' . $result . "\n\n");
                fclose($fp);
            }
        }
    }

    function viewlog() {
        $log = $página_inicio = file_get_contents('update-git.log');
        echo nl2br($log);
    }

    function tile() {
        $this->load->library('parser');
        $data['title']='Branch:<br/>'.$this->getBranchName().'<br>E:'.ENVIRONMENT;
        //$data['number']='Branch';
        $data['icon']='ion-usb';
        $data['more_info_link']=$this->base_url.'git/viewlog';
        $data['more_info_text']='view log';
        echo $this->parser->parse('dashboard/tiles/tile-orange', $data, true, true);
        
    }

    public function getBranchName() {
        if (is_file('.git/HEAD')) {
            $stringfromfile = file('.git/HEAD', FILE_USE_INCLUDE_PATH);
            $stringfromfile = $stringfromfile[0]; //get the string from the array

            $explodedstring = explode("/", $stringfromfile); //seperate out by the "/" in the string

            return trim(end($explodedstring)); //get the one that is always the branch name
        }
        return false;
    }
    
    public function status(){
        $this->load->library('parser');
        $repo=$this->git->open(FCPATH);
        $renderData['title'] ='Status';
        $renderData['url'] =$this->module_url.'status';
        $renderData['base_url'] = $this->base_url;
        $renderData['status']=$repo->status_extended();
        $renderData['qtty']=count($renderData['status']);
        
        $renderData['status']=array_map(
            function($file){
             $class='';
                switch ($file['status']){
                    case 'A'://---staged
                    //---don't display adde
                        $class='info';
                        break;
                    case 'H'://---cached
                        $class='warning';
                        break;
                        
                    case 'S'://---skip-worktree
                        $class='warning';
                        break;
                        
                    case 'M'://---unmerged
                        $class='primary';
                        break;
                        
                    case 'D'://---removed/deleted
                        $class='danger';
                    case 'R'://---renames
                        $class='warning';
                        break;
                    case 'UU'://---Conflicted
                        $class='danger';
                        break;
                        
                    case '??' ://---untracked
                        $class='success';
                        break;
                        
                    case 'C' ://---modified/changed
                        $class='primary';
                        break;
                        
                    default :
                        $class='primary';
                        break;
                }
            $file['class']=$class;
            //---dont return these
            if(in_array($file['status'],$this->stageInculde)){
              return null;
            } 
            return $file;
            },
            $renderData['status']);
            $renderData['status']=array_filter($renderData['status']);
            // var_dump($renderData['status']);exit;
        $renderData['content']=$this->parser->parse('gitmod/status', $renderData,true,true);
        return $this->parser->parse('dashboard/widgets/box_default', $renderData,true,true);
    }
    public function staged(){
        $this->load->library('parser');
        $repo=$this->git->open(FCPATH);
        $renderData['title'] = "Staged [".$repo->active_branch()."]";
        $renderData['base_url'] = $this->base_url;
        $renderData['staged']=$repo->status_extended();
        $renderData['staged']=array_map(
            function($file){
                $class='';
                switch ($file['status']){
                    case 'A'://---staged
                    //---don't display adde
                        $class='info';
                        break;
                    case 'H'://---cached
                        $class='warning';
                        break;
                        
                    case 'S'://---skip-worktree
                        $class='warning';
                        break;
                    case 'R'://---skip-worktree
                        $class='warning';
                        break;
                        
                    case 'M'://---unmerged
                        $class='primary';
                        break;
                        
                    case 'D'://---removed/deleted
                        $class='danger';
                        break;
                    case 'UU'://---Conflicted
                        $class='danger';
                        break;
                        
                    case '??' ://---untracked
                        $class='success';
                        break;
                        
                    case 'C' ://---modified/changed
                        $class='primary';
                        break;
                        
                    default :
                        $class='primary';
                        break;
                }
            $file['class']=$class;
            //--- return these
                if(in_array($file['status'],$this->stageInculde)){
                    return $file;
                } 
            },
            $renderData['staged']);
        $renderData['staged']=array_filter($renderData['staged']);
        $renderData['content']=$this->parser->parse('gitmod/staged', $renderData,true,true);
        
        $renderData['class'] ='col-md-6';
        return $this->parser->parse('dashboard/widgets/box_default_solid', $renderData,true,true);
    }
    
    function stage(){
        $repo=$this->git->open(FCPATH);
        $files=$this->input->post('files');
        $date=date('H:i:s');
        //---stage
        $repo->add($files);
        echo "<span class='text-success'>$date <i class='fa fa-chevron-circle-right'></i> Staging ".implode(',',$files)."</span>";
    }
    
    function unstage(){
        $repo=$this->git->open(FCPATH);
        $files=$this->input->post('files');
        $date=date('H:i:s');
        //---unstage
        foreach($files as $filename){
            $repo->run('reset HEAD -- $filename');
        }
        echo "<span class='text-warning'>$date <i class='fa fa-chevron-circle-left'></i> Un Staging ".implode(',',$files)."</span>";
    }
    function commit(){
        $repo=$this->git->open(FCPATH);
        $txt=$this->input->post('commitTxt');
        $date=date('H:i:s');
        //---commit($message = "", $commit_all = true) 
        $repo->commit($txt,false);
        
        echo "<span class='text-info'>$date <i class='fa fa-thumbs-up'></i> Commited ok!</span>";
    }
}