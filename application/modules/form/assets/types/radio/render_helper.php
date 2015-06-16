<?php

function edit_radio($frame, $value) {
    $CI = & get_instance();
    $retstr = '';
    $ops = array();
    $disabled='';
     $required='';
//$CI->load->library('mongo');
//---ensure array----
     //var_dump($frame);
    $value = (array) $value;

    $height = (isset($frame['cols'])) ? $frame['cols'] : 10;
    
    if(isset($frame['locked']) && $frame['locked'] === true)
        $locked = "readonly";
    else $locked ='';
    
    ///Campo Hidden
    if(isset($frame['hidden']) && $frame['hidden'] === true)
        $disabled = "hidden";
    else $disabled ='';
    
    ///Campo requerido
    if(isset($frame['required']) && $frame['required'] === true)
        $required = "required";
    else $required ='';  
     
    /*
    if (isset($frame['disabled']))
        $disabled = ($frame['disabled']) ? getDisabledStr($frame['type']) : null;

    if (isset($frame['required']))
        $required = (isset($frame['required'])) ? getRequiredStr($frame['type']) : null;
    */    
    $option = $CI->mongo->db->options->findOne(array('idop' => (int)$frame['idop']));
//prepare options array
    if (isset($option['fromContainer'])) { // if gets data from internal db
        $option['data'] = getOpsFromContainer($option);
    }
    //var_dump($frame,$option);
    //--data comes from loaded options
    foreach ($option['data'] as $thisop) {
        $ops[$thisop['value']] = $thisop['text'];
    }

    //---4 ordering
    if (isset($frame['sortBy'])) {
        if ($frame['sortBy']=='value') {
            ksort($ops);
        } else {
            asort($ops);
        }
    }

    if (count($ops) > 60 and !isset($frame['cols']))
        $height = 20;
    if (count($ops) > 150 and !isset($frame['cols']))
        $height = 50;
    /* PENDING
     * nclude("checkrelative.php");
     */

    if (count($ops)) {
        $i = 1;
        if ($required<>'')
            $retstr.="<label for=\"" . $frame['cname'] . "\" class=\"error\" style=\"display:none\">* Seleccione uno</label>";
        $retstr.="<div style='display:table'><div style='display:table-row'><div style='display:table-cell'>";

        foreach ($ops as $key => $text) {
            $sel = (in_array((string) $key, $value)) ? "checked='checked'" : '';
            $retstr.="<label><input type='radio' $required $disabled name='" . $frame['cname'] . "' value='$key' $sel>";
            
            if($disabled != 'hidden')
            $retstr.=$text;
            
            $retstr.="</label><br>\r";
            
            
            if ($i++==$height) {
                $retstr.="</div><div style='display:table-cell'>";
                $i = 1;
            }
        }
        $retstr.="</div></div></div>\r";
        if (isset($frame['allowOther'])) {
            /* TODO
             * f ($sel==-1) {
             * chek="checked";
             * SQL="SELECT valor FROM regopciones WHERE id=$id AND idopcion=$idopcion";
             * val=$forms2->Execute($SQL) or DIE ("Option:$nombrecontrol No se pudo abrir la consulta.");
             * valor=$val->Fields("valor");
             }
             * retstr.="<input type=radio name='".$nombrecontrol."' value='-1' $chek";
             * retstr.="><font class=text>Otros</font><br>\r";
             }
             * retstr.="</td><td id='_$nombrecontrol'></td></tr></table>";
             * f ($otros==1) {
             * retstr.="<table><tr><td>&nbsp;&nbsp;<input type=text value='$valor' name='O".$nombrecontrol."' id='O".$nombrecontrol."'><font class=text size=1> (Si eligi� 'Otros' especificar) </font></td></tr></table>";
             *
             */
        }
    }
    //echo $retstr.'</br>';
    return $retstr;
}

//----how has to be viewed
function view_radio($frame, $value) {
    $CI = & get_instance();
    $retstr = '';
    $ops=array();
    $value=(array)$value;
    $option = $CI->mongo->db->options->findOne(array('idop' => $frame['idop']));
    //prepare options array
    if (isset($option['fromContainer'])) { // if gets data from internal db
        $option['data'] = getOpsFromContainer($option);
    }

    //--data comes from loaded options
    foreach ($option['data'] as $thisop) {
        if (in_array($thisop['value'], $value))
            $ops[$thisop['value']] = $thisop['text'];
    }
    return implode(',', $ops);
}
?>
