<?php

function run_CollapsedSubprocess($shape, $wf, $CI) {
    $debug = (isset($CI->debug[__FUNCTION__])) ? $CI->debug[__FUNCTION__] : false;
    // $debug=true;
    if ($debug)
        echo '<H1>COLLAPSED SUBPROCESS:' . $shape->properties->name . '</H1>';

    $token = $CI->bpm->get_token($wf->idwf, $wf->case, $shape->resourceId);
    $idcase = $wf->case;
    $idwf = $wf->idwf;
    $parent['token'] = $token;
    $parent['case'] = $wf->case;
    $parent['idwf'] = $wf->idwf;
    $case=$CI->bpm->get_case($wf->case, $wf->idwf);
    $pdata=$case['data'];
    $silent = true;
    if($debug) echo "<h2>Sub-Proc Type:{$shape->properties->subprocesstype}</h2>";
    switch($shape->properties->subprocesstype){
        case  "Embedded":
            //---replace embedded
            run_Subprocess($shape, $wf, $CI);        
            break;
        case  "Independent":
        case  "Reference":
            $data=array();
            $data['parent']=$parent;
            $data['parent_data']=$case['data'];
                //---check if child proceses already exists.
            // if (isset($token['child'])) {
            //     // ---now run child processes
            //         if ($shape->properties->entry) {
            //             $child_idwf = $shape->properties->entry;
            //             foreach ($token['child'][$child_idwf] as $child_idcase) {
            //                 $this->start('model', $child_idwf, $child_idcase);
            //             }
            //         }
            // } else {
                
                //--Set token status to waiting
                $CI->bpm->set_token($wf->idwf, $wf->case, $shape->resourceId, $shape->stencil->id, 'waiting');
                if ($shape->properties->entry) {
                    $child_idwf = $shape->properties->entry;
                    /* Create new child cases
                     * Check if multiple $dataStoreName
                     */
                    $dataStoreName=''; 
                    $prev=$CI->bpm->get_previous($shape->resourceId, $wf);
                    foreach($prev as $prev_shape){
                        if($prev_shape->stencil->id=='DataStore'){
                        $dataStoreName=$prev_shape->properties->name;
                        }
                    }
                    
                    switch ($shape->properties->looptype) {
                        case "Sequential"://---start one instance at a time assumes data input does not change
                        if($dataStoreName){
                                // loop thru data input and start a case for each one
                                if($CI->data->$dataStoreName){
                                    //@todo get next item
                                    // foreach($CI->data->$dataStoreName as $item){
                                    //     //start a case with $item as data in data['parent_data']
                                    //     // var_dump($item);
                                    //     $data['parent_data']=$item;
                                    //     //---Newcase($model, $idwf, $manual = false, $parent = null, $silent = false,$data=array())
                                    //     $CI->newcase('model', $child_idwf, false, $parent, $silent,$data);
                                    // }
                                } else {
                                    show_error('DataStore:'.$dataStoreName.' not loaded');
                                }
                            } else {
                                //----create from shape
                                //start a case with $item as data in data['parent_data']
                                $child_case=$CI->bpm->gen_case($child_idwf,null , $data) ;
                                $pdata['data']['child'] = isset($pdata['data']['child']) ?(array) $pdata['data']['child'] : array();
                                $pdata['data']['child'][$child_idwf][] = $child_case;
                                $pdata['data']['child'][$child_idwf]=array_unique($pdata['data']['child'][$child_idwf]);
                                $CI->bpm->update_case($idwf, $idcase, $pdata);
                                //---Start childs left first to start last
                                $CI->Startcase('model', $child_idwf, $child_case,false);
                                    
                                
                            }
                            break;
                        case "Parallel"://---start all instances at once
                        // echo "paralell";
                            if($dataStoreName){
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
                            } else {
                                //----create from shape
                                for($i=1;$i<=$shape->properties->startquantity;$i++){
                                        //start a case with $item as data in data['parent_data']
                                        
                                        $child_case=$CI->bpm->gen_case($child_idwf,null , $data) ;

                                        
                                        $pdata['data']['child'] = isset($pdata['data']['child']) ?(array) $pdata['data']['child'] : array();
                                        $pdata['data']['child'][$child_idwf][] = $child_case;
                                        $pdata['data']['child'][$child_idwf]=array_unique($pdata['data']['child'][$child_idwf]);
                                        $CI->bpm->update_case($idwf, $idcase, $pdata);
                                        //---Start childs left first to start last
                                        if($i<>1){
                                            $CI->Startcase('model', $child_idwf, $child_case,true);
                                            $CI->Run('model', $child_idwf, $child_case,null, true);
                                        } else {
                                            $runrun=$child_case;
                                        }
                                    }
                                    
                                    $CI->start('model', $child_idwf, $runrun,false);
                                
                            }
                            break;
                            
                        /**
                         * STANDARD JUST 1
                         */ 
                        case "Standard":
                        default://-- "None" start just 1 child case
                             $child_case=$CI->bpm->get_case($idcase, $child_idwf);
                             if(!$child_case){
                                $newcase=$CI->bpm->gen_case($child_idwf,$idcase , $data) ;
                             } else {
                                 //---update childcase
                                 $childcase['data']['parent_data']=$case['data'];
                                 $CI->bpm->save_case($child_case);
                             }
                             $case=$CI->bpm->get_case($wf->case, $wf->idwf);
                             $case['data']['child'] = isset($case['data']['child']) ? $case['data']['child'] : array();
                             $case['data']['child'][$child_idwf][] = $idcase;
                             $case['data']['child'][$child_idwf]=array_unique($case['data']['child'][$child_idwf]);
                             $CI->bpm->save_case($case);
                            //---Start child
                             $CI->Startcase('model', $child_idwf, $wf->case,false);
                            break;
                    }
                }
            
                
            // }//---end if token['child']
            break;
        default:
            break;
    }
}

function run_Subprocess($shape, $wf, $CI) {
    $CI = & get_instance();
    $debug = (isset($CI->debug[__FUNCTION__])) ? $CI->debug[__FUNCTION__] : false;
    $debug=true;
    if ($debug)
        echo '<H1>SUBPROCESS:' . $shape->properties->name . '</H1>';
    $token = $CI->bpm->get_token($wf->idwf, $wf->case, $shape->resourceId);
    if ($debug)
        var_dump($token['status']);
    switch ($token['status']) {
        case 'waiting':
            //---check that some finish event has been reached
            foreach ($shape->childShapes as $child) {
                $has_finihed = false;
                //---only one finis event can make the subproc marked as finish.
                // find end events  childs
                if (preg_match('/^End/', $child->stencil->id)) {
                    $child_token = $CI->bpm->get_token($wf->idwf, $wf->case, $child->resourceId);
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
            $start_shapes = $CI->bpm->get_start_shapes($shape);
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
                //---Start all  StartNoneEvents as possible as case_subproc
                
                foreach ($start_shapes as $start_shape) {
                    // $CI->bpm->set_token($wf->idwf, $wf->case.'_'.$shape->properties->name, $start_shape->resourceId, $start_shape->stencil->id, 'pending');
                    $CI->bpm->set_token($wf->idwf, $wf->case, $start_shape->resourceId, $start_shape->stencil->id, 'pending');
                }
                //---now Set the status to waiting
                $CI->bpm->set_token($wf->idwf, $wf->case, $shape->resourceId, $shape->stencil->id, 'waiting');
            } else {
                //----if has no childshapes move next
                $CI->bpm->movenext($shape, $wf);
            }
            break;
    }//----end switch
}

?>
