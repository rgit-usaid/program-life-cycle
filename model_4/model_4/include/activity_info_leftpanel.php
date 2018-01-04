<?php
$cbi_sel = $pps_sel = $ir_subir = $prv_rat = $prj_role = $url_project_doc =""; 
if((strpos($_SERVER['REQUEST_URI'],'add_activity.php')==true) || (strpos($_SERVER['REQUEST_URI'],'edit_activity.php')==true)){
	$cbi_sel="select";
}

if((strpos($_SERVER['REQUEST_URI'],'manage_activity_team')==true) || (strpos($_SERVER['REQUEST_URI'],'add_activity_team_member')==true) || (strpos($_SERVER['REQUEST_URI'],'edit_activity_team_member')==true) || (strpos($_SERVER['REQUEST_URI'],'archive_activity_team_member')==true)){
	$cbi_sel="select";
	$pps_sel="select";
}

if((strpos($_SERVER['REQUEST_URI'],'manage_activity_finance')==true)){
	$cbi_sel="select";
	$pps_sel="select";
	$ir_subir="select";	
}

if((strpos($_SERVER['REQUEST_URI'],'manage_activity_procurement')==true)){
	$cbi_sel="select";
	$pps_sel="select";
	$ir_subir="select";	
	$prv_rat="select";
}

if((strpos($_SERVER['REQUEST_URI'],'manage_activity_program_element')==true)){
	$cbi_sel="select";
	$pps_sel="select";
	$ir_subir="select";	
	$prv_rat="select";
	$prj_role="select";
}
?>
<p class="text-center" style="font-size: 17px;"><b>Edit Activity Details</b></p>
<ul id="left-menu" style="padding-left: 0;">
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'add_activity.php')==true) || (strpos($_SERVER['REQUEST_URI'],'edit_activity.php')==true)){?>active-left-menu<?php }?> <?php if($cbi_sel!="select"){?>disable-link<?php }?>">
		<a <?php if($cbi_sel=="select"){?>href="manage_activity.php" <?php }?>>
			<img src="img/skyblue.png"/>
			<p>Detail</p>
		</a>
	</li>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'manage_activity_team')==true) || (strpos($_SERVER['REQUEST_URI'],'add_activity_team_member')==true) || (strpos($_SERVER['REQUEST_URI'],'edit_activity_team_member')==true) || (strpos($_SERVER['REQUEST_URI'],'archive_activity_team_member')==true)){?>active-left-menu<?php }?> <?php if($pps_sel!="select"){?>disable-link<?php }?>">
		<a <?php if($pps_sel=="select"){?> href="manage_activity_team.php" <?php }?>>
			<img src="img/red.png"/>
			<p>Team</p>
		</a>
	</li>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'manage_activity_finance')==true)){?>active-left-menu<?php }?> <?php if($ir_subir!="select"){?>disable-link<?php }?>">
		<a <?php if($ir_subir=="select"){?> href="manage_activity_finance.php" <?php }?>>
			<img src="img/orange.png"/>
			<p>Finance</p>
		</a>
	</li>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'manage_activity_procurement')==true)){?>active-left-menu<?php }?> <?php if($prv_rat!="select"){?>disable-link<?php }?>">
		<a <?php if($prv_rat=="select"){?> href="manage_activity_procurement.php" <?php }?>>
			<img src="img/parrot-green.png"/>
			<p>Procurement</p>
		</a>
	</li>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'manage_activity_program_element')==true)){?>active-left-menu<?php }?> <?php if($prj_role!="select"){?>disable-link<?php }?>">
		<a <?php if($prj_role=="select"){?> href="manage_activity_procurement.php" <?php }?>>
			<img src="img/composite.png"/>
			<p>Program Element</p>
		</a>
	</li>
</ul>