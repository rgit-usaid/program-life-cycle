<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['title']) and $_REQUEST['title']!='')
{
    $data = array();
    $title = $mysqli->real_escape_string(trim($_REQUEST['title']));
    $select_project = "select p.*,ps.stage_name ,ps.stage_percentage
                        from usaid_project as p
                        left join usaid_project_stage as ps ON ps.stage_id = p.project_stage_id where p.title like '%".$title."%' group by p.id";
    $result_project = $mysqli->query($select_project);
    $i=0; 
    while($fetch_project = $result_project->fetch_array())
    {
         $next_review_date = '';
         $data[$i]['project_id'] = $fetch_project['project_id']; 
         $data[$i]['title'] = $fetch_project['title'];
         $data[$i]['project_published'] = $fetch_project['project_published']; 
         $data[$i]['project_stage_id'] = $fetch_project['project_stage_id']; 
         $data[$i]['stage_name'] = $fetch_project['stage_name']; 
         $data[$i]['stage_percentage'] = $fetch_project['stage_percentage']; 
         $data[$i]['design_record_create_date'] = dateFormat($fetch_project['design_record_create_date']);
         $data[$i]['planned_start_date'] = dateFormat($fetch_project['planned_start_date']); 
         $data[$i]['planned_end_date'] = dateFormat($fetch_project['planned_end_date']); 
         $data[$i]['actual_start_date'] = dateFormat($fetch_project['actual_start_date']); 
         $data[$i]['actual_end_date'] = dateFormat($fetch_project['actual_end_date']); 
         $data[$i]['next_review_date'] =  dateFormat($fetch_project['next_review_date']);
         $data[$i]['project_purpose'] = $fetch_project['project_purpose'];
         $data[$i]['environmental_threshold'] = $fetch_project['environmental_threshold'];          
         $data[$i]['gender_threshold'] = $fetch_project['gender_threshold'];     
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