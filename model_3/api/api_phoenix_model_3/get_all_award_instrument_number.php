<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all award instrument number============

if(isset($_REQUEST['vendor_id']))
{
    $cond = " where id!='' ";
    if(isset($_REQUEST['vendor_id']) and $_REQUEST['vendor_id']!='')
    {
        $cond .= " and vendor_id='".$_REQUEST['vendor_id']."' ";
    }
    if(isset($_REQUEST['operating_unit_id']) and $_REQUEST['operating_unit_id']!='')
    {
        $cond .= " and operating_unit_id='".$_REQUEST['operating_unit_id']."' ";
    }
    $data = array();
    echo $select_data = "select * from usaid_requisition_award  ".$cond."";
    $result_data = $mysqli->query($select_data);
    $i=0; 

    while($fetch_data = $result_data->fetch_array())
    {
         $data[$i]['id'] = $fetch_data['id'];  
         $data[$i]['award_number'] = $fetch_data['award_number'];
         $data[$i]['award_name'] = $fetch_data['award_name']; 
         $data[$i]['employee_id'] = $fetch_data['employee_id'];
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
function deliverResponse($status,$status_msg,$data)
{
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}
?>