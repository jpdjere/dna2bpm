<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Acceso a la informacion registrada en la DB dna3 para PACC1.3 a fin de guardar la informacion necesaria para ser mostrada en graficos/mapas
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 */
class Updaterdb extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->main_container = 'container.proyectos_pacc';
        $this->tmp_container = $this->main_container . '13_tmp';

        /* LOADER */
        $this->load->helper('pacc/normal_distribution');
    }

    /**
     * Update MongoDB
     * 
     * @name save_dashboard_subsec_model
     * 
     * @see Api13::recalcular_pacc11_dashboard()
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */
    function save_dashboard_subsec_model() {

        //ini_set("error_reporting", 0);
        $arr = array();
        $container = $this->main_container;
        $dbconnect = $this->load->database('dna2', $this->db);

        $tmp = array('17', '12', '14', '20', '30', '60', '120', '125', '50', '100', '480', '500');
        $query = array("status" => "activa", 5689 => array('$exists' => true));
        $rs = $this->mongowrapper->db->$container->find($query);



        foreach ($rs as $each) {


            $this->db->where('idpreg', '5689');
            $this->db->where('id', $each['id']);
            $query = $this->db->get('forms2.th_pacc');

            $estado_arr = array();
            foreach ($query->result() as $row) {
                switch ($row->valor) {

                    case '480':
                    case '500':
                        $reverso_estado = "finalizados";
                        break;

                    case '20':
                    case '30':
                    case '14':
                    case '12':
                        $reverso_estado = "presentados";
                        break;

                    case '60':
                        $reverso_estado = "pre_aprobados";
                        break;


                    case '120':
                    case '125':
                        $reverso_estado = "aprobados";
                        break;


                    case '100':
                        $reverso_estado = "rechazados";
                        break;

                    case '17':
                        $reverso_estado = "preparacion";
                        break;
                }

                if (isset($reverso_estado)) {
                    if (!in_array($reverso_estado, $estado_arr))
                        $estado_arr[] = $reverso_estado;
                }
            }

            // var_dump($estado_arr);

            /* DATOS PROYECTO */
            $id = $each['id'];


            $id_emprendedor = isset($each['8256'][0]) ? $each['8256'][0] : $each['6065'][0];

            /* DATOS EMPRENDEDOR */
            $filter = array('container' => 'container.empresas', 'id' => $id_emprendedor);
            $datos_empresa = $this->get_by_id($filter);

            $provincia = $datos_empresa[0][4651][0];

            $anr = 0;
            $arr_pagado = array();
            $desembolso = 0;
            /* DESEMBOLSO */
//            $arr_pagado = array(translate_currency($each[8867])
//                , translate_currency($each[8868])
//                , translate_currency($each[8869])
//                , translate_currency($each[8870]));
//
//            $anr = translate_currency($each[6058]);
//            $desiste = translate_currency($each[8865]);
//            $rechaza = translate_currency($each[8866]);
//
//            $sum_resta = array_sum(array($desiste, $rechaza));
//
//            $sum_pagado = array_sum($arr_pagado);
//            $desembolso = $anr - ($sum_pagado - $sum_resta);



            if (isset($each['7057'])) {
                $pay_orders = $each['7057'];

                foreach ($pay_orders as $order) {
                  
                    $filter = array('container' => 'container.orden_de_pago_pacc', 'id' => $order);
                    $datos_pago = $this->get_by_id($filter);

                    if (isset($datos_pago[0]['7052']))
                        $arr_pagado[] = translate_currency($datos_pago[0]['7048']);
                }
                
                 $desembolso = array_sum($arr_pagado);
            } 
           


            $ip_proyecto = isset($each[7356]) ? $each[7356] : $each[5691];
            $ejercicio = explode("/", $ip_proyecto);

            /* INCUBADORAS/incubadoraS */
            $id_incubadora = $each[7949][0];

            $filter_agencia = array('container' => 'container.agencias', 'id' => $id_incubadora, '4899' => 'INCUBA');
            $datos_agencia = $this->get_by_id($filter_agencia);


            $this->load->model('app');
            $option = $this->app->get_ops(58);

            /* SAVE */
            $parameter = array();
            $parameter['id'] = $id;
            $parameter['ip'] = $ip_proyecto;
            $parameter['provincia'] = $provincia;
            $parameter['estado'] = $estado_arr;
            $parameter['ejercicio'] = $ejercicio[1];
            $parameter['previsto'] = (float) $anr;
            $parameter['desembolso'] = (float) $desembolso;

            if (isset($datos_agencia[0][4897][0])) {
                $parameter['incubadora'] = (float) $id_incubadora;
                $parameter['incubadora_provincia'] = $datos_agencia[0][4897][0];
            }

            if (isset($datos_agencia[0][8102][0])) {
                $parameter['partido'] = $option[$datos_agencia[0][8102][0]];
                $parameter['partido_id'] = $datos_agencia[0][8102][0];
            }

            $rs = $this->save_tmp($parameter, $id);
          
        }
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

    function save_dashboard_tmp($parameter, $incubadora = null) {

        $container = $this->tmp_container . "_dashboard" . $incubadora;


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

        if (isset($filter['id']))
            $query['id'] = $filter['id'];

        $query_sum = array(
            array(
                '$match' => $query,
            ),
            array(
                '$group' => array(
                    '_id' => array('incubadora' => '$incubadora'
                        , 'estado' => '$estado'
                        , 'provincia' => '$provincia'
                        , 'incubadora_provincia' => '$incubadora_provincia'
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


        $query = array('id' => $filter_id);

        if (isset($filter['id']))
            $query['id'] = (float) $filter['id'];


        if (isset($filter['4899']))
            $query['4899'] = $filter['4899'];



        $rs = $this->mongowrapper->db->$container->find($query);

        foreach ($rs as $list) {
            unset($list['_id']);
            $rtn[] = $list;
        }
        return $rtn;
    }
    
    /**
     * Dashboard Subsecretaria
     * 
     * @name get_dashboard_subsec_model
     * 
     * @author Diego Otero <daotero@industria.gob.ar>
     * 
     * @date Jun 28, 2015  
     * 
     * @param type $filter
     */

    function get_dashboard_subsec_model($filter = null) {

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
     * Obtiene la informacion registrada en el sistema y arma un ranking
     * 
     * @name get_quantity_by_status
     * 
     * @see Api13::incubadoras_por_id()
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
     * Obtiene la informacion registrada en el sistema y arma un ranking por incubadora
     * 
     * @name get_quantity_by_status_incubadora
     * 
     * @see Api13::incubadoras_por_id()
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
     * Obtiene la informacion registrada en el sistema y arma un ranking por proyecto
     * 
     * @name get_quantity_by_proyect
     * 
     * @see Api13::incubadoras_por_id()
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

}
