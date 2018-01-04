<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['employee_id']))
{
    $data = array();
    $employee_id = $mysqli->real_escape_string(trim($_REQUEST['employee_id']));
    $select_project = "select p.*,ps.stage_name ,ps.stage_percentage
                        from usaid_project as p
                        left join usaid_project_stage as ps ON ps.stage_id = p.project_stage_id
                        left join rgdemode_amp.usaid_project_team as pt ON pt.project_id = p.project_id group by p.project_id";
    $result_project = $mysqli->query($select_project);
    $i=0; 
    while($fetch_project = $result_project->fetch_array())
    {
         $data[$i]['project_id'] = $fetch_project['project_id']; 
		 $data[$i]['title'] = $fetch_project['title'];
		 $data[$i]['project_purpose'] = $fetch_project['project_purpose'];
		 $data[$i]['estimated_total_funding_amount'] = $fetch_project['estimated_total_funding_amount'];
		 $data[$i]['originating_operating_unit_id'] = $fetch_project['originating_operating_unit_id']; 
		 $data[$i]['implementing_operating_unit_id'] = $fetch_project['implementing_operating_unit_id']; 
		 $data[$i]['use_of_govt_to_govt_plan'] = $fetch_project['use_of_govt_to_govt_plan'];  
		 $data[$i]['conducting_analyses_plan'] = $fetch_project['conducting_analyses_plan'];  
		 $data[$i]['proposed_design_schedule'] = $fetch_project['proposed_design_schedule'];  
		 $data[$i]['proposed_design_cost'] = $fetch_project['proposed_design_cost'];
		 $data[$i]['project_stage_id'] = $fetch_project['project_stage_id'];
		 $data[$i]['stage_percentage'] = $fetch_project['stage_percentage'];
		 $data[$i]['stage_name'] = $fetch_project['stage_name']; 	
         $i++; 
    }
    if(count($data)>0){
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