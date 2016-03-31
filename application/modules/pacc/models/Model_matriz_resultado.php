<?php

/**
 * Funciones para el manejo de datos de Matriz de Resultados.
 * 
 * @author MAGonzalez <mglongchamps@gmail.com>
 * @date 14/09/2015
 * 
 */

class Model_matriz_resultado extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        
        
        $this->load->library('cimongo/Cimongo.php', '', 'db_pacc');
        $this->db_pacc->switch_db('pacc');
               
    }
    
    /**
     * Devuelve lista de cargados en el sistema
     * 
     * @return array $result
     */
    
    function lista_cargados(){ /// Devuelve lista de cargados en el sistema
        $result = array();
        $container = 'container.matriz_resultado';
        $query = array('borrado'=>0);
        $this->db_pacc->where($query);
        $result = $this->db_pacc->get($container)->result_array();
        
        return $result; 
        
    }
    
    /**
     * Devuelve array con toda la info
     * 
     * @param MongoID $id_m
     * @return array $result
     */
    
    function detalle_matriz_resultado($id_m){ // Devuelve array con toda la info - Se le pasa $id_m
        
        $result = array();
        $container = 'container.matriz_resultado';
        $mongoID=new MongoID($id_m);
        
        $query = array('_id'=>$mongoID);
    
        $this->db_pacc->where($query);
        $result = $this->db_pacc->get($container)->result_array();
               
        return $result;
    }
       
    /**
     * Devuelve la cantidad de cargados
     * 
     * @return int $result
     */
    
    
    function count_cargados(){ // Devuelve la cantidad de cargados
        $rtn = array();
        $container = 'container.matriz_resultado';
        $fields = array('filename');
        $query = array();
        
        $result = $this->db_pacc->count_all($container);
       
        return $result;
     
    }
    
    /**
     * Borra logicamente
     * 
     * @param MongoID $id_m
     */
    
    function borrar_matriz_resultado($id_m){ // Borra logicamente -- Se le pasa $id_m
        $container = 'container.matriz_resultado';
        $data = array(
            'borrado' => 1
            );
        $mongoID=new MongoID($id_m);
        $query = array('_id'=>$mongoID);       
        
        $this->db_pacc->where($query);
        $this->db_pacc->update($container,$data);
        
    }
    
    /**
     * Guarda en la base el nuevo registro de matriz
     * 
     * @param array $array_impacto{
     *      @var string $array_impacto["INDICADORES"]
     *      @var string $array_impacto["UNIDAD DE MEDIDA"]
     *      @var string $array_impacto["LINEA DE BASE"]
     *      @var string $array_impacto["MEDICIONES INTERMEDIAS"]
     *      @var string $array_impacto["METAS AL FINAL DEL PROYECTO"]
     *      @var string $array_impacto["FUENTE"]
     *      @var string $array_impacto["OBSERVACIONES"] 
     * }
     * @param int $lines_imp
     * @param array $array_resultado{
     *      @var string $array_resultado["INDICADORES"]
     *      @var string $array_resultado["UNIDAD DE MEDIDA"]
     *      @var string $array_resultado["LINEA DE BASE"]
     *      @var string $array_resultado["MEDICIONES INTERMEDIAS"]
     *      @var string $array_resultado["METAS AL FINAL DEL PROYECTO"]
     *      @var string $array_resultado["FUENTE"]
     *      @var string $array_resultado["OBSERVACIONES"] 
     * }
     * @param int $lines_result
     * @param array $array_producto{
     *      @var string $array_producto["PRODUCTOS"]
     *      @var string $array_producto["UNIDAD DE MEDIDA"]
     *      @var string $array_producto["LINEA DE BASE"]
     *      @var string $array_producto["MEDICIONES INTERMEDIAS"]
     *      @var string $array_producto["METAS AL FINAL DEL PROYECTO"]
     *      @var string $array_producto["FUENTE"]
     * }
     * @param int $lines_prod
     
     */
    
    function put_array_matriz_resultado($array_impacto = array(),$lines_imp, $array_resultado = array(),$lines_result,$array_producto = array(),$lines_prod) { // Guarda en la base el nuevo registro -- Se le pasa:
        
        
        //La cantidad de $lines_x a cargar
        //$array_x info de cada informe: IMPACTO - RESULTADO - PRODUCTO 
        //////////////
        
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
            $new_filename = $new_filename . '0' . $hoy['seconds'] . '-M-RESULT-';
        else
            $new_filename = $new_filename . $hoy['seconds'] . '-M-RESULT-';
        
        $new_filename = $new_filename . rand(1000, 5000) .'.xls';
        
        
        $container = 'container.matriz_resultado';
        $id = 1;
        $headerArr = array();
        $headerArr['filename'] = $new_filename;
        $headerArr['date'] = $hoy;
        $index_l = (0);
        $index_c = (0);
        
        
        $Arr_name_impacto = array(
            "INDICADORES",
            "UNIDAD DE MEDIDA",
            "LINEA DE BASE",
            "MEDICIONES INTERMEDIAS",
            "METAS AL FINAL DEL PROYECTO",
            "FUENTE",
            "OBSERVACIONES"
        );
        
        $Arr_name_resultado = array(
            "INDICADORES",
            "UNIDAD DE MEDIDA",
            "LINEA DE BASE",
            "MEDICIONES INTERMEDIAS",
            "METAS AL FINAL DEL PROYECTO",
            "FUENTE",
            "OBSERVACIONES"
        );
        
        $Arr_name_producto = array(
            "PRODUCTOS",
            "UNIDAD DE MEDIDA",
            "LINEA DE BASE",
            "MEDICIONES INTERMEDIAS",
            "METAS AL FINAL DEL PROYECTO",
            "FUENTE"
        );
        $cols_impacto =6;
        $cols_resultado =6;
        $cols_producto =5;
        //var_dump($array_impacto);
        //var_dump($Arr_name_impacto);
        //exit();
        
        for ($index_l = 1; $index_l <= $lines_imp; $index_l++) {
            for ($index_c = 0; $index_c <= $cols_impacto; $index_c++){
                $headerArr['IMPACTO'][$index_l][$Arr_name_impacto[($index_c )]] = $array_impacto[$index_l][$Arr_name_impacto[($index_c )]]; 
            }
        }
        
        for ($index_l = 1; $index_l <= $lines_result; $index_l++) {
            for ($index_c = 0; $index_c <= $cols_resultado; $index_c++){
                $headerArr['RESULTADO'][$index_l][$Arr_name_resultado[($index_c )]] = $array_resultado[$index_l][$Arr_name_resultado[($index_c )]]; 
            }
        }
        
        for ($index_l = 1; $index_l <= $lines_prod; $index_l++) {
            for ($index_c = 0; $index_c <= $cols_producto; $index_c++){
                $headerArr['PRODUCTO'][$index_l][$Arr_name_producto[($index_c )]] = $array_producto[$index_l][$Arr_name_producto[($index_c )]]; 
            }
        }
        //var_dump($headerArr);
       
        $headerArr['borrado'] = 0;
        
        //var_dump($headerArr);
        //exit();
        
        ////////////////
        $thisArr = array();
        $result = $this->db_pacc->insert($container,$headerArr);
        return $result;
        
        
    }
    
     /**
     * Editar en la base registro de matriz
     * 
     * @param  MongoID $id_mongo Description 
     * @param array $array_impacto{
     *      @var string $array_impacto["INDICADORES"]
     *      @var string $array_impacto["UNIDAD DE MEDIDA"]
     *      @var string $array_impacto["LINEA DE BASE"]
     *      @var string $array_impacto["MEDICIONES INTERMEDIAS"]
     *      @var string $array_impacto["METAS AL FINAL DEL PROYECTO"]
     *      @var string $array_impacto["FUENTE"]
     *      @var string $array_impacto["OBSERVACIONES"] 
     * }
     * @param int $lines_imp
     * @param array $array_resultado{
     *      @var string $array_resultado["INDICADORES"]
     *      @var string $array_resultado["UNIDAD DE MEDIDA"]
     *      @var string $array_resultado["LINEA DE BASE"]
     *      @var string $array_resultado["MEDICIONES INTERMEDIAS"]
     *      @var string $array_resultado["METAS AL FINAL DEL PROYECTO"]
     *      @var string $array_resultado["FUENTE"]
     *      @var string $array_resultado["OBSERVACIONES"] 
     * }
     * @param int $lines_result
     * @param array $array_producto{
     *      @var string $array_producto["PRODUCTOS"]
     *      @var string $array_producto["UNIDAD DE MEDIDA"]
     *      @var string $array_producto["LINEA DE BASE"]
     *      @var string $array_producto["MEDICIONES INTERMEDIAS"]
     *      @var string $array_producto["METAS AL FINAL DEL PROYECTO"]
     *      @var string $array_producto["FUENTE"]
     * }
     * @param int $lines_prod
     
     */
   
    function edit_array_matriz_resultado($id_mongo,$impacto, $im_c, $resultado, $res_c, $producto, $pro_c) { // Guarda en la base el nuevo registro -- Se le pasa:
        
        //$id_mongo: ID del registro a modificar
        //La cantidad de $lines_x a cargar
        //$array_x info de cada informe: IMPACTO - RESULTADO - PRODUCTO 
        //////////////
       
        $headerArr['IMPACTO'] = $impacto;
        $headerArr['RESULTADO'] = $resultado;
        $headerArr['PRODUCTO'] = $producto;
        $headerArr['borrado'] = 0;
        
        //var_dump($headerArr);
        //exit();
        
        
        
        $container = 'container.matriz_resultado';
        $mongoID=new MongoID($id_mongo);
        $query = array('_id'=>$mongoID);
        
        $this->db_pacc->where($query);
        $this->db_pacc->update($container,$headerArr);
        
        
        
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





