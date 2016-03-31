<?php
/**
* CONTROLADOR DEL PANEL DE ADMINISTRACION
* 
* BUSCA LOS DESEMBOLSOS PREVISTOS CONTRA LOS EJECUTADOS EN UN LAPSO DE TIEMPO
* 
* @author Luciano Menez <lucianomenez1212@gmail.com>
* @date Jun 10, 2015
*/
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administracion extends MX_Controller {

    function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->module('pacc11/api11');
        $this->idu = $this->session->userdata('iduser');
    }
    /*
     * INVOCACION AL DASHBOARD
     */
    function Index() {
        $this->dashboard_administracion();
    }
    /**
     * Dashboard de ADMINISTRACION
     */
    function dashboard_administracion() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_administracion.json');
    }


    //WIDGET DEL BUSCADOR
    function desembolsos_previstos_vs_ejecutados(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = "Desembolsos Previstos vs Ejecutados";
        $renderData['content']= $this->parser->parse('desembolsos-previstos-vs-ejecutados', $renderData, true, true);
       
        return $this->dashboard->widget($template, $renderData);
    }
    
    //PROCESAMIENTO DE LA CONSULTA
    
    function consultar($m1, $d1, $a1, $m2, $d2, $a2){
       $this->load->module('dashboard');
       $date1 = $a1.'/'.$m1.'/'.$d1;  
       $date2 = $a2.'/'.$m2.'/'.$d2;
       $filter['fecha_desde'] = $date1;
       $filter['fecha_hasta'] = $date2;
       
       $template = array (
              'ip' => '-',
              'previsto' => '-',
              'desembolso' => '-',
              'diferencia' => '-',
              'componente' => '-'
              );
       $renderData['data'] = $this->api11->desembolsos_previstos_vs_ejecutados($filter,'array');
              
       if (count($renderData['data']) == 0){
           $renderData['data'][0] = array (
              'ip' => "No se registran desembolsos realizados en este período",
              'previsto' => '-',
              'desembolso' => '-',
              'diferencia' => '-',
              'componente' => '-'
            );
        echo $this->parser->parse('tabla-administracion', $renderData, true, true);    
        exit;    
       }
       
       foreach($renderData['data'] as $key =>  $value){
       $renderData['data'][$key] += $template;       
       };
       
       echo $this->parser->parse('tabla-administracion', $renderData, true, true);
       
    }
    


 
}
