<?php

/**
 * Funciones para el manejo de datos del POA.
 * 
 * @author MAGonzalez <mglongchamps@gmail.com>
 * @date 14/09/2015
 * 
 */

class model_bonita_reportes extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        //$this->load->config('importar');
        //var_dump($this->config);exit;
        $config['hostname'] = 'localhost';
        $config['username'] = 'root';
        $config['password'] = 'seba1553';
        $config['database'] = 'importar';
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';
        $this->db_importar = $this->load->database($config,TRUE);
    }
    
    /**
     * Función que lista todos los POA cargados en la base de datos.
     * 
     * @return array $result
     */
    
    function lista_provincias(){ /// Devuelve lista de POA cargados en el sistema
        $result = array();
        $container = 'bonita_provincias';
        
        $result = $this->db_importar->get($container)->result_array();
        
        return $result;
    }
    
    function sectores(){ 
        $result = array();
        $container = 'bonita_prestamos';
        $this->db_importar->select('sector');
        $this->db_importar->distinct();
        $result = $this->db_importar->get($container)->result_array();
        
        return $result; 
        
    }
    
    function tamanios(){ 
        $result = array();
        $container = 'bonita_prestamos';
        $this->db_importar->select('tam_empresa');
        $this->db_importar->distinct();
        $result = $this->db_importar->get($container)->result_array();
        
        return $result; 
        
    }
    
    function datos_sectores($sect,$desde,$hasta){ /// Devuelve lista de POA cargados en el sistema
        $result = array();
        $container = 'bonita_prestamos';
        
        $new_desde=strtotime($desde);
        $new_desde=date('Y-m-d',$new_desde);
        
        $new_hasta=strtotime($hasta);
        $new_hasta=date('Y-m-d',$new_hasta);
        $capital =0;
        $cantidad = 0;
        
        $this->db_importar->select_sum('cap');
        $this->db_importar->where('sector =',$sect);
        $this->db_importar->where('fecha_acredita >= ',$new_desde);
        $this->db_importar->where('fecha_acredita < ',$new_hasta);
        $bus_capital = $this->db_importar->get($container)->result_array();
        
        foreach($bus_capital as $bcap){
            $capital = $bcap['cap'];
        }
        
        
        $this->db_importar->like('sector',$sect);
        $this->db_importar->where('fecha_acredita >= ',$new_desde);
        $this->db_importar->where('fecha_acredita < ',$new_hasta);
        $cantidad = $this->db_importar->count_all_results($container);
        
        //$result['sector'] = $sect;
        $result['capital'] = $capital;
        $result['cantidad'] = $cantidad;
        $result['sector'] = $sect;
        return $result; 
        
    }
    
    
    function datos_sectores_tams($sect,$tams,$desde,$hasta){ /// Devuelve lista de POA cargados en el sistema
        $result = array();
        $container = 'bonita_prestamos';
        
        $new_desde=strtotime($desde);
        $new_desde=date('Y-m-d',$new_desde);
        
        $new_hasta=strtotime($hasta);
        $new_hasta=date('Y-m-d',$new_hasta);
        $capital =0;
        $cantidad = 0;
        
        $this->db_importar->select_sum('cap');
        $this->db_importar->where('sector =',$sect);
        $this->db_importar->where('tam_empresa =',$tams);
        $this->db_importar->where('fecha_acredita >= ',$new_desde);
        $this->db_importar->where('fecha_acredita < ',$new_hasta);
        $bus_capital = $this->db_importar->get($container)->result_array();
        
        foreach($bus_capital as $bcap){
            $capital = $bcap['cap'];
        }
        
        
        $this->db_importar->like('sector',$sect);
        $this->db_importar->like('tam_empresa',$tams);
        $this->db_importar->where('fecha_acredita >= ',$new_desde);
        $this->db_importar->where('fecha_acredita < ',$new_hasta);
        $cantidad = $this->db_importar->count_all_results($container);
        
        //$result['sector'] = $sect;
        $result['capital'] = $capital;
        $result['cantidad'] = $cantidad;
        $result['sector'] = $sect;
        $result['tam_empresa'] = $tams;
        
        return $result; 
        
    }
    
    function prestamos_total($desde,$hasta){ /// Devuelve lista de POA cargados en el sistema
        $result = array();
        $container = 'bonita_prestamos';
        $new_desde=strtotime($desde);
        $new_desde=date('Y-m-d',$new_desde);
        
        $new_hasta=strtotime($hasta);
        $new_hasta=date('Y-m-d',$new_hasta);
        
        
        $this->db_importar->where('fecha_acredita >= ',$new_desde);
        $this->db_importar->where('fecha_acredita < ',$new_hasta);
        $result = $this->db_importar->count_all_results($container);//->get($container)->result_array();
        
        return $result; 
        
    }
    function monto_total($desde,$hasta){ /// Devuelve lista de POA cargados en el sistema
        $result = array();
        $container = 'bonita_prestamos';
        
        $new_desde=strtotime($desde);
        $new_desde=date('Y-m-d',$new_desde);
        
        $new_hasta=strtotime($hasta);
        $new_hasta=date('Y-m-d',$new_hasta);
        
        
        $this->db_importar->select('cap');
        $this->db_importar->where('fecha_acredita >= ',$new_desde);
        $this->db_importar->where('fecha_acredita < ',$new_hasta);
        $result = $this->db_importar->get($container)->result_array();//$this->db_importar->get($container)->result_array();
        $suma = 0;
        foreach ($result as $key) {
            $suma= $suma+floatval($key['cap']);
        }
        return $suma; 
        
    }
    
    function montos_provincias($provincia,$prestamos_total,$monto_total,$desde,$hasta){
        $result = array();
        $container = 'bonita_prestamos';
        $new_desde=strtotime($desde);
        $new_desde=date('Y-m-d',$new_desde);
                
        $new_hasta=strtotime($hasta);
        $new_hasta=date('Y-m-d',$new_hasta);
       
        $result['provincia'] = $provincia;
        $this->db_importar->where('fecha_acredita >= ',$new_desde);
        $this->db_importar->where('fecha_acredita < ',$new_hasta);
        $this->db_importar->where('provincia =', $provincia);
        $result['prestamos_prov'] = $this->db_importar->count_all_results($container);

        $result['porcent_prestamos'] = number_format((float)(($result['prestamos_prov'] / $prestamos_total)*100), 2, ',', '');
        $this->db_importar->select('cap');
        $this->db_importar->where('fecha_acredita >= ',$new_desde);
        $this->db_importar->where('fecha_acredita < ',$new_hasta);
        $this->db_importar->where('provincia =', $provincia);
        $montos = $this->db_importar->get($container)->result_array();//$this->db_importar->get($container)->result_array();
        $result['montos_prov'] = 0;
        
        foreach ($montos as $key) {
            $result['montos_prov'] = $result['montos_prov'] +floatval($key['cap']);
        }
        $monto_prov = $result['montos_prov']; 
        //$result['montos_prov'] = number_format((float)($result['montos_prov']), 2, '.','');
        $result['montos_prov'] = ($result['montos_prov']);
        //$result['porcent_montos'] = number_format((float)(($monto_prov / $monto_total)*100), 2, '.','');
        $result['porcent_montos'] = (($monto_prov / $monto_total)*100);
        
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





