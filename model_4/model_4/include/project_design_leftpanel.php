<?php
$chs_sel = $pps_sel = $ppd_sel = ""; 
if(strpos($_SERVER['REQUEST_URI'],'choose_project_starting_point')==true){
	$chs_sel="select";
}

if((strpos($_SERVER['REQUEST_URI'],'create_design_plan')==true)){
	$chs_sel="select";
	$ppd_sel="select";
}

if((strpos($_SERVER['REQUEST_URI'],'choose_project_for_edit')==true) || (strpos($_SERVER['REQUEST_URI'],'project_purpose_statement')==true) || (strpos($_SERVER['REQUEST_URI'],'project_appraisal_document')==true)){
	$chs_sel="select";
	$pps_sel="select";
	$ppd_sel="select";
}


?>
<p class="text-center" style="font-size: 17px;"><b>Project Design Plan</b></p>
<ul id="left-menu" style="padding-left: 0;">
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'choose_project_starting_point')==true){?>active-left-menu<?php }?> <?php if($chs_sel!="select"){?>disable-link<?php }?>">
		<a <?php if($chs_sel=="select"){?>href="choose_project_starting_point.php" <?php }?>>
			<img src="img/skyblue.png"/>
			<p>Choose where to start</p>
		</a>
	</li>
	<?php 
	$url = "";
	if((strpos($_SERVER['REQUEST_URI'],'choose_project_for_edit')==true)){
		$url = "choose_project_for_edit.php";
	}
	
	if((strpos($_SERVER['REQUEST_URI'],'project_purpose_statement')==true)){
		$url = "project_purpose_statement.php";
	}
	
	if((strpos($_SERVER['REQUEST_URI'],'choose_project_for_edit')==true) || (strpos($_SERVER['REQUEST_URI'],'project_purpose_statement')==true) || (strpos($_SERVER['REQUEST_URI'],'project_appraisal_document')==true)){?>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'choose_project_for_edit')==true) || (strpos($_SERVER['REQUEST_URI'],'project_purpose_statement')==true) || (strpos($_SERVER['REQUEST_URI'],'project_appraisal_document')==true)){?>active-left-menu<?php }?> <?php if($ppd_sel!="select"){?>disable-link<?php }?>">
		<a <?php if($ppd_sel=="select"){?> href="<?php echo $url;?>" <?php }?>>
			<img src="img/orange.png" />
			<p>Convert a Project Purpose Statement</p>
		</a>
	</li>
	<?php }?>
	<?php if((strpos($_SERVER['REQUEST_URI'],'create_design_plan')==true)){?>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'create_design_plan')==true)){?>active-left-menu<?php }?> <?php if($ppd_sel!="select"){?>disable-link<?php }?>">
		<a <?php if($ppd_sel=="select"){?> href="project_design_plan.php" <?php }?>>
			<img src="img/orange.png"/>
			<p>Create a new Project Design Plan</p>
		</a>
	</li>
	<?php }?>
</ul>