<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['operating_unit_id']))
{
    $operating_unit_id = $_REQUEST['operating_unit_id'];
    $data = array();
    $select_project = "select p.*,ps.stage_name ,ps.stage_percentage
                        from usaid_project as p
                        left join usaid_project_stage as ps ON ps.stage_id = p.project_stage_id 
                        where p.implementing_operating_unit_id = '".$operating_unit_id."'";
    $result_project = $mysqli->query($select_project);
    $i=0; 
    while($fetch_project = $result_project->fetch_array())
    {
         $data[$i]['project_id'] = $fetch_project['project_id']; 
         $data[$i]['title'] = $fetch_project['title'];
         $data[$i]['project_published'] = $fetch_project['project_published']; 
         $data[$i]['project_stage_id'] = $fetch_project['project_stage_id']; 
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