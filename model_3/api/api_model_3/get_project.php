<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['project_id']))
{
    $data = array();
    $project_id = $mysqli->real_escape_string(trim($_REQUEST['project_id']));
    $select_project = "select p.*,ps.stage_name ,ps.stage_percentage
                        from usaid_project as p
                        left join usaid_project_stage as ps ON ps.stage_id = p.project_stage_id where p.project_id = '".$project_id."'";
    $result_project = $mysqli->query($select_project);
    $fetch_project = $result_project->fetch_array();
    if($fetch_project['project_id']!='')
    {
        $data['project_id'] = $fetch_project['project_id']; 
        $data['title'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['title']));
		$data['project_purpose'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['project_purpose']));
        $data['project_stage_id'] = $fetch_project['project_stage_id'];
		$data['stage_name'] = $fetch_project['stage_name'];  
		$data['stage_percentage'] = $fetch_project['stage_percentage'];  
		$data['project_description'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['project_description'])); 
        $data['employee_id'] = $fetch_project['employee_id'];
		$data['originating_operating_unit_id'] = $fetch_project['originating_operating_unit_id'];
		$data['implementing_operating_unit_id'] = $fetch_project['implementing_operating_unit_id'];
		$data['estimated_total_funding_amount'] = $fetch_project['estimated_total_funding_amount'];
		$data['engaging_local_actor_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['engaging_local_actor_plan']));
		$data['conducting_analyses_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['conducting_analyses_plan']));
		$data['use_of_govt_to_govt_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['use_of_govt_to_govt_plan']));
		$data['proposed_design_schedule'] = $fetch_project['proposed_design_schedule'];
		$data['proposed_design_cost'] = $fetch_project['proposed_design_cost'];
		$data['context'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['context']));
		$data['leveraged_resources'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['leveraged_resources']));
		$data['conclusions_and_analyses_summary'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['conclusions_and_analyses_summary']));
		$data['management_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['management_plan']));
		$data['financial_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['financial_plan']));
		$data['monitoring_evaluation_and_learning_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['monitoring_evaluation_and_learning_plan']));
		$data['activity_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['activity_plan']));
		$data['logical_framework_discretion'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['logical_framework_discretion']));
		$data['planned_start_date'] ="";
		if($fetch_project['planned_start_date']!=""){
			$data['planned_start_date'] = dateFormat($fetch_project['planned_start_date']);
		}
		$data['planned_end_date'] = "";
		if($fetch_project['planned_end_date']!=""){
			$data['planned_end_date'] = dateFormat($fetch_project['planned_end_date']);
		}
		$data['next_review_date'] ="";
		if($fetch_project['next_review_date']!=""){
			$data['next_review_date'] = dateFormat($fetch_project['next_review_date']);
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