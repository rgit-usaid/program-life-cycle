<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all Clin 1 by requisition number============
if(isset($_REQUEST['associate_from_archive_id']) and $_REQUEST['associate_type']!='')
{   
    $data = array();
    $associate_from_archive_id = trim($_REQUEST['associate_from_archive_id']);
	$associate_type = trim($_REQUEST['associate_type']); 
    $select_data = "select * from usaid_archive_association_link where associate_from_archive_id='".$associate_from_archive_id."' and associate_from_type='".$associate_type."'";
    $result_data = $mysqli->query($select_data);
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
         $data[$i]['id'] = $fetch_data['id'];  
		 $data[$i]['associate_from_archive_id'] = $fetch_data['associate_from_archive_id'];  
         $data[$i]['associate_from_number'] = $fetch_data['associate_from_number'];
         $data[$i]['link_to_id'] = $fetch_data['link_to_id'];
         $data[$i]['link_to_type'] = $fetch_data['link_to_type'];
         $data[$i]['associate_from_type'] = $fetch_data['associate_from_type'];
         $data[$i]['status'] = $fetch_data['status'];
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