<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

if(isset($_REQUEST['activity_id']))
{
	 $activity_id = $_REQUEST['activity_id']; 
	 $url = API_HOST_URL."get_all_fund_strip_by_ledger_id.php?ledger_type_id=".$activity_id.""; 
	 $unique_fund_arr = requestByCURL($url);
	 //$unique_fund_arr = array_unique($unique_fund_arr);
	if($activity_id!='')
	{
		?> 
		<select class="form-control fund" name="fund[]" >
			<option value="">Select </option>
			<?php
			for($i=0; $i<count($unique_fund_arr['data']); $i++)
			{ 
				$unique_total_fund_arr = getClosingBalance($unique_fund_arr['data'][$i]['ledger_type_id'],$unique_fund_arr['data'][$i]['fund_beginning_fiscal_year'],$unique_fund_arr['data'][$i]['fund_ending_fiscal_year'],$unique_fund_arr['data'][$i]['fund_id'],'Project Activity',$unique_fund_arr['data'][$i]['program_element_id'],'Obligate');
			?>  
			<option value="<?php echo $unique_fund_arr['data'][$i]['narration'].' '.$unique_fund_arr['data'][$i]['fund_id'].' $'.$unique_total_fund_arr['closing_balance']; ?>"><?php echo $unique_fund_arr['data'][$i]['narration']; if($unique_total_fund_arr['closing_balance']!='')echo ' ( $'.$unique_total_fund_arr['closing_balance'].')'; ?></option>
			<?php
			}
			?>	  
			
		</select> 
	<?php
	} 
}
?>
