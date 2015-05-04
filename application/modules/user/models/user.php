<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Model {
    //---default discover policy
    var  $autodiscover=true;
    
    function __construct() {
        parent::__construct();
        $this->idu = $this->session->userdata('iduser');
        $this->config->load('user/config');
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;
        $this->autodiscover=($this->config->item('autodiscover'))?true:false;
    }
    

    function add($user_data) {
        $user = null;
        //---1st check if user exists by its idu
        if(!is_array($user_data['group']))
            $user_data['group'] = array_map('intval', explode(',', $user_data['group']));
        $user_data['idu'] = isset($user_data['idu']) ? $user_data['idu'] : null;
        if ($user_data['idu']) {
            //---set proper typo 4 id
            $user_data['idu'] = (int) $user_data['idu'];
            //---if found then update data
            $user = (array) $this->getbyid($user_data['idu']);
            //---add previous data not submited _id & iduser
            $user_data+=$user;
            //---Preserves password if not set, else make a hash
            $user_data['passw'] = ($user_data['passw'] == '') ? $user['passw'] : md5($user_data['passw']);

            $result = $this->save($user_data);
            //var_dump($result);
        } else {
            $user_data['idu'] = $this->genid();
            //---hash that password down
            $user_data['passw'] = $this->hash($user_data['passw']);
            $result = $this->save($user_data);
        }

        $user = $user_data;
        return $user;
    }

    private function hash($str) {
        return md5($str);
    }

    ////-----update last access
    private function update_lastacc($idu = null) {
        if ($idu) {
            $query = array('lastacc' => date('Y-m-d H:i:s'));
            $criteria = array('idu' => $idu);
            $this->db->update('users', $query, $criteria);
        }
    }

    public function getLevel() {
        return;
    }

    function authenticate($username = '', $password = '') {
        //----MD5 is used for password hashing
        if($username=='' or $password==''){
            return false;
        }
        $query = array('nick' => $username, 'passw' => $this->hash($password));
        $thisUser = $this->db->select(array('idu'))->get_where('users', $query)->result();
        if (isset($thisUser[0])) {
            $thisUser = $thisUser[0]; //---get first an d only first
            $this->update_lastacc($thisUser->idu);
            return $thisUser->idu;
        } else {
            return false;
        }
    }

    function authenticateByHash($username = '', $hash = '') {
        $query = array('nick' => $username, 'passw' => $hash);
        $thisUser = $this->db->select('idu')->get_where('users', $query)->result();
        if (isset($thisUser[0])) {
            $thisUser = $thisUser[0]; //---get first an d only first
            $this->update_lastacc($thisUser->idu);
            return $thisUser->idu;
        } else {
            return false;
        }
    }

    function authorize($reqlevel = null) {
//        $CI=& get_instance();
        $this->load->model('user/rbac');
        //---check if already logged in
        $this->isloggedin();

        $canaccess = false;
        //--first check if user still exists
        $thisUser = $this->get_user($this->idu);
        $class = $this->router->class;
        $method = $this->router->method;
        if (!$thisUser) {
            //----user doesn't exists in db
            $canaccess = false;
        } else {
            //----user exists
            //---define the path for module auth
            //var_dump($this->router);
            $path = str_replace('../', '', $this->router->fetch_directory() . implode('/', array_filter(array($class, $method))));
            /*
             * Auto-discover from existent will add all the paths it's hits
             * turn off for production
             */
            if($this->autodiscover){ 
                $this->rbac->put_path($path, array(
                    'source' => 'AutoDiscovery',
                    'checkdate' => date('Y-m-d H:i:s'),
                    'idu' => $this->idu
                ));
            }
            //---give access if belong to group ADMINS
            if ($this->isAdmin($thisUser)) {
                $canaccess = true;
            } else {
                //----$reqlevel override $path
                $path = (isset($reqlevel)) ? $reqlevel : $path;
                //---give access if have path exists
                if ($this->user->has('root/' . $path, $thisUser)) {
                    $canaccess = true;
                } else {
                    show_error("User doesn't have: $path </br>");
                }
            }
        }
        if (!$canaccess) {
            $this->session->set_userdata('redir', base_url() . uri_string());
            $this->session->set_userdata('msg', 'nolevel');
            redirect(base_url() . 'user/login');
        }
    }

    /*
     * Check if the user belong to Admin Group 
     */

    function isAdmin($thisUser = null) {
        if (!$thisUser)
            $thisUser = $this->user->get_user($this->idu);
        if ($this->isloggedin()) {
            //---this is the ADMIN policy
            if (in_array($this->config->item('groupAdmin'), $thisUser->group)) {
                return true;
            }
        }
        return false;
    }

    function isloggedin() {
        if (!$this->session->userdata('loggedin')) {
            $this->session->set_userdata('redir', base_url() . uri_string());
            $this->session->set_userdata('msg', 'hastolog');
            redirect(base_url() . 'user/login');
        } else {
            return true;
        }
    }

    function has($path, $thisUser = null) {
        if (!$thisUser)
            $thisUser = $this->user->get_user($this->idu);

        $this->db->where(array('path' => $path));
        $this->db->where_in('idgroup', $thisUser->group);
        $level = $this->db
                ->get('perm.groups')
                ->result();
        //$level=$this->db->result();

        if (count($level)) {
            return true;
        } else {
            return false;
        }
    }

    function getapps($idu) {
        
    }

    function getby_id($_id) {
        /**
         * returns single user with matching id
         */
        //var_dump(json_encode($query));
        //$this->db->debug = true;
        $this->db->where(array('_id' => new MongoId($_id)));
        $result = $this->db->get('users')->result();
        ///----return only 1st
        //$this->db->debug = false;
        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }
    function getby_token($token=null) {
        /**
         * returns single user with matching id
         */
        //var_dump(json_encode($query));
        //$this->db->debug = true;
        $this->db->where(array('token' =>$token));
        $result = $this->db->get('users')->result();
        ///----return only 1st
        //$this->db->debug = false;
        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    function getbyid($iduser) {
        /**
         * returns single user with matching id
         */
        //var_dump(json_encode($query));
        $this->db->where(array('idu' => (int) $iduser));
        $result = $this->db->get('users')->result();
        ///----return only 1st
        if (isset($result[0]->idu)) {
            return $result[0];
        } else {
            return false;
        }
    }

    function getbyids($arr_ids) {
        /**
         * returns an array with matching id's
         */
        $userarr = (array) json_decode($arr_ids);
        //var_dump(json_encode($query));
        $this->db->where_in('idu', $userarr);
        $result = $this->db->get('users')->result();
        return $result;
    }

    function getbynick($nick) {
        //$userarr = ((array) json_decode((string) $nick)) ? (array) json_decode((string) $nick) : array($nick);
        //var_dump($nick,json_decode($nick),json_encode($query));
        $this->db->where(array('nick' => $nick));
        $result = $this->db->get('users')->result();
        //----return only 1st
        if (count($result)) {
            return $result[0];
        } else {
            return false;
        }
    }

    //forgot passw: used to change password 
    function getbymailaddress($mail) {

        $this->db->where(array('email' => $mail));
        $result = $this->db->get('users')->result();

        //----return only 1st
        if (count($result)) {
            return $result[0];
        } else {
            return false;
        }
    }

    function getbygroup($idgroup) {
        $grouparr = (is_array($idgroup)) ? $idgroup : (array) json_decode((string) $idgroup);
        $this->db->where_in('group', $grouparr);
        $this->db->order_by(
                array(
                    'name' => 'asc',
                    'lastname' => 'asc'
                )
        );
        $result = $this->db->get('users')->result();
        return $result;
    }

    function getbygroupname($groupname) {
        //---1st get group
        $group = $this->group->get_byname($groupname);

        return $this->getbygroup($group['idgroup']);
    }

    //---getuser alias.
    function getuser($iduser) {
        return $this->get_user($iduser);
    }

    function get_user($iduser) {
        //*
        //returns an array with  matching id's
        $query = array('idu' => (int) $iduser);

        //var_dump(json_encode($query));
        $user = $this->db->get_where('users', $query)->result();
        if ($user)
            return $user[0];
    }

    //forgot password: change password token
    function get_token($token) {

        $query = array('token' => $token);
        //var_dump(json_encode($query));

        $details = $this->db->get_where('users_token', $query)->result();
        if ($details)
            return $details[0];
    }

    /*
     * Get user data without passwords or any other security info
     */

    function get_user_safe($iduser) {
        //*
        //returns an array with  matching id's
        $query = array('idu' => (int) $iduser);

        //var_dump(json_encode($query));
        $user = $this->db->get_where('users', $query)->result();
        if ($user) {
            unset($user[0]->passw);
            unset($user[0]->_id);
            return $user[0];
        }
    }

    function get_user_array($iduser) {
        //*
        //returns an array with  matching id's
        $query = array('idu' => (int) $iduser);

        //var_dump(json_encode($query));
        $user = $this->db->get_where('users', $query)->result_array();
        if ($user)
            return $user[0];
    }


    function get_users_count($query_txt = null, $idgroup = null,$match='both') {
        if ($idgroup) {
            $this->db->where_in('group', (array) $idgroup);
        }

        if ($query_txt) {
            $this->db->or_like('nick', $query_txt, $match);
            $this->db->or_like('name', $query_txt, $match);
            $this->db->or_like('lastname', $query_txt, $match);
            $this->db->or_like('email', $query_txt, $match);

            if (is_numeric($query_txt)) {
                $this->db->or_where('idu', (int) $query_txt);
            }

            //$query+=array('$where'=>"this.name.match(/$query_txt/i)");
        }
        return $this->db->count_all_results('users');
    }

    function get_users($offset = 0, $limit = 50, $order = null, $query_txt = null, $idgroup = null, $match = 'both') {
        $this->db->get('users');
               
        //var_dump($start,$limit,$idgroup, $order, $idgroup);
        if ($idgroup) {
            $this->db->where_in('group', (array) $idgroup);
        }
               
        if ($query_txt) {
            $this->db->or_like('nick', $query_txt);
            $this->db->or_like('name', $query_txt);
            $this->db->or_like('lastname', $query_txt);
            $this->db->or_like('email', $query_txt);

            if (is_numeric($query_txt)) {
                $this->db->or_where('idu', (int) $query_txt);
            }

            //$query+=array('$where'=>"this.name.match(/$query_txt/i)");
        }
        if ($order) {
            #@todo //--check order like
            $this->db->order_by($order);
        }
        $result = $this->db->get('users', $limit, $offset)->result();
        return $result;
    }

    function put_user($object) {
        //var_dump($object);
        $options = array('upsert' => true, 'w' => true);
        unset($object['_id']);
        $query=array('idu'=>$object['idu']);
        return $this->db->where($query)->update('users',$object, $options);
    }

    function remove($iduser) {
        /**
         * 
         * @todo add code to remove a user from database
         * @param $user_data
         */
    }

    function update($user_data) {
    return $this->save($user_data);
         
    }
    /**
    * Get user avatar from disk
    */
    function get_avatar($idu=null){
        $iduser=($idu)? $idu:$this->idu;
        $current_user=(array) $this->user->get_user_safe($iduser);
        $genero = isset($current_user['gender']) ? ($current_user['gender']) : ("male");

        // Chequeo avatar
        if ( is_file("images/avatar/".$iduser.".jpg")){
        	return base_url()."images/avatar/".$iduser.".jpg";
        }elseif(is_file("images/avatar/".$iduser.".png")){
        	return base_url()."images/avatar/".$iduser.".png";
        }else{
        	return ($genero == "male")?(base_url()."images/avatar/male.jpg"):(base_url()."images/avatar/female.jpg");    	
        }
    }

    /**
     * Save Raw user data
     */

    function save($data) {
        //var_dump($data);
        unset($data['_id']);
        $options = array('w' => true, 'upsert' => true);
        $this->db->where(array('idu'=>$data['idu']));
        $result = $this->db->update('users',$data, $options);
        return $result;
    }

    //forgot password: change password token
    function save_token($object) {
        //var_dump($object);
        $options = array('w' => true, 'upsert' => true);
        return $this->db->insert('users_token',$object, $options);
    }

    function delete_token($token) {

        $this->db->where(array('token' => $token));
        //---now delete original
        $result = $this->db->delete('users_token');
        return $result;
    }
    
    function delete_by_id($_id) {

        //----make backup first
        $obj = $this->getby_id($_id);
        if ($obj) {
            unset($obj->_id);
            //---delete from backup
            $this->db->where(array('idu' => $obj->idu));
            $this->db->delete('users.back');
            //---make a new copy in backup table.
            $result = $this->db->insert('users.back', (array) $obj);
        }
        $this->db->where(array('_id' => new MongoId($_id)));
        //---now delete original
        $result = $this->db->delete('users');
        return $result;
    }

    function delete($iduser) {

        //----make backup first
        $obj = $this->getbyid($iduser);
        if ($obj) {
            $oldid = $obj->_id;
            unset($obj->_id);
            //---delete from backup
            $this->db->where(array('idu' => $obj->idu));
            $this->db->delete('users.back');
            //---make a new copy in backup table.
            $result = $this->db->insert('users.back', (array) $obj);
        }
        $this->db->where(array('idu' => (int) $obj->idu));
        //---now delete original
        $result = $this->db->delete('users');
        return $result;
    }

    function genid() {
        $insert = array();
        $trys = 10;
        $i = 0;
        $id = mt_rand();
        $container = 'users';
        //---if passed specific id
        if (func_num_args() > 0) {
            $id = (int) func_get_arg(0);
            $passed = true;
            //echo "passed: $id<br>";
        }
        $hasone = false;

        while (!$hasone and $i <= $trys) {//---search until found or $trys iterations
            
            //while (!$hasone) {//---search until found or 1000 iterations
            $query = array('idu' => $id);
            $result = $this->db->where($query)->count_all_results($container);
            $i++;
            if ($result) {
                if ($passed) {
                    show_error("id:$id already Exists in $container");
                    break;
                } 
                $hasone = false;
                $id = mt_rand();
                
            } else {
                $hasone=true;
            }
        }
        if (!$hasone) {//-----cant allocate free id
            show_error("Can't allocate an id in $container after $trys attempts");
        }
        return $id;
    }

}
