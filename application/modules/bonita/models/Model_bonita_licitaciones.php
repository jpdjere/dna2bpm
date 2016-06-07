<?php

/**
 * Funciones para el manejo de datos del POA.
 * 
 * @author MAGonzalez <mglongchamps@gmail.com>
 * @date 14/09/2015
 * 
 */

class model_bonita_licitaciones extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('cimongo/cimongo.php', '', 'db_bonita');
        $this->db_bonita->switch_db('bonita');
    }

/**************************************ENTIDADES**************************************/
    function listar_entidades(){
        $container = 'container.bonita.entidades';
        $query = array('borrado'=>0);
        $this->db_bonita->where($query);
        $result = $this->db_bonita->get($container)->result_array();
        return $result;
    }

    function guardar_entidades($headerArr){
        $container = 'container.bonita.entidades';
        $hoy = getdate();
        $headerArr['fecha'] = $hoy;
        $result = $this->db_bonita->insert($container,$headerArr);
        return $result;
    }
    
    function guardar_entidades_editar($headerArr){
        $container = 'container.bonita.entidades';
        $hoy = getdate();
        $headerArr['fecha'] = $hoy;
        $id_mongo=$headerArr['id_mongo'];     
        $mongoID=new MongoID($id_mongo);
        $query = array('_id'=>$mongoID); 
        $this->db_bonita->where($query);
        $result = $this->db_bonita->update($container,$headerArr);
        return $result;
    }
    
    function borrar_entidades($headerArr){
        $container = 'container.bonita.entidades';
        $data = array(
               'borrado' => 1
            );
        $id_mongo=$headerArr['id_mongo'];     
        $mongoID=new MongoID($id_mongo);
        $query = array('_id'=>$mongoID); 
        $this->db_bonita->where($query);
        $result = $this->db_bonita->update($container,$data);
        return $result;
    }

/**************************************LICITACIONES**************************************/
    function listar_licitaciones(){
        $container = 'container.bonita.licitaciones';
        $query = array('borrado'=>0, 'editable'=>true);
        $this->db_bonita->where($query);
        $result = $this->db_bonita->get($container)->result_array();
        return $result;
    }

    function guardar_licitaciones($headerArr){
        $container = 'container.bonita.licitaciones';
        $hoy = getdate();
        $headerArr['fecha'] = $hoy;
        $headerArr['cmax']=$headerArr['cmax']*1;
        $headerArr['maxeeff']=$headerArr['maxeeff']*1;
        $id_mongo=$headerArr['id_mongo'];     
        $mongoID=new MongoID($id_mongo);
        $query = array('_id'=>$mongoID); 
        $this->db_bonita->where($query);
        $result = $this->db_bonita->insert($container,$headerArr);
        return $result;
    }

    function guardar_licitaciones_editar($headerArr){
        $container = 'container.bonita.licitaciones';
        $hoy = getdate();
        $headerArr['fecha'] = $hoy;
        $id_mongo=$headerArr['id_mongo'];     
        $mongoID=new MongoID($id_mongo);
        $query = array('_id'=>$mongoID); 
        $this->db_bonita->where($query);
        $result = $this->db_bonita->update($container,$headerArr);
        return $result;
    }
    
    function borrar_licitaciones($headerArr){
        $container = 'container.bonita.licitaciones';
        $data = array(
               'borrado' => 1
            );
        $id_mongo=$headerArr['id_mongo'];     
        $mongoID=new MongoID($id_mongo);
        $query = array('_id'=>$mongoID); 
        $this->db_bonita->where($query);
        $result = $this->db_bonita->update($container,$data);
        return $result;
    }
    
/**************************************CARGA LICITACION ENTIDAD**************************************/
    function listar_licitaciones_no_editables(){
        $container = 'container.bonita.licitaciones';
        $query = array('borrado'=>0);
        $this->db_bonita->where($query);
        $result = $this->db_bonita->get($container)->result_array();
        return $result;
    }

    function get_datos_licitacion($id_mongo){
        $container = 'container.bonita.licitaciones';
        try{
            $mongoID=new MongoID($id_mongo);
        }catch(MongoException $e){
            return array();
        }
        $query = array('_id'=>$mongoID, 'borrado'=>0);
        $this->db_bonita->where($query);
        $result = $this->db_bonita->get($container)->result_array();
        return $result;
    }
    
    function guardar_carga($headerArr){
        $container = 'container.bonita.licitaciones';
        $IdLicitacion=new MongoID($headerArr['id_mongo']);
        $query = array('_id'=>$IdLicitacion);
        $this->db_bonita->where($query);
        $data = $this->db_bonita->get($container)->result_array();
        $data=$data[0];
        $data["editable"]=$headerArr["editable"];
        $id_entidad=$headerArr['id_entidad'];
        $data["ofertas"][]=array('id_entidad'=>new MongoID($id_entidad),'monto'=>$headerArr['monto']*1,'borrado'=>false);

        $this->db_bonita->where($query);
        $result = $this->db_bonita->update($container,$data);
        return $result;
    }
    
    function get_datos_cargados($id_mongo){
        $container = 'container.bonita.licitaciones';
        $mongoID=new MongoID($id_mongo);
        $query = array('_id'=>$mongoID, 'borrado'=>0);
        $this->db_bonita->where($query);
        $resultado_licitaciones = $this->db_bonita->get($container)->result_array();
        $resultado_licitaciones = $resultado_licitaciones[0]['ofertas'];
        
        $container = 'container.bonita.entidades';
        $i=0;
        foreach($resultado_licitaciones as $entidad){
            if($entidad['borrado']==false){
                $mongoID=new MongoID($entidad['id_entidad']);
                $query = array('_id'=>$mongoID, 'borrado'=>0);
                $this->db_bonita->where($query);
                $resultado_entidad = $this->db_bonita->get($container)->result_array();
                $rsocial = $resultado_entidad[0]['rsocial'];
                $resultado_licitaciones[$i]['rsocial']=$rsocial;
            }else{
                unset($resultado_licitaciones[$i]);
            }
            $i=$i+1;
        }
        
        return $resultado_licitaciones;
    }
    
    function get_entidades_disponibles($id_mongo){
        
        $entidades_cargadas=$this->get_datos_cargados($id_mongo);
        $entidades_disponbles=$this->listar_entidades();
        $i=0;
        foreach($entidades_disponbles as $entidad_disponible){
            foreach($entidades_cargadas as $entidad_cargada){
                if($entidad_disponible['_id'] == $entidad_cargada['id_entidad']){
                    unset($entidades_disponbles[$i]);
                }
            }
            $i=$i+1;
        }
        return $entidades_disponbles;
    }
    
    function borrar_carga($headerArr){
        //borra la carga de un monto en una licitacion partucular
        $container = 'container.bonita.licitaciones';
        $id_licitacion=$headerArr['id_licitacion'];
        $id_entidad=$headerArr['id_entidad'];
        $query = array('_id'=>new MongoID($id_licitacion)); 
        $this->db_bonita->where($query);
        $data = $this->db_bonita->get($container)->result_array();
        $data=$data[0];
        $ofertas=$data['ofertas'];
        
        $i=0;
        foreach($ofertas as $oferta){
            if($oferta['id_entidad']==new MongoID($id_entidad)){
                $data['ofertas'][$i]['borrado']=true;
            }
            $i=$i+1;
        }
        
        $this->db_bonita->where($query);
        $result = $this->db_bonita->update($container,$data);
        return $result;
    }
    
    function get_cmax($header){
        $container = 'container.bonita.licitaciones';
        $mongoID=new MongoID($header);
        $query = array('_id'=>$mongoID);
        $this->db_bonita->where($query);
        $data = $this->db_bonita->get($container)->result_array();
        return $data[0]["cmax"];
    }

    
    
    
    
    
    //$mongoID=new MongoID($id_poa_param);
    //    $query = array('_id'=>$mongoID); 
    
    /**
     * Función que lista todos los POA cargados en la base de datos.
     * 
     * @return array $result
     */
    /*
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
    */
    
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





