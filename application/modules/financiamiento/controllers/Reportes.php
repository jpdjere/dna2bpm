<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * Description of the class
 * 
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 */
class Reportes extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->model('model_reportes');
        $this->load->library('parser');
        $this->load->model('bpm/bpm');
        $this->load->module('bpm/kpi');
        $this->load->model('bpm/Kpi_model');
    }
    
    function Index() {
        $this->user->authorize();
    	$this->load->module('dashboard');
    	$this->dashboard->dashboard('financiamiento/json/kpi_menu.json');
    }
    
    function get_cases_by_kpi($kpi){
        //obtiene los casos con el kpi
        return $this->kpi->Get_cases($kpi);
    }
    
    function get_form_data($cases){
        //$obtiene la data de la base de $cases
        return $this->model_reportes->get_cases_data($cases);
    }
    
    function get_bpm_data($cases, $kpi){
        //$obtiene la data del bpm de $cases
        $i=1;
        foreach($cases as $case){
            $idcase = $case ['case'];
			$bpm_case = $this->bpm->get_case ( $idcase, $kpi ['idwf'] );
			$bpm_case ['data'] = $this->bpm->load_case_data ($bpm_case);
			// ---Ensures $case['data'] exists
			$bpm_case ['data'] = (isset ( $bpm_case ['data'] )) ? $bpm_case ['data'] : array ();
			$token = $this->bpm->get_token ( $kpi ['idwf'], $idcase, $kpi ['resourceId'] );
			// ---Flatten data a bit so it can be parsed
			$new_data [] = array_merge ( array (
		        'contador'=>$i,
				'token' => $token ['_id'],
				'resrourceId' => $kpi ['resourceId'],
				'status'=>$bpm_case['status'],
				'checkdate' => date ( $this->lang->line ( 'dateTimeFmt' ), strtotime ( $bpm_case ['checkdate'] ) ),
			), $case);
			$i++;
        }
        return $new_data;
    }
    
    function get_filter($kpi){
        //obtiene los filtros de $kpi
        return json_decode ( $kpi ['list_fields']);
    }
    
    function parse_values(&$cases){
        //parsea los valores numericos a la descripcion del valor
        $this->load->helper('listas');
        $tabla_maestra=array(
            'tipo_sociedad'=>get_tipo_sociedad(),
            'provincia'=>get_provincia(),
            'compartir_efis'=>get_si_no(),
            'bancos_otros'=>get_todos_bancos(),
            'sector_actividad'=>get_sector_actividad(),
            'cat_pyme'=>get_cat_pyme(),
            'tiene_prestamos'=>get_si_no(),
            'clasificacion_deudores'=>get_si_no(),
            'tiene_tramite'=>get_si_no(),
            'destino_prestamo'=>get_destino_prestamo(),
            'sectores_proyecto'=>get_sectores_proyecto(),
            'parque_industria'=>get_si_no(),
            'concurso_homologado'=>get_si_no(),
            'sectores_proyecto_nobanc'=>get_sectores_proyecto(),
            'destino_prestamo_nobanc'=>get_destino_prestamo_fona(),
            'monto_solicitado_bienes_cap'=>get_monto_prestamo_bienes_cap(),
            'monto_solicitado_inversion_prod'=>get_monto_prestamo_inversion_prod(),
            'destino_prestamo_gran'=>get_destino_prestamo_gran(),
            'sectores_proyecto_gran'=>get_sectores_proyecto(),
            'situacion_2_gran'=>get_si_no(),
            'deuda_afip_gran'=>get_si_no(),
            'signo_negativo_gran'=>get_si_no(),
            'endeudamiento_gran'=>get_si_no(),
            'liquidez_gran'=>get_si_no(),
            'capital_trabajo'=>get_si_no(),
            'monto_solicitado_otros'=>get_monto_solicitado_otros(),
        );
        
        foreach($cases as &$case){
            foreach($case as $key=>&$value){
                if(array_key_exists($key, $tabla_maestra)){
                    if(is_array($value)){
                        foreach($value as $arr_key=>&$arr_val){
                            $case[$key][$arr_key]=$tabla_maestra[$key][$arr_val];
                        }
                    }else{
                        $case[$key]=$tabla_maestra[$key][$value];
                    }
                }
            }
        }
    }
    
    function parse_subarrays(&$cases){
        //cambia los subarrays del array recibido como parametro en una unica cadena
        foreach($cases as &$case){
            foreach($case as $key=>&$value){
                if(is_array($value)){
                    $case[$key]=implode(', ', $value);
                }
            }
        }
    }
    
    function make_table($filter, $cases){
        //construye la tabla con el contenido de $cases pero solo con los campos de $filter
		foreach ($filter as $key => $value ) {
			$header [] = '<th>' . $key . '</th>';
			$values [] = "<td>{" . $value . "}</td>\n";
		}
        $customData['cases']=$cases;
        $customData['campos']=implode($header);
        $customData['datos']='{cases}<tr>'.implode($values).'</tr>{/cases}';
        $content = html_entity_decode($this->parser->parse('financiamiento/reportes/content.php', $customData, true, true));
        return $this->parser->parse_string($content, $customData, true, true);
    }
    
    function make_report($kpi, $table){
        //construye el reporte
        $customData['content']=$table;
        $customData['title']=$kpi['title'];
        $customData['base_url']=$this->base_url;
        return $this->parser->parse('financiamiento/reportes/basico.php', $customData, true, true);
    }
    
    function get_report($kpi_id, $exportar=false){
        //obtiene el reporte
        $kpi = $this->Kpi_model->get($kpi_id);
        $cases = $this->get_cases_by_kpi($kpi);
        if($cases){
            $cases = $this->get_form_data($cases);
            $cases = $this->get_bpm_data($cases, $kpi);
            $this->parse_values($cases);
            $this->parse_subarrays($cases);
            $filter = $this->get_filter($kpi);
            $table = $this->make_table($filter, $cases);
            $report = $this->make_report($kpi, $table);
            if($exportar){
                $customData['content']=$report;
                $customData['filename']=$kpi['title'].rand().'.xls';
                echo $this->parser->parse('financiamiento/reportes/exportar', $customData, true, true);
            }else{
                echo $report;
            }
        }else{
            echo 'No hay casos para este reporte.';
        }
    }
    /********************VIEWS****************************/
    function mostrar_menu(){
        //muestra un menu con todos los kpis de este model
        $kpis=$this->kpi_model->get_model('form_entrada');
        $customData['base_url']=$this->base_url;
        $customData['reportes']=$kpis;
        return $this->parser->parse('financiamiento/reportes/menu_kpi', $customData, true, true);
    }
    
    function test(){
        $kpis=$this->kpi_model->get_model('form_entrada');
        //var_dump($kpis);
        $customData['base_url']=$this->base_url;
        $customData['reportes']=$kpis;
        echo $this->parser->parse('financiamiento/reportes/menu_kpi', $customData, true, true);
    }
}




