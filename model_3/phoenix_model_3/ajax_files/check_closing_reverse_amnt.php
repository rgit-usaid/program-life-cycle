<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

### add fund to bureau===========
if(isset($_REQUEST['debit_from_id']))
{
	$debit_from_id = trim($_REQUEST['debit_from_id']);
	$fund_id = trim($_REQUEST['fund_id']);
	$ledger_type = trim($_REQUEST['ledger_type']);
	$total_amount = trim($_REQUEST['total_amount']);
	$fund_beginning_fiscal_year = trim($_REQUEST['fund_beginning_fiscal_year']);
	$fund_ending_fiscal_year = trim($_REQUEST['fund_ending_fiscal_year']);
	$reverse_amount = trim($_REQUEST['reverse_amount']);
	$pe_id='';
if(isset($_REQUEST['pe_id']))
{
	$pe_id=$_REQUEST['pe_id'];
}
	$closing_balance_arr = getClosingBalance($debit_from_id,$fund_beginning_fiscal_year,$fund_ending_fiscal_year,$fund_id,$ledger_type,$pe_id);

	$closing_amnt = $closing_balance_arr['closing_balance'];
	 
	if($closing_amnt==0)
	{
		echo "Sorry you can not reverse due to insufficient balance";
	}
	elseif($reverse_amount>$closing_amnt)
	{
		echo "Sorry you can not reverse more than $".$closing_amnt.' available balance';
	}
}
?>
