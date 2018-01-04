<?php
include("config/config.inc.php");
header('Content-type: application/json');

##request for stage==============
if(isset($_REQUEST['stage']))
{
    $wherVal = " where stage_id!=''";
    $stage_id = trim($_REQUEST['stage_id']);
    if($stage_id!='')
    {
        $wherVal .= " and stage_id = '".$stage_id."'";
    }
    getStage($wherVal);
}

## request for project============
if(isset($_REQUEST['project']))
{
    $wherVal = " where p.id!=''";
    $project_id = trim($_REQUEST['project_id']);
    if($project_id!='')
    {
       $wherVal .= " and p.project_id = '".$project_id."'"; 
    }
    getProject($wherVal);
}  

## create by : rachit
## function for use to get all stage of a project ================
function getStage($wherVal='')
{
    global $mysqli;
    $data = array();
    $select_stage = "select stage_id,stage_name from usaid_project_stage ".$wherVal."";
    $result_stage = $mysqli->query($select_stage);
    $i=0; 
    while($fetch_stage = $result_stage->fetch_array())
    {
         $data[$i]['stage_id'] = $fetch_stage['stage_id']; 
         $data[$i]['stage_name'] = $fetch_stage['stage_name']; 
         $i++;
    }
    if(count($data)>0)
    {
        deliverResponse(200,'Record Found',$data);
    }
    else
    {
        deliverResponse(400,'Invalid Request',NULL);
    } 
}

## create by : rachit
##function for get all project or single project using condition================
function getProject($wherVal='')
{
    global $mysqli;
    $data = array();
    $select_project = "select p.*,ps.stage_name 
                        from usaid_project as p
                        left join usaid_project_stage as ps ON ps.stage_id = p.project_stage_id
                         ".$wherVal."";
    $result_project = $mysqli->query($select_project);
    $i=0; 
    while($fetch_project = $result_project->fetch_array())
    {
         $data[$i]['project_id'] = $fetch_project['project_id']; 
         $data[$i]['title'] = $fetch_project['title'];
         $data[$i]['project_published'] = $fetch_project['project_published']; 
         $data[$i]['stage_name'] = $fetch_project['stage_name']; 
         $data[$i]['design_record_create_date'] = $fetch_project['design_record_create_date'];
         $data[$i]['estimated_start_date'] = $fetch_project['estimated_start_date'];
         $data[$i]['estimated_end_date'] = $fetch_project['estimated_start_date'];        
         $i++; 
    }
    if(count($data)>0)
    {
        deliverResponse(200,'Record Found',$data);
    }
    else
    {
        deliverResponse(400,'Invalid Request',NULL);
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
}

?>