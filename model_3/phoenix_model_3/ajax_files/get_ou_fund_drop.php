<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

### get OU drop down with fund  ===========
if(isset($_REQUEST['operating_unit_id']))
{
	$operating_unit_id = trim($_REQUEST['operating_unit_id']);
	$url = API_HOST_URL."get_ou_fund_unique.php?operating_unit_id=".$operating_unit_id."";
	$unique_fund_arr = requestByCURL($url);
	 
	?>
	<select name="ou_year" class="form-control ou_year" onchange="getcurrentFiscalyear();">
		<option value="">Select</option>
	<?php
	for($i=0; $i<count($unique_fund_arr['data']); $i++)
	{
		$unique_total_fund_arr = getClosingBalance($operating_unit_id,$unique_fund_arr['data'][$i]['fund_beginning_fiscal_year'],$unique_fund_arr['data'][$i]['fund_ending_fiscal_year'],$unique_fund_arr['data'][$i]['fund_id'],'Operating Unit',$unique_fund_arr['data'][$i]['program_element_id']);
		 
	?> 
		<option value="<?php echo $unique_fund_arr['data'][$i]['gp_year'].'>>'.$unique_total_fund_arr['closing_balance']; ?>"><?php echo $unique_fund_arr['data'][$i]['gp_year'];if($unique_total_fund_arr['closing_balance']!='')echo ' ($'.number_format($unique_total_fund_arr['closing_balance']).')'; ?></option>
	<?php
	}
	echo '</select>';
	 
}
?>
