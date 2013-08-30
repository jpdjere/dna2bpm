<?php

/**
 * @class genia
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Genias_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->idu = (int) $this->session->userdata('iduser');
        /* Set locale to Spansih */
    }

    // ======= TAREAS ======= //

    function remove_task($id) {
        $container = 'container.genias_tasks';
        $query = array('id' => (integer) $id);
        $rs = $this->mongo->db->$container->remove($query);
        return $rs['err'];
    }

    function add_task($task) {
        $options = array('upsert' => true, 'safe' => true);
        $container = 'container.genias_tasks';
        $query = array('id' => (integer) $task['id']);
        $rs = $this->mongo->db->$container->update($query, $task, $options);
        return $rs['err'];
    }

    function get_tasks($idu, $proyecto) {

        //$query = array('idu' => (int) $idu, 'proyecto' => $proyecto);

        $container = 'container.genias_tasks';
        $genias = $this->get_genia($idu);
        $idus = array($idu);
        if ($genias !== false) { // 
            foreach ($genias['genias'] as $genia) {
                if ($genias['rol'] == 'coordinador') {
                    //$query = array('idu' => array('$in'=>$genia['users']),'idu' => (double) $idu);
                    $idus = array_merge($genia['users'], $idus);
                }
            }
        }
        $query = array('idu' => array('$in' => $idus), 'proyecto' => $proyecto);


        $result = $this->mongo->db->$container->find($query)->sort(array('$natural' => -1));

        //var_dump($result, json_encode($result), $result->count());
        return $result;
    }

    // ======= METAS ======= //

    function add_goal($goal) {
        $container = 'container.genias';
        $id = new MongoId($goal['genia']);
        $query = array('_id' => $id);
        $mygenia = $this->mongo->db->$container->findOne($query);
        $goal['genia_nombre'] = $mygenia['nombre'];
        $options = array('upsert' => true, 'safe' => true);
        $container = 'container.genias_goals';
        $query = array('id' => (integer) $goal['id']);
        return $this->mongo->db->$container->update($query, $goal, $options);
    }

    function update_goal() {

        $container = 'container.genias_goals';
        $data = $this->input->post('data');

        $date = date_create_from_format('d-m-Y', '01-' . $data['desde']);
        $month = $date->format('m');
        $year = $date->format('Y');
        $daycount = cal_days_in_month(CAL_GREGORIAN, $month, $year); // 31

        $data['desde'] = "$year-$month-01";
        $data['hasta'] = "$year-$month-$daycount";


        $id = new MongoId($data['metaid']);
        $query = array('_id' => $id);
        $options = array('safe' => true);

        return $this->mongo->db->$container->update($query, array('$set' => $data), $options);
    }

    function get_goals($idu) {
        $container = 'container.genias_goals';
        $this->lang->load('calendar', $this->config->item('languaje'));

        $genias = $this->get_genia($idu);
        $lista_genias = array();

        if ($genias !== false) { // 
            foreach ($genias['genias'] as $genia) {
                $lista_genias[] = (string) $genia['_id'];
            }
        }
        if ($genias['rol'] == 'coordinador') {
            // Coordinador
            $query = array('genia' => array('$in' => $lista_genias));
        } else {
            $query = array('idu' => $idu);
        }
        $goals = $this->mongo->db->$container->find($query)->sort(array('desde' => -1));
        $result = array();
        while ($mygoals = $goals->getnext()) {
            // Mes 
            $date = date_create_from_format('Y-m-d', $mygoals['desde']);
            $mes = 'cal_' . strtolower(date_format($date, 'F'));
            $year = date_format($date, 'Y');
            $mygoals['desde'] = $this->lang->line($mes) . " ,$year";
            $mygoals['desde_raw'] = date_format($date, 'm-Y');
            $result[] = $mygoals;
        }

        return $result;
    }

    function get_case($case) {
        $query = array('id' => $case);
        $container = 'case';
        $result = $this->mongo->db->$container->findOne($query);
        return $result;
    }

    // ======= CONFIG ======= //

    function get_config_item($name) {
        $container = 'container.genias_config';
        $query = array('name' => $name);
        $result = $this->mongo->db->$container->findOne($query);
        return $result;
    }

    function config_set($data) {
        $container = 'container.genias_config';
        $options = array('upsert' => true, 'safe' => true);
        $query = array('name' => 'projects');
        $rs = $this->mongo->db->$container->update($query, $data, $options);
        return $rs['err'];
    }

    /* RETURN EMPRESAS */

    function get_empresas($query) {
        $rtn = array();
        //$query['status'] = 'activa';
        $fields = array('id',
            'status'
            , '1693'  //     Nombre de la empresa
            , '1695'  //     CUIT
            , '7819' // 	Longitud
            , '7820' // 	Latitud
            , '4651' // 	Provincia
            , '4653' //     Calle Ruta
            , '4654' //     Nro /km
            , '4655' //     Piso
            , '4656' //     Dto Oficina
            , '1699' // 	Partido
            , '1694'    // Tipo de empresa
            , '1698'    //cod postal
            , '1701'    //telefonos
            , '1703'    //email
            , '1704'    //web
            , '1711'    //Cantidad de Empleados actual  
            /* contacto */
            , '7876'    // Apellido y Nombre del Contacto
            , '7877'    // E-mail del Contacto
            , '7878'    // Rubro de la Empresa                
            /* PLANTA */
            , '7879'    // Superficie Cubierta
            , '7880'    // Posesi�n (idopcion = 729)
            , '1715'    // Productos o servicios que Ofrece
            /* PRODUCCION */
            , '7881'    // Tiene componentes importados (idopcion = 15)
            , '7882'    // Pueden ser reemplazados? (idopcion = 15)
            , '7883'    // Tiene capacidad para exportar? (idopcion = 15)
            , '1716'    // Mercado destino (idopcion = 88)
            , '7884'    // Proveedores
            , 'C7663'    // La empresa ha realizado o realiza acciones vinculadas a la Responsabilidad Social (idopcion = 716)
            , '7665'    // Registro �nico de Organizaciones de Responsabilidad Social (idopcion = 715)
            , 'origenGenia'    // usuario que tocó la empresa
        );
        $container = 'container.empresas';
        $sort = array('origenGenia' => -1);
        $result = $this->mongo->db->$container->find($query, $fields);
        $result->limit(2500)->sort($sort);
        foreach ($result as $empresa) {
            unset($empresa['_id']);
            $rtn[] = $empresa;
        }
        return $rtn;
    }

    /* RETURN VISITAS */

    function get_visitas($query, $idu) {
        $rtn = array();
        $query['idu'] = (int) $idu;
        $fields = array('id'
            , 'fecha'           // 	Fecha de la Visita 
            , 'cuit'            //      CUIT
            , 'nota'            // 	Comentarios 
            , 'tipovisita'      //      tipo de visita
            , 'otros'           //      para tipo de visita otros
            , '7898'            //      Programas Informados           
        );
        $container = 'container.genias_visitas';
        $result = $this->mongo->db->$container->find($query, $fields);
        $result->limit(2000);
        foreach ($result as $visita) {
            unset($visita['_id']);
            //unset($visita['id']);
            $rtn[] = $visita;
        }
        return $rtn;
    }

    function get_visitas_all() {
        $rtn = array();
        $container = 'container.genias_visitas';
        $result = $this->mongo->db->$container->find();
        $result->limit(2000);
        foreach ($result as $visita) {
            unset($visita['_id']);
            //unset($visita['id']);
            $rtn[] = $visita;
        }
        return $rtn;
    }

    function visitas_remove($container, $id = null) {
        $query = array(
            'id' => $id
        );
        $visita = $this->mongo->db->$container->remove($query);

        if ($visita) {
            $newQuery = array(
                'cumplidas' => $id
            );
            $newContainer = 'container.genias_goals';
            $result = $this->mongo->db->$newContainer->findOne($newQuery);

            $arrCheck = $result['cumplidas'];
            $resultArr = @array_diff($arrCheck, array($id));

            if (!empty($resultArr)) {
                foreach ($resultArr as $key => $value) {
                    $newArr[] = $value;
                }

                $idQuery = array('id' => $result['id']);
                $update = array('$set' => array('cumplidas' => $newArr));
                $options = array('multi' => false, 'upsert' => false);
                $rs = $this->mongo->db->$newContainer->update($idQuery, $update, $options);

                return $rs;
                exit();
            }
        }
    }

    function array_delete($value, $array) {
        $array = array_diff($array, array($value));
        return $array;
    }

    /* RETURN ENCUESTAS */

    function get_encuestas($query, $idu) {
        $rtn = array();
        $query['idu'] = (int) $idu;
        $fields = array('id'
            , 'fecha'       // 	Fecha de la Visita 
            , 'cuit'        //      CUIT
            , '7663'        // 	Ha realizado/a acciones vinculadas a la Responsabilidad Social 
            , '7664'        //      Tienen relaci&oacute;n con organismos gubernamentales
            , '7883'        //      Registro Unico de Organizaciones de Responsabilidad Social
            , '7886'        //      Modos de Financiamiento
            , '7887'        //      Con Programas Sepyme/Ministerio de Industria
            , '7888'        //      Recibi� Capacitaci�n Empresarial / Gerencial / Mandos Medios
            , '7889'        //      Realiz� capacitaciones al personal
            , '7890'        //      Recibi� asesoramiento t�cnico
            , '7891'        //      Capacitaci�n y/o Asistencia con Programas Sepyme / Ministerio de Industria 
        );
        $container = 'container.genias_encuestas';
        $result = $this->mongo->db->$container->find($query, $fields);
        $result->limit(2000);
        foreach ($result as $result) {
            unset($result['_id']);
            unset($result['id']);
            $rtn[] = $result;
        }
        return $rtn;
    }

    //======== Actualiza Meta Activa =========//

    function goal_update_all($proyecto = '2', $id_visita = null, $idu = null, $fecha = null) {
        $container_metas = 'container.genias_goals';
        list($monthValue) = explode("/", $fecha);

        //----busco meta activa
        $query = array(
            'proyecto' => $proyecto,
            'idu' => $idu,
            'hasta' => array('$lte' => date('Y-' . $monthValue . '-t')),
            'desde' => array('$gte' => date('Y-' . $monthValue . '-01')),
        );

        $metas = $this->mongo->db->$container_metas->find($query);
        foreach ($metas as $meta) {

            //return "<pre>" . json_encode($meta['case']). " " . json_encode($query). "</pre>";exit;

            $case = $this->get_case($meta['case']);
            if ($case['status'] == 'closed') {
                break;
            }
        }
        //var_dump($query,$meta);exit;       
        if (isset($meta) && isset($case) && $case['status'] == 'closed') {
            //----Agrego visita a la meta
            $meta['cumplidas'][] = $id_visita;
            $meta['cumplidas'] = array_filter(array_unique($meta['cumplidas']));
            $result = $this->mongo->db->$container_metas->save($meta);
            return "<pre> " . $meta['case'] . " " . $id_visita . " " . $idu . " " . $monthValue . "</pre>"; //$result;
        }
    }

    //======== Actualiza Meta Activa =========//

    function goal_update($proyecto = '2', $id_visita = null) {
        $container_metas = 'container.genias_goals';

        //----busco meta activa
        $query = array(
            'proyecto' => $proyecto,
            'idu' => $this->idu,
            'hasta' => array('$lte' => date('Y-m-t')),
            'desde' => array('$gte' => date('Y-m-01')),
        );
        //return "Consulta" . json_encode($query);exit;
        $metas = $this->mongo->db->$container_metas->find($query);

        foreach ($metas as $meta) {
            $case = $this->get_case($meta['case']);
            if ($case['status'] == 'closed') {
                break;
            }
        }
        //var_dump($query,$meta);exit;
        if (isset($meta) && isset($case) && $case['status'] == 'closed') {
            //----Agrego visita a la meta
            $meta['cumplidas'][] = $id_visita;
            $meta['cumplidas'] = array_filter(array_unique($meta['cumplidas']));
            $result = $this->mongo->db->$container_metas->save($meta);
            return $result;
        }
    }

    function remove_goal($id) {
        $container_metas = 'container.genias_goals';

        $query = array("_id" => new MongoId($id));
        $result = $this->mongo->db->$container_metas->remove($query);
        return (isset($result['err'])) ? ($result['err']) : (0);
    }

    // ======= USER CONTROL ======= //
    //==== Devuelve las genias del usuario====/

    function get_genia($idu) {
        $container = 'container.genias';

        // Es coordinador?    
        $query = array('coordinadores' => ((int) $idu));
        $result = $this->mongo->db->$container->find($query);

        $genias = array();
        $rol = '';
        while ($r = $result->getNext()) {
            $rol = 'coordinador';
            $my_genias[] = $r;
            //var_dump($r['_id']);
        }

        if ($rol == 'coordinador') {
            $genias['rol'] = $rol;
            $genias['genias'] = $my_genias;
            return $genias;
        }

        // Es usuario?
        $query = array('users' => (int) $idu);
        $result = $this->mongo->db->$container->find($query);
        while ($r = $result->getNext()) {
            $rol = 'user';
            $my_genias[] = $r;
        }

        if ($rol == 'user') {
            $genias['rol'] = $rol;
            $genias['genias'] = $my_genias;
            return $genias;
        }

        return false;
    }

    //==== Devuelve todas las genias====//
    function get_genias() {
        $container = 'container.genias';
        $result = $this->mongo->db->$container->find();
        return $result;
    }

    function touch($cuit = null) {
        //if(!$cuit)exit();
        $container = 'container.empresas';
        $update = array('$set' => array('origenGenia' => (int) $this->idu));
        $query = array('1695' => $cuit);
        $this->mongo->db->$container->update($query, $update);
    }

    //==== RESUMEN DE VISITAS ====//

    function get_resumen_visitas() {
        $container = 'container.genias_visitas';
        $this->load->model('user/user');



        $result = $this->mongo->db->$container->find();
        $listado = array();
        foreach ($result as $visita) {
            //
            if (isset($visita['fecha']) && isset($visita['cuit']) && isset($visita['idu'])) {
                $user = $this->user->get_user((int) $visita['idu']);
                $username = (isset($user)) ? ($user->lastname . ", " . $user->name) : ("-");

                $myVisita = array('fecha' => $visita['fecha'], 'idu' => $username);
                $empresa = $this->get_empresas(array('1695' => $visita['cuit']));
                if (empty($empresa) || empty($empresa[0][4651]))
                    continue;
                $prov = (array) $empresa[0][4651];


                $listado[$prov[0]][$visita['cuit']]['empresa'] = $empresa[0][1693];
                $listado[$prov[0]][$visita['cuit']]['4651'] = $prov[0];
                $listado[$prov[0]][$visita['cuit']]['fechas'][] = $myVisita;
                $listado[$prov[0]][$visita['cuit']]['nombre'] = $username;
            }
        }
        return $listado;
    }

}
