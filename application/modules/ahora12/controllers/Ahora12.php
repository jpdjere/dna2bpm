<?php

/**
 * Description of pacc
 *
 * @author juanignacioborda@gmail.com
 * @date    2015-09-09
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ahora12 extends MX_Controller {

    function __construct() {
        parent::__construct();
        // var_dump(PDO::getAvailableDrivers());exit;
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }
    /*
     * Main function if no other invoked
     */
    function Index(){
        $this->dashboard();
    }

    /**
     * Dashboard para empresas
     */
    function dashboard(){
        $debug=false;
        Modules::run('dashboard/dashboard', 'ahora12/dashboards/dashboard.json',$debug);

    }

    function tile_descargar() {
        $data ['number'] = '17/09/2015';
        $data ['title'] = 'Descargar';
        $data ['icon'] = 'ion-android-download';
        $data ['more_info_text'] = 'click aquí';
        $data ['more_info_link'] = $this->base_url . 'ahora12/download';
        echo Modules::run('dashboard/tile', 'dashboard/tiles/tile-green', $data);
    }

    function xTarjeta($action=null){
        $this->load->model('ahora12/model_ahora12');
        $this->load->library('parser');
        $renderData['rango']['hasta']=$this->model_ahora12->current_range();
        $wData['tarjetas']=$this->model_ahora12->xTarjeta();
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = "xTarjeta";
        $renderData['content']= $this->parser->parse('ahora12/xTarjeta',$wData+$renderData,true);
        switch ($action) {
            case 'xls':
            header("Content-Description: File Transfer");
            header("Content-type: application/x-msexcel");
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=xTarjeta-".str_replace('/','-',$renderData['rango']['hasta']).".xls");
            header("Content-Description: PHP Generated XLS Data");
            $template='ahora12/empty_html';
	        $this->parser->parse($template, $renderData);
	        break;
            case 'json':
                echo json_encode($wData);
                break;
        default:

            $template="dashboard/widgets/box_info.php";
	        return $this->parser->parse($template, $renderData,true,true);
	        break;
        }
    }
    function xProvincia($action=null){
        $this->load->model('ahora12/model_ahora12');
        $this->load->library('parser');
        $renderData['rango']['hasta']=$this->model_ahora12->current_range();
        $wData['xProvincia']=$this->model_ahora12->ultimo_xProvincia();
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = "xProvincia";
        $renderData['content']= $this->parser->parse('ahora12/xProvincia',$wData+$renderData,true);
        switch ($action) {
            case 'xls':
            header("Content-Description: File Transfer");
            header("Content-type: application/x-msexcel");
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=xProvincia-".str_replace('/','-',$renderData['rango']['hasta']).".xls");
            header("Content-Description: PHP Generated XLS Data");
            $template='ahora12/empty_html';
	        $this->parser->parse($template, $renderData);
	        break;
            case 'json':
                echo json_encode($wData);
                break;
        default:

            $template="dashboard/widgets/box_info.php";
	        return $this->parser->parse($template, $renderData,true,true);
	        break;
        }
    }
    function xRubro($action=null){
        $this->load->model('ahora12/model_ahora12');
        $this->load->library('parser');
        $renderData['rango']['hasta']=$this->model_ahora12->rango_rubro();
        $wData['xProvincia']=$this->model_ahora12->ultimo_xRubro();
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = 'xRubro    <span class="label label-danger">NUEVO!</span>';
        $renderData['content']= $this->parser->parse('ahora12/xRubro',$wData+$renderData,true);
        switch ($action) {
            case 'xls':
            header("Content-Description: File Transfer");
            header("Content-type: application/x-msexcel");
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=xProvincia-".str_replace('/','-',$renderData['rango']['hasta']).".xls");
            header("Content-Description: PHP Generated XLS Data");
            $template='ahora12/empty_html';
	        $this->parser->parse($template, $renderData);
	        break;
            case 'json':
                echo json_encode($wData);
                break;
        default:

            $template="dashboard/widgets/box_info.php";
	        return $this->parser->parse($template, $renderData,true,true);
	        break;
        }
    }

function chart_montos() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Tendencia Montos";
        $data['json_url'] = $this->base_url . 'ahora12/montos/json';
        $data['class'] = "data_lines";
        return $this->parser->parse('ahora12/charts', $data, true, true);
    }
    
    function montos($out='json'){
        $this->load->model('ahora12/model_ahora12');
        $data=$this->model_ahora12->montos_x_corte();
        $items=array();
        foreach($data as $p){
            $items[]=array(
                'key'=>$p['FECHA'],
                'item1'=>number_format(intval($p['MONTO_VENTAS'])/1000000,2,'.','')
                );
        }
        
        $rtn_data['data']=$items;
        $rtn_data['key']='key';
        $rtn_data['items']=array('item1');
        $rtn_data['labels']=array('Monto');
        $rtn_data['postUnits']='M';
        $this->output->set_content_type('json','utf-8');
        $this->output->set_output(json_encode($rtn_data));
    }

}