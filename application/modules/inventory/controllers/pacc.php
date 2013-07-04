<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * pacc
 * 
 * Description of the class pacc
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date   Jul 4, 2013
 */
class Pacc extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database('dna2');
        $this->load->model('user');
        $this->user->authorize();
        $this->load->library('parser');
        $this->load->library('ui');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'inventory/';
        //----LOAD LANGUAGE
        $this->idu = (float) $this->session->userdata('iduser');
        //---QR
        $this->load->module('qr');
    }

    function Index() {
        $cpData = array();
        $SQL = "SELECT tp1.id AS id, tp2.valor AS PDE, tp3.valor AS Nombre, tp4.valor AS Empresa
FROM td_pacc_1 AS tp1
INNER JOIN td_pacc_1 AS tp2 ON tp1.id = tp2.id
INNER JOIN td_pacc AS tp3 ON tp1.id = tp3.id
INNER JOIN td_pacc_1 AS tp4 ON tp1.id = tp4.id
INNER JOIN idsent ON tp1.id = idsent.id
WHERE tp1.idpreg = 6225
AND tp1.valor NOT IN ('10','100','470','480','490','50','500','99')
AND tp2.idpreg = 6390
AND tp3.idpreg = 5673
AND tp4.idpreg = 6223
AND idsent.estado = 'activa' 
LIMIT 10";
        $query = $this->db->query($SQL);
        foreach ($query->result() as $row) {
            $qr = $row;
            $SQL = "SELECT t1.valor as nombre,t2.valor as cuit FROM td_empresas AS t1 
                INNER JOIN td_empresas AS t2 ON t1.id = t2.id
                WHERE 
                t1.idpreg=1693 
                AND t2.idpreg=1695 
                AND t1.id=" . $row->Empresa;
            $query_empresa = $this->db->query($SQL);
            $empresa = $query_empresa->result();
            $empresa = $empresa[0];
            if ($empresa) {
                $qr->nombre_empresa = $empresa->nombre;
                $qr->cuit_empresa = $empresa->cuit;
            }
            $data = $this->module_url . "info/PDE/" . $qr->PDE;
            $encoded_url = $this->qr->encode($data);
            $qr->src = $this->base_url . "qr/gen_url/$encoded_url/6/L";

            $cpData['qr'][] = (array) $qr;
        }
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $this->ui->compose('avery6', 'bootstrap.ui.php', $cpData);
    }

}

/* End of file pacc */
/* Location: ./system/application/controllers/welcome.php */