<?php
$cbi_sel = $pps_sel = $ir_subir = $prv_rat = $prj_role =""; 
if((strpos($_SERVER['REQUEST_URI'],'project_info')==true)){
	$cbi_sel="select";
}

if((strpos($_SERVER['REQUEST_URI'],'manage_activity')==true) || (strpos($_SERVER['REQUEST_URI'],'activity_list')==true)){
	$cbi_sel="select";
	$prv_rat="select";	
}

if((strpos($_SERVER['REQUEST_URI'],'project_finance')==true)){
	$cbi_sel="select";
	$prv_rat="select";	
	$pps_sel="select";
}

if((strpos($_SERVER['REQUEST_URI'],'project_performance')==true)){
	$cbi_sel="select";
	$prv_rat="select";	
	$pps_sel="select";
	$ir_subir="select";
}

if((strpos($_SERVER['REQUEST_URI'],'manage_project_document')==true)){
	$cbi_sel="select";
	$prv_rat="select";	
	$pps_sel="select";
	$ir_subir="select";
	$prj_role="select";
}

$url_activity = "manage_activity.php";
if(strpos($_SERVER['REQUEST_URI'],'activity_list')==true){
	$url_activity = "activity_list.php";
}
?>
<p class="text-center" style="font-size: 17px;"><b>Edit Existing Project</b></p>
<ul id="left-menu" style="padding-left: 0;">
	<?php if((strpos($_SERVER['REQUEST_URI'],'existing_project')==true)){?>
	<li>
		<a href="existing_project.php">
			<img src="img/skyblue.png"/>
			<p>Choose a project to edit</p>
		</a>
	</li>
	<?php }?>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'project_info')==true)){?>active-left-menu<?php }?> <?php if($cbi_sel!="select"){?>disable-link<?php }?>">
		<a <?php if($cbi_sel=="select"){?>href="project_info.php" <?php }?>>
			<img src="img/skyblue.png"/>
			<p>Project Info</p>
		</a>
	</li>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'manage_activity')==true) || (strpos($_SERVER['REQUEST_URI'],'activity_list')==true)){?>active-left-menu<?php }?> <?php if($prv_rat!="select"){?>disable-link<?php }?>">
		<a <?php if($prv_rat=="select"){?>href="<?php echo $url_activity;?>" <?php }?>>
			<img src="img/red.png"/>
			<p>Activities</p>
		</a>
	</li>
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'project_finance')==true){?>active-left-menu<?php }?> <?php if($pps_sel!="select"){?>disable-link<?php }?>">
		<a <?php if($pps_sel=="select"){?> href="project_finance.php" <?php }?>>
			<img src="img/orange.png"/>
			<p>Project Financial Info</p>
		</a>
	</li>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'project_performance')==true)){?>active-left-menu<?php }?> <?php if($ir_subir!="select"){?>disable-link<?php }?>">
		<a <?php if($ir_subir=="select"){?> href="project_performance.php" <?php }?>>
			<img src="img/parrot-green.png"/>
			<p>Performance</p>
		</a>
	</li>
	
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'manage_project_document')==true){?>active-left-menu<?php }?> <?php if($prj_role!="select"){?>disable-link<?php }?>">
		<a <?php if($prj_role=="select"){?>href="manage_project_document.php" <?php }?>>
			<img src="img/composite.png"/>
			<p>Documents</p>
		</a>
	</li>
</ul>