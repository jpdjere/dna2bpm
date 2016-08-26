<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * inventory
 * 
 * Da de alta los prestamos que informan los bancos
 * 
 * @author Sebastian Blazquez
 * @date    Jul 13, 2016
 */
class prestamos extends MX_Controller {

    var $config;

    var $fecha = array (
        2 => '31', 3 => '31', 4 => '30', 5 => '31', 6 => '30', 7 => '31', 8 => '31', 9 => '28', 10 => '31',
        11 => '30', 12 => '31', 13 => '30', 14 => '31', 15 => '31', 16 => '30', 17 => '31', 18 => '30',
        19 => '31', 20 => '31', 21 => '28', 22 => '31', 23 => '30', 24 => '31', 25 => '30', 26 => '31',
        27 => '31', 28 => '30', 29 => '31', 30 => '30', 31 => '31', 32 => '31', 33 => '28', 34 => '31',
        35 => '30', 36 => '31', 37 => '30', 38 => '31', 39 => '31', 40 => '30', 41 => '31', 42 => '30',
        43 => '31', 44 => '31', 45 => '29', 46 => '31', 47 => '30', 48 => '31', 49 => '30', 50 => '31',
        51 => '31', 52 => '30', 53 => '31', 54 => '30', 55 => '31', 56 => '31', 57 => '28', 58 => '31',
        59 => '30', 60 => '31',
    );

   function __construct() {
        parent::__construct();
        $this->load->model('user/user');
        $this->load->model('user/group');
        $this->load->model('model_prestamos');
        $this->load->module('dashboard');
        $this->load->library('parser');
        $this->load->helper('amortizacion/aleman');
        $this->load->helper('amortizacion/frances');
        // $this->user->authorize('modules/bonita'); ??
        $this->base_url = base_url();
        //$this->module_url = base_url() . 'bonita/'; ??

        $prestamosDIR = dirname($_SERVER["SCRIPT_FILENAME"]) . '/application/modules/bonita/uploads';

        if (!is_dir($prestamosDIR)) {
            mkdir($prestamosDIR, 0777, true);
        }

        $this->config = array(
            'upload_path' => $prestamosDIR,
            'upload_url' => $this->base_url,
            'allowed_types' => "xls|xlsx",
            'overwrite' => true,
        );
    }

    /**
     * Redirecciona al Menu de los Prestamos
     */
    function Index(){
        redirect($this->base_url.'bonita/menu_prestamos');
    }
    
    
    
    /**
     * Inserta una entidad en la base
     */
    function insertar_entidad(){
        return $this->model_prestamos->insertar_entidad($this->input->post());
    }
    
    /**
     * Actualiza una entidad en la base
     */
    function actualizar_entidad(){
        return $this->model_prestamos->actualizar_entidad($this->input->post());
    }
    
    /**
     * Borra una entidad en la base
     */
    function borrar_entidad(){
        return $this->model_prestamos->borrar_entidad($this->input->post());
    }
    
    
    
    /**
     * Inserta un destino en la base
     */
    function insertar_destino(){
        return $this->model_prestamos->insertar_destino($this->input->post());
    }
    
    /**
     * Actualiza un destino en la base
     */
    function actualizar_destino(){
        return $this->model_prestamos->actualizar_destino($this->input->post());
    }
    
    /**
     * Borra un destino en la base
     */
    function borrar_destino(){
        return $this->model_prestamos->borrar_destino($this->input->post());
    }
    
    
    
    /**
     * Inserta una resolucion en la base
     */
    function insertar_resolucion(){
        return $this->model_prestamos->insertar_resolucion($this->input->post());
    }
    
    /**
     * Actualiza una resolucion en la base
     */
    function actualizar_resolucion(){
        return $this->model_prestamos->actualizar_resolucion($this->input->post());
    }
    
    /**
     * Borra una resolucion en la base
     */
    function borrar_resolucion(){
        return $this->model_prestamos->borrar_resolucion($this->input->post());
    }
    
    
    
    /**
     * Inserta un monto en la base
     */
    function insertar_monto(){
        return $this->model_prestamos->insertar_monto($this->input->post());
    }
    
    /**
     * Actualiza un monto en la base
     */
    function actualizar_monto(){
        return $this->model_prestamos->actualizar_monto($this->input->post());
    }
    
    /**
     * Borra un monto en la base
     */
    function borrar_monto(){
        echo $this->model_prestamos->borrar_monto($this->input->post());
    }



    /**
     * Muestra el abm de entidades
     */
    function AbmEntidades(){
        $C = "1500000.00";
        $T = 22;
        $ptosbon = 5;
        $days = "31";
        $fecha = array (
            2 => '31', 3 => '31', 4 => '30', 5 => '31', 6 => '30', 7 => '31', 8 => '31', 9 => '28', 10 => '31',
            11 => '30', 12 => '31', 13 => '30', 14 => '31', 15 => '31', 16 => '30', 17 => '31', 18 => '30',
            19 => '31', 20 => '31', 21 => '28', 22 => '31', 23 => '30', 24 => '31', 25 => '30', 26 => '31',
            27 => '31', 28 => '30', 29 => '31', 30 => '30', 31 => '31', 32 => '31', 33 => '28', 34 => '31',
            35 => '30', 36 => '31', 37 => '30', 38 => '31', 39 => '31', 40 => '30', 41 => '31', 42 => '30',
            43 => '31', 44 => '31', 45 => '29', 46 => '31', 47 => '30', 48 => '31', 49 => '30', 50 => '31',
            51 => '31', 52 => '30', 53 => '31', 54 => '30', 55 => '31', 56 => '31', 57 => '28', 58 => '31',
            59 => '30', 60 => '31',
        );
        $n = 60;
        $gc = 6;
        $gi = 0;
        $frec_cap = 1;
        $frec_int = 1;
        aleman($C, $T, $ptosbon, $days, $fecha, $n, $gc, $gi, $frec_cap, $frec_int);

        $C = "1500000.00";
        $T = 17;
        $ptosbon = 5;
        $days = "31";
        $fecha = array (
            2 => '31', 3 => '31', 4 => '30', 5 => '31', 6 => '30', 7 => '31', 8 => '31', 9 => '28', 10 => '31',
            11 => '30', 12 => '31', 13 => '30', 14 => '31', 15 => '31', 16 => '30', 17 => '31', 18 => '30',
            19 => '31', 20 => '31', 21 => '28', 22 => '31', 23 => '30', 24 => '31', 25 => '30', 26 => '31',
            27 => '31', 28 => '30', 29 => '31', 30 => '30', 31 => '31', 32 => '31', 33 => '28', 34 => '31',
            35 => '30', 36 => '31', 37 => '30', 38 => '31', 39 => '31', 40 => '30', 41 => '31', 42 => '30',
            43 => '31', 44 => '31', 45 => '29', 46 => '31', 47 => '30', 48 => '31', 49 => '30', 50 => '31',
            51 => '31', 52 => '30', 53 => '31', 54 => '30', 55 => '31', 56 => '31', 57 => '28', 58 => '31',
            59 => '30', 60 => '31',
        );
        $n = 60;
        $gc = 6;
        $gi = 0;
        $frec_cap = 1;
        $frec_int = 1;
        $a= frances($C, $T, $ptosbon, $days, $fecha, $n, $gc, $gi, $frec_cap, $frec_int);
        $this->dashboard->dashboard('bonita/json/prestamos/abm_entidades.json');
    }
    
    function contenido_abm_entidades(){
        $customData['content'] = $this->model_prestamos->listar_entidades();
        $customData['base_url'] = $this->base_url;
        echo $this->parser->parse('bonita/views/prestamos/abm/crud_entidades', $customData, true);
    }
    
    /**
     * Muestra el abm de los destinos
     */
    function AbmDestinos(){
        $this->dashboard->dashboard('bonita/json/prestamos/abm_destinos.json');
    }
    
    function contenido_abm_destinos(){
        $customData['content'] = $this->model_prestamos->listar_destinos();
        $customData['base_url'] = $this->base_url;
        $this->capitalize_array($customData);
        echo $this->parser->parse('bonita/views/prestamos/abm/crud_destinos', $customData, true);
    }
    
    /**
     * Muestra el abm de las resoluciones
     */
    function AbmResoluciones(){
        $this->dashboard->dashboard('bonita/json/prestamos/abm_resoluciones.json');
    }
    
    function contenido_abm_resoluciones(){
        $this->load->helper('prestamos/prestamos');
        $customData['content'] = $this->model_prestamos->listar_resoluciones();
        $categorias_pyme = $this->model_prestamos->listar_categorias_pyme();
        categorias_parser($categorias_pyme);
        foreach($customData['content'] as &$reso){
            foreach($categorias_pyme as $entrada){
                if($entrada['id_resolucion'] == $reso['id']){
                    $reso['tamano'][] = ['tamano'=>$entrada['tamano'], 'monto'=>$entrada['monto'], 'tamano_parseado'=>$entrada['tamano_parseado']];
                }
            }
        }
        // var_dump($customData['content']);
        // exit;
        $customData['base_url'] = $this->base_url;
        $this->capitalize_array($customData);
        echo $this->parser->parse('bonita/views/prestamos/abm/crud_resoluciones', $customData, true);
    }
    
    /**
     * Muestra el abm de los montos por destino
     */
    function AbmMontos(){
        $this->dashboard->dashboard('bonita/json/prestamos/abm_montos.json');
    }
    
    function contenido_abm_montos(){
        $customData['content'] = $this->model_prestamos->listar_montos();
        $customData['base_url'] = $this->base_url;
        $customData['resoluciones'] = $this->model_prestamos->listar_resoluciones();
        $customData['destinos'] = $this->model_prestamos->listar_destinos();
        $this->capitalize_array($customData);
        //var_dump($customData);exit;
        echo $this->parser->parse('bonita/views/prestamos/abm/crud_montos', $customData, true);
    }
    
    function capitalize_array(&$array){
        array_walk_recursive($array, function(&$value){$value=ucwords($value);});
    }
    
    function parse_array($array){
        foreach($array as $key => $value){
            $new_array[]=array('clave'=>$key, 'valor'=>$value);
        }
        return $new_array;
    }
    
    /**
     * Muestra el formulario de carga de prestamos
     */
    function AltaPrestamosManual(){
        $this->dashboard->dashboard('bonita/json/prestamos/alta_prestamos_manual.json');
    }
    
    function contenido_alta_prestamos_manual(){
        $customData['partidos'] = $this->parse_array($this->app->get_ops(58));
        $customData['provincias'] = $this->parse_array($this->app->get_ops(39));
        $customData['sectores'] = $this->parse_array($this->app->get_ops(494));
        $customData['entidades']=$this->model_prestamos->listar_entidades();
        $customData['destinos'] = $this->model_prestamos->listar_destinos();
        $customData['sis_amortizacion'] = $this->model_prestamos->listar_sis_amortizacion();
        $customData['resoluciones'] = $this->model_prestamos->listar_resoluciones();
        
        $this->capitalize_array($customData);
        
        $muni = $this->app->get_ops(741);
        //var_dump($muni);exit;
        echo $this->parser->parse('bonita/views/prestamos/altaprestamos/formulario_carga', $customData, true);
    }

    /**
     * Muestra el formulario para subir los prestamos importandolos desde un Excel
     */
    function AltaPrestamosImport($error = ''){
        $extraData['alerts'] = '<p>'.urldecode($error).'</p>';
        $this->dashboard->dashboard('bonita/json/prestamos/importar_excel.json', false, $extraData);
    }

    function contenido_importar_excel(){
        $this->load->helper(['form', 'url']);
        $this->load->view('bonita/views/prestamos/altaprestamos/importar_excel',false, $error);
    }
    
    /**
     * Sube el excel con los datos de los prestamos
     */
    function upload_excel(){

        $this->load->library('upload', $this->config);
        if(!$this->upload->do_upload('userfile')){
            redirect('bonita/prestamos/AltaPrestamosImport/'.$this->upload->display_errors(''   , ''));
        }

        $full_path = $this->upload->data('full_path');
        if(!chmod($full_path, 0774)){
            redirect('bonita/prestamos/AltaPrestamosImport/PermissionError');
        }

        $this->load->library('bonita/Excel');
        $excel_file = $this->excel->load($full_path);
        
        define('FILA_MINIMA', 9);
        define('COLUMNA_MINIMA', 'A');
        define('COLUMNA_MAXIMA', 'BE');

        $this->load->library('bonita/Excel');
        $excel_file = $this->excel->load($full_path);
        $sheet = $excel_file->getSheet(0);
        $data = $sheet->rangeToArray(COLUMNA_MINIMA.FILA_MINIMA.':'.COLUMNA_MAXIMA.$sheet->getHighestRow());
        //$this->load->library('bonita/validator');
        $this->limpiarFilasVacias($data);
        foreach($data as $row){
            //$result = $this->validator->altaprestamos($row);
            //if(!$result == true){
                //redirect('bonita/prestamos/AltaPrestamosImport/'.$result);
            //}
            $arrayPrestamo = $this->generarArrayDePrestamo($row); //TODO: validar array resultado con reglas validacion

            $this->calcularCuotas($arrayPrestamo);
        }
    }

    private function calcularCuotas ($arrayPrestamo) {

        $capital = $arrayPrestamo['capitalAcreditado'];
        $tna_bruta = $arrayPrestamo['tnaPymesNeta'];
        $puntos_bon = $arrayPrestamo['puntosBonif'];
        $days = 31; //TODO: ver de donde sale en bonita viejo
        $num_days = $this->fecha; //TODO: Calcular array en base a la fecha de comienzo del prestamo
        $cantcuot = $arrayPrestamo['plazoTotalEnMeses'];
        $gracia_cap = $arrayPrestamo['graciaCapital'];
        $gracia_int = 0; //No viene mas en el nuevo excel
        $frec_cap = $arrayPrestamo['frecuenciaServiciosCapital'];
        $frec_int = $arrayPrestamo['fecha1erVencCuotaInteres'];

        //Pasa la gracia de períodos a meses (de bonita viejo)
        $gracia_cap = $gracia_cap * $frec_cap;
        $gracia_int = $gracia_int * $frec_int;

        switch ($arrayPrestamo['sistemaAmort']) {
            case 'ALEMAN':
                $tna_bruta=($tna_bruta*100)+$puntos_bon; //(de bonita viejo)
                $paymentsystem = aleman($capital,$tna_bruta,$puntos_bon,$days,$num_days,$cantcuot,$gracia_cap,$gracia_int,$frec_cap,$frec_int);
                break;
            case 'FRANCES':
                $tna_bruta=($tna_bruta*100); //(de bonita viejo)
                $paymentsystem = frances($capital,$tna_bruta,$puntos_bon,$days,$num_days,$cantcuot,$gracia_cap,$gracia_int,$frec_cap,$frec_int);
        }

        for($i=1;$i<=$cantcuot;$i++) {
            $paymentschedule[$i]=array_merge($paymentarray[$i],$paymentsystem[$i]);
        }
    }

    private function limpiarFilasVacias (&$data) {
        $data = array_map('array_filter', $data);
        $data = array_filter($data);
    }

    private function generarArrayDePrestamo ($row) {
        $prestamo = array();
        $prestamo['sucursalBanco'] = $row[1];
        $prestamo['razonSocial'] = $row[2];
        $prestamo['cuit'] = $row[3];
        $prestamo['domicilio'] = $row[4];
        $prestamo['localidad'] = $row[5];
        $prestamo['municipio'] = $row[6];
        $prestamo['partido'] = $row[7];
        $prestamo['provincia'] = $row[8];
        $prestamo['cp'] = $row[9];
        $prestamo['telefono'] = $row[10];
        $prestamo['mail'] = $row[11];
        $prestamo['fechaInicioAct'] = $row[12];
        $prestamo['sectorActividad'] = $row[13];
        $prestamo['sectoresPriorizados'] = $row[14];
        $prestamo['codActividadFinanciera'] = $row[15];
        $prestamo['detalleActividad'] = $row[16];
        $prestamo['ventaAnualUltimotEjer'] = $row[17];
        $prestamo['periodoCorrespondiente'] = $row[18];
        $prestamo['promVentas3Ejer'] = $row[19];
        $prestamo['hasta20porcientoImporte'] = $row[20];
        $prestamo['montoInversionTotal'] = $row[21];
        $prestamo['cantEmpleados'] = $row[22];
        $prestamo['nroPrestamo'] = $row[23];
        $prestamo['fechaFirmaContrato'] = $row[24];
        $prestamo['fechaAcreditacionPrestamo'] = $row[25];
        $prestamo['capitalAcreditado'] = $row[26];
        $prestamo['fechaEntregaDelBien'] = $row[27];
        $prestamo['destinoDeFondos'] = $row[28];
        $prestamo['plazoTotalEnMeses'] = $row[29];
        $prestamo['tnaPymesNeta'] = $row[30];
        $prestamo['tnaBruta'] = $row[31];
        $prestamo['puntosBonif'] = $row[32];
        $prestamo['sistemaAmort'] = $row[33];
        $prestamo['cantCuotasCapital'] = $row[34];
        $prestamo['cantCuotasInteres'] = $row[35];
        $prestamo['fecha1erVencCuotaCapital'] = $row[36];
        $prestamo['fecha1erVencCuotaInteres'] = $row[37];
        $prestamo['montoCanon'] = $row[38];
        $prestamo['montoCanonDiferencial'] = $row[39];
        $prestamo['montoOpcionCompra'] = $row[40];
        $prestamo['fecha2doVencCuotaCapital'] = $row[41];
        $prestamo['fecha2doVencCuotaInteres'] = $row[42];
        $prestamo['graciaCapital'] = $row[43];
        $prestamo['frecuenciaServiciosCapital'] = $row[44];
        $prestamo['frecuenciaServiciosInteres'] = $row[45];
        $prestamo['esquemaDesembolsos'] = $row[46];
        $prestamo['montoTotalDesembolsado'] = $row[47];
        $prestamo['inclusionFinanciera'] = $row[48];
        $prestamo['planBelgranoIndicar'] = $row[49];
        $prestamo['planBelgranoProvincia'] = $row[50];
        $prestamo['planBelgranoDomicilio'] = $row[51];
        $prestamo['radicadaEnParqueIndustrial'] = $row[52];
        $prestamo['garantiaSGR'] = $row[53];
        $prestamo['SRGinvolucrada'] = $row[54];
        $prestamo['garantia'] = $row[55];
        $prestamo['esquemaBonificacion'] = $row[56];
        $prestamo['observaciones'] = $row[57];
        return $prestamo;
    }

    /**
     * Inserta el prestamo en la base
     */
    function insertar_prestamo(){
        $this->load->helper('prestamos');
        $data = $this->input->post();
        
        $this->model_prestamos->insertar_tabla_temp_prestamos($this->input->post(), $this->user->idu);
    }    
}