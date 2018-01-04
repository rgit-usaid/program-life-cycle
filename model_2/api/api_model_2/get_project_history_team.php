<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get project current team ============
if(isset($_REQUEST['project_id']) or isset($_REQUEST['activity_id']))
{
    $data = array();
    $whereVal = " where team_member_status='Remove' and team_member_end_date is not null ";
    if(isset($_REQUEST['project_id']) and $_REQUEST['project_id']!='')
    {
        $whereVal .=" and project_id = '".$_REQUEST['project_id']."'";
    }
    if(isset($_REQUEST['activity_id']) and $_REQUEST['activity_id']!='')
    {
        $whereVal .=" and activity_id = '".$_REQUEST['activity_id']."'";
    }
    $select_project_team = "select * from usaid_project_team ".$whereVal;
    $result_project_team = $mysqli->query($select_project_team);
    $i=0; 
    while($fetch_project_team = $result_project_team->fetch_array())
    {
        $data[$i]['project_id'] = $fetch_project_team['project_id'];
        $data[$i]['activity_id'] = $fetch_project_team['activity_id']; 
        $data[$i]['employee_id'] = $fetch_project_team['employee_id']; 
        $data[$i]['project_team_role'] = $fetch_project_team['project_team_role'];  
        $data[$i]['team_member_start_date'] = dateFormat($fetch_project_team['team_member_start_date']);
		$data[$i]['team_member_end_date'] = dateFormat($fetch_project_team['team_member_end_date']);
        $i++; 
    }
    if(count($data)>0){
        deliverResponse(200,'Record Found',$data);
    }
    else{
       deliverResponse(200,'No Record Found',NULL);
    }
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