<?php

/**
 * Description of kpi_count_cases
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 */
class kpi_state {

    //put your code here
    var $CI;

    public function __construct($params = array()) {

// Set the super object to a local variable for use throughout the class
        $this->CI = & get_instance();
    }

    function tile($kpi) {
        if ($kpi['resourceId'] <> '') {
            $cpData = $this->core($kpi);
            $cpData['id']=$cpData['idkpi'];
            $cpData['more_info_class'] = "load_tiles_after";
            $cpData['more_info_link'] = base_url() . "bpm/kpi/list_cases/" . $kpi['idkpi'];
            $rtn = $this->CI->parser->parse('dashboard/tiles/' . $kpi['widget'], $cpData, true);
        } else {
            $rtn = '<strong>Warning!</strong>Function:' . $kpi['type'] . '<br/>' . $kpi['title'] . '<br/>resourceId not defined. ';
        }
        return $rtn;
    }

    function list_cases($kpi) {

        $filter = $this->CI->kpi_model->get_filter($kpi); 
        unset($filter['resourceId']);
        $status = (isset($kpi['status'])) ? $kpi['status'] : 'user';
        
        $filter = $this->CI->kpi_model->get_filter($kpi);        
        $filter['status'] = $kpi['status'];
        $tokens = $this->CI->bpm->get_tokens_byResourceId($kpi['resourceId'], $filter);
        $cases = array_map(function ($token) {
            return $token['case'];
        }, $tokens);
        return $cases;
        
    }

    function core($kpi) {
        $filter = $this->CI->kpi_model->get_filter($kpi);        
        $status = (isset($kpi['status'])) ? $kpi['status'] : 'user';
        $filter['status'] = $kpi['status'];
        $tokens = $this->CI->bpm->get_tokens_byFilter_count($filter);
        $cpData = $kpi;
        
        $cpData['number'] = $tokens;
        return $cpData;
    }

    function widget($kpi) {
        if ($kpi['resourceId'] <> '') {


            $cpData = $this->core($kpi);
            $cpData['label'] = $cpData['number'];
            $cpData['more_info_class'] = "load_tiles_after";
            $cpData['more_info_link'] = base_url() . "bpm/kpi/list_cases/" . $kpi['idkpi'];
            $rtn = $this->CI->parser->parse('dashboard/' . $kpi['widget_type'] . '/' . $kpi['widget'], $cpData, true);
        } else {
            $rtn = $this->CI->ShowMsg('<strong>Warning!</strong>Function:' . $kpi['type'] . '<br/>' . $kpi['title'] . '<br/>resourceId not defined. ', 'alert');
        }
        return $rtn;
    }

}
