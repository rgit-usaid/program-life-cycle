<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

if(isset($_REQUEST['associate_type']))
{
	$associate_type = $_REQUEST['associate_type'];
	if($associate_type=='Project')
	{
		## API for get all project=======
		$url = AMP_API_HOST_URL."get_all_project.php";
		$all_project_arr = requestByCURL($url);	
		?>
		<label for="associate_id" class="col-md-6 control-label"> Project:</label>
		<div class="col-md-6"> 
			<select class="form-control" id="sel1" name="link_to_id[]">
				<option value="">Select Project</option>
				<?php
				for($j=0; $j<count($all_project_arr['data']); $j++)
				{
				?>	
					<option value="<?php echo $all_project_arr['data'][$j]['project_id'];?>" <?php if($_REQUEST['selected_val']!="" && $_REQUEST['selected_val']== $all_project_arr['data'][$j]['project_id']) { echo "selected"; }?>><?php echo $all_project_arr['data'][$j]['title'].' ('.$all_project_arr['data'][$j]['project_id'].')';?></option>  
				<?php
				}
				?>	 
			</select>							
		</div>
	<?php
	}
	elseif($associate_type=='Project Activity')
	{
		## API for get all project=======
		$url = AMP_API_HOST_URL."get_all_project.php";
		$all_project_arr = requestByCURL($url);	
		?>
		<label for="project_id" class="col-md-6 control-label"> Project:</label>
		<div class="col-md-6 link_to_act_blk"> 
			<select class="form-control project_id" id="sel1" name="project_id" onchange="showProjectActivity(this);">
				<option value="">Select Project</option>
				<?php $split_val="";
				for($j=0; $j<count($all_project_arr['data']); $j++)
				{
					$arr_val=explode("-",$_REQUEST['selected_val']);
					$split_val= $arr_val[0];
				?>	
					<option value="<?php echo $all_project_arr['data'][$j]['project_id'];?>" <?php if($split_val!="" && $split_val == $all_project_arr['data'][$j]['project_id']) { echo "selected"; }?> ><?php echo $all_project_arr['data'][$j]['title'].' ('.$all_project_arr['data'][$j]['project_id'].')';?></option>  
				<?php
				}
				?>	 
			</select>							
			<input type="hidden" class="sel_activity_id project_id"  value="<?php echo $_REQUEST['selected_val'];?>"/>
		</div>
		<?php if($_REQUEST['selected_val']!="") {?>
		<script>
			$('.link_to_act_blk .project_id').trigger('change');
		</script>
		<?php } else { ?>
		<script>
			$('.link_to_act_blk .sel_activity_id').trigger('change');
		</script>
		<?php } ?>
		<div style="clear:both; height:10px;"></div>
		
		<label for="associate_id" class="col-md-6 control-label"> Activity:</label>
		<div class="col-md-6 show_activity">
			<select class="form-control" id="sel1" name="link_to_id">
				<option value="">Select Activity</option>
			</select>  
		</div>
	<?php 
	}
	elseif($associate_type=='DOAG')
	{?>
		<label for="associate_id" class="col-md-6 control-label"> DOAG:</label>
		<div class="col-md-6"> 
			<select class="form-control" id="sel1" name="link_to_id[]">
				<option value="">Select DOAG</option>
				<option value="DOAG ID 1001" <?php if($_REQUEST['selected_val']!="" && $_REQUEST['selected_val']=="DOAG ID 1001") { echo "selected"; }?> >DOAG ID 1001</option>  
				<option value="DOAG ID 1002" <?php if($_REQUEST['selected_val']!="" && $_REQUEST['selected_val']=="DOAG ID 1002") { echo "selected"; }?>>DOAG ID 1002</option> 
				<option value="DOAG ID 1003" <?php if($_REQUEST['selected_val']!="" && $_REQUEST['selected_val']=="DOAG ID 1003") { echo "selected"; }?>>DOAG ID 1003</option>
			</select>							
		</div>
	<?php
	}
}
?>
