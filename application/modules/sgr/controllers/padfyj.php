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
        $this->db->switch_db('sgr');
    }

    function Index() {
        set_time_limit(3600 * 8);
        $this->output->enable_profiler(true);
        $this->load->model('padfyj_model');
        $filename_base = '/home/juanb/Desktop/tmp/Persona Física/padfyj/padfyj_%.txt';
        $i = 1;
        for ($i = 5; $i <= 9; $i++) {
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

        header("Content-Description: File Transfer");
        header("Content-type: application/x-msexcel");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=padfyj.xls");
        header("Content-Description: PHP Generated XLS Data");

        $rtn = "";
        foreach ($this->empresa() as $data) {
            $fields = array('CUIT', 'DENOMINACION');
            $container = 'padfyj';
            $query = array('CUIT' => $data[0]);
            $result = $this->mongo->db->$container->find($query, $fields);
            $result->timeout(100000);
            foreach ($result as $result) {
                $rtn .= "<tr><td>" . $result['CUIT'] . "</td><td>" . $result['DENOMINACION'] . "</td></tr>";
            }
        }
        echo "<table>";
        echo "<tr><th>CUIT</th><th>DENOMINACION</th></tr>";
        echo $rtn;
        echo "</table>";
    }

    function empresa() {
        $rtn = array();
        $query = array();
        $fields = array(1695);
        $container = 'container.empresas';
        $result = $this->mongo->db->$container->find();
        $result->timeout(100000);
        //$result->limit(5);
        foreach ($result as $result) {
            unset($result['_id']);
            unset($result['id']);

            $result['CUIT'] = str_replace("-", "", $result[1695]);
            $rtn[] = array($result['CUIT']);
        }

        return $rtn;
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */