<?php
include('config/config.inc.php');
include('include/function.inc.php'); 

if(isset($_REQUEST['project_id']))
{
	$project_id=$_REQUEST['project_id'];
}

$url=API_HOST_URL_PROJECT."get_all_project_activity.php?project_id=".$project_id;  
$all_project_activity = requestByCURL($url); 

if($all_project_activity['data']!='') {
?>
			<select name="actovity_id">
					<?php
					for($j=0; $j<count($all_project_activity['data']); $j++){ ?>	
					<option value="<?php echo $all_project_activity['data'][$j]['activity_id']?>" ><?php echo $all_project_activity['data'][$j]['title'];?></option>
					<?php } ?>
			</select>
<?php } ?>			