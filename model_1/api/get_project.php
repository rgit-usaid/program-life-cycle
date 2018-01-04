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
        $data['project_published'] = $fetch_project['project_published']; 
        $data['project_stage_id'] = $fetch_project['project_stage_id']; 
        $data['stage_name'] = $fetch_project['stage_name']; 
        $data['stage_percentage'] = $fetch_project['stage_percentage']; 
        $data['design_record_create_date'] = dateFormat($fetch_project['design_record_create_date']);
        $data['planned_start_date'] = dateFormat($fetch_project['planned_start_date']); 
        $data['planned_end_date'] = dateFormat($fetch_project['planned_end_date']); 
        $data['actual_start_date'] = dateFormat($fetch_project['actual_start_date']); 
        $data['actual_end_date'] = dateFormat($fetch_project['actual_end_date']); 
        $data['next_review_date'] =  dateFormat($fetch_project['next_review_date']);
        $data['environmental_threshold'] = $fetch_project['environmental_threshold'];          
        $data['gender_threshold'] = $fetch_project['gender_threshold'];
        $data['team_marker'] = $fetch_project['team_marker'];
        $data['employee_id'] = $fetch_project['employee_id'];
		$data['operating_unit_id'] = $fetch_project['operating_unit_id'];
		$data['smart_sheet_hyperlink'] = $fetch_project['smart_sheet_hyperlink'];        
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