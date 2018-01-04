<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
 
$data = array();
$i=0;

if(isset($_REQUEST['gohashid']) && isset($_REQUEST['association_type']) && $_REQUEST['association_type']=="Project"){	
	/*Delete old project activity including own project record*/
	$del_assoc= "DELETE FROM usaid_association WHERE gohashid = '".$_REQUEST['gohashid']."' and (association_type = 'Project' OR association_type = 'Activity') and association_id LIKE '%".$_REQUEST['elem_id']."%'";
	$res_assoc = $mysqli->query($del_assoc);
	
	if(!$res_assoc){
		$data['msg']="Something went wrong";
		$data['msg_type']="Error";  
	}	
	
	/*Insert new project record if project is selected*/
	if($_REQUEST['action']=="Ins"){
		$ins_assoc= "INSERT into usaid_association 
		SET gohashid = '".$_REQUEST['gohashid']."', 
		association_type = '".$_REQUEST['association_type']."',
		association_id = '".$_REQUEST['elem_id']."'";
		$res_assoc = $mysqli->query($ins_assoc);
		
		if($res_assoc){
			$data['msg']="Association Inserted Successfully";
			$data['msg_type']="Success";  
		}
		else{
			$data['msg']="Something went wrong"; 
			$data['msg_type']="Error"; 
		}	
	}
	
}
else if(isset($_REQUEST['gohashid']) && isset($_REQUEST['association_type']) && $_REQUEST['association_type']=="Activity"){	
	/*Delete old project activity including own project record*/
	$activity_arr = explode(',',$_REQUEST['elems']);
	$temp_array = explode('-',$activity_arr[0]);
	$project_id = $temp_array[0]; 
	$del_assoc= "DELETE FROM usaid_association WHERE gohashid = '".$_REQUEST['gohashid']."' and (association_type = 'Project' OR association_type = 'Activity') and association_id LIKE '%".$project_id."%'";
	$res_assoc = $mysqli->query($del_assoc);
	
	if(!$res_assoc){
		$data['msg']="Something went wrong";
		$data['msg_type']="Error";  
	}	
	
	/*Insert new project activity record if project activity is selected*/
	if(count($activity_arr)>0){
		$ins_act = "INSERT into usaid_association (gohashid, association_type, association_id) VALUES"; 
		
		for($i=0; $i<count($activity_arr); $i++){
			$ins_act.= "('".$_REQUEST['gohashid']."','".$_REQUEST['association_type']."','".$activity_arr[$i]."'),";
		}
		
		$ins_act = substr_replace($ins_act,"",-1);
		$res_assoc = $mysqli->query($ins_act);
		
		if($res_assoc){
			$data['msg']="Association Inserted Successfully";
			$data['msg_type']="Success";  
		}
		else{
			$data['msg']="Something went wrong"; 
			$data['msg_type']="Error"; 
		}	
	}
	
}
if(count($data)>0)
{
	deliverResponse(200,'Record Found',$data);
}
else{
   deliverResponse(200,'No Record Found',NULL);
} 
  
###function for deliver reponse on request===================
function deliverResponse($status,$status_msg,$data)
{
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}?>