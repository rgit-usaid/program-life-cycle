<?php
include('../config/config.inc.php');
include('../include/function.inc.php');
### get all project of this operating unit ===========
if(isset($_REQUEST['operating_unit_id']))
{
	$operating_unit_id = trim($_REQUEST['operating_unit_id']); 
	$url = AMP_API_HOST_URL."get_all_project_by_ou_id.php?operating_unit_id=".$operating_unit_id."";
	$all_project_arr = requestByCURL($url);
	
	 ?>
		<select class="form-control project_id" name="project_id" onChange="getProjectId(this)"; required>
			<option value="">Select</option>
		<?php	
			for($j=0; $j<count($all_project_arr['data']); $j++)
			{
			?>	
				<option value="<?php echo $all_project_arr['data'][$j]['project_id'];?>"><?php echo $all_project_arr['data'][$j]['title'].' ('.$all_project_arr['data'][$j]['project_id'].')';?></option>
			<?php
			}
		?>
		</select>
<?php
}
?> 
