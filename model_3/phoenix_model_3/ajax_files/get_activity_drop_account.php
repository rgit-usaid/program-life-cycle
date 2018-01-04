<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

if(isset($_REQUEST['project_id']))
{
	$project_id = $_REQUEST['project_id']; 
	if($project_id!='')
	{
		## API for get all project activity=======
		$url = AMP_API_HOST_URL."get_all_project_activity.php?project_id=".$project_id."";
		$all_project_activity_arr = requestByCURL($url);	
		?> 
		<select class="form-control" name="activity_id[]" onChange="getActivityId(this)"; required>
			<option value="">Select Activity</option>
			<?php
			for($j=0; $j<count($all_project_activity_arr['data']); $j++)
			{
			?>	
				<option value="<?php echo $all_project_activity_arr['data'][$j]['activity_id'];?>"><?php echo $all_project_activity_arr['data'][$j]['title'].' ('.$all_project_activity_arr['data'][$j]['activity_id'].')';?></option>  
			<?php
			}
			?>	 
		</select> 
	<?php
	} 
}
?>
