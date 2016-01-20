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
        $status = (isset($kpi['status'])) ? $kpi['status'] : 'user';
        $filter['$and'] = array(
            array('token_status.' . $kpi['resourceId'] => array('$exists' => true)),
            array('token_status.' . $kpi['resourceId'] => $status),
        );
        $cases_filtered = $this->CI->bpm->get_cases_byFilter($filter);

        $cases = array_map(function ($case) {
            return $case['id'];
        }, $cases_filtered);
        return $cases;
    }

    function core($kpi) {
        $filter = $this->CI->kpi_model->get_filter($kpi);
        unset($filter['resourceId']);
        $status = (isset($kpi['status'])) ? $kpi['status'] : 'user';
        $filter['$and'] = array(
            array('token_status.' . $kpi['resourceId'] => array('$exists' => true)),
            array('token_status.' . $kpi['resourceId'] => $status),
        );
        $cases_filtered = $this->CI->bpm->get_cases_byFilter($filter);
        $cpData = $kpi;
        $cpData['number'] = count($cases_filtered);
        // var_dump(json_encode($filter),$cpData);
        // exit;
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
