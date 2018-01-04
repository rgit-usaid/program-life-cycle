<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

if(isset($_REQUEST['award_number']))
{
	 $award_number = $_REQUEST['award_number']; 
	 $url = API_HOST_URL."get_all_sub_obligate_fund_unique.php?ledger_type_id=".$award_number.""; 
	 $unique_fund_arr = requestByCURL($url);
	if($award_number!='')
	{
		?> 
		<select class="form-control fund" name="fund[]" >
			<option value="">Select </option>
			<?php
			for($i=0; $i<count($unique_fund_arr['data']); $i++)
			{ 
				$unique_total_fund_arr = getClosingBalance($unique_fund_arr['data'][$i]['ledger_type_id'],$unique_fund_arr['data'][$i]['fund_beginning_fiscal_year'],$unique_fund_arr['data'][$i]['fund_ending_fiscal_year'],$unique_fund_arr['data'][$i]['fund_id'],'Award CLIN',$unique_fund_arr['data'][$i]['program_element_id'],$unique_fund_arr['data'][$i]['fund_status']);
			?>  
			<option value="<?php echo $unique_fund_arr['data'][$i]['narration'].' = '.$unique_fund_arr['data'][$i]['fund_status']; ?>"><?php echo $unique_fund_arr['data'][$i]['narration']; if($unique_total_fund_arr['closing_balance']!='')echo ' ( $'.$unique_total_fund_arr['closing_balance'].') '.$unique_fund_arr['data'][$i]['fund_status']; ?></option>
			<?php
			}
			?>	  
			
		</select> 
	<?php
	} 
}
?>
