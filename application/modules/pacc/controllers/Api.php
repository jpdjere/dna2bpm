<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * api
 *
 * esta clase provee servicios para componentes externos ya sea en formato JSON
 * u otros necesarios dependiendo del cliente.
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jan 28, 2015
 */
class Api extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('api');
        $this->load->helper('html');
        $this->load->model('bpm/bpm');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

    /**
     * List all api methods not in ignore_arr
     *
     */
    function Index() {
        $ignore_arr = array('Index', '__construct', '__get');
        $methods = array_diff(get_class_methods('api'), $ignore_arr);
        asort($methods);
        $links = array_map(function($item) {
            return '<a href="' . $this->module_url . strtolower(get_class()) . '/' . strtolower($item) . '">' . $item . '</a>';
        }, $methods);
        $attributes = array('class' => 'api_endpoint');
        echo ul($links, $attributes);
    }

    /**
     * Desempeño analista 1.1 y 1.3
     * http://redmine.industria.gob.ar/issues/19395
     *
     * @param type $filter Apply filter
     */
    function ranking_analista($filter = null,$mode = 'json') {
        $this->load->model('pacc/model_pacc');
        $idgroup=66;
        $rs= $this->user->getbygroup($idgroup);
        if (!count($rs))
            $rs = $this->user->getbygroup(1);

        $users=array_map(function($user){
            return $user['idu'];
        },$rs);

        $rsqtty=$this->model_pacc->ranking_group($idgroup,array('pacc1PDE'));
        //---acá hay que pesar cada token con la tabla de Seba
        $total=0;

        foreach($rsqtty['result'] as $result){
            // var_dump($token);
            $ranking[$result['_id']]=$result['qtty'];
            $total+=$result['qtty'];

        }
        
        $arr_class = array("success", "warning", "danger");
        foreach ($rs as $user) {
            // $porc = rand(0, 100);
            $porc=(isset($ranking[$user['idu']]))?intval(($ranking[$user['idu']]/$total)*100):0;
            $qtty=(isset($ranking[$user['idu']]))?$ranking[$user['idu']]:0;
            $arr = array(
                'id' => $user['idu'],
                'name' => $user['name'] . ' ' . $user['lastname'],
                'color' => '',
                'avatar' => $this->user->get_avatar($user['idu']),
                'value' => $porc,
                'qtty'=>$qtty,
            );
            $porc_arr[]=$qtty;
            if ($porc < 34) {
                $arr['class'] = 'danger';
            } elseif ($porc > 66) {
                $arr['class'] = 'success';
            } else {
                $arr['class'] = 'warning';
            }

            $data[] = $arr;
        }

        array_multisort($porc_arr,SORT_DESC,$data);
        $data=  array_slice($data,0,10);
        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "dump":
                var_dump($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
    }
    /**
     * Devuelve las prioridades para los widgets de prioridades
     *
     */
    function prioridades(){
        $filter=array(
            'resourceId'=>'oryx_4C79F11E-C212-4140-8B99-1162B761B636',
            'status'=>'user',
        );
        $fields=array('iduser','checkdate','case','idwf');
        $tokens=$this->bpm->get_tokens_byFilter($filter, $fields);
        $now= new DateTime();

        foreach($tokens as &$token){
            $checkdate=new DateTime($token['checkdate']);
            $diff=  date_diff($checkdate, $now);
            $token['age']=$diff->days;
            $diff_arr[]=$diff->days;
        }
        array_multisort($diff_arr, SORT_DESC, $tokens);
        $tokens=array_slice($tokens, 0, 5);
        var_dump($tokens);
    }

    /**
    *  Recibimos notifiación del proceso de scan y delegamos el flujo
    */
    function scan(){
        Modules::run('pacc/mesa_de_entradas/scan');
    }

    /**
     * Proceso para checkear la existencia de un nro IP
     *
     */
    function check(){
        $this->load->model('model_pacc');
        $this->load->model('app');
        $parts=$this->uri->segment_array();
        $ipstr=$parts[4].'/'.$parts[5];
        $rs=$this->model_pacc->get_proyecto($ipstr);
        $rtn=array('check'=>false);
        if($rs){
            $ops = $this->app->get_ops(648);
            $rtn['check']=true;
            $rtn['title']=(isset($rs[0][5673])) ? $rs[0][5673]:'';
            $rtn['estado']=(isset($rs[0][6225])) ? $ops[$rs[0][6225][0]]:'';
            //---Tomo datos de la empresa
            if(isset($rs[0][6223])){
            $idemp=$rs[0][6223][0];
            // var_dump($idemp);exit;
            $rtn['CUIT']=$this->app->getvalue($idemp, 1695);
            $rtn['nombre']=$this->app->getvalue($idemp, 1693);

            }
        }
        //----para cors
        header("Access-Control-Allow-Origin: *");
        $this->output->set_content_type('json');
        echo json_encode($rtn);
    }
    /**
     * Proceso para checkear la existencia de un nro IP
     *
     */
    function get(){
        $api_key="7938C7DAECB874E23B0BFC63B47540CD";
        if($api_key==$this->input->post('api_key')){
            $this->load->model('model_pacc');
            $parts=$this->uri->segment_array();
            $ipstr=$parts[4].'/'.$parts[5];
            $rs=$this->model_pacc->get_proyecto($ipstr);
            if($rs){
                $rtn=$rs;
                $rtn['check']=true;
            } else {
                $rtn=array('check'=>false);
            }
            //----para cors
            header("Access-Control-Allow-Origin: *");
            $this->output->set_content_type('json');
            echo json_encode($rtn);
        }
    }

    /**
     * Proceso para checkear la existencia de un nro IP
     *
     */
    function tipos_documentos(){
            $this->load->model('model_pacc');
            $this->load->model('app');
            $parts=$this->uri->segment_array();
            if(count($parts)>4){
            $ipstr=$parts[4].'/'.$parts[5];
            $rs=$this->model_pacc->get_proyecto($ipstr);
            // $tipos=array(
            //     'Formulario de Presentación',
            //     'Certificación Contable',
            //     'Anexo DDJJ',
            //     'DDJJ sobre la no vinculación de proveedores',
            //     'Escritura',
            //     'Copia DNI',
            //     'Datos Bancarios',
            //     'Presupuesto e informes',
            //     'Carta Compromiso',
            //     'Formulario de Solicitud de Desembolso',
            //     'Formulario de Rendición de Anticipo',
            //     'Formulario de Solicitud de Anticipo',
            //     'Certificación Contable',
            //     'Proveedores y Prestadores - Copia de facturas',
            //     'Proveedores y Prestadores - Copia de recibos, transferencias, cheques y retenciones',
            //     'Proveedores y Prestadores - Copia de remitos',
            //     'Certificado de origen',
            //     'Seguro de Caución',
            //     'Productos Verificables',
            //     'Recibo de ANR',
            //     'Pedido de modificaciones',
            //     'Inscripción en la Afip',
            //     'Notas de Proyectos',
            //     'Notas varias',
            //     'Formulario de Solicitud de Retribución',
            //     'Factura de Ventanilla',
            //     'Recibo de Ventanilla',
            //     'Otros'
            // );
            $tipos=$this->app->get_ops(827);

            // sort($tipos);
            $rtn['document_types']=$tipos;

            //---hago un switch del estado para ver que documentos aplican
            switch ($estado){

            }
            } else {
                $rtn['error']='Error en Nro proyecto';
            }
            //----para cors
            header("Access-Control-Allow-Origin: *");
            $this->output->set_content_type('json');
            echo json_encode($rtn);
    }

    function buscar_proyectos($type = null,$query=null) {

        $this->load->model('model_pacc');
        $this->load->model('app');
        if(!$query)
            $query=$this->input->post('query');
        $tokens=$this->model_pacc->buscar_proyectos($type,$query);
        $ret=array();
        $ops = $this->app->get_ops(648);
        foreach($tokens as $token){
        $rtn=array();
        $rs[0]=$token['data'];
        //   var_dump($rs);
            $rtn['check']=true;
            $rtn['tipo']=$type;
            $rtn['nro']=(isset($rs[0][6390])) ? $rs[0][6390]:'';
            $rtn['title']=(isset($rs[0][5673])) ? $rs[0][5673]:'';
            $estado='';
            if(isset($rs[0][6225])){
                $estado=(isset($ops[$rs[0][6225][0]])) ? $ops[$rs[0][6225][0]]:$rs[0][6225][0];
            } else {
                $estado='N/A';
            }
            $rtn['estado']=$estado;
            //---Tomo datos de la empresa
            // var_dump($idemp);exit;
            $rtn['CUIT']=(isset($rs[0][1695])) ? $rs[0][1695]:'';
            $rtn['nombre']=(isset($rs[0][1695]))?$rs[0][1693]:'';
            $ret[]=$rtn;

        }

        //----para cors
        header("Access-Control-Allow-Origin: *");
        $this->output->set_content_type('json');
        echo json_encode($ret);
    }
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */