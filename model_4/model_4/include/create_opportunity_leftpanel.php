<?php
$cao_sel = $por_sel = $dop_sel = $mac_sel = ""; 
if(strpos($_SERVER['REQUEST_URI'],'create_an_opportunity')==true){
	$cao_sel="select";
}

if(strpos($_SERVER['REQUEST_URI'],'program_office_review')==true){
	$cao_sel="select";
	$por_sel="select";
}

if(strpos($_SERVER['REQUEST_URI'],'decision_to_proceed')==true){
	$cao_sel="select";
	$por_sel="select";
	$dop_sel="select";
}

if(strpos($_SERVER['REQUEST_URI'],'manage_activity')==true){
	$cao_sel="select";
	$por_sel="select";
	$dop_sel="select";
	$mac_sel = "select";	
}

?>
<ul id="left-menu" style="padding-left: 0;">
	<li class="left-menu-header"><span >Create an Opportunity</span></li>
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'create_an_opportunity')==true){?>active-left-menu<?php }?> <?php if($cao_sel!="select"){?>disable-link<?php }?>" ><a <?php if($cao_sel=="select"){?>href="create_an_opportunity.php" <?php }?>><img src="img/skyblue.png" class="img-responsive" /><p>Enter Data</p></a></li>
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'program_office_review')==true){?>active-left-menu<?php }?> <?php if($por_sel!="select"){?>disable-link<?php }?>"><a <?php if($por_sel=="select"){?> href="program_office_review.php" <?php }?>><img src="img/red.png" class="img-responsive" /><p>Program Office Review </p></a></li>
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'decision_to_proceed')==true){?>active-left-menu<?php }?> <?php if($dop_sel!="select"){?>disable-link<?php }?>"><a  <?php if($dop_sel=="select"){?> href="decision_to_proceed.php" <?php }?>><img src="img/orange.png" class="img-responsive" /><p>Decision to proceed</p></a></li>

	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'manage_activity')==true){?>active-left-menu<?php  }?> <?php if($mac_sel!="select"){?>disable-link<?php }?>"><a <?php if($mac_sel=="select"){?> href="manage_activity.php" <?php }?>><img src="img/dark-composite.png" class="img-responsive" /><p>Manage Activities</p></a></li>
</ul>