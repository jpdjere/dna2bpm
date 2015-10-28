<?

class Siac extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('api');
        $this->load->helper('html');
        $this->load->model('bpm/bpm');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

    function Index() {
        $ignore_arr = array('Index', '__construct', '__get');
        $methods = array_diff(get_class_methods(get_class($this)), $ignore_arr);
        asort($methods);
        $links = array_map(function($item) {
            return '<a href="' . $this->module_url . strtolower(get_class()) . '/' . strtolower($item) . '">' . $item . '</a>';
        }, $methods);
        $attributes = array('class' => 'api_endpoint');
        echo ul($links, $attributes);
    }

    /*
     * Create function is public
     */

    function create($data) {
        // create a new issue
        /*
          $data=array(
          'project_id' => '355',
          'subject' => 'Test:' . date('Y-m-d H:i:s'),
          'description' => 'bla balblabl ablabalb 23423424',
          );
         */
        $issue = new Issue(
                $data
        );
        $issue->site = $this->config->item('site');
        $issue->user = $this->config->item('api_key');
        $issue->save();
        return $issue;
    }

    function read($id = null) {
        $this->user->authorize();
        $issue = new Issue();
        $issue->site = $this->config->item('site');
        $issue->user = $this->config->item('api_key');
        $query = ($id) ? $id : 'all';
        $issues = $issue->find($query);
//        
//        if (!$id) {
//            for ($i = 0; $i < count($issues); $i++) {
//                echo $issues[$i]->id . '::' . $issues[$i]->subject . '<br/>';
//            }
//        } else {
//
//        if($issues->_data){
//            echo $issues->id . '::' . $issues->subject . '<br/>';
//        } else {
//            show_error("Issue #$id Not Found.");
//        }
//        }
        return $issues;
    }

    function update($id, $data) {
        $this->user->authorize();
        if ($id and is_array($data)) {
            $issue = new Issue();
            $issue->site = $this->config->item('site');
            $issue->user = $this->config->item('api_key');
            // find and update an issue
            $issue->find($id);
            foreach ($data as $key => $val) {
                $issue->set($key, $val);
            }
            $issue->save();
            return $issue;
        }
    }

    function delete($id) {
        $this->user->authorize();
        // delete an issue
        if ($id) {
            $issue = new Issue();
            $issue->site = $this->config->item('site');
            $issue->user = $this->config->item('api_key');
            $issue->find($id);
            $issue->destroy();
            return true;
        } else {
            return false;
        }
    }

}

?>
