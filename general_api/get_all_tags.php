<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all tags============
$data = array();
$select_tags = "select * from usaid_tags";
$result_tags = $mysqli->query($select_tags);
if($result_tags->num_rows>0){
	 $i=0;
	 
	 while($fetch_tags = $result_tags->fetch_array()){
	 	$data[$i]['tag_id'] = $fetch_tags['id']; 
		$data[$i]['name'] = $fetch_tags['name']; 
	 	$i++;
	 }
	 deliverResponse(200,'Record Found',$data); 
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