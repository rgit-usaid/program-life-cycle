<?php
include('../config/config.inc.php');
include('../include/function.inc.php');
### get all project of this operating unit ===========
if(isset($_REQUEST['operating_unit_id']))
{
	$operating_unit_id = trim($_REQUEST['operating_unit_id']); 
	$url = AMP_API_HOST_URL."get_all_project_by_ou_id.php?operating_unit_id=".$operating_unit_id."";
	$project_arr = requestByCURL($url);
	 ?>
	<div class="form-group">	
		<label class="col-md-4" for="obligate_in_id">Activity:</label>
		<div class="col-md-8">
			<select class="form-control " name="obligate_in_id">
				<option value="">Select</option>
			<?php
			for($i=0; $i<count($project_arr['data']); $i++)
			{
				$url = AMP_API_HOST_URL."get_all_project_activity.php?project_id=".$project_arr['data'][$i]['project_id']."";
				$project_activity_arr = requestByCURL($url);
				for($j=0; $j<count($project_activity_arr['data']); $j++)
				{
				?>	
					<option value="<?php echo $project_activity_arr['data'][$j]['activity_id'];?>"><?php echo $project_activity_arr['data'][$j]['title'].' ('.$project_activity_arr['data'][$j]['activity_id'].')';?></option>
				<?php
				}
			}
			?>
			</select>
		</div>
	</div>
<?php
}
?> 
