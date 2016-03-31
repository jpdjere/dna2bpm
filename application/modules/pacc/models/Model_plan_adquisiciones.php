<?php

/**
 * Funciones para el manejo de datos del Plan de adquisiciones.
 * 
 * @author MAGonzalez <mglongchamps@gmail.com>
 * @date 14/09/2015
 * 
 */


class Model_plan_adquisiciones extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
       
        
        $this->load->library('cimongo/cimongo', '', 'db_pacc');
        $this->db_pacc->switch_db('pacc');
               
    }
    
    
    //// Sección Planes ////
    
    
    /**
     * Lista todos los planes cargados
     * 
     * @return array $result
     */
    
    function lista_cargados(){ //Lista todos los planes cargados
        /*
         * Devuelve array con la info de todos los planes cargados
        */
        
        $rtn = array();
        $container = 'container.pacc_plan_adquisicion';
        //$fields = array('filename','borrado');
        //$query = array();
        $query = array('borrado'=>0);
        $this->db_pacc->where($query);
        $result = $this->db_pacc->get($container)->result_array();
        //var_dump($result);
        return $result; 
        
    }
    
    /**
     * Cuenta la cantidad de planes cargados
     * 
     * @return int $result
     */
    
    
    function count_cargados(){ //Cuenta la cantidad de planes cargados.
        $rtn = array();
        $container = 'container.pacc_plan_adquisicion';
        //$fields = array('filename');
        //$query = array();
        
        $result = $this->db_pacc->count_all($container);
       
        return $result;
     
    }   
    
    /**
     * Destalle de plan
     * 
     * @param MongoID $id_plan
     * @return array $result
     */
    
    function detalle_plan_adquisiciones($id_plan){ //Destalle de plan -- se le pasa $id_plan
        
        /*
         * Devuelve array con los campos del plan:
         * 
         */
       
        $result = array();
        $container = 'container.pacc_plan_adquisicion';
        
        if($id_plan != ''){
        $mongoID=new MongoID($id_plan);
        $query= array('_id'=>$mongoID);
        
        $this->db_pacc->where($query);
        $result = $this->db_pacc->get($container)->result_array();
        }
        /*$i=0;
        foreach ($result_mongo as $list) {
            $result[$i] = $list;
            $i++;
            
        }*/
        return $result;
    }
    
    /**
     * Borra plan de adquisiciones logicamente
     * 
     * @param MongoID $id_plan
     */
    
    function borrar_plan_adquisiciones_db($id_plan){ //Borra plan -- se le pasa el $id_plan
        $container = 'container.pacc_plan_adquisicion';
        $data = array(
            'borrado' => 1
            );
        $mongoID=new MongoID($id_plan);
        $query= array('_id'=>$mongoID);        
        
        $this->db_pacc->where($query);
        $this->db_pacc->update($container,$data);
        
    }
    
    /**
     * Edita el plan de adquisiciones
     * 
     * @param MongoID $id_plan
     * @param array $val_arr{
     *      @var string $val_arr['FECHA_ALTA_CONTRATO']
     *      @var string $val_arr['CATEGORIA_PROCESO']
     *      @var string $val_arr['RUBRO']
     *      @var string $val_arr['METODO_ADQUISICION']
     *      @var string $val_arr['DESCRIPCION']
     *      @var string $val_arr['MONEDA']
     *      @var string $val_arr['COTIZACION']
     *      @var string $val_arr['COSTO_EST_PESOS']
     *      @var string $val_arr['REVISION']
     *      @var string $val_arr['RESPONSABLE']
     *      @var string $val_arr['PARI_PASSU']
     *      @var string $val_arr['FIN_BID']
     *      @var string $val_arr['FIN_LOC']
     *      @var string $val_arr['PUBLIC_AEA']
     *      @var string $val_arr['FIN_CONT']
     *      @var string $val_arr['CANT_D_FIRMA']
     *      @var string $val_arr['PORCENTAJE_PAGO']
     * 
     * }
     */
    
    function edit_plan_adquisiciones($id_plan, $val_arr = array()) { // Edita el plan -- Recibe el $id_plan y $val_array
        
        /* El $val_arr tiene la siguiente forma:
        $val_arr['FECHA_ALTA_CONTRATO']
        $val_arr['CATEGORIA_PROCESO']
        $val_arr['RUBRO']
        $val_arr['METODO_ADQUISICION']
        $val_arr['DESCRIPCION']
        $val_arr['MONEDA']
        $val_arr['COTIZACION']
        $val_arr['COSTO_EST_PESOS']
        $val_arr['REVISION']
        $val_arr['RESPONSABLE']
        $val_arr['PARI_PASSU']
        $val_arr['FIN_BID']
        $val_arr['FIN_LOC']
        $val_arr['PUBLIC_AEA']
        $val_arr['FIN_CONT']
        $val_arr['CANT_D_FIRMA']
        $val_arr['PORCENTAJE_PAGO']
         *
         * 
         *          */
        
        $container = 'container.pacc_plan_adquisicion';
       
        $mongoID=new MongoID($id_plan);
        $query= array('_id'=>$mongoID); 
        
        $thisArr = array();
        
        $this->db_pacc->where($query);
        $this->db_pacc->update($container, $val_arr); 
        
        //return $result;
               
    }
    
    /**
     * inserta nuevo plan de adquisiciones
     * 
     * @param array $val_arr{
     *      @var string $val_arr['FECHA_ALTA_CONTRATO']
     *      @var string $val_arr['CATEGORIA_PROCESO']
     *      @var string $val_arr['RUBRO']
     *      @var string $val_arr['METODO_ADQUISICION']
     *      @var string $val_arr['DESCRIPCION']
     *      @var string $val_arr['MONEDA']
     *      @var string $val_arr['COTIZACION']
     *      @var string $val_arr['COSTO_EST_PESOS']
     *      @var string $val_arr['REVISION']
     *      @var string $val_arr['RESPONSABLE']
     *      @var string $val_arr['PARI_PASSU']
     *      @var string $val_arr['FIN_BID']
     *      @var string $val_arr['FIN_LOC']
     *      @var string $val_arr['PUBLIC_AEA']
     *      @var string $val_arr['FIN_CONT']
     *      @var string $val_arr['CANT_D_FIRMA']
     *      @var string $val_arr['PORCENTAJE_PAGO']
     * } 
     */
    
    function insert_plan_adquisiciones($val_arr = array()) { //inserta nuevo plan -Se le pasa el nuevo array 
        
        
        /*

         El $val_arr tiene la siguiente forma:
        $val_arr['FECHA_ALTA_CONTRATO']
        $val_arr['CATEGORIA_PROCESO']
        $val_arr['RUBRO']
        $val_arr['METODO_ADQUISICION']
        $val_arr['DESCRIPCION']
        $val_arr['MONEDA']
        $val_arr['COTIZACION']
        $val_arr['COSTO_EST_PESOS']
        $val_arr['REVISION']
        $val_arr['RESPONSABLE']
        $val_arr['PARI_PASSU']
        $val_arr['FIN_BID']
        $val_arr['FIN_LOC']
        $val_arr['PUBLIC_AEA']
        $val_arr['FIN_CONT']
        $val_arr['CANT_D_FIRMA']
        $val_arr['PORCENTAJE_PAGO']       */
        
        $container = 'container.pacc_plan_adquisicion';
        $hoy = getdate();

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
            $new_filename = $new_filename . '0' . $hoy['seconds'] ;
        else
            $new_filename = $new_filename . $hoy['seconds'] ;
        
        $new_filename = $new_filename ."-".rand(1000, 5000);
        
        $val_arr['REAL_PUBLIC_AEA'] = '';
        $val_arr['REAL_FIN_CONT'] ='';
        $val_arr['pagos'] = array();       
        
        $val_arr['filename'] = $new_filename;
        $val_arr['date'] = $hoy;
        
        $val_arr['borrado'] = 0;
        
        
        //$result = 
        $this->db_pacc->insert($container,$val_arr);
        //return $result;
        
        
    }
    
    //// Sección Fechas Reales ////
    
    
    /**
     * Devuelve fechas reales de un plan de adquisición
     * 
     * @param MongoID $id_plan
     * @return array $result
     */
    
    
    function detalle_fechas_reales($id_plan){ //Devuelve fechas reales - se le pasa el $id_plan
                
        $result = array();
        $container = 'container.pacc_plan_adquisicion';
                
        $mongoID=new MongoID($id_plan);
        $query= array('_id'=>$mongoID);
        $this->db_pacc->select(array('REAL_FIN_CONT','REAL_PUBLIC_AEA'));
        $this->db_pacc->where($query);
        
        $result = $this->db_pacc->get($container)->result_array();
        return $result;
     
    }
    
    /**
     * Editar fechas reales
     * 
     * @param MongoID $id_plan
     * @param array $val_arr{
     *      @var string $val_arr['REAL_PUBLIC_AEA']
     *      @var string $val_arr['REAL_FIN_CONT']
     * }
     */
    
    function edit_array_fecha_real($id_plan, $val_arr = array()) { // Editar fechas reales - se le pasa el $id_plan
        
        /*El array tiene la forma:
         * $val_arr = array();
         * $val_arr['REAL_PUBLIC_AEA']
         * $val_arr['REAL_FIN_CONT']
         *          */
        $container = 'container.pacc_plan_adquisicion';
        $mongoID=new MongoID($id_plan);
        $query= array('_id'=>$mongoID);
        $thisArr = array();
        //var_dump($val_arr);
        //var_dump($id_plan);
        $this->db_pacc->where($query);
        //$result = 
        $this->db_pacc->update($container, $val_arr); 
        //return $result;
                
    }
    
    //// Sección Pagos ///
    
    /**
     * 
     * @param MongoID $id_plan
     * @return array $result{
     *      @var string $result['PORCENTAJE']
     *      @var string $result['MONTO']
     *      @var string $result['DIAS']
     *      @var string $result['FECHA_DE_PAGO']
     *      @var int $result['id_pago']
     *      @var int $result['borrado']
     * }
     */
    
    
    
    function detalle_pagos($id_plan){ //Lista los pagos de un Plan - Se le pasa el $id_plan
        
        /* 
         * Devuelve array de array, cada uno:
         *'PORCENTAJE' => string
          'MONTO' => string
          'DIAS' => string
          'FECHA_DE_PAGO' => string
          'id_pago' => int
          'borrado' => int

         *          
         */
                
        $result = array();
        $container = 'container.pacc_plan_adquisicion';
                
        $mongoID=new MongoID($id_plan);
        $query= array('_id'=>$mongoID);
       
        
        $this->db_pacc->select(array('pagos'));
        $this->db_pacc->where($query);
        
        $result = $this->db_pacc->get($container)->result_array();
        return $result;
     
    }
    
    /**
     * Agrega nuevo pago a un Plan
     * 
     * @param MongoID $id_plan
     * @param array $pago{
     *      @var string $pago['PORCENTAJE']
     *      @var string $pago['MONTO']
     *      @var string $pago['DIAS']
     *      @var string $pago['FECHA_DE_PAGO']
     * }
     * @return type
     */
    
    function cargar_pagos($id_plan, $pago = array()){ // Agrega nuevo pago a un Plan - Se le pasa $id_plan y el array $pago
        
        /*Array pagos:
         * PORCENTAJE
         * MONTO
         * DIAS
         * FECHA_DE_PAGO        
        */        
        $result = array();
        $val_array = array();
        $aux = array();
        $pagos_cargados = array();
        $container = 'container.pacc_plan_adquisicion';
                
        $mongoID=new MongoID($id_plan);
        $query= array('_id'=>$mongoID);
        $this->db_pacc->where($query);
        $pagos_cargados = $this->db_pacc->get($container)->result_array();
        //var_dump($pagos_cargados);
        $aux = $pagos_cargados[0]['pagos'];
        $count = count($aux);
        
        //var_dump($query);
        
        
        if($count == NULL){
            $count = 0;
        }
        $pago['id_pago'] = $count;
        $pago['borrado'] =0;
        $aux[$count] = $pago;
        
        $val_array['pagos'] = $aux;
        $this->db_pacc->where($query);
        $result = $this->db_pacc->update($container,$val_array);
        return $result;
     
    }
    
    /**
     * Edita pago a un Plan
     * 
     * @param MongoID $id_plan
     * @param int $id_pago
     * @param array $pago{
     *      @var string $pago['PORCENTAJE']
     *      @var string $pago['MONTO']
     *      @var string $pago['DIAS']
     *      @var string $pago['FECHA_DE_PAGO']
     *}    
     */
    
    function editar_pagos($id_plan, $id_pago, $pago = array()){ // Edita pago a un Plan - Se le pasa $id_plan, $id_pago y el array $pago
        
        /*Array pagos:
         * PORCENTAJE
         * MONTO
         * DIAS
         * FECHA_DE_PAGO        
        */        
        $result = array();
        $val_array = array();
        $aux = array();
        $pagos_cargados = array();
        $container = 'container.pacc_plan_adquisicion';
                
        $mongoID=new MongoID($id_plan);
        $query= array('_id'=>$mongoID);
        $this->db_pacc->where($query);
        $pagos_cargados = $this->db_pacc->get($container)->result_array();
        //var_dump($pagos_cargados);
        $aux = $pagos_cargados[0]['pagos'];
        
        $pago['id_pago'] = $id_pago;
        $pago['borrado'] =0;
        $aux[$id_pago] = $pago;
        
        $val_array['pagos'] = $aux;
        $this->db_pacc->where($query);
        $result = $this->db_pacc->update($container,$val_array);
        return $result;
     
    }
    
    /**
     * Borra Pagos cargados
     * 
     * @param MongoID $id_plan
     * @param int $id_pago
     */
    
    function borrar_pagos($id_plan, $id_pago){ //Borra Pagos cargados - Hay que pasarle el $id_plan y $id_pago
                
        $container = 'container.pacc_plan_adquisicion';
                
        $mongoID=new MongoID($id_plan);
        $query= array('_id'=>$mongoID);
        $this->db_pacc->where($query);
        $pagos_cargados = $this->db_pacc->get($container)->result_array();
        //var_dump($pagos_cargados);
        $aux = $pagos_cargados[0]['pagos'];
        
        $aux[$id_pago]['borrado'] =1;
        
        $val_array['pagos'] = $aux;
        //var_dump($val_array);
        $this->db_pacc->where($query);
        $result = $this->db_pacc->update($container,$val_array);
        return $result;
     
    }
    
    /**
     * Sustituye parametros especiales y acentos de un string
     * 
     * @param string $String
     * @return string $String
     */ 

    function limpiar($String) {

        $String = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", $String);
        $String = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "A", $String);
        $String = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "I", $String);
        $String = str_replace(array('í', 'ì', 'î', 'ï'), "i", $String);
        $String = str_replace(array('é', 'è', 'ê', 'ë'), "e", $String);
        $String = str_replace(array('É', 'È', 'Ê', 'Ë'), "E", $String);
        $String = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $String);
        $String = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "O", $String);
        $String = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $String);
        $String = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "U", $String);
        $String = str_replace(array('[', '^', '´', '`', '¨', '~', ']'), "", $String);
        $String = str_replace("ç", "c", $String);
        $String = str_replace("Ç", "C", $String);
        $String = str_replace("ñ", "n", $String);
        $String = str_replace("Ñ", "N", $String);
        $String = str_replace("Ý", "Y", $String);
        $String = str_replace("ý", "y", $String);

        $String = str_replace("&aacute;", "a", $String);
        $String = str_replace("&Aacute;", "A", $String);
        $String = str_replace("&eacute;", "e", $String);
        $String = str_replace("&Eacute;", "E", $String);
        $String = str_replace("&iacute;", "i", $String);
        $String = str_replace("&Iacute;", "I", $String);
        $String = str_replace("&oacute;", "o", $String);
        $String = str_replace("&Oacute;", "O", $String);
        $String = str_replace("&uacute;", "u", $String);
        $String = str_replace("&Uacute;", "U", $String);
        return $String;
    }
  
}





