<?php
include('../config/config.inc.php');
include('../include/function.inc.php');
### get OU drop down with fund  ===========
if(isset($_REQUEST['commit_type']))
{
	$commit_type = trim($_REQUEST['commit_type']); 
	$operating_unit_id = trim($_REQUEST['operating_unit_id']); 

	## code for get all project drop down if select type project============
	if($commit_type=='Project')
	{ 
		$url = AMP_API_HOST_URL."get_all_project_by_ou_id.php?operating_unit_id=".$operating_unit_id.""; // get all project
		//$url = AMP_API_HOST_URL."get_all_project.php"; // get all project
		$unique_fund_arr = requestByCURL($url);
		?>
		<div class="form-group">
			<label class="col-md-4" for="project_id">Project:</label>
			<div class="col-md-8">
			<select class="form-control" name="commit_in_id">
				<option>Select</option>
			<?php
			for($i=0; $i<count($unique_fund_arr['data']); $i++)
			{
			?>	
				<option value="<?php echo $unique_fund_arr['data'][$i]['project_id'];?>"><?php echo $unique_fund_arr['data'][$i]['project_id'].' ('.$unique_fund_arr['data'][$i]['title'].')';?></option> 
			<?php
			}
			?>
			</select>	
			</div> 
		</div>
	<?php
	}

	### code for get all project and select their activty ===========
	if($commit_type=='Project Activity')
	{ 
		$url = AMP_API_HOST_URL."get_all_project_by_ou_id.php?operating_unit_id=".$operating_unit_id.""; // get all project
		//$url = AMP_API_HOST_URL."get_all_project.php"; // get all project
		$unique_fund_arr = requestByCURL($url);
		?>
		<div class="form-group">
			<label class="col-md-4" for="project_id">Project:</label>
			<div class="col-md-8">
				<select class="form-control project_id_class" name="project_id" onchange="showProjectActivity();">
					<option>Select</option>
				<?php
				for($i=0; $i<count($unique_fund_arr['data']); $i++)
				{
				?>	
					<option value="<?php echo $unique_fund_arr['data'][$i]['project_id'];?>"><?php echo $unique_fund_arr['data'][$i]['title'].' ('.$unique_fund_arr['data'][$i]['project_id'].')';?></option> 
				<?php
				}
				?>
				</select>	
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4" for="commit_in_id">Activity :</label>
			<div class="col-md-8 show_activity">
				<select class="form-control" name="commit_in_id">
					<option>Select</option>
				</select>
			</div>
		</div>
	<?php
	}


	if($commit_type=='DOAG')
	{ ?>
	<div class="form-group">	
		<label class="col-md-4" for="DOAG ID">DOAG ID:</label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="commit_in_id" id="" placeholder="DOAG ID" value="">
		</div>
	</div>
	<?php
	}

	
	if($commit_type=='Award CLIN')
	{ 
	$url = GS_API_HOST_URL."get_all_award_clin_by_ou_id.php?ou_id=".$operating_unit_id.""; // get all Award exit;
	$unique_fund_arr = requestByCURL($url);

	?>
	<div class="form-group">
		<label class="col-md-4" for="Amount">Award CLIN ID :</label>
		<div class="col-md-8">
				<select class="form-control award_id_class" name="commit_in_id" >
					<option>Select</option>
				<?php
				for($i=0; $i<count($unique_fund_arr['data']); $i++)
				{
				?>	
					<option value="<?php echo $unique_fund_arr['data'][$i]['component_number'];?>"><?php echo $unique_fund_arr['data'][$i]['component_name'].'('. $unique_fund_arr['data'][$i]['component_number'].')';?></option> 
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
