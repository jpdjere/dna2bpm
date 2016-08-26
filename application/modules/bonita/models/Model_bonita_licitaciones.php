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
        echo("RESULTADO");
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
        $headerArr['abierta']=true;
        $headerArr['fechalic']=strtotime($headerArr['fechalic']);
        $headerArr['fechalic']=getdate($headerArr['fechalic']);
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
        $headerArr['fechalic']=strtotime($headerArr['fechalic']);
        $headerArr['fechalic']=getdate($headerArr['fechalic']);
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
        $query = array('borrado'=>0, 'abierta'=>true);
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
        //devuelve las ofertas que no fueron borradas con la rsocial, monto, id_entidad
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

/**************************************CERRAR LICITACION**************************************/
    function persistir_licitacion_y_cerrar($id_mongo, $asignacion_actual){
        $container = 'container.bonita.licitaciones';
        $query = array('_id'=>new MongoID($id_mongo)); 
        $this->db_bonita->where($query);
        $data = $this->db_bonita->get($container)->result_array();
        $data=$data[0];
        
        $data['abierta']=false;
        $data['fecha_cierre']=getdate();
        
        $x=0;
        $i=0;
        foreach($data['ofertas'] as $oferta){
            if($oferta['borrado']==false){
                $data['ofertas'][$x]['asignacion']=$asignacion_actual[$i];
                $i+=1;
            }
            $x+=1;
        }
        
        $this->db_bonita->where($query);
        $result = $this->db_bonita->update($container,$data);
        return $result;
    }

/**************************************REPORTES LICITACIONES CERRADAS**************************************/
    function listar_licitaciones_cerradas(){
        $container = 'container.bonita.licitaciones';
        $query = array('borrado'=>0, 'abierta'=>false);
        $this->db_bonita->where($query);
        $result = $this->db_bonita->get($container)->result_array();
        return $result;
    }
    
    function get_datos_licitacion_cerrada($id_licitacion){
        $container = 'container.bonita.licitaciones';
        try{
            $query = array('borrado'=>0, 'abierta'=>false, '_id'=>new MongoID($id_licitacion));
        }catch(MongoException $e){
            return;
        }
        $this->db_bonita->where($query);
        $result = $this->db_bonita->get($container)->result_array();
        return $result[0];
    }
    
    function get_rsocial($id_entidad){
        $container = 'container.bonita.entidades';
        $query = array('borrado'=>0, '_id'=>new MongoID($id_entidad));
        $this->db_bonita->where($query);
        $result = $this->db_bonita->get($container)->result_array();
        return $result[0]['rsocial'];
    }
    
    /*function limpiar($String) {

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
    }*/
}





