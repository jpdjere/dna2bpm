<?php

/**
 * Description of pacc11
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 */
class model_pacc extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;
    }

    /**
     * Proyectos Presentados
     * @param type $filter
     */
    function get_proyecto($ipstr = null) {
        list($type, $ip) = explode('-', $ipstr);
        //---PP Emprendedores
        //---PDE Empresas
        $frame = ($type == 'PP') ? '7356' : '6390';
        $ipstr=str_replace('PP-','',$ipstr);
        $this->db->where(array(
            $frame => $ip //---empresas
        ));
        return $this->db->get('container.proyectos_pacc')->result_array();
        // return $this->db->count_all_results('container.proyectos_pacc');
    }

    /**
     * Proyectos evaluados Técnicos
     * @param type $filter
     */
    function Evaluados_tecnicos($filter = null) {

    }

    /**
     * Proyectos Presentados
     * @param type $filter
     */
    function Primer_pago($filter = null) {

    }

    /**
     * Proyectos Aprobados
     * @param type $filter
     */
    function Aprobados($filter = null) {

    }

    /**
     * Proyectos pre-aprobados
     * @param type $filter
     */
    function Pre_aprobados($filter = null) {

    }

    /**
     * Proyectos Finalizados
     * @param type $filter
     */
    function Finalizados($filter = null) {

    }

    function prioridades($pconfig = null) {

        $idwfs = array();
        $rIds = array();

        foreach ($pconfig as $thisconf) {
            $idwfs[] = $thisconf['idwf'];
            $rIds[] = $thisconf['lane'];
        }

        $query = array(
            'type' => 'Lane',
            'status' => 'open',
            'interval.days' => array('$gt' => 0),
        );
        $this->db->where($query);
        //----array de los lanes que medimos
        $this->db->where_in('idwf', $idwfs);
        //$this->db->where_in('resourceId',$rIds);
        // $this->db->debug=true;
        $this->db->order_by(array('interval.days' => 'DESC'));
        return $this->db->get('tokens')->result_array();
    }

    function datos_empresa($id){

        $id_int = floatval($id);
        $query = array(
            'id' => $id_int,
        );
        //var_dump($query);
        $container = 'container.empresas';
        $result = array();
        $this->db->where($query);

        $result = $this->db->get($container)->result_array();
        //var_dump($result);
        $rtn['cuit']=(isset($result[0][1695]))? $result[0][1695]:'???';

        $rtn['nombre']=(isset($result[0][1693]))? $result[0][1693]:'???';
        return $rtn;
    }


    function buscar_actividades_pacc_1($query = null) {

        $rtn = array();
        $container = 'container.actividades_pacc_11';

        $rs = $this->mongowrapper->db->$container->find($query);

        foreach ($rs as $list) {
            unset($list['_id']);
            $rtn[] = $list;
        }


        return $rtn;
    }

    function buscar_proyectos_pacc($query = null) {

        $rtn = array();
        $container = 'container.proyectos_pacc';

        $rs = $this->mongowrapper->db->$container->find($query);

        foreach ($rs as $list) {
            unset($list['_id']);
            $rtn[] = $list;
        }



        return $rtn;
    }

    function setup_areas(){

    $this->db->delete('container.areas_pacc');
    $arr[] = array(
            'nombre_area' => "Apoyo a Nuevas Empresas",
            'nombre_parser' => "Apoyo a Nuevas Empresas",
            'id' => '2',
            );
    $arr[] = array(
            'nombre_area' => "Area Planificación",
            'nombre_parser' => "Area Planificación",
            'id' => '1',
            );
    foreach ($arr as $area){
            $this->db->insert('container.areas_pacc', $area);
    }
    $rs = $this->db->get('container.areas_pacc')->result_array();
    return $rs;
    }

    function setup_componentes(){

    $this->db->delete('container.componentes_pacc');

    $arr[] = array(
            'comp' => "1",
            'descripcion_comp' => "descripcion del componente 1",
            'idrel' => "1"
            );
    $arr[] = array(
            'comp' => "2",
            'descripcion_comp' => "descripcion del compomente 2",
            'idrel' => "2"
            );
    $arr[] = array(
            'comp' => "3",
            'descripcion_comp' => "descripcion del compomente 3",
            'idrel' => "2"
            );

    foreach ($arr as $componente){
            $this->db->insert('container.componentes_pacc', $componente);
    }
    $rs = $this->db->get('container.componentes_pacc')->result_array();
    return $rs;
    }

    function get_areas(){
        $this->db->select('nombre_area as nombre');
        $rs = $this->db->get('container.areas_pacc')->result_array();
        return $rs;
    }

    function get_componentes($id = null){
        if ($id != null){
            $this->db->where (array('idrel' => $id));
            $rs = $this->db->get('container.componentes_pacc')->result_array();
            return $rs;
        }
        else{
            $rs = $this->db->get('container.componentes_pacc')->result_array();
            return $rs;
        }
    }

    function get_subcomponentes($id = null){
        if ($id != null){
            $this->db->where (array('idrel' => $id));
            $rs = $this->db->get('container.subcomponentes_pacc')->result_array();
            return $rs;
        }
        else{
            $rs = $this->db->get('container.subcomponentes_pacc')->result_array();
            return $rs;
        }
    }

    function delete_componente($id){
        $this->db->where (array('comp' => $id));
        $rs = $this->db->delete('container.componentes_pacc');
        return $rs;
    }

    function delete_subcomponente($id){
        $this->db->where (array('scomp' => $id));
        $rs = $this->db->delete('container.subcomponentes_pacc');
        return $rs;
    }

    function add_componente($data){
        $rs = $this->db->insert('container.componentes_pacc', $data);
        return $rs;


    }

    function add_subcomponente($data){
        $rs = $this->db->insert('container.subcomponentes_pacc', $data);
        return $rs;


    }

    function setup_subcomponentes(){

    $this->db->delete('container.subcomponentes_pacc');
    $arr[] = array(
            'descripcion_scomp' => "SUBCOMPONENTE 1 DE COMPONENTE 2",
            'scomp' => '2.1',
            'idrel' => "2"
            );
    $arr[] = array(
            'descripcion_scomp' => "SUBCOMPONENTE 1 DE COMPONENTE 1",
            'scomp' => '1.1',
            'idrel' => "1"
            );
    foreach ($arr as $area){
            $this->db->insert('container.subcomponentes_pacc', $area);
    }
    $rs = $this->db->get('container.subcomponentes_pacc')->result_array();
    return $rs;
    }

    function buscar_proyectos($type = null, $query = null) {

        $filter = array(
            'idwf' => 'pacc1PDE',
            'resourceId' => 'oryx_0962BF68-BBCD-470D-A307-C4453AFA4FBA'
        );
        $data ['querystring'] = $query;
        // -----busco en el cuit
        $filter ['$or'] [] = array(
            'data.1695' => array(
                '$regex' => new MongoRegex('/' . $query . '/i')
            )
        );
        // -----busco en el nombre empresa
        $filter ['$or'] [] = array(
            'data.1693' => array(
                '$regex' => new MongoRegex('/' . $query . '/i')
            )
        );
        // -----busco en el nro proyecto
        $filter ['$or'] [] = array(
            'data.6390' => array(
                '$regex' => new MongoRegex('/' . $query . '/i')
            )
        );
        $filter ['$or'] [] = array(
            'case' => array(
                '$regex' => new MongoRegex('/' . $query . '/i')
            )
        );
        $tokens = $this->bpm->get_tokens_byFilter($filter, array(
            'case',
            'data',
            'checkdate'
                ), array(
            'checkdate' => false
        ));
        return $tokens;
    }
    /**
     * Returns a ranking of tasks by user
     *
     */
    function ranking_group($idgroup,$idwfs=array()){
        $rs= $this->user->getbygroup($idgroup);
        $users=array_map(function($user){
            return $user['idu'];
        },$rs);
        $match= array('$match'=>array(
                    'iduser'=>array('$in'=>$users),
                    'type'=>'Task',

                    )
                );
        if(count($idwfs))
        $match['$match']['idwf']=array('$in'=>$idwfs);
        $query=array(
           $match,
            array('$group'=>array(
                '_id'=>'$iduser',
                'qtty'=>array('$sum'=>1)
                )),
            array('$sort'=>array('qtty'=>-1))
            );
        $rs=$this->mongowrapper->db->tokens->aggregate($query);
        return $rs;
    }
}
