<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['project_id']))
{
    $data = array();
    $project_id = $mysqli->real_escape_string(trim($_REQUEST['project_id']));
    $select_project_geo = "select pg.*
                        from usaid_project_geo as pg
                        left join usaid_project as p ON p.project_id = pg.project_id where p.project_id = '".$project_id."'";
    $result_project_geo = $mysqli->query($select_project_geo);
    
    if($result_project_geo->num_rows>0)
    {
		$i=0;
       	while($fetch_project_geo = $result_project_geo->fetch_array()){
			$data[$i]['geo_location_id'] = $fetch_project_geo['id'];
			$data[$i]['project_id'] = $fetch_project_geo['project_id'];
			$data[$i]['project_activity_id'] = $fetch_project_geo['project_activity_id'];
			$data[$i]['address'] = $fetch_project_geo['address'];
			$data[$i]['latitude'] = $fetch_project_geo['latitude']; 
			$data[$i]['longitude'] = $fetch_project_geo['longitude'];
			$data[$i]['location_type'] = $fetch_project_geo['location_type'];
			$data[$i]['precision_code'] = $fetch_project_geo['precision_code'];
			$data[$i]['centrally_managed'] = $fetch_project_geo['centrally_managed'];
			$data[$i]['impacted_area'] = $fetch_project_geo['impacted_area'];
			$data[$i]['country'] = $fetch_project_geo['country'];
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