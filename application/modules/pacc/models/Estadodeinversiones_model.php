<?php

/**
 * Funciones para el manejo de datos de Estado de inversiones. 
 * No se finalizo por falta de base UEPEX
 * 
 * @author MAGonzalez <mglongchamps@gmail.com>
 * @date 14/09/2015
 * 
 */


class estadodeinversiones_model extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        
        
        $this->load->library('cimongo/cimongo', '', 'db_pacc');
        
        $this->db_pacc->switch_db('pacc');
        $this->db_uepex = $this->load->database('uepex',TRUE);
               
    }
    
    /**
     * Lista de inversiones. 
     * 
     * @return array $result
     */
    
    function inversion_list_ap(){
        $rtn = array();
        $container = 'obviewap';
        $ap = '1.1884';
        $this->db_uepex->select('AP,Descripcion');
        $this->db_uepex->where('AP =', $ap);
        for($i = 0; $i<10;$i++){
            $this->db_uepex->or_where('AP =', $ap.'.'.$i);
            for($j = 0; $j<10;$j++){
                $this->db_uepex->or_where('AP =', $ap.'.'.$i.'.'.$j);
                           
            }
            
       
        }
        $result = $this->db_uepex->get($container)->result_array();
        
        
        return $result;
        
    }
            
    /**
     * Calculo del monto de inversión.
     *  
     * @param date $dateinit
     * @param date $datefin
     * @param string $categoria
     * @param string $fuente
     * @return int $totalusd
     */
    
    
    function inversion_calc_monto($dateinit, $datefin, $categoria,$fuente){
        
        $rtn = array();
        $container = 'ppitemsmedidaspres';
        $join = 'ppmedidaspres';
       
        
        //$dateinit = date_format($dateinit, 'Y-m-d H:i:s');
        
        //$datefin = date_format($datefin, 'Y-m-d H:i:s');
        //echo 'Categoría:'.$categoria.'</br>';
        //echo 'Fuente:'.$fuente.'</br>';
        //echo $dateinit.'</br>';
        //echo $datefin.'</br>';
        $status = '-1';
        //$this->db_uepex->select_sum('Importe');
        $this->db_uepex->select('ppmedidaspres.Fecha,ppitemsmedidaspres.Importe');
        $this->db_uepex->join($join,'ppitemsmedidaspres.idMedidaPres = ppmedidaspres.idMedidaPres' );
        $this->db_uepex->where('ppmedidaspres.Fecha >', $dateinit);
        $this->db_uepex->where('ppmedidaspres.Fecha <', $datefin);
        $this->db_uepex->where('ppmedidaspres.Anulada =', '0');
        $this->db_uepex->where('ppitemsmedidaspres.idFuente =', $fuente);
        $this->db_uepex->like('AP',$categoria, 'both');
        //$this->db_uepex->limit(1);
        $result = $this->db_uepex->get($container)->result_array();
        //var_dump($result);
        $i =0;
        $totalusd = (float)0;
        foreach ($result as $list) {
           // if($list['borrado'] == 0){
            $container_moneda = 'gencotizaciones';
            $rtn[$i] = $list['Importe'];
            //echo 'Monto $:'.($rtn[$i]).'</br>';
            //echo 'Fecha Carga:'.($list['Fecha']).'</br>';
            $fecha = $list['Fecha'];
            $this->db_uepex->select('iVenta, fechaCotizacion');
            $this->db_uepex->where('fechaCotizacion <', $fecha);
            $this->db_uepex->order_by("fechaCotizacion", "desc"); 
            $this->db_uepex->limit(1);
            $cotiz = $this->db_uepex->get($container_moneda)->result_array();
            
            foreach ($cotiz as $moneda){
            //echo 'Cotizacion:'.$moneda['iVenta'].'</br>';
            //echo 'Fecha Cotizacion:'.$moneda['fechaCotizacion'].'</br>';
            }
            //var_dump($cotiz['iVenta']);
            $usd = ($rtn[$i] / $moneda['iVenta']);
            $usd = number_format((float)$usd,2, '.', '');
            //echo 'Monto U$D:'.$usd.'</br></br></br>';
            $totalusd = $totalusd + $usd;
            
            $i++;
           // }
        }
        //echo 'total U$D:'.$totalusd.'</br></br></br>';
        return $totalusd;
    }
    
    /**
     * Calculo de la inversión previa
     * 
     * @param date $datefin
     * @param string $categoria
     * @param string $fuente
     * @return int $totalusd
     */
    
    function inversion_calc_monto_prev($datefin, $categoria,$fuente){
        
        $rtn = array();
        $container = 'ppitemsmedidaspres';
        $join = 'ppmedidaspres';
       
        
        //$datefin = date_format($datefin, 'Y-m-d H:i:s');
        
        //$datefin = date_format($datefin, 'Y-m-d H:i:s');
        //echo 'Categoría:'.$categoria.'</br>';
        //echo 'Fuente:'.$fuente.'</br>';
        
        //echo $datefin.'</br>';
        $status = '-1';
        //$this->db_uepex->select_sum('Importe');
        $this->db_uepex->select('ppmedidaspres.Fecha,ppitemsmedidaspres.Importe');
        $this->db_uepex->join($join,'ppitemsmedidaspres.idMedidaPres = ppmedidaspres.idMedidaPres' );
        //$this->db_uepex->where('ppmedidaspres.Fecha >', $dateinit);
        $this->db_uepex->where('ppmedidaspres.Fecha <', $datefin);
        $this->db_uepex->where('ppmedidaspres.Anulada =', '0');
        $this->db_uepex->where('ppitemsmedidaspres.idFuente =', $fuente);
        $this->db_uepex->like('AP',$categoria, 'both');
        //$this->db_uepex->limit(1);
        $result = $this->db_uepex->get($container)->result_array();
        //var_dump($result);
        $i =0;
        $totalusd = (float)0;
        foreach ($result as $list) {
           // if($list['borrado'] == 0){
            $container_moneda = 'gencotizaciones';
            $rtn[$i] = $list['Importe'];
            //echo 'Monto $:'.($rtn[$i]).'</br>';
            //echo 'Fecha Carga:'.($list['Fecha']).'</br>';
            $fecha = $list['Fecha'];
            $this->db_uepex->select('iVenta, fechaCotizacion');
            $this->db_uepex->where('fechaCotizacion <', $fecha);
            $this->db_uepex->order_by("fechaCotizacion", "desc"); 
            $this->db_uepex->limit(1);
            $cotiz = $this->db_uepex->get($container_moneda)->result_array();
            
            foreach ($cotiz as $moneda){
            //echo 'Cotizacion:'.$moneda['iVenta'].'</br>';
            //echo 'Fecha Cotizacion:'.$moneda['fechaCotizacion'].'</br>';
            }
            //var_dump($cotiz['iVenta']);
            $usd = ($rtn[$i] / $moneda['iVenta']);
            $usd = number_format((float)$usd,2, '.', '');
            //echo 'Monto U$D:'.$usd.'</br></br></br>';
            $totalusd = $totalusd + $usd;
            
            $i++;
           // }
        }
        //echo 'total U$D:'.$totalusd.'</br></br></br>';
        return $totalusd;
    }
    
    /**
     * Insertar Estado de Inversión 
     *  
     * @param array $val_arr
     */
    
    function put_array_inversion($val_arr = array()) {
        
        $hoy = getdate();
        $container = 'container.pacc_estado_inv';
        //var_dump($hoy);
        
        $new_filename = $hoy['year'] . '-';
        if ($hoy['mon'] < 10)
            $new_filename = $new_filename . '0' . $hoy['mon'] . '-';
        else
            $new_filename = $new_filename . $hoy['mon'] . '-';

        if ($hoy['mday'] < 10)
            $new_filename = $new_filename . '0' . $hoy['mday'] . '-';
        else
            $new_filename = $new_filename . $hoy['mday'] . '-';

        if ($hoy['hours'] < 10)
            $new_filename = $new_filename . '0' . $hoy['hours'] . '-';
        else
            $new_filename = $new_filename . $hoy['hours'] . '-';

        if ($hoy['minutes'] < 10)
            $new_filename = $new_filename . '0' . $hoy['minutes'] . '-';
        else
            $new_filename = $new_filename . $hoy['minutes'] . '-';

        if ($hoy['seconds'] < 10)
            $new_filename = $new_filename . '0' . $hoy['seconds'] . '-PACC-EstadoInversion-';
        else
            $new_filename = $new_filename . $hoy['seconds'] . '-PACC-EstadoInversion-';
        
        $new_filename = $new_filename . rand(1000, 5000) .'.xls';
        
        $val_arr['filename'] = $new_filename;
        $thisArr = array();
        $result = $this->db_pacc->insert($container,$val_arr);
        return $result;
        
        
    }
    
    /**
     * Lista todos los reportes de estado de inversión cargados
     *  
     * @return array $rtn
     */
    
    function lista_cargados(){
        $rtn = array();
        $container = 'container.pacc_estado_inv';
        $fields = array('filename','borrado');
        $query = array();
        
        
        $result = $this->db_pacc->get($container)->result_array();
        
        $i =0;
        foreach ($result as $list) {
            if($list['borrado'] == 0){
            $rtn[$i] = $list['filename'];
            $i++;
            }
        }
        //var_dump($rtn);
        return $rtn;
    }
    
    /**
     * Cuenta los reportes de Estado de inversión generados
     * 
     * @return int $result
     */
    
    function count_cargados(){
        $rtn = array();
        $container = 'container.pacc_estado_inv';
        $fields = array('filename');
        $query = array();
        
        $result = $this->db_pacc->count_all($container);
       
        return $result;
     
    }
    
    /**
     * Borra logicamente los reportes de Estado de Inversión.
     * 
     * @param string $filename
     */
    
    function borrar_db($filename){
        $container = 'container.pacc_estado_inv';
        $data = array(
            'borrado' => 1
            );
        $query= array('filename'=>$filename);        
        
        $this->db_pacc->where($query);
        $this->db_pacc->update($container,$data);
        
    }
    
    
    
    }


?>
