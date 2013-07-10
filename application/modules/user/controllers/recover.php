<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Recover extends MX_Controller {

    public function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'user/';
        //----load parser
        $this->load->library('parser');
        $this->load->library('email');
        $this->load->config('config');   
    }


    function Index() {
        $msg = $this->session->userdata('msg');
        //----LOAD LANGUAGE
        $this->lang->load('recover', $this->config->item('language'));
        //---add language data
        $cpData['lang'] = $this->lang->language;

        $cpData['title'] = 'Forgot Password Form';
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['theme'] = $this->config->item('theme');
        //----NO USER

        if ($msg == 'nouser') {
            $cpData['msgcode'] = $this->lang->line('nousr');
        }
        //----USER DOESN'T HAS PROPPER LEVELS

        if ($msg == 'nolevel') {
            $cpData['msgcode'] = $this->lang->line('nolevel') . "<br>" . $this->session->userdata('redir');
        }

        //----USER has to be logged first
        if ($msg == 'hastolog') {
            $cpData['msgcode'] = $this->lang->line('hastolog') . "<br>" . $this->session->userdata('redir');
        }
        
        $this->session->set_userdata('msg', $msg);
        //---build UI 
        //---define files to viewport
        $cpData['css'] = array($this->module_url . "assets/css/login.css" => 'Login Specific');
        
        //---
        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'show_warn' =>$this->config->item('show_warn'),
            'msg' => $msg,
            'msgcode' => (isset($cpData['msgcode'])) ? $cpData['msgcode'] : ''
        );
        $cpData['show_warn']=($this->config->item('show_warn') and $msg<>'');
        //----clear data
         $this->session->unset_userdata('msg');

        $this->ui->compose('user/recover.php','user/bootstrap.ui.php',$cpData);
    }
    
    function Send() {
        
        $clean['email']  = $this->input->post('mail');
        echo "entro:".$clean['email'];
  ////////////////////////////////////////////////            
//        $email_pattern = '/^[^@\s<&>]+@([-a-z0-9]+\.)+[a-z]{2,}$/i';
//        if (!preg_match($email_pattern, $_POST['email']))
//        {
//        exit("0, Ingrese un email v�lido");
//        }
        // Chequeo datos atraves del email
        $dbobj=(array)$this->user->getbymailaddress($clean['email']);
       
        // Envio
        if(isset($dbobj['idu'])){ 

            $token=md5($dbobj['email'].$dbobj['idu']);

            $content="<h2>Estimado usuario, </h2>";
            $content.="<p>Hemos recibido un pedido de reseteo de contrase�a a su nombre.</p>";
            $content.="<p>Su nombre de usuario es: <strong>{$dbobj['nick']}</strong></p>";
            $content.="<p>Si ha sido efectuado por Ud. simplemente haga click en el link al pie y ud podr� elegir su nueva contrase�a.</p>";
            $content.="<a href='{$this->base_url}user/recover/new_pass/token=$token&uid={$dbobj['idu']}'>Quiero resetear mi clave</a>";

            $this->email->clear();
            $config['mailtype'] = "html";
            $this->email->initialize($config);
            $this->email->set_newline("\r\n");
            $this->email->from('dna2@industria.gob.ar', 'Soporte');
            $list = array($clean['email']); //$list = array('xxx@gmail.com', 'xxx@gmail.com');
            $this->email->to($list);
            $data = array();
            $this->email->subject('Reseteo de contrase�a sistema DNA2');
            $this->email->message($content);



            if ($this->email->send()){
                //echo 'Your email was sent, thanks chamil.';
                //save token
                
            }else show_error($this->email->print_debugger());
            
                
        }else{
        exit("0, No se ha podido enviar el email. No existe el email o el DNI.");
        }

        
        
    }
    
    
    function ChangePassword(){
        
        $clean['email']  = $this->input->post('mail');
        echo "entro:".$clean['email'];
        
//        
//        if($_REQUEST["cmd"]=='changePassToken'){
//$clean = array();
//$clean['passw'] = htmlspecialchars (utf8_decode($_POST["passw"]));
//$clean['uid'] = htmlspecialchars ($_POST["uid"]);
//
//if(strlen($clean['passw'])<5){
//exit("0, Su contrase�a debe tener al menos 5 car�cteres.");
//}
//
//
//// Solo puede cambiar si tiene no pass
//$SQL="select * from users where idusuario={$clean['uid']} and passw=md5('nopass')";
//$rs1=$forms2->Execute($SQL);
//	if($rs1){
//		$token2=md5($rs1->fields['email'].$rs1->fields['idusuario']);
//		if($token2==$_POST["token"]){
//		$SQL="UPDATE users SET passw=MD5('".$clean['passw']."') WHERE idusuario={$clean['uid']}";
//		$usuario=$forms2->Execute($SQL) or die($forms2->ErrorMsg()."<br>$SQL");
//		$_SESSION['idu']=$rs1->Fields("idusuario");
//		$redir="/appfront/index.php";
//		exit("1,$basedir$redir");
//		}else{
//		exit("0, Ha habido alg�n error $SQL");
//		}
//		
//	}else{
//	exit("0, Ha habido alg�n error");
//	}
//}
//
//        
   }
    

}


?>
