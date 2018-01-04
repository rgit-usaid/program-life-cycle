<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

if(isset($_REQUEST['award_number']))
{	
	$award_number = $_REQUEST['award_number']; 
	if($award_number!='')
		{
			## API for get all project activity=======
			$url = GS_API_HOST_URL."get_all_award_clin_by_award_number.php?award_number=".$award_number.""; 
			$all_clin_arr = requestByCURL($url);	
			?> 
			<select class="form-control clin_number" name="clin_number[]" >
			
				<option value="">Select Clin</option>
				<?php
				for($j=0; $j<count($all_clin_arr['data']); $j++)
				{
				?>	
					<option value="<?php echo $all_clin_arr['data'][$j]['clin_number'];?>"><?php echo $all_clin_arr['data'][$j]['clin_name'];?></option>  
				<?php
				}
				?>	
			</select> 
		<?php
		} 
}
?>
