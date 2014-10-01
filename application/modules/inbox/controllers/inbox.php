<?php

class Inbox extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->config('config');
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->library('pagination');
        $this->load->model('msg');
        $this->load->model('user');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module().'/';
        $this->user->authorize();
        //----LOAD LANGUAGE
        $this->lang->load('inbox', $this->config->item('language'));

        $this->idu = (int) $this->session->userdata('iduser');
        

        
    }
    
    //==== MAIN LISTING

    function Index() {

    	$customData['lang']= $this->lang->language;
     	$customData['user'] = (array) $this->user->get_user($this->idu);
     	$customData['inbox_icon'] = 'icon-envelope';
     	$customData['usercan_create'] = $this->user->has('root/modules/inbox/controllers/inbox/new_msg') || $this->user->isAdmin();

     	//$customData['usercan_create']=true;
     	$customData['js'] = array(
     			'selectJS'		
     	);
  	
     	$customData['css'] = array(
     			$this->base_url . "inbox/assets/css/inbox.css" => 'Dashboard CSS'
     	);
     	
     	$customData['base_url'] = $this->base_url;
     	$customData['module_url'] = $this->module_url;   	
    	$customData['folder']='inbox';  // Default	
    	$customData['reply']=false;
     	$customData['inbox_count']=$this->msg->count_msgs($this->idu,'inbox'); 
     	$customData['my_msgs']=$this->get_folder();
     	
    	$customData['content']=$this->parser->parse('inbox/inbox', $customData, true, true);

	    return $customData;
    }
    
    //====== wrapper
    function print_folder($folder='inbox',$current_page=1){
    	$filter=$this->input->post('filter');
    	echo $this->get_folder($folder,$current_page,$filter);
    }
    
    //====== folder inbox
    function get_folder($folder='inbox',$current_page=1,$filter=array()){


	    // Star 
		if($folder=='star'){
			$folder=null;
			$filter=array('star'=>true);
		}

    	$source='to';
    	$customData['folder']=$folder;

    //==== Pagination
    	define("ITEMS_X_PAGE",10);
    	$skip=($current_page-1)*ITEMS_X_PAGE;
    	//==== Bring me my MSGs!!!
    	$mymgs = $this->msg->get_msgs($this->idu,$folder,$skip,ITEMS_X_PAGE,$filter);

    	$items=$mymgs->count();

    	$config=array('url'=>$this->base_url."inbox/print_folder/".$folder,
    			'current_page'=>$current_page,
    			'items_total'=>$items, // Total items in array
    			'items_x_page'=>ITEMS_X_PAGE,
    			'pagination_width'=>5,
    			//     			'class_ul'=>""
    			'class_a'=>"ajax",
    			'ajax_target'=>'inbox-list'
    	);
    	$customData['pagination']=$this->pagination->index($config);

    	foreach ($mymgs as $msg) {
    		$msg['msgid'] = $msg['_id'];
    		$msg['subject']=(strlen($msg['subject'])!=0)?($msg['subject']):("No Subject");

    		//Time lapse
    		$datetime1 = date_create($msg['checkdate']);
    		$datetime2 = date_create('now');
    		$interval = date_diff($datetime1, $datetime2);
		
    	    	    if($interval->format('%a%')==0){
    			// Less then 24hs
    			$horas=$interval->h;
    			$min=$interval->i;
    			if($horas==0){
    				$msg['msg_time']="$min min";
    			}else{
    				$msg['msg_time']=$datetime1->format('H:i');
    			}
    		}else{
    			$msg['msg_time']=$datetime1->format('Y-m-d H:i'); 
    		}


    		$msg['icon_star'] = (isset($msg['star']) && $msg['star']==true) ? ('fa fa-star') : ('fa fa-star-o');
    		$msg['read'] = (isset($msg['read'])&&$msg['read']==true) ? ('read') : ('unread');
    		$msg['body']=nl2br($msg['body']);
    		$userdata = $this->user->get_user($msg['from']);
    		$msg['from_name']=(empty($userdata))?('No user'):($userdata->nick);
    		$userdata = $this->user->get_user($msg['to']);
    		$msg['to_name']=(empty($userdata))?('No user'):($userdata->nick);
    
    		$customData['mymsgs'][] = $msg;
    	}
    	
    	return $this->parser->parse('inbox/msgs', $customData, true, true);
    }
    
    //====  Mini INBOX version for toolbar
    function toolbar(){
        $customData['lang']= $this->lang->language;
    	$customData['base_url'] = $this->base_url;
    	$customData['module_url'] = $this->module_url;
    	$customData['inbox_count']=$this->msg->count_msgs($this->idu,'inbox',false);
    	$mymgs = $this->msg->get_msgs($this->idu,'inbox',null,4);
    	foreach ($mymgs as $msg) {
    		$msg['msgid'] = $msg['_id'];
    		$msg['subject']=(strlen($msg['subject'])!=0)?($msg['subject']):("No Subject");
    		//Time lapse
    		$datetime1 = date_create($msg['checkdate']);
    		$datetime2 = date_create('now');
    		$interval = date_diff($datetime1, $datetime2);
    	    	    if($interval->format('%a%')==0){
    			// Less then 24hs
    			$horas=$interval->h;
    			$min=$interval->i;
    			if($horas==0){
    				$msg['msg_time']="$min min";
    			}else{
    				$msg['msg_time']=$datetime1->format('H:i');
    			}
    		}else{
    			$msg['msg_time']=$datetime1->format('Y-m-d H:i'); 
    		}
			// 
    		$msg['excerpt']=substr($msg['body'],0,10);
    		$customData['mymsgs'][] = $msg;
     	}
    	return $this->parser->parse('inbox/toolbar', $customData, true, true);

    }
    
    //====  Widget - show msgs by case
    function show_msgs_by_filter($filter=array()){

    	$customData['lang']= $this->lang->language;
    	$customData['base_url'] = $this->base_url;
    	$customData['module_url'] = $this->module_url;

    	$mymgs = $this->msg->get_msgs_by_filter($filter);
    	$customData['qtty']=$mymgs->count();

    	foreach ($mymgs as $msg) {
    		$msg['msgid'] = $msg['_id'];
    		$msg['subject']=(strlen($msg['subject'])!=0)?($msg['subject']):("No Subject");
    		//Time lapse
    		$datetime1 = date_create($msg['checkdate']);
    		$datetime2 = date_create('now');
    		$interval = date_diff($datetime1, $datetime2);
    	    if($interval->format('%a%')==0){
    			// Less then 24hs
    			$horas=$interval->h;
    			$min=$interval->i;
    			if($horas==0){
    				$msg['msg_time']="$min min";
    			}else{
    				$msg['msg_time']=$datetime1->format('H:i');
    			}
    		}else{
    			$msg['msg_time']=$datetime1->format('Y-m-d'); 
    		}
    		//
    		$msg['excerpt']=substr($msg['body'],0,10);
    		$customData['mymsgs'][] = $msg;

    	}

    	echo $this->parser->parse('inbox/widgets/msgs_by_case', $customData, true, true);
    }
  

    //==== GET MSG BY ID
    function get_msg(){
    $msgid=$this->input->post('id');

    $mymgs = $this->msg->get_msg($msgid);
    $mymgs['debug']=$this->input->post('whereiam');
	   if($this->input->post('whereiam')!='outbox')
	    	$this->msg->set_read("read",$msgid); // Marco leido
    
    echo json_encode($mymgs);
    }
    

    
    //====  STAR MARK
    function set_star(){
    $state=$this->input->post('state');
    $id=$this->input->post('msgid');
    $this->msg->set_star($state,$id);
    }

    //====  READ MARK
    function set_read(){
    $state=$this->input->post('state');
    $id=$this->input->post('msgid');
	    foreach($id as $myid){
	    	$this->msg->set_read($state,$myid);
	    }
    }
    
    //====  TAG MARK
    function set_tag(){
     	$tag=$this->input->post('tag');
     	$id=$this->input->post('msgid');

     	foreach($id as $myid){
     		$this->msg->set_tag($tag,$myid);
     	}
    }
    
    //====  SEND MSG
    function send(){

        $data=$this->input->post('data');    
        $to=explode(",",$data[0]['value']);
        $subject=$data[1]['value'];
        $body=$data[2]['value'];
        $msg=array(
        'subject'=>$subject,
        'body'=>$body,
        'from'=>$this->idu
        );
        $i=0;

        foreach($to as $user){
            $this->msg->send($msg,(int)$user);
            $i++;
        }
        echo $i;
        
    }
    
    //====  NEW MSG
    function new_msg(){

         $customData['user'] = (array) $this->user->get_user($this->idu);
         $customData['user']['signature']=empty($customData['user']['signature'])?"":"\n\n".$customData['user']['signature'];
         $customData['lang']= $this->lang->language;
		// REPLY: segment 4 is msgid 
		$customData['reply']=0;

//         if($this->uri->segment(4)){
//             $msgid=$this->uri->segment(4);
//             $mymgs = $this->msg->get_msg($msgid);
            
//             $sender=$this->user->get_user($mymgs['from']);

//             $customData['reply']=1;
//             $customData['reply_name']=$sender->nick;
//             $customData['reply_idu']=$sender->idu;
//             $customData['reply_body']=$mymgs['body'];
//             $customData['reply_title']=$mymgs['subject'];
//             $customData['reply_date']=$mymgs['checkdate'];
//         }
                

         $this->parser->parse('inbox/inbox_new', $customData);

    }
    
    // Get list of users
    function get_users(){
       
        $row_array = array();
        $term=$this->input->post('term');

        $allusers=$this->user->get_users(null,100,null,$term,null,'both');

        foreach($allusers as $myuser){
/*         	var_dump($myuser);
        	continue; */
        	$name="";
        	if(!empty($myuser->name))$name.=$myuser->name;
        	if(!empty($myuser->lastname))$name.=", ".$myuser->lastname;
			if(!empty($myuser->nick))
				$name.=" (".$myuser->nick.")";
				
          	 $row_array[]=array('text'=> $name,'id'=>$myuser->idu);
        }

        $ret['results']=$row_array;
        echo json_encode($ret);
        
    }
    
    // Move msg to trash
    
    function move(){
        $msgid=$this->input->post('msgid');
        $folder=$this->input->post('folder');
        foreach($msgid as $msg){
        	$this->msg->move($msg,$folder);
        }
    }
   
        // Move msg to trash
    
    function remove(){
        $msgid=$this->input->post('msgid');
        foreach($msgid as $msg){
        	 $this->msg->remove($msg);
        }
       
    }
    


    
} //

?>
