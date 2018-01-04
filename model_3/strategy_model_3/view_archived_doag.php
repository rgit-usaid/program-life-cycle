<?php
include('config/config.inc.php');
include('include/function.inc.php');

//## get Detail operating unit ===========
if($_SESSION['operating_unit_id']!='')
{
	$operating_unit_id = $_SESSION['operating_unit_id'];
	$url = PHOENIX_API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id;
	$operating_unit_arr = requestByCURL($url); 
}
else
{
	header('location:index.php');
}

/*generate_filename*/
function generate_filename($filename){
	$new_filename = '';
	$explode = explode(".",$filename);
	$new_filename = $explode[0].strtotime("now").'.'.$explode[1];
	return $new_filename;
}



if(!empty($_REQUEST['objective_agreement_view_id']))
{
	$objective_agreement_view_id = $_REQUEST['objective_agreement_view_id'];
	$select_object_agreement="select * from usaid_objective_agreement where id='".$objective_agreement_view_id."'"; 
	$result_object_agreement = $mysqli->query($select_object_agreement);
	$fetch_object_agreement = $result_object_agreement->fetch_array();
	
	 $approved_date = $fetch_object_agreement['approved_date']; 
	 $date=explode("-", $approved_date);
	 $year = $date[0];
	 $month = $date[1];
	 $day = $date[2];
	 
	$select_relation="select * from usaid_objective_agreement_relation where objective_agreement_id='".$objective_agreement_view_id."'";
	$result_relation = $mysqli->query($select_relation);
	$total_relation = $result_relation->num_rows;
		$i=0;
	while($fetch_relation = $result_relation->fetch_array())
		{	
			$relation_arr[$i] = $fetch_relation['relation_id']; $i++;
		}
	$select_document="select * from usaid_objective_agreement_document where objective_agreement_id='".$objective_agreement_view_id."'";
	$result_document = $mysqli->query($select_document);
	$total_document = $result_document->num_rows;
	
	
}
# get all dovelopment objective by operating unit 
	$select_frame="select * from usaid_frame where operating_unit_id='".$operating_unit_id."' and status='Active'";
	$result_frame = $mysqli->query($select_frame);
	$fetch_frame = $result_frame->fetch_array();
	$frame_id = $fetch_frame['id'];
	
	$select_DO="select * from usaid_development_objective where frame_id='".$frame_id."'";
	$result_DO = $mysqli->query($select_DO);
	$total_DO = $result_DO->num_rows;
	

$page_name="doag_management";	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>USAID</title>
	<!-- Bootstrap -->
	<link rel="shortcut icon" type="image/ico" href="images/favicon.ico"/>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">	
	<link href="css/sumoselect.css" rel="stylesheet">
	<link href="css/uswds.min.css" rel="stylesheet">

	
	<link rel="stylesheet" type="text/css" href="css/plugin/tags/bootstrap-tagsinput.css">
	
	<script>
		function refreshPage(){
			window.location.reload();
		} 
	</script>	
</head>
<body>
	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>
	
	<!-- Header Details -->
	<div class="container-fluid">
		<div class="header-detail">
			<div class="head-title">
				<div class="head">Operating Unit <span class="pull-right">(<a href="go_to_doag.php">Back </a>)</span></div>
			</div>
			<div class="row clear details">
				<div class="col-md-12"><a href="manage.php" class="remove-line"><?php echo $operating_unit_arr['data']['operating_unit_description']; ?></a> >> <a href="go_to_doag.php" class="remove-line"> DOAGs and SOAGs</a> >> <a href="archived_doag.php" class="remove-line"> Archived</a> >> View</div>
			</div>
		</div>
	</div>

	<!-- Frame Menu -->

	<div class="container-fluid">
		<div class="col-md-4 text-center">
			<a class="usa-button usa-button-outline active-class-white disable active" href="add_doag.php">Add a DOAG/SOAG</a>
		</div>
		<div class="col-md-4 text-center">
			<a class="usa-button usa-button-outline active-class-white disable active" href="go_to_doag.php">Go to DOAG & SOAG</a>
		</div>
		<div class="col-md-4 text-center">
			<a class="usa-button usa-button-outline-inverse active-class-white active" href="archived_doag.php">Archived DOAGs & SOAGs</a>
		</div>
	</div>

	<div class="container-fluid">
		<form class="usa-form" method="post" action="" enctype="multipart/form-data">
		<div id="add_doag_blk" style="width:500px; margin-left:350px;">
			<div style="padding:1px; text-align:center;"><h2 style="margin-top: 8px; margin-bottom: 3px;">View <?php echo $fetch_object_agreement['objective_agreement_type']; ?></h2></div>
			<fieldset>
					<div class="form-group">
						<?php  if($fetch_object_agreement!=''){ $objective_agreement_id = $fetch_object_agreement['id'];}  ?>	
						<input id="input-type-text" type="hidden" name="objective_agreement_id" value="<?php echo $objective_agreement_id; ?>" readonly>
						
					<!--	<?php  if($fetch_object_agreement!=''){ $objective_agreement_type = $fetch_object_agreement['objective_agreement_type'];}  ?>	
						<div style="display:inline-block; padding-right:5px;"><input name="objective_agreement_type" type="radio" value="DOAG" <?php if($objective_agreement_type=='DOAG') echo "checked"; ?> readonly><label>DOAG</label></div>
						<div style="display:inline-block"><input name="objective_agreement_type" type="radio" value="SOAG" <?php if($objective_agreement_type=='SOAG') echo "checked"; ?> readonly><label>SOAG</label></div>
					</div>  -->
					<div class="form-group">
						<label for="input-type-text">Name</label>
						<?php  if($fetch_object_agreement!=''){ $name = $fetch_object_agreement['name'];}  ?>	
						<input id="input-type-text" name="name" value="<?php echo $name; ?>" type="text" readonly>
					</div>
					<div class="form-group">
						<label for="input-type-textarea">Description</label>
						<?php  if($fetch_object_agreement!=''){ $description = $fetch_object_agreement['description'];}  ?>	
						<textarea id="input-type-textarea" name="description" readonly><?php echo $description; ?></textarea>
					</div>
					<div class="form-group">
						<label for="input-type-textarea">Estimate of Funding Needed</label>
						<?php  if($fetch_object_agreement!=''){ $funding = $fetch_object_agreement['funding_estimate'];}  ?>	
						<input name="funding_estimate" value="<?php if($funding!='') echo "$".number_format($funding); ?>" readonly>
					</div>
					<div class="form-group">
						<label for="input-type-textarea">Date Approved</label>
						<div class="usa-date-of-birth">
							<div class="usa-form-group usa-form-group-month">
								<label for="date_of_birth_1">Month</label>
								<input class="usa-input-inline" aria-describedby="dobHint"  id="date_of_birth_1" name="month" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="<?php echo $month; ?>" placeholder="MM" style="font-size:13px" readonly>
							</div>
							<div class="usa-form-group usa-form-group-day">
								<label for="date_of_birth_2">Day</label>
								<input class="usa-input-inline" aria-describedby="dobHint" id="date_of_birth_2" name="day" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="<?php echo $day; ?>" placeholder="DD" readonly>
							</div>
							<div class="usa-form-group usa-form-group-year">
								<label for="date_of_birth_3">Year</label>
								<input class="usa-input-inline" aria-describedby="dobHint" id="date_of_birth_3" name="year" pattern="[0-9]{4}" type="number" min="1900" max="3000" value="<?php echo $year; ?>" placeholder="YYYY" readonly>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
					<?php if($total_DO>0) { ?>
						<label for="input-type-textarea">Relationship to DOs</label>
					<!--	<select multiple="multiple" name="relation_id[]" class="SlectBox" > -->	
					<?php 
						 while($fetch_DO = $result_DO->fetch_array()) 
							{ 
							$do_id=$fetch_DO['id']; 
							 $objective_description=$fetch_DO['objective_description']; ?>
							<option value="<?php echo $do_id; ?>"<?php if(in_array($do_id, $relation_arr)) echo "selected"; ?>><?php echo $objective_description; ?></option>
					<?php   }  ?>
						<!-- </select> -->
					<?php } else { ?>
						<label for="input-type-textarea">No Active DOs</label>
					<?php } ?>	
					</div>
					<?php if($total_document>0) { 
					while($fetch_document = $result_document->fetch_array()) {?>
					<div class="add_doc_blk">
						<div class="close_btn disp_none"><i class="fa fa-times text-danger" title="Remove"></i></div>
						<input type="hidden" name="document_id_arr[]" value="<?php echo $fetch_document['id']; ?>" >
						<div class="form-group">
							<span class="bold">Document Name</span>
							<input name="document_name[]" value="<?php echo $fetch_document['document_name']; ?>" type="text" readonly/>
						</div>
						
						<div class="form-group">
							<span class="bold">Document Tags</span>
							<input type="text" name="tags[]" value="<?php echo $fetch_document['document_tags']; ?>" class="tagsinput-typeahead" readonly>
						</div>
						
						<div class="form-group">
							<span class="bold">Document</span>
							<?php if($fetch_document['document_path']!=""){?>
							<a href="<?php echo $fetch_document['document_path']; ?>" class="download_link pull-right" target="_blank"><img src="img/download-icon.png" title="<?php echo $fetch_document['document_name']; ?>"></a>
							<div class="clearfix"></div>
						<?php } else echo "<br>No file"; ?>
							
							
						</div>
					</div>
					<?php } } else { ?>
					<div class="add_doc_blk">
						<div class="close_btn disp_none"><i class="fa fa-times text-danger" title="Remove"></i></div>
						<div class="form-group">
							<span class="bold">Document Name</span>
							<input name="document_name[]" type="text" readonly/>
						</div>
						
						<div class="form-group">
							<span class="bold">Document Tags</span>
							<input type="text" name="tags[]" class="tagsinput-typeahead" readonly>
						</div>
						
						<div class="form-group">
							<span class="bold">Document</span>
							<input name="document_file[]" type="file" />
						</div>
					</div>
					<?php } ?>
					
				</fieldset>
		</div>
		
		</form>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/plugin/tags/bootstrap-tagsinput.js"></script>
	<script type="text/javascript" src="js/plugin/tags/bootstrap3-typeahead.js"></script>
	<script src="js/uswds.min.js"></script>
	
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script src="js/jquery.sumoselect.min.js"></script>
	<script>
		$(document).ready(function () {
			$('.SlectBox').SumoSelect({
				placeholder: 'Select Development Objective',
				okCancelInMulti: false,

				search : true,
				csvDispCount: 1
			});
			$('.link').SumoSelect({
				placeholder: 'Select DOAGs',
				okCancelInMulti: false,
				search : true,
				csvDispCount: 1
			});
		});
	var tags = new Array();	
	


	$(document).on('click','.tagsinput-typeahead',function(){
		$(this).tagsinput({
		 typeahead: {
		source: tags.map(function(item) { return item.name }),
		afterSelect: function() {
		this.$element[0].value = '';
		alert('dd');
		}
		 }
		}); 
	});
	
	$(document).on('click','.add_doc_blk .close_btn',function(){
		$(this).closest('.add_doc_blk').remove();
	});
	
	$(document).ready(function () {
		$('.add_document').click(function(){
			var clone = $('.add_doc_blk:first').clone();
			clone.find('input').val("");
			clone.find('.bootstrap-tagsinput').remove();
			clone.find('.tagsinput-typeahead').css('display','block');
			clone.find('.close_btn').removeClass('disp_none');
			$(clone).insertAfter('.add_doc_blk:last');
		});
	});
	
	</script>
</body>
</html>