<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
 
## request for get all geo location archive details============
$data = array(); 
if(isset($_REQUEST['project_id'])){
	$project_id = trim($_REQUEST['project_id']);
	$select_archive_geo_location = "select *  from usaid_archive_geo WHERE project_id ='".$project_id."' order by archive_on desc";
	$result_archive_geo_location = $mysqli->query($select_archive_geo_location);
	$i=0; 
	while($fetch_archive_geo_location = $result_archive_geo_location->fetch_array()){
		 $data[$i]['id'] = $fetch_archive_geo_location['id']; 
		 $data[$i]['project_id'] = $fetch_archive_geo_location['project_id'];
		 $data[$i]['archive_on'] = $fetch_archive_geo_location['archive_on'];
		 $data[$i]['modified_by'] = $fetch_archive_geo_location['modified_by'];
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