<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

### add fund to bureau===========
if(isset($_REQUEST['bureau_id']))
{
	$bureau_id = trim($_REQUEST['bureau_id']);
	$url = API_HOST_URL."get_bureau_fund_unique.php?operating_unit_id=".$bureau_id."";
	$unique_fund_arr = requestByCURL($url);
	?>
	<select name="bureau_year" class="form-control">
		<option value="">Select</option>
	<?php
	for($i=0; $i<count($unique_fund_arr['data']); $i++)
	{
		$closing_balance_arr =getClosingBalance($bureau_id,$unique_fund_arr['data'][$i]['fund_beginning_fiscal_year'],$unique_fund_arr['data'][$i]['fund_ending_fiscal_year'],$unique_fund_arr['data'][$i]['fund_id'],'Operating Unit',$unique_fund_arr['data'][$i]['program_element_id']); 
	?> 
		<option value="<?php echo $unique_fund_arr['data'][$i]['gp_year'].'>>'.$closing_balance_arr['closing_balance']; ?>"><?php echo $unique_fund_arr['data'][$i]['gp_year'];if($closing_balance_arr['closing_balance']!='')echo ' ($'.number_format($closing_balance_arr['closing_balance']).')'; ?></option>
	<?php
	}
	echo '</select>';  
}
?>
