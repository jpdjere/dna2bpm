<?php

/**
 * @class genia
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sgr_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');
        $this->idu = (int) $this->session->userdata('iduser');
        if (!$this->idu)
            header("$this->module_url/user/logout");
        /* Set locale to Spansih */
    }

    /* RETURN ANEXOS */

    function get_anexos() {
        $container = 'container.sgr_anexos';
        $result = $this->mongo->db->$container->find();
        return $result;
    }

    function get_anexo($anexo) {
        $container = 'container.sgr_anexos';
        $query['number'] = $anexo;
        $result = $this->mongo->db->$container->findOne($query);
        return $result;
    }

    function get_sgr() {
        $rtn = array();
        $this->load->model('user/user');
        $idu = (int) $this->idu;
        $data = array();
        // Listado de empresas
        $container = 'container.empresas';
        $fields = array('id', '1695', '4651', '1693', '1703');
        $query = array("owner" => $idu, "6026" => '30', "status" => 'activa');
        $result = $this->mongo->db->$container->find($query, $fields);

        foreach ($result as $empresa) {
            unset($empresa['_id']);
            $rtn[] = $empresa;
        }
        return $rtn;
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

    function get_tasks($idu, $proyecto, $periodo = null) {

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

        if (isset($periodo)) {
            $fecha = new MongoRegex("/$periodo-\d{2}/");
            $query = array('idu' => array('$in' => $idus), 'proyecto' => $proyecto, 'dia' => $fecha);
        } else {
            $query = array('idu' => array('$in' => $idus), 'proyecto' => $proyecto);
        }

        $result = $this->mongo->db->$container->find($query)->sort(array('dia' => -1));

//        var_dump(iterator_to_array($result));
//        exit();
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

        $proyectos = $this->genias_model->get_config_item('projects');
        foreach ($proyectos['items'] as $v) {
            if ($data['proyecto'] == $v['id'])
                $data['proyecto_nombre'] = $v['name'];
        }

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

    function get_empresas_raw($query) {
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
        $result->sort($sort);
        foreach ($result as $empresa) {
            unset($empresa['_id']);
            $rtn[] = $empresa;
        }
        return $rtn;
    }

    function get_empresas_id($query) {
        $rtn = array();
        //$query['status'] = 'activa';


        $container = 'container.empresas';
        $sort = array('id' => 1);
        $result = $this->mongo->db->$container->find($query);
        $result->sort($sort);
        foreach ($result as $empresa) {
            $empresaRtn['id'] = $empresa['id'];
            $empresaRtn['checksum'] = md5(json_encode($empresa));
            $rtn[] = $empresaRtn;
        }
        return $rtn;
    }

    /* RETURN VISITAS */

    function get_visitas($query = array(), $idu = null) {
        $rtn = array();
        if (!is_null($idu))
            $query['idu'] = (int) $idu;

        $fields = array('id'
            , 'fecha'           // 	Fecha de la Visita 
            , 'cuit'            //      CUIT
            , 'nota'            // 	Comentarios 
            , 'tipovisita'      //      tipo de visita
            , 'otros'           //      para tipo de visita otros
            , '7898'            //      Programas Informados  
            , 'idu'
        );
        $container = 'container.genias_visitas';
        $result = $this->mongo->db->$container->find($query, $fields);
        //$result->limit(2000);
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

    function goal_clear_cumplidas($proyecto = '2') {
        $container_metas = 'container.genias_goals';
        $query = array();
        $update = array('$set' => array('cumplidas' => array()));
        $options = array('multiple' => true);
        $result = $this->mongo->db->$container_metas->update($query, $update, $options);
        return $result;
    }

    function goal_update_all($proyecto = '2', $id_visita = null, $idu = null, $fecha = null, $i) {


        $container_metas = 'container.genias_goals';
        list($monthValue, $dayValue, $yearValue) = explode("/", $fecha);

        //----busco meta activa
        $query = array(
            'proyecto' => $proyecto,
            'idu' => $idu,
            'hasta' => array('$lte' => date('Y-' . $monthValue . '-t', mktime(0, 0, 0, (int) $monthValue, 15, (int) $yearValue))),
            'desde' => array('$gte' => date('Y-' . $monthValue . '-01', mktime(0, 0, 0, (int) $monthValue, 15, (int) $yearValue))),
        );



        $metas = $this->mongo->db->$container_metas->find($query);

        if ($metas->count() == 0)
            return "<pre>--------- Query: " . print_r($query, true) . " | $monthValue $dayValue $yearValue ID: $id_visita  / $i</pre>";
        // Loop , si hay varias metas del mismo periodo salgo en la primera que este cerrada

        foreach ($metas as $meta) {
            $case = $this->get_case($meta['case']);
            if ($case['status'] == 'closed') {
                $meta['cumplidas'][] = (float) $id_visita;
                $meta['cumplidas'] = array_filter(array_unique($meta['cumplidas']));
                $result = $this->mongo->db->$container_metas->save($meta);
                break;
            }
        }

        if ($result) {
            return "<pre>{$meta['case']} |status:" . $case['status'] . " |visita: $id_visita | $i --</pre>";
        }

        return false;
        //var_dump($query,$meta);exit;       
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
        $container_case = 'case';
        // Busco el case del goal
        $query = array("_id" => new MongoId($id));
        $my_goal = $this->mongo->db->$container_metas->findOne($query);
        $my_case = $my_goal['case'];

        $result = $this->mongo->db->$container_metas->remove($query);

        if (!isset($result['err'])) {
            // Marco el case como borrado
            $checkoutdate = date("Y-m-d H:i:s");
            $query = array("id" => $my_case);
            $data = array('$set' => array("checkoutdate" => $checkoutdate, "status" => "deleted"));
            $rs_case = $this->mongo->db->$container_case->update($query, $data);
        } else {
            return $result['err'];
        }
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

    function get_resumen_visitas($periodo) {

        $this->load->model('user/user');


        // LIstado de Provincias permitidas
        $provincias = array();
        $misgenias = $this->get_genia($this->idu);
        foreach ($misgenias['genias'] as $genia) {
            if (isset($genia['query_empresas'][4651])) {
                if (is_array($genia['query_empresas'][4651])) {
                    $provincias = array_merge($genia['query_empresas'][4651], $provincias);
                } else {
                    $provincias[] = $genia['query_empresas'][4651];
                }
            }
        }

        // Todos los cuits cargados en visitas
        $container = 'container.genias_visitas';
        $cuits = $this->mongo->db->$container->distinct('cuit');
        // Todos los idus cargados en visitas
        $idus = $this->mongo->db->$container->distinct('idu');

        // Listado de empresas
        $container = 'container.empresas';
        $fields = array('1695', '4651', '1693', '1703');
        $query = array("1695" => array('$in' => $cuits), "4651" => array('$in' => $provincias));
        $mongo_empresas = $this->mongo->db->$container->find($query, $fields);
        foreach ($mongo_empresas as $empresa) {
            $empresas[$empresa['1695']] = $empresa;
        }

        $query = array("1695" => array('$in' => $cuits), "4651" => array('$in' => $provincias));
        $mongo_empresas = $this->mongo->db->$container->find($query, $fields);

        // Usuarios
        $container = 'users';
        $fields = array('lastname', 'name', 'email', 'idu');
        $query = array("idu" => array('$in' => $idus));
        $mongo_usuarios = $this->mongo->db->$container->find($query, $fields);
        $usuarios = array();
        foreach ($mongo_usuarios as $usuario) {
            $usuarios[$usuario['idu']] = $usuario;
        }
        // Visitas
        $container = 'container.genias_visitas';
        $rx = new MongoRegex("/" . $periodo . "/");
        $query = ($misgenias['rol'] == 'coordinador') ? (array('fecha' => $rx)) : (array('idu' => $this->idu, 'fecha' => $rx));
        $visitas = $this->mongo->db->$container->find($query);

//var_dump(iterator_to_array($visitas));
//exit();
        $listado = array();


        foreach ($visitas as $visita) {
            if (!isset($visita['cuit']))
                continue;
            if (!array_key_exists($visita['cuit'], $empresas))
                continue;

            $empresa = $empresas[$visita['cuit']];
            $temp = (array) $empresa['4651'];
            $prov = $temp[0];

            // Si no hay cuit salgo del loop

            $email = (empty($empresa[1703])) ? ('-') : ($empresa[1703]);
            $razon_social = (empty($empresa[1693])) ? ('-') : ($empresa[1693]);


            if ($visita['idu'] != 0 && array_key_exists((int) $visita['idu'], $usuarios)) {
                $username = "{$usuarios[$visita['idu']]['name']} {$usuarios[$visita['idu']]['lastname']}";
            } else {
                $username = "-";
            }
            $myVisita = array('fecha' => $visita['fecha'], 'idu' => $username);

            $listado[$prov][$empresa['1695']]['empresa'] = $razon_social;
            $listado[$prov][$empresa['1695']]['4651'] = $prov;
            $listado[$prov][$empresa['1695']]['fechas'][] = $myVisita;
            $listado[$prov][$empresa['1695']]['nombre'] = $username;
            $listado[$prov][$empresa['1695']]['1703'] = $email;
        }


        return $listado;
    }

}
