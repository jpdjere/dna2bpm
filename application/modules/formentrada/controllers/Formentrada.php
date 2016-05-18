<?php
header("access-control-allow-origin: *");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * inventory
 * 
 * Description of the class
 * 
 * @author Martin González 
 * @date    Apr 20, 2015
 */
class formentrada extends MX_Controller {

    function __construct() {
        parent::__construct();
         //$this->load->model('user/user');
         //$this->load->model('user/group');
         $this->load->model('model_formentrada');
         $this->load->model('app');
         //$this->user->authorize('modules/formentrada');
         $this->load->library('parser');
//         $this->load->library('ui');
// //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'formentrada/';
// ;
// //----LOAD LANGUAGE
        // $this->idu = (float) $this->session->userdata('iduser');
// //---config
         //$this->load->config('formentrada/config');
// //---QR
         //$this->load->module('qr');
    }

    
    function Index(){
    /*
    $this->user->authorize();
	$this->load->module('dashboard');
	$this->dashboard->dashboard('formentrada/json/formentrada.json');
    */
    $customData = array();
    $customData['base_url'] = $this->base_url;
    echo $this->parser->parse('formentrada/formentrada_table-1',$customData,true,true);
    }
    
    function segundo_formulario(){
    $customData = array();
    $customData['base_url'] = $this->base_url;
    /*
    $idop = 750;
    $options = $this->app->get_ops($idop);
    $select_afip = '';
    foreach($options as $field=>$value) {
        $select_afip = $select_afip.'<option value="'.$field.' - '.$value.'">'.$field.' - '.$value.'</option>';
        
        }
    $customData['select_afip'] = $select_afip;*/
    
    
    $return['tabla'] = $this->parser->parse('formentrada/formentrada_table-2',$customData,true,true);
    
    //$return['tabla'] = '<p>Prueba!!</p>';
    echo json_encode($return);
    //return $return;
    }
    
    function buscar_clanae(){
    $customData = array();
    $customData['base_url'] = $this->base_url;
    $clanae = array();
    $clanae = $this->input->post();
    $idop = 750;
    $options = $this->app->get_ops($idop);
    $select_afip = '';
    foreach($options as $field=>$value) {
        if($field == $clanae['clanae']){
            $select_afip = $value;
        }
        }
    if($select_afip == ''){    
        $select_afip = 'No se encontro el código en nuestra base de datos.';    
    }
    //$customData['select_afip'] = $select_afip;
    
    //$select_afip = 'Prueba';
    //$return['tabla'] = $this->parser->parse('formentrada/formentrada_table-2',$customData,true,true);
    
    //$return['tabla'] = '<p>Prueba!!</p>';
    echo json_encode($select_afip);
    //return $return;
    }
    
    
   //$this->input->post('data');
    
    function guardar_datos(){
    $customData = array();
    $fields = array();
    $fields = $this->input->post();
    $options = array();
    /*$idop = 748;
    $options = $this->app->get_ops($idop);
    $fields['ops1'] = $options;
    $idop = 749;
    $options = $this->app->get_ops($idop);
    $fields['ops2'] = $options;*/
    $idop = 750;
    $options = $this->app->get_ops($idop);
    $select_afip = '';
    
    foreach($options as $field=>$value) {
        if($field == $fields['fields2'][8]['value']){
            $select_afip = $value;
        }
        }
    if($select_afip == ''){    
        $select_afip = 'No se encontro el código en nuestra base de datos.';    
    }
    $fields['fields2'][8]['detalle'] =  $select_afip;
    //var_dump($fields2);
    //$fields = json_decode($fields2);
    $model = 'model_formentrada';
    $result = $this->load->model($model);
    $this->model_formentrada->insert_registros($fields);
    //$customData['base_url'] = $this->base_url;
    //$return['tabla'] = $fields[0];
    $result = true;
    //$return['tabla'] = '<p>Prueba!!</p>';
    //echo json_encode($fields2);
    return $result;
    }
    
    
    function send_mail() {
        $attach = "/var/www/dna2bpm/application/modules/formentrada/assets/ANEXO II - Formulario FONAPYME PRODUCCION ESTRATEGICA.xls";
        //$fields = array();
        $email = $this->input->post('email');
        //if (property_exists($user,'email')) {
            $this->load->library('phpmailer/phpmailer');
            $this->load->config('email');
            $ok = false;
            $mail = $this->phpmailer;
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host = $this->config->item('smtp_host'); // SMTP server
            $mail->SMTPDebug = 2;                     // enables SMTP debug information (for testing)
// 1 = errors and messages
// 2 = messages only
            //$mail->SetFrom($this->config->item('smtp_user'), $this->config->item('smtp_user_name'));
            $mail->SetFrom("fonapyme@produccion.gob.ar", "FONAPYME");
            $mail->Subject = utf8_decode(/*$this->config->item('mail_suffix').' ' .*/ "Formulario Presentación FONAPYME");
            $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
            $mail->IsHTML(true);
            $mail->MsgHTML(utf8_decode(nl2br("Este mail se envió en forma automática desde la página del Ministerio de Producción.
Tengo el agrado de dirigirme a usted, a efectos de comunicarle que, de acuerdo al análisis de la información presentada, en principio, su proyecto se ajusta a lo establecido en las Bases y Condiciones del Llamado a Concurso Público de Proyectos para el Fondo Nacional de Desarrollo de la Micro, Pequeña y Mediana Empresa (FONAPyME). Por tal motivo, se adjunta al presente el Anexo II “Formulario de Presentación de Proyectos”, el cual deberá ser cumplimentado por la empresa requirente.
Para que las presentaciones sean admitidas, los interesados deberán cumplir con todos y cada uno de los siguientes requisitos:
1. Realizar la presentación de la información y documentación requerida en el FONAPYME.
2. Cada proyecto, junto a la documentación requerida para cada caso, se entregará encarpetado (no anillado), en hojas tamaño A4, en un juego original. Se deberán firmar todas y cada una de las hojas de los formularios y de la documentación anexa presentada. A su vez, deberá acompañarse en soporte digital, con identificación de la empresa, según consta en el Formulario de Presentación de Proyectos, debidamente completado.
3. Deberá ser presentado en la Mesa de Entradas de la SECRETARÍA DE EMPRENDEDORES Y DE LA PEQUEÑA Y MEDIANA EMPRESA del MINISTERIO DE PRODUCCIÓN, sita en la Av. Julio A. Roca 651, Planta Baja, Sector 2, de la Ciudad Autónoma de Buenos Aires , y en todos aquellos sitios que el MINISTERIO DE PRODUCCIÓN determine oportunamente. Las presentaciones enviadas por correo serán aceptadas cuando la fecha del matasellos postal indique que la remisión fue realizada dentro de las SETENTA Y DOS HORAS (72 hs) hábiles posteriores a la fecha y hora de cada cierre de la Convocatoria.")));

            $mail->AddAddress($email, "");
            $mail->AddAttachment($attach, "ANEXO II - Formulario FONAPYME PRODUCCION ESTRATEGICA.xls");

//        $mail->AddAttachment("images/phpmailer.gif");      // attachment
//        $mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
            
            if (!$mail->Send()) {
                return "error: " . $mail->ErrorInfo;
            } else {
                return true;
            }
        //}
    }
    
     function send_mail_1() {
        $attach = "/var/www/dna2bpm/application/modules/formentrada/assets/ANEXO II - Formulario FONAPYME - FORTALECIMIENTO COMPETITIVO.xls";
        //$fields = array();
        $email = $this->input->post('email');
        //if (property_exists($user,'email')) {
            $this->load->library('phpmailer/phpmailer');
            $this->load->config('email');
            $ok = false;
            $mail = $this->phpmailer;
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host = $this->config->item('smtp_host'); // SMTP server
            $mail->SMTPDebug = 2;                     // enables SMTP debug information (for testing)
// 1 = errors and messages
// 2 = messages only
            //$mail->SetFrom($this->config->item('smtp_user'), $this->config->item('smtp_user_name'));
            $mail->SetFrom("fonapyme@produccion.gob.ar", "FONAPYME");
            $mail->Subject = utf8_decode(/*$this->config->item('mail_suffix').' ' . */"Formulario Presentación FONAPYME");
            $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
            $mail->IsHTML(true);
            $mail->MsgHTML(utf8_decode(nl2br("Este mail se envió en forma automática desde la página del Ministerio de Producción.
Tengo el agrado de dirigirme a usted, a efectos de comunicarle que, de acuerdo al análisis de la información presentada, en principio, su proyecto se ajusta a lo establecido en las Bases y Condiciones del Llamado a Concurso Público de Proyectos para el Fondo Nacional de Desarrollo de la Micro, Pequeña y Mediana Empresa (FONAPyME). Por tal motivo, se adjunta al presente el Anexo II “Formulario de Presentación de Proyectos”, el cual deberá ser cumplimentado por la empresa requirente.
Para que las presentaciones sean admitidas, los interesados deberán cumplir con todos y cada uno de los siguientes requisitos:
1. Realizar la presentación de la información y documentación requerida en el FONAPYME.
2. Cada proyecto, junto a la documentación requerida para cada caso, se entregará encarpetado (no anillado), en hojas tamaño A4, en un juego original. Se deberán firmar todas y cada una de las hojas de los formularios y de la documentación anexa presentada. A su vez, deberá acompañarse en soporte digital, con identificación de la empresa, según consta en el Formulario de Presentación de Proyectos, debidamente completado.
3. Deberá ser presentado en la Mesa de Entradas de la SECRETARÍA DE EMPRENDEDORES Y DE LA PEQUEÑA Y MEDIANA EMPRESA del MINISTERIO DE PRODUCCIÓN, sita en la Av. Julio A. Roca 651, Planta Baja, Sector 2, de la Ciudad Autónoma de Buenos Aires , y en todos aquellos sitios que el MINISTERIO DE PRODUCCIÓN determine oportunamente. Las presentaciones enviadas por correo serán aceptadas cuando la fecha del matasellos postal indique que la remisión fue realizada dentro de las SETENTA Y DOS HORAS (72 hs) hábiles posteriores a la fecha y hora de cada cierre de la Convocatoria.")));

            $mail->AddAddress($email, "");
            $mail->AddAttachment($attach, "ANEXO II - Formulario FONAPYME - FORTALECIMIENTO COMPETITIVO.xls");

//        $mail->AddAttachment("images/phpmailer.gif");      // attachment
//        $mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
            
            if (!$mail->Send()) {
                return "error: " . $mail->ErrorInfo;
            } else {
                return true;
            }
        //}
    }
    function send_mail_2() {
        $attach = "/var/www/dna2bpm/application/modules/formentrada/assets/Línea de Financiamiento BICE - INV, BK y KT- 2016.pdf";
        $attach1 = "/var/www/dna2bpm/application/modules/formentrada/assets/Preanálisis.pdf";
        //$fields = array();
        $email = $this->input->post('email');
        //if (property_exists($user,'email')) {
            $this->load->library('phpmailer/phpmailer');
            $this->load->config('email');
            $ok = false;
            $mail = $this->phpmailer;
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host = $this->config->item('smtp_host'); // SMTP server
            $mail->SMTPDebug = 2;                     // enables SMTP debug information (for testing)
// 1 = errors and messages
// 2 = messages only
            //$mail->SetFrom($this->config->item('smtp_user'), $this->config->item('smtp_user_name'));
            $mail->SetFrom("rbt@produccion.gob.ar", utf8_decode("Régimen de Bonificación de Tasas"));
            $mail->Subject = utf8_decode("Régimen de Bonificación de Tasas");
            $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
            $mail->IsHTML(true);
            $mail->MsgHTML(utf8_decode(nl2br("Este mail se envió en forma automática desde la página del Ministerio de Producción.
            Tengo el agrado de dirigirme a usted, a efectos de comunicarle que, de acuerdo al análisis de la información presentada, en principio, su proyecto se ajusta a ser financiado por las líneas de crédito bancarias con bonificación de tasas (Régimen de bonificación de tasas). Se deja constancia que la presente no implica el otorgamiento del derecho a esa empresa a la aprobación del crédito en cuestión. Por tal motivo, se adjunta al presente la alternativa de financiamiento vigente para su análisis, y en caso de cumplir con los requisitos podrá hacer su presentación ante la entidad financiera para su calificación como sujeto de crédito de la línea. Asimismo, la presentación en la entidad financiera deberá contener la información que se detalla en el adjunto Preanálisis. 
En caso que así lo requiera, se podrá hacer una derivación y contacto con el Banco. Nuestro mail de contacto es rbt@produccion.gob.ar
Para ello será necesario indique mail, teléfono y responsable del contacto con la entidad financiera..")));

            $mail->AddAddress($email, "");
            $mail->AddAttachment($attach, utf8_decode("Línea de Financiamiento BICE - INV, BK y KT- 2016.pdf"));
            $mail->AddAttachment($attach1, utf8_decode("Preanálisis.pdf"));
//        $mail->AddAttachment("images/phpmailer.gif");      // attachment
//        $mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
            
            if (!$mail->Send()) {
                return "error: " . $mail->ErrorInfo;
            } else {
                return true;
            }
        //}
    }
   
   /*
   
   function formentrada_cuadro(){
        $model='model_formentrada';
        $customData = array();
        $customData['base_url'] = $this->base_url;
       
        
        echo $this->parser->parse('formentrada/formentrada_table',$customData,true,true);
        
   }
   */
   
   
   
    
}

