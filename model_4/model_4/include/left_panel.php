<?php $chs_sel = $pps_sel = $ppd_sel = ""; 
if(strpos($_SERVER['REQUEST_URI'],'/')==true || strpos($_SERVER['REQUEST_URI'],'/index.php')==true || strpos($_SERVER['REQUEST_URI'],'/existing_ou_project.php')==true){
	$chs_sel="select";
}

if((strpos($_SERVER['REQUEST_URI'],'existing_project')==true)){
	$ppd_sel="select";
}
?>
<p class="text-center" style="font-size: 17px;"><b>What would you like to do?</b></p>
<ul id="left-menu" style="padding-left: 0;">
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'/')==true || strpos($_SERVER['REQUEST_URI'],'/index.php')==true || strpos($_SERVER['REQUEST_URI'],'/existing_ou_project.php')==true){?>active-left-menu<?php }?>">
		<?php if(isset($lp_sel) && $lp_sel=="operating_unit"){?>
		<a href="index.php">
			<img src="img/skyblue.png" />
			<p>I want to see all projects for every Operating Unit.</p>
		</a>	
		<?php } else {?>
		<a href="existing_ou_project.php">
			<img src="img/skyblue.png"/>
			<p>I want to see everything related to my Operating Unit.</p>
		</a>
		<?php }?>
	</li>
	<li>
		<a href="<?php echo SITE_PATH;?>usaid/strategy3" target="_blank">
			<img src="img/red.png"  />
			<p>I want to work on a Results Framework, a Results Chain or a Theory of Change</p>
		</a>
	</li>
	<li>
		<a href="cdcs.php">
			<img src="img/orange.png" />
			<p>I have an idea for a new project so I want to create a Project Purpose Statement</p>
		</a>
	</li>
	<li>
		<a href="choose_project_starting_point.php">
			<img src="img/parrot-green.png"/>
			<p>I want to convert a Project Purpose Statement into a Project Design Plan or I want to create a new Project Design Plan</p>
		</a>
	</li>
	<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'existing_project')==true){?>active-left-menu<?php }?>">
		<a href="existing_project.php">
			<img src="img/composite.png"/>
			<p>I want to edit an existing Project</p>
		</a>
	</li>
	<li>
		<a href="todo_list.php">
			<img src="img/dark-composite.png" class="img-responsive" />
			<p>I want to work on tasks in my Task List</p>
		</a>
	</li>
</ul>