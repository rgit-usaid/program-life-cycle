<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['activity_id']))
{
    $activity_id = trim($_REQUEST['activity_id']);

    $FY_year_arr= array();
    $fyear = array(); // year info forecaster data
    $fyear["byear"] = $fyear["eyear"] = $fyear["bmonth"] = $fyear["emonth"] = 0; 
    $start_fyear = $end_fyear = $start_fmonth = $end_fmonth = $start_fquarter = $end_fquarter = 0;
    $FY_finance_monthly = array();
    $FY_finance_quarterly = array();
    
    ### get all award of a activity==============
    $url = API_HOST_URL_GLAAS."get_award_by_activity.php?activity_id=".$activity_id."";
    $award_arr  = requestByCURL($url);
    ## loop for get only award number in array==============
    $award_number_arr = array();
    for($j=0; $j<count($award_arr['data']); $j++)
    {
        $award_number_arr[$j] = $award_arr['data'][$j]['award_number'];
    } 
    $data_award = urlencode(serialize($award_number_arr));

    ### get obligated fund =============
    $url = API_HOST_URL_PHOENIX."get_fund_by_awards.php?data_award=".$data_award."&fund_status=Obligate&fund_status2=Subobligate";  
    $award_ob_fund  = requestByCURL($url);

    ## get committed fund=============
    $url = API_HOST_URL_PHOENIX."get_fund_by_awards.php?data_award=".$data_award."&fund_status=Commit";  
    $activity_cm_fund  = requestByCURL($url);

    ### get account payable fund=========
    $url = API_HOST_URL_PHOENIX."get_all_account_payable_by_award.php?data_award=".$data_award."";  
    $activity_payable_fund  = requestByCURL($url);
    
    ### fill obligate data ============
    for($i=0; $i<count($award_ob_fund['data']);$i++){
        $byear = $award_ob_fund['data'][$i]['transaction_year'];
        $eyear = $byear+1;
        
        if($byear>0){
            $disp_label = $byear.'-'.substr($eyear,-2,2);
            $FY_year_arr[$byear]['fiscal_year']= $disp_label;
            $FY_year_arr[$byear]['b_year']= $byear;
            $FY_year_arr[$byear]['obligated']=$FY_year_arr[$byear]['obligated'] + $award_ob_fund['data'][$i]['credit_amount'];
            $FY_year_arr[$byear]['committed']= 0;
            $FY_year_arr[$byear]['spend']= 0;
            $FY_year_arr[$byear]['forecast']= 0;
        }
    }
    ## fill committed data==========
    for($i=0; $i<count($activity_cm_fund['data']);$i++){
        $byear = $activity_cm_fund['data'][$i]['transaction_year'];
        $eyear = $byear+1;
        if($byear>0){
            $disp_label = $byear.'-'.substr($eyear,-2,2);
            $FY_year_arr[$byear]['fiscal_year']= $disp_label;
            $FY_year_arr[$byear]['b_year']= $byear;
            $FY_year_arr[$byear]['committed']= $FY_year_arr[$byear]['committed'] + $activity_cm_fund['data'][$i]['credit_amount'];
            if(!array_key_exists('obligated',$FY_year_arr[$byear])){
                $FY_year_arr[$byear]['obligated']= 0;
            }
            $FY_year_arr[$byear]['forecast']= 0;
        }
    }

    ##==fill spend data===========
    for($i=0; $i<count($activity_payable_fund['data']);$i++)
    {
        $invoice_date = date('Y-m-d',strtotime($activity_payable_fund['data'][$i]['invoice_date']."+25 days"));
        $actual_year = $byear = date('Y',strtotime($invoice_date));
        $month = date('m',strtotime($invoice_date));
        $quarter = get_quarter($month);
        if($month<10){
            $byear = date('Y',strtotime($invoice_date))-1;
        }
        
        if($byear>0){
            $eyear = $byear+1;
            $disp_label = $byear.'-'.substr($eyear,-2,2);
            $FY_year_arr[$byear]['fiscal_year']= $disp_label;
            $FY_year_arr[$byear]['b_year']= $byear;
            if(!array_key_exists('obligated',$FY_year_arr[$byear])){
                $FY_year_arr[$byear]['spend']= $activity_payable_fund['data'][$i]['total_paid_amount'];
            }
            else{
                $FY_year_arr[$byear]['spend']= $activity_payable_fund['data'][$i]['total_paid_amount'] + $FY_year_arr[$byear]['spend'];
            }
            
            if(!array_key_exists('obligated',$FY_year_arr[$byear])){
                $FY_year_arr[$byear]['obligated']= 0;
            }
            
            if(!array_key_exists('committed',$FY_year_arr[$byear]) || !array_key_exists('obligated',$FY_year_arr[$byear])){
                $FY_year_arr[$byear]['committed']= 0;
            }
            $FY_year_arr[$byear]['forecast']= 0;
            
            /*fill spend array for monthly data*/
            $disp_arr_label = $actual_year.'-'.$month;
            if(!array_key_exists($disp_arr_label,$FY_finance_monthly)){
                $FY_finance_monthly[$disp_arr_label]['spend'] = $activity_payable_fund['data'][$i]['total_paid_amount'];
            }
            else{
                $FY_finance_monthly[$disp_arr_label]['spend'] = $FY_finance_monthly[$disp_arr_label]['spend'] + $activity_payable_fund['data'][$i]['total_paid_amount'];
            }
            
            /*fill spend array for quarterly data*/
            if($month>=10)
                   $disp_arr_qlabel = $actual_year.'-'.$quarter;
                 else
                   $disp_arr_qlabel = ($actual_year-1).'-'.$quarter;
            if(!array_key_exists($disp_arr_qlabel,$FY_finance_quarterly)){
                $FY_finance_quarterly[$disp_arr_qlabel]['spend'] = $activity_payable_fund['data'][$i]['total_paid_amount'];
            }
            else{
                $FY_finance_quarterly[$disp_arr_qlabel]['spend'] = $FY_finance_quarterly[$disp_arr_qlabel]['spend'] + $activity_payable_fund['data'][$i]['total_paid_amount'];
            }
        }
    }

    /*====select forecaster data for this activity===*/
    $url = API_HOST_URL_PROJECT."get_project_activity_finance.php?activity_id=".$activity_id."&type=Forecaster";  
    $activity_forecaster_finance  = requestByCURL($url);
    for($i=0;$i<count($activity_forecaster_finance['data']);$i++){
        $actual_year = $byear =  $activity_forecaster_finance['data'][$i]['finance_year'];
        $month = $activity_forecaster_finance['data'][$i]['finance_month'];
        $quarter = get_quarter($month);
        if($month<10){
            $month= "0".$month; 
            }
        if($activity_forecaster_finance['data'][$i]['finance_month']<10){
            $byear = $activity_forecaster_finance['data'][$i]['finance_year']-1;        
        }

        if(array_key_exists($byear,$FY_year_arr)){
            $FY_year_arr[$byear]['forecast'] = $FY_year_arr[$byear]['forecast'] + $activity_forecaster_finance['data'][$i]['total_amount'];
        }
        
        /*fill spend array for monthly data*/
        $disp_arr_label = $actual_year.'-'.$month;
        
        if(!array_key_exists($disp_arr_label,$FY_finance_monthly)){
            $FY_finance_monthly[$disp_arr_label]['forecaster'] = $activity_forecaster_finance['data'][$i]['total_amount'];
        }
        else{
            $FY_finance_monthly[$disp_arr_label]['forecaster'] = $FY_finance_monthly[$disp_arr_label]['forecaster'] + $activity_forecaster_finance['data'][$i]['total_amount'];
        }
        
        /*fill spend array for quarterly data*/
        if($month>=10)
                   $disp_arr_qlabel = $actual_year.'-'.$quarter;
                 else
                   $disp_arr_qlabel = ($actual_year-1).'-'.$quarter;
        if(!array_key_exists($disp_arr_qlabel,$FY_finance_quarterly)){
            $FY_finance_quarterly[$disp_arr_qlabel]['forecaster'] = $activity_forecaster_finance['data'][$i]['total_amount'];
        }
        else{
            $FY_finance_quarterly[$disp_arr_qlabel]['forecaster'] = $FY_finance_quarterly[$disp_arr_qlabel]['forecaster'] + $activity_forecaster_finance['data'][$i]['total_amount'];
        }
    }

    ###====select planner data for this activity===*/
    $url = API_HOST_URL_PROJECT."get_project_activity_finance.php?activity_id=".$activity_id."&type=Planner";  
    $activity_forecaster_finance  = requestByCURL($url);
    for($i=0;$i<count($activity_forecaster_finance['data']);$i++){
        $actual_year = $byear =  $activity_forecaster_finance['data'][$i]['finance_year'];
        $month = $activity_forecaster_finance['data'][$i]['finance_month'];
        $quarter = get_quarter($month);
        if($month<10){
            $month= "0".$month; 
            }
        if($activity_forecaster_finance['data'][$i]['finance_month']<10){
            $byear = $activity_forecaster_finance['data'][$i]['finance_year']-1;        
        }

        if(array_key_exists($byear,$FY_year_arr)){
            $FY_year_arr[$byear]['planner'] = $FY_year_arr[$byear]['planner'] + $activity_forecaster_finance['data'][$i]['total_amount'];
        }
        
        /*fill spend array for monthly data*/
        $disp_arr_label = $actual_year.'-'.$month;
        if(!array_key_exists($disp_arr_label,$FY_finance_monthly)){
            $FY_finance_monthly[$disp_arr_label]['planner'] = $activity_forecaster_finance['data'][$i]['total_amount'];
        }
        else{
            $FY_finance_monthly[$disp_arr_label]['planner'] = $FY_finance_monthly[$disp_arr_label]['planner'] + $activity_forecaster_finance['data'][$i]['total_amount'];
        }
        
        /*fill spend array for quarterly data*/
        if($month>=10)
                   $disp_arr_qlabel = $actual_year.'-'.$quarter;
                 else
                   $disp_arr_qlabel = ($actual_year-1).'-'.$quarter;
        if(!array_key_exists($disp_arr_qlabel,$FY_finance_quarterly)){
            $FY_finance_quarterly[$disp_arr_qlabel]['planner'] = $activity_forecaster_finance['data'][$i]['total_amount'];
        }
        else{
            $FY_finance_quarterly[$disp_arr_qlabel]['planner'] = $FY_finance_quarterly[$disp_arr_qlabel]['planner'] + $activity_forecaster_finance['data'][$i]['total_amount'];
        }
    }
ksort($FY_finance_monthly);
ksort($FY_finance_quarterly);

$temp_month_arr = array_keys($FY_finance_monthly);
$temp_data = explode('-',$temp_month_arr[0]);
$start_fyear = $temp_data[0]; 
$start_fmonth = intval($temp_data[1]); 
$temp_data = explode('-',$temp_month_arr[count($temp_month_arr)-1]);
$end_fyear = $temp_data[0]; 
$end_fmonth = intval($temp_data[1]); 

$temp_quarter_arr = array_keys($FY_finance_quarterly);
$temp_data = explode('-',$temp_quarter_arr[0]);
$start_fquarter = $temp_data[1]; 
$temp_data = explode('-',$temp_quarter_arr[count($temp_quarter_arr)-1]);
$end_fquarter = $temp_data[1]; 

//*===fill month array data===*//
$tmp_sfm=0;$tmp_efm=0; //temp_sfm is temp_start-fiscal-month 
if($start_fyear>0 && $end_fyear>0 && $tmp_sfm>=0 && $tmp_efm>=0){
    for($i=$start_fyear;$i<=$end_fyear;$i++){
        if($i==$start_fyear && $i!=$end_fyear)
         {
            $tmp_sfm=10;
            $tmp_efm=12;
         }
         else if($i==$start_fyear && $i==$end_fyear)
         {
           $tmp_sfm = $start_fmonth;
           $tmp_efm = 9;    
         }
         else if($i>$start_fyear && $i<=$end_fyear)
         {
            $tmp_sfm=1;
            if($i==$end_fyear)
                $tmp_efm = 9;
            else
                $tmp_efm = 12;
         }
        for($j=$tmp_sfm;$j<=$tmp_efm;$j++){
        if($j<10){
                $disp_label= $i."-0".$j;
            }
            else{
                $disp_label= $i.'-'.$j; 
            } 
        $quarter = get_quarter($j); 
        if($j>=10)
          $disp_qlabel = $i.'-'.$quarter; 
        else
            $disp_qlabel = ($i-1).'-'.$quarter; 
            
            //if no data found for this month year in this forecaster than set 0//
            
                if(!array_key_exists('spend',$FY_finance_monthly[$disp_label])){
                    $FY_finance_monthly[$disp_label]['spend']= 0;   
                }
                
                if(!array_key_exists('forecaster',$FY_finance_monthly[$disp_label])){
                    $FY_finance_monthly[$disp_label]['forecaster']= 0;  
                }
                
                if(!array_key_exists('planner',$FY_finance_monthly[$disp_label])){
                    $FY_finance_monthly[$disp_label]['planner']= 0; 
                }
            
            //if no data found for this quarter year in this forecaster than set 0//
            
                if(!array_key_exists('spend',$FY_finance_quarterly[$disp_qlabel])){
                    $FY_finance_quarterly[$disp_qlabel]['spend']= 0;    
                }
                
                if(!array_key_exists('forecaster',$FY_finance_quarterly[$disp_qlabel])){
                    $FY_finance_quarterly[$disp_qlabel]['forecaster']= 0;   
                }
                
                if(!array_key_exists('planner',$FY_finance_quarterly[$disp_qlabel])){
                    $FY_finance_quarterly[$disp_qlabel]['planner']= 0;  
                }
        }
    }
}
//** sort forecaster array by month and year
ksort($FY_finance_monthly);
ksort($FY_finance_quarterly);

/*===sort finance array by year===*/
ksort($FY_year_arr);
$month = date('m');
if($month<10){
    $current_year_index = (date('Y')-1); 
}
else{
    $current_year_index = date('Y'); 
}

if(!array_key_exists($current_year_index,$FY_year_arr)){
    $current_year_index = key($FY_year_arr);
}

$finance_arr = $FY_year_arr; 
$lifetime_budget=0;
$lifetime_actual_spend=0; 
$lifetime_forecast=0;

## get tatal count of spent , budget,forecast===================
$lifetime_budget = array_sum(array_map(create_function('$finance_arr', 'return $finance_arr["obligated"];'), $FY_year_arr));
$lifetime_actual_spend = array_sum(array_map(create_function('$finance_arr', 'return $finance_arr["spend"];'), $FY_year_arr));
$lifetime_forecast = array_sum(array_map(create_function('$finance_arr', 'return $finance_arr["forecast"];'), $FY_year_arr));

 ## start logical data to mobile app=========================

    $data = array(); 
    $i=0; 
    $data[$i]['activity_id'] = $activity_id;
    $data[$i]['lifetime_budget'] = $lifetime_budget;
    $data[$i]['lifetime_actual_spend'] = $lifetime_actual_spend;
    $data[$i]['lifetime_forecast'] = $lifetime_forecast; 
   
    ### array for month data according to mobile==============
    $i=0;
    foreach ($FY_finance_monthly as $key_month => $finance_monthly_value) 
    { 
       $period = date('M Y',strtotime($key_month));
        ### inner array for spent===============
        $data[0]['monthly'][0]['spent'][$i]['period'] = $period;
        $data[0]['monthly'][0]['spent'][$i]['value'] = $finance_monthly_value['spend'];
        $data[0]['monthly'][0]['forecast'][$i]['period'] = $period;
        $data[0]['monthly'][0]['forecast'][$i]['value'] = $finance_monthly_value['forecaster'];
        $data[0]['monthly'][0]['budget'][$i]['period'] = $period;
        $data[0]['monthly'][0]['budget'][$i]['value'] = $finance_monthly_value['planner'];
        $i++;
    }
    ### array for quarterly data according to mobile==============
    $j=0;
    foreach ($FY_finance_quarterly as $key_quarter => $finance_quarterly_value) 
    { 
       $key_year_arr = explode('-',$key_quarter);
       $quarter = $key_year_arr[1];
       $period_year = date('y',strtotime($key_quarter));
       $period = 'Q'.$quarter.' '.$period_year;
        ### inner array for spent===============
        $data[0]['quarterly'][0]['spent'][$j]['period'] = $period;
        $data[0]['quarterly'][0]['spent'][$j]['value'] = $finance_quarterly_value['spend'];
        $data[0]['quarterly'][0]['forecast'][$j]['period'] = $period;
        $data[0]['quarterly'][0]['forecast'][$j]['value'] = $finance_quarterly_value['forecaster'];
        $data[0]['quarterly'][0]['budget'][$j]['period'] = $period;
        $data[0]['quarterly'][0]['budget'][$j]['value'] = $finance_quarterly_value['planner'];
        $j++;
    }
    ### array for year list with obligate ,Committed and available==============
    $k=0;
    foreach ($FY_year_arr as $key_year => $finance_year_value) 
    { 
        $b_year = $finance_year_value['b_year'];
        $current_fy_flag='no';
        if($b_year== date('Y')){$current_fy_flag='yes';}

        $data[0]['funds'][$k]['year']  = $finance_year_value['fiscal_year'];
        $data[0]['funds'][$k]['committed']  = $finance_year_value['committed'];
        $data[0]['funds'][$k]['actual_spent']  = $finance_year_value['spend'];
        $data[0]['funds'][$k]['obligated']  = $finance_year_value['obligated'];
        $data[0]['funds'][$k]['forecast']  = $finance_year_value['forecast'];
        $data[0]['funds'][$k]['available']  = $finance_year_value['obligated'] - $finance_year_value['spend'];
        $data[0]['funds'][$k]['current_fy_flag'] = $current_fy_flag;       
        $k++;
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