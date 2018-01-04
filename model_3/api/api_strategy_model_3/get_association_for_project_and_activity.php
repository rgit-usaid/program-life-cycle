<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
 

$data = array();
if(isset($_REQUEST['gohashid'])){
	$sel_assoc = "SELECT * FROM usaid_association WHERE gohashid='".$_REQUEST['gohashid']."' AND association_type = 'Project' AND association_id ='".$_REQUEST['project_id']."'";
	$exe_assoc = $mysqli->query($sel_assoc);
	if($exe_assoc->num_rows>0){
		$data['assoc_type'] =  "Project";
	}
	else{
		$sel_assoc = "SELECT * FROM usaid_association WHERE gohashid='".$_REQUEST['gohashid']."' AND association_type = 'Activity' AND association_id LIKE '%".$_REQUEST['project_id']."-%'";
		$exe_assoc = $mysqli->query($sel_assoc);
		$i=0;
		if($exe_assoc->num_rows>0){
			while($res_assoc = $exe_assoc->fetch_array()){
				$data['assoc_type'] =  "Activity";
				$data['activity_list'][$i] =  $res_assoc['association_id'];
				$i++;
			}
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
}
?>