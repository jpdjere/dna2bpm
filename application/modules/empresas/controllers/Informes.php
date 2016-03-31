<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * empresa
 * 
 * Description of the class empresa
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date   Jul 31, 2014
 */
class Informes extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('empresas/model_empresas');
        $this->user->authorize();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
    }

    function Index() {
        Modules::run('dashboard/dashboard', 'empresas/json/empresas_informes.json');
    }
    function tile_altas_mes() {
        $data['number'] = 'Empresa';
        $data['title'] = 'Cargar una empresa';
        $data['icon'] = 'ion-document-text';
        $data['more_info_text'] = 'Comenzar';
        $data['more_info_link'] = $this->base_url . 'bpm/engine/newcase/model/empresa_carga';
        echo Modules::run('dashboard/tile', 'dashboard/tiles/tile-green', $data);
    }
    
    function chart_altas_anio($year=null) {
        if(!$year) $year=date('Y');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Registros $year";
        $data['json_url'] = $this->base_url . 'empresas/api/altas_anio/'.$year.'/json';
        $data['class'] = "data_lines";
        return $this->parser->parse('empresas/charts', $data, true, true);
    }

function chart_altas_todas($year=null) {
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Todas";
        $data['json_url'] = $this->base_url . 'empresas/api/altas_todas/json';
        $data['class'] = "data_lines";
        return $this->parser->parse('empresas/charts', $data, true, true);
    }
function tabla_altas_todas($action=null) {
        $this->load->library('parser');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Empresas Cargadas x Año";
        $data['data']=$this->model_empresas->altas_todas();
        $data['total']=0;
        foreach($data['data'] as $p)
            $data['total']+=$p['qtty'];
        $data['class'] = "data_lines";
         switch ($action) {
            case 'xls':
            header("Content-Description: File Transfer");
            header("Content-type: application/x-msexcel");
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=Altas Empresas.xls");
            header("Content-Description: PHP Generated XLS Data");
	        $this->parser->parse('empresas/tabla_year', $data,false,true);
	        break;
	        case 'html':
                $this->parser->parse('empresas/tabla_month', $data,false,true);
	        default:
            $data['xls_url'] = "informes/tabla_altas_todas/xls";
            return $this->parser->parse('empresas/tabla_year', $data, true, true);
         }
    }
function tabla_altas_anio($year=null,$action=null) {
        $this->load->library('parser');
        if(!$year) $year=date('Y');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Empresas Cargadas $year";
        $data['data']=$this->model_empresas->altas_anio($year);
        $data['total']=0;
        foreach($data['data'] as $p)
            $data['total']+=$p['qtty'];
        $data['class'] = "data_lines";
         switch ($action) {
            case 'xls':
                header("Content-Description: File Transfer");
                header("Content-type: application/x-msexcel");
                header("Content-Type: application/force-download");
                header("Content-Disposition: attachment; filename=Altas Empresas.xls");
                header("Content-Description: PHP Generated XLS Data");
                $this->parser->parse('empresas/tabla_month', $data,false,true);
	        break;
	        case 'html':
                $this->parser->parse('empresas/tabla_month', $data,false,true);
	        break;
	        
	        default:
            $data['xls_url'] = "informes/tabla_altas_anio/$year/xls";
            return $this->parser->parse('empresas/tabla_month', $data, true, true);
         }
    }

}

/* End of file empresa */
/* Location: ./system/application/controllers/welcome.php */