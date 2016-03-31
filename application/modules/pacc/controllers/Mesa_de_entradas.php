<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * api
 *
 * esta clase provee servicios para componentes externos ya sea en formato JSON
 * u otros necesarios dependiendo del cliente.
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jan 28, 2015
 */
class Mesa_de_entradas extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('api');
        $this->load->helper('html');
        $this->load->model('bpm/bpm');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

    /**
     * List all api methods not in ignore_arr
     *
     */
    function Index() {
            $this->dashboard();
    }

    function dashboard($debug=false) {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_me.json',$debug);
    }

    function scan(){
        ini_set('xdebug.var_display_max_depth',5);
        $this->load->library('parser');
        $this->load->library('dashboard/ui');
        $this->load->model('model_pacc');
        $this->load->model('bpm');
        $this->load->module('dashboard');
        // //----POST
        // $ip=$this->input->post($ip);
        // $idu=$this->input->post($idu);
        // $docs=$this->input->post($scans);
        //----GET
        $renderData=array();
        $parts=$this->uri->segment_array();
        $ipstr=$parts[4].'/'.$parts[5];
        $rs=$this->model_pacc->get_proyecto($ipstr);
        $idwfs=array('pacc1PDEF','pacc1SDE');
        $query['data.Proyectos_pacc.query.id']=$rs[0]['id'];
        $query['idwf']=array('$in'=>$idwfs);
        $cases=$this->bpm->get_cases_byFilter($query,array('id','idwf','status','checkdate'));
        //---armo query para los tokens
        foreach($cases as $case){
            $query=array();
            $query['case']=$case['id'];
            $query['tasktype']='Manual';
            $tokens=$this->bpm->get_tokens_byFilter($query,array('title','resourceId','status'));
            if(count($tokens)){
                $case['mytasks']=$tokens;
                $renderData['cases'][]=$case;
            } 
        }
        $renderData['qtty']=count($renderData['cases']);
        $renderData['title']='Ingresos Pendientes';
        $renderData['base_url']=$this->base_url;
        $customData['tiles_after']=$this->parser->parse('pacc/widgets/2doMeManual',$renderData,true,true);
        $customData['title']='Post Scan';
        Modules::run('dashboard/dashboard','pacc/json/layout_2cols_collapsed_vacio.json',false,$customData);
        // var_dump(error_reporting());exit;
        /**
         * chequear que se escaneó y

        echo "Recibimos notifiación del proceso de scan y delegamos el flujo para ip: $ip";
        */
    }
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */