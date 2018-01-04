<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
$data = array();
$select_fund_category = "select * from usaid_fund order by fund_name";
$result_fund_category = $mysqli->query($select_fund_category);
$i=0; 
while($fetch_fund_category = $result_fund_category->fetch_array())
{
     $data[$i]['id'] = $fetch_fund_category['id'];
     $data[$i]['fund_id'] = $fetch_fund_category['fund_id'];
     $data[$i]['fund_name'] = $fetch_fund_category['fund_name'];  
     $data[$i]['fund_category'] = $fetch_fund_category['fund_category'];
     $i++; 
}
if(count($data)>0){
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