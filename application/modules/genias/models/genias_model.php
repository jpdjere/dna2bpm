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
        $this->load->helper('genias/tools');
        $this->idu = (int) $this->session->userdata('iduser');
        if (!$this->idu)
            header("$this->module_url/user/logout");
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

    function get_instituciones($query) {
        $rtn = array();
        //$query['status'] = 'activa';
        $fields = array('id',
            'status',
            '4896', //Nombre
            '4897', //Provincia (39)
            '8102', // Partido (58)
            '8103', // Localidad
            '8104', // Tipo (495)
            '8108', // Teléfono
            '6196', // E-mail
            '8111', //  Pagina web 
            '8109', // Latitud Institucion
            '8110', // Longitud Institucion
            //-----------Contacto
            '8105', // Nombre del Contacto
            '8107', // Cargo del Contacto
            '8117', // telefonos Contacto
            '8116', // email Contacto
            //------------DOmicilio
            '8106', // nro / Km
            '8112', //  Calle / Ruta 
            '8113', //  Piso
            '8114', //  Dto / Oficina 
            '8115', //  CP
            'origenGenia', //Origen de los datos Genia 2013 Genia
        );
        $container = 'container.agencias';
        $sort = array('origenGenia' => -1);
        $result = $this->mongo->db->$container->find($query, $fields);
        $result->limit(2500)->sort($sort);
        foreach ($result as $institucion) {
            unset($institucion['_id']);
            $rtn[] = $institucion;
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
            ,'proyecto'
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
        
        // No encontre meta para la visita
        if ($metas->count() == 0){
            return array('found'=>0,'query'=>$query,'id'=>$id_visita,'#'=>$i,'status'=>'Sin meta');
        }
        
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
        
        // Se encontro Meta para la visita
        if ($result) {
            return array('found'=>1,'query'=>$query,'id'=>$id_visita,'#'=>$i,'status'=>'Meta encontrada');
        }

        // Salimos
        return array('found'=>0,'query'=>$query,'id'=>$id_visita,'#'=>$i,'status'=>'Meta abierta');
      
    }

    //======== Actualiza Meta Activa =========//
    // Actualiza las metas cuando se carga visita en formulario
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
        $empresas=array();
        
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
//exit();}

        $listado = array();

        foreach ($visitas as $visita) {
            if (!isset($visita['cuit']))
                continue;
            if (!array_key_exists($visita['cuit'], $empresas)){
                continue;
            }
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
            $myVisita = array('fecha' => $visita['fecha'], 'idu' => $username,'nota'=>$visita['nota']);
            $listado[$prov][$empresa['1695']]['empresa_partido'] = (empty($agencia[1699])) ? ('-') : ($agencia[1699]);
            $listado[$prov][$empresa['1695']]['empresa_tipo'] = (empty($agencia[1694])) ? ('-') : ($agencia[1694]);
            $listado[$prov][$empresa['1695']]['empresa_web'] = (empty($agencia[1704])) ? ('-') : ($agencia[1704]);
            
            $listado[$prov][$empresa['1695']]['empresa'] = $razon_social;
            $listado[$prov][$empresa['1695']]['4651'] = $prov;
            $listado[$prov][$empresa['1695']]['fechas'][] = $myVisita;
            $listado[$prov][$empresa['1695']]['nombre'] = $username;
            $listado[$prov][$empresa['1695']]['1703'] = $email;
        }


        return $listado;
    }
    
    //==== RESUMEN DE VISITAS  INSTITUCIONES ====//

    function get_resumen_visitas_instituciones($periodo) {

        $this->load->model('user/user');


        // LIstado de Provincias permitidas
        $provincias = array();
        $agencias=array();
        
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
        $cuits = $this->mongo->db->$container->distinct('cuit',array('proyecto'=>4));
        // Todos los idus cargados en visitas
        $idus = $this->mongo->db->$container->distinct('idu',array('proyecto'=>4));
        

        // Listado de empresas
        $container = 'container.agencias';
        $fields = array('4896', '4897','6196');
        $query = array("4896" => array('$in' => $cuits), "4897" => array('$in' => $provincias));
        $mongo_agencias = $this->mongo->db->$container->find($query, $fields);

        foreach ($mongo_agencias as $agencia) {
            $agencias[$agencia['4896']] = $agencia;
        }

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
            if (!array_key_exists($visita['cuit'], $agencias))
                continue;
            
            $agencia = $agencias[$visita['cuit']];
            $temp = (array) $agencia['4897'];
            $prov = $temp[0];

            // xxx Si no hay cuit salgo del loop


            if ($visita['idu'] != 0 && array_key_exists((int) $visita['idu'], $usuarios)) {
                $username = "{$usuarios[$visita['idu']]['name']} {$usuarios[$visita['idu']]['lastname']}";
            } else {
                $username = "-";
            }
            $myVisita = array('fecha' => $visita['fecha'], 'idu' => $username,'nota'=>$visita['nota']);

            $listado[$prov][$agencia['4896']]['empresa'] = (empty($agencia[4896])) ? ('-') : ($agencia[4896]);
            
            $listado[$prov][$agencia['4896']]['empresa_partido'] = (empty($agencia[8102])) ? ('-') : ($agencia[8102]);
            $listado[$prov][$agencia['4896']]['empresa_localidad'] = (empty($agencia[8103])) ? ('-') : ($agencia[8103]);
            $listado[$prov][$agencia['4896']]['empresa_tipo'] = (empty($agencia[8104])) ? ('-') : ($agencia[8104]);
            $listado[$prov][$agencia['4896']]['empresa_web'] = (empty($agencia[8111])) ? ('-') : ($agencia[8111]);
            
            $listado[$prov][$agencia['4896']]['4896'] = $prov;
            $listado[$prov][$agencia['4896']]['fechas'][] = $myVisita;
            $listado[$prov][$agencia['4896']]['nombre'] = $username;
            $listado[$prov][$agencia['4896']]['1703'] = $email;
        }


        return $listado;
    }

    // ======= ESTADISTICAS ======= //
    
     function estadisticas() {

        $stats=array();
        $total_visitas=0;
        $total_metas=0;
        $container = 'container.genias';
        $misgenias = iterator_to_array($this->mongo->db->$container->find()->limit(100));
        
        // Genia x Genia    
        foreach ($misgenias as $genia) {
            
            // Visitas
            $container = 'container.genias_visitas';    
            if(empty($genia['users']))continue;
            
            $count_visitas=$this->mongo->db->$container->find(array('idu'=>array('$in'=>$genia['users'])))->count();
            $total_visitas+=$count_visitas;
            $stats['genias'][(string)$genia['_id']]=array(
                'id'=>$genia['_id'],
                'nombre'=>$genia['nombre'],
                'visitas'=>$count_visitas
                );
            
            // Metas
            $container = 'container.genias_goals';
            $metas=$this->mongo->db->$container->find(array('genia'=>(string)$genia['_id']));
            $count_metas=0;
            $count_cumplidas=0;
         //   var_dump(iterator_to_array($metas));
            
            foreach($metas as $meta){
                $count_metas+=(integer)$meta['cantidad'];
                $count_cumplidas+=(empty($meta['cumplidas']))?(0):(count($meta['cumplidas']));
            }
            $stats['genias'][(string)$genia['_id']]['cantidad']=$count_metas;
            $stats['genias'][(string)$genia['_id']]['cumplidas']=$count_cumplidas;
            $total_metas+=$count_metas;
//            echo $genia['nombre']."/".$total_metas."<br>";
        }
        $stats['total_visitas']=$total_visitas;
        $stats['total_metas']=$total_metas;
      var_dump($stats);
        
        
     }

}
