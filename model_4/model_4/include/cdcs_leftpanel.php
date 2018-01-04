<?php
$cbi_sel = $pps_sel = $ir_subir = $prv_rat = $prj_role =""; 
if(strpos($_SERVER['REQUEST_URI'],'cdcs')==true){
	$cbi_sel="select";
}

if(strpos($_SERVER['REQUEST_URI'],'create_an_opportunity')==true){
	$cbi_sel="select";
	$pps_sel="select";
}

if((strpos($_SERVER['REQUEST_URI'],'create_ir_subir')==true) || (strpos($_SERVER['REQUEST_URI'],'no_framework_found')==true)){
	$cbi_sel="select";
	$pps_sel="select";
	$ir_subir="select";
}

if(strpos($_SERVER['REQUEST_URI'],'provide_rational')==true){
	$cbi_sel="select";
	$pps_sel="select";
	$ir_subir="select";
	$prv_rat="select";	
}

if(strpos($_SERVER['REQUEST_URI'],'project_role')==true){
	$cbi_sel="select";
	$pps_sel="select";
	$ir_subir="select";
	$prv_rat="select";	
	$prj_role="select";
}

?>
<p class="text-center" style="font-size: 17px;"><b>Create a Project Purpose Statement</b></p>
<ul id="left-menu" style="padding-left: 0;">
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'cdcs')==true){?>active-left-menu<?php }?> <?php if($cbi_sel!="select"){?>disable-link<?php }?>">
		<a <?php if($cbi_sel=="select"){?>href="operating_unit.php" <?php }?>>
			<img src="img/skyblue.png"/>
			<p>Collect background information</p>
		</a>
	</li>
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'create_an_opportunity')==true){?>active-left-menu<?php }?> <?php if($pps_sel!="select"){?>disable-link<?php }?>">
		<a <?php if($pps_sel=="select"){?> href="create_an_opportunity.php" <?php }?>>
			<img src="img/red.png"/>
			<p>Create a Project Purpose Statement</p>
		</a>
	</li>
	<li class="<?php if((strpos($_SERVER['REQUEST_URI'],'create_ir_subir')==true) || (strpos($_SERVER['REQUEST_URI'],'no_framework_found')==true)){?>active-left-menu<?php }?> <?php if($ir_subir!="select"){?>disable-link<?php }?>">
		<a <?php if($ir_subir=="select"){?> href="create_ir_subir.php" <?php }?>>
			<img src="img/orange.png"/>
			<p>Select associated IRs or Sub-IRs</p>
		</a>
	</li>
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'provide_rational')==true){?>active-left-menu<?php }?> <?php if($prv_rat!="select"){?>disable-link<?php }?>">
		<a <?php if($prv_rat=="select"){?>href="provide_rational.php" <?php }?>>
			<img src="img/parrot-green.png"/>
			<p>Provide rationale for each IR or Sub-IR</p>
		</a>
	</li>
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'project_role')==true){?>active-left-menu<?php }?> <?php if($prj_role!="select"){?>disable-link<?php }?>">
		<a <?php if($prv_rat=="select"){?>href="project_role.php" <?php }?>>
			<img src="img/composite.png"/>
			<p>Identify project roles that will be needed</p>
		</a>
	</li>
</ul>