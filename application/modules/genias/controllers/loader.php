<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * loader
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Oct 9, 2013
 */
class Loader extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('app');
        $this->load->model('user/user');
        $this->load->model('bpm/bpm');
        $this->load->model('user/rbac');
        $this->load->model('genias/genias_model');
        $this->load->helper('genias/tools');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'genias/';
    }

    function empresas_test($idgenia = null) {
        $this->load->model('app');
        $debug = false;
        $prov = 'JUJ';
        $provincias = $this->app->get_ops(39);
        $this->load->library('table');

        $this->table->set_heading(array('Provincia', 'Cantidad', 'Size', 'gziped'));

        foreach ($provincias as $key => $valor) {
            $query = array('4651' => $key);
            $empresas = $this->genias_model->get_empresas_raw($query);
            $rtnArr = array();
            $rtnArr['totalCount'] = count($empresas);
            $rtnArr['rows'] = $empresas;
            $this->table->add_row(
                    array(
                        "prov::$key::$valor",
                        count($empresas),
                        number_format(strlen(json_encode($rtnArr)) / 1024, 2) . " Kb",
                        number_format(strlen(gzcompress(json_encode($rtnArr))) / 1024, 2) . " Kb"
                    )
            );
            //echo "prov::$key::$valor::" . count($empresas) . ":: <strong>" . number_format(strlen(json_encode($rtnArr)) / 1024, 2) . " Kb</strong><br/>";
        }
        echo $this->table->generate();
        $this->output->enable_profiler(TRUE);
    }

    function Index() {
        
    }

    function Listado_empresas($parm = null) {

        //echo $this->idu;   
        //---Libraries
        $this->load->library('parser');
        $this->load->library('ui');

        $cpData = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Escenario Pyme.';


        $cpData['js'] = array(
            $this->module_url . 'assets/jscript/onlineStatus.js' => 'Online/Offline Status',
            $this->base_url . "jscript/ext/src/ux/form/SearchField.js" => 'Search Field',
            $this->base_url . "jscript/ext/src/ux/statusbar/StatusBar.js" => 'Status Bar',
            $this->base_url . "jscript/ext/src/ux/LiveFilterGridPanel.js" => 'Live Filter Panel',
            //$this->module_url . 'assets/jscript/ext.settings.js' => 'Ext Settings',
            $this->module_url . 'assets/jscript/empresas.ext.data.js' => 'Base Data',
            $this->module_url . 'assets/jscript/empresasAlt/btnSync.js' => 'btnSync',
            $this->module_url . 'assets/jscript/empresasAlt/empresas.grid.js' => 'Grid Empresas',
            $this->module_url . 'assets/jscript/empresasAlt/ext.viewport.empresas.table.js' => 'ViewPort',
        );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        $this->ui->makeui('ext.ui.php', $cpData);
    }

    function empresas_id($idgenia=null) {
        $debug = false;
        $compress = false;
        $query = array('4651' => 'BA');
        
        
        $empresas = $this->genias_model->get_empresas_id($query);
        //var_dump($empresas);
        $rtnArr = array();
        $rtnArr['totalCount'] = count($empresas);
        $rtnArr['rows'] = $empresas;
        if (!$debug) {
            header('Content-type: application/json;charset=UTF-8');
            if ($compress) {
                header('Content-Encoding: gzip');
                print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
                echo gzcompress(json_encode($rtnArr));
            } else {
                echo json_encode($rtnArr);
            }
        } else {
            var_dump(json_encode($rtnArr));
        }
    }
    function empresa($idemp) {
        $debug = false;
        $compress = false;
        
        $query = array(
            '4651' => 'BA',
            'id'=>(double)$idemp
            );
        $empresas = $this->genias_model->get_empresas_raw($query);
        for ($i = 0; $i < count($empresas); $i++) {
            $thisEmpresa = &$empresas[$i];
            //-----partido por texto
            if (isset($thisEmpresa[1699])) {
                $thisEmpresa['partido_txt'] = (isset($partidos[$thisEmpresa[1699][0]])) ? $partidos[$thisEmpresa[1699][0]] : $thisEmpresa[1699][0];
            } else {
                $thisEmpresa['partido_txt'] = '<span class="label label-important"><i class="icon-info-sign"/> COMPLETAR! </span>';
            }
        }
        //var_dump($empresas);
        $rtnArr = array();
        $rtnArr['totalCount'] = count($empresas);
        $rtnArr = $empresas[0];
        if (!$debug) {
            header('Content-type: application/json;charset=UTF-8');
            if ($compress) {
                header('Content-Encoding: gzip');
                print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
                echo gzcompress(json_encode($rtnArr));
            } else {
                echo json_encode($rtnArr);
            }
        } else {
            var_dump(json_encode($rtnArr));
        }
    }

    function test() {

        //echo $this->idu;   
        //---Libraries
        $this->load->library('parser');
        $this->load->library('ui');

        $cpData = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'LOADER TEST';


        

        $cpData['js'] = array(
            $this->module_url . 'assets/jscript/onlineStatus.js' => 'Online/Offline Status',
            //$this->base_url . "jscript/ext/src/ux/form/SearchField.js" => 'Search Field',
            //$this->module_url . 'assets/jscript/ext.settings.js' => 'Ext Settings',
            $this->module_url . 'assets/jscript/empresas.ext.data_1.js' => 'Base Data',
            //$this->module_url . 'assets/jscript/empresas.grid.js' => 'Grid Empresas',
            //$this->module_url . 'assets/jscript/empresas.form.js' => 'Form Empresas',
            $this->module_url . 'assets/jscript/ext.viewport.empresas_1.js' => 'ViewPort',
        );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        $this->ui->makeui('ext.ui.php', $cpData);
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */