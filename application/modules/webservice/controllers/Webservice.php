<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * WEBSERVICE
 * 
 * Description of the class WEBSERVICE
 * 
 * @author Diego Otero <xxcynicxx@gmail.com>
 * @date   Oct 21, 2014
 */
class Webservice extends MX_Controller {

    function __construct() {
        $dbconnect = $this->load->database('dna2', $this->db);
        $this->load->model('app');
        $this->load->config('config');
        $this->load->model('sgr/model_06');
        $this->load->helper('sgr/tools');
    }

    /* MAIN QUERY CLASS */

    public function querys($program_name, $date_from = null, $date_to = null) {






        switch ($program_name) {
            case 'sgr':
                return $this->querys_sgr($date_from, $date_to);
                break;

            case 'bonita':
                return $this->querys_bonita($date_from, $date_to);
                break;

            default:
                return $this->querys_default($program_name, $date_from, $date_to);
                break;
        }
    }

    /* SGR QUERY */

    public function querys_sgr($date_from = null, $date_to = null) {


        $result = $this->model_06->get_partners_ws($date_from, $date_to);

        foreach ($result as $each) {

            $program_array = array(
                'consulta' => 'sgr',
                'identificador' => $each[1695],
                'codigo_postal' => $each[1698],
                'provincia' => $each[4651][0],
                'localidad' => $each[1700],
                'clanae' => $each[5208],
                'monto' => 0,
                'fecha' => mongodate_to_print($each['FECHA_DE_TRANSACCION']),
                'programa' => 'SGR');
            $rtn[] = $program_array;
        }

        if (empty($rtn))
            $rtn[] = $program_array;


        return $rtn;
    }

    /* BONIFICACION DE TASA QUERY */

    public function querys_bonita($date_from = null, $date_to = null) {



        $date_from = ($date_from != '') ? $date_from . "-01" : '2012-01-01';
        $date_to = ($date_to != '') ? $date_to . "-31" : date('Y') . '-12-31';


        $table = 'bonita_cuotas';

        $this->db->select('*', 'sum(total_bon)', $table);
        $this->db->join('importar.bonita_prestamos', 'importar.bonita_prestamos.nro = ' . $table . '.prestamo', 'inner');

        $this->db->where('fecha_acredita >=', $date_from);
        $this->db->where('fecha_acredita <=', $date_to);

        $this->db->group_by('cuit');

        $query = $this->db->get('importar.' . $table);       
       
        $i= 1;
        $parameter = array();


        foreach ($query->result() as $row) {


            $program_array = array(
                'consulta' => 'bonita ' . $i++,
                'identificador' => $row->cuit,
                'codigo_postal' => $row->cp,
                'provincia' => $row->provincia,
                'localidad' => $row->localidad,
                'clanae' => $row->codigo,
                'monto' => $row->total_bon,
                'fecha' => $row->fecha_acredita,
                'programa' => 'BoniTa');
            $rtn[] = $program_array;
        }

        if (empty($rtn))
            $rtn[] = $program_array;



        return $rtn;
    }

    public function querys_default($program_name, $date_from = null, $date_to = null) {

        $date_from = ($date_from != '') ? $date_from . "-01" : '2009-01-01';
        $date_to = ($date_to != '') ? $this->get_date($date_to) : date('Y') . '-12-31';


        $parameter = $program_name . '/' . $date_from . '/' . $date_to;

        $programas = ($program_name != '') ? array($program_name) : array('CreFis', 'FonDyF', 'PACC1', 'PACC3', 'EXPYME', 'SGR', 'BoniTa');


        $classes = array_map('strtolower', $programas);


        /* PROGRAM CLASSES */
        foreach ($classes as $class)
            $this->load->library("programs/" . strtolower($class));

        foreach ($programas as $nombre) {



            $id_proyectos = array();
            $programa = new $nombre();

            $id_proyectos = $this->search_no_id($programa->idpreg, $programa->value, $programa->tablahist, $date_from, $date_to);

            $program_array = array('consulta' => $parameter, 'resultado' => 'Sin Resultados Encontrados');
            $arr_parameter = array($parameter);

            foreach ($id_proyectos as $each) {

                list($id, $fecha) = explode("*", $each);

                /* PROYECTO */

                $monto = str_replace(".", "", $this->getvalue($id, $programa->monto, $programa->tabladest));

                /* EMPRESA */
                $id_empresa = $this->getvalue($id, $programa->where, $programa->tabladest);
                $cuit = $this->getvalue($id_empresa, $programa->cuit_value, $programa->cuit_table);
                $clanae = $this->getvalue($id_empresa, $programa->clanae, $programa->cuit_table);
                $zip = $this->getvalue($id_empresa, $programa->zip, $programa->cuit_table);
                $pcia = $this->getvalue($id_empresa, $programa->pcia, $programa->cuit_table);
                $localidad = $this->getvalue($id_empresa, $programa->localidad, $programa->cuit_table);

                $program_array = array(
                    'consulta' => $parameter,
                    'identificador' => $cuit,
                    'codigo_postal' => $zip,
                    'provincia' => $pcia,
                    'localidad' => $localidad,
                    'clanae' => $clanae,
                    'monto' => $monto,
                    'fecha' => $fecha,
                    'programa' => $programa->nombre);

                $rtn[] = $program_array;
            }
        }

        if (empty($rtn))
            $rtn[] = $program_array;


        return $rtn;
    }

    function get_status($status_ctrl) {
        $this->db->select('*');
        $this->db->where('idpreg', $status_ctrl);
        $query = $this->db->get('preguntas');
        foreach ($query->result() as $row)
            $idopcion = $row->idopcion;
        return $idopcion;
    }

    function getvalue($id, $idframe, $tabledest) {

        $table = $tabledest;

        $this->db->select('valor');
        $this->db->where('idpreg', $idframe);
        $this->db->where('id', $id);
        $query = $this->db->get($table);
        $parameter = array();
        foreach ($query->result() as $row)
            return $row->valor;
    }

    function get_hist($id, $idframe, $tabledest) {

        $tablahist = str_replace("td_", "th_", $tabledest);


        $this->db->select('fecha');
        $this->db->where('idpreg', $idframe);
        $this->db->where('id', $id);
        $query = $this->db->get($tablahist);
        $parameter = array();
        foreach ($query->result() as $row)
            $parameter[] = $row->fecha;

        return $parameter;
    }

    function search_no_id($idpreg, $value, $table, $date_from, $date_to) {

        /*
          Credito Fiscal
          Estado frame = 4970
          Estado valor = 99
          Fecha = 4970, 99, th_crefis
          Monto Solicitado = 5040
          Monto Pagado = 5672
          Tabla = td_crefis

          Fondyf
          Estado frame = 8334
          Estado valor = 90
          Fecha = 8334, 90, th_fondyf
          Monto Solicitado = 8573
          Monto Pagado = 8573
          Tabla = td_fondyf

          Expertos Pyme
          Estado frame = 5550
          Estado valor = >=30 && <06 (monto = 3543) // >=06 && <10 (monto = 3543 + 6116)
          Fecha = 5550, estado valor, th_saberpyme
          Monto Solicitado = 3543 + 6116
          Monto Pagado = 3543 + 6116
          Tabla = td_saberpyme
          Empresa = 8325

          PACC 1.1
          Estado frame = 6225 (td_pacc_1)
          Estado valor = >= 120
          Monto Solicitado = 6383 (td_pacc_1)
          ACTIVIDADES = 6243-td_pacc_1 : foreach
          Fecha = 6843-td_actividades1 (if fecha != '')
          Monto Pagado = 6846-td_actividades1
          Tablas = td_pacc_1, td_actividades1

          PACC 1.3
          Estado frame = 5689 (td_pacc)
          Estado valor = >= 120
          Monto Solicitado = 6058 (td_pacc)
          ACTIVIDADES = 7057-td_pacc : foreach
          Fecha = 7049-td_pacc (if fecha != '')
          Monto Pagado = 7048-td_pacc
          Tablas = td_pacc
         */

        $rtnarr = array();


        $this->db->select('*', $table);
        $this->db->join('idsent', 'idsent.id = ' . $table . '.id', 'inner');
        $this->db->where('idpreg', $idpreg);

        switch ($table) {

            case "th_pacc_1":
            case "th_pacc_3":
            case "th_saberpyme":
                $this->db->where('valor >=', $value);
                break;

            default:
                $this->db->where('valor', $value);
                break;
        }



        $this->db->where('estado', 'activa');

        /* DATE */
        $this->db->where('fecha >', $date_from);
        $this->db->where('fecha <', $date_to);

        $query = $this->db->get($table);

        /* DEBUG */
        //var_dump($this->db->queries);


        $parameter = array();
        foreach ($query->result() as $newrow)
            $rtnarr[] = $newrow->id . "*" . $newrow->fecha;

        return $rtnarr;
    }

    function get_date($date) {

        list($year, $month) = explode("-", $date);
        $month = (int) $month;
        $month_value = intval(date("t", $month));

        return $date . "_" . $month_value;
    }

    public function get_sgr_data($date_from = null, $date_to = null) {

        $this->load->model('sgr/sgr_model');
        $this->load->model('sgr/model_06');


        $date_from = ($date_from != '') ? $date_from . "-01" : '2009-01-01';
        $date_to = ($date_to != '') ? $this->get_date($date_to) : date('Y') . '-12-31';
    }

    public function msg_parameter($parameter) {



        $programas = array('CreFis', 'FonDyF', 'PACC1', 'PACC3', 'EXPYME');
        $classes = array_map('strtolower', $programas);

        /* PROGRAM CLASSES */
        foreach ($classes as $class)
            $this->load->library("programs/" . $class);


        $rtn = array();

        $check_cuit = $this->cuit_checker($parameter);


        if ($check_cuit == false) {
            $program_array = array('consulta' => $parameter, 'resultado' => 'El C.U.I.T. No es valido');

            $rtn[] = $program_array;
            return $rtn;
            exit;
        }

        $program_array = array('consulta' => $parameter, 'resultado' => 'El C.U.I.T. No Encontrado');
        $arr_parameter = array($parameter);


        $empresas = (!isset($parameter)) ? "" : $arr_parameter;

        foreach ($empresas as $CUIT) {

            $cuit = explode('-', $CUIT);

            $id = -1;
            $tbl_dest = 'td_empresas';
            $this->db->select('*');
            $this->db->where('idpreg', 1695);
            $this->db->like('valor', trim($cuit[1]));
            $query = $this->db->get('td_empresas');
            foreach ($query->result() as $row_cuit) {
                $id = $row_cuit->id;
            }

            foreach ($programas as $nombre) {

                $id_proyectos = array();
                $programa = new $nombre();


                if ($programa->self) {
                    $id_proyectos = $this->search4relSelf($id, $programa->where, $programa->tabladest);
                } else {
                    $id_proyectos = $this->search4rel($id, $programa->where, $programa->tabladest);
                }



                foreach ($id_proyectos as $idrel) {



                    if ($programa->estado != 0) {
                        $ip = ($programa->id != 0) ? $this->getvalue($idrel, $programa->id, $programa->tabladest) : "N/A";
                        $get_estado = ($programa->id != 0) ? $this->getvalue($idrel, $programa->estado, $programa->tabladest) : null;
                        $titulo = utf8_decode($this->getvalue($idrel, $programa->titulo, $programa->tabladest));

                        $monto = ($programa->monto() != 0) ? $this->getvalue($idrel, $programa->monto(), $programa->tabladest) : null;

                        $fecha = NULL;
                        $estados = ($programa->estado != 0) ? $this->get_hist($idrel, $programa->estado, $programa->tabladest) : array();



                        if (is_array($estados)) {
                            asort($estados);
                            foreach ($estados as $estado => $fecha) {
                                $fecha = date('d/m/Y', strtotime($fecha));
                            }
                        }

                        $show_estado = "N/A";
                        if (isset($get_estado)) {
                            $idopcion = $this->get_status($programa->estado);
                            $opcion_arr = $this->app->get_ops($idopcion);
                            $show_estado = $opcion_arr[$get_estado];
                        }
                    }


                    $program_array = array(
                        'consulta' => $parameter,
                        'resultado' => 'encontrado',
                        'identificador' => $ip,
                        'titulo' => $titulo,
                        'monto' => $monto,
                        'estado' => $show_estado,
                        'id_dna' => $idrel,
                        'fecha' => $fecha,
                        'programa' => $programa->nombre);

                    $rtn[] = $program_array;
                }
            }
        }


        if (empty($rtn)) {

            $rtn[] = $program_array;
        }

        return $rtn;
    }

    function search4rel($id, $idpreg, $table) {

        $rtnarr = array();

        $this->db->select('idsent.id', $table);
        $this->db->join('idsent', 'idsent.id = ' . $table . '.id', 'inner');
        $this->db->where('idpreg', $idpreg);
        $this->db->where('estado', 'activa');
        $this->db->like('valor', trim($id));

        $query = $this->db->get($table);
        $parameter = array();
        foreach ($query->result() as $newrow) {
            $rtnarr[] = $newrow->id;
        }
        return $rtnarr;
    }

    function search4relSelf($id, $idpregs, $table) {
        $rtnarr = array();
        foreach ($idpregs as $idpreg) {

            $this->db->select('idsent.id', $table);
            $this->db->join('idsent', 'idsent.id = ' . $table . '.id', 'inner');
            $this->db->where('idpreg', $idpreg);
            $this->db->where('estado', 'activa');
            $this->db->where($table . '.id', $id);

            $query = $this->db->get($table);
            $parameter = array();
            foreach ($query->result() as $newrow) {
                $rtnarr[] = $newrow->id;
            }
        }
        return $rtnarr;
    }

    function cuit_checker($cuit) {
        if ((int) strlen($cuit) != 13) {
            return false;
        }

        if (ctype_alpha($cuit)) {
            return false;
        }

        $cuit = str_replace("-", "", $cuit);

        if (strstr($cuit, '-')) {
            return false;
            exit();
        }

        /* VALIDATOR ALGORITHM */
        $cadena = str_split($cuit);

        $result = $cadena[0] * 5;
        $result += $cadena[1] * 4;
        $result += $cadena[2] * 3;
        $result += $cadena[3] * 2;
        $result += $cadena[4] * 7;
        $result += $cadena[5] * 6;
        $result += $cadena[6] * 5;
        $result += $cadena[7] * 4;
        $result += $cadena[8] * 3;
        $result += $cadena[9] * 2;

        $div = intval($result / 11);
        $resto = $result - ($div * 11);

        if ($resto == 0) {
            if ($resto == $cadena[10]) {
                return true;
            } else {
                return false;
            }
        } elseif ($resto == 1) {
            if ($cadena[10] == 9 AND $cadena[0] == 2 AND $cadena[1] == 3) {
                return true;
            } elseif ($cadena[10] == 4 AND $cadena[0] == 2 AND $cadena[1] == 3) {
                return true;
            }
        } elseif ($cadena[10] == (11 - $resto)) {
            return true;
        } else {
            return false;
        }
    }

    /* FORM */

    function sandbox() {

        echo '<form method="post" action="responder">

    
        <dl>
            <dt id="valor">
            <label for="cuit">cuit</label>
            <input type="text" name="cuit" placeholder="Ingrese el valor"/>
            </dt>
        </dl> 
        
        <dl>
                    <dt id="Enviar">
                    <input type="submit" value="Enviar" id="Enviar"  />
                    </dt>
                </dl>
  
</form>';
    }

}

/* End of file webservice */
    /* Location: ./system/application/controllers/welcome.php */    