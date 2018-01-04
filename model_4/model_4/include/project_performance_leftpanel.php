<?php
$cbi_sel = $pps_sel = $ir_subir = $prv_rat = $prj_role = $url_project_doc =""; 
if((strpos($_SERVER['REQUEST_URI'],'manage_project_monitoring')==true)){
	$cbi_sel="select";
}

if((strpos($_SERVER['REQUEST_URI'],'manage_project_evaluation')==true)){
	$cbi_sel="select";
	$ir_subir="select";
}
?>
<p class="text-center" style="font-size: 17px;"><b>Edit Project Performance Details</b></p>
<ul id="left-menu" style="padding-left: 0;">
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'manage_project_monitoring')==true){?>active-left-menu<?php }?> <?php if($cbi_sel!="select"){?>disable-link<?php }?>">
		<a <?php if($cbi_sel=="select"){?> href="manage_project_monitoring.php" <?php }?>>
			<img src="img/red.png"/>
			<p>Monitoring</p>
		</a>
	</li>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'manage_project_evaluation')==true)){?>active-left-menu<?php }?> <?php if($ir_subir!="select"){?>disable-link<?php }?>">
		<a <?php if($ir_subir=="select"){?> href="manage_project_evaluation.php" <?php }?>>
			<img src="img/orange.png"/>
			<p>Evaluation</p>
		</a>
	</li>
</ul>