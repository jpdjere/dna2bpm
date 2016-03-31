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
        $this->idu = (float) $this->session->userdata('iduser');
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;
        $this->db->switch_db('padfyj');
    }

    function Index() {
        set_time_limit(3600 * 8);
        $this->output->enable_profiler(true);
        $this->load->model('padfyj_model');
        $filename_base = '/home/ich/Desktop/padfyj/padfyj_%.txt';
        $i = 1;
        for ($i = 9; $i <= 10; $i++) {
            $filename = str_replace('%', $i, $filename_base);
            echo "<h3>Procesando: $filename </h3>";
            $file = @fopen($filename, "r");
            $j = 1;
            while (($buffer = fgets($file, 4096)) !== false /* and $j <= 10 */) {
                //---sólo proceso si el chenk tiene 191 caracteres
                if (strlen($buffer) == 191) {
                    $data = array(
                        'CUIT' => substr($buffer, 0, 11),
                        'DENOMINACION' => utf8_encode(trim(substr($buffer, 11, 160))),
                        'ACTIVIDAD' => trim(substr($buffer, 171, 6)),
                    );
                    $retult = $this->padfyj_model->save($data);
                    //var_dump($result);
                }
                $j++;
            }
        }
    }

    function info() {
        //header('Content-type: text/html; charset=UTF-8');

        header("Content-Description: File Transfer");
        header("Content-type: application/x-msexcel");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=padfyj.xls");
        header("Content-Description: PHP Generated XLS Data");

        $rtn = "";

        //var_dump($this->empresa());
        foreach ($this->empresa() as $data) {



            $fields = array('CUIT', 'DENOMINACION');
            $container = 'padfyj';
            $query = array('CUIT' => $data);
            $this->load->model('sgr_model');
            $this->load->model('padfyj_model');

            //clae2013
            ini_set("error_reporting", E_ALL);


            $results = $this->mongowrapper->padfyj->$container->find($query);

            $results->timeout(100000);
            foreach ($results as $result) {
                $actividad = "n/a";

                if ($result['ACTIVIDAD'])
                    $actividad = $this->padfyj_model->search_code($result['ACTIVIDAD']);


                $rtn .= "<tr><td>" . $result['CUIT'] . "</td><td>" . $result['DENOMINACION'] . "</td><td> (" . $result['ACTIVIDAD'] . ")</td><td> " . $actividad . "</td></tr>";
            }
        }
        echo "<table>";
        echo "<tr><th>CUIT</th><th>DENOMINACION</th><th>CODIGO</th><th>ACTIVIDAD</th></tr>";
        echo $rtn;
        echo "</table>";
    }

    function empresa() {
        $rtn = array();
        $query = array();
        $fields = array(1695);
        $container = 'container.empresas';
        $result = $this->mongowrapper->db->$container->find();
        $result->timeout(100000);
        //$result->limit(5);
        foreach ($result as $result) {
            unset($result['_id']);
            unset($result['id']);

            $result['CUIT'] = str_replace("-", "", $result[1695]);
            $rtn[] = array($result['CUIT']);
        }
	
	$empresas = array('33630659219','20939368508');
        

        return $empresas;
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
