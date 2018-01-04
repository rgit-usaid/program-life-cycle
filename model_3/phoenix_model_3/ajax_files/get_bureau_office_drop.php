<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

### add fund to bureau===========
if(isset($_REQUEST['bureau_id']))
{
	$bureau_id = trim($_REQUEST['bureau_id']);
	$url = API_HOST_URL."get_bureau_offices.php?bureau_id=".$bureau_id;
	$bureau_office_arr = requestByCURL($url);
	?>
	<select name="operating_unit_id" class="form-control">
		<option value="">Select</option>
		<?php
		for($b_count=0; $b_count<count($bureau_office_arr['data']); $b_count++)
		{ 
		?> 
			<option value="<?php echo $bureau_office_arr['data'][$b_count]['operating_unit_id']; ?>"><?php echo $bureau_office_arr['data'][$b_count]['operating_unit_abbreviation'].' ('.$bureau_office_arr['data'][$b_count]['operating_unit_description'].')'; ?></option>
		<?php
		}
	echo '</select>';  
}
?>
