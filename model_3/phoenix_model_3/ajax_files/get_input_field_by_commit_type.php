<?php
include('../config/config.inc.php');
include('../include/function.inc.php');
### get OU drop down with fund  ===========
if(isset($_REQUEST['commit_type']))
{
	$commit_type = trim($_REQUEST['commit_type']); 
	$operating_unit_id = trim($_REQUEST['operating_unit_id']); 
  
	if($commit_type=='DOAG')
	{
		$url = STRATEGY3_API_HOST_URL."get_doag_by_ou_id.php?ou_id=".$operating_unit_id.""; // get all DOAG by operating id;
		$doag_arr = requestByCURL($url);  
	?>
	<div class="form-group">	
		<label class="col-md-4" for="DOAG ID">DOAG :</label>
		<div class="col-md-8">
			<select class="form-control doag_id_class" name="commit_in_id" >
				<option>Select</option>
			<?php
			for($i=0; $i<count($doag_arr['data']); $i++)
			{
			?>	
				<option value="<?php echo $doag_arr['data'][$i]['id'];?>"><?php echo $doag_arr['data'][$i]['name'];?></option> 
			<?php
			}
			?>
			</select>	
		</div>
	</div>
	<?php
	} 
	
	if($commit_type=='Award CLIN')
	{ 
		$url = GS_API_HOST_URL."get_all_award_by_ou_id.php?ou_id=".$operating_unit_id.""; // get all Award by operating id;
		$unique_fund_arr = requestByCURL($url); 
	?>
	<div class="form-group">
		<label class="col-md-4" for="Amount">Award CLIN :</label>
		<div class="col-md-8">
				<select class="form-control award_id_class" name="commit_in_id" >
					<option>Select</option>
				<?php
				for($i=0; $i<count($unique_fund_arr['data']); $i++)
				{
				?>	
					<option value="<?php echo $unique_fund_arr['data'][$i]['award_number'];?>"><?php echo $unique_fund_arr['data'][$i]['award_name'].'('. $unique_fund_arr['data'][$i]['award_number'].')';?></option> 
				<?php
				}
				?>
				</select>	
			</div>
	
	</div>
	<?php
	}          
}
?>
