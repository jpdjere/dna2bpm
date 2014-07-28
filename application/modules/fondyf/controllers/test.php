<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jul 28, 2014
 */
class test extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;
        $this->dna2 = $this->load->database('dna2', true, true);
    }

    function Index() {
        echo "<h2>Pongo empresa de juan-borda a Limon</h2>";
        $idempresa = 3520936162;
        $idu_limon = 1574513092;
        $update = array("owner" => $idu_limon);
        $criteria = array("id" => $idempresa);
        $this->db
                ->where($criteria)
                ->update('container.empresas', $update);

        echo "Mongo ok:<br/>";
        $this->dna2->where(array(
            'id' => $idempresa
        ));
        $this->dna2->update('idsent', array('idu' => $idu_limon));
        echo "DNA2 ok:<br/>";
    }

    function undo() {
        echo "<h2>Pongo empresa de juan-borda a juan-borda</h2>";
        $idempresa = 3520936162;
        $idu_jb = 1;
        $update = array("owner" => $idu_jb);
        $criteria = array("id" => $idempresa);
        $this->db
                ->where($criteria)
                ->update('container.empresas', $update);

        echo "Mongo ok:<br/>";
        $this->dna2->where(array(
            'id' => $idempresa
        ));
        $this->dna2->update('idsent', array('idu' => $idu_jb));
        echo "DNA2 ok:<br/>";
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */