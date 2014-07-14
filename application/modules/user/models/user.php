<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = $this->session->userdata('iduser');
        $this->config->load('user/config');
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;
    }

    function add($user_data) {
        $user = null;
        //---1st check if user exists by its idu
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
        $this->db->debug = false;
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
        if (!$thisUser) {
            //----user doesn't exists in db
            $canaccess = false;
        } else {
            //----user exists
            //---define the path for module auth
            $path = str_replace('../', '', $this->router->fetch_directory() . implode('/', array(
                        $this->router->class,
                        $this->router->method,
                            )
            ));
            /*
             * Auto-discover from existent will add all the paths it's hits
             * turn off for production
             */
            $this->rbac->put_path($path, array(
                'source' => 'AutoDiscovery',
                'checkdate' => date('Y-m-d H:i:s'),
                'idu' => $this->idu
            ));

            //---give access if belong to group ADMINS
            if ($this->isAdmin($thisUser)) {
                $canaccess = true;
            } else {
                //----$reqlevel override $path
                $path = (isset($reqlevel)) ? $reqlevel : $path;
                //---give access if have path exists
                if ($this->user->has('root/' . $path, $thisUser))
                    $canaccess = true;
            }
        }
        if (!$canaccess) {
            $this->session->set_userdata('redir', base_url() . uri_string());
            $this->session->set_userdata('msg', 'nolevel');
            header('Location: ' . base_url() . 'user/login');
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
            header('Location: ' . base_url() . 'user/login');
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
            unset($user[0]->password);
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

    function get_groups($order = null, $query_txt = null) {
        /*
         *  Function get_groups
         * 
         * @todo translate this function to ActiveRecord
         */
        $query = array();
        if ($query_txt) {
            $query = array('name' => new MongoRegex('/' . $query_txt . '/i'));
        }
        //var_dump('$order',$order,'$query',$query);
        $rs = $this->mongo->db->groups->find($query);
        if ($order)
            $rs->sort(array($order => 1));
        return $rs;
    }

    function get_users($offset = 0, $limit = 50, $order = null, $query_txt = null, $idgroup = null, $match = 'both') {
        $this->db->get('users');
        //var_dump($start,$limit,$idgroup, $order, $idgroup);
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
        //var_dump('$order', $order, '$query', $query);
        //$rs = $this->mongo->db->users->find($query)->skip($start)->limit($limit);
        //$order = (isset($order)) ? $rs->sort($order) : $rs->sort(array('lastname' => 1, 'name' => 1));
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
        return $this->mongo->db->users->save($object, $options);
    }

    function remove($iduser) {
        /**
         * 
         * @todo add code to remove a user from database
         * @param $user_data
         */
    }

    function update($user_data) {
        $user = null;
        //---1st check if user exists by its idu
        if (isset($user_data['idu'])) {
            $user = $this->getbyid($user_data['idu']);
            //---if not found then add to db
            if (!$user) {
                $result = $this->save($user_data);
                $user = $user_data;
            } else {
	
	        $options = array('safe' => true, 'upsert' => true);
	        $query=array('idu'=>$user_data['idu']);
	        $result = $this->mongo->db->users->update($query,array('$set'=>$user_data), $options);
            	$user = $user_data;
            }
            return $result;
        }
    }

    /*
     * Save Raw user data
     */

    function save($data) {
        //var_dump($data);
        $options = array('w' => true, 'upsert' => true);
        $result = $this->mongo->db->users->save($data, $options);
        return $result;
    }

    //forgot password: change password token
    function save_token($object) {
        //var_dump($object);
        $options = array('w' => true, 'upsert' => true);
        return $this->mongo->db->users_token->save($object, $options);
    }

    function delete_token($token) {

        $this->db->where(array('token' => $token));
        //---now delete original
        $result = $this->db->delete('users_token');
        return $result;
    }

    function delete_group($idgroup) {
        $options_delete = array("justOne" => true, "safe" => true);
        $options_save = array('upsert' => true, 'w' => true);
        $criteria = array('idgroup' => (int) $idgroup);
        //----make backup first
        $obj = $this->group->get($idgroup);
        $this->mongo->db->selectCollection('groups.back')->save($obj, $options_save);
        $this->mongo->db->groups->remove($criteria, $options_delete);
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
        $id = mt_rand();
        $trys = 10;
        $i = 0;
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
            $result = $this->mongo->db->selectCollection($container)->findOne($query);
            $i++;
            if ($result) {
                if ($passed) {
                    show_error("id:$id already Exists in $container");
                    $hasone = true;
                    break;
                } else {//---continue search for free id
                    $id = mt_rand();
                }
            } else {//---result is null
                $hasone = true;
            }
        }
        if (!$hasone) {//-----cant allocate free id
            show_error("Can't allocate an id in $container after $trys attempts");
        }
        //-----make basic object
        $insert['id'] = $id;
        //----Allocate id in the collection (may result in empty docs)
        //$this->mongo->db->selectCollection($container)->save($insert);
        return $id;
    }

}
