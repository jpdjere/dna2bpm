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
    
    function requerimientos(){
        $this->load->library('table');
        
    $req=array(
        "Capacitacion Pyme"=> array ('V1313','V1315','V1316','V3955','V3956','V1308','V518','V1329','V1135','V1716','V1237','V1325','V3523','V3280','V1256','V2384','V3031','V1255','V1254','V1428','V1228','V1229','V1429'),
            //-----ciudades para emprender
        "Ciudades para Emprender" => array ('V3943','V3944','V3945','V3946','V3947','V3948','V3949','V3950'),
        
        "PACC Emprendedores"=>array(
            "V3697","V3698","V1507","V3384","V3398","V3425","V3401","V2152","V3396","V3395","V3391","V3392","V3387","V1504","V1507","V3386","V3388","V3389","V3700","V3450","V3451","V3452","V3599","V3470","V3954","V2620","V2621","V2622","V2623","V2624","V2625","V3239","V3250","V3251","V3278","V3279","V3302","V3304","V3303","V3305","V2239","V3245","V3247","V3248","V2163","V3266","V3267","V3268","V3269","V3307","V3261","V7085","V3262","V3263","V3290","V2164","V2165","V3291","V3292","V3293","V3286","V3287","V3288","V2166","V3422","V2592","V3380","V2635","V3323","V3321","V2228","V2227"
            ),
        "PACC Empresas"=>array(
            "V3407","V3429","V3404","V3405","V2279","V2280","V1961","V1992","V1859","V1937","V1878","V1877","V6708","V3610","V1951","V1954","V1952","V2013","V2014","V2015","V2016","V2017","V2028","V2029","V2030","V2031","V1979","V2002","V2459","V2460","V1940","V1941","V2453","V2454","V1965","V1973","V2038","V2070","V2092","V2095","V3066","V3535",
            ),
        "Misión comercial" => array ('V3019','V1311','V1308','V518','V3198','V3204'),
        "Ucap Dircon" => array ('V1123','V1129','V1170','V1136','V1137','V1128','V1126','V1139','V1140','V1137'),
        "Capital Semilla" => array ('V1539','V1541','V1543','V1551','V1600','V1542','V1495','V1553','V1552','V1547','V1549','V1555','V1556','V1512','V1504','V1505','V1545','F364','F366','V1554','V1308','V518','V1546'),
        );
    // $this->db->debug=true;
    //-----tomo entidades
    $entidades=$this->db->get('entities')->result_array();
    //----tomo vistas
    $flat_req=array();
    foreach($req as $rarr)
    $flat_req=array_merge($flat_req,$rarr);
    $flat_req=array_unique($flat_req);
    sort($flat_req);

    $objetos=$this->db->where_in('idobj',$flat_req)->get('forms')->result_array();
    $flat_preguntas=array();
    $preguntas_index=array();
    //---recorro los form y reuno las preguntas
    foreach ($objetos as $form){
        $flat_preguntas[$form['ident']]=(!is_array($flat_preguntas[$form['ident']])) ? array():$flat_preguntas[$form['ident']];
        $flat_preguntas[$form['ident']]=array_merge($flat_preguntas[$form['ident']],array_keys($form['frames']));
        
        //---agrego al index
        foreach(array_keys($form['frames']) as $idframe){
            $preguntas_index[$idframe]=(!is_array($preguntas_index[$idframe])) ? array():$preguntas_index[$idframe];
            array_push($preguntas_index[$idframe],$form['idobj']);
        }
            
        
        // var_dump(array_merge($flat_preguntas[$form['ident']],array_keys($form['frames'])));
    }
    //-----armo la tabla de preguntas
    // $this->db->debug=true;
    $heading=array('#','idpreg','Tipo','Nombre', 'Entidad');
    $i=0;
    $programas=array_keys($req);
    $heading=array_merge($heading,$programas);
    $heading=array_merge($heading,array('cant'));
    $this->table->set_heading($heading);
    foreach($flat_preguntas as $identidad=>$frames){
        $entidad=$this->db->where(array('ident'=>$identidad))->get('entities')->result_array();
        $preguntas=$this->db->where_in('idframe',$frames)->order_by(array('idframe'=>'asc'))->get('frames')->result_array();
        
        foreach($preguntas as $pregunta){
            if(in_array($pregunta['type'],array('label','subform','alias'))) continue;
            if($pregunta['type']=='datetime' && $pregunta['automatic']) continue;
            $row=array(++$i,$pregunta['idframe'],$pregunta['type'],strip_tags($pregunta['title']),$entidad[0]['name']);
            ///----evaluo para cada programa si está la pregunta
            
            foreach($req as $preguntas_programa){
                $match=(array_intersect($preguntas_programa,$preguntas_index[$pregunta['idframe']]))?'x':'';
                $match=(array_intersect($preguntas_programa,$preguntas_index[$pregunta['idframe']]))?count(array_intersect($preguntas_programa,$preguntas_index[$pregunta['idframe']])):'';
                array_push($row,$match);
            }
            //---pongo la cantidad de matches
            $cant=count($preguntas_index[$pregunta['idframe']]);
            array_push($row,$cant);
            
            $this->table->add_row($row);
            
        }
    }
    
    //----template
    $template = array(
        'table_open'            => '<table border="1" cellpadding="4" cellspacing="0">',

        'thead_open'            => '<thead>',
        'thead_close'           => '</thead>',

        'heading_row_start'     => '<tr>',
        'heading_row_end'       => '</tr>',
        'heading_cell_start'    => '<th>',
        'heading_cell_end'      => '</th>',

        'tbody_open'            => '<tbody>',
        'tbody_close'           => '</tbody>',

        'row_start'             => '<tr>',
        'row_end'               => '</tr>',
        'cell_start'            => '<td>',
        'cell_end'              => '</td>',

        'row_alt_start'         => '<tr>',
        'row_alt_end'           => '</tr>',
        'cell_alt_start'        => '<td>',
        'cell_alt_end'          => '</td>',

        'table_close'           => '</table>'
);

$this->table->set_template($template);
    echo $this->table->generate();
    
    }

}

/* End of file empresa */
/* Location: ./system/application/controllers/welcome.php */