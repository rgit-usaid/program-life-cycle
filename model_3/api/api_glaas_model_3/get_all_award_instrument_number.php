<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all award instrument number============

if(isset($_REQUEST['vendor_id']))
{
    $cond = " a.id!='' ";
    if(isset($_REQUEST['operating_unit_id']))
    {
        $cond .= " and a.operating_unit_id = '".trim($_REQUEST['operating_unit_id'])."' ";
    }
    if(isset($_REQUEST['project_id']))
    {
        $cond .= " and al.link_to_id='".trim($_REQUEST['project_id'])."' ";
    } 
    if(isset($_REQUEST['vendor_id']))
    {
        $cond .= " and a.vendor_id='".trim($_REQUEST['vendor_id'])."' ";
    }
    $data = array();
    $select_data = "select a.award_number,a.award_name,a.employee_id  
                    from usaid_requisition_award as a 
                    left join usaid_association_link as al ON al.associate_from_number = a.award_number
                    where  ".$cond." group by a.award_number";
    $result_data = $mysqli->query($select_data);

    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
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