<?php
function requestByCURL($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);                               
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);
	$data_arr = json_decode($output,true);
	return $data_arr;
}

function dateFormat($date)
{
	$date_formated = '';
   	if($date!='')
   	{
     	$date_formated = date('Y-m-d',strtotime($date));
   	}
   	return $date_formated; 
}

function dateTimeFormat($date)
{
	$date_formated = '';
   	if($date!='')
   	{
     	$date_formated = date('Y-m-d H:i',strtotime($date));
   	}
   	return $date_formated; 
}


## get closing amount by api===================
function getClosingBalance($ledger_type_id,$be_year,$ed_year,$fund_id,$ledger_type,$pe_id='',$fund_status='')
{
	global $mysqli;
	$ledger_type = str_replace(' ', '_', $ledger_type);
	$data = array();				 
	$url = API_HOST_URL."get_openning_closing_balance.php?ledger_type_id=".$ledger_type_id."&b_year=".$be_year."&e_year=".$ed_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type=".$ledger_type."&fund_status=".$fund_status.""; 
	$closing_balance_arr = requestByCURL($url);
	$data['opening_balance'] = $closing_balance_arr['data']['opening_balance'];
	$data['closing_balance'] = $closing_balance_arr['data']['closing_balance'];
	return $data;
}

## get total alloted to fs+pe===================
function getTotalAllotedToPE($ledger_type_id,$be_year,$ed_year,$fund_id='',$ledger_type='',$pe_id='',$fund_status='')
{
	global $mysqli;
	$data = array();				 
	$url = API_HOST_URL."get_all_fund_time_transaction_alloted_fs_pe_count.php?ledger_type_id=".$ledger_type_id."&b_year=".$be_year."&e_year=".$ed_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type=".$ledger_type."&fund_status=".$fund_status.""; 
	$alloted_balance_arr = requestByCURL($url);
	$data['total_debit_amount'] = $alloted_balance_arr['data'][0]['total_debit_amount']; 
	return $data;
}


## get total alloted to bureau===================
function getTotalAllotedToBureau($ledger_type_id,$be_year,$ed_year,$fund_id='',$ledger_type='',$pe_id='',$fund_status='')
{
	global $mysqli;
	$data = array();				 
	$url = API_HOST_URL."get_all_fund_time_transaction_alloted_bureau_count.php?ledger_type_id=".$ledger_type_id."&b_year=".$be_year."&e_year=".$ed_year."";
	$alloted_balance_arr = requestByCURL($url);
	$data['total_debit_amount'] = $alloted_balance_arr['data'][0]['total_debit_amount']; 
	return $data;
}

## get total alloted to bureau===================
function getTotalAllotedToOU($ledger_type_id,$be_year,$ed_year,$fund_id='',$ledger_type='',$pe_id='')
{
	global $mysqli;
	$data = array();				 
	$url = API_HOST_URL."get_all_bureau_transaction_alloted_ou_count.php?ledger_type_id=".$ledger_type_id."&b_year=".$be_year."&e_year=".$ed_year."&fund_id=".$fund_id."&pe_id=".$pe_id."";
	$alloted_balance_arr = requestByCURL($url);
	$data['total_debit_amount'] = $alloted_balance_arr['data'][0]['total_debit_amount']; 
	return $data;
}

## get total Commited By OU==================
function getTotalCommitedTOOU($ledger_type_id,$be_year,$ed_year,$fund_id,$ledger_type,$pe_id='',$fund_status='')
{
	global $mysqli;
	$data = array();				 
	$url = API_HOST_URL."get_all_transaction_commited_to_ou_count.php?ledger_type_id=".$ledger_type_id."&b_year=".$be_year."&e_year=".$ed_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type=".$ledger_type."&fund_status=Commit"; 
	$alloted_balance_arr = requestByCURL($url);
	$data['total_debit_amount'] = $alloted_balance_arr['data'][0]['total_debit_amount']; 
	return $data;
}

## get total Obligate By OU==================
function getTotalObligateTOOU($ledger_type_id,$be_year,$ed_year,$fund_id,$ledger_type,$pe_id='',$fund_status='')
{
	global $mysqli;
	$data = array();				 
	$url = API_HOST_URL."get_all_transaction_obligate_to_ou_count.php?ledger_type_id=".$ledger_type_id."&b_year=".$be_year."&e_year=".$ed_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type=".$ledger_type."&fund_status=Obligate"; 
	$alloted_balance_arr = requestByCURL($url);
	$data['total_debit_amount'] = $alloted_balance_arr['data'][0]['total_debit_amount']; 
	return $data;
}

## get total Obligated ==================
function getTotalObligated($ledger_type_id,$be_year,$ed_year,$fund_id,$ledger_type,$pe_id='',$fund_status='')
{
	global $mysqli;
	$data = array();				 
	$url = API_HOST_URL."get_all_transaction_obligated_by_commited_count.php?ledger_type_id=".$ledger_type_id."&b_year=".$be_year."&e_year=".$ed_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type=".$ledger_type."&fund_status=Obligate"; 
	$alloted_balance_arr = requestByCURL($url);
	$data['total_debit_amount'] = $alloted_balance_arr['data'][0]['total_debit_amount']; 
	return $data;
}
## get total UnObligated ==================
function getTotalUnObligated($ledger_type_id,$be_year,$ed_year,$fund_id,$ledger_type,$pe_id='',$fund_status='')
{
	global $mysqli;
	$data = array();				 
	$url = API_HOST_URL."get_all_transaction_unobligated_by_commited_count.php?ledger_type_id=".$ledger_type_id."&b_year=".$be_year."&e_year=".$ed_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type=".$ledger_type.""; 
	$alloted_balance_arr = requestByCURL($url);
	$data['total_debit_amount'] = $alloted_balance_arr['data'][0]['total_debit_amount']; 
	return $data;
}

## get total Invoice Paid==================
function getTotalInvoicePaid($ledger_type_id,$be_year,$ed_year,$fund_id,$ledger_type,$pe_id='',$fund_status='')
{
	global $mysqli;
	$data = array();				 
	$url = API_HOST_URL."get_all_transaction_obligated_to_account_payable_count.php?ledger_type_id=".$ledger_type_id."&b_year=".$be_year."&e_year=".$ed_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type=".$ledger_type.""; 
	$alloted_balance_arr = requestByCURL($url);
	$data['total_amount'] = $alloted_balance_arr['data'][0]['total_amount']; 
	return $data;
}


## function for amount to remove comma and dollar sign============
function getNumericAmount($amount)
{
	$nu_amount = str_replace('$', '', $amount);
	$num_amount = str_replace(',', '', $nu_amount);
	return $num_amount;
}

## ============
function getInvoiceYearByDate($date)
{
	$month_no = date("m", strtotime($date));
	$year = date('Y',strtotime($date)); // get invoice year from invoice date	
	if($month_no>9)
	{
		$year = $year+1;
	}
	return $year;
}

### fundtion for sorting multidimesion array
function array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;

}
?>