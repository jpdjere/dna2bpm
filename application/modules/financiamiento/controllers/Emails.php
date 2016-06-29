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
    }
    
    function Index() {
        //$this->session->set_userdata(array('iduser'=>756148209, 'loggedin'=>true));
        //redirect($this->base_url.'bpm/engine/newcase/model/form_entrada');
    }

    function email_gran_empresa_bancario(){

        $email['subject'] = 'Líneas de Crédito. Banco BICE';
        $email['body'] = 'Este mail se envió en forma automática desde la página del Ministerio de Producción.
        De acuerdo a los datos enviados mediante el formulario, hoy en día su solicitud podría canalizarse por medio de las líneas de crédito para Grandes Empresas disponibles en Banco BICE.
        En líneas generales, para proyectos de inversión, el financiamiento es de hasta $200 millones o su equivalente en dólares, con un plazo de hasta 15 años a tasa “Badlar Bancos Privados” más el spread que el BICE determine en cada caso. En dólares con un plazo de hasta 10 años en dólares, a tasa Libor más el spread que el BICE determine en cada caso.
        En caso de estar interesado, la empresa deberá hacer la presentación ante la entidad financiera para su calificación como sujeto de crédito de la línea.  La misma, deberá contener la información que se detalla en el adjunto Pre-análisis. 
        Para mayor información podrá ingresar al siguiente link  http://www.bice.com.ar/es/
        Se deja constancia que la presente no implica el otorgamiento del derecho a esa empresa a la aprobación del crédito en cuestión
        Cualquier duda o dificultad que pudiera surgir comunicarse con financiamiento@producción.gob.ar o  comercial@bice.com.ar. 
        ';
        $email['reply_email'] = 'financiamiento@producción.gob.ar';
        $email['reply_nicename'] = 'Financiamiento Ministerio de Producción';
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/gran_empresa/bancario/preanalisis.pdf'=>'Preanalisis.pdf');
        
        return $email;
    }
    
    function email_gran_empresa_no_bancario(){

        $email['subject'] = 'Solicitud de información complementaria. Ministerio de Producción.';
        $email['body'] = 'Este mail se envió en forma automática desde la página del Ministerio de Producción.
        De acuerdo al análisis de la información presentada consideramos necesario profundizar el estudio del caso para encontrar la mejor alternativa de financiamiento que se corresponda con las características de su empresa y proyecto.
        Una vez completada la planilla, deberá enviarse a: financiamiento@producción.gob.ar con el asunto: ENVIO DE INFORMACIÓN ADICIONAL- NOMBRE DE EMPRESA.
        Cualquier duda o dificultad que pudiera surgir comunicarse con financiamiento@producción.gob.ar
        ';
        $email['reply_email'] = 'financiamiento@producción.gob.ar';
        $email['reply_nicename'] = 'Financiamiento Ministerio de Producción';
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/gran_empresa/no_bancario/plantilla_empresa.xlsx'=>'Plantilla Empresa.xlsx');
        
        return $email;
    }
}




