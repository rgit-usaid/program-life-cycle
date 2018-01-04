<!--project overview start-->
<?php 
	##==unset activity id====
	unset($_SESSION['activity_id']);
?>
<?php if($project_id!=""){
	 $_SESSION['project_id'] = $project_id;
	?>
	<div class="row">
		<!--project basic info start-->
		<div id="project_overview" class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
			<h2>Project:<span><?php echo $project_id;?></span></h2>
			<div class="project_name">
				<?php 
					$title = '';
					if(isset($_REQUEST['title'])) $title = $_REQUEST['title'];
					if(isset($project_arr)) $title = $project_arr['data']['title'];
					echo $title;
				?>
			</div>
		</div>
		<!--project basic info end-->
		<!--project stage start-->
		<div class="col-lg-5 col-md-4 col-sm-5 col-xs-12 hdr-stage">
			<div class="stage-heading complete">Stage</div>
			<form action="" method="post">
				<input type="hidden" value="" name="view_only_mode" class="view_only_mode"/>
				<?php 
					$sel_stage='';
					for($stage_count=0; $stage_count<count($project_stage_arr['data']); $stage_count++) {
						if($project_stage_arr['data'][$stage_count]['stage_id']==$project_arr['data']['project_stage_id']){
							$sel_stage=$project_stage_arr['data'][$stage_count]['stage_id'];
						}
					if($sel_stage==""){?>
						<div class="project-stage complete project-st-<?php echo $project_stage_arr['data'][$stage_count]['stage_id'];?>">
							<?php echo $project_stage_arr['data'][$stage_count]['stage_name'];?>
							<input class="stage_name" value="<?php echo $project_stage_arr['data'][$stage_count]['stage_id'];?>" type="hidden"/>
						</div>
					<?php } else if($sel_stage==$project_stage_arr['data'][$stage_count]['stage_id']){?>
						<div class="project-stage select project-st-<?php echo $project_stage_arr['data'][$stage_count]['stage_id'];?>">
							<?php echo $project_stage_arr['data'][$stage_count]['stage_name'];?>
							<input class="stage_name" value="<?php echo $project_stage_arr['data'][$stage_count]['stage_id'];?>" type="hidden"/>
						</div>	
					<?php } else {?>
						<div class="project-stage incomplete project-st-<?php echo $project_stage_arr['data'][$stage_count]['stage_id'];?>">
							<?php echo $project_stage_arr['data'][$stage_count]['stage_name'];?>
							<input class="stage_name" value="<?php echo $project_stage_arr['data'][$stage_count]['stage_id'];?>" type="hidden"/>
						</div>	
					<?php }?>
				<?php } ?>
			</form>
			<input type="hidden" name="project_stage_id"/>
		</div>
		<!--project overview end-->
	</div>
	<!--project info end-->
	<!--project navbar start-->
  <div class="row">
	<div class="col-lg-7 col-md-8 col-sm-12 col-xs-12 project_header">
		<div id="project-navbar" class="btn-group btn-group-justified" role="group">
		  <div class="btn-group" role="group">
			  <button type="button" class="btn btn-dark-blue dropdown-toggle no-bdr-rd" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Project Info <span class="caret"></span></button>
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
			<button type="button" class="btn btn-dark-blue" onclick="redirect_to('list_activity')">Activities</button>
		  </div>
		  <div class="btn-group" role="group">
			<button type="button" class="btn btn-dark-blue no-bdr-rd dropdown-toggle" data-toggle="dropdown">Documents <span class="caret"></span></button>
			<ul class="dropdown-menu">
				<li><a href="javascript:redirect_to('manage_key_documents')">Key Documents</a></li>
			 </ul>
		  </div>
		</div>
	</div>
	<div id="project_navbar_bdr" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div></div>
	</div>
  </div>
<form id="selected_project" method="post">
	<input type="hidden" name="project_id" value="<?php echo $project_id;?>"/>
	<input type="hidden" name="details" value="Details">
</form>
<?php }?>
<script>
function redirect_to(url){
	$('#selected_project').attr('action',url);
	$('#selected_project').submit();
}
$(document).ready(function(){
	$('.hdr-stage .project-stage').click(function(){
		var project_stage = $(this).find('.stage_name').val();
		var sel_stage = $(this).closest('.hdr-stage').find('.project-stage.select').find('.stage_name').val();
		var flag_stage_doc = 0;
		if(sel_stage>2 && project_stage<3){	/*if selected stage is from desing plan than see design plan in readonly mode*/
			$(this).closest('form').find('.view_only_mode').val(2);
		}
		else if(sel_stage>2 && project_stage>=3){ /*if selected stage is from apporval group than see apporval document*/
			$(this).closest('form').find('.view_only_mode').val(1);
		}
		else{ /*if selected stage is from desing plan than see design plan*/
			$(this).closest('form').find('.view_only_mode').val(0);
		}
		$(this).closest('form').submit();
		/*if(project_stage>2){
			$(this).closest('form').attr('action','');
		}
		else{
			$(this).closest('form').attr('action','');
		}
		$(this).closest('form').submit();*/
	});
});
</script>		
<!--project navbar end-->