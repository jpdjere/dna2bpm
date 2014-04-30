<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Haberes extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->model('haberes/m_haberes');
        $this->load->helper('dump');
        $this->load->library('pdf');
        $this->load->library('parser');
    }

    public function liquidacion($id = null) {


        if (($id != null)) {
            $cpData['base_url'] = $this->base_url;

            $cpData['recibo'] = (array) $this->m_haberes->get_by_id($id);
            $cpData['detalle_bonificados'] = $this->m_haberes->get_conceptos_bonificados_by_id($cpData['recibo']['ldet_id']);
            $cpData['detalle_no_bonificados'] = $this->m_haberes->get_conceptos_no_bonificados_by_id($cpData['recibo']['ldet_id']);

            $nombre  = $cpData['recibo']['ldet_nombre'];
            $mes  = $cpData['recibo']['lcu_mes'];
            $anio  = $cpData['recibo']['lcu_anio'];
            $nombre = str_replace(" ", "_", $nombre);
            $importe_bonif = $this->m_haberes->get_total_importe_bonificados_by_id($cpData['recibo']['ldet_id']);
            $importe_no_bonif = $this->m_haberes->get_total_importe_no_bonificados_by_id($cpData['recibo']['ldet_id']);
            $cpData['liquido'] = $importe_bonif[0]['lco_importe'] - $importe_no_bonif[0]['lco_importe'];
            $cpData['fecha_emision'] = date('d/m/Y');
            $this->pdf->parse('recibo_sueldo', $cpData);
            $this->pdf->render();
            $archivo = "comprobante_haberes_".$nombre."_".$mes."_".$anio.".pdf";
            $this->pdf->stream($archivo);
        }
    }

}