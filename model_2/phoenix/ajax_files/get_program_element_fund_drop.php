<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

### add fund to bureau===========
if(isset($_REQUEST['pe_id']))
{
	$pe_id = trim($_REQUEST['pe_id']);
	$url = API_HOST_URL."get_program_element_fund_unique.php?ledger_type_id=".$pe_id."";
	$unique_fund_arr = requestByCURL($url);
	?>
	<select name="pe_fund" class="form-control">
		<option value="">Select</option>
	<?php
	for($i=0; $i<count($unique_fund_arr['data']); $i++)
	{
	$url = API_HOST_URL."get_total_period_fund.php?ledger_type_id=".$pe_id."&b_year=".$unique_fund_arr['data'][$i]['fund_beginning_fiscal_year']."&e_year=".$unique_fund_arr['data'][$i]['fund_ending_fiscal_year']."";

		$unique_total_fund_arr = requestByCURL($url);
	?> 
		<option value="<?php echo $unique_fund_arr['data'][$i]['gp_year']; ?>"><?php echo $unique_fund_arr['data'][$i]['gp_year'];if($unique_total_fund_arr['data']['closing_balance']!='')echo ' ($'.$unique_total_fund_arr['data']['closing_balance'].')'; ?></option>
	<?php
	}
	echo '</select>';  
}
?>
