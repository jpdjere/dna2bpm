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
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['module_url_encoded'] = $this->qr->encode($this->module_url);
        $cpData['title'] = 'Inventario Módulo PACC';
        $cpData['css'] = array(
            $this->module_url . 'assets/css/inventory.css' => "Mesa Entrada css",
        );
        $cpData['js'] = array(
            $this->base_url . "inventory/assets/jscript/bootbox.min.js" => 'BootBox',
            $this->base_url . "inventory/assets/jscript/actions.js" => 'Main Search',
        );
        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        $this->ui->compose('index_pacc', 'bootstrap.ui.php', $cpData);
    }

    function movimientos($mode = 'PDE') {
        $this->load->model('inventory/inventory_model');
        $cpData = array();
        switch ($mode) {
            case 'PP2012':
                $type = 'PP';
                $SQL = "SELECT tp1.id AS id, tp2.valor AS PDE, tp3.valor AS Nombre, tp4.valor AS Empresa
FROM td_pacc AS tp1
INNER JOIN td_pacc AS tp2 ON tp1.id = tp2.id
INNER JOIN td_pacc AS tp3 ON tp1.id = tp3.id
INNER JOIN td_pacc AS tp4 ON tp1.id = tp4.id
INNER JOIN idsent ON tp1.id = idsent.id
WHERE tp1.idpreg =5689
AND tp1.valor
IN (
'120', '125'
)
AND tp2.idpreg =7356
AND tp2.valor LIKE '%/2012'
AND tp3.idpreg =5673
AND tp4.idpreg =6065
AND idsent.estado = 'activa'
ORDER BY PDE";
                break;
            case 'PP2011':
                $type = 'PP';
                $SQL = "SELECT tp1.id AS id, tp2.valor AS PDE, tp3.valor AS Nombre, tp4.valor AS Empresa
FROM td_pacc AS tp1
INNER JOIN td_pacc AS tp2 ON tp1.id = tp2.id
INNER JOIN td_pacc AS tp3 ON tp1.id = tp3.id
INNER JOIN td_pacc AS tp4 ON tp1.id = tp4.id
INNER JOIN idsent ON tp1.id = idsent.id
WHERE tp1.idpreg =5689
AND tp1.valor
IN (
'120', '125'
)
AND tp2.idpreg =5691
AND tp2.valor LIKE '%/2011'
AND tp3.idpreg =5673
AND tp4.idpreg =6065
AND idsent.estado = 'activa'
ORDER BY PDE";
                break;
            default:
                $type = 'PDE';
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
ORDER BY PDE";
                break;
        }
        $query = $this->db->query($SQL);
        foreach ($query->result() as $row) {
            $mov = $this->inventory_model->get($type, $row->PDE);
            $add = array('type' => $type, 'code' => $row->PDE);
            if ($mov) {
                $last = end($mov);
                $last['user_data'][] = $this->user->get_user_array((double) $last['user']);
                ///-----calculate days
                $date1 = new DateTime();
                $date2 = new DateTime($last['date']);
                $interval = $date2->diff($date1);
                $last['days'] = $interval->format('%a');
                $add+=$last;
            }
            $cpData['result'][] = $add;
        }
        //var_dump($cpData);
        //exit;
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Listado';
        $cpData['css'] = array(
            $this->base_url . "inventory/assets/css/avery6.css" => 'custom css',
        );
        $this->ui->compose('info_table_user', 'bootstrap.ui.php', $cpData);
    }

    function PDE() {
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
ORDER BY PDE";
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
            $qr->PDE = 'PDE-' . $qr->PDE;
            $cpData['qr'][] = (array) $qr;
        }
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Avery4';
        $cpData['css'] = array(
            $this->base_url . "inventory/assets/css/avery6.css" => 'custom css',
        );
        $this->ui->compose('table', 'bootstrap.ui.php', $cpData);
    }

    function PP2011() {
        $cpData = array();
        $SQL = "SELECT tp1.id AS id, tp2.valor AS PDE, tp3.valor AS Nombre, tp4.valor AS Empresa
FROM td_pacc AS tp1
INNER JOIN td_pacc AS tp2 ON tp1.id = tp2.id
INNER JOIN td_pacc AS tp3 ON tp1.id = tp3.id
INNER JOIN td_pacc AS tp4 ON tp1.id = tp4.id
INNER JOIN idsent ON tp1.id = idsent.id
WHERE tp1.idpreg =5689
AND tp1.valor
IN (
'120', '125'
)
AND tp2.idpreg =5691
AND tp2.valor LIKE '%/2011'
AND tp3.idpreg =5673
AND tp4.idpreg =6065
AND idsent.estado = 'activa'
ORDER BY PDE";
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
            $data = $this->module_url . "info/PP/" . $qr->PDE;
            $encoded_url = $this->qr->encode($data);
            $qr->src = $this->base_url . "qr/gen_url/$encoded_url/6/L";
            $qr->PDE = 'PP-' . $qr->PDE;
            $cpData['qr'][] = (array) $qr;
        }
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Avery4';
        $cpData['css'] = array(
            $this->base_url . "inventory/assets/css/avery6.css" => 'custom css',
        );
        $this->ui->compose('table', 'bootstrap.ui.php', $cpData);
    }

    function PP2012() {
        $cpData = array();
        $SQL = "SELECT tp1.id AS id, tp2.valor AS PDE, tp3.valor AS Nombre, tp4.valor AS Empresa
FROM td_pacc AS tp1
INNER JOIN td_pacc AS tp2 ON tp1.id = tp2.id
INNER JOIN td_pacc AS tp3 ON tp1.id = tp3.id
INNER JOIN td_pacc AS tp4 ON tp1.id = tp4.id
INNER JOIN idsent ON tp1.id = idsent.id
WHERE tp1.idpreg =5689
AND tp1.valor
IN (
'120', '125'
)
AND tp2.idpreg =7356
AND tp2.valor LIKE '%/2012'
AND tp3.idpreg =5673
AND tp4.idpreg =6065
AND idsent.estado = 'activa'
ORDER BY PDE";
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
            $empresa = (isset($empresa[0])) ? $empresa[0] : null;
            if ($empresa) {
                $qr->nombre_empresa = $empresa->nombre;
                $qr->cuit_empresa = $empresa->cuit;
            }
            $data = $this->module_url . "info/PP/" . $qr->PDE;
            $encoded_url = $this->qr->encode($data);
            $qr->src = $this->base_url . "qr/gen_url/$encoded_url/6/L";
            $qr->PDE = 'PP-' . $qr->PDE;
            $cpData['qr'][] = (array) $qr;
        }
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Avery4';
        $cpData['css'] = array(
            $this->base_url . "inventory/assets/css/avery6.css" => 'custom css',
        );
        $this->ui->compose('table', 'bootstrap.ui.php', $cpData);
    }

    function Pdf() {
        require APPPATH . 'modules/inventory/libraries/fpdf.php';
        define('FPDF_FONTPATH', APPPATH . 'modules/inventory/assets/font');
        $this->fpdf = new FPDF();
        $this->fpdf->SetFont('Arial', '', 14);
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
ORDER BY PDE 
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
            $qr->PDE = 'PDE-' . $qr->PDE;
            $cpData['qr'][] = (array) $qr;

            $this->fpdf->Cell(31, 11, $qr->PDE);
            $this->fpdf->Image($qr->src, 0, 0, 60, 60, 'png');
            $this->fpdf->AddPage();
        }


        $this->fpdf->Output('filename.pdf', 'I');
    }

}

/* End of file pacc */
/* Location: ./system/application/controllers/welcome.php */