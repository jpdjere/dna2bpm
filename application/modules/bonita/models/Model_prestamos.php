<?php

/**
 * Funciones para el manejo de datos del POA.
 * 
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 13/07/2016
 * 
 */

class model_prestamos extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->importar = $this->load->database('importar', true);
        $this->load->model('model_bonita_reportes');
        $this->reportes = $this->model_bonita_reportes;
        
        $this->load->library('cimongo/cimongo.php', '', 'dbmongo');
        $this->dbmongo->switch_db('bonita');
    }
    
    
    
    /**
     * Inserta una entidad en la base
     */
    function insertar_entidad($entidad){
        return $this->importar->insert('entidades', $entidad);
    }
    
    /**
     * Actualiza una entidad en la base
     */
    function actualizar_entidad($entidad){
        $id=$entidad['id'];
        unset($entidad['id']);
        return $this->importar->update('entidades', $entidad, "id=$id");
    }
    
    /**
     * Borra una entidad en la base
     */
    function borrar_entidad($id){
        echo $this->importar->update('entidades', array('borrado'=>true), "id=".$id['id']);
    }
    
    /**
     * Lista las entidades no borradas de la base
     */
    function listar_entidades(){
        return $this->importar->get_where('entidades', 'borrado=false')->result_array();
    }
    
    
    
    /**
     * Inserta un destino en la base
     */
    function insertar_destino($destino){
        $destino = array_map('strtolower', $destino);
        return $this->importar->insert('destinos', $destino);
    }
    
    /**
     * Actualiza un destino en la base
     */
    function actualizar_destino($destino){
        $id=$destino['id'];
        unset($destino['id']);
        $destino = array_map('strtolower', $destino);
        return $this->importar->update('destinos', $destino, "id=$id");
    }
    
    /**
     * Borra un destino en la base
     */
    function borrar_destino($id){
        echo $this->importar->update('destinos', array('borrado'=>true), "id=".$id['id']);
    }
    
    /**
     * Lista los destinos no borrados de la base
     */
    function listar_destinos(){
        return $this->importar->get_where('destinos', 'borrado=false')->result_array();
    }
    
    /**
     * Devuelve el destino correspondiente al id pasado por parametro
     */
    function devuelve_nombre_destino($id){
        $reso = $this->importar->get_where('destinos', "id=$id")->result_array();
        return $reso[0]['destino'];
    }
        
    
    
    /**
     * Inserta una resolucion en la base
     */
    function insertar_resolucion($resolucion){
        array_walk_recursive($resolucion, function(&$value){$value=strtolower($value);});
        $this->importar->insert('resoluciones', ['resolucion' => $resolucion['resolucion']]);
        $insertId = $this->importar->insert_id();
        echo $insertId; var_dump($resolucion);
        foreach($resolucion['tamano'] as $tamano => $monto){
            $this->importar->insert('categorias_pyme', ['id_resolucion' => $insertId, 'tamano' => $tamano, 'monto' => $monto]);
        }
    }
    
    /**
     * Actualiza una resolucion en la base
     */
    function actualizar_resolucion($resolucion){
        $id=$resolucion['id'];
        
        $this->importar->update('resoluciones', ['resolucion'=>strtolower($resolucion['resolucion'])], "id=$id");
        
        $update_batch = []; 
        foreach($resolucion['tamano'] as $tamano => $monto){
            $update_batch[]=[
                'tamano' => $tamano,
                'monto' => $monto            
            ];
        }

        $this->importar->where('id_resolucion', $id);
        $result = $this->importar->update_batch('categorias_pyme', $update_batch, 'tamano'); //'id'
        var_dump($result);exit;
    }
    
    /**
     * Borra una resolucion en la base
     */
    function borrar_resolucion($id){
        echo $this->importar->update('resoluciones', array('borrado'=>true), "id=".$id['id']);
    }
    
    /**
     * Lista las resoluciones no borradas de la base
     */
    function listar_resoluciones(){
        return $this->importar->get_where('resoluciones', 'borrado=false')->result_array();
    }
    
    
    /**
     * Devuelve la resolucion correspondiente al id pasado por parametro
     */
    function devuelve_nombre_resolucion($id){
        $reso = $this->importar->get_where('resoluciones', "id=$id")->result_array();
        return $reso[0]['resolucion'];
    }
    
    
    /**
     * Lista las categorias pyme no borradas de la base
     */
    function listar_categorias_pyme(){
        return $this->importar->get_where('categorias_pyme')->result_array();
    }

    
    /**
     * Inserta un monto en la base mongo
     */
    function insertar_monto($resolucion){
        $resolucion['borrado']=false;
        return $this->dbmongo->insert('resoluciones', $resolucion);
    }
    
    /**
     * Actualiza un monto en la base mongo
     */
    function actualizar_monto($resolucion){
        $id=$resolucion['_id'];
        unset($resolucion['_id']);
        $this->dbmongo->where(array('_id'=>new MongoId($id)));
        return $this->dbmongo->update('resoluciones', $resolucion);
    }
    
    /**
     * Borra un monto en la base mongo
     */
    function borrar_monto($resolucion){
        $id=$resolucion['_id'];
        unset($resolucion['_id']);
        $this->dbmongo->where(array('_id'=>new MongoId($id)));
        return $this->dbmongo->update('resoluciones', array('borrado'=>true));
    }
    
    /**
     * Lista los montos por destino no borrados de la base mongo
     */
    function listar_montos(){
        $content = $this->dbmongo->get_where('resoluciones', array('borrado'=>false))->result_array();
        foreach($content as &$contenido){
            $reso = $contenido['resolucion'];
            $contenido['resolucion'] = array();
            $contenido['resolucion']['value']=$reso;
            $contenido['resolucion']['name'] = $this->devuelve_nombre_resolucion($reso);
            foreach($contenido['destino'] as &$destino){
                $dest=$destino;
                $destino = array();
                $destino['value'] = $dest;
                $destino['name'] = $this->devuelve_nombre_destino($dest);
            }
        }
        return $content; 
    }
    
    /**
     * Lista los sistemas de amortizacion
     */
    function listar_sis_amortizacion(){
        return $this->importar->get('bonita_sistemas')->result_array();
    }
    
    /**
     * Inserta una tabla temporal con los datos cargados
     */
    function insertar_tabla_temp_prestamos($data, $userid){
        $tb_temp_bon="tmp_bonita_".$userid;
        $this->fimportar = $this->load->dbforge($this->importar, true);
        $fields = array(
                'efi' => array('type'=>'varchar(255)'),
                'nro' => array('type'=>'varchar(255)'),
                'old_nro' => array('type'=>'varchar(255)'),
                'dispo' => array('type'=>'varchar(255)'),
                'cuit' => array('type'=>'varchar(255)'),
                'razon_social' => array('type'=>'varchar(255)'),
                'provincia' => array('type'=>'varchar(255)'),
                'partidodpto' => array('type'=>'varchar(255)'),
                'localidad' => array('type'=>'varchar(255)'),
                'municipio' => array('type'=>'varchar(255)'),
                'cp' => array('type'=>'varchar(255)'),
                'telefono' => array('type'=>'varchar(255)'),
                'email' => array('type'=>'varchar(255)'),
                'fecha_ini_actividades' => array('type'=>'date'),
                'sector' => array('type'=>'varchar(255)'),
                'codigo' => array('type'=>'varchar(255)'),
                'actividad' => array('type'=>'varchar(255)'),
                'ventas_ult_ej' => array('type'=>'varchar(100)'),
                'ventas_prom_utl3' => array('type'=>'varchar(100)'),
                'cant_emp' => array('type'=>'varchar(100)'),
                'endeudamiento_sist_finan' => array('type'=>'varchar(100)'),
                'edeudamiento_banco' => array('type'=>'varchar(100)'),
                'cap' => array('type'=>'varchar(100)'),
                'fecha_acredita' => array('type'=>'date'),
                'destino' => array('type'=>'tinytext'),
                'plazo_meses' => array('type'=>'varchar(100)'),
                'tna' => array('type'=>'varchar(100)'),
                'sistema_amort' => array('type'=>'varchar(255)'),
                'cant_cuot' => array('type'=>'varchar(50)'),
                'fecha_1er_vencimiento' => array('type'=>'date'),
                'fecha_1er_venc_interes' => array('type'=>'date'),
                'frec_int' => array('type'=>'varchar(50)'),
                'frec_cap' => array('type'=>'varchar(50)'),
                'gracia_int' => array('type'=>'varchar(50)'),
                'gracia_cap' => array('type'=>'varchar(50)'),
                'joven_empresario' => array('type'=>'set("SI","NO")'),
                'cliente_nuevo' => array('type'=>'set("SI","NO")'),
                'garantia_sgr' => array('type'=>'set("SI","NO")'),
                'sgr_involucrada' => array('type'=>'varchar(100)'),
                'garantia' => array('type'=>'varchar(100)'),
                'observaciones' => array('type'=>'text'),
                'tam_empresa' => array('type'=>'varchar(255)'),
                'puntos_bon' => array('type'=>'varchar(50)'),
                'total_bon' => array('type'=>'varchar(100)'),
                'fecha_alta' => array('type'=>'datetime')
            );
        $this->fimportar->drop_table($tb_temp_bon, true);
        $this->fimportar->add_field($fields);
        $this->fimportar->create_table($tb_temp_bon, true);
        $this->importar->insert($tb_temp_bon, $data);
    }
}





