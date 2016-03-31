<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Oct 27, 2014
 */
class test extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->model('bpm/bpm');
        $this->load->helper('pacc/normal_distribution');
    }

    function Index() {
        $data['base_url'] = $this->base_url;
        $data['module_url'] = $this->module_url;
        $this->parser->parse('pacc/tests', $data, false);
    }

    /**
     * funcion que genera datos para PDE
     */
    function pacc_pde() {
        $idgroup = 1;
        //---trato de tomar los evaluadores
        $users = $this->user->getbygroup(66);
        if (!count($users))
            $users = $this->user->getbygroup(1);

        $group = array();
        foreach ($users as $user) {
            $group[] = (int) $user['idu'];
        }
        $group_count = count($group);

        echo "<h2>Generando Datos de Prueba Empresas...</h2>";
        //----Generar Casos
        echo "<h4>Generando Casos...</h4>";

        for ($i = 1; $i <= 150; $i++) {
            $idwf = 'pacc1PDE';
            $caseid = 'PACC11-' . chr(64 + rand(1, 26)) . chr(64 + rand(1, 26)) . chr(64 + rand(1, 26)) . chr(64 + rand(1, 26));
            echo "<h3>$caseid</h3>";
            $this->bpm->gen_case($idwf, $caseid);
            //----Generar tokens
            if (purebell(0, 2)) {
                echo "<h4>Generando Tokens: Metas para Empresas - Presentados</h4>";
                $data['resourceId'] = 'oryx_5EE22BAB-9BC7-4D32-BA78-17959107DF9B';
                $data['type'] = "IntermediateMessageEventCatching";
                $data['title'] = "M2";
                $data['case'] = $caseid;
                $data['idwf'] = $idwf;
                $data['iduser'] = $group[purebell(0, $group_count - 1)];
                $this->gen_token($data);
                //-----Evaluados Técnicos
                if (purebell(0, 2)) {

                    echo "<h4>Generando Tokens: Metas para Empresas - Evaluados Técnicos</h4>";
                    //---genero token tarea evaluar 

                    $data['resourceId'] = 'oryx_4C79F11E-C212-4140-8B99-1162B761B636';
                    $data['type'] = "Task";
                    $data['title'] = "Evalua FPP";
                    $data['case'] = $caseid;
                    $data['idwf'] = $idwf;
                    $data['iduser'] = $group[purebell(0, $group_count - 1)];
                    $data['status']='user';
                    $this->gen_token($data,60);
                    unset($data['status']);
                    //----ahora el token de ya evaluado
                    $r_array = array(
                        'oryx_C53F382C-E066-4A7F-BF6C-4694F58CB227',
                        // 'oryx_0392203B-FE46-45A7-B78F-3BA3F12690CD',
                        // 'oryx_FE99479E-69ED-46FC-8A51-95DE3F0FB01F'
                    );
                    // $data['resourceId'] = $r_array[purebell(0, 1)];
                    $data['resourceId']='oryx_C53F382C-E066-4A7F-BF6C-4694F58CB227';
                    $data['type'] = "EndMessageEvent";
                    $data['title'] = "M4";
                    $data['case'] = $caseid;
                    $data['idwf'] = $idwf;
                    $data['iduser'] = $group[purebell(0, $group_count - 1)];
                    $this->gen_token($data);

                    //---genero pre-aprobados p(0,3)
                    if (purebell(0, 2)) {
                        echo "<h4>Metas para Empresas - Pre Aprobados</h4>";
                        $data['resourceId'] = 'oryx_BEB71C63-D63E-4510-82F1-D04118F228B9';
                        $data['type'] = "EndNoneEvent";
                        $data['title'] = "M2";
                        $data['case'] = $caseid;
                        $data['idwf'] = $idwf;
                        $data['iduser'] = $group[purebell(0, $group_count - 1)];
                        $this->gen_token($data);
                        //---genero aprobados
                        if (purebell(0, 2)) {
                            echo "<h4>Metas para Empresas - Aprobados</h4>";
                            $data['resourceId'] = 'oryx_E30BD322-F07A-4582-AA30-084613B1ACD0';
                            $data['type'] = "EndNoneEvent";
                            $data['title'] = "M2";
                            $data['case'] = $caseid;
                            $data['idwf'] = $idwf;
                            $data['iduser'] = $group[purebell(0, $group_count - 1)];
                            $this->gen_token($data);
                            /*
                             * SDE
                             */
                            if (purebell(0, 2)) {
                                echo "<h4>Metas para Empresas - Primer Pago</h4>";
                                $idwf = 'paccPagoSD';
                                $data['resourceId'] = 'oryx_35CEFC1B-5CA1-4DAF-A5B8-F5A6154577FF';
                                $data['type'] = "EndNoneEvent";
                                $data['title'] = "M2";
                                $data['case'] = $caseid;
                                $data['idwf'] = $idwf;
                                $data['iduser'] = $group[purebell(0, $group_count - 1)];
                                $this->gen_token($data);
                                //--close case
                                $this->bpm->gen_case($idwf, $caseid, array('tipo_proyecto' => 'Empresas'));
                            }//---fin SDE
                        }//---fin Aprobados
                    }//---fin pre-aprobados
                }//---fin evaluados técnicos
            } //--fin presentados
        }
    }

    /**
     * funcion que genera datos para PDE
     */
    function pacc_emprendedores() {
        $idgroup = 1;
        $users = $this->user->getbygroup(1);
        $group = array();
        foreach ($users as $user)
            $group[] = (int) $user['idu'];
        $group_count = count($group);

        echo "<h2>Generando Datos de Prueba Emprendedores...</h2>";
        //----Generar Casos
        echo "<h4>Generando Casos...</h4>";

        for ($i = 1; $i <= 150; $i++) {
            $idwf = 'pacc3PP';
            $caseid = 'PACC13-' . chr(64 + rand(1, 26)) . chr(64 + rand(1, 26)) . chr(64 + rand(1, 26)) . chr(64 + rand(1, 26));
            echo "<h3>$caseid</h3>";
            $this->bpm->gen_case($idwf, $caseid);
            //----Generar tokens
            if (!purebell(0, 1)) {
                echo "<h4>Generando Tokens: Metas para Emprendedores - Presentados</h4>";
                $data['resourceId'] = 'oryx_4EFED47A-7AAD-4728-9577-49F8901AC5B9';
                $data['type'] = "EndMessageEvent";
                $data['title'] = "M2";
                $data['case'] = $caseid;
                $data['idwf'] = $idwf;
                $data['iduser'] = $group[purebell(0, $group_count - 1)];
                $this->gen_token($data);
                //---genero evaluados
                if (purebell(0, 2)) {

                    echo "<h4>Generando Tokens: Metas para Emprendedores - Evaluados Técnicos</h4>";
                    $r_array = array(
                        'oryx_B9D1931C-1F5B-4D3D-82F4-9B919750F4A3',
                        'oryx_FF122EC2-566D-4D7F-AC17-1A5B71B35922',
                        'oryx_1FEB7B2E-757D-415D-99EF-27C124FC747B'
                    );
                    $data['resourceId'] = $r_array[purebell(0, 2)];
                    $data['type'] = "EndMessageEvent";
                    $data['title'] = "M4";
                    $data['case'] = $caseid;
                    $data['idwf'] = $idwf;
                    $data['iduser'] = $group[purebell(0, $group_count - 1)];
                    $this->gen_token($data);
                    //---genero pre-aprobados para p(0,1)
                    if (purebell(0, 2)) {
                        echo "<h4>Metas para Emprendedores - Pre Aprobados</h4>";
                        $data['resourceId'] = 'oryx_7428B138-66E5-4E33-B5D8-0337D86248EF';
                        $data['type'] = "EndNoneEvent";
                        $data['title'] = "M2";
                        $data['case'] = $caseid;
                        $data['idwf'] = $idwf;
                        $data['iduser'] = $group[purebell(0, $group_count - 1)];
                        $this->gen_token($data);

                        if (purebell(0, 3)) {
                            echo "<h4>Metas para Emprendedores - PITCH Presentado</h4>";
                            $data['resourceId'] = 'oryx_0B1514DD-8EE4-4AFA-831B-6445A961DDA6';
                            $data['type'] = "EndMessageEvent";
                            $data['title'] = "video PN";
                            $data['case'] = $caseid;
                            $data['idwf'] = $idwf;
                            $data['iduser'] = $group[purebell(0, $group_count - 1)];
                            $this->gen_token($data);
                            //---genero PITCH Evaluados
                            if (purebell(0, 2)) {
                                echo "<h4>Metas para Emprendedores - PITCH Evaluado</h4>";
                                $data['resourceId'] = 'oryx_5D8FB24B-C582-4B17-9326-D9B445353921';
                                $data['type'] = "Exclusive_Databased_Gateway";
                                $data['title'] = "pitch evaluado";
                                $data['case'] = $caseid;
                                $data['idwf'] = $idwf;
                                $data['iduser'] = $group[purebell(0, $group_count - 1)];
                                $this->gen_token($data);
                                //---genero PITCH Aprobados
                                if (purebell(0, 2)) {
                                    echo "<h4>Metas para Emprendedores - PITCH Aprobado</h4>";
                                    $data['resourceId'] = 'oryx_4E95C71F-A219-4088-A208-00775D2294D';
                                    $data['type'] = "EndMessageEvent";
                                    $data['title'] = "pitch aprobado";
                                    $data['case'] = $caseid;
                                    $data['idwf'] = $idwf;
                                    $data['iduser'] = $group[purebell(0, $group_count - 1)];
                                    $this->gen_token($data);
                                }
                            }
                        }
                        //---genero aprobados
                        if (purebell(0, 4)) {
                            $idwf = 'pacc3PPF';
                            echo "<h4>Metas para Emprendedores - Aprobados</h4>";
                            $data['resourceId'] = 'oryx_C845FE55-9CF5-4D1F-8982-504731566C56';
                            $data['type'] = "EndNoneEvent";
                            $data['title'] = "M2";
                            $data['case'] = $caseid;
                            $data['idwf'] = $idwf;
                            $data['iduser'] = $group[purebell(0, $group_count - 1)];
                            $this->gen_token($data);
                            //--close case
                            $this->bpm->gen_case($idwf, $caseid, array('tipo_proyecto' => 'Emprendedores'));
                            /*
                             * SDE
                             */
                            if (purebell(0, 4)) {
                                echo "<h4>Metas para Emprendedores - Primer Pago</h4>";
                                $idwf = 'paccPagoSD';
                                $data['resourceId'] = 'oryx_35CEFC1B-5CA1-4DAF-A5B8-F5A6154577FF';
                                $data['type'] = "EndNoneEvent";
                                $data['title'] = "M2";
                                $data['case'] = $caseid;
                                $data['idwf'] = $idwf;
                                $data['iduser'] = $group[purebell(0, $group_count - 1)];
                                $this->gen_token($data);
                                //--close case
                                $this->bpm->gen_case($idwf, $caseid, array('tipo_proyecto' => 'Emprendedores'));
                            }
                        }//---fin aprobados
                    }//---fin pre-aprobados
                }//---fin evaluados técnicos
                //---genero PITCH presentados
            }//---fin presentados
        }
    }

    function pacc_pde_clear() {
        $query = array(
            'id' => new MongoRegex('/PACC11-/'),
        );

        echo '<h1>Limpiando Empresas: </h1>';
        $cases = $this->bpm->get_cases_byFilter($query);
        foreach ($cases as $case) {
            echo '<h5>Limpiando: ' . $case['idwf'] . ' ' . $case['id'] . '</h5>';
            $this->bpm->delete_case($case['idwf'], $case['id']);
        }
    }

    function pacc_emprendedores_clear() {
        $query = array(
            'id' => new MongoRegex('/PACC13-/'),
        );
        echo '<h1>Limpiando Emprendedores: </h1>';

        $cases = $this->bpm->get_cases_byFilter($query);
        foreach ($cases as $case) {
            echo '<h5>Limpiando: ' . $case['idwf'] . ' ' . $case['id'] . '</h5>';
            $this->bpm->delete_case($case['idwf'], $case['id']);
        }
    }

    private function gen_case($data = array()) {
        
    }

    /**
     * Generate a purebellom token within the given time window in days
     * @param type $data
     * @param type $time_window
     */
    private function gen_token($data = array(), $time_window = 30) {
        $base = array(
            'checkdate' => date('Y-m-d H:i:s', strtotime(purebell(5, $time_window + 5) . ' days ago')),
            'idu' => 1,
            'iduser' => 1,
            'idwf' => 'genia_metas',
            'interval' =>
            array(
                'y' => 0,
                'm' => 0,
                'd' => 0,
                'h' => 0,
                'i' => 0,
                's' => 0,
                'invert' => 0,
                'days' => purebell(0, 7),
            ),
            'microtime' => '0.31319800 1372273110',
            'run' => 1,
            'status' => 'finished',
        );
        $token = array_merge($base, $data);
        $this->bpm->save_token($token);
    }

    function tile_toast() {
        $data['lang'] = $this->lang->language;
        $data['base_url'] = $this->base_url;
        $data['module_url'] = $this->module_url;
        $files = Modules::run('test/Toast_all/_get_test_files');
        $data['number'] = count($files);
        $data['title'] = "Run Tests";
        $this->parser->parse('dashboard/tiles/tile-green', $data, false, true);
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */