<!--project overview start-->
<style type="text/css">
	#activity-navbar .dropdown-menu {
    background: #112e51;
    border-radius: 0;
    top: 43px;
}
.dropdown-menu li{list-style: none;}
.dropdown-menu li a{color: #fff !important}
.dropdown-menu li a:hover, .dropdown-menu li a:focus{color: #fff !important; text-decoration: underline; background-color:#112e51 }
</style>
	<?php if($activity_id!=""){?>
	<div class="row">
		<!--project basic info start-->
		<div id="project_overview" class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
			<h2>Activity Id:<span><?php echo $activity_id;?></span></h2>
			<div class="project_name">
				<?php 
					$title = '';
					if(isset($project_activity_arr)) $title = $project_activity_arr['data']['title'];
					echo $title;
				?>
			</div>
		</div>
		<!--project basic info end-->
		<!--project stage start-->
		<div class="col-lg-5 col-md-4 col-sm-5 col-xs-12 hdr-stage">
			<!--<div  class="text-center">
			<img src="img/user.png" class="img-responsive center-block activity_user_img" width="100"/>
				<p class="info">Alison Anne Smith</p>
				<p class="info">Activity Inputter</p>
			</div>-->
		</div>
		<!--project overview end-->
	</div>
	<?php } else {?>
	<div style="height:10px"></div>
	<?php }?>
<!--project info end-->
<!--project navbar start-->
  <div class="row activity_header">
	<div class="col-lg-7 col-md-8 col-sm-12 col-xs-12 project_header">
		<div id="project-navbar" class="btn-group btn-group-justified" role="group">
		  <div class="btn-group" role="group">
			<button type="button" class="btn btn-dark-blue dropdown-toggle no-bdr-rd" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Project Info <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu">
				<li><a href="javascript:redirect_to('add_new_project')">Details</a></li>
				<li><a href="javascript:redirect_to('manage_team_member')">Team</a></li>
				<li><a href="javascript:redirect_to('add_project_geo_location')">Geo-Coding</a></li>
				<li><a href="javascript:redirect_to('view_strategy')">Strategy</a></li>
			  </ul>
		  </div>
		  <div class="btn-group" role="group">
			<button type="button" class="btn btn-dark-blue dropdown-toggle" data-toggle="dropdown">Financial Info <span class="caret"></span></button>
			 <ul class="dropdown-menu">
				<li><a href="project_finance">Finance</a></li>
				<li><a href="project_procurement">Procurement</a></li>
			 </ul>
		  </div>
		  <div class="btn-group" role="group">
			<button type="button" class="btn btn-dark-blue" data-toggle="dropdown">Performance <span class="caret"></span></button> 
			<ul class="dropdown-menu">
				<li><a href="javascript:redirect_to('add_project_review')">Monitoring</a></li>
				<li><a href="javascript:redirect_to('add_new_evaluation')">Evaluation</a></li>
			 </ul>
		  </div>
		  <div class="btn-group" role="group">
			<button type="button" class="btn btn-dark-blue " onclick="redirect_to('list_activity')">Activites</button>
		  </div>
		  <div class="btn-group" role="group">
			<button type="button" class="btn btn-dark-blue no-brd-rd dropdown-toggle" data-toggle="dropdown">Documents <span class="caret"></span></button>
			<ul class="dropdown-menu">
				<li><a href="javascript:redirect_to('manage_key_documents')">Key Documents</a></li>
			 </ul>
		  </div>
		</div>
	</div>
	<?php if($activity_id!=""){?>
	<div class="col-lg-7 col-md-8 col-sm-12 col-xs-12 activity_navbar">
		<button onclick="redirect_to('add_new_activity')" type="button" class="usa-button-active <?php if(strpos($_SERVER['REQUEST_URI'],'add_new_activity')!==false) {?> usa-button-primary-alt <?php }?>">Details</button>
		<button onclick="redirect_to('manage_activity_team_member')" type="button" class="usa-button-active <?php if(strpos($_SERVER['REQUEST_URI'],'manage_activity_team_member')!=false) {?> usa-button-primary-alt <?php }?>">Team</button>
		<button onclick="redirect_to('project_activity_finance')" type="button" class="usa-button-active <?php if(strpos($_SERVER['REQUEST_URI'],'project_activity_finance')!=false) {?> usa-button-primary-alt <?php }?>">Finance</button>
		<button onclick="redirect_to('project_activity_procurement')" type="button" class="usa-button-active <?php if(strpos($_SERVER['REQUEST_URI'],'project_activity_procurement')!=false) {?> usa-button-primary-alt <?php }?>">Procurement</button> 
		<div id="activity-navbar" class="btn-group" role="group">
			<button type="button" class="usa-button-active" data-toggle="dropdown">Performance <span class="caret"></span></button> 
			<ul class="dropdown-menu">
				<li><a href="javascript:redirect_to('add_activity_review')">Monitoring</a></li>
				<li><a href="javascript:redirect_to('add_activity_evaluation')">Evaluation</a></li>
			 </ul>
		</div> 
	</div>
	<?php }?>
	<div id="project_navbar_bdr" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div></div>
	</div>
  </div>
<form id="selected_project" method="post">
	<input type="hidden" name="project_id" value="<?php echo $project_id;?>"/>
	<input type="hidden" name="details" value="Details">
</form>	
<script>
	function redirect_to(url){
		$('#selected_project').attr('action',url);
		$('#selected_project').submit();
	}
</script>	
<!--project navbar end-->