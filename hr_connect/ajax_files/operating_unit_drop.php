<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

### add fund to bureau===========
if(isset($_REQUEST['keyword']))
{
	$name = trim($_REQUEST['keyword']);
	if($name!='')
	{
		$url = API_HOST_URL."get_all_operating_unit.php?name=".$name;
		$ou_arr = requestByCURL($url);
		?>
		<ul id="ou-list">
			<?php
			for($b_count=0; $b_count<count($ou_arr['data']); $b_count++)
			{ 
			?> 
			<li onClick="selectOU('<?php echo $ou_arr['data'][$b_count]['operating_unit_abbreviation']; ?>','<?php echo $ou_arr['data'][$b_count]['operating_unit_id']; ?>');"><?php echo $ou_arr['data'][$b_count]['operating_unit_abbreviation'].' ('.$ou_arr['data'][$b_count]['operating_unit_description'].')'; ?></li>
			<?php
			}
		echo '</ul>';
	}  
}
else
?>
