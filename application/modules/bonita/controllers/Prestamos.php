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
   var $arrayCuotas = array();

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

    function mostrar_cuotas_calculadas () {
        $customData['base_url'] = $this->base_url;
        $cuotas = $this->arrayCuotas;
        $lista = new String();
        foreach($cuotas as $lic){
            $lista =  $lista.
                '<tr>
                <td>'.$lic['fecha_pago'].'</td>
                <td>'.$lic['fecha_liq'].'</td>
                <td>'.$lic['num_days'].'</td>
                <td>'.$lic['amortizacion'].'</td>
                <td>'.$lic['remaining'].'</td>
                <td>'.$lic['intereses'].'</td>
                <td>'.$lic['cuota'].'</td>
                <td>'.$lic['accInt'].'</td>
                <td>'.$lic['bonif'].'</td>
                <td>'.$lic['periodo'].'</td>
                <td>'.$lic['puntos_bon'].'</td>
                <td>'.$lic['accCap'].'</td>
            </tr>';
        }
        $customData['lista'] = $lista;
        return $this->parser->parse('bonita/views/prestamos/ver_prestamos',$customData,true,true);
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

        define('FILA_MINIMA', 9);
        define('COLUMNA_MINIMA', 'A');
        define('COLUMNA_MAXIMA', 'BE');

        $this->load->library('bonita/Excel');
        $excel_file = $this->excel->load($full_path);
        $sheet = $excel_file->getSheet(0);
        $data = $sheet->rangeToArray(COLUMNA_MINIMA.FILA_MINIMA.':'.COLUMNA_MAXIMA.$sheet->getHighestRow());
        //$this->load->library('bonita/validator'); TODO: ver de abstraer validaciones en library o helpers
        $data = $this->limpiarFilasVacias($data);
        foreach($data as $row){
            //$result = $this->validator->altaprestamos($row);
            //if(!$result == true){
                //redirect('bonita/prestamos/AltaPrestamosImport/'.$result);
            //}
            $arrayPrestamo = $this->generarArrayDePrestamo($row); //TODO: validar array resultado con reglas validacion

            //En bonita viejo:  $cantcuot=$plazo/$frec_int;
            $cantidadCuotas = $arrayPrestamo['plazoTotalEnMeses']/$arrayPrestamo['frecuenciaServiciosInteres'];

            $arrayPaymentDays = $this->calcularFechas($arrayPrestamo['fechaAcreditacionPrestamo'],
                $arrayPrestamo['fecha1erVencCuotaInteres'], $arrayPrestamo['frecuenciaServiciosInteres'] , $cantidadCuotas,
                $arrayPrestamo['sistemaAmort']);

            $paymentArray = $arrayPaymentDays[0];
            $days = $arrayPaymentDays[1];
            $arrayDays = $arrayPaymentDays[2];
            $paymentSchedule = $this->calcularCuotas($arrayPrestamo, $cantidadCuotas, $paymentArray, $days, $arrayDays);
            /*
             * TODO: Falta persistir el resultado de calcularCuotas ($paymentSchedule). En bonita_prestamos, bonita_cuotas y bonita_cuotas_history)
            */
            //Ver resultados en la vista
            array_push($this->arrayCuotas,$paymentSchedule);
        }
        $this->dashboard->dashboard('bonita/json/prestamos/mostrar_cuotas.json');
    }

    /**
     * Se realiza ésta conversion para utilizar la fecha correctamente en con la funcion dateDifference.
     *
     * @param $fecha String "07/11/2016" ó serial date 42562 (excel date format).
     * @return DateTime
     */
    private function fechaToObject ($fecha) {
        if (is_numeric($fecha)){
            $fecha = PHPExcel_Shared_Date::ExcelToPHPObject($fecha);
        }
        else {
            $fecha = date_create(str_replace('/','-',$fecha));
        }
        return $fecha;
    }

    /**
     * Genera un array con la fecha recibida por parámetro
     *
     * @param $fecha DateTime 27-07-2016
     * @return array
     *
     * array (
     *      0 => '27',
     *      1 => '07',
     *      2 => '2016',
     *      )
     */
    private function fechaToArray ($fecha) {
        return explode('-', $fecha->format('d-m-Y'));
    }

    /**
     * Calculo de fechas en base a la cantidad de cuotas del préstamo.
     *
     * @param $fechaAcreditacionPrestamo
     * @param $fecha1erVencCuotaInteres
     * @param $frec_int
     * @param $cantcuot
     * @param $sistema
     * @return array
     */
    private function calcularFechas ($fechaAcreditacionPrestamo, $fecha1erVencCuotaInteres, $frec_int, $cantcuot, $sistema) {

        $fechaAcreditacionPrestamo = $this->fechaToObject($fechaAcreditacionPrestamo);

        $fecha1erVencCuotaInteres = $this->fechaToObject($fecha1erVencCuotaInteres);

        //$fechaacred = explode('/',date("d/m/Y",$fechaAcreditacion)); //No se usa porque reemplazo lineas 165 a 172 de calcAmort
        $fechaini = $this->fechaToArray($fecha1erVencCuotaInteres);

        $days= $this->dateDifference($fechaAcreditacionPrestamo,$fecha1erVencCuotaInteres); //Reemplazo lineas 165 a 172 de calcAmort

        $fecha_pago=array();
        $fecha_liq=array();
        $num_days=array();
        $paymentarray=array();

        for($i=1 ; $i <= $cantcuot ; $i++) {
            $month_step = (int)$frec_int * ($i-1);
            $month = $fechaini[1] + $month_step;
            $year = $fechaini[2];

            $ultimo_dia = $fechaini[0];

            $resto = ($month) % 12;

            if($resto==0) {
                $resto= 12;
            }

            switch ($fechaini[0]) {
                case 29:
                    $sumac = array('2');//si el dia de fecha es 29 y mes 2 el ultimo dia es 28 o 29
                    if(in_array($resto,$sumac)){
                        $ultimo_dia = date('d',mktime(0, 0, 0, $month+1, 0, $year));
                    } else $ultimo_dia = $fechaini[0];
                    break;
                case 30:
                    $sumac = array('2');//si el dia de fecha es 30 y mes 2 el ultimo dia es 28 o 29
                    if(in_array($resto,$sumac)){
                        $ultimo_dia = date('d',mktime(0, 0, 0, $month+1, 0, $year));//se pone $month+1 porque necesitamos que calcule el año siguiente cuando corresponda
                    } else $ultimo_dia = $fechaini[0];
                    break;
                case 31:
                    $sumac = array('2','4','6','9','11'); //si el dia de fecha es 31 y mes feb abril junio sep y nov el ultimo dia es 28 o 29
                    if(in_array($resto,$sumac)){
                        $ultimo_dia = date('d',mktime(0, 0, 0, $month+1, 0, $year));
                    } else $ultimo_dia = $fechaini[0];
                    break;
            }

            $fecha_pago[$i] = date('Y-m-d',mktime(0,0,0,$month,$ultimo_dia,$year));
            $fecha_liq[$i] = date('Y-m-d',mktime(0,0,0,$month,$ultimo_dia,$year));

        }
        //---array el array para bonificar los primeros n dias dps cuento los demas (comentario de bonita viejo)
        $paymentarray[1]['fecha_pago'] = $fecha_pago[1];
        $paymentarray[1]['fecha_liq'] = $fecha_liq[1];
        $paymentarray[1]['num_days'] = $days;

        for($i=2 ; $i <= $cantcuot ; $i++) {
            $date_1 = $this->fechaToObject($fecha_pago[$i-1]);
            $date_2 = $this->fechaToObject($fecha_pago[$i]);

            if($sistema == 'ALEMAN360' || $sistema == 'FRANCES360') {
                $num_days[$i] = (int)$frec_int*30;
            }
            else {
                $num_days[$i] = $this->dateDifference($date_1,$date_2);
            }

            $paymentarray[$i]['fecha_pago']=$fecha_pago[$i];
            $paymentarray[$i]['fecha_liq']=$fecha_liq[$i];
            $paymentarray[$i]['num_days']=$num_days[$i];
        }

        return (array($paymentarray, $days, $num_days));
    }

    /**
     * Dadas dos fechas, retorna la cantidad de días entre una y otra.
     *
     * @param $date_1
     * @param $date_2
     * @param string $differenceFormat '%a'= día
     * @return string
     */
    function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' ){
        $interval = date_diff($date_1, $date_2);
        return $interval->format($differenceFormat);
    }

    /**
     * Calcula la cantidad de cuotas en base al sistema de amortización del préstamo.
     *
     * @param $arrayPrestamo
     * @param $cantidadCuotas
     * @param $paymentArray
     * @param $cantidadDias
     * @param $arrayDays
     * @return array
     */
    private function calcularCuotas ($arrayPrestamo, $cantidadCuotas, $paymentArray, $cantidadDias, $arrayDays) {
        $capital = $arrayPrestamo['capitalAcreditado'];
        $tna_bruta = $arrayPrestamo['tnaPymesNeta'];
        $puntos_bon = $arrayPrestamo['puntosBonif'];
        $days = $cantidadDias;
        $num_days = $arrayDays;
        $frec_cap = $arrayPrestamo['frecuenciaServiciosCapital'];
        $frec_int = $arrayPrestamo['frecuenciaServiciosInteres'];
        $cantcuot = $cantidadCuotas;
        $gracia_cap = $arrayPrestamo['graciaCapital'];
        $gracia_int = 0; //No viene mas en el nuevo excel TODO: ¿Se elimina el parámetro o queda?

        //Pasa la gracia de períodos a meses (de bonita viejo)
        $gracia_cap = $gracia_cap * $frec_cap;
        $gracia_int = $gracia_int * $frec_int;

        switch ($arrayPrestamo['sistemaAmort']) {
            case 'ALEMAN':
                //$tna_bruta=($tna_bruta*100)+$puntos_bon; //(de bonita viejo)
                $tna_bruta += $puntos_bon;
                $paymentSystem = aleman($capital,$tna_bruta,$puntos_bon,$days,$num_days,$cantcuot,$gracia_cap,$gracia_int,$frec_cap,$frec_int);
                break;
            case 'FRANCES':
                //$tna_bruta=($tna_bruta*100); //(de bonita viejo)
                $paymentSystem = frances($capital,$tna_bruta,$puntos_bon,$days,$num_days,$cantcuot,$gracia_cap,$gracia_int,$frec_cap,$frec_int);
        }

        //Genera el esquema de pagos en base al $paymentArray y $paymentSystem
        for($i=1;$i<=$cantcuot;$i++) {
            $paymentSchedule[$i]=array_merge($paymentArray[$i],$paymentSystem[$i]);
        }
        return $paymentSchedule;
    }

    /**
     * Dado el array de datos leídos desde el excel, se eliminan las filas que no contienen datos.
     *
     * @param $data
     * @return array
     */
    private function limpiarFilasVacias ($data) {
        return array_filter($data, function($fila) {
            foreach ($fila as $valor){
                if($valor !== null && $valor !== "") {
                    return true;
                }
            }
            return false;
        });
    }

    /**
     * Parseo de datos del excel de préstamos a un array con etiquetas por tipo de dato.
     *
     * @param $row
     * @return array
     */
    private function generarArrayDePrestamo ($row) {
        $prestamo = array();
        $prestamo['sucursalBanco'] = $row[0];
        $prestamo['razonSocial'] = $row[1];
        $prestamo['cuit'] = $row[2];
        $prestamo['domicilio'] = $row[3];
        $prestamo['localidad'] = $row[4];
        $prestamo['municipio'] = $row[5];
        $prestamo['partido'] = $row[6];
        $prestamo['provincia'] = $row[7];
        $prestamo['cp'] = $row[8];
        $prestamo['telefono'] = $row[9];
        $prestamo['mail'] = $row[10];
        $prestamo['fechaInicioAct'] = $row[11];
        $prestamo['sectorActividad'] = $row[12];
        $prestamo['sectoresPriorizados'] = $row[13];
        $prestamo['codActividadFinanciera'] = $row[14];
        $prestamo['detalleActividad'] = $row[15];
        $prestamo['ventaAnualUltimotEjer'] = $row[16];
        $prestamo['periodoCorrespondiente'] = $row[17];
        $prestamo['promVentas3Ejer'] = $row[18];
        $prestamo['hasta20porcientoImporte'] = $row[19];
        $prestamo['montoInversionTotal'] = $row[20];
        $prestamo['cantEmpleados'] = $row[21];
        $prestamo['nroPrestamo'] = $row[22];
        $prestamo['fechaFirmaContrato'] = $row[23];
        $prestamo['fechaAcreditacionPrestamo'] = $row[24];
        $prestamo['capitalAcreditado'] = $row[25];
        $prestamo['fechaEntregaDelBien'] = $row[26];
        $prestamo['destinoDeFondos'] = $row[27];
        $prestamo['plazoTotalEnMeses'] = $row[28];
        $prestamo['tnaPymesNeta'] = $row[29];
        $prestamo['tnaBruta'] = $row[30];
        $prestamo['puntosBonif'] = $row[31];
        $prestamo['sistemaAmort'] = $row[32];
        $prestamo['cantCuotasCapital'] = $row[33];
        $prestamo['cantCuotasInteres'] = $row[34];
        $prestamo['fecha1erVencCuotaCapital'] = $row[35];
        $prestamo['fecha1erVencCuotaInteres'] = $row[36];
        $prestamo['montoCanon'] = $row[37];
        $prestamo['montoCanonDiferencial'] = $row[38];
        $prestamo['montoOpcionCompra'] = $row[39];
        $prestamo['fecha2doVencCuotaCapital'] = $row[40];
        $prestamo['fecha2doVencCuotaInteres'] = $row[41];
        $prestamo['graciaCapital'] = $row[42];
        $prestamo['frecuenciaServiciosCapital'] = $row[43];
        $prestamo['frecuenciaServiciosInteres'] = $row[44];
        $prestamo['esquemaDesembolsos'] = $row[45];
        $prestamo['montoTotalDesembolsado'] = $row[46];
        $prestamo['inclusionFinanciera'] = $row[47];
        $prestamo['planBelgranoIndicar'] = $row[48];
        $prestamo['planBelgranoProvincia'] = $row[49];
        $prestamo['planBelgranoDomicilio'] = $row[50];
        $prestamo['radicadaEnParqueIndustrial'] = $row[51];
        $prestamo['garantiaSGR'] = $row[52];
        $prestamo['SRGinvolucrada'] = $row[53];
        $prestamo['garantia'] = $row[54];
        $prestamo['esquemaBonificacion'] = $row[55];
        $prestamo['observaciones'] = $row[56];
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