<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Acceso a la informacion registrada en la DB dna3 para PACC1.3
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 */
class Pacc13 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->main_container = 'container.proyectos_pacc';
        $this->tmp_container = $this->main_container . '13_tmp';

        /* LOADER */
        $this->load->helper('pacc/normal_distribution');
        $this->load->library('cimongo/Cimongo.php', '', 'dna3');
        $this->dna3->switch_db('dna3');
        $this->load->model('app');
    }

    /**
     * Dashboard Subsecretaria Totales
     * 
     * @name get_dashboard_subsec_totales_model
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function get_dashboard_subsec_totales_model($filter = null) {

        $container = $this->tmp_container;

        $query = array('id' => array('$exists' => true));
        if (isset($filter['ejercicio']))
            $query['ejercicio'] = array($regex => $filter['ejercicio']);


        $query_sum = array(
            array(
                '$match' => $query,
            ),
            array(
                '$group' => array(
                    '_id' => array('provincia' => '$provincia'),
                    'cantidad' => array('$sum' => 1),
                    'desembolso' => array('$sum' => '$desembolso')
                ),
            ),
            array(
                '$sort' => array('_id' => 1),
            ),
        );

        $rs = $this->mongowrapper->dna3_tmp->$container->aggregate($query_sum);

        return $rs;
    }

    /**
     * Guarda informacion temporal para calculos del Dashboard
     * 
     * @name save_dashboard_tmp
     * 
     * @see Api13::dashboard_sub_empresas_save()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $parameter
     */
    function save_dashboard_tmp($parameter) {

        $container = $this->tmp_container . "_dashboard";

        $criteria = array();
        $update = array('$set' => $parameter);
        $options = array('upsert' => true, 'w' => 1);
        $result = $this->mongowrapper->dna3_tmp->selectCollection($container)->update($criteria, $update, $options);

        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }

        return $out;
    }

    /**
     * Obtiene informacion temporal para calculos
     * 
     * @name get_dashboard_tmp
     * 
     * @see save_dashboard_subsec_model()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function get_dashboard_tmp($var = null) {
        $rtn = array();
        $container = $this->tmp_container . "_dashboard" . $var;
        $rs = $this->mongowrapper->dna3_tmp->$container->find();

        foreach ($rs as $list) {
            unset($list['_id']);
            $rtn[] = $list;
        }
        return $rtn;
    }

    /**
     * Cantidades por proyecto
     * 
     * @name get_quantity_by_proyect
     * 
     * @see Api13::dashboard_sub_empresas_save()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function get_quantity_by_proyect($filter = null) {
        $container = $this->tmp_container;
        $query = array(
            'provincia' => $filter['provincia'],
            'desembolso' => array(
                '$ne' => 0
            )
        );


        $rs = $this->mongowrapper->dna3_tmp->$container->find($query)->count();

        return $rs;
    }

    /**
     * Obtiene informacion utilizando el CUIT como indice
     * 
     * @name get_by_cuit
     * 
     * @see buscar_empresa_model()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param array $filter {
     *     @var string  $cuit  cuit de la empresa a consultar
     *     @var string $container   nombre del container en la mongoDB database
     * }
     */
    function get_by_cuit($filter) {

        $rtn = array();
        $container = $filter['container']; //container.empresas';
        $query = array('1695' => $filter['cuit']);

        $rs = $this->mongowrapper->db->$container->find($query);

        foreach ($rs as $list) {
            unset($list['_id']);
            $rtn[] = $list;
        }
        return $rtn;
    }

    /**
     * Obtiene informacion de todos los container utilizando el id como indice
     * 
     * @name get_by_id
     * 
     * @see save_dashboard_subsec_model()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param array $filter {
     *     @var int  $id id
     *     @var string $container   nombre del container en la mongoDB database
     * }
     */
    function get_by_id($filter) {


        $rtn = array();
        $container = $filter['container']; //container.empresas';

        $float_id = (float) $filter['id'];
        $filter_id = ($float_id == 0) ? $filter['id'] : $float_id;


        $query = array('id' => $filter_id);

        $rs = $this->mongowrapper->db->$container->find($query);

        foreach ($rs as $list) {
            unset($list['_id']);
            $rtn[] = $list;
        }
        return $rtn;
    }

    /**
     * Obtener el nombre de la Incubadora asociada a un id
     * 
     * @name incubadora_nombre_model
     * 
     * @see Api13::incubadora_nombre()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $query
     */
    function incubadora_nombre_model($query, $container) {

        $rs = $this->mongowrapper->db->$container->findOne($query);
        $rtn = isset($rs['4896']) ? $rs['4896'] : $rs['id'];

        return $rtn;
    }

    /**
     * Obtiene la informacion para todas las incubadoras registradas en el sistema
     * 
     * @name get_incubadoras_model
     * 
     * @see Api13::incubadoras_listado()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $query
     */
    function get_incubadoras_model($query, $container) {
        
        $rs = $this->mongowrapper->db->$container->find($query)->sort(array(4896 => 1));
        
  
        
        foreach ($rs as $list) {
            unset($list['_id']);
            $rtn[] = $list;
        }
        return $rtn;
    }

    /**
     * Obtiene la informacion para todas las incubadoras registradas en el sistema y arma un ranking
     * 
     * @name get_ranking_incubadoras_model
     * 
     * @see Api13::incubadoras_ranking()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function get_ranking_incubadoras_model($filter = null) {

        $container = $this->tmp_container;
        //$provincia = isset($filter) ? $filter['provincia'] : 'CABA';

        $query = array('estado' => 'aprobados', 'incubadora' => array('$exists' => true));

        if (isset($filter['provincia']))
            $query['provincia'] = $filter['provincia'];


        $query_sum = array(
            array(
                '$match' => $query,
            ),
            array(
                '$group' => array(
                    '_id' => array('incubadora' => '$incubadora'
                        , 'estado' => '$estado'
                        , 'provincia' => '$provincia'
                    ),
                    'cantidad' => array('$sum' => 1)
                ),
            ),
            array(
                '$sort' => array('cantidad' => -1),
            ),
        );

        $rs = $this->mongowrapper->dna3_tmp->$container->aggregate($query_sum);

        return $rs;
    }

    /**
     * Obtiene la informacion para todas las incubadoras registradas en el sistema y armae un ranking
     * 
     * @name get_quantity_by_status_incubadora
     * 
     * @see Api13::incubadoras_ranking()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function get_quantity_by_status_incubadora($filter = null) {
        $container = $this->tmp_container;

        // var_dump($filter);

        $query_arr = array($filter['estado']);
        $query = array('provincia' => $filter['provincia'], 'incubadora' => $filter['incubadora'], 'estado' => array('$in' => $query_arr));
        $rs = $this->mongowrapper->dna3_tmp->$container->find($query)->count();

        return $rs;
    }

    /**
     * Obtiene la cantidad de desembolsos realizado para las incubadoras registradas en el sistema
     * 
     * @name incubadoras_desembolso_model
     * 
     * @see Api13::incubadoras_por_id()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function incubadoras_desembolso_model($incubadora, $provincia) {
        $container = 'container.proyectos_pacc13_tmp';
        $query = array('incubadora' => (float) $incubadora, 'provincia' => $provincia, "desembolso" => array('$ne' => 0));

        $query_sum = array(
            array(
                '$match' => $query,
            ),
            array(
                '$group' => array(
                    '_id' => array('incubadora' => '$incubadora'),
                    'desembolso' => array('$sum' => '$desembolso'),
                    'cantidad' => array('$sum' => 1)
                ),
            )
        );
        $rs = $this->mongowrapper->dna3_tmp->$container->aggregate($query_sum);
        return $rs;
    }

    /**
     * Obtiene la informacion de  las incubadoras registradas en el sistema por provincia
     * 
     * @name get_incubadoras_por_provincia_model
     * 
     * @see Api13::incubadoras_por_partido()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function get_incubadoras_por_provincia_model($filter = null) {


        $container = $this->tmp_container;
        $query = array('id' => array('$exists' => true));

        if (isset($filter['provincia']))
            $query['provincia'] = $filter['provincia'];

        if (isset($filter['incubadora']))
            $query['incubadora'] = (float) $filter['incubadora'];

        $query_sum = array(
            array(
                '$match' => $query,
            ),
            array(
                '$group' => array(
                    '_id' => array('incubadora' => '$incubadora'
                        , 'estado' => '$estado'
                        , 'provincia' => '$provincia'
                        , 'partido' => '$partido'
                        , 'partido_id' => '$partido_id'
                    ),
                    'cantidad' => array('$sum' => 1)
                ),
            ),
            array(
                '$sort' => array('cantidad' => -1),
            ),
        );



        $rs = $this->mongowrapper->dna3_tmp->$container->aggregate($query_sum);

        return $rs;
    }

    /**
     * Obtiene la informacion de  las incubadoras registradas en el sistema por proyectos presentados
     * 
     * @name incubadoras_model
     * 
     * @see Api13::incubadoras_proy_presentados()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $query 
     */
    function incubadoras_model($query, $container) {


        $query_sum = array(
            array(
                '$match' => $query,
            ),
            array(
                '$group' => array(
                    '_id' => "",
                    'cantidad' => array('$sum' => 1)
                ),
            )
        );
        $rs = $this->mongowrapper->db->$container->aggregate($query_sum);
        return $rs;
    }

    /**
     * Obtiene la informacion de  las incubadoras registradas en el sistema por proyectos
     * 
     * @name incubadoras_user_proy_model
     * 
     * @see Api13::incubadoras_facturas_aprobadas()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $query
     */
    function incubadoras_user_proy_model($query, $container) {
        //$this->dna3->select(array('id'));
        $this->dna3->where($query);
        $result = $this->dna3->get($container)->result_array();
        //$id_result= $result[0]['id'];
        return $result;
    }

    /**
     * Obtiene la informacion del usuario asociado a la incubadora registradas en el sistema
     * 
     * @name incubadoras_user_model
     * 
     * @see Api13::incubadoras_usuarios_grupo()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $query
     */
    function incubadoras_user_model($query, $container) {
        $this->dna3->select(array('id'));
        $this->dna3->where($query);
        $result = $this->dna3->get($container)->result_array();
        $id_result = $result[0]['id'];
        return $id_result;
    }

    /**
     * Buscador de Empresas
     * 
     * @name buscar_empresa_model
     * 
     * @see Api13::buscar_by_cuit()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $parameter
     */
    function buscar_empresa_model($parameter) {
        /* DATOS EMPRESA */
        $parameter = '20-35318841-1';
        $filter = array('container' => 'container.empresas', 'cuit' => $parameter);
        $datos_empresa = $this->get_by_cuit($filter);

        $id_empresa = (float) $datos_empresa[0]['id'];

        $container = $this->main_container;
        $query = array("status" => "activa", 6065 => $id_empresa);
        $rs = $this->mongowrapper->db->$container->find($query);

        $rtn = array();
        foreach ($rs as $each) {
            $rtn[] = $each;
        }

        return $rtn;
    }

    /**
     * Buscar Proyectos
     *
     * @name buscar_proyectos
     *
     * @see Api11::buscar()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function buscar_proyectos($filter = null, $page = 1, $pagesize = 10) {


        $offset = ($page - 1) * $pagesize;
        $container = $this->main_container;

        $query = array("status" => "activa");

        if (isset($filter['id']))
            $query = array("id" => (float) $filter['id']);

        if (isset($filter['ip'])) {

            $ipvalue = new MongoRegex('/' . trim($filter['ip']) . '/i');

            $query = array('$or' => array(
                    array("7356" => $ipvalue),
                    array("5691" => $ipvalue)
            ));
        }

        /* datos de la empresa 6065 */
        if (isset($filter['cuit'])) {

            $new_filter = array('container' => 'container.empresas', 'cuit' => $filter['cuit']);
            $datos_empresa = $this->get_by_cuit($new_filter);
            if (isset($datos_empresa[0]['id']))
                $query = array("6065" => (float) $datos_empresa[0]['id']);
        }       

        // $this->db->debug=true;
        $rs = $this->db->where($query)->get($container, $pagesize, $offset)->result_array();
        $rs['recordCount'] = $this->db->where($query)->count_all_results($container);


        //solicitudes pacc1pde solo para mesa de entrada

        return $rs;
    }

    /**
     * Buscador
     * 
     * @name buscar_model
     * 
     * @see Api13::buscar()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function buscar_model($filter = null) {

        $container = $this->main_container;
        //$filter['id'] = 1648188505;

        if (isset($filter['id']))
            $query = array("status" => "activa", "id" => $filter['id']);

        if (isset($filter['ip']))
            $query = array("status" => "activa", "7356" => $filter['ip']);

        //var_dump($query);

        $rs = $this->mongowrapper->db->$container->findOne($query);



        /* EVALUADOR */
        $datos_evaluador = $this->user->get_user($rs[6096][0]);

        /* DATOS EMPRESA */
        $filter = array('container' => 'container.empresas', 'id' => $rs['6065'][0]);
        $datos_empresa = $this->get_by_id($filter);



        /* DATOS INCUBADORA */
        $filter = array('container' => 'container.agencias', 'id' => $rs[6071][0]);
        $datos_incubadora = $this->get_by_id($filter);



        $representante_filter = array('container' => 'container.personas', 'id' => $datos_empresa[0][5356][0]);
        $datos_representante_empresa = $this->get_by_id($representante_filter);

        $contacto_filter = array('container' => 'container.personas', 'id' => $datos_empresa[0][1766][0]);
        $datos_contacto_empresa = $this->get_by_id($contacto_filter);


        /* OPCIONES */
        $reverso_estado = $this->app->get_ops(580);
        $reverso_provincia = $this->app->get_ops(39);
        $reverso_tipo_empresa = $this->app->get_ops(45);
        $reverso_clanae = $this->app->get_ops(597);


        /* HISTORIAL DATA */
        //$history_fecha_aprobacion = $this->get_fecha_historial(array("id" => $rs['id'], "idpreg" => '6225', 'valor' => 70));
        //$history_fecha_del_estado = $this->get_fecha_historial(array("id" => $rs['id'], "idpreg" => '6225', 'valor' => $rs[6225][0]));


        /* PORCENTUALES */
        $total_proyecto = isset($rs[6057]) ? $rs[6057] : 0;
        $total_ANR_proyecto = isset($rs[6058]) ? $rs[6058] : 0;
        $porcentual_ANR_proyecto = isset($rs[6059]) ? $rs[6059] : 0;
        $total_aporte_emprendedor = isset($rs[6061]) ? $rs[6061] : 0;



        $data = array(
            'id' => $rs['id'],
            'proyecto numero' => isset($rs[7356]) ? $rs[7356] : $rs[5691],
            'titulo proyecto' => $rs[5673],
            'empresa' => $datos_empresa[0][1693],
            'cuit_empresa' => $datos_empresa[0][1695],
            'tipo_empresa' => $reverso_tipo_empresa[$datos_empresa[0][1694][0]],
            'actividad clae empresa' => $reverso_clanae[$rs[7765]],
            'facturacion empresa' => $datos_empresa[0][4930],
            'empleados empresa' => $datos_empresa[0][1711],
            'domicilio legal empresa' => $datos_empresa[0][4913],
            'provincia empresa' => $reverso_provincia[$datos_empresa[0][4651][0]],
            'ciudad empresa' => $datos_empresa[0][1700],
            'representante_empresa' => $datos_representante_empresa[0][1784] . ", " . $datos_representante_empresa[0][1783],
            'telefono empresa' => $datos_representante_empresa[0][1785],
            'email empresa' => $datos_empresa[0][1703],
            'contacto tecnico empresa' => $datos_contacto_empresa[0][1784] . ", " . $datos_contacto_empresa[0][1783],
            'telefono contacto tecnico empresa' => $datos_contacto_empresa[0][1785],
            'email contacto tecnico empresa' => $datos_contacto_empresa[0][1786],
            'fecha aprobacion' => $rs[7526],
            'estado del proyecto' => $reverso_estado[$rs[5689][0]],
            'fecha del estado' => date_for_print($history_fecha_del_estado),
            'fecha de fin actividades' => "-",
            'evaluador' => $datos_evaluador->lastname . ", " . $datos_evaluador->name,
            'ventanilla o incubadora' => $datos_incubadora[0][4896],
            'descripcion' => $rs[6380],
            'total proyecto' => "$" . $total_proyecto,
            'total ANR proyecto' => "$" . $total_ANR_proyecto,
            'total aporte emprendedor' => "$" . $total_aporte_emprendedor,
            'porcentual ANR proyecto' => $porcentual_ANR_proyecto, //$rs[6339],
            'gastos certificacion' => $rs[6080],
            'motivo del rechazo' => $rs[6133],
            'comentarios internos' => $rs[6402],
            /* ACTIVIDAD */
            'actividad' => $this->get_actividad_info($rs[5729])
        );

        return $data;
    }

    /**
     * Fecha historica de un estado en particular
     * 
     * @name get_fecha_historial
     * 
     * @see buscar_model()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function get_fecha_historial($filter) {

        $dbconnect = $this->load->database('dna2', $this->db);

        $this->db->where('idpreg', $filter['idpreg']);
        $this->db->where('valor', $filter['valor']);
        $this->db->where('id', $filter['id']);
        $query = $this->db->get('forms2.th_pacc_1');

        $rtn = "N/A";
        foreach ($query->result() as $row) {
            $rtn = $row->fecha;
        }


        return $rtn;
    }

    /**
     * Obtiene informacion relacionada con las actividades registradas en los proyectos
     * 
     * @name get_actividad_info
     * 
     * @see buscar_model()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $idactividad
     */
    function get_actividad_info($idactividad) {

        /* OPCIONES */
        $reverso_estado_act = $this->app->get_ops(670);

        $data = array();

        foreach ($idactividad as $each) {

            $actividad_filter = array('container' => 'container.actividades_pacc_1.1', 'id' => $each);
            $result = $this->get_by_id($actividad_filter);


            $new_data = array(
                'id' => $each,
                'actividad' => $result[0][5728][0],
                'mes presentacion' => $result[0][5732],
                'descripcion' => $result[0][6321],
                'fecha vencimiento original' => $result[0][7944],
                'fecha vencimiento vigente' => $result[0][7944],
                'total actividad' => $result[0][6051],
                'ANR actividad' => $result[0][6052],
                'fecha aprobacion' => $result[0][7508],
                'estado actividad' => $reverso_estado_act[$result[0][7627][0]],
                'fecha estado' => "-",
                'tipo seguimiento' => $result[0][8912],
                'fecha seguimiento email' => $result[0][8547],
                'fecha vencimiento seguimiento email' => $result[0][8549],
                'fecha respuesta seguimiento email' => $result[0][8550],
                'respuesta seguimiento' => $result[0][8551],
                'fecha seguimiento cd' => $result[0][8311]
            );

            if ($result)
                $data[] = $new_data;
        }

        return $data;
    }

    /**
     * Informacion sobre las Incubadoras por sección electoral
     * 
     * @name get_option_info
     * 
     * @see Api13::filtrar_partidos()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $parameter
     */
    function get_option_info($idop = 58, $filter = null) {

        $ops = array();
        $idrel = null;
        $this->load->model('app');


        if (isset($filter['provincia']))
            $idrel = $filter['provincia'];


        $option = $this->mongowrapper->db->options->findOne(array('idop' => 58));
        //prepare options array
        $option['data'] = (isset($option['data'])) ? $option['data'] : array();
        $option['data'] = (isset($option['fromContainer'])) ? $this->app->get_ops_from_container($option) : $option['data'];


        foreach ($option['data'] as $thisop) {
            /* TODO optimizar */

            if ($idrel == $thisop['idrel']) {
                if (in_array($idrel, $thisop)) {
                    $ops[$thisop['value']] = $thisop['text'];
                }
            }
        }
        return $ops;
    }

    /**
     * Informacion sobre las retribuciones  por proyecto o estado
     * 
     * @name get_retribuciones_model
     * 
     * @see Api13::retribuciones_pagadas_emprendedores()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function get_retribuciones_model($filter = null) {

        $container = 'container.facturas';
        $gte_date = get_date_least_2_years();

        $query = array('id' => array('$exists' => 'true'), "status" => "activa", '6903' => array(
                '$gte' => $gte_date
            ),);

        if (isset($filter['pagadas']))
            $query['6904'] = array('$exists' => 'true');

        $query_sum = array(
            array(
                '$match' => $query,
            ),
            array(
                '$group' => array(
                    '_id' => '$6903',
                    'cantidad' => array('$sum' => 1)
                ),
            ),
            array(
                '$sort' => array('_id' => 1)
            ),
        );

        $rs = $this->mongowrapper->db->$container->aggregate($query_sum);
        return $rs;
    }

    /**
     * Informacion sobre las retribuciones  por proyecto o estado
     * 
     * @name get_cant_proyectos_reclam_cd_venc_crono_model
     * 
     * @see Api13::cant_proyectos_reclam_cd_venc_crono()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function get_cant_proyectos_reclam_cd_venc_crono_model($filter = null) {

        $container = $filter['container'];
        $gte_date = get_date_least_2_years();

        $query = array('8180' => 120, 'status' => 'activa', '8183' => array(
                '$gte' => $gte_date
            ),);

        $query_sum = array(
            array(
                '$match' => $query,
            ),
            array(
                '$group' => array(
                    '_id' => '$8183',
                    'cantidad' => array('$sum' => 1)
                ),
            ),
            array(
                '$sort' => array('_id' => 1)
            ),
        );

        $rs = $this->mongowrapper->db->$container->aggregate($query_sum);
        return $rs;
    }

    /**
     * Informacion sobre las retribuciones  por proyecto o estado
     * 
     * @name get_cant_proyectos_reclam_cd_deuda_model
     * 
     * @see Api13::cant_proyectos_reclam_cd_deuda()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function get_cant_proyectos_reclam_cd_deuda_model($filter = null) {

        $container = $filter['container'];
        $gte_date = get_date_least_2_years();

        $val = 'RECUPERO';
        $regex = new MongoRegex('/' . $val . '/i');

        $query = array('8553' => $regex, 'status' => 'activa', '8552' => array(
                '$gte' => $gte_date
            ),);

        $query_sum = array(
            array(
                '$match' => $query,
            ),
            array(
                '$group' => array(
                    '_id' => '$8552',
                    'cantidad' => array('$sum' => 1)
                ),
            ),
            array(
                '$sort' => array('_id' => 1)
            ),
        );

        $rs = $this->mongowrapper->db->$container->aggregate($query_sum);
        return $rs;
    }

}
