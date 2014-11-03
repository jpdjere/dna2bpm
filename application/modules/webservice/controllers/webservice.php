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
        $dbconnect = $this->load->database('dna2');
        $this->load->model('app');
        ini_set("error_reporting", E_ALL);
    }

    public function msg() {
	

        /* PROGRAM CLASSES */
        $this->load->library("programs/crefis");
        $this->load->library("programs/crefis_ucap");
        $this->load->library("programs/sgr");


        $programas = array(
            'creFis', 'CreFis_UCAP', 'SGR'
        );


        $get_cuit = array('20-24006411-2', '30-70951279-6', '20-25366263-9');
        $msg = null;
        $show_msg = null;


        if ($get_cuit) {
            $empresas = $get_cuit;
        } else {
            $this->db->select('*');
            $query = $this->db->get('empresas_repro');
            foreach ($query->result() as $row)
                $empresas[] = $row->CUIT;
        }



        foreach ($empresas as $CUIT) {
            $cuit = explode('-', $CUIT);

            $id = -1;
            $msg = '<span class="error">No encontrado</span>';

            $this->db->select('*');
            $this->db->where('idpreg', 1695);
            $this->db->like('valor', '%' . $cuit[1] . '%');
            $query = $this->db->get('td_empresas');
            foreach ($query->result() as $row_cuit) {
                $id = $row_cuit->id;
                $msg = '<br/><span class="ok">Encontrado: ' . getvalue($id, 1693) . ' id:' . $id . '</span>';
            }

            $show_msg .= '<hr/>' . $CUIT . ':' . $msg;

            $show_msg .= '<table class="tablesorter" id="table_C6659_1' . $cuit[1] . '">
		<thead>
			<tr class="row-0">
				<th width="80" class="{sorter: false} hcol-0"></th>
				<th class="hcol-1 header">Programa</th>
				<th class="hcol-2 header">Identificador</th>
				<th class="hcol-3 header">Titulo</th>
				<th class="hcol-4 header">Monto</th>
				<th class="hcol-5 header">Fecha</th>
				<th class="hcol-6 header">Estado</th>
				<th class="hcol-7 header">Detalle</th>
				<th class="hcol-8 header">ID DNA</th>
			</tr>
		</thead>
		<tbody>';

            foreach ($programas as $nombre) {
                $id_proyectos = array();
                $id = null;
                $this->load->library("programs/" . $nombre);
                $programa = new $nombre();


                if ($programa->self) {
                    $id_proyectos = $this->search4relSelf($id, $programa->where);
                } else {
                    $id_proyectos = $this->search4rel($id, $programa->where);
                }

                if ($programa->estado != 0) {
                    $this->db->select('*');
                    $this->db->where('idpreg', $programa->estado);
                    $query = $this->db->get('preguntas');
                    foreach ($query->result() as $row)
                        $idopcion = $row->idopcion;

                    $arr_estados = array();

                    if (isset($idopcion))
                        $arr_estados = $this->app->get_ops($idopcion);


                    foreach ($id_proyectos as $idrel) {

                        $ip = ($programa->id != 0) ? $this->test_getvalue($idrel, $programa->id) : null;

                        if (isset($ip)) {
                            $titulo = $this->test_getvalue($idrel, $programa->titulo);


                            if (($programa->estado != 0)) {
                                $estado_value = $this->test_getvalue($idrel, $programa->estado);
                                $estado_opt = $this->app->get_ops($programa->estado);
                                $estado = $estado_opt[$estado_value];
                            }
                        }



                        if (isset($ip)) {
                            $titulo = $this->test_getvalue($idrel, $programa->titulo);
                            $estado = ($programa->estado != 0) ? $this->test_getvalue($idrel, $programa->estado) : "";
                            $monto = $programa->monto($idrel);
                            $fecha = '';

                            $estados = ($programa->estado != 0) ? $this->test_gethist($idrel, $programa->estado) : array();
                            if (is_array($estados)) {
                                asort($estados);
                                foreach ($estados as $estado => $fecha) {
                                    $fecha = date('d/m/Y', strtotime($fecha));
                                }
                            }

                            $basedir = null;
                            $monto = null;

                            $show_msg .= '<tr class="row-1"	id="child_C6659' . $cuit[1] . '_1">
											<td class="col-0"><a class="subformPreview" title="Ver Datos" href="' . $basedir . '/' . $programa->url . '>&id=' . $idrel . '"
											target="_blank"><img border="0" align="absmiddle" alt="Ver Datos"
											src="http:www.accionpyme.mecon.gov.ar/dna2/Icons/24x24/Document 2 Search.gif"></a>
				</td>
				<td class="col-1">';
                            
                            $show_msg .= $programa->nombre;
                            $show_msg .= '</td>
				<td class="col-2">' . $ip . '</td>
				<td class="col-3">' . $titulo . '</td>
				<td class="col-4">' . $monto . '</td>
				<td class="col-5">' . $fecha . '</td>
				<td class="col-6">' . $arr_estados[$estado] . '(' . $estado . ')</td>
				<td class="col-7">' . $programa->monto($idrel) . '</td>
				<td class="col-8">' . $idrel . '</td></tr>';
                        }
                    }
                }
            }


            $show_msg .= ' </tbody></table>';
        }






        return "<p>sandbox</p>" . $show_msg;
    }

    function test_getvalue($id, $idframe) {

        $this->db->select('tabladest');
        $this->db->where('idpreg', $idframe);
        $query = $this->db->get('preguntas');
        $parameter = array();
        foreach ($query->result() as $row)
            $tabladest = $row;


        $plainrow = (array) $row;
        foreach ($plainrow as $key => $value)
            $table = $value;


        $this->db->select('valor');
        $this->db->where('idpreg', $idframe);
        $this->db->where('id', $id);
        $query = $this->db->get($table);
        $parameter = array();
        foreach ($query->result() as $row)
            return $row->valor;
    }

    function test_gethist($id, $idframe) {

        $this->db->select('tabladest');
        $this->db->where('idpreg', $idframe);
        $query = $this->db->get('preguntas');
        $parameter = array();
        foreach ($query->result() as $row)
            $tabladest = $row;


        $plainrow = (array) $row;
        foreach ($plainrow as $key => $value)
            $table = $value;


        $tablahist = str_replace("td_", "th_", $table);


        $this->db->select('valor');
        $this->db->where('idpreg', $idframe);
        $this->db->where('id', $id);
        $query = $this->db->get($tablahist);
        $parameter = array();
        foreach ($query->result() as $row)
            return $row->valor;
    }

    function search4rel($id, $idpregs) {
        $rtnarr = array();
        foreach ($idpregs as $idpreg) {
            $this->db->select('tabladest');
            $this->db->where('idpreg', $idpreg);
            $query = $this->db->get('preguntas');
            $parameter = array();
            foreach ($query->result() as $row)
                $tabladest = $row;


            $plainrow = (array) $row;
            foreach ($plainrow as $key => $value)
                $table = $value;

            $this->db->select('idsent.id', $table);
            $this->db->join('idsent', 'idsent.id = ' . $table . '.id', 'inner');
            $this->db->where('idpreg', $idpreg);
            $this->db->where('estado', 'activa');
            if (isset($id))
                $this->db->like('tdest.id', '%' . $id . '%');
            $query = $this->db->get($table);
            $parameter = array();
            foreach ($query->result() as $newrow) {
                $rtnarr[] = $newrow->id;
            }
        }
        return $rtnarr;
    }

    function search4relSelf($id, $idpregs) {
        $rtnarr = array();
        foreach ($idpregs as $idpreg) {
            $this->db->select('tabladest');
            $this->db->where('idpreg', $idpreg);
            $query = $this->db->get('preguntas');
            $parameter = array();
            foreach ($query->result() as $row)
                $tabladest = $row;


            $plainrow = (array) $row;
            foreach ($plainrow as $key => $value)
                $table = $value;

            $this->db->select('idsent.id', $table);
            $this->db->join('idsent', 'idsent.id = ' . $table . '.id', 'inner');
            $this->db->where('idpreg', $idpreg);
            $this->db->where('estado', 'activa');
            if (isset($id))
                $this->db->where('tdest.id', $id);

            $query = $this->db->get($table);
            $parameter = array();
            foreach ($query->result() as $newrow) {
                $rtnarr[] = $newrow->id;
            }
        }
        return $rtnarr;
    }

}

/* End of file webservice */
    /* Location: ./system/application/controllers/welcome.php */    