<?php
/**
* CONTROLADOR DEL PANEL INCUBAR
* 
* MUESTRA LOS PROYECTOS Y LAS INCUBADORAS REGISTRADAS POR DIVERSOS FILTROS.
* 
* LOS SCRIPTS DE MANEJO DE LA UI ESTÁN INVOCADOS EN EL DASHBOARD
* 
* @author Luciano Menez <lucianomenez1212@gmail.com>
* @date May 10, 2015
*/
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Incubar extends MX_Controller {


    function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
          $this->load->model('pacc13/pacc13');
        
    }
    /*
     * Main function if no other invoked
     */
    function Index() {
        $this->dashboard_incubar();
    }
    /**
     * Dashboard INCUBAR.
     */
    function dashboard_incubar() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_incubar.json');
    }

    //GRAFICO DE RETRIBUCIONES PAGADAS
    function area_charts() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = " Deuda Total / Retribución pagada";
        $data['class-incubar'] = "tour-incubar incubar-seis";
        $data['id'] = "chart-pagadas";
        $data['json_url'] = $this->base_url . 'pacc/incubar/retribucion_pagadas';
        
        return $this->parser->parse('area-charts', $data, true, true);
    }
    //GRAFICO DE RETRIBUCIONES PAGADAS
    function area_charts_negativa() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = " Deuda Total / Retribución no pagada";
        $data['class-incubar-dos'] = "tour-incubar incubar-siete";
        $data['id'] = "chart-no-pagadas";
        $data['json_url'] = $this->base_url . 'pacc/incubar/retribucion_no_pagadas';
  
        //$data['json_url'] = $this->base_url . 'demo/data_bars';
        return $this->parser->parse('area-charts', $data, true, true);
    }
    //SETEO DEL JSON PARA LA LIBRERIA DE RETRIBUCIONES PAGADAS
    function retribucion_pagadas(){
        $this->load->helper('api');
        $this->load->module('pacc11/api11');
        $arr=$this->api11->retribuciones_pagadas_empresas('array');
        $rtnArr=array();
        foreach($arr as $key=>$value){
            $rtnArr[]=array(
                'y'=>$key,
                'item1'=>$value['facturadas'],
                'item2'=>$value['pagadas']
                );
        }
        output_json($rtnArr);
    }
    //SETEO DEL JSON PARA LA LIBRERIA DE RETRIBUCIONES NO PAGADAS
    function retribucion_no_pagadas(){
        $this->load->helper('api');
        $this->load->module('pacc11/api11');
        $arr=$this->api11->retribuciones_pagadas_empresas('array');
        $rtnArr=array();
        foreach($arr as $key=>$value){
            $rtnArr[]=array(
                'y'=>$key,
                'item2'=>$value['facturadas'],
                'item1'=>$value['no_pagadas'],
                );
        }
        
        output_json($rtnArr);
    }
    //MAPA SVG DEL PAIS, CON LA TABLA RESPECTIVA QUE ACOMODA LA INDO
    function map_table($id, $title=""){
        $this->load->module('dashboard');
        $renderData['title'] = "Mapa Incubar";
        $renderData['table'] = $title;
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template="dashboard/widgets/box_info.php";
        $renderData['class']="map-table"; 
        $renderData['json_url'] = $this->base_url.'map/data_test_json';
        $renderData['provincia'] = $title;
        $renderData['content']= $this->parser->parse('map-table', $renderData, true, true);
        
        return $this->dashboard->widget($template, $renderData);
    }
    //TABLA DE PROYECTOS EMPRENDEDORES
    function proyectos_emprendedores(){
        
        $this->load->module('dashboard');
        $this->load->module('pacc13/api13');

        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = 'Proyectos Emprendedores';
        $filter="";
        $renderData['proyectos']= $this->api13->dashboard_sub_emprendedores(null, 'array');
        $renderData['desembolsos']= $this->api13->dashboard_sub_emprendedores_totales(null, 'array');
        $provincias = $this->app->get_ops(39);
        
                $clean = array (
               'desembolso' => '-',
              'proyectos_desembolsados' => '-'
              );
    
        
        foreach ($renderData['proyectos'] as $key=> &$proyectos){
            
            if (!isset($renderData['proyectos'][$key]['finalizados'])){
            $renderData['proyectos'][$key]['finalizados']['cantidad'] = '-';};
             $renderData['proyectos'][$key] += $clean;
            
             foreach ($renderData['desembolsos'] as $desembolsos){
             if    ($desembolsos['provincia'] == $key){ 
             $proyectos = $proyectos + $desembolsos;
          //   $proyectos['desembolsos'] = $desembolsos['desembolsos']['desembolso'] ;
             }
             }
             if ($key <> ''){
             $provincias['BAC'] = null;     
             $proyectos['provincia'] = $provincias[$key];
             $filter['provincia'] = $key; 
             $incubadoras =$this->api13->incubadoras_por_provincia($filter, 'array');
             $proyectos['incubadoras'] = count($incubadoras[$key]);
             }
        }

        $template="dashboard/widgets/box_info.php";

        $renderData['content']= $this->parser->parse('tabla-proyecto-emprendedores', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
        
    }
    //FUNCION PARA EXPORTAR EN EXCEL
    function proyectos_emprendedores_excell(){
        
        $this->load->module('dashboard');
        $this->load->module('pacc13/api13');

        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = 'Proyectos Emprendedores';
        $filter="";
        $renderData['proyectos']= $this->api13->dashboard_sub_emprendedores($filter, 'array');
        $renderData['desembolsos']= $this->api13->dashboard_sub_emprendedores_totales($filter, 'array');
        $provincias = $this->app->get_ops(39);
        
        $filter['provincia'] = "TUC";
        $data = $this->api13->incubadoras_por_provincia($filter, 'array');
        
    
        foreach ($renderData['proyectos'] as $key=> &$proyectos){
             foreach ($renderData['desembolsos'] as $desembolsos){
          //   if    ($desembolsos['provincia'] == $key){ 
        //     $proyectos = $proyectos + $desembolsos;
          //   $proyectos['desembolsos'] = $desembolsos['desembolsos']['desembolso'] ;
          //   }
            }
             if ($key <> ''){
             $provincias['BAC'] = null;     
             $proyectos['provincia'] = $provincias[$key];
             $filter['provincia'] = $key; 
             $incubadoras =$this->api13->incubadoras_por_provincia($filter, 'array');
             $proyectos['incubadoras'] = count($incubadoras[$key]);
             }
        }

        $template="dashboard/widgets/box_info.php";
     
        echo $this->parser->parse('tabla-proyecto-emprendedores-excell', $renderData, true, true);
       // return $this->dashboard->widget($template, $renderData);
    }
     //TABLA DE PROYECTOS EMPRESAS
    function proyectos_empresas(){
        
        $this->load->module('dashboard');
        $this->load->module('pacc11/api11');
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = 'Proyectos Empresas';
        $template="dashboard/widgets/box_info.php";
        $filter="";
        $renderData['proyectos']= $this->api11->dashboard_sub_empresas_totales($filter, 'array');
      //  $renderData['desembolsos']= $this->api11->dashboard_sub_empresas_totales_($filter, 'array');

        $provincias = $this->app->get_ops(39);
   
        $clean = array (
               'desembolso' => '-',
              'proyectos_desembolsados' => '-'
              );
    
        foreach ($renderData['proyectos'] as $key=> &$proyectos){
            
            if (!isset($renderData['proyectos'][$key]['finalizados'])){
                $renderData['proyectos'][$key]['finalizados']['cantidad'] = '-';};
            
             $renderData['proyectos'][$key] += $clean;
             foreach ($renderData['desembolsos'] as $desembolsos){
             if    ($desembolsos['provincia'] == $key){ 
             $proyectos = $proyectos + $desembolsos;
          //   $proyectos['desembolsos'] = $desembolsos['desembolsos']['desembolso'] ;
             }
             }
             if ($key <> ''){ 
             $proyectos['provincia'] = $provincias[$key];
             }
        }

 

        foreach ($renderData['proyectos'] as $i => &$proyecto){
            
          if (!isset($renderData['proyectos']['rechazados'])){$renderData['proyectos']['rechazados']['cantidad'] ="-";}
            
        }

        $renderData['content']= $this->parser->parse('tabla-proyecto-empresas', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
        
    }
   //FUNCION PARA EXPORTAR EN EXCEL
    function proyectos_empresas_excell(){
        
        $this->load->module('dashboard');
        $this->load->module('pacc11/api11');
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = 'Proyectos Empresas';
        $template="dashboard/widgets/box_info.php";
        $filter="";
        $renderData['proyectos']= $this->api11->dashboard_sub_empresas_totales($filter, 'array');
     //   $renderData['desembolsos']= $this->api11->dashboard_sub_empresas_totales($filter, 'array');
        
        
 
        $provincias = $this->app->get_ops(39);
   
    
         foreach ($renderData['proyectos'] as $key=> &$proyectos){
        //      foreach ($renderData['desembolsos'] as $desembolsos){
        //      if    ($desembolsos['provincia'] == $key){ 
        //      $proyectos = $proyectos + $desembolsos;
        //   //   $proyectos['desembolsos'] = $desembolsos['desembolsos']['desembolso'] ;
        //      }
        //      }
             if ($key <> ''){ 
          $proyectos['provincia'] = $provincias[$key];
          }
          }

 

        foreach ($renderData['proyectos'] as $i => &$proyecto){
            
          if (!isset($renderData['proyectos']['rechazados'])){$renderData['proyectos']['rechazados']['cantidad'] ="-";}
            
        }
            
        echo $this->parser->parse('tabla-proyecto-empresas_excell', $renderData, true, true);
       // return $this->dashboard->widget($template, $renderData);
        
    }
    //TABLA CON BUSCADOR DE INCUBADORAS
    function incubadoras_provincia_localidad(){
        
        $this->load->module('dashboard');
        $this->load->module('pacc13/api13');
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = 'Incubadoras según Provincia y Localidad';
        $template="dashboard/widgets/box_info.php";
        $renderData['select_provincias'] = $this->filter_provincias(0);
        $renderData['select_partidos'] = $this->filter_partidos(0);
        $renderData['tabla_provincia_localidad']= $this->tabla_provincia_localidad();
        $incubadoras= $this->api13->incubadoras_por_provincia($filter, 'array');
        $renderData['incubadoras'] = $incubadoras[''];
        $renderData['content']= $this->parser->parse('tabla-incubadoras-provincia-localidad', $renderData, true, true);
        
        return $this->dashboard->widget($template, $renderData);
    }
    
    function tabla_provincia_localidad($param=null){
    
     
       $this->load->module('pacc13/api13');
       $this->load->model('app');
       $this->load->library('parser');
       $renderData = array();
      
       return $this->parser->parse('pacc/tabla-provincia-localidad', $renderData, true, true);
    }
        
    function estado_de_las_incubadoras(){
       
        $this->load->module('dashboard');
        $this->load->module('pacc11/api11');
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = 'Estado de las Incubadoras';
        $template="dashboard/widgets/box_info.php";
        $filter="";
        $renderData['incubadoras']= $this->api13->incubadoras_listado($filter, 'array');
        
 
        $value = 0;
        $data = array();
        foreach ($renderData['incubadoras'] as $incubadora => $i){
            $data[$value] = array(
                 'nombre' => $i['nombre'],
                 'id' => $incubadora
                 );
                 $value++;
        }
        $renderData['incubadoras']= $data;
        $renderData['tabla_estado']= "";
        $renderData['content']= $this->parser->parse('estado-de-las-incubadoras', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
        
    }
    
    function estado_incubadora($incubadora = null){
        $this->load->module('pacc13/api13');
        $this->load->module('dashboard');
        $this->load->library('parser');
        $template="dashboard/widgets/box_info.php";
        $incubadora = urldecode($incubadora);
        
        $renderData['incubadoras']= $this->api13->incubadoras_por_id($incubadora, 'array');
       
        
       
       $data = array();
       
        foreach ($renderData['incubadoras'] as $key => $incubadora){
            foreach ($incubadora as $key2 => $datos){
            $datos['provincia'] = $key;
            $data[] = $datos;
        };
         
        };
       
       $renderData['incubadoras'] =$data;
       
        if (count($renderData['incubadoras']) == 0){
           $renderData['incubadoras'][0] = array (
              'provincia' => "NO SE REGISTRAN DATOS PARA ESTA INCUBADORA",
              'presentados' => '-',
              'pre_aprobados' => '-',
              'aprobados' => '-',
              'rechazados' => '-',
              'finalizados' => '-',
              'proyectos_desembolsados' => '-',
              'desembolso' => '-'
                );
        echo $this->parser->parse('pacc/tabla-estado', $renderData, true, true);
        exit;
        }
       
       $template = array (
              'presentados' => '-',
              'pre_aprobados' => '-',
              'aprobados' => '-',
              'rechazados' => '-',
              'proyectos_desembolsados' => '-',
              'finalizados' => '-',
              'realizados' => '-'
              );
      
       foreach($renderData['incubadoras'] as $key =>  $value){
           
       $renderData['incubadoras'][$key] += $template;       
       
       
       };
       
       
       
       echo $this->parser->parse('tabla-estado', $renderData, true, true);
        
      
    
    }
    


    //FUNCIONES DE FILTRADO Y RECARGA DE LAS TABLAS Y LOS SELECTORES SEGUN LOS ID

    function reload_tabla_provincia_localidad($param, $param2){
    
       $this->load->module('pacc13/api13');
       $this->load->model('app');
       $this->load->library('parser');
       $filter="";
       $param = urldecode($param);
       $param2 = urldecode($param2);
       
    if ($param2 == "TODOS"){
       
       $data = $this->api13->incubadoras_por_provincia($filter, 'array');
       $nombres = $this->api13->incubadoras_listado('array');
       $value =0;
       $provincias = $this->app->get_ops(39);
       foreach ($provincias as $provincia => $i){
           $data[$i] = array(
             'id' => $i + $value,  
             'nombre' => $i,
             'value' => $value
             );
       $value++;
       }
       $provincias = array_flip($provincias);
       $param = $provincias[$param];
       $renderData['data']= $data[$param];
      
      $template = array (
              'presentados' => '-',
              'pre_aprobados' => '-',
              'aprobados' => '-',
              'rechazados' => '-',
              'finalizados' => '-',
              'proyectos_desembolsados' => '-',
              'desembolso' => '-'
              );
 
       foreach($renderData['data'] as $id =>  $value){
          $renderData['data'][$id] += $template;
          $renderData['data'][$id]['nombre'] = isset($nombres[$id]) ? $nombres[$id] : $id  ;
      };
       $renderData['data'] = array_values($renderData['data']);

       echo $this->parser->parse('pacc/tabla-provincia-localidad', $renderData, true, true);
    }
    else
    {
     //  $filter['partidos'] = $param2;
       $data = $this->api13->incubadoras_por_partido($filter, 'array');
       $nombres = $this->api13->incubadoras_listado('array');
       error_reporting(0);
       $renderData['data'] = $data[$param2];
       
       if (!isset($renderData['data'])){
           $renderData['data'][0] = array (
              'nombre' => "NO SE REGISTRAN INCUBADORAS PARA ESTE PARTIDO",
              'presentados' => '-',
              'pre_aprobados' => '-',
              'aprobados' => '-',
              'rechazados' => '-',
              'finalizados' => '-',
              'proyectos_desembolsados' => '-',
              'desembolso' => '-'
                );
       echo $this->parser->parse('pacc/tabla-provincia-localidad', $renderData, true, true);
       exit;
       }
       
       $template = array (
              'presentados' => '-',
              'pre_aprobados' => '-',
              'aprobados' => '-',
              'rechazados' => '-',
              'finalizados' => '-',
              'proyectos_desembolsados' => '-',
              'desembolso' => '-'
              );
      
       foreach($renderData['data'] as $key =>  $value){
           
       $renderData['data'][$key] += $template;       
       $renderData['data'][$key]['nombre'] = isset($nombres[$key]) ? $nombres[$key] : $key;
       
       };
       $renderData['data'] = array_values($renderData['data']);
       echo $this->parser->parse('pacc/tabla-provincia-localidad', $renderData, true, true);
    }
    }
    function reload_tabla_provincia_localidad_excell($param, $param2){
    
       $this->load->module('pacc13/api13');
       $this->load->model('app');
       $this->load->library('parser');
       $filter="";
       $param = urldecode($param);
       $param2 = urldecode($param2);
       
    if ($param2 == "TODOS"){
       
       $data = $this->api13->incubadoras_por_provincia($filter, 'array');
       $nombres = $this->api13->incubadoras_listado('array');
       $value =0;
       $provincias = $this->app->get_ops(39);
       foreach ($provincias as $provincia => $i){
           $data[$i] = array(
             'id' => $i + $value,  
             'nombre' => $i,
             'value' => $value
             );
       $value++;
       }
       $provincias = array_flip($provincias);
       $param = $provincias[$param];
       $renderData['data']= $data[$param];
      
      $template = array (
              'presentados' => '-',
              'pre_aprobados' => '-',
              'aprobados' => '-',
              'rechazados' => '-',
              'finalizados' => '-',
              'realizados' => '-'
              );
 
       foreach($renderData['data'] as $id =>  $value){
          $renderData['data'][$id] += $template;
          $renderData['data'][$id]['nombre'] = isset($nombres[$id]) ? $nombres[$id] : $id  ;
      };
       $renderData['data'] = array_values($renderData['data']);

       echo $this->parser->parse('pacc/tabla-provincia-localidad_excell', $renderData, true, true);
    }
    else
    {
     //  $filter['partidos'] = $param2;
       $data = $this->api13->incubadoras_por_partido($filter, 'array');
       $nombres = $this->api13->incubadoras_listado('array');
       error_reporting(0);
       $renderData['data'] = $data[$param2];
       
       if (!isset($renderData['data'])){
           $renderData['data'][0] = array (
              'nombre' => "NO SE REGISTRAN INCUBADORAS PARA ESTE PARTIDO",
              'presentados' => '-',
              'pre_aprobados' => '-',
              'aprobados' => '-',
              'rechazados' => '-',
              'finalizados' => '-',
              'realizados' => '-'
                );
       echo $this->parser->parse('pacc/tabla-provincia-localidad_excell', $renderData, true, true);
       exit;
       }
       
       $template = array (
              'presentados' => '-',
              'pre_aprobados' => '-',
              'aprobados' => '-',
              'rechazados' => '-',
              'finalizados' => '-',
              'realizados' => '-'
              );
      
       foreach($renderData['data'] as $key =>  $value){
           
       $renderData['data'][$key] += $template;       
       $renderData['data'][$key]['nombre'] = isset($nombres[$key]) ? $nombres[$key] : $key;
       
       };
       $renderData['data'] = array_values($renderData['data']);
       echo $this->parser->parse('pacc/tabla-provincia-localidad_excell', $renderData, true, true);
    }
    }
    function data_partidos($data) {
        $data = array(0,1,2,3,4,5);
        $data[0] = array(
            'nombre' => 'La Plata'
        );
        $data[1] = array(
            'nombre' => 'Florencio Varela'
        );
        $data[2] = array(
            'nombre' => 'Lujan'
        );
        $data[3] = array(
            'nombre' => 'Berazategui'
        );
        $data[4] = array(
            'nombre' => 'Avellaneda'
        );
        $data[5] = array(
            'nombre' => 'San Nicolás de los Arroyos'
        );
        return $data;
    }
    function filter_provincias($option = null){
       $this->load->library('parser');
       $this->load->model('app');
       $data = array();
       $value =0;
       $provincias = $this->app->get_ops(39);
       
       foreach ($provincias as $provincia => $i){
           $data[$i] = array(
             'nombre' => $i,
             'value' => $value
             );
       $value++;
       }
       $renderData['data'] = $data;
      return $this->parser->parse('select-provincias', $renderData, true, true);   
    }
    function filter($param){
        
       $this->load->module('pacc13/api13');
       $this->load->library('parser');
       $this->load->model('app');
       $filter="";
       $param = urldecode($param);
       $data = $this->api13->incubadoras_por_provincia($filter, 'array');
       $value =0;
       $provincias = $this->app->get_ops(39);
       
       foreach ($provincias as $provincia => $i){
            $data[$i] = array(
            'nombre' => $i,
            'value' => $value
            );
       $value++;
       }
       
       $provincias = array_flip($provincias);
       $param = $provincias[$param];
       $renderData['data']= $data[$param];
       echo $this->parser->parse('pacc/tabla-incubadoras-provincia-localidad', $renderData, true, true);   
  }
    function filter_partidos($option = null){
       $this->load->library('parser');
        
       $filter['provincia'] = "CBA";
       $renderData['data'] = array();
       $option = urldecode($option);
       if ($option == "Buenos Aires"){$renderData['data']= $this->data_partidos($data);};
       return $this->parser->parse('select-partidos', $renderData, true, true);
       

    }
    function reload_table($id, $param){
       $data = array();
       $this->load->module('pacc13/api13');
       $this->load->model('app');
       $this->load->library('parser');
       $param = urldecode($param);
       $provincias = $this->app->get_ops(39);
       
       if ($id == "BUE"){
           
        $renderData['secciones'] = $this->api13->incubadoras_por_seccion_electoral(null,'array');
        foreach ($renderData['secciones'] as $i => &$seccion){
            $seccion['seccion'] = $i;
        }
        
        $renderData['secciones'] = array_reverse($renderData['secciones']);
        
        echo $this->parser->parse('reload-table-ba', $renderData, true, true);   
        }else{
      
       $nombre_prov = $param;
       
       $provincias = array_flip($provincias);
       $filter['provincia']= $provincias[$nombre_prov];
       
       $data = $this->api13->incubadoras_por_provincia($filter, 'array');
       
    //   $nombres = $this->api13->incubadoras_listado('array');
    //   $value =0;
       
    //   foreach ($provincias as $provincia => $i){
    //       $data[$i] = array(
    //          'nombre' => $i,
    //          'value' => $value
    //          );
    //   $value++;
    //   }
    //   $provincias = array_flip($provincias);
    //   $param = $provincias[$param];
       
     //  $renderData['data']= $data;
       $renderData['cantidad'] = count($data[$filter['provincia']]);
       $renderData['provincia'] = $nombre_prov;
      
       echo $this->parser->parse('reload-table', $renderData, true, true); 
       }
    }
    function reload_map(){
        $this->load->module('dashboard');
        $renderData['title'] = "Mapa De Buenos Aires";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template="dashboard/widgets/box_info.php";
        echo $this->parser->parse('map-svg-bs', $renderData, true, true);
    }
    function reload_filter_partidos($option = null){
       $data = array();    
       $this->load->module('pacc13/api13');
       $this->load->library('parser');
       $this->load->model('app');
       $option = urldecode($option);
       $provincias = $this->app->get_ops(39);
       $value=0;
       foreach ($provincias as $provincia => $i){
           $data[$i] = array(
             'nombre' => $i,
             'value' => $value
             );
       $value++;
       }
       $provincias = array_flip($provincias);
       $filter['provincia'] = $provincias[$option];
       
       $renderData['data']= $this->api13->filtrar_partidos($filter,'array');
      // $renderData['partidos'] = $this->api13->incubadoras_por_partido($filter,'array');
       
       echo $this->parser->parse('select-partidos', $renderData, true, true);   
    }
    function reload_seccion($title){
        $this->load->library('parser');
        $title = urldecode($title);
        $renderData['seccion'] = $title;
        echo $this->parser->parse('seccion-table', $renderData, true, true); 
     
    }
}
