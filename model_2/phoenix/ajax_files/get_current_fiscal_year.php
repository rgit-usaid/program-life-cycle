<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

if(isset($_REQUEST['cur_year']))
{
	$cur_year = $_REQUEST['cur_year'];
	$year=explode(" ",$cur_year);
	$strip_arr = $year;
	$years=$year[2];
	$current_fiscal_year=explode("-",$years);
	$current_fiscal_beginning_year=$current_fiscal_year[0];
	$current_fiscal_ending_year=$current_fiscal_year[1];
	
	if($current_fiscal_beginning_year!='' and in_array('FY', $strip_arr))
	{
	?>
	<div class="form-group">
		<label class="col-md-4" for="Current Fiscal Year">Current Fiscal Year:</label>
		<div class="col-md-8">
			<select  class="form-control" name="current_fiscal_year">
			<option value="">Select</option>
			<?php
			for($i=$current_fiscal_beginning_year;$i<=$current_fiscal_ending_year;$i++)
			{?>
			<option value="<?php echo $i;?>"><?php echo $i;?></option>
			<?php } ?>
			</select>
		</div>
	</div>	
<?php }   
	else 
	{ ?>
		<div class="form-group">
			<label class="col-md-4" for="Current Fiscal Year">Current Fiscal Year:</label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="current_fiscal_year" placeholder="Current Fiscal Year" value="">
			</div>
		</div>	
<?php } 
} ?>
