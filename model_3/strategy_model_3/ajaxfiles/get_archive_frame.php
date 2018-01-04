<?php
include('../config/config.inc.php');
if(isset($_REQUEST['archive_frame_id']))
{
	$AF_id = $_REQUEST['archive_frame_id'];
?>
	<iframe src="<?php echo HOST_URL;?>archive_view.php?archive_frame_id=<?php echo $AF_id;?>" width="100%" height="600"></iframe>
<?php }   ?>	