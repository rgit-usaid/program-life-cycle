<?php
include("config/config.inc.php");
header('Content-type: application/json');

## function for get date format========
function dateFormat($date)
{
   $date_formated = '';
   if($date!='0000-00-00' and $date!='')
   {
     $date_formated = date('m/d/Y',strtotime($date)); 
   }
   return $date_formated; 
}

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
if(isset($_REQUEST['project_search']))
{
    $wherVal = " where p.id!=''";
    $search_title = trim($_REQUEST['search_title']);
    if($search_title!='')
    {
       $wherVal .= " and p.title like '%".$search_title."%'"; 
    }
    getProject($wherVal);
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
    $select_stage = "select stage_id,stage_name,stage_percentage from usaid_project_stage ".$wherVal."";
    $result_stage = $mysqli->query($select_stage);
    $i=0; 
    while($fetch_stage = $result_stage->fetch_array())
    {
         $data[$i]['stage_id'] = $fetch_stage['stage_id']; 
         $data[$i]['stage_name'] = $fetch_stage['stage_name'];
         $data[$i]['stage_percentage'] = $fetch_stage['stage_percentage'];  
         $i++;
    }
    if(count($data)>0)
    {
        deliverResponse(200,'Record Found',$data);
    }
    else
    {
        deliverResponse(200,'No Record Found',NULL);
    } 
}

## create by : rachit
##function for get all project or single project using condition================
function getProject($wherVal='')
{
    global $mysqli;
    $data = array();
    $select_project = "select p.*,ps.stage_name ,ps.stage_percentage
                        from usaid_project as p
                        left join usaid_project_stage as ps ON ps.stage_id = p.project_stage_id
                         ".$wherVal."";
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
    if(count($data)>0)
    {
        deliverResponse(200,'Record Found',$data);
    }
    else
    {
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
}

?>