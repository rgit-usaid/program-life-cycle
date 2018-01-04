<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

if(isset($_REQUEST['vendor_id']))
{
	$vendor_id = trim($_REQUEST['vendor_id']);
	$operating_unit_id = trim($_REQUEST['operating_unit_id']);
	if($vendor_id!='')
	{
		## API for get all project activity=======
		$url = GS_API_HOST_URL."get_all_award_instrument_number.php?vendor_id=".$vendor_id."&operating_unit_id=".$operating_unit_id."";
		$all_award_number_arr = requestByCURL($url);	
		?> 
		<select class="form-control award_number" name="award_instrument_no" onChange="getAwardNumber(this),getAwardFundStrip(this);";>
			<option value="">Select</option> 
			<?php
			for($j=0; $j<count($all_award_number_arr['data']); $j++)
			{
			?>	
				<option value="<?php echo $all_award_number_arr['data'][$j]['award_number'];?>" ><?php echo $all_award_number_arr['data'][$j]['award_number'];?></option>  
			<?php
			}
			?>	 
		</select>
			
	<?php
	} 
}
?>
