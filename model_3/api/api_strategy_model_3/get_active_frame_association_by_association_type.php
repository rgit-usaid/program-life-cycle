<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## get all association of active frame============
$data = array();
$i=0;	
if(isset($_REQUEST['association_type']) && $_REQUEST['association_type']!="" && isset($_REQUEST['association_id']) && $_REQUEST['association_id']!="" && isset($_REQUEST['elem_type']) && $_REQUEST['elem_type']!=""){
	
	if($_REQUEST['elem_type']=="subir"){
		$sel_assoc = "SELECT uac.gohashid FROM usaid_association uac 
		LEFT JOIN usaid_sub_intermediate_result sbir ON sbir.id = REPLACE(uac.gohashid,'SR-','') 
		LEFT JOIN usaid_frame uf ON uf.id = sbir.frame_id 
		WHERE uac.association_type='".$_REQUEST['association_type']."' AND uac.association_id ='".$_REQUEST['association_id']."' AND uf.status='Active' AND uac.gohashid LIKE 'SR-%'";
	}
	else if($_REQUEST['elem_type']=="ir"){
		$sel_assoc = "SELECT uac.gohashid FROM usaid_association uac 
		LEFT JOIN usaid_intermediate_result ir ON ir.id = REPLACE(uac.gohashid,'IR-','') 
		LEFT JOIN usaid_frame uf ON uf.id = ir.frame_id 
		WHERE uac.association_type='".$_REQUEST['association_type']."' AND uac.association_id ='".$_REQUEST['association_id']."' AND uf.status='Active' AND uac.gohashid LIKE 'IR-%'";
	}
	$exe_assoc = $mysqli->query($sel_assoc);
	while($res_assoc = $exe_assoc->fetch_array()){
		$data[$i] = $res_assoc['gohashid'];
		$i++;
	}
}
if(count($data)>0)
{
	deliverResponse(200,'Record Found',$data);
}
else{
   deliverResponse(200,'No Record Found',NULL);
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