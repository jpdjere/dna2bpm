<?php

/**
 * Description of pacc
 *
 * @author juanb
 * @date   Jan 16, 2015
 * 
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class pacc13 extends MX_Controller {

    function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->model('bpm/bpm');
        // ----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
        $this->user->authorize();
        /* GROUP */
        $user = $this->user->get_user($this->idu);

        $this->id_group = ($user->{'group'});
    }

    /*
     * Main function if no other invoked
     */

    function Index() {
        echo "<h1>" . $this->router->fetch_module() . '</h1>';
    }
    function imprimir_proyecto($idwf, $idcase, $token, $id = null) {

        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->model('dna2/dna2old');
        $dna2url = $this->dna2old->get('url');
//         if ($id) {
//             $url = $dna2url . "frontcustom/284/proyecto_fondyf_preA_new.php?id=$id&idwf=$idwf&case=$idcase&token=$token";         
//         } else {
//             show_error('El Caso no tiene id de proyecto');
//         }
//         $url = $this->bpm->gateway($url);
//         redirect($url);
        if ($id) {
            $todo = $id . '&idwf=' . $idwf . '&case=' . $idcase . '&token=' . $token;
//                <p align='left'>2. <a href="{$dna2url}frontcustom/290/cartacompromiso1-1.php?id=$todo" target="_blank">Carta compromiso</a></p>
            echo <<<BLOCK
                <p align='left'>1. <a href="{$dna2url}frontcustom/290/pacc13.externo2016.print.php?id=$todo" target="_blank">Imprimir del Plan de Negocio</a></p>
BLOCK;
        } else {
            echo 'div class="alert alert-success" role="alert">El Caso no tiene id de proyecto</div>';
        }
    }
  
    function asignar_evaluador($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador del caso
        $evaluador = $renderData['Proyectos_pacc']['6096'][0];
        //----token que hay que finalizar 
         $src_resourceId = 'oryx_A150EBF2-8F30-4631-B04B-90DBDB019C41';
        // ---Token de pp asignado
        $lane_resourceId = 'oryx_295810F2-8C34-4D03-80F8-7B5C371381B8';

        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }

    function asignar_evaluador_ppf($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador del caso
        $evaluador = $renderData['Proyectos_pacc']['6096'][0];
        //----token que hay que finalizar
        $src_resourceId = 'oryx_42CD1D03-1250-4CA5-9868-4498DB9D498B';
        // ---Token de pp asignado (Lane)
        $lane_resourceId = 'oryx_AD0108D7-CA5D-4989-845E-CBC0E6158CF3';

        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }

    /**
     * Asigna un evaluador para una solicitud de desembolso
     */ 
    
    function asignar_evaluador_sde($idwf, $idcase, $tokenId, $src_resourceId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador administrativo
        $evaluador_admin = $renderData['Proyectos_pacc']['7106'][0];
        //----tomo evaluador técnico
        $evaluador = $renderData['Proyectos_pacc']['6096'][0];
        //----token que hay que finalizar (self)
        //$src_resourceId = 'oryx_43E6BB74-5545-4CAB-BD71-3F3B42533211';
        // ---Token de pp asignado (Lane)
        $lane_resourceId = 'oryx_891FFEB1-5EE3-44C6-A16B-13153912E0F1';
        
        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }

    function asignar_evaluador_administrativo_sde($idwf, $idcase, $tokenId, $src_resourceId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador administrativo
        $evaluador = $renderData['Proyectos_pacc']['7106'][0];
        //----token que hay que finalizar (self)
        //$src_resourceId = 'oryx_BD7F84C3-73FE-48E0-831F-DEB0B9F78DCC';
        // ---Token de Lane asignado
        $lane_resourceId = 'oryx_CD23C511-FAE2-4549-8D26-2182224D770F';
        
        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }

    function clone_case($from_idwf, $to_idwf, $idcase) {
        $this->load->model('bpm/bpm');
        $this->load->module('bpm/engine');
        $case = $this->bpm->get_case($idcase, $from_idwf);
        $case_to = $this->bpm->get_case($idcase, $to_idwf);
        if (!$case_to) {

            $this->bpm->gen_case($to_idwf, $idcase);
            $case_to = $this->bpm->get_case($idcase, $to_idwf);
            $case_to['data'] = $case['data'];
            $case_to['iduser'] = $case['iduser'];
            $case_to = $this->bpm->save_case($case_to);
            $this->engine->Startcase('model', $to_idwf, $idcase);
        } else {
            /*
             *   continue case
             */
            $mywf = $this->bpm->load($to_idwf);
            if (!$mywf) {
                show_error("Model referenced:$idwf does not exists");
            }
            $wf = bindArrayToObject($mywf ['data']);
            // ---Get all start points of diagram
            $start_shapes = $this->bpm->get_start_shapes($wf);
            // ----Raise an error if doesn't found any start point
            if (!$start_shapes)
                show_error("The Schema doesn't have an start point");
            // ---Start all StartNoneEvents as possible
            foreach ($start_shapes as $start_shape) {
                $this->bpm->set_token($to_idwf, $idcase, $start_shape->resourceId, $start_shape->stencil->id, 'pending');
            }
            $this->engine->Run('model', $to_idwf, $idcase);
        }

        //----run case
        // Modules::run("bpm/run/model/$to_idwf/$idcase");
    }
    
    /**
     * Funcion crear las SDE a partir de un proyecto
     * @todo customizar valores según MJL
     * 
     * Modules::run('pacc11/create_SDE',$idwf,$idcase);
     * 
     */
     
    function create_SDE($idwf,$idcase,$suffix){
        $this->load->model('bpm/bpm');
        $this->load->model('app');
        $this->load->module('bpm/engine');
        $case = $this->bpm->get_case($idcase, $idwf);
        $id=$case['data']['Proyectos_pacc']['query']['id'];
        $id_empresa=$case['data']['Empresas']['query']['id'];
        
        $data['Empresas']=$case['data']['Empresas'];
        $data['Proyectos_pacc']=$case['data']['Proyectos_pacc'];
        
        $caserendicion=$this->bpm->gen_case('pacc3SDAREND',$idcase.'-'.$suffix,$data);
        $this->bpm->engine->Startcase('model', 'pacc3SDAREND', $caserendicion, true);
        $resourceId=null;
        $silent=true;
        $this->engine->Run('model', 'pacc3SDAREND', $caserendicion,$resourceId,$silent);
        
    }
    public $consolida_resrourceId='oryx_85AA85C0-2F51-46E0-9EB3-28FFAD508E48';
    function buscarEmprend($type = null) {
        $this->load->model('bpm/bpm');
        $this->user->authorize();
        $this->load->library('parser');
        $templateAg = 'pacc13/listar_13';
        $filter = array(
            'idwf' => 'pacc3SDAREND',
            'resourceId' =>$this->consolida_resrourceId
        );
        // -----busco en el cuit
        $data ['querystring'] = $this->input->post('query');
        $filter ['$or'] [] = array(
            'data.1695' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        // -----busco en el nombre empresa
        $filter ['$or'] [] = array(
            'data.1693' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        // -----busco en el nro proyecto
        $filter ['$or'] [] = array(
            'data.7356' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        $filter ['$or'] [] = array(
            'case' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        
        $tokens = $this->bpm->get_tokens_byFilter($filter , array('case','data','checkdate'), array('checkdate' => false));
		
        $data ['empresas'] = array_map(function ($token) {
            // var_dump($token['_id']);
            $case = $this->bpm->get_case($token ['case'], 'pacc3SDAREND');
            $pacc3SDAREND = $this->bpm->get_case($token ['case'], 'pacc3SDAREND');
            $model = 'pacc3SDAREND';
            $data = $this->bpm->load_case_data($case);
            $url = '';
            $url_msg = '';
            $hist=$this->bpm->get_token_history('pacc3SDAREND',$token['case']);
            foreach($hist as $t) $keys[$t['resourceId']]=$t['status'];
            $keys = array_keys($case['token_status']);
            $url_clone = ''; 
            //var_dump($token['_id'],$keys);
            $idResource = $model; 
            $estado = $data ['Proyectos_pacc'] ['5689'][0];
            $url_clone =$this->base_url . 'bpm/engine/run/model/' . $model. '/' .$token['case'];
            $url_cancelar_pp = '';
            $url_cancelar_pde = '';
            $url_reevaluar_pp = '';
            $url_reevaluar_pde = '';
            $url_bpm = '';
            //if (in_array(145, $this->id_group) or in_array(1001, $this->id_group) or $this->user->isAdmin()) {
                //$model = ($pacc3SDAREND) ? 'pacc3SDAREND' : 'pacc3SDAREND';
                $url_bpm = $this->base_url . 'bpm/engine/run/model/' . $model . '/' . $token ['case'];
            //}

            /* STATUS */
            $status = "N/A";
            if (isset($data ['Proyectos_pacc'] ['5689'])) {
                $this->load->model('app');
                $option = $this->app->get_ops(580);
                $status = $option[$data ['Proyectos_pacc'] ['5689'][0]];
            }
            $id=$data ['Proyectos_pacc'] ['id'];
            if ($id) {
                    $todo = $id . '&idwf=' . $idwf . '&case=' . $idcase . '&token=' . $token;
                    $url_ver ='../../dna2/RenderView/printvista.php?idvista=4123&idap=295&id='.$todo;
            } else {
                $url_ver ='';
            }
            return array(
                '_d' => $token ['_id'],
                'case' => $token ['case'],
                'nombre' => (isset($data['Empresas']['1693'])) ? $data['Empresas']['1693'] : 'Error//no está cargado',
                'cuit' =>  (isset($data['Empresas']['1695'])) ? $data['Empresas']['1695'] : 'XXXX',
                'Nro' => (isset($data ['Proyectos_pacc']['7356'])) ? $data ['Proyectos_pacc'] ['7356'] : 'N/A',
                'estado' => $status,
                'fechaent' => date('d/m/Y', strtotime($token ['checkdate'])),
                'link_open' => $this->bpm->gateway($url),
                'link_msg' => $url_ver,
                'url_clone' => $url_clone,
                'url_bpm' => $url_bpm,
                'url_cancelar_pp' => $url_cancelar_pp,
                'url_cancelar_pde' => $url_cancelar_pde,
                'url_reevaluar_pp' => $url_reevaluar_pp,
                'url_reevaluar_pde' => $url_reevaluar_pde,

            );
        }, $tokens);
        $data ['count'] = count($tokens);
        var_dump(count($tokens));
//****************************
	if (count($tokens)==0 || count($tokens)=='0'){
            
            $consolida_resrourceId='oryx_A58D5ECD-6899-4F60-856C-CFE89B36FB91';
            var_dump($consolida_resrourceId);
            
				$filter = array(
					'idwf' => 'pacc3PPF',
					'resourceId' =>$this->consolida_resrourceId
				);
				// -----busco en el cuit
				
				$data ['querystring'] = $this->input->post('query');
				$filter ['$or'] [] = array(
					'data.1695' => array(
						'$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
					)
				);
				// -----busco en el nombre empresa
				$filter ['$or'] [] = array(
					'data.1693' => array(
						'$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
					)
				);
				// -----busco en el nro proyecto
				$filter ['$or'] [] = array(
					'data.7356' => array(
						'$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
					)
				);
				$filter ['$or'] [] = array(
					'case' => array(
						'$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
					)
				);
				
				$tokens = $this->bpm->get_tokens_byFilter($filter , array('case','data','checkdate'), array('checkdate' => false));
				
				$data ['empresas'] = array_map(function ($token) {
					// var_dump($token['_id']);
					$case = $this->bpm->get_case($token ['case'], 'pacc3PPF');
					$pacc3PPF = $this->bpm->get_case($token ['case'], 'pacc3PPF');
					$model = 'pacc3PPF';
					$data = $this->bpm->load_case_data($case);
					$url = '';
					$url_msg = '';
					$hist=$this->bpm->get_token_history('pacc3PPF',$token['case']);
					foreach($hist as $t) $keys[$t['resourceId']]=$t['status'];
					$keys = array_keys($case['token_status']);
					$url_clone = ''; 
					//var_dump($token['_id'],$keys);
					$idResource = $model; 
					$estado = $data ['Proyectos_pacc'] ['5689'][0];
					$url_clone =$this->base_url . 'bpm/engine/run/model/' . $model. '/' .$token['case'];
					$url_cancelar_pp = '';
					$url_cancelar_pde = '';
					$url_reevaluar_pp = '';
					$url_reevaluar_pde = '';
					$url_bpm = '';
					//if (in_array(145, $this->id_group) or in_array(1001, $this->id_group) or $this->user->isAdmin()) {
						//$model = ($pacc3PPF) ? 'pacc3PPF' : 'pacc3PPF';
						$url_bpm = $this->base_url . 'bpm/engine/run/model/' . $model . '/' . $token ['case'];
					//}

					/* STATUS */
					$status = "N/A";
					if (isset($data ['Proyectos_pacc'] ['5689'])) {
						$this->load->model('app');
						$option = $this->app->get_ops(580);
						$status = $option[$data ['Proyectos_pacc'] ['5689'][0]];
					}
					$id=$data ['Proyectos_pacc'] ['id'];
					if ($id) {
							$todo = $id . '&idwf=' . $idwf . '&case=' . $idcase . '&token=' . $token;
							$url_ver ='../../dna2/RenderView/printvista.php?idvista=4123&idap=295&id='.$todo;
					} else {
						$url_ver ='';
					}
					return array(
						'_d' => $token ['_id'],
						'case' => $token ['case'],
						'nombre' => (isset($data['Empresas']['1693'])) ? $data['Empresas']['1693'] : 'Error//no está cargado',
						'cuit' =>  (isset($data['Empresas']['1695'])) ? $data['Empresas']['1695'] : 'XXXX',
						'Nro' => (isset($data ['Proyectos_pacc']['7356'])) ? $data ['Proyectos_pacc'] ['7356'] : 'N/A',
						'estado' => $status,
						'fechaent' => date('d/m/Y', strtotime($token ['checkdate'])),
						'link_open' => $this->bpm->gateway($url),
						'link_msg' => $url_ver,
						'url_clone' => $url_clone,
						'url_bpm' => $url_bpm,
						'url_cancelar_pp' => $url_cancelar_pp,
						'url_cancelar_pde' => $url_cancelar_pde,
						'url_reevaluar_pp' => $url_reevaluar_pp,
						'url_reevaluar_pde' => $url_reevaluar_pde,

					);
				}, $tokens);
				$data ['count']= count($tokens);
        var_dump(count($tokens));
		if (count($tokens)==0 || count($tokens)=='0'){
                     $consolida_resrourceId='oryx_E82C9FE5-E125-41EF-8C14-D4999E97CDE5';
                    
            var_dump($consolida_resrourceId);
			$filter = array(
				'idwf' => 'pacc3PP',
				'resourceId' =>$this->consolida_resrourceId
			);
			// -----busco en el cuit
			$data ['querystring'] = $this->input->post('query');
			$filter ['$or'] [] = array(
				'data.1695' => array(
					'$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
				)
			);
			// -----busco en el nombre empresa
			$filter ['$or'] [] = array(
				'data.1693' => array(
					'$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
				)
			);
			// -----busco en el nro proyecto
			$filter ['$or'] [] = array(
				'data.7356' => array(
					'$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
				)
			);
			$filter ['$or'] [] = array(
				'case' => array(
					'$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
				)
			);
			
			$tokens = $this->bpm->get_tokens_byFilter($filter , array('case','data','checkdate'), array('checkdate' => false));
			
			$data ['empresas'] = array_map(function ($token) {
				// var_dump($token['_id']);
				$case = $this->bpm->get_case($token ['case'], 'pacc3PP');
				$pacc3PP = $this->bpm->get_case($token ['case'], 'pacc3PP');
				$model = 'pacc3PP';
				$data = $this->bpm->load_case_data($case);
				$url = '';
				$url_msg = '';
				$hist=$this->bpm->get_token_history('pacc3PP',$token['case']);
				foreach($hist as $t) $keys[$t['resourceId']]=$t['status'];
				$keys = array_keys($case['token_status']);
				$url_clone = ''; 
				//var_dump($token['_id'],$keys);
				$idResource = $model; 
				$estado = $data ['Proyectos_pacc'] ['5689'][0];
				$url_clone =$this->base_url . 'bpm/engine/run/model/' . $model. '/' .$token['case'];
				$url_cancelar_pp = '';
				$url_cancelar_pde = '';
				$url_reevaluar_pp = '';
				$url_reevaluar_pde = '';
				$url_bpm = '';
				//if (in_array(145, $this->id_group) or in_array(1001, $this->id_group) or $this->user->isAdmin()) {
					//$model = ($pacc3PP) ? 'pacc3PP' : 'pacc3PP';
					$url_bpm = $this->base_url . 'bpm/engine/run/model/' . $model . '/' . $token ['case'];
				//}

				/* STATUS */
				$status = "N/A";
				if (isset($data ['Proyectos_pacc'] ['5689'])) {
					$this->load->model('app');
					$option = $this->app->get_ops(580);
					$status = $option[$data ['Proyectos_pacc'] ['5689'][0]];
				}
				$id=$data ['Proyectos_pacc'] ['id'];
				if ($id) {
						$todo = $id . '&idwf=' . $idwf . '&case=' . $idcase . '&token=' . $token;
						$url_ver ='../../dna2/RenderView/printvista.php?idvista=4123&idap=295&id='.$todo;
				} else {
					$url_ver ='';
				}
				return array(
					'_d' => $token ['_id'],
					'case' => $token ['case'],
					'nombre' => (isset($data['Empresas']['1693'])) ? $data['Empresas']['1693'] : 'Error//no está cargado',
					'cuit' =>  (isset($data['Empresas']['1695'])) ? $data['Empresas']['1695'] : 'XXXX',
					'Nro' => (isset($data ['Proyectos_pacc']['7356'])) ? $data ['Proyectos_pacc'] ['7356'] : 'N/A',
					'estado' => $status,
					'fechaent' => date('d/m/Y', strtotime($token ['checkdate'])),
					'link_open' => $this->bpm->gateway($url),
					'link_msg' => $url_ver,
					'url_clone' => $url_clone,
					'url_bpm' => $url_bpm,
					'url_cancelar_pp' => $url_cancelar_pp,
					'url_cancelar_pde' => $url_cancelar_pde,
					'url_reevaluar_pp' => $url_reevaluar_pp,
					'url_reevaluar_pde' => $url_reevaluar_pde,

				);
			}, $tokens);
			$data ['count']= count($tokens);
        var_dump(count($tokens));
		}
	}

//***************************
        $data['base_url'] = $this->base_url;
        //var_dump($keys,$data);exit;
        $this->parser->parse($templateAg, $data, false, true);
        
    }
}
