<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * padfyj
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Oct 21, 2013
 */
class Padfyj extends MX_Controller {

    function __construct() {
        parent::__construct();
    }

    function Index() {
        $this->load->model('padfyj_model');
        $filename_base = '/home/juanb/Desktop/tmp/Persona Física/padfyj/padfyj_%.txt';
        $i = 1;
        for ($i = 0; $i <= 4; $i++) {
            $filename = str_replace('%', $i, $filename_base);
            echo "<h3>Procesando: $filename </h3>";
            $file = @fopen($filename, "r");
            $j = 1;
            while (($buffer = fgets($file, 4096)) !== false /* and $j <= 10*/) {
                //---sólo proceso si el chenk tiene 191 caracteres
                if (strlen($buffer) == 191) {
                    $data = array(
                        'CUIT' => substr($buffer, 0, 11),
                        'DENOMINACION' =>  utf8_encode( trim(substr($buffer, 11, 160))),
                        'ACTIVIDAD' => trim(substr($buffer, 171, 6)),
                    );
                    $retult=$this->padfyj_model->save($data);
                    //var_dump($result);
                }
                $j++;
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */