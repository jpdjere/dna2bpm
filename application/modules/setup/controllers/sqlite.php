<?php

class Sqlite extends MX_Controller {

    function Sqlite() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->library('index/ui');
        $this->load->helper('file');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';

    }
    
    
    function Index(){
        $this->load->dbforge();
        $cpData['title'] = 'DNA2BPM SQLite3 Setup()';
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['environment']='';
        $user=  array('blog_id' => array(
                             'type' => 'INT',
                             'constraint' => 5, 
                             'auto_increment' => TRUE
                      ),
                        'blog_title' => array(
                             'type' => 'VARCHAR',
                             'constraint' => '100',
                                          ),
                        'blog_author' => array(
                             'type' =>'VARCHAR',
                             'constraint' => '100',
                             'default' => 'King of Town',
                                          ),
                        'blog_description' => array(
                             'type' => 'TEXT',
                             'null' => TRUE,
                                          ),
                  );
        $this->dbforge->add_field($user);
        $this->dbforge->create_table('users', TRUE);
        $this->ui->compose('setup/step2.bootstrap.php', 'setup/bootstrap3.ui.php', $cpData);
    }
}