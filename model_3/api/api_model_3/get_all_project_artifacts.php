<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
 
## request for get all employee details============
$data = array(); 
if(isset($_REQUEST['project_id']) && $_REQUEST['project_id']!=""){
	
	$select_artifacts = "SELECT ud.*, pr.title as project, pa.title as activity, par.title as activity_project, dt.tags FROM usaid_documents ud
	LEFT JOIN usaid_project pr ON pr.project_id = ud.link_id
	LEFT JOIN usaid_project_activity pa ON pa.activity_id = ud.link_id
	LEFT JOIN usaid_project par ON par.project_id = pa.project_id
	LEFT JOIN usaid_documents_tags dt ON dt.document_id = ud.id
	WHERE pr.project_id LIKE '%".$_REQUEST['project_id']."%' OR pa.project_id LIKE '%".$_REQUEST['project_id']."%'";
	$result_artifacts = $mysqli->query($select_artifacts);
	$i=0; 
	while($fetch_artifacts = $result_artifacts->fetch_array()){
		 
		 $data[$i]['document_id'] = $fetch_artifacts['id']; 
		 $data[$i]['link_id'] = $fetch_artifacts['link_id']; 
		 $data[$i]['link_type'] = $fetch_artifacts['link_type'];
		 $data[$i]['filename'] = $fetch_artifacts['filename'];
		 $data[$i]['filepath'] = $fetch_artifacts['filepath'];
		 $data[$i]['project'] = $fetch_artifacts['project'];
		 if($fetch_artifacts['activity']!="" && $fetch_artifacts['activity_project']!=""){
		 	$data[$i]['project'] = $fetch_artifacts['activity_project'];
		 }
		 $data[$i]['activity'] = $fetch_artifacts['activity'];
		 $data[$i]['tags'] = $fetch_artifacts['tags'];
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
function deliverResponse($status,$status_msg,$data){
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}

?>