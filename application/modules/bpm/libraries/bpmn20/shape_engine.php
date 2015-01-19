<?php
//---Include all traits needed
include(APPPATH.'modules/bpm/libraries/bpmn20/task_trait.php');
include(APPPATH.'modules/bpm/libraries/bpmn20/event_trait.php');
include(APPPATH.'modules/bpm/libraries/bpmn20/flow_trait.php');
include(APPPATH.'modules/bpm/libraries/bpmn20/gate_trait.php');
include(APPPATH.'modules/bpm/libraries/bpmn20/start_end_trait.php');
include(APPPATH.'modules/bpm/libraries/bpmn20/subproc_trait.php');

/**
 * This Class handles the execution of shapes
 */ 

class Shape_engine {
    //put your code here
    var $CI;
    use task_trait;
    use event_trait;
    use flow_trait;
    use gate_trait;
    use start_end_trait;
    use subproc_trait;
    public function __construct() {
// Set the super object to a local variable for use throughout the class
        $this->CI = & get_instance();
    }
   
}