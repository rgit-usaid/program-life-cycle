<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
## request for get all archive project monitoring output score ============
if(isset($_REQUEST['archive_project_monitoring_id']))
{
    $data = array();
    $archive_project_monitoring_id = trim($_REQUEST['archive_project_monitoring_id']);
    $select_archive_monitoring_output_score = "select * from usaid_archive_project_monitoring_output_score where archive_project_monitoring_id = '".$archive_project_monitoring_id."'";
    $result_archive_monitoring_output_score = $mysqli->query($select_archive_monitoring_output_score);
	$i=0; 
	while($fetch_archive_output_score = $result_archive_monitoring_output_score->fetch_array()){
			$data[$i]['id'] = $fetch_archive_output_score['id'];
			$data[$i]['project_monitoring_id'] = $fetch_archive_output_score['project_monitoring_id']; 
			$data[$i]['project_output_score_description'] = $fetch_archive_output_score['project_output_score_description'];
			$data[$i]['project_output_impact_weight'] = $fetch_archive_output_score['project_output_impact_weight'];
			$data[$i]['project_output_performance'] = $fetch_archive_output_score['project_output_performance'];
			$data[$i]['project_output_risk'] = $fetch_archive_output_score['project_output_risk'];
			$data[$i]['added_on'] = $fetch_archive_output_score['added_on'];
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
	 deliverResponse(200,'No Record Found',NULL);
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