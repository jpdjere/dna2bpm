  <?php

  // if (!defined('BASEPATH'))
  //     exit('No direct script access allowed');
  /**
  * "ventanilla electrónica" de la AFIP
  * 
  * @autor Diego Otero
  * 
  * @version     1.0 
  * 
  * 
  */

  class Consultas extends MX_Controller {
   
    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';

        
        #LIBRARIES          
        $this->load->library('parser');
        $this->load->library('dashboard/ui');
        $this->load->library('pdf/pdf');
        
        #MODELS
        $this->load->model('consultas_model');

        
        #CREDENTIALS
        $this->idu = (int) $this->session->userdata('iduser');
        $this->user->authorize();
        


    }
    
    function Index() {
        $data['base_url'] = $this->base_url;
        $data['title'] = 'Consulta por CUIT';
        $data['logobar'] = $this->ui->render_logobar();
        

        $data_select = NULL;        

        echo $this->parser->parse('form_consulta_cuit', $data, true, true);
    }


    function _new_report($anexo='06'){
      $data = array();
        $report_name = $this->input->post('report_name');


        $data['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $data['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';

        if ($this->input->post('cuit_socio'))
            $data['cuit_socio'] = $this->input->post('cuit_socio');

        $data['sgr_id'] = $this->input->post('sgr');

        if ($this->input->post('sgr')) {
            $result = $this->consultas_model->buscar_cuits_registrados($data);  

        }

    }

    function process() {


        $data = $this->input->post('cuit');
        
        $data = isset($data)? $this->input->post('cuit') : '20180826123';#30712164707;#30714571725;

        #$data = (int)$data;


        $rtn = $this->consultas_model->buscar_cuits_registrados($data);      


        if(empty($rtn)){
            $rtn['tipo_socio'] = 0;
        } else {
          $vinculados = $this->vinculados($data); 
          
          $rtn['vinculados'] = "<h4>VINCULADOS</h4><hr> <table width='100%' class='table table-hover'>" . $vinculados. "</table>";
          
         }

      if(!$debug) {
        $this->output->set_content_type('json','utf-8');
        echo json_encode($rtn);

    }else{
        var_dump($rtn);
    }
  }    

  function vinculados($parameter) {

        $vinculados_info = $this->consultas_model->buscar_cuits_vinculados($parameter);
        $hear_arr = array("title" => 'vinculados'); 
        $rtn = $this->parser->parse('consultas/vinculados_table_head_view', $hear_arr, true, true);  

        foreach ($vinculados_info as $key => $value) {          

          $data = array();
          $data['CUIT_VINCULADO'] = $value['anexo']['CUIT_VINCULADO'];
          $data['RAZON_SOCIAL_VINCULADO'] = $value['anexo']['RAZON_SOCIAL_VINCULADO'];
          $data['TIPO_RELACION_VINCULACION']= $value['anexo']['TIPO_RELACION_VINCULACION'];
          $data['PORCENTAJE_ACCIONES'] = ($value['anexo']['PORCENTAJE_ACCIONES']) ? $value['anexo']['PORCENTAJE_ACCIONES']*100:0;          
          
          $rtn .= $this->parser->parse('consultas/vinculados_table_view', $data, true, true); 

         }
         
        if(empty($vinculados_info))
          $rtn = '<tr><td>NO TIENE VINCULADOS</td></tr>';

        return $rtn;
  } 

  


  function mongodate_to_print($date) {
    $check_year = (int) date('Y', $date->sec);
    if ($check_year > 1970)
        return date('d/m/Y', $date->sec);
  }

  }//class
