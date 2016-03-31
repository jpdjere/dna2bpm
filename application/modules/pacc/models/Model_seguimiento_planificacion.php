<?php

class Model_seguimiento_planificacion extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();

        $this->load->library('cimongo/Cimongo.php', '', 'db_pacc');
        $this->db_pacc->switch_db('dna3');
        $this->idu = $this->session->userdata('iduser'); //Id user
        /* LOADER */
        $this->load->helper('normal_distribution');
        
        ini_set( 'html_errors' , 0 );
    }

    function get_hist($id, $idframe, $status, $tabledest) {
        $dbconnect = $this->load->database('dna2', $this->db);

        $this->db->select('fecha');
        $this->db->where('idpreg', $idframe);
        $this->db->where('valor', $status);
        $this->db->where('id', $id);
        $query = $this->db->get($tabledest);
        $parameter = array();


        foreach ($query->result() as $row)
            $parameter[] = $row->fecha;

        return $parameter;
    }

    function proyectos_PDE_presentados($in_for_array, $graph_range) {
        $this->load->model('app');
        $result = array();
        $return = array();
        $id_user = $this->idu;

        $id_u = strval($id_user);
        $container = 'container.proyectos_pacc';


        $query = array("status" => "activa", 6225 => array('$in' => $in_for_array), 6390 => array('$exists' => true));

        $this->db_pacc->where($query);
        $result = $this->db_pacc->get($container)->result_array();
        $count_estados_dias = array('1', '2', '3');

        foreach ($result as $proy) {

            $fecha_del_estado = $this->get_hist($proy['id'], "6225", $proy[6225][0], "th_pacc_1");

            if (isset($fecha_del_estado[0])) {
                $cantidad_dias = get_date_diff($fecha_del_estado[0]);

                switch ($cantidad_dias) {

                    case in_array($cantidad_dias, $graph_range[0]):
                        $count_estados_dias['1'][] = $proy['id'];
                        break;

                    case in_array($cantidad_dias, $graph_range[1]):
                        $count_estados_dias['2'][] = $proy['id'];
                        break;

                    default:
                        $count_estados_dias['3'][] = $proy['id'];
                }
            }
        }
        return $count_estados_dias;
    }
    
    function proyectos_PDE_obs($in_for_array, $graph_range, $ctrl = null) {
        $this->load->model('app');
        $result = array();
        $return = array();
        $id_user = $this->idu;

        $id_u = strval($id_user);
        $container = 'container.proyectos_pacc';

        
        if(isset($ctrl))
            $ctrl = 6225; //sin_respuesta

        $query = array("status" => "activa", $ctrl => array('$in' => $in_for_array), 6390 => array('$exists' => true));        
        
        $this->db_pacc->where($query);
        $result = $this->db_pacc->get($container)->result_array();
        $count_estados_dias = array('1', '2', '3');

        foreach ($result as $proy) {

            $fecha_del_estado = $this->get_hist($proy['id'], $ctrl, $proy[$ctrl][0], "th_pacc_1");

            if (isset($fecha_del_estado[0])) {
                $cantidad_dias = get_date_diff($fecha_del_estado[0]);

                switch ($cantidad_dias) {

                    case in_array($cantidad_dias, $graph_range[0]):
                        $count_estados_dias['1'][] = $proy['id'];
                        break;

                    case in_array($cantidad_dias, $graph_range[1]):
                        $count_estados_dias['2'][] = $proy['id'];
                        break;

                    default:
                        $count_estados_dias['3'][] = $proy['id'];
                }
            }
        }
        return $count_estados_dias;
    }

    function proyectos_PN_presentados($in_for_array, $graph_range) {
        $this->load->model('app');
        $result = array();
        $return = array();
        $id_user = $this->idu;

        $id_u = strval($id_user);
        $container = 'container.proyectos_pacc';


        $query = array("status" => "activa", 5689 => array('$in' => $in_for_array), 7356 => array('$exists' => true));

        $this->db_pacc->where($query);
        $result = $this->db_pacc->get($container)->result_array();
        $count_estados_dias = array('1', '2', '3');

        foreach ($result as $proy) {

            $fecha_del_estado = $this->get_hist($proy['id'], "5689", $proy[5689][0], "th_pacc");

            if (isset($fecha_del_estado[0])) {
                $cantidad_dias = get_date_diff($fecha_del_estado[0]);

                switch ($cantidad_dias) {

                    case in_array($cantidad_dias, $graph_range[0]):
                        $count_estados_dias['1'][] = $proy['id'];
                        break;

                    case in_array($cantidad_dias, $graph_range[1]):
                        $count_estados_dias['2'][] = $proy['id'];
                        break;

                    default:
                        $count_estados_dias['3'][] = $proy['id'];
                }
            }
        }
        return $count_estados_dias;
    }
    
    function proyectos_PN_obs_sin_respuesta($in_for_array, $graph_range) {
        
       
        
        $this->load->model('app');
        $result = array();
        $return = array();
        $id_user = $this->idu;

        $id_u = strval($id_user);
        $container = 'container.proyectos_pacc';


        $query = array("status" => "activa", 5689 => array('$in' => $in_for_array), 7356 => array('$exists' => true));

        $this->db_pacc->where($query);
        ini_set( 'html_errors' , 0 );
        
        var_dump ("->", json_encode($query));
        
        $result = $this->db_pacc->get($container)->result_array();
        $count_estados_dias = array('1', '2', '3');

        foreach ($result as $proy) {
            
            var_dump($proy);

            $fecha_del_estado = $this->get_hist($proy['id'], "5689", $proy[5689][0], "th_pacc");

            if (isset($fecha_del_estado[0])) {
                $cantidad_dias = get_date_diff($fecha_del_estado[0]);

                switch ($cantidad_dias) {

                    case in_array($cantidad_dias, $graph_range[0]):
                        $count_estados_dias['1'][] = $proy['id'];
                        break;

                    case in_array($cantidad_dias, $graph_range[1]):
                        $count_estados_dias['2'][] = $proy['id'];
                        break;

                    default:
                        $count_estados_dias['3'][] = $proy['id'];
                }
            }
        }
        return $count_estados_dias;
    }

}
