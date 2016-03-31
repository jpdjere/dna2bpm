<?php

/**
 * Funciones para el manejo de datos del POA.
 * 
 * @author MAGonzalez <mglongchamps@gmail.com>
 * @date 14/09/2015
 * 
 */

class Model_POA_list extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        
        
        $this->load->library('cimongo/cimongo', '', 'db_pacc');
        $this->db_pacc->switch_db('pacc');
               
    }
    
    /**
     * Función que lista todos los POA cargados en la base de datos.
     * 
     * @return array $result
     */
    
    function lista_cargados_poa(){ /// Devuelve lista de POA cargados en el sistema
        $result = array();
        $container = 'container.pacc_POA';
        
        $result = $this->db_pacc->get($container)->result_array();
        
        return $result; 
        
    }
    
    /**
     * Devuelve array con toda la info de cada POA
     * 
     * @param MongoID $id_poa
     * @return array $result
     */
    
    function detalle_poa($id_poa){ // Devuelve array con toda la info de cada POA - Se le pasa $id_poa
        
        $result = array();
        $container = 'container.pacc_POA';
        $mongoID=new MongoID($id_poa);
         
        $query = array('_id'=>$mongoID);
    
        $this->db_pacc->where($query);
        $result = $this->db_pacc->get($container)->result_array();
               
        return $result;
    }
     
    /**
     * Devuelve la cantidad de POA cargados
     * 
     * @return int $result
     */
    
    function count_cargados(){ // Devuelve la cantidad de POA cargados
        $rtn = array();
        $container = 'container.pacc_POA';
        $fields = array('filename');
        $query = array();
        
        $result = $this->db_pacc->count_all($container);
       
        return $result;
     
    }
    
    /**
     * Borra logicamente un POA
     * 
     * @param MongoID $id_poa
     */
    
    function borrar_poa_db($id_poa){ // Borra logicamente un POA -- Se le pasa $id_poa
        $container = 'container.pacc_POA';
        $data = array(
            'borrado' => 1
            );
        $mongoID=new MongoID($id_poa);
        $query = array('_id'=>$mongoID);       
        
        $this->db_pacc->where($query);
        $this->db_pacc->update($container,$data);
        
    }
    
    /**
     * Guarda en la base el nuevo POA
     * 
     * @param array $array - El Array de arrays de columnas, a partir de la fila 6
     * @param int $lines - La cantidad de lineas a cargar
     * @param int $PESO_TI_BID - La valuación del dolar según BID
     * @param int $PESO_TI_BNA - La valuación del dolar según BNA 
     * @return $result
     */
    
    
    
    function put_array_POA($array = array(), $lines, $PESO_TI_BID, $PESO_TI_BNA) { // Guarda en la base el nuevo POA -- Se le pasa:
        
        
        //El Array de arrays de columnas, a partir de la fila 6 con el formato de $Arr_name.
        //La cantidad de $lineas a cargar
        //La valuación del dolar según BID
        //La valuación del dolar según BNA 
        ////////////////
        $filename_ext = ($this->anexo == '09') ? ".pdf" : ".xls";

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
            $new_filename = $new_filename . '0' . $hoy['seconds'] . '-PACC-POA-';
        else
            $new_filename = $new_filename . $hoy['seconds'] . '-PACC-POA-';
        
        $new_filename = $new_filename . rand(1000, 5000) .'.xls';
        
        $Arr_name = array(
            "AREA",
            "SCOMP",
            "COMP",
            "CODIGO",
            "DESCRIP",
            "CONTRATADO",
            "IP_UNIDAD",
            "IP_TI",
            "IP_TII",
            "IP_TIII",
            "IP_TIV",
            "IP_TOTAL",
            "COSTO_UNI",
            "COSTO_UNI_USD",//nuevo
            "Inciso_ONP",
            "FUENTE_22",
            "FUENTE_11",
            "FUENTE_PYME",
            "PESO_TI_BID",
            "PESO_TI_BNA",
            "PESO_TI_PYME",
            "PESO_TII_BID",
            "PESO_TII_BNA",
            "PESO_TII_PYME",
            "PESO_TIII_BID",
            "PESO_TIII_BNA",
            "PESO_TIII_PYME",
            "PESO_TIV_BID",
            "PESO_TIV_BNA",
            "PESO_TIV_PYME",
            "PESO_TOTFUE_BID",
            "PESO_TOTFUE_BNA",
            "PESO_TOTFUE_PYME",
            "PESO_TOTAL",
            "USD_TI_BID",
            "USD_TI_BNA",
            "USD_TI_PYME",
            "USD_TII_BID",
            "USD_TII_BNA",
            "USD_TII_PYME",
            "USD_TIII_BID",
            "USD_TIII_BNA",
            "USD_TIII_PYME",
            "USD_TIV_BID",
            "USD_TIV_BNA",
            "USD_TIV_PYME",
            "USD_TOTFUE_BID",
            "USD_TOTFUE_BNA",
            "USD_TOTFUE_PYME",
            "USD_TOTAL",
            
            //NUEVOS
            "IP_TI_REAL",//nuevo
            "IP_TII_REAL",//nuevo
            "IP_TIII_REAL",//nuevo
            "IP_TIV_REAL",//nuevo
            "IP_TOTAL_REAL",//nuevo
            "PESO_TI_BID_REAL",//nuevo
            "PESO_TI_BNA_REAL",//nuevo
            "PESO_TI_PYME_REAL",//nuevo
            "PESO_TII_BID_REAL",//nuevo
            "PESO_TII_BNA_REAL",//nuevo
            "PESO_TII_PYME_REAL",//nuevo
            "PESO_TIII_BID_REAL",//nuevo
            "PESO_TIII_BNA_REAL",//nuevo
            "PESO_TIII_PYME_REAL",//nuevo
            "PESO_TIV_BID_REAL",//nuevo
            "PESO_TIV_BNA_REAL",//nuevo
            "PESO_TIV_PYME_REAL",//nuevo
            "PESO_TOTFUE_BID_REAL",//nuevo
            "PESO_TOTFUE_BNA_REAL",//nuevo
            "PESO_TOTFUE_PYME_REAL",//nuevo
            "PESO_TOTAL_REAL"//nuevo

        );
   
        $container_POA = 'container.pacc_POA';
        $id = 1;
        $headerArr['filename'] = $new_filename;
        $headerArr['date'] = $hoy;
        $index_l = (0);
        $index_c = (0);
        
        $headerArr[1] = array(
            //"COMP_SCOMP" => "1",
            "AREA" => "",
            "SCOMP" => "",
            "COMP" => "",
            "CODIGO" => "",
            "DESCRIP" => "",
            "CONTRATADO" => "",
            "IP_UNIDAD" => "",
            "IP_TI" => "",
            "IP_TII" => "",
            "IP_TIII" => "",
            "IP_TIV" => "",
            "IP_TOTAL" => "",
            "COSTO_UNI" => "",
            "COSTO_UNI_USD"=> "",//nuevo
            "Inciso_ONP" => "Cotización planificación",
            "FUENTE_22" => "",
            "FUENTE_11" => "",
            "FUENTE_PYME" => "",
            "PESO_TI_BID" => $PESO_TI_BID,
            "PESO_TI_BNA" => $PESO_TI_BNA,
            "PESO_TI_PYME" => "",
            "PESO_TII_BID" => "",
            "PESO_TII_BNA" => "",
            "PESO_TII_PYME" => "",
            "PESO_TIII_BID" => "",
            "PESO_TIII_BNA" => "",
            "PESO_TIII_PYME" => "",
            "PESO_TIV_BID" => "",
            "PESO_TIV_BNA" => "",
            "PESO_TIV_PYME" => "",
            "PESO_TOTFUE_BID" => "",
            "PESO_TOTFUE_BNA" => "",
            "PESO_TOTFUE_PYME" => "",
            "PESO_TOTAL" => "",
            "USD_TI_BID" => "",
            "USD_TI_BNA" => "",
            "USD_TI_PYME" => "",
            "USD_TII_BID" => "",
            "USD_TII_BNA" => "",
            "USD_TII_PYME" => "",
            "USD_TIII_BID" => "",
            "USD_TIII_BNA" => "",
            "USD_TIII_PYME" => "",
            "USD_TIV_BID" => "",
            "USD_TIV_BNA" => "",
            "USD_TIV_PYME" => "",
            "USD_TOTFUE_BID" => "",
            "USD_TOTFUE_BNA" => "",
            "USD_TOTFUE_PYME" => "",
            "USD_TOTAL" => "",
            
            //NUEVOS
            "IP_TI_REAL" => "",//nuevo
            "IP_TII_REAL" => "",//nuevo
            "IP_TIII_REAL" => "",//nuevo
            "IP_TIV_REAL" => "",//nuevo
            "IP_TOTAL_REAL" => "",//nuevo
            "PESO_TI_BID_REAL" => "",//nuevo
            "PESO_TI_BNA_REAL" => "",//nuevo
            "PESO_TI_PYME_REAL" => "",//nuevo
            "PESO_TII_BID_REAL" => "",//nuevo
            "PESO_TII_BNA_REAL" => "",//nuevo
            "PESO_TII_PYME_REAL" => "",//nuevo
            "PESO_TIII_BID_REAL" => "",//nuevo
            "PESO_TIII_BNA_REAL" => "",//nuevo
            "PESO_TIII_PYME_REAL" => "",//nuevo
            "PESO_TIV_BID_REAL" => "",//nuevo
            "PESO_TIV_BNA_REAL" => "",//nuevo
            "PESO_TIV_PYME_REAL" => "",//nuevo
            "PESO_TOTFUE_BID_REAL" => "",//nuevo
            "PESO_TOTFUE_BNA_REAL" => "",//nuevo
            "PESO_TOTFUE_PYME_REAL" => "",//nuevo
            "PESO_TOTAL_REAL" => ""//nuevo
        );
    
        $headerArr[2] = array(        
        //"COMP_SCOMP" => "COMP / SUBCOMP",
        "AREA" => "ÁREA RESPONSABLE",
        "SCOMP" => "PRODUCTOS (BIEN, SERVICIO, PROYECTO O NORMA)",
        "COMP" => "",
        "CODIGO" => "",
        "DESCRIP" => "",
        "CONTRATADO" => "",
        "IP_UNIDAD" => "",
        "IP_TI" => "",
        "IP_TII" => "",
        "IP_TIII" => "",
        "IP_TIV" => "",
        "IP_TOTAL" => "",
        "COSTO_UNI" => "",
        "COSTO_UNI_USD"=> "",//nuevo
        "Inciso_ONP" => "",
        "FUENTE_22" => "",
        "FUENTE_11" => "",
        "FUENTE_PYME" => "",
        "PESO_TI_BID" => "INDICADOR PRESUPUESTARIO",
        "PESO_TI_BNA" => "",
        "PESO_TI_PYME" => "",
        "PESO_TII_BID" => "",
        "PESO_TII_BNA" => "",
        "PESO_TII_PYME" => "",
        "PESO_TIII_BID" => "",
        "PESO_TIII_BNA" => "",
        "PESO_TIII_PYME" => "",
        "PESO_TIV_BID" => "",
        "PESO_TIV_BNA" => "",
        "PESO_TIV_PYME" => "",
        "PESO_TOTFUE_BID" => "",
        "PESO_TOTFUE_BNA" => "",
        "PESO_TOTFUE_PYME" => "",
        "PESO_TOTAL" => "",
        "USD_TI_BID" => "INDICADOR PRESUPUESTARIO",
        "USD_TI_BNA" => "",
        "USD_TI_PYME" => "",
        "USD_TII_BID" => "",
        "USD_TII_BNA" => "",
        "USD_TII_PYME" => "",
        "USD_TIII_BID" => "",
        "USD_TIII_BNA" => "",
        "USD_TIII_PYME" => "",
        "USD_TIV_BID" => "",
        "USD_TIV_BNA" => "",
        "USD_TIV_PYME" => "",
        "USD_TOTFUE_BID" => "",
        "USD_TOTFUE_BNA" => "",
        "USD_TOTFUE_PYME" => "",
        "USD_TOTAL" => "",
        
        //NUEVOS
        "IP_TI_REAL" => "",//nuevo
        "IP_TII_REAL" => "",//nuevo
        "IP_TIII_REAL" => "",//nuevo
        "IP_TIV_REAL" => "",//nuevo
        "IP_TOTAL_REAL" => "",//nuevo
        "PESO_TI_BID_REAL" => "",//nuevo
        "PESO_TI_BNA_REAL" => "",//nuevo
        "PESO_TI_PYME_REAL" => "",//nuevo
        "PESO_TII_BID_REAL" => "",//nuevo
        "PESO_TII_BNA_REAL" => "",//nuevo
        "PESO_TII_PYME_REAL" => "",//nuevo
        "PESO_TIII_BID_REAL" => "",//nuevo
        "PESO_TIII_BNA_REAL" => "",//nuevo
        "PESO_TIII_PYME_REAL" => "",//nuevo
        "PESO_TIV_BID_REAL" => "",//nuevo
        "PESO_TIV_BNA_REAL" => "",//nuevo
        "PESO_TIV_PYME_REAL" => "",//nuevo
        "PESO_TOTFUE_BID_REAL" => "",//nuevo
        "PESO_TOTFUE_BNA_REAL" => "",//nuevo
        "PESO_TOTFUE_PYME_REAL" => "",//nuevo
        "PESO_TOTAL_REAL" => ""//nuevo    
    );
        
    $headerArr[3] = array(    
        //"COMP_SCOMP" => "",
        "AREA" => "",
        "SCOMP" => "SCOMP",
        "COMP" => "COMP",
        "CODIGO" => "CÓDIGO",
        "DESCRIP" => "DESCRIPCIÓN",
        "CONTRATADO" => "CONTRATADO",
        "IP_UNIDAD" => "INDICADOR PRODUCTO",
        "IP_TI" => "",
        "IP_TII" => "",
        "IP_TIII" => "",
        "IP_TIV" => "",
        "IP_TOTAL" => "",
        "COSTO_UNI" => "COSTO UNITARIO",
        "COSTO_UNI_USD"=> "COSTO UNITARIO USD",//nuevo
        "Inciso_ONP" => "Inciso ONP",
        "FUENTE_22" => "FUENTE",
        "FUENTE_11" => "",
        "FUENTE_PYME" => "",
        "PESO_TI_BID" => "T I ($)",
        "PESO_TI_BNA" => "",
        "PESO_TI_PYME" => "",
        "PESO_TII_BID" => "T II ($)",
        "PESO_TII_BNA" => "",
        "PESO_TII_PYME" => "",
        "PESO_TIII_BID" => "T III ($)",
        "PESO_TIII_BNA" => "",
        "PESO_TIII_PYME" => "",
        "PESO_TIV_BID" => "T IV ($)",
        "PESO_TIV_BNA" => "",
        "PESO_TIV_PYME" => "",
        "PESO_TOTFUE_BID" => "TOTAL POR FUENTE",
        "PESO_TOTFUE_BNA" => "",
        "PESO_TOTFUE_PYME" => "",
        "PESO_TOTAL" => "TOTAL (S)",
        "USD_TI_BID" => "T I (USS)",
        "USD_TI_BNA" => "",
        "USD_TI_PYME" => "",
        "USD_TII_BID" => "T II (USS)",
        "USD_TII_BNA" => "",
        "USD_TII_PYME" => "",
        "USD_TIII_BID" => "T III (USS)",
        "USD_TIII_BNA" => "",
        "USD_TIII_PYME" => "",
        "USD_TIV_BID" => "T IV (USS)",
        "USD_TIV_BNA" => "",
        "USD_TIV_PYME" => "",
        "USD_TOTFUE_BID" => "TOTAL POR FUENTE (USS)",
        "USD_TOTFUE_BNA" => "",
        "USD_TOTFUE_PYME" => "",
        "USD_TOTAL" => 'TOTAL (USS)',
        //NUEVOS
        "IP_TI_REAL" => "",//nuevo
        "IP_TII_REAL" => "",//nuevo
        "IP_TIII_REAL" => "",//nuevo
        "IP_TIV_REAL" => "",//nuevo
        "IP_TOTAL_REAL" => "",//nuevo
        "PESO_TI_BID_REAL" => "",//nuevo
        "PESO_TI_BNA_REAL" => "",//nuevo
        "PESO_TI_PYME_REAL" => "",//nuevo
        "PESO_TII_BID_REAL" => "",//nuevo
        "PESO_TII_BNA_REAL" => "",//nuevo
        "PESO_TII_PYME_REAL" => "",//nuevo
        "PESO_TIII_BID_REAL" => "",//nuevo
        "PESO_TIII_BNA_REAL" => "",//nuevo
        "PESO_TIII_PYME_REAL" => "",//nuevo
        "PESO_TIV_BID_REAL" => "",//nuevo
        "PESO_TIV_BNA_REAL" => "",//nuevo
        "PESO_TIV_PYME_REAL" => "",//nuevo
        "PESO_TOTFUE_BID_REAL" => "",//nuevo
        "PESO_TOTFUE_BNA_REAL" => "",//nuevo
        "PESO_TOTFUE_PYME_REAL" => "",//nuevo
        "PESO_TOTAL_REAL" => ""//nuevo 
       );
    
    $headerArr[4] = array(
        //"COMP_SCOMP" => "",
        "AREA" => "",
        "SCOMP" => "",
        "COMP" => "",
        "CODIGO" => "",
        "DESCRIP" => "",
        "CONTRATADO" => "",
        "IP_UNIDAD" => "UNIDAD MEDIDA",
        "IP_TI" => "T I",
        "IP_TII" => "T II",
        "IP_TIII" => "T III",
        "IP_TIV" => "T IV",
        "IP_TOTAL" => "TOTAL",
        "COSTO_UNI" => "",
        "COSTO_UNI_USD"=> "",//nuevo
        "Inciso_ONP" => "",
        "FUENTE_22" => "22",
        "FUENTE_11" => "11",
        "FUENTE_PYME" => "PYME",
        "PESO_TI_BID" => "BID",
        "PESO_TI_BNA" => "NACIÓN",
        "PESO_TI_PYME" => "APORTE PYME",
        "PESO_TII_BID" => "BID",
        "PESO_TII_BNA" => "NACIÓN",
        "PESO_TII_PYME" => "APORTE PYME",
        "PESO_TIII_BID" => "BID",
        "PESO_TIII_BNA" => "NACIÓN",
        "PESO_TIII_PYME" => "APORTE PYME",
        "PESO_TIV_BID" => "BID",
        "PESO_TIV_BNA" => "NACIÓN",
        "PESO_TIV_PYME" => "APORTE PYME",
        "PESO_TOTFUE_BID" => "BID",
        "PESO_TOTFUE_BNA" => "NACIÓN",
        "PESO_TOTFUE_PYME" => "APORTE PYME",
        "PESO_TOTAL" => "",
        "USD_TI_BID" => "BID",
        "USD_TI_BNA" => "NACIÓN",
        "USD_TI_PYME" => "APORTE PYME",
        "USD_TII_BID" => "BID",
        "USD_TII_BNA" => "NACIÓN",
        "USD_TII_PYME" => "APORTE PYME",
        "USD_TIII_BID" => "BID",
        "USD_TIII_BNA" => "NACIÓN",
        "USD_TIII_PYME" => "APORTE PYME",
        "USD_TIV_BID" => "BID",
        "USD_TIV_BNA" => "NACIÓN",
        "USD_TIV_PYME" => "APORTE PYME",
        "USD_TOTFUE_BID" => "BID",
        "USD_TOTFUE_BNA" => "NACIÓN",
        "USD_TOTFUE_PYME" => "APORTE PYME",
        "USD_TOTAL" => "",
        //NUEVOS
        "IP_TI_REAL" => "",//nuevo
        "IP_TII_REAL" => "",//nuevo
        "IP_TIII_REAL" => "",//nuevo
        "IP_TIV_REAL" => "",//nuevo
        "IP_TOTAL_REAL" => "",//nuevo
        "PESO_TI_BID_REAL" => "",//nuevo
        "PESO_TI_BNA_REAL" => "",//nuevo
        "PESO_TI_PYME_REAL" => "",//nuevo
        "PESO_TII_BID_REAL" => "",//nuevo
        "PESO_TII_BNA_REAL" => "",//nuevo
        "PESO_TII_PYME_REAL" => "",//nuevo
        "PESO_TIII_BID_REAL" => "",//nuevo
        "PESO_TIII_BNA_REAL" => "",//nuevo
        "PESO_TIII_PYME_REAL" => "",//nuevo
        "PESO_TIV_BID_REAL" => "",//nuevo
        "PESO_TIV_BNA_REAL" => "",//nuevo
        "PESO_TIV_PYME_REAL" => "",//nuevo
        "PESO_TOTFUE_BID_REAL" => "",//nuevo
        "PESO_TOTFUE_BNA_REAL" => "",//nuevo
        "PESO_TOTFUE_PYME_REAL" => "",//nuevo
        "PESO_TOTAL_REAL" => ""//nuevo 
        );
    
    $headerArr[5] = array(
        //"COMP_SCOMP" => "",
        "AREA" => "",
        "SCOMP" => "",
        "COMP" => "",
        "CODIGO" => "",
        "DESCRIP" => "",
        "CONTRATADO" => "",
        "IP_UNIDAD" => "",
        "IP_TI" => "ESTIM",
        "IP_TII" => "ESTIM",
        "IP_TIII" => "ESTIM",
        "IP_TIV" => "ESTIM",
        "IP_TOTAL" => "ESTIM",
        "COSTO_UNI" => "",
        "COSTO_UNI_USD"=> "",//nuevo
        "Inciso_ONP" => "",
        "FUENTE_22" => "",
        "FUENTE_11" => "",
        "FUENTE_PYME" => "",
        "PESO_TI_BID" => "ESTIMADO",
        "PESO_TI_BNA" => "ESTIMADO",
        "PESO_TI_PYME" => "ESTIMADO",
        "PESO_TII_BID" => "ESTIMADO",
        "PESO_TII_BNA" => "ESTIMADO",
        "PESO_TII_PYME" => "ESTIMADO",
        "PESO_TIII_BID" => "ESTIMADO",
        "PESO_TIII_BNA" => "ESTIMADO",
        "PESO_TIII_PYME" => "ESTIMADO",
        "PESO_TIV_BID" => "ESTIMADO",
        "PESO_TIV_BNA" => "ESTIMADO",
        "PESO_TIV_PYME" => "ESTIMADO",
        "PESO_TOTFUE_BID" => "",
        "PESO_TOTFUE_BNA" => "",
        "PESO_TOTFUE_PYME" => "",
        "PESO_TOTAL" => "",
        "USD_TI_BID" => "ESTIMADO",
        "USD_TI_BNA" => "ESTIMADO",
        "USD_TI_PYME" => "ESTIMADO",
        "USD_TII_BID" => "ESTIMADO",
        "USD_TII_BNA" => "ESTIMADO",
        "USD_TII_PYME" => "ESTIMADO",
        "USD_TIII_BID" => "",
        "USD_TIII_BNA" => "",
        "USD_TIII_PYME" => "",
        "USD_TIV_BID" => "",
        "USD_TIV_BNA" => "",
        "USD_TIV_PYME" => "",
        "USD_TOTFUE_BID" => "",
        "USD_TOTFUE_BNA" => "",
        "USD_TOTFUE_PYME" => "",
        "USD_TOTAL" => "",
        //NUEVOS
        "IP_TI_REAL" => "REAL",//nuevo
        "IP_TII_REAL" => "REAL",//nuevo
        "IP_TIII_REAL" => "REAL",//nuevo
        "IP_TIV_REAL" => "REAL",//nuevo
        "IP_TOTAL_REAL" => "REAL",//nuevo
        "PESO_TI_BID_REAL" => "REAL",//nuevo
        "PESO_TI_BNA_REAL" => "REAL",//nuevo
        "PESO_TI_PYME_REAL" => "REAL",//nuevo
        "PESO_TII_BID_REAL" => "REAL",//nuevo
        "PESO_TII_BNA_REAL" => "REAL",//nuevo
        "PESO_TII_PYME_REAL" => "REAL",//nuevo
        "PESO_TIII_BID_REAL" => "REAL",//nuevo
        "PESO_TIII_BNA_REAL" => "REAL",//nuevo
        "PESO_TIII_PYME_REAL" => "REAL",//nuevo
        "PESO_TIV_BID_REAL" => "REAL",//nuevo
        "PESO_TIV_BNA_REAL" => "REAL",//nuevo
        "PESO_TIV_PYME_REAL" => "REAL",//nuevo
        "PESO_TOTFUE_BID_REAL" => "REAL",//nuevo
        "PESO_TOTFUE_BNA_REAL" => "REAL",//nuevo
        "PESO_TOTFUE_PYME_REAL" => "REAL",//nuevo
        "PESO_TOTAL_REAL" => "REAL"//nuevo 
        );
        
        for ($index_l = 6; $index_l <= $lines; $index_l++) {
            $headerArr[$index_l]  = $array[$index_l - 6]; 
        }
        
        //var_dump($headerArr);
        $headerArr['PESO_TI_BID'] = $PESO_TI_BID;
        $headerArr['PESO_TI_BNA'] = $PESO_TI_BNA;
        $headerArr['borrado'] = 0;
        
        //var_dump($headerArr);
        //exit();
        
        ////////////////
        $thisArr = array();
        $result = $this->db_pacc->insert($container_POA,$headerArr);
        return $result;
        
        
    }
    /**
     * Guarda en la base el nuevo POA para carga desde archivo excell
     * 
     * @param string $new_filename - ÇNombre del archivo generado
     * @param array $array - El Array de arrays de columnas, a partir de la fila 6
     * @param int $lines - La cantidad de lineas a cargar
     * @param int $PESO_TI_BID - La valuación del dolar según BID
     * @param int $PESO_TI_BNA - La valuación del dolar según BNA 
     * @return $result
     */
    
    function put_array_POA_arch($new_filename, $array = array(), $lines, $PESO_TI_BID, $PESO_TI_BNA) { // Guarda en la base el nuevo POA -- Se le pasa:
        
        
        //El Array de arrays de columnas, a partir de la fila 6 con el formato de $Arr_name.
        //La cantidad de $lineas a cargar
        //La valuación del dolar según BID
        //La valuación del dolar según BNA 
        ////////////////
        $filename_ext = ($this->anexo == '09') ? ".pdf" : ".xls";
        
        /*
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
            $new_filename = $new_filename . '0' . $hoy['seconds'] . '-PACC-POA-';
        else
            $new_filename = $new_filename . $hoy['seconds'] . '-PACC-POA-';
        
        $new_filename = $new_filename . rand(1000, 5000) .'.xls';
        */
        $Arr_name = array(
            "AREA",
            "SCOMP",
            "COMP",
            "CODIGO",
            "DESCRIP",
            "CONTRATADO",
            "IP_UNIDAD",
            "IP_TI",
            "IP_TII",
            "IP_TIII",
            "IP_TIV",
            "IP_TOTAL",
            "COSTO_UNI",
            "COSTO_UNI_USD",//nuevo
            "Inciso_ONP",
            "FUENTE_22",
            "FUENTE_11",
            "FUENTE_PYME",
            "PESO_TI_BID",
            "PESO_TI_BNA",
            "PESO_TI_PYME",
            "PESO_TII_BID",
            "PESO_TII_BNA",
            "PESO_TII_PYME",
            "PESO_TIII_BID",
            "PESO_TIII_BNA",
            "PESO_TIII_PYME",
            "PESO_TIV_BID",
            "PESO_TIV_BNA",
            "PESO_TIV_PYME",
            "PESO_TOTFUE_BID",
            "PESO_TOTFUE_BNA",
            "PESO_TOTFUE_PYME",
            "PESO_TOTAL",
            "USD_TI_BID",
            "USD_TI_BNA",
            "USD_TI_PYME",
            "USD_TII_BID",
            "USD_TII_BNA",
            "USD_TII_PYME",
            "USD_TIII_BID",
            "USD_TIII_BNA",
            "USD_TIII_PYME",
            "USD_TIV_BID",
            "USD_TIV_BNA",
            "USD_TIV_PYME",
            "USD_TOTFUE_BID",
            "USD_TOTFUE_BNA",
            "USD_TOTFUE_PYME",
            "USD_TOTAL",
            
            //NUEVOS
            "IP_TI_REAL",//nuevo
            "IP_TII_REAL",//nuevo
            "IP_TIII_REAL",//nuevo
            "IP_TIV_REAL",//nuevo
            "IP_TOTAL_REAL",//nuevo
            "PESO_TI_BID_REAL",//nuevo
            "PESO_TI_BNA_REAL",//nuevo
            "PESO_TI_PYME_REAL",//nuevo
            "PESO_TII_BID_REAL",//nuevo
            "PESO_TII_BNA_REAL",//nuevo
            "PESO_TII_PYME_REAL",//nuevo
            "PESO_TIII_BID_REAL",//nuevo
            "PESO_TIII_BNA_REAL",//nuevo
            "PESO_TIII_PYME_REAL",//nuevo
            "PESO_TIV_BID_REAL",//nuevo
            "PESO_TIV_BNA_REAL",//nuevo
            "PESO_TIV_PYME_REAL",//nuevo
            "PESO_TOTFUE_BID_REAL",//nuevo
            "PESO_TOTFUE_BNA_REAL",//nuevo
            "PESO_TOTFUE_PYME_REAL",//nuevo
            "PESO_TOTAL_REAL"//nuevo

        );
   
        $container_POA = 'container.pacc_POA';
        $id = 1;
        $headerArr['filename'] = $new_filename;
        $headerArr['date'] = $array['date'];
        $index_l = (0);
        $index_c = (0);
        
        $headerArr[1] = array(
            //"COMP_SCOMP" => "1",
            "AREA" => "",
            "SCOMP" => "",
            "COMP" => "",
            "CODIGO" => "",
            "DESCRIP" => "",
            "CONTRATADO" => "",
            "IP_UNIDAD" => "",
            "IP_TI" => "",
            "IP_TII" => "",
            "IP_TIII" => "",
            "IP_TIV" => "",
            "IP_TOTAL" => "",
            "COSTO_UNI" => "",
            "COSTO_UNI_USD"=> "",//nuevo
            "Inciso_ONP" => "Cotización planificación",
            "FUENTE_22" => "",
            "FUENTE_11" => "",
            "FUENTE_PYME" => "",
            "PESO_TI_BID" => $PESO_TI_BID,
            "PESO_TI_BNA" => $PESO_TI_BNA,
            "PESO_TI_PYME" => "",
            "PESO_TII_BID" => "",
            "PESO_TII_BNA" => "",
            "PESO_TII_PYME" => "",
            "PESO_TIII_BID" => "",
            "PESO_TIII_BNA" => "",
            "PESO_TIII_PYME" => "",
            "PESO_TIV_BID" => "",
            "PESO_TIV_BNA" => "",
            "PESO_TIV_PYME" => "",
            "PESO_TOTFUE_BID" => "",
            "PESO_TOTFUE_BNA" => "",
            "PESO_TOTFUE_PYME" => "",
            "PESO_TOTAL" => "",
            "USD_TI_BID" => "",
            "USD_TI_BNA" => "",
            "USD_TI_PYME" => "",
            "USD_TII_BID" => "",
            "USD_TII_BNA" => "",
            "USD_TII_PYME" => "",
            "USD_TIII_BID" => "",
            "USD_TIII_BNA" => "",
            "USD_TIII_PYME" => "",
            "USD_TIV_BID" => "",
            "USD_TIV_BNA" => "",
            "USD_TIV_PYME" => "",
            "USD_TOTFUE_BID" => "",
            "USD_TOTFUE_BNA" => "",
            "USD_TOTFUE_PYME" => "",
            "USD_TOTAL" => "",
            
            //NUEVOS
            "IP_TI_REAL" => "",//nuevo
            "IP_TII_REAL" => "",//nuevo
            "IP_TIII_REAL" => "",//nuevo
            "IP_TIV_REAL" => "",//nuevo
            "IP_TOTAL_REAL" => "",//nuevo
            "PESO_TI_BID_REAL" => "",//nuevo
            "PESO_TI_BNA_REAL" => "",//nuevo
            "PESO_TI_PYME_REAL" => "",//nuevo
            "PESO_TII_BID_REAL" => "",//nuevo
            "PESO_TII_BNA_REAL" => "",//nuevo
            "PESO_TII_PYME_REAL" => "",//nuevo
            "PESO_TIII_BID_REAL" => "",//nuevo
            "PESO_TIII_BNA_REAL" => "",//nuevo
            "PESO_TIII_PYME_REAL" => "",//nuevo
            "PESO_TIV_BID_REAL" => "",//nuevo
            "PESO_TIV_BNA_REAL" => "",//nuevo
            "PESO_TIV_PYME_REAL" => "",//nuevo
            "PESO_TOTFUE_BID_REAL" => "",//nuevo
            "PESO_TOTFUE_BNA_REAL" => "",//nuevo
            "PESO_TOTFUE_PYME_REAL" => "",//nuevo
            "PESO_TOTAL_REAL" => ""//nuevo
        );
    
        $headerArr[2] = array(        
        //"COMP_SCOMP" => "COMP / SUBCOMP",
        "AREA" => "ÁREA RESPONSABLE",
        "SCOMP" => "PRODUCTOS (BIEN, SERVICIO, PROYECTO O NORMA)",
        "COMP" => "",
        "CODIGO" => "",
        "DESCRIP" => "",
        "CONTRATADO" => "",
        "IP_UNIDAD" => "",
        "IP_TI" => "",
        "IP_TII" => "",
        "IP_TIII" => "",
        "IP_TIV" => "",
        "IP_TOTAL" => "",
        "COSTO_UNI" => "",
        "COSTO_UNI_USD"=> "",//nuevo
        "Inciso_ONP" => "",
        "FUENTE_22" => "",
        "FUENTE_11" => "",
        "FUENTE_PYME" => "",
        "PESO_TI_BID" => "INDICADOR PRESUPUESTARIO",
        "PESO_TI_BNA" => "",
        "PESO_TI_PYME" => "",
        "PESO_TII_BID" => "",
        "PESO_TII_BNA" => "",
        "PESO_TII_PYME" => "",
        "PESO_TIII_BID" => "",
        "PESO_TIII_BNA" => "",
        "PESO_TIII_PYME" => "",
        "PESO_TIV_BID" => "",
        "PESO_TIV_BNA" => "",
        "PESO_TIV_PYME" => "",
        "PESO_TOTFUE_BID" => "",
        "PESO_TOTFUE_BNA" => "",
        "PESO_TOTFUE_PYME" => "",
        "PESO_TOTAL" => "",
        "USD_TI_BID" => "INDICADOR PRESUPUESTARIO",
        "USD_TI_BNA" => "",
        "USD_TI_PYME" => "",
        "USD_TII_BID" => "",
        "USD_TII_BNA" => "",
        "USD_TII_PYME" => "",
        "USD_TIII_BID" => "",
        "USD_TIII_BNA" => "",
        "USD_TIII_PYME" => "",
        "USD_TIV_BID" => "",
        "USD_TIV_BNA" => "",
        "USD_TIV_PYME" => "",
        "USD_TOTFUE_BID" => "",
        "USD_TOTFUE_BNA" => "",
        "USD_TOTFUE_PYME" => "",
        "USD_TOTAL" => "",
        
        //NUEVOS
        "IP_TI_REAL" => "",//nuevo
        "IP_TII_REAL" => "",//nuevo
        "IP_TIII_REAL" => "",//nuevo
        "IP_TIV_REAL" => "",//nuevo
        "IP_TOTAL_REAL" => "",//nuevo
        "PESO_TI_BID_REAL" => "",//nuevo
        "PESO_TI_BNA_REAL" => "",//nuevo
        "PESO_TI_PYME_REAL" => "",//nuevo
        "PESO_TII_BID_REAL" => "",//nuevo
        "PESO_TII_BNA_REAL" => "",//nuevo
        "PESO_TII_PYME_REAL" => "",//nuevo
        "PESO_TIII_BID_REAL" => "",//nuevo
        "PESO_TIII_BNA_REAL" => "",//nuevo
        "PESO_TIII_PYME_REAL" => "",//nuevo
        "PESO_TIV_BID_REAL" => "",//nuevo
        "PESO_TIV_BNA_REAL" => "",//nuevo
        "PESO_TIV_PYME_REAL" => "",//nuevo
        "PESO_TOTFUE_BID_REAL" => "",//nuevo
        "PESO_TOTFUE_BNA_REAL" => "",//nuevo
        "PESO_TOTFUE_PYME_REAL" => "",//nuevo
        "PESO_TOTAL_REAL" => ""//nuevo    
    );
        
    $headerArr[3] = array(    
        //"COMP_SCOMP" => "",
        "AREA" => "",
        "SCOMP" => "SCOMP",
        "COMP" => "COMP",
        "CODIGO" => "CÓDIGO",
        "DESCRIP" => "DESCRIPCIÓN",
        "CONTRATADO" => "CONTRATADO",
        "IP_UNIDAD" => "INDICADOR PRODUCTO",
        "IP_TI" => "",
        "IP_TII" => "",
        "IP_TIII" => "",
        "IP_TIV" => "",
        "IP_TOTAL" => "",
        "COSTO_UNI" => "COSTO UNITARIO",
        "COSTO_UNI_USD"=> "COSTO UNITARIO USD",//nuevo
        "Inciso_ONP" => "Inciso ONP",
        "FUENTE_22" => "FUENTE",
        "FUENTE_11" => "",
        "FUENTE_PYME" => "",
        "PESO_TI_BID" => "T I ($)",
        "PESO_TI_BNA" => "",
        "PESO_TI_PYME" => "",
        "PESO_TII_BID" => "T II ($)",
        "PESO_TII_BNA" => "",
        "PESO_TII_PYME" => "",
        "PESO_TIII_BID" => "T III ($)",
        "PESO_TIII_BNA" => "",
        "PESO_TIII_PYME" => "",
        "PESO_TIV_BID" => "T IV ($)",
        "PESO_TIV_BNA" => "",
        "PESO_TIV_PYME" => "",
        "PESO_TOTFUE_BID" => "TOTAL POR FUENTE",
        "PESO_TOTFUE_BNA" => "",
        "PESO_TOTFUE_PYME" => "",
        "PESO_TOTAL" => "TOTAL (S)",
        "USD_TI_BID" => "T I (USS)",
        "USD_TI_BNA" => "",
        "USD_TI_PYME" => "",
        "USD_TII_BID" => "T II (USS)",
        "USD_TII_BNA" => "",
        "USD_TII_PYME" => "",
        "USD_TIII_BID" => "T III (USS)",
        "USD_TIII_BNA" => "",
        "USD_TIII_PYME" => "",
        "USD_TIV_BID" => "T IV (USS)",
        "USD_TIV_BNA" => "",
        "USD_TIV_PYME" => "",
        "USD_TOTFUE_BID" => "TOTAL POR FUENTE (USS)",
        "USD_TOTFUE_BNA" => "",
        "USD_TOTFUE_PYME" => "",
        "USD_TOTAL" => 'TOTAL (USS)',
        //NUEVOS
        "IP_TI_REAL" => "",//nuevo
        "IP_TII_REAL" => "",//nuevo
        "IP_TIII_REAL" => "",//nuevo
        "IP_TIV_REAL" => "",//nuevo
        "IP_TOTAL_REAL" => "",//nuevo
        "PESO_TI_BID_REAL" => "",//nuevo
        "PESO_TI_BNA_REAL" => "",//nuevo
        "PESO_TI_PYME_REAL" => "",//nuevo
        "PESO_TII_BID_REAL" => "",//nuevo
        "PESO_TII_BNA_REAL" => "",//nuevo
        "PESO_TII_PYME_REAL" => "",//nuevo
        "PESO_TIII_BID_REAL" => "",//nuevo
        "PESO_TIII_BNA_REAL" => "",//nuevo
        "PESO_TIII_PYME_REAL" => "",//nuevo
        "PESO_TIV_BID_REAL" => "",//nuevo
        "PESO_TIV_BNA_REAL" => "",//nuevo
        "PESO_TIV_PYME_REAL" => "",//nuevo
        "PESO_TOTFUE_BID_REAL" => "",//nuevo
        "PESO_TOTFUE_BNA_REAL" => "",//nuevo
        "PESO_TOTFUE_PYME_REAL" => "",//nuevo
        "PESO_TOTAL_REAL" => ""//nuevo 
       );
    
    $headerArr[4] = array(
        //"COMP_SCOMP" => "",
        "AREA" => "",
        "SCOMP" => "",
        "COMP" => "",
        "CODIGO" => "",
        "DESCRIP" => "",
        "CONTRATADO" => "",
        "IP_UNIDAD" => "UNIDAD MEDIDA",
        "IP_TI" => "T I",
        "IP_TII" => "T II",
        "IP_TIII" => "T III",
        "IP_TIV" => "T IV",
        "IP_TOTAL" => "TOTAL",
        "COSTO_UNI" => "",
        "COSTO_UNI_USD"=> "",//nuevo
        "Inciso_ONP" => "",
        "FUENTE_22" => "22",
        "FUENTE_11" => "11",
        "FUENTE_PYME" => "PYME",
        "PESO_TI_BID" => "BID",
        "PESO_TI_BNA" => "NACIÓN",
        "PESO_TI_PYME" => "APORTE PYME",
        "PESO_TII_BID" => "BID",
        "PESO_TII_BNA" => "NACIÓN",
        "PESO_TII_PYME" => "APORTE PYME",
        "PESO_TIII_BID" => "BID",
        "PESO_TIII_BNA" => "NACIÓN",
        "PESO_TIII_PYME" => "APORTE PYME",
        "PESO_TIV_BID" => "BID",
        "PESO_TIV_BNA" => "NACIÓN",
        "PESO_TIV_PYME" => "APORTE PYME",
        "PESO_TOTFUE_BID" => "BID",
        "PESO_TOTFUE_BNA" => "NACIÓN",
        "PESO_TOTFUE_PYME" => "APORTE PYME",
        "PESO_TOTAL" => "",
        "USD_TI_BID" => "BID",
        "USD_TI_BNA" => "NACIÓN",
        "USD_TI_PYME" => "APORTE PYME",
        "USD_TII_BID" => "BID",
        "USD_TII_BNA" => "NACIÓN",
        "USD_TII_PYME" => "APORTE PYME",
        "USD_TIII_BID" => "BID",
        "USD_TIII_BNA" => "NACIÓN",
        "USD_TIII_PYME" => "APORTE PYME",
        "USD_TIV_BID" => "BID",
        "USD_TIV_BNA" => "NACIÓN",
        "USD_TIV_PYME" => "APORTE PYME",
        "USD_TOTFUE_BID" => "BID",
        "USD_TOTFUE_BNA" => "NACIÓN",
        "USD_TOTFUE_PYME" => "APORTE PYME",
        "USD_TOTAL" => "",
        //NUEVOS
        "IP_TI_REAL" => "TI_REAL",//nuevo
        "IP_TII_REAL" => "TII_REAL",//nuevo
        "IP_TIII_REAL" => "TIII_REAL",//nuevo
        "IP_TIV_REAL" => "TIV_REAL",//nuevo
        "IP_TOTAL_REAL" => "TOTAL_REAL",//nuevo
        "PESO_TI_BID_REAL" => "TI_BID_REAL",//nuevo
        "PESO_TI_BNA_REAL" => "TI_BNA_REAL",//nuevo
        "PESO_TI_PYME_REAL" => "TI_PYME_REAL",//nuevo
        "PESO_TII_BID_REAL" => "TII_BID_REAL",//nuevo
        "PESO_TII_BNA_REAL" => "TII_BNA_REAL",//nuevo
        "PESO_TII_PYME_REAL" => "TII_PYME_REAL",//nuevo
        "PESO_TIII_BID_REAL" => "TIII_BID_REAL",//nuevo
        "PESO_TIII_BNA_REAL" => "TIII_BNA_REAL",//nuevo
        "PESO_TIII_PYME_REAL" => "TIII_PYME_REAL",//nuevo
        "PESO_TIV_BID_REAL" => "TIV_BID_REAL",//nuevo
        "PESO_TIV_BNA_REAL" => "TIV_BNA_REAL",//nuevo
        "PESO_TIV_PYME_REAL" => "TIV_PYME_REAL",//nuevo
        "PESO_TOTFUE_BID_REAL" => "TOTFUE_BID_REAL",//nuevo
        "PESO_TOTFUE_BNA_REAL" => "TOTFUE_BNA_REAL",//nuevo
        "PESO_TOTFUE_PYME_REAL" => "TOTFUE_PYME_REAL",//nuevo
        "PESO_TOTAL_REAL" => "TOTAL_REAL"//nuevo 
        );
    
    $headerArr[5] = array(
        //"COMP_SCOMP" => "",
        "AREA" => "",
        "SCOMP" => "",
        "COMP" => "",
        "CODIGO" => "",
        "DESCRIP" => "",
        "CONTRATADO" => "",
        "IP_UNIDAD" => "",
        "IP_TI" => "ESTIM",
        "IP_TII" => "ESTIM",
        "IP_TIII" => "ESTIM",
        "IP_TIV" => "ESTIM",
        "IP_TOTAL" => "ESTIM",
        "COSTO_UNI" => "",
        "COSTO_UNI_USD"=> "",//nuevo
        "Inciso_ONP" => "",
        "FUENTE_22" => "",
        "FUENTE_11" => "",
        "FUENTE_PYME" => "",
        "PESO_TI_BID" => "ESTIMADO",
        "PESO_TI_BNA" => "ESTIMADO",
        "PESO_TI_PYME" => "ESTIMADO",
        "PESO_TII_BID" => "ESTIMADO",
        "PESO_TII_BNA" => "ESTIMADO",
        "PESO_TII_PYME" => "ESTIMADO",
        "PESO_TIII_BID" => "ESTIMADO",
        "PESO_TIII_BNA" => "ESTIMADO",
        "PESO_TIII_PYME" => "ESTIMADO",
        "PESO_TIV_BID" => "ESTIMADO",
        "PESO_TIV_BNA" => "ESTIMADO",
        "PESO_TIV_PYME" => "ESTIMADO",
        "PESO_TOTFUE_BID" => "",
        "PESO_TOTFUE_BNA" => "",
        "PESO_TOTFUE_PYME" => "",
        "PESO_TOTAL" => "",
        "USD_TI_BID" => "ESTIMADO",
        "USD_TI_BNA" => "ESTIMADO",
        "USD_TI_PYME" => "ESTIMADO",
        "USD_TII_BID" => "ESTIMADO",
        "USD_TII_BNA" => "ESTIMADO",
        "USD_TII_PYME" => "ESTIMADO",
        "USD_TIII_BID" => "",
        "USD_TIII_BNA" => "",
        "USD_TIII_PYME" => "",
        "USD_TIV_BID" => "",
        "USD_TIV_BNA" => "",
        "USD_TIV_PYME" => "",
        "USD_TOTFUE_BID" => "",
        "USD_TOTFUE_BNA" => "",
        "USD_TOTFUE_PYME" => "",
        "USD_TOTAL" => "",
        //NUEVOS
        "IP_TI_REAL" => "REAL",//nuevo
        "IP_TII_REAL" => "REAL",//nuevo
        "IP_TIII_REAL" => "REAL",//nuevo
        "IP_TIV_REAL" => "REAL",//nuevo
        "IP_TOTAL_REAL" => "REAL",//nuevo
        "PESO_TI_BID_REAL" => "REAL",//nuevo
        "PESO_TI_BNA_REAL" => "REAL",//nuevo
        "PESO_TI_PYME_REAL" => "REAL",//nuevo
        "PESO_TII_BID_REAL" => "REAL",//nuevo
        "PESO_TII_BNA_REAL" => "REAL",//nuevo
        "PESO_TII_PYME_REAL" => "REAL",//nuevo
        "PESO_TIII_BID_REAL" => "REAL",//nuevo
        "PESO_TIII_BNA_REAL" => "REAL",//nuevo
        "PESO_TIII_PYME_REAL" => "REAL",//nuevo
        "PESO_TIV_BID_REAL" => "REAL",//nuevo
        "PESO_TIV_BNA_REAL" => "REAL",//nuevo
        "PESO_TIV_PYME_REAL" => "REAL",//nuevo
        "PESO_TOTFUE_BID_REAL" => "REAL",//nuevo
        "PESO_TOTFUE_BNA_REAL" => "REAL",//nuevo
        "PESO_TOTFUE_PYME_REAL" => "REAL",//nuevo
        "PESO_TOTAL_REAL" => "REAL"//nuevo 
        );

        
        for ($index_l = 6; $index_l <= $lines; $index_l++) {
            
            $headerArr[$index_l]  = $array[$index_l]; 
            
        }
        //var_dump($headerArr);
        $headerArr['PESO_TI_BID'] = $PESO_TI_BID;
        $headerArr['PESO_TI_BNA'] = $PESO_TI_BNA;
        $headerArr['borrado'] = 0;
        
        
        
        ////////////////
        $thisArr = array();
        $result = $this->db_pacc->insert($container_POA,$headerArr);
        return $result;
        
    }
    
    //// POA Parametros ////
    
    
    /**
     * Inserta nuevo poa parametro al cuadro general
     * 
     * @param array $array
     * @return $result
     */
    
    function put_poa_parametros($array = array()){ // Inserta nuevo poa parametro -- Recibe $array :
        
        //CODIGO
        //AREA_REGISTRADA
        //DESCRIPCION
        //ACTIVO
        
        $array['borrado'] = '0';
        $container = 'container.pacc_POA_param';
        $result = $this->db_pacc->insert($container,$array);
        return $result;
    }
    
    /**
     * Borrar poa parametros
     * 
     * @param MongoID $id_poa_param
     */
    
    function borrar_poa_parametros($id_poa_param){ // Borrar poa parametros -- se le pasa el $id_poa_param
        $container = 'container.pacc_POA_param';
        $data = array(
            'borrado' => 1
            );
        $mongoID=new MongoID($id_poa_param);
        $query = array('_id'=>$mongoID);       
        
        $this->db_pacc->where($query);
        $this->db_pacc->update($container,$data);
        
    }
    
    /**
     * Editar un parametro
     * 
     * @param MongoID $id_poa_param
     * @param array $array
     */
    
    function editar_poa_parametros($id_poa_param, $array = array()){ /// Editar un parametro -- Se pasa el $id_poa_param y el $array :
        //CODIGO
        //AREA_REGISTRADA
        //DESCRIPCION
        //ACTIVO
        
        $container = 'container.pacc_POA_param';
        $array['borrado'] = '0';
        $mongoID=new MongoID($id_poa_param);
        $query = array('_id'=>$mongoID);       
        
        $this->db_pacc->where($query);
        $this->db_pacc->update($container,$array);
        
    }
    
    /**
     * Devuelve lista de parametros POA cargados
     * 
     * @return array $result
     */
    
    function lista_cargados_poa_parametros(){ /// Devuelve lista de parametros POA cargados en el sistema
        $result = array();
        $container = 'container.pacc_POA_param';
        
        $result = $this->db_pacc->get($container)->result_array();
        
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





