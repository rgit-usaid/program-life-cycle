<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all archive project geo location============
if(isset($_REQUEST['archive_geo_id'])) 
{
    $data = array();
    $archive_geo_id = trim($_REQUEST['archive_geo_id']);
    $select_archive_project_geo = "select * from usaid_archive_project_geo where archive_geo_id = '".$archive_geo_id."'";
    $result_archive_project_geo = $mysqli->query($select_archive_project_geo);
    
   if($result_archive_project_geo->num_rows>0)
    {
		$i=0;
       	while($fetch_archive_project_geo = $result_archive_project_geo->fetch_array()){
			$data[$i]['id'] = $fetch_archive_project_geo['id'];
			$data[$i]['archive_geo_id'] = $fetch_archive_project_geo['archive_geo_id'];
			$data[$i]['project_id'] = $fetch_archive_project_geo['project_id'];
			$data[$i]['project_activity_id'] = $fetch_archive_project_geo['project_activity_id'];
			$data[$i]['address'] = $fetch_archive_project_geo['address'];
			$data[$i]['latitude'] = $fetch_archive_project_geo['latitude']; 
			$data[$i]['longitude'] = $fetch_archive_project_geo['longitude'];
			$data[$i]['location_type'] = $fetch_archive_project_geo['location_type'];
			$data[$i]['precision_code'] = $fetch_archive_project_geo['precision_code'];
			$data[$i]['centrally_managed'] = $fetch_archive_project_geo['centrally_managed'];
			$data[$i]['impacted_area'] = $fetch_archive_project_geo['impacted_area'];
			$data[$i]['country'] = $fetch_archive_project_geo['country'];
			$data[$i]['added_on'] = $fetch_archive_project_geo['added_on'];
			$i++;
	}    
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