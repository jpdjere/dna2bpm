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
        $this->lang->load('library', $this->config->item('language'));
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

    function notificacion($idwf, $idcase, $resourceId) {
        $this->load->model('bpm/bpm');
        $this->load->library('parser');
//        $this->bpm->debug['load_case_data'] = true;
        $user=$this->user->getuser((int) $this->session->userdata('iduser'));
        $case = $this->bpm->get_case($idcase, $idwf);
        $this->user->Initiator=$case['iduser'];
        $token = $this->bpm->get_token($idwf, $idcase, $resourceId);
        //---saco título para el resultado
        $mywf = $this->bpm->load($idwf);
        $wf = $this->bpm->bindArrayToObject($mywf ['data']);
        //---tomo el template de la tarea
        $shape = $this->bpm->get_shape($resourceId, $wf);

        $data = $this->bpm->load_case_data($case, $idwf);
        $data['user'] = (array) $user;
        $data['date'] = date($this->lang->line('dateFmt'));
        $msg['from'] = $this->idu;
        $msg['subject'] = $this->parser->parse_string($shape->properties->name, $data, true, true);
        $msg['body'] = $this->parser->parse_string($shape->properties->documentation, $data, true, true);

        $msg['idwf'] = $idwf;
        $msg['case'] = $idcase;
        if ($shape->properties->properties <> '') {
            foreach ($shape->properties->properties->items as $property) {
                $msg[$property->name] = $property->datastate;
            }
        }
        $resources = $this->bpm->get_resources($shape, $wf, $case);
        //---if has no messageref and noone is assigned then
        //---fire a message to lane or self         
//            if (!count($resources['assign']) and !$shape->properties->messageref) {
//                $lane = $this->bpm->find_parent($shape, 'Lane', $wf);
//                //---try to get resources from lane
//                if ($lane) {
//                    $resources = $this->bpm->get_resources($lane, $wf);
//                }
//                //---if can't get resources from lane then assign it self as destinatary
//                if (!count($resources['assign']))
//                    $resources['assign'][] = $this->user->Initiator;
//            }
        //---process inbox--------------
        $to = (isset($resources['assign'])) ? array_merge($token['assign'], $resources['assign']) : $token['assign'];
        $to = array_unique(array_filter($to));
        foreach($to as $iduser){
            $user=$this->user->get_user_safe($iduser);
            $msg['to'][]=$user;
        }
        var_dump($msg);
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */