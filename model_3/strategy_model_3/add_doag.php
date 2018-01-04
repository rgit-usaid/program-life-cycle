<?php
include('config/config.inc.php');
include('include/function.inc.php');

//echo $previous_page = basename($_SERVER['HTTP_REFERER'], '?' . $_SERVER['QUERY_STRING']); exit;

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

## click on move to archive button =======
if(isset($_REQUEST['move_archive']))
	{	
		$date= date("Y,m,d"); 
		$archive_frame_id = trim($_REQUEST['archive_frame_id']); 
		if($archive_frame_id!='')
		{
			$updata_frame = "update usaid_objective_agreement set
			status = 'Archive', modified_on='now()' where id='".$archive_frame_id."'";
			$result_data = $mysqli->query($updata_frame);
			if($result_data)
			{
				header("location:archived_doag.php");
			}
		}
	}

## insert archived data =============
function insertArchivedDoagSoag($objective_agreement_id)
{
	global $mysqli;
	if($objective_agreement_id!='')
	{
		## insert archive data into usaid_archive_objective_agreement table from usaid_objective_agreement table ==============
		$select_objective_agreement="select * from usaid_objective_agreement where id='".$objective_agreement_id."'";
		$total_agreement_res = $mysqli->query($select_objective_agreement);
		if($total_agreement_res->num_rows>0)
		{
			while($fetch_objective_agreement_data = $total_agreement_res->fetch_array())
			{
				$insert_archive_objective_agreement = "insert into usaid_archive_objective_agreement set 
				objective_agreement_id = '".$fetch_objective_agreement_data['id']."',
				operating_unit_id = '".$fetch_objective_agreement_data['operating_unit_id']."',
				objective_agreement_type='".$fetch_objective_agreement_data['objective_agreement_type']."',
				name='".$mysqli->real_escape_string($fetch_objective_agreement_data['name'])."',
				description='".$mysqli->real_escape_string($fetch_objective_agreement_data['description'])."',
				funding_estimate='".$fetch_objective_agreement_data['funding_estimate']."',
				status='".$fetch_objective_agreement_data['status']."',
				approved_date='".$fetch_objective_agreement_data['approved_date']."',
				added_on='".$fetch_objective_agreement_data['added_on']."',
				modified_on='".$fetch_objective_agreement_data['modified_on']."'"; 
				$result_archive_objective_agreement = $mysqli->query($insert_archive_objective_agreement);
				$archive_document_new_id = $mysqli->insert_id;
			}	 
		}
	## insert archive data into usaid_archive_objective_agreement_relation table from usaid_objective_agreement_relation table ==============
		$select_objective_agreement_relation="select * from usaid_objective_agreement_relation where objective_agreement_id='".$objective_agreement_id."'";
		$total_agreement_relation_res = $mysqli->query($select_objective_agreement_relation);
		if($total_agreement_relation_res->num_rows>0)
		{
			while($fetch_agreement_relation_data = $total_agreement_relation_res->fetch_array())
			{
				$insert_archive_objective_agreement_relation = "insert into usaid_archive_objective_agreement_relation set 
				archive_id = '".$fetch_agreement_relation_data['id']."',
				objective_agreement_id = '".$archive_document_new_id."',
				relation_id='".$fetch_agreement_relation_data['relation_id']."'"; 
				$result_archive_objective_agreement_relation = $mysqli->query($insert_archive_objective_agreement_relation);
			}	 
		}
	## insert archive data into usaid_archive_objective_agreement_document table from usaid_objective_agreement_document table ==============
		$select_objective_agreement_document="select * from usaid_objective_agreement_document where objective_agreement_id='".$objective_agreement_id."'";
		$total_agreement_document_res = $mysqli->query($select_objective_agreement_document);
		if($total_agreement_document_res->num_rows>0)
		{
			while($fetch_agreement_document_data = $total_agreement_document_res->fetch_array())
			{
				$insert_archive_objective_agreement_document = "insert into usaid_archive_objective_agreement_document set 
					archive_id = '".$fetch_agreement_document_data['id']."',
					objective_agreement_id = '".$archive_document_new_id."',
					document_name='".$mysqli->real_escape_string($fetch_agreement_document_data['document_name'])."',
					document_path='".$fetch_agreement_document_data['document_path']."',
					document_tags='".$fetch_agreement_document_data['document_tags']."'"; 
				$result_archive_objective_agreement_document = $mysqli->query($insert_archive_objective_agreement_document);
			}	 
		}
	}
}
	
		
/*generate_filename*/
function generate_filename($filename){
	$new_filename = '';
	$explode = explode(".",$filename);
	$new_filename = $explode[0].strtotime("now").'.'.$explode[1];
	return $new_filename;
}

$error = '';
if(isset($_REQUEST['save_objective_agreement']))
{	
	$objective_agreement_type = $_REQUEST['objective_agreement_type'];
	$name = $mysqli->real_escape_string(trim($_REQUEST['name']));
	$description = $mysqli->real_escape_string(trim($_REQUEST['description']));
	$funding_estimate = str_replace(",","",$_REQUEST['funding_estimate']);
	$funding_estimate = str_replace("$","",$funding_estimate);
	$month = trim($_REQUEST['month']);
	$day = trim($_REQUEST['day']);
	$year = trim($_REQUEST['year']);
	$relation_id = $_REQUEST['relation_id']; // array of relation id  =====
	$tags =  $_REQUEST['tags'];// array of tags ====
	$document_name =  $_REQUEST['document_name']; // array of document name =====
	$document_file = $_FILES['document_file']; // array of document file ====
	
	$objective_agreement_id = $_REQUEST['objective_agreement_id']; 
	$document_id_arr = $_REQUEST['document_id_arr']; 
	
	$approved_date=$month.'/'.$day.'/'.$year;
	$approved_date_formate=date('Y-m-d',strtotime($approved_date));
	
	$folder="objective_agreement_document";
	@mkdir($folder,0777);
	
	
	if($objective_agreement_type=='')
	{
		$error="Please cheacked any one DOAG/SOAG";
	}
	if($operating_unit_id=='')
	{
		$error="Please select operating unit again";
	}
		$date= date("Y,m,d"); 
	if($error=='')
	{
		/* if document id is blank then insert */
		if($objective_agreement_id=='')
		{
				$insert_objective_agreement_data = "insert into usaid_objective_agreement set
					operating_unit_id = '".$operating_unit_id."',
					objective_agreement_type = '".$objective_agreement_type."',
					name = '".$name."',
					description = '".$description."',
					funding_estimate = '".$funding_estimate."',
					approved_date = '".$approved_date_formate."',
					added_on = '".$date."'";
				$result_data = $mysqli->query($insert_objective_agreement_data);
				$objective_agreement_new_id = $mysqli->insert_id; 
				if($result_data)
				{
					## for relation id instert==============
					for($i=0; $i<count($relation_id); $i++)
					{
						if(!empty($relation_id[$i]))
						{
							
							$insert_relation = "insert into usaid_objective_agreement_relation set 
									objective_agreement_id='".$objective_agreement_new_id."',
									relation_id='".$relation_id[$i]."'";  
							$result_relation = $mysqli->query($insert_relation);   	
						}
					}	
					## for document insert =======================
					for($i=0; $i<count($document_name); $i++)
					{
						if($document_name[$i]!='')
						{
							if($_FILES['document_file']['name'][$i]!='')
							{
							 $filename = $mysqli->real_escape_string(generate_filename($_FILES['document_file']['name'][$i])); 
								 $path="".$folder."/".$filename;
								@move_uploaded_file($_FILES['document_file']['tmp_name'][$i], $path);
							}
							$insert_document = "insert into usaid_objective_agreement_document set 
									objective_agreement_id='".$objective_agreement_new_id."',
									document_name='".$document_name[$i]."',
									document_path='".$path."',
									document_tags='".$tags[$i]."'";
							$result_document = $mysqli->query($insert_document);  
							$document_new_id = $mysqli->insert_id;
						}
					}	
				}
			}
			
			else
			{
				insertArchivedDoagSoag($objective_agreement_id); // function for insert archived data
				## for update objective agreement data========
			  $update_objective_agreement_data = "update usaid_objective_agreement set
					operating_unit_id = '".$operating_unit_id."',
					objective_agreement_type = '".$objective_agreement_type."',
					name = '".$name."',
					description = '".$description."',
					funding_estimate = ".$funding_estimate.",
					approved_date = '".$approved_date_formate."',
					modified_on = '".$date."'
					where id = '".$objective_agreement_id."'"; 
				$result_update_data = $mysqli->query($update_objective_agreement_data);
				$objective_agreement_new_id = $mysqli->insert_id; 
			
				if($result_update_data)
				{
					## for update relation of DO ==============
					$delete_objective_agreement_relation = "delete from usaid_objective_agreement_relation where objective_agreement_id='".$objective_agreement_id."'";
					$relation_data = $mysqli->query($delete_objective_agreement_relation);
					for($i=0; $i<count($relation_id); $i++)
					{
						if(!empty($relation_id[$i]))
						{
							$insert_relation = "insert into usaid_objective_agreement_relation set 
									objective_agreement_id='".$objective_agreement_id."',
									relation_id='".$relation_id[$i]."'";  
							$result_relation = $mysqli->query($insert_relation);   	
						}
					}	
					## for update documnts =======================
					$select_object_agreement_document="select * from usaid_objective_agreement_document where objective_agreement_id='".$fetch_object_agreement['id']."' limit 1";
					$result_document = $mysqli->query($select_object_agreement_document);
					while($fetch_document = $result_document->fetch_array())
					{
						if(!in_array($fetch_document['id'], $document_id_arr))
						{
							$delete_objective_agreement_document = "delete from usaid_objective_agreement_document where id='".$fetch_document['id']."'";
							$document_data = $mysqli->query($delete_objective_agreement_document);
						}
					}
						
					for($i=0; $i<count($document_name); $i++)
					{
						if($document_id_arr[$i]!='')
						{
							$update_document = "update usaid_objective_agreement_document set 
										objective_agreement_id='".$objective_agreement_id."',
										document_name='".$document_name[$i]."'";
										
								if($_FILES['document_file']['name'][$i]!='')
								{
								 $filename = $mysqli->real_escape_string(generate_filename($_FILES['document_file']['name'][$i])); 
								 $path="".$folder."/".$filename;
								 @move_uploaded_file($_FILES['document_file']['tmp_name'][$i], $path);
								 
								 $update_document.=", document_path='".$path."'";
								}
								
								$update_document.="where id='".$document_id_arr[$i]."'";		
								$result_update_document = $mysqli->query($update_document); 
						}
						else
						{
							if($_FILES['document_file']['name'][$i]!='')
							{
							 $filename = $mysqli->real_escape_string(generate_filename($_FILES['document_file']['name'][$i])); 
								 $path="".$folder."/".$filename;
								@move_uploaded_file($_FILES['document_file']['tmp_name'][$i], $path);
							}
							$insert_document = "insert into usaid_objective_agreement_document set 
									objective_agreement_id='".$objective_agreement_id."',
									document_name='".$document_name[$i]."',
									document_path='".$path."',
									document_tags='".$tags[$i]."'";
							$result_document = $mysqli->query($insert_document); 
						}	 
					}	
				}
			}	
	}
	header('location:go_to_doag.php');
}


if(!empty($_REQUEST['val'])=='new')
{
unset($_SESSION['objective_agreement_edit_id']);
}

if(!empty($_REQUEST['objective_agreement_edit_id']))
{
$_SESSION['objective_agreement_edit_id']=$_REQUEST['objective_agreement_edit_id'];
}

if($_SESSION['objective_agreement_edit_id']!='')
{

	$objective_agreement_edit_id=$_SESSION['objective_agreement_edit_id'];
	$select_object_agreement="select * from usaid_objective_agreement where id='".$objective_agreement_edit_id."'"; 
	$result_object_agreement = $mysqli->query($select_object_agreement);
	$fetch_object_agreement = $result_object_agreement->fetch_array();
	
	 $approved_date = $fetch_object_agreement['approved_date']; 
	 $date=explode("-", $approved_date);
	 $year = $date[0];
	 $month = $date[1];
	 $day = $date[2];
	 
	$select_relation="select * from usaid_objective_agreement_relation where objective_agreement_id='".$objective_agreement_edit_id."'";
	$result_relation = $mysqli->query($select_relation);
	$total_relation = $result_relation->num_rows;
	$i=0;
	while($fetch_relation = $result_relation->fetch_array())
	{	
		$relation_arr[$i] = $fetch_relation['relation_id']; $i++;
	}
	$select_document="select * from usaid_objective_agreement_document where objective_agreement_id='".$objective_agreement_edit_id."'";
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
				<div class="head">Operating Unit <span class="pull-right">(<a href=".">Back to Operating Unit</a>)</span></div>
			</div>
			<div class="row clear details">
				<div class="col-md-12"><a href="manage.php" class="remove-line"><?php echo $operating_unit_arr['data']['operating_unit_description'];?></a> >><a href="go_to_doag.php" class="remove-line"> DOAGs and SOAGs Management</a> >><?php if($fetch_object_agreement!='') echo " Edit"; else echo " Add"; ?></div> 
			</div>
		</div>
	</div>

	<!-- Frame Menu -->

	<div class="container-fluid">
		<div class="col-md-4 text-center">
			<a class="usa-button usa-button-outline-inverse active-class-white active" href="add_doag.php?val=new">Add a DOAG/SOAG</a>
		</div>
		<div class="col-md-4 text-center">
			<a class="usa-button usa-button-outline active-class-white disable active" href="go_to_doag.php">Go to DOAG & SOAG</a>
		</div>
		<div class="col-md-4 text-center">
			<a class="usa-button usa-button-outline active-class-white disable active" href="archived_doag.php">Archived DOAGs & SOAGs</a>
		</div>
	</div>

	<div class="container-fluid" >
		<form class="usa-form" method="post" action="" enctype="multipart/form-data" style="max-width:inherit">
		<div id="add_doag_blk" style="width:500px; margin:auto">
			<div style="padding:1px; text-align:center;"><h2 style="margin-top: 8px; margin-bottom: 3px;">Add a DOAG/SOAG</h2></div>
			<fieldset>
					<div class="form-group">
						<?php  if($fetch_object_agreement!=''){ $objective_agreement_id = $fetch_object_agreement['id'];}  ?>	
						<input type="hidden" name="objective_agreement_id" value="<?php echo $objective_agreement_id; ?>" >
						
						<?php  if($fetch_object_agreement!=''){ $objective_agreement_type = $fetch_object_agreement['objective_agreement_type'];}  ?>	
						<div style="display:inline-block; padding-right:5px;"><input name="objective_agreement_type" type="radio" value="DOAG" <?php if($objective_agreement_type=='DOAG') echo "checked"; ?>><label>DOAG</label></div>
						<div style="display:inline-block"><input name="objective_agreement_type" type="radio" value="SOAG" <?php if($objective_agreement_type=='SOAG') echo "checked"; ?>><label>SOAG</label></div>
					</div>
					<div class="form-group">
						<label for="input-type-text">Name</label>
						<?php  if($fetch_object_agreement!=''){ $name = $fetch_object_agreement['name'];}  ?>	
						<input name="name" value="<?php echo $name; ?>" type="text">
					</div>
					<div class="form-group">
						<label for="input-type-textarea">Description</label>
						<?php  if($fetch_object_agreement!=''){ $description = $fetch_object_agreement['description'];}  ?>	
						<textarea id="input-type-textarea" name="description"><?php echo $description; ?></textarea>
					</div>
					<div class="form-group">
						<label for="input-type-textarea">Estimate of Funding Needed</label>
						<?php  if($fetch_object_agreement!=''){ $funding = $fetch_object_agreement['funding_estimate'];}  ?>	
						<input name="funding_estimate" value="<?php echo $funding; ?>">
					</div>
					<div class="form-group">
						<label for="input-type-textarea">Date Approved</label>
						<div class="usa-date-of-birth">
							<div class="usa-form-group usa-form-group-month">
								<label for="date_of_birth_1">Month</label>
								<input class="usa-input-inline" aria-describedby="dobHint"  id="date_of_birth_1" name="month" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="<?php echo $month; ?>" placeholder="MM" style="font-size:13px">
							</div>
							<div class="usa-form-group usa-form-group-day">
								<label for="date_of_birth_2">Day</label>
								<input class="usa-input-inline" aria-describedby="dobHint" id="date_of_birth_2" name="day" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="<?php echo $day; ?>" placeholder="DD">
							</div>
							<div class="usa-form-group usa-form-group-year">
								<label for="date_of_birth_3">Year</label>
								<input class="usa-input-inline" aria-describedby="dobHint" id="date_of_birth_3" name="year" pattern="[0-9]{4}" type="number" min="1900" max="3000" value="<?php echo $year; ?>" placeholder="YYYY">
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
					<?php if($total_DO>0) { ?>
						<label for="input-type-textarea">Relationship to Developement Objectives</label>
						<select multiple="multiple" name="relation_id[]" class="SlectBox" >	
					<?php 
						 while($fetch_DO = $result_DO->fetch_array()) 
							{ 
							$do_id=$fetch_DO['id']; 
							 $objective_description=$fetch_DO['objective_description']; ?>
							<option value="<?php echo $do_id; ?>"<?php if(in_array($do_id, $relation_arr)) echo "selected"; ?>><?php echo $objective_description; ?></option>
					<?php   }  ?>
						</select>
					<?php } else { ?>
						<label for="input-type-textarea">No Active DOs</label>
					<?php } ?>	
					</div>
					<?php if($total_document>0) { 
					while($fetch_document = $result_document->fetch_array()) {?>
					<div class="add_doc_blk">
						<div class="close_btn disp_none"><i class="fa fa-times text-danger" title="Remove"></i></div>
						<?php if($fetch_document['id']!=""){?>
							<a href="<?php echo $fetch_document['document_path']; ?>" class="download_link pull-right" target="_blank"><img src="img/download-icon.png" title="<?php echo $fetch_document['document_name']; ?>"></a>
							<div class="clearfix"></div>
						<?php }?>
						<input type="hidden" name="document_id_arr[]" value="<?php echo $fetch_document['id']; ?>" >
						<div class="form-group">
							<span class="bold">Document Name</span>
							<input name="document_name[]" value="<?php echo $fetch_document['document_name']; ?>" type="text" />
						</div>
						
						<div class="form-group">
							<span class="bold">Document Tags</span>
							<input type="text" name="tags[]" value="<?php echo $fetch_document['document_tags']; ?>" class="tagsinput-typeahead">
						</div>
						
						<div class="form-group">
							<span class="bold">Document</span>
							<input name="document_file[]" type="file" />
							
						</div>
					</div>
					<?php } } else { ?>
					<div class="add_doc_blk">
						<div class="close_btn disp_none"><i class="fa fa-times text-danger" title="Remove"></i></div>
						<div class="form-group">
							<span class="bold">Document Name</span>
							<input name="document_name[]" type="text" />
						</div>
						
						<div class="form-group">
							<span class="bold">Document Tags</span>
							<input type="text" name="tags[]" class="tagsinput-typeahead">
						</div>
						
						<div class="form-group">
							<span class="bold">Document</span>
							<input name="document_file[]" type="file" />
						</div>
					</div>
					<?php } ?>
					<div> <button class="usa-button-gray pull-right add_document" type="button">Add Documents</button><div class="clearfix"></div></div>
					<button class="usa-button-outline" onClick="refreshPage();">Cancel</button>
					<?php 
					if($_SESSION['objective_agreement_edit_id']!='')
					{ ?>
					<input type="submit" name="save_objective_agreement" value="Save" style="display: inline-block; padding-left: 1.2em; padding-right: 1.2em;height: 3.7rem;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="hidden" name="archive_frame_id" value="<?php  echo $fetch_object_agreement['id']; ?>">
						<button name="move_archive" onClick="if(confirm('Are You Sure, You Want To Archive This Record ?')){ return true;} else { return false; }" style="display: inline-block; padding-left: 1.2em; padding-right: 1.2em;height: 3.7rem;">Move to Archive</button>
						
						<?php  
						## Checked for history ================
						$select_archives_objective_agreement="select * from usaid_archive_objective_agreement where objective_agreement_id = '".						$fetch_object_agreement['id']."'";
						$result_archives_objective_agreement = $mysqli->query($select_archives_objective_agreement);
						$total_num_row = $result_archives_objective_agreement->num_rows; 
						if($total_num_row>0) {?>
						<br><a href="doag_history_list.php?history_id=<?php echo $objective_agreement_edit_id; ?>">View History </a>
						<?php } ?>
				<?php } else {  ?>
				<input type="submit" name="save_objective_agreement" value="Save" style="display: inline-block; padding-left: 1.2em; padding-right: 1.2em;height: 3.7rem;">
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
			clone.find('.download_link').remove();
			clone.find('.bootstrap-tagsinput').remove();
			clone.find('.tagsinput-typeahead').css('display','block');
			clone.find('.close_btn').removeClass('disp_none');
			$(clone).insertAfter('.add_doc_blk:last');
		});
	});
	
	</script>
</body>
</html>