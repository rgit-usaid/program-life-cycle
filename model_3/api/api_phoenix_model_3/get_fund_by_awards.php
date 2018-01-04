<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['fund_status']) and $_REQUEST['fund_status']!='')
{   
    $data_award = $str = str_replace('\\', '', $_REQUEST['data_award']);
    $award_arr = unserialize(urldecode($data_award));
    $ledger_type_id_str = '';
    for($k=0; $k<count($award_arr); $k++)
    {
        $ledger_type_id_str .= "'".$award_arr[$k]."',"; // all award       
    }
    $ledger_type_id_str = substr($ledger_type_id_str, 0,-1);
    $data = array();
    $ledger_type_id = trim($_REQUEST['ledger_type_id']);
	$fund_status = trim($_REQUEST['fund_status']);
	
    $cond =  " and td.fund_status='".$fund_status."' ";
    ### condition for subobligate==========
    if(isset($_REQUEST['fund_status2']))
    {
        $cond =" and (td.fund_status='".$fund_status."' or td.fund_status='".$_REQUEST['fund_status2']."') ";
    }

   $select_fund_transaction = "select td.transaction_year, sum(td.credit_amount) as credit_amount
                        from usaid_fund_transaction_detail as td
                        left join usaid_fund_transaction as t ON td.transaction_id = t.id
                        where td.ledger_type='Award CLIN' ".$cond." and td.transaction_year is not null and td.credit_amount is not null and td.ledger_type_id in(".$ledger_type_id_str.") group by td.ledger_type_id, td.transaction_year, td.fund_status";

    $result_fund_transaction = $mysqli->query($select_fund_transaction) or die('Error'. $mysqli->error);
    $i=0; 
    while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
    {
         $data[$i]['credit_amount'] = $fetch_fund_transaction['credit_amount'];
		 $data[$i]['transaction_year'] = $fetch_fund_transaction['transaction_year'];	
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
}
 
?>