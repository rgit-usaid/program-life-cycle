<?php
$cbi_sel = $pps_sel = $ir_subir = $prv_rat = $prj_role = $url_project_doc =""; 
if((strpos($_SERVER['REQUEST_URI'],'manage_project_design_plan')==true) || (strpos($_SERVER['REQUEST_URI'],'manage_project_appraisal_document')==true)){
	$cbi_sel="select";
}

$url_project_doc = "manage_project_appraisal_document.php";

if((strpos($_SERVER['REQUEST_URI'],'manage_project_design_plan')==true)){
	$url_project_doc = "manage_project_design_plan.php";
}

if((strpos($_SERVER['REQUEST_URI'],'manage_project_team')==true) || (strpos($_SERVER['REQUEST_URI'],'add_project_team_member')==true) || (strpos($_SERVER['REQUEST_URI'],'edit_project_team_member')==true) || (strpos($_SERVER['REQUEST_URI'],'archive_project_team_member')==true)){
	$cbi_sel="select";
	$pps_sel="select";
}

if((strpos($_SERVER['REQUEST_URI'],'manage_project_geo_location')==true)){
	$cbi_sel="select";
	$pps_sel="select";
	$ir_subir="select";	
}

if(strpos($_SERVER['REQUEST_URI'],'manage_project_strategy')==true){
	$cbi_sel="select";
	$pps_sel="select";
	$ir_subir="select";
	$prj_role="select";	
}

if(strpos($_SERVER['REQUEST_URI'],'project_role')==true){
	$cbi_sel="select";
	$pps_sel="select";
	$ir_subir="select";
	$prv_rat="select";	
	$prj_role="select";
}

?>
<p class="text-center" style="font-size: 17px;"><b>Edit Project Details</b></p>
<ul id="left-menu" style="padding-left: 0;">
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'manage_project_design_plan')==true) || (strpos($_SERVER['REQUEST_URI'],'manage_project_appraisal_document')==true)){?>active-left-menu<?php }?> <?php if($cbi_sel!="select"){?>disable-link<?php }?>">
		<a <?php if($cbi_sel=="select"){?>href="<?php echo $url_project_doc;?>" <?php }?>>
			<img src="img/skyblue.png"/>
			<p>Detail</p>
		</a>
	</li>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'manage_project_team')==true) || (strpos($_SERVER['REQUEST_URI'],'add_project_team_member')==true) || (strpos($_SERVER['REQUEST_URI'],'edit_project_team_member')==true) || (strpos($_SERVER['REQUEST_URI'],'archive_project_team_member')==true)){?>active-left-menu<?php }?> <?php if($pps_sel!="select"){?>disable-link<?php }?>">
		<a <?php if($pps_sel=="select"){?> href="manage_project_team.php" <?php }?>>
			<img src="img/red.png"/>
			<p>Team</p>
		</a>
	</li>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'manage_project_geo_location')==true)){?>active-left-menu<?php }?> <?php if($ir_subir!="select"){?>disable-link<?php }?>">
		<a <?php if($ir_subir=="select"){?> href="manage_project_geo_location.php" <?php }?>>
			<img src="img/orange.png"/>
			<p>Geo-Coding</p>
		</a>
	</li>
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'manage_project_strategy')==true){?>active-left-menu<?php }?> <?php if($prj_role!="select"){?>disable-link<?php }?>">
		<a <?php if($prj_role=="select"){?>href="manage_project_strategy.php" <?php }?>>
			<img src="img/parrot-green.png" />
			<p>Strategy</p>
		</a>
	</li>
</ul>