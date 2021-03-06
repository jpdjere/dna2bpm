<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Dec 9, 2013
 */
class Test extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (double) $this->session->userdata('iduser');
    }

    function Index() {
        echo "<h3>get_groups</h3>";
        var_dump($this->group->get_groups());
    }

    function ldap() {

        echo "<h1>LDAP TEST</h1>";
        echo "<h3>Authenticate: jborda -> jborda1234</h3>";
        $userId = $this->user->authenticate('jborda', 'jborda1234');
        var_dump('$userId', $userId);
        echo "<h3>get_user(2002)</h3>";
        var_dump($this->user->get_user($userId));
        echo "<h3>get_groups</h3>";
        var_dump($this->group->get_groups());
        echo "<h3>get_group by gidnumber 1010</h3>";
        var_dump($this->group->get(1010));
        echo "<h3>genid()</h3>";
        $newid = $this->group->genid();
        var_dump($newid);
        echo "<h3>Add Group:$newid</h3>";
        $group = $this->group->save(
                array(
                    'idgroup' => $newid,
                    'name' => 'SQL/Admin/Test_' . $newid
                )
        );
        echo "<h3>Remove Group:$newid</h3>";
        $this->group->delete($newid);
    }
    
    function get_avatar(){
    echo 'me:'. $this->user->get_avatar().'<hr/>';    
    echo 'UserId:10:'. $this->user->get_avatar(10);    
    }
    
    function testint(){
        $users=$this->mongowrapper->db->users->find();
        while ($user=$users->getNext()){
            if(isset($user['idu'])){
                if(gettype($user['idu']*1)<>'integer'){
                    echo "Not Int:".$user['idu'].'->'.gettype($user['idu']*1).'<br/>';
                }
            }
        }
    }
    function group(){
        $this->load->model('user/group');
        $this->load->helper('bpm/bpm');
        
        echo "<h2>GenId</h2>";
        var_dump2($this->group->genid());
        
        echo "<h2>get_count</h2>";
        var_dump2($this->group->get_count(1));
        
        echo "<h2>get(1)</h2>";
        var_dump2($this->group->get(1));
        
        echo "<h2>getbyid(1)</h2>";
        var_dump2($this->group->getbyid(1));
        
        echo "<h2>get_byname('Equipo DNA²')</h2>";
        var_dump2($this->group->get_byname('Equipo DNA²'));
        
        echo '<h2>save($object)</h2>';
        $group=$this->group->get(1);
        $group['checkdate']=date('Y-m-d H:i:s');
        var_dump2($this->group->save($group));
        var_dump2($this->group->getbyid(1));
        
        echo "<h2>get_groups(\$order = null, 'FonDyF')</h2>";
        var_dump2($this->group->get_groups(null, 'FonDyF'));
        
        echo "<h2>save(\$data)</h2>";
        $group=$this->group->save(array(
            'idgroup'=>15000,
            'name'=>'TESTO GROUP',
            'desc'=>'delete me !',
            ));
        
        var_dump2($group);
        $idgroup=$group['idgroup'];
        
        echo "<h2>DELETE($idgroup)</h2>";
        var_dump($idgroup);
        var_dump2($this->group->delete($idgroup));
        
    }
    
    function user(){
        $this->load->helper('bpm/bpm');
        $user=json_decode('{
    "_id" : ObjectId("515f6eccc93c9d544982fb93"),
    "birthDate" : "0000-00-00 00:00:00",
    "checkdate" : "2011-06-29 21:06:14",
    "email" : "omarelectric@gmail.com",
    "group" : [ 
        8
    ],
    "idgroup" : 8,
    "idnumber" : "30323981",
    "idu" : 1785499079,
    "lastacc" : "2015-05-04 16:01:15",
    "lastname" : "ricail",
    "name" : "maria",
    "nick" : "meri",
    "notification_by_email" : "yes",
    "owner" : 666,
    "passw" : "f8461b554d59b3014e8ff5165dc62fac",
    "phone" : "0378315910111"
}'
);
        echo "<h2>genid()</h2>";
        var_dump2($this->user->genid());
        
        echo "<h2>genid(1)</h2>";
        var_dump2($this->user->genid(1));
    }
}
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */