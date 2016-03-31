<?php

/**
 *  Acceso a la informacion registrada en la DB dna3 para PACC1.1
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 */
class pacc11 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->main_container = 'container.proyectos_pacc';
        $this->tmp_container = $this->main_container . '_tmp';
        /* LOADER */
        $this->load->helper('pacc/normal_distribution');
        $this->load->model('app');
    }

    /**
     * Proyectos Presentados
     * @param type $filter
     */
    function Presentados($filter = null) {
        
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

    /**
     * Buscar Auditorias
     *
     * @name buscar_auditorias
     *
     * @see Api11::cant_auditorias_pde_realizadas()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $query
     */
    function buscar_auditorias($query = null, $fields = null) {
        $rtn = array();
        $container = $this->main_container;

        $rs = $this->mongowrapper->db->$container->find($query, $fields);

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

        if (isset($filter['id']))
            $query = array("status" => "activa", "id" => (float) $filter['id']);

//        if (isset($filter['ip']))
//            $query = array("status" => "activa","6390" => new MongoRegex('/' . trim($filter['ip']) . '/i'));
        
        if (isset($filter['ip']))
            $query = array("6390" => new MongoRegex('/' . trim($filter['ip']) . '/i'));
        

        /* datos de la empresa 6223 */
        if (isset($filter['cuit'])) {

            $new_filter = array('container' => 'container.empresas', 'cuit' => $filter['cuit']);
            $datos_empresa = $this->get_by_cuit($new_filter);
            if (isset($datos_empresa[0]['id']))
                $query = array("status" => "activa", "6223" => (float) $datos_empresa[0]['id']);
        }

        // $this->db->debug=true;
        $rs = $this->db->where($query)->get($container, $pagesize, $offset)->result_array();
        $rs['recordCount'] = $this->db->where($query)->count_all_results($container);       
        
        //solicitudes pacc1pde solo para mesa de entrada
        
        return $rs;
    }

    /**
     * Buscar
     *
     * @name buscar_model
     *
     * @see Api11::buscar()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function buscar_model_ori($filter = null) {

        $container = $this->main_container;


        /* OPCIONES */
        $reverso_estado = $this->app->get_ops(648);
        $reverso_provincia = $this->app->get_ops(39);
        $reverso_tipo_empresa = $this->app->get_ops(45);
        $reverso_clanae = $this->app->get_ops(597);


        //$filter['id'] = 1312856497;



        if (isset($filter['id']))
            $query = array("status" => "activa", "id" => (float) $filter['id']);

        if (isset($filter['ip']))
            $query = array("status" => "activa", "6390" => trim($filter['ip']));

        $rs = $this->mongowrapper->db->$container->findOne($query);

        /* EVALUADOR */
        $datos_evaluador = $this->user->get_user($rs[6404][0]);

        /* DATOS EMPRESA */
        $filter = array('container' => 'container.empresas', 'id' => $rs[6223][0]);
        $datos_empresa = $this->get_by_id($filter);

        $clae = isset($rs[7366]) ? $reverso_clanae[$rs[7366]] : $rs[7292];

        /* DATOS FORMULADOR */
        $filter = array('container' => 'container.empresas', 'id' => $rs['6394'][0]);
        $datos_formulador = $this->get_by_id($filter);


        /* DATOS INCUBADORA */
        $filter = array('container' => 'container.agencias', 'id' => $rs[6380][0]);
        $datos_incubadora = $this->get_by_id($filter);


        $representante_filter = array('container' => 'container.personas', 'id' => $datos_empresa[0][1766][0]);
        $datos_representante_empresa = $this->get_by_id($representante_filter);



        /* HISTORIAL DATA */
        $history_fecha_aprobacion = $this->get_fecha_historial(array("id" => $id, "idpreg" => '6225', 'valor' => 70));
        $history_fecha_del_estado = $this->get_fecha_historial(array("id" => $id, "idpreg" => '6225', 'valor' => $rs[6225][0]));


        /* PORCENTUALES */
        $total_proyecto = isset($rs[6267]) ? $rs[6267] : 0;
        $total_ANR_proyecto = isset($rs[6383]) ? $rs[6383] : 0;
        $porcentual_ANR_proyecto = isset($rs[7250]) ? $rs[7250] : 0;
        $aporte_empresa = isset($rs[6358]) ? $rs[6358] : 0;
        $gasto_certificacion = isset($rs[6271]) ? $rs[6271] : 0;


        /* Data */
        $ip = isset($rs[6390]) ? $rs[6390] : $rs[5691];

        $data = array(
            'id' => $rs['id'],
            'proyecto numero' => $ip,
            'titulo proyecto' => $rs[5673],
            'empresa' => $datos_empresa[0][1693],
            'cuit_empresa' => $datos_empresa[0][1695],
            'tipo_empresa' => $reverso_tipo_empresa[$datos_empresa[0][1694][0]],
            'actividad clae empresa' => $clae,
            'facturacion empresa' => $datos_empresa[0][4930],
            'empleados empresa' => $datos_empresa[0][1711],
            'domicilio legal empresa' => $datos_empresa[0][4913],
            'provincia empresa' => $reverso_provincia[$datos_empresa[0][4651][0]],
            'ciudad empresa' => $datos_empresa[0][1700],
            'representante_empresa' => $datos_representante_empresa[0][1784] . ", " . $datos_representante_empresa[0][1783],
            'telefono empresa' => $datos_representante_empresa[0][1785],
            'email empresa' => $datos_empresa[0][1703],
            'contacto tecnico empresa' => "-",
            'telefono contacto tecnico empresa' => "-",
            'email contacto tecnico empresa' => "-",
            'fecha aprobacion' => $rs[6536], //date_for_print($history_fecha_aprobacion),
            'estado del proyecto' => $reverso_estado[$rs[6225][0]],
            'fecha del estado' => date_for_print($history_fecha_del_estado),
            'fecha de fin actividades' => "-",
            'evaluador' => $datos_evaluador->lastname . ", " . $datos_evaluador->name,
            'ventanilla o incubadora' => $datos_incubadora[0][4896],
            'descripcion' => $rs[6380],
            'total proyecto' => "$" . $total_proyecto,
            'total ANR proyecto' => "$" . $total_ANR_proyecto,
            'total aporte emprendedor' => "$" . $aporte_empresa,
            'porcentual ANR proyecto' => $porcentual_ANR_proyecto, //$rs[6339],
            'gastos certificacion' => $gasto_certificacion,
            'motivo del rechazo' => $rs[6421],
            'comentarios internos' => $rs[6407],
            /* ACTIVIDAD */
            'actividad' => $this->get_actividad_info($rs[6243])
        );

        return $data;
    }

    /**
     * Buscar
     *
     * @name buscar_model
     *
     * @see Api11::buscar()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function buscar_model($filter = null) {

        $query = array();
        $container = $this->main_container;


        /* OPCIONES */
        $reverso_estado = $this->app->get_ops(648);
        $reverso_provincia = $this->app->get_ops(39);
        $reverso_tipo_empresa = $this->app->get_ops(45);
        $reverso_clanae = $this->app->get_ops(597);


        //$filter['id'] = 1312856497;


        $rs = array();
        if (isset($filter['id']))
            $query = array("status" => "activa", "id" => (float) $filter['id']);

        if (isset($filter['ip']))
            $query = array("status" => "activa", "6390" => trim($filter['ip']));

        $rs = $this->mongowrapper->db->$container->findOne($query);


        $datos_evaluador = isset($rs[6404][0]) ? $this->user->get_user($rs[6404][0]) : 0;

        /* DATOS EMPRESA */
        $id_empresa = isset($rs[6223][0]) ? $rs[6223][0] : 0;
        $filter = array('container' => 'container.empresas', 'id' => $id_empresa);

        $datos_empresa = $this->get_by_id($filter);

        $clae = isset($rs[7366]) ? $reverso_clanae[$rs[7366]] : $rs[7292];

        /* DATOS FORMULADOR */
        $id_formulador = isset($rs['6394'][0]) ? $rs['6394'][0] : 0;
        $filter = array('container' => 'container.empresas', 'id' => $id_formulador);
        $datos_formulador = $this->get_by_id($filter);


        /* DATOS INCUBADORA */
        $id_incuba = isset($rs[6380][0]) ? $rs[6380][0] : 0;
        $filter = array('container' => 'container.agencias', 'id' => $id_incuba);
        $datos_incubadora = $this->get_by_id($filter);

        $id_representante = isset($datos_empresa[0][1766][0]) ? $datos_empresa[0][1766][0] : 0;
        $representante_filter = array('container' => 'container.personas', 'id' => $id_representante);
        $datos_representante_empresa = $this->get_by_id($representante_filter);



        /* HISTORIAL DATA */
        $h_valor_estado = isset($rs[6225][0]) ? $rs[6225][0] : 0;
        $history_fecha_aprobacion = $this->get_fecha_historial(array("id" => $filter['id'], "idpreg" => '6225', 'valor' => 70));
        $history_fecha_del_estado = $this->get_fecha_historial(array("id" => $filter['id'], "idpreg" => '6225', 'valor' => $h_valor_estado));


        /* PORCENTUALES */
        $total_proyecto = isset($rs[6267]) ? $rs[6267] : 0;
        $total_ANR_proyecto = isset($rs[6383]) ? $rs[6383] : 0;
        $porcentual_ANR_proyecto = isset($rs[7250]) ? $rs[7250] : 0;
        $aporte_empresa = isset($rs[6358]) ? $rs[6358] : 0;
        $gasto_certificacion = isset($rs[6271]) ? $rs[6271] : 0;


        /* Data */
        $ip = isset($rs[6390]) ? $rs[6390] : $rs[5691];
        $id_actividad = isset($rs[6243]) ? $this->get_actividad_info($rs[6243]) : 0;


        $empresa = isset($datos_empresa[0][1693]) ? $datos_empresa[0][1693] : null;
        $cuit_empresa = isset($datos_empresa[0][1695]) ? $datos_empresa[0][1695] : null;
        $tipo_empresa = isset($datos_empresa[0][1694][0]) ? $reverso_tipo_empresa[$datos_empresa[0][1694][0]] : "N/A";
        $facturacion_empresa = isset($datos_empresa[0][4930]) ? $datos_empresa[0][4930] : "N/A";
        $empleados_empresa = isset($datos_empresa[0][1711]) ? $datos_empresa[0][1711] : "N/A";
        $domicilio_legal_empresa = isset($datos_empresa[0][4913]) ? $datos_empresa[0][4913] : "N/A";

        $provincia_empresa = isset($datos_empresa[0][4651][0]) ? $reverso_provincia[$datos_empresa[0][4651][0]] : "N/A";
        $ciudad_empresa = isset($datos_empresa[0][1700]) ? $datos_empresa[0][1700] : "N/A";


        $titulo_proyecto = isset($rs[5673]) ? $rs[5673] : "N/A";
        $representante_empresa_nombre = isset($datos_representante_empresa[0][1784]) ? $datos_representante_empresa[0][1784] : "N/A";
        $representante_empresa_apellido = isset($datos_representante_empresa[0][1783]) ? $datos_representante_empresa[0][1783] : "N/A";
        $telefono_empresa = isset($datos_representante_empresa[0][1785]) ? $datos_representante_empresa[0][1785] : "N/A";
        $email_empresa = isset($datos_empresa[0][1703]) ? $datos_empresa[0][1703] : "N/A";

        $fecha_aprobacion = isset($rs[6536]) ? $rs[6536] : "N/A";
        $estado_del_proyecto = isset($rs[6225][0]) ? $reverso_estado[$rs[6225][0]] : "N/A";
        $ventanilla_o_incubadora = isset($datos_incubadora[0][4896]) ? $datos_incubadora[0][4896] : "N/A";
        $descripcion = isset($rs[6380]) ? $rs[6380] : "N/A";

        $motivo_del_rechazo = isset($rs[6421]) ? $rs[6421] : "N/A";
        $comentarios_internos = isset($rs[6407]) ? $rs[6407] : "N/A";

        /* Evaluador */
        $evaluador_lastname = isset($datos_evaluador->lastname) ? $datos_evaluador->lastname : "N/A";
        $evaluador_name = isset($datos_evaluador->name) ? $datos_evaluador->name : "N/A";

        $data = array(
            'id' => $rs['id'],
            'proyecto numero' => $ip,
            'titulo proyecto' => $titulo_proyecto,
            'empresa' => $empresa,
            'cuit_empresa' => $cuit_empresa,
            'tipo_empresa' => $tipo_empresa,
            'actividad clae empresa' => $clae,
            'facturacion empresa' => $facturacion_empresa,
            'empleados empresa' => $empleados_empresa,
            'domicilio legal empresa' => $domicilio_legal_empresa,
            'provincia empresa' => $provincia_empresa,
            'ciudad empresa' => $ciudad_empresa,
            'representante_empresa' => $representante_empresa_nombre . ", " . $representante_empresa_apellido,
            'telefono empresa' => $telefono_empresa,
            'email empresa' => $email_empresa,
            'contacto tecnico empresa' => "-",
            'telefono contacto tecnico empresa' => "-",
            'email contacto tecnico empresa' => "-",
            'fecha aprobacion' => $fecha_aprobacion, //date_for_print($history_fecha_aprobacion),
            'estado del proyecto' => $estado_del_proyecto,
            'fecha del estado' => date_for_print($history_fecha_del_estado),
            'fecha de fin actividades' => "-",
            'evaluador' => $evaluador_lastname . ", " . $evaluador_name,
            'ventanilla o incubadora' => $ventanilla_o_incubadora,
            'descripcion' => $descripcion,
            'total proyecto' => "$" . $total_proyecto,
            'total ANR proyecto' => "$" . $total_ANR_proyecto,
            'total aporte emprendedor' => "$" . $aporte_empresa,
            'porcentual ANR proyecto' => $porcentual_ANR_proyecto, //$rs[6339],
            'gastos certificacion' => $gasto_certificacion,
            'motivo del rechazo' => $motivo_del_rechazo,
            'comentarios internos' => $comentarios_internos,
            /* ACTIVIDAD */
            'actividad' => $id_actividad
        );

        return $data;
    }

    /**
     * Historial
     *
     * @name get_fecha_historial
     *
     * @see Buscar()
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

        $rtn = null;
        foreach ($query->result() as $row) {
            $rtn = $row->fecha;
        }


        return $rtn;
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
     * Datos sobre la actividad
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


            $tipo_seguimiento = "N/A";

            if (isset($result[0][8311])) {
                $tipo_seguimiento = "CD";
            } else if (isset($result[0][8307])) {
                $tipo_seguimiento = "MAIL";
            }


            $new_data = array(
                'id' => $each,
                'actividad' => $result[0][6239][0],
                'mes presentacion' => $result[0][6823],
                'descripcion' => $result[0][6321],
                'fecha vencimiento original' => $result[0][6558],
                'fecha vencimiento vigente' => $result[0][6558],
                'total actividad' => $result[0][6336],
                'ANR actividad' => $result[0][6337],
                'fecha aprobacion' => $result[6627],
                'estado actividad' => $reverso_estado_act[$result[0][6638][0]],
                'fecha estado' => "-",
                'tipo seguimiento' => $tipo_seguimiento,
                'fecha seguimiento email' => $result[0][8307],
                'fecha vencimiento seguimiento email' => $result[0][8315],
                'fecha respuesta seguimiento email' => $result[0][8309],
                'respuesta seguimiento' => $result[0][8310],
                'fecha seguimiento cd' => $result[0][8311]
            );

            if ($result)
                $data[] = $new_data;
        }

        return $data;
    }

    /**
     * Desembolsos_previstos_vs_ejecutados
     *
     * @name get_desembolsos_previstos_vs_ejecutados_model
     *
     * @see Api11::desembolsos_previstos_vs_ejecutados()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function get_desembolsos_previstos_vs_ejecutados_model($filter = null) {
        //ini_set("error_reporting", E_ALL);

        $container = $this->tmp_container;
        $fields = array("ip", "desembolso", "previsto");
        $query = array();

        /* FOR TEST */
        $fecha_desde = isset($filter['fecha_desde']) ? $filter['fecha_desde'] : '2015-01-01';
        $fecha_hasta = isset($filter['fecha_hasta']) ? $filter['fecha_hasta'] : '2015-08-01';


        $query['ejercicio'] = array('$gte' => $fecha_desde, '$lte' => $fecha_hasta);



        $rs = $this->mongowrapper->dna3_tmp->$container->find($query, $fields);


        $rtn = array();
        foreach ($rs as $each) {
            $new_rtn = array();
            unset($each['_id']);
            $new_rtn['ip'] = $each['ip'];
            $new_rtn['previsto'] = $each['previsto'];
            $new_rtn['desembolso'] = $each['desembolso'];
            $new_rtn['diferencia'] = $each['previsto'] - $each['desembolso'];
            $rtn[] = $new_rtn;
        }

        return $rtn;
    }

    /**
     * Dashboard Subsecretaria
     *
     * @name get_dashboard_subsec_model
     *
     * @see Api11::dashboard_sub_empresas_save()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function get_dashboard_subsec_model($filter = null) {
        //ini_set("error_reporting", E_ALL);

        $container = $this->tmp_container;
        $query = array('id' => array('$exists' => 'true'));


        $query_sum = array(
            array(
                '$match' => $query,
            ),
            array(
                '$group' => array(
                    '_id' => array('provincia' => '$provincia', 'estado' => '$estado'),
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
        //ini_set("error_reporting", E_ALL);

        $container = $this->tmp_container;
        $query = array('id' => array('$exists' => 'true'));


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
     * Cantidades por Estado de proyectos
     *
     * @name get_quantity_by_status
     *
     * @see Api11::dashboard_sub_empresas_save()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function get_quantity_by_status($filter = null) {
        $container = $this->tmp_container;

        // var_dump($filter);

        $query_arr = array($filter['estado']);
        $query = array('provincia' => $filter['provincia'], 'estado' => array('$in' => $query_arr));
        $rs = $this->mongowrapper->dna3_tmp->$container->find($query)->count();

        return $rs;
    }

    /**
     * Cantidades por proyecto
     *
     * @name get_quantity_by_proyect
     *
     * @see Api11::dashboard_sub_empresas_save()
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
     * Update MongoDB
     *
     * @name save_dashboard_subsec_model
     *
     * @see Api11::recalcular_pacc11_dashboard()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function save_dashboard_subsec_model() {
        $arr = array();
        $container = $this->main_container;
        $dbconnect = $this->load->database('dna2', $this->db);



        $tmp = array('20', '60', '120', '125', '50', '100', '480', '500');
        $query = array("status" => "activa", 6225 => array('$in' => $tmp));
        //$query = array("status" => "activa");
        $rs = $this->mongowrapper->db->$container->find($query);


        foreach ($rs as $each) {


            $this->db->where('idpreg', '6225');
            $this->db->where('id', $each['id']);
            $query = $this->db->get('forms2.th_pacc_1');


            $estado_arr = array();
            foreach ($query->result() as $row) {

                // var_dump($row->id, $row->valor);
                $reverso_estado = null;
                switch ($row->valor) {

                    case '480':
                    case '500':
                        $reverso_estado = "finalizados";
                        break;

                    case '20':
                        $reverso_estado = "presentados";
                        break;

                    case '60':
                        $reverso_estado = "pre_aprobados";
                        break;


                    case '120':
                    case '125':
                        $reverso_estado = "aprobados";
                        break;

                    case '50':
                    case '100':
                        $reverso_estado = "rechazados";
                        break;
                }
                if (isset($reverso_estado)) {
                    if (!in_array($reverso_estado, $estado_arr))
                        $estado_arr[] = $reverso_estado;
                    // $estado_arr = array_unique($estado_arr);
                }
            }



            //var_dump($estado_arr);

            /* DATOS PROYECTO */
            $id = $each['id'];

            //6385 		Total Proyecto
            $ejercicio = $each[6534];

            /* DATOS EMPRESA */
            $filter = array('container' => 'container.empresas', 'id' => $each['6223'][0]);
            $datos_empresa = $this->get_by_id($filter);
            $provincia = $datos_empresa[0][4651][0];

            /* DESEMBOLSO */
            $desemboldo_subform_id = $each[6243];
            $desembolso = $this->desembolsos($desemboldo_subform_id);

            $desembolso = (isset($desembolso)) ? $desembolso : 0;



            $ip_proyecto = $each[6390];
            $anr = translate_currency($each[6385]);


            /* SAVE */
            $parameter = array();
            $parameter['id'] = $id;
            $parameter['ip'] = $ip_proyecto;
            $parameter['provincia'] = $provincia;
            $parameter['estado'] = $estado_arr;
            $parameter['ejercicio'] = $ejercicio;
            $parameter['previsto'] = (float) $anr;
            $parameter['desembolso'] = (float) $desembolso;

            //var_dump($parameter);

            $this->save_tmp($parameter, $id);
        }
    }

    /**
     * Obtiene informacion temporal para calculos del Dashboard
     *
     * @name get_dashboard_tmp
     *
     * @see Api11::dashboard_sub_empresas()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     */
    function get_dashboard_tmp() {
        $rtn = array();
        $container = $this->tmp_container . "_dashboard";
        $rs = $this->mongowrapper->dna3_tmp->$container->find();

        foreach ($rs as $list) {
            unset($list['_id']);
            $rtn[] = $list;
        }
        return $rtn;
    }

    /**
     * Guarda informacion temporal para calculos del Dashboard
     *
     * @name save_dashboard_tmp
     *
     * @see Api11::dashboard_sub_empresas_save()
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
     * Guarda informacion temporal para calculos
     *
     * @name save_tmp
     *
     * @see save_dashboard_subsec_model()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $parameter
     */
    function save_tmp($parameter) {

        $container = $this->tmp_container;

        $criteria = array('id' => (float) $parameter['id']);
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
        $query = array('id' => (float) $filter['id']);
        $rs = $this->mongowrapper->db->$container->find($query);

        foreach ($rs as $list) {
            unset($list['_id']);
            $rtn[] = $list;
        }

        return $rtn;
    }

    /**
     * Desembolsos
     *
     * @name desembolsos
     *
     * @see save_dashboard_subsec_model()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $proyecto_id
     */
    function desembolsos($proyecto_id) {

        $container = "container.actividades_pacc_11";

        $rtn = array();

        foreach ($proyecto_id as $each) {
            $query = array('id' => $each);
            $rs = $this->mongowrapper->db->$container->find($query);

            $total = array();

            foreach ($rs as $list) {
                if ($list[6842])
                    $parcial[] = translate_currency($list[6846]);
            }
        }

        $totales = array_sum($parcial);
        return $totales;
    }

    /**
     * Desembolsos Previstos
     *
     * @name desembolsos_previstos
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $proyecto_id
     */
    function desembolsos_previstos($proyecto_id) {

        $container = "container.actividades_pacc_11";

        $rtn = array();

        foreach ($proyecto_id as $each) {
            $query = array('id' => $each);
            $rs = $this->mongowrapper->db->$container->find($query);

            $total = array();

            foreach ($rs as $list) {
                if ($list[6842])
                    $parcial[] = translate_currency($list[6846]);
            }
        }

        $totales = array_sum($parcial);
        return $totales;
    }

    /**
     * Retribuciones
     *
     * @name get_retribuciones_model
     *
     * @see Api11::retribuciones_pagadas_empresas()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Jun 28, 2015
     *
     * @param type $filter
     */
    function get_retribuciones_model($filter = null) {

        $container = 'container.orden_de_pago_pacc';
        $gte_date = get_date_least_2_years();

        $query = array('id' => array('$exists' => 'true'), "status" => "activa", '7043' => array(
                '$gte' => $gte_date
            ),);

        if (isset($filter['pagadas']))
            $query['7517'] = array('$exists' => 'true');

        $query_sum = array(
            array(
                '$match' => $query,
            ),
            array(
                '$group' => array(
                    '_id' => '$7043',
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
