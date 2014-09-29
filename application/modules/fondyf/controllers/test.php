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
        $this->user->authorize();
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

    function fix_8339() {
        $query = json_decode('{"$and":[{"8339":{"$exists":true}},{"8339":{"$ne":""}}]}', true);
        var_dump($query);
//        exit;
        $this->db->where($query, true);
        $this->db->select();
        $this->db->order_by(array('8339' => 1));
        $rs = $this->db->get('container.proyectos_fondyf')->result();

        foreach ($rs as $proj) {
            //$user=$this->user->get_user($proj->idu);
            $query = array('data.Proyectos_fondyf.query.id' => $proj->id);
            $this->db->where($query);
            $this->db->select('id');
            $case = $this->db->get('case')->result();
            var_dump($case, $proj->id, $proj->{8339}
                    //,$proj->idu
                    //,$user->name.' '.$user->lastname
            );
            echo '<hr/>';
        }
    }

    function fix_data($case = null) {
        $this->load->model('bpm/bpm');
        $query = ($case) ? array('idwf' => 'fondyfpp', 'id' => $case) : array('idwf' => 'fondyfpp');
        $this->db->where($query);
        $rs = $case = $this->db->get('case')->result();
        foreach ($rs as $case) {
            $token=$this->bpm->get_token('fondyfpp', $case->id, 'oryx_508C9A17-620B-4A6F-8508-D3D14DAB6DA2');
            //---Create Token if not exists
            if(!$token){
                $token=array(
                    
                );
                $token['assign']=case['assign'];
                $token['checkdate']=case['checkdate'];
                $token['iduser']=case['iduser'];
            }
            var_dump($case->id,$token);
            //----empresa
            if(isset($case->data['Empresas'])){
                echo "Tiene empresa<br/>";
            }
            //----Proyecto
            if(isset($case->data['Proyectos_fondyf'])){
                echo "Tiene Projecto<br/>";
            }
            echo '<hr/>';
        }
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */