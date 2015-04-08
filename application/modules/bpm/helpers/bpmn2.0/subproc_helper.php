<?php

function run_CollapsedSubprocess($shape, $wf, $CI) {
    $debug = (isset($CI->debug[__FUNCTION__])) ? $CI->debug[__FUNCTION__] : false;
    if ($debug)
        echo '<H1>COLLAPSED SUBPROCESS:' . $shape->properties->name . '</H1>';

    $token = $CI->bpm->get_token($wf->idwf, $wf->idcase, $shape->resourceId);
    $parent['token'] = $token;
    $parent['case'] = $wf->idcase;
    $parent['idwf'] = $wf->idwf;
    $silent = true;
    //----Set token status to waiting
    $CI->bpm->set_token($wf->idwf, $wf->idcase, $shape->resourceId, $shape->stencil->id, 'waiting');
    //---check if child proceses already exists.
    if (isset($token['child'])) {
        $CI->Run('model', $token['child']['idwf'], $token['child']['case']);
        // ---now run child processes
        if (isset($token ['child'])) {
            if ($shape->properties->entry) {
                $child_idwf = $shape->properties->entry;
                foreach ($token['child'][$child_idwf] as $child_idcase) {
                    $this->Run('model', $child_idwf, $child_idcase);
                }
            }
        }
    } else {
        if ($shape->properties->entry) {
            $child_idwf = $shape->properties->entry;
            /* Create new child cases
             * Check if multiple
             */
            $prev=$CI->bpm->get_previous($shape->resourceId, $wf);
            foreach($prev as $prev_shape){
                if($prev_shape->stencil->id=='DataStore'){
                $dataStoreName=$prev_shape->properties->name;
                }
            }
            switch ($shape->properties->looptype) {
                case "Sequential"://---start one instance at a time assumes data input does not change
                    break;
                case "Parallel"://---start all instances at once
                // echo "paralell";
                    // loop thru data input and start a case for each one
                    if($CI->data->$dataStoreName){
                        foreach($CI->data->$dataStoreName as $item){
                            //start a case with $item as data in data['parent_data']
                            // var_dump($item);
                            $data['parent_data']=$item;
                            //---Newcase($model, $idwf, $manual = false, $parent = null, $silent = false,$data=array())
                            $CI->newcase('model', $child_idwf, false, $parent, $silent,$data);
                        }
                    } else {
                        show_error('DataStore:'.$dataStoreName.' not loaded');
                    }
                    break;
                case "Standard":
                    break;
                case "Standard":
                    break;
                default://-- "None"
                    $CI->newcase('model', $child_idwf, false, $parent, $silent);
                    break;
            }
        }
    }
}

function run_Subprocess($shape, $wf, $CI) {
    $CI = & get_instance();
    $debug = (isset($CI->debug[__FUNCTION__])) ? $CI->debug[__FUNCTION__] : false;
    if ($debug)
        echo '<H1>SUBPROCESS:' . $shape->properties->name . '</H1>';
    $token = $CI->bpm->get_token($wf->idwf, $wf->idcase, $shape->resourceId);
    switch ($token['status']) {
        case 'waiting':
            //---check that some finish event has been reached
            foreach ($shape->childShapes as $child) {
                $has_finihed = false;
                //---only one finis event can make the subproc marked as finish.
                // find end events  childs
                if (preg_match('/^End/', $child->stencil->id)) {
                    $child_token = $CI->bpm->get_token($wf->idwf, $wf->idcase, $child->resourceId);
                    if ($child_token['status'] == 'finished') {
                        $has_finihed = true;
                    }
                }
            }
            //----if all went well then move on!
            if ($has_finihed) {
                $CI->bpm->movenext($shape, $wf);
            }
            break;
        default:
            //---SAME AS STARTING A CASE
            //---Get start shape
            $start_shapes = $CI->bpm->get_shape_byname('StartNoneEvent', $shape);
            if (count($start_shapes)) {
                $start_shape = $start_shapes[0];
                if ($debug) {
                    echo '<h2>$start_shapes</h2>';
                    var_dump($start_shape);
                    echo '<hr>';
                }
                //----Raise an error if doesn't found any start point
                if (!$start_shapes)
                    show_error("The Schema doesn't have an start point");
                //---Start all  StartNoneEvents as possible
                foreach ($start_shapes as $start_shape) {
                    $CI->bpm->set_token($wf->idwf, $wf->idcase, $start_shape->resourceId, $start_shape->stencil->id, 'pending');
                }
                //---now Set the status to waiting
                $CI->bpm->set_token($wf->idwf, $wf->idcase, $shape->resourceId, $shape->stencil->id, 'waiting');
            } else {
                //----if has no childshapes move next
                $CI->bpm->movenext($shape, $wf);
            }
            break;
    }//----end switch
}

?>
