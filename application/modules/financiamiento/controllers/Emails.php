<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * Description of the class
 * 
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 */
class Emails extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->model('model_financiamiento');
        $this->load->library('parser');
        $this->load->model('bpm/bpm');
        $this->reply_email='financiamiento@producción.gob.ar';
        $this->reply_nicename='Financiamiento Ministerio de Producción';
    }
    
    function Index() {
        //$this->session->set_userdata(array('iduser'=>756148209, 'loggedin'=>true));
        //redirect($this->base_url.'bpm/engine/newcase/model/form_entrada');
    }

    function email_gran_empresa_bancario(){

        $email['subject'] = 'Líneas de Crédito. Banco BICE';
        $email['body'] = $this->load->view("financiamiento/cuerpos_emails/gran_empresa_bancario.htm", '', true);
        $email['reply_email'] = $this->reply_email;
        $email['reply_nicename'] = $this->reply_nicename;
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/gran_empresa/bancario/preanalisis.pdf'
            =>'Preanalisis.pdf');
        
        return $email;
    }
    
    function email_gran_empresa_no_bancario(){

        $email['subject'] = 'Solicitud de información complementaria. Ministerio de Producción.';
        $email['body'] = $this->load->view("financiamiento/cuerpos_emails/gran_empresa_no_bancario.htm", '', true);
        $email['reply_email'] = $this->reply_email;
        $email['reply_nicename'] = $this->reply_nicename;
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/gran_empresa/no_bancario/plantilla_empresa.xlsx'
            =>'Plantilla Empresa.xlsx');
        
        return $email;
    }
    
    function email_fona_ct(){

        $email['subject'] = 'Formulario Presentación FONAPYME – FORTALECIMIENTO COMPETITIVO';
        $email['body'] = $this->load->view("financiamiento/cuerpos_emails/fona_ctrabajo.htm", '', true);
        $email['reply_email'] = $this->reply_email;
        $email['reply_nicename'] = $this->reply_nicename;
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/no_bancario/fona_ct.xls'
            =>'ANEXO II - Formulario FONAPYME - FORTALECIMIENTO COMPETITIVO.xls');
        
        return $email;
    }
    
    function email_fona_bc(){

        $email['subject'] = 'Formulario Presentación FONAPYME – PRODUCCIÓN ESTRATÉGICA';
        $email['body'] = $this->load->view("financiamiento/cuerpos_emails/fona_bc.htm", '', true);
        $email['reply_email'] = $this->reply_email;
        $email['reply_nicename'] = $this->reply_nicename;
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/no_bancario/fona_bc.xls'
            =>'ANEXO II -  FONAPYME PRODUCCION ESTRATEGICA.xls');
        
        return $email;
    }

    function email_fona_otros(){

        $email['subject'] = 'Solicitud de información complementaria.';
        $email['body'] = $this->load->view("financiamiento/cuerpos_emails/fona_otros.htm", '', true);
        $email['reply_email'] = $this->reply_email;
        $email['reply_nicename'] = $this->reply_nicename;
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/no_bancario/plantilla_empresa.xlsx'
            =>'Plantilla Empresa.xlsx');
        
        return $email;
    }

    function email_mi_galpon(){

        $email['subject'] = 'Presentación "Mi Galpón"';
        $email['body'] = $this->load->view("financiamiento/cuerpos_emails/mi_galpon.htm", '', true);
        $email['reply_email'] = $this->reply_email;
        $email['reply_nicename'] = $this->reply_nicename;
        $email['attachments'] = array(
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/mi_galpon_informe.docx'
            =>'Modelo Informe en Caracter de Declaración Jurada 2016.docx',
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/mi_galpon_test_escr_pfisica.docx'
            =>'Testimonio de Escritura Pública para Persona física.docx',
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/mi_galpon_test_escr_pjuridica.docx'
            =>'Testimonio de Escritura Pública para Personas Jurídicas.docx'
            );
        
        return $email;
    }

    function email_parques(){

        $email['subject'] = 'Presentación "Parques Industriales"';
        $email['body'] = $this->load->view("financiamiento/cuerpos_emails/parques.htm", '', true);
        $email['reply_email'] = $this->reply_email;
        $email['reply_nicename'] = $this->reply_nicename;
        $email['attachments'] = array(
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/parques_certificacion.docx'
            =>'Modelo de Cerificación Contable Parques Industriales 2016.docx',
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/parques_escritura_pfisicas.docx'
            =>'Modelo de Escritura Pública para Personas Físicas.docx',
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/parques_escritura_pjuridcas.docx'
            =>'Modelo de Escritura Pública para Personas Jurídicas.docx'
            );
        
        return $email;
    }

    function email_rbt_bice(){

        $email['subject'] = 'Régimen de Bonificación de Tasa.';
        $email['body'] = $this->load->view("financiamiento/cuerpos_emails/rbt_bice.htm", '', true);
        $email['reply_email'] = $this->reply_email;
        $email['reply_nicename'] = $this->reply_nicename;
        $email['attachments'] = array(
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/rbt_bice_convenio.pdf'
            =>'Convenio Bice.pdf',
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/rbt_bice_preanalisis.pdf'
            =>'Preanalisis Bice.docx'
            );
        
        return $email;
    }

    function email_rbt_bna(){

        $email['subject'] = 'Régimen de Bonificación de Tasa.';
        $email['body'] = $this->load->view("financiamiento/cuerpos_emails/rbt_bna.htm", '', true);
        $email['reply_email'] = $this->reply_email;
        $email['reply_nicename'] = $this->reply_nicename;
        $email['attachments'] = array(
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/rbt_bna_convenio.pdf'
            =>'Convenio Bna.pdf'
            );
        
        return $email;
    }
    
    function email_pyme_bancario_otros(){

        $email['subject'] = 'Líneas de Crédito. Banco BICE';
        $email['body'] = $this->load->view("financiamiento/cuerpos_emails/otros_pyme_banc.htm", '', true);
        $email['reply_email'] = $this->reply_email;
        $email['reply_nicename'] = $this->reply_nicename;
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/gran_empresa/bancario/preanalisis.pdf'
            =>'Preanalisis.pdf'
            );
        
        return $email;
    }
}




