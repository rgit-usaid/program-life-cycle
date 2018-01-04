<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");

## request for get all project============
if(isset($_REQUEST['project_id']) && $_REQUEST['project_id']!="")
{
    $data = array();
    $project_id = $mysqli->real_escape_string(trim($_REQUEST['project_id']));
	
	$select_project_evaluation = "select pe.*, p.project_id
		from usaid_project_evaluation as pe
		left join usaid_project as p ON pe.project_id = p.project_id 
		where p.project_id = '".$project_id."'
		ORDER BY pe.start_date";
		
	$result_project_evaluation = $mysqli->query($select_project_evaluation);
	if($result_project_evaluation->num_rows>0)
	{	
		$i=0;
		while($fetch_project_evaluation = $result_project_evaluation->fetch_array()){
			$data[$i]['evaluation_id'] = $fetch_project_evaluation['id'];
			$data[$i]['project_id'] = $fetch_project_evaluation['project_id']; 
			$data[$i]['type'] = $fetch_project_evaluation['type'];
			$data[$i]['evaluation_type_description_other'] = $fetch_project_evaluation['evaluation_type_description_other'];
			$data[$i]['management_type'] = $fetch_project_evaluation['management_type'];
			$data[$i]['estimated_cost'] = $fetch_project_evaluation['estimated_cost'];
			$data[$i]['start_date'] = $fetch_project_evaluation['start_date'];
			$data[$i]['end_date'] = $fetch_project_evaluation['end_date'];
			$data[$i]['additional_comment'] = $fetch_project_evaluation['additional_comment'];
			$i++;
		}
		deliverResponse(200,'Record Found',$data); 
	}
	else{
		deliverResponse(200,'No Record Found',NULL);
	}	
}
else{
     deliverResponse(200,'Invalid Request',NULL);
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