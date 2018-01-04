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

if(isset($_REQUEST['move_archive']))
	{	
		$date= date("Y,m,d"); 
		$archive_frame_id = trim($_REQUEST['archive_frame_id']); 
		if($archive_frame_id!='')
		{
			$updata_frame = "update usaid_objective_agreement set
			status = 'Archive', modified_on='".$date."' where id='".$archive_frame_id."'";
			$result_data = $mysqli->query($updata_frame);
			if($result_data)
			{
				header("location:archived_doag.php");
			}
			
		}
	}	

if(isset($_REQUEST['delete']))
	{	
		$objective_agreement_delete_id = trim($_REQUEST['objective_agreement_delete_id']); 
		if($objective_agreement_delete_id!='')
		{
			$delete_objective_agreement = "delete from usaid_objective_agreement where id='".$objective_agreement_delete_id."'";
			$result_data = $mysqli->query($delete_objective_agreement);
			
			$delete_objective_agreement_relation = "delete from usaid_objective_agreement_relation where objective_agreement_id='".$objective_agreement_delete_id."'";
			$relation_data = $mysqli->query($delete_objective_agreement_relation);
			
			## delete the DOs file form folder ================
			$select_object_agreement_document="select * from usaid_objective_agreement_document where objective_agreement_id='".$objective_agreement_delete_id."'";
			$result_document = $mysqli->query($select_object_agreement_document);
			while($fetch_document = $result_document->fetch_array())
			{
			 $document_path = $fetch_document['document_path']; 
				@unlink($document_path); 
			}
			$delete_objective_agreement_document = "delete from usaid_objective_agreement_document where objective_agreement_id='".$objective_agreement_delete_id."'";
			$document_data = $mysqli->query($delete_objective_agreement_document);
			
		}
	}	
	
## Get detail for DOAG SOAG ============
$select_object_agreement="select * from usaid_objective_agreement where operating_unit_id='".$operating_unit_id."' and status='Active'";
$result_object_agreement = $mysqli->query($select_object_agreement);
$total_count = $result_object_agreement->num_rows;

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
	<link href="css/uswds.min.css" rel="stylesheet">
	<link href="css/responsive.dataTables.min.css" type="text/css" rel="stylesheet">
	<script>
	/*	function showDoc()
		{
		alert(" ok thanks ");
		doc_file
		document.getElementbyid
		} */
	</script>
	<style>
	.disp-none{
		display:none;
	}
</style>
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
				<div class="col-md-12"><a href="manage.php" class="remove-line"><?php echo $operating_unit_arr['data']['operating_unit_description'];?></a> >><a href="go_to_doag.php" class="remove-line"> DOAGs and SOAGs</a> >> List </div>
			</div>
		</div>
	</div>
	

	<div class="container-fluid">
		<div class="col-md-4 text-center">
			<a class="usa-button usa-button-outline active-class-white disable active" href="add_doag.php?val=new">Add a DOAG/SOAG</a>
		</div>
		<div class="col-md-4 text-center">
			<a class="usa-button usa-button-outline-inverse active-class-white active" href="go_to_doag.php">Go to DOAG & SOAG</a>
		</div>
		<div class="col-md-4 text-center">
			<a class="usa-button usa-button-outline active-class-white disable active" href="archived_doag.php">Archived DOAGs & SOAGs</a>
		</div>
	</div>

	<!-- Diaplay Table DOAGs -->

	<div class="container-fluid">
		<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<td colspan="10" style="background:white;"> List of DOAGs and SOAGs </td>
				</tr>
				<tr>
					<th class="text-center">ID</th>
					<th class="text-center">Type</th>
					<th class="text-center">Name</th>
					<th class="text-center">Description</th>
					<th class="text-center" width="250">Estimated Funding Needed</th>
					<th class="text-center" width="150">Date Approved</th>
					<th class="text-center" width="250">Document</th>
					<th class="text-center" width="200"></th>
					<th class="text-center">History</th> 
					<th width="200">Action</th>
				</tr>
			</thead>
			<tbody>
			<?php if($total_count>0) {
			while($fetch_object_agreement = $result_object_agreement->fetch_array())
			{
			 ?>
				<tr class="parent-link" height="5" style="padding:3px; !important">
					<td valign="top"><?php echo $fetch_object_agreement['id'];?></td>
					<td valign="top"><?php echo $fetch_object_agreement['objective_agreement_type'];?></td>
					<td valign="top"><?php echo ucwords($fetch_object_agreement['name']);?></td>
					<td valign="top"><?php echo $fetch_object_agreement['description'];?></td>
					<td valign="top"><?php if($fetch_object_agreement['funding_estimate']>0) { echo "$".number_format($fetch_object_agreement['funding_estimate']); }?></td>
					<td valign="top"><?php echo dateFormatView($fetch_object_agreement['approved_date']);?></td>
					<td valign="top">
					<?php $select_object_agreement_document="select document_name, document_path from usaid_objective_agreement_document where objective_agreement_id='".						$fetch_object_agreement['id']."'";
						$result_document = $mysqli->query($select_object_agreement_document);
						$total_document = $result_document->num_rows;
						if($total_document>0) { ?>
					 <?php if($total_document==1) echo $total_document." "."Document"; else echo $total_document." "."Documents";  ?>  <i style="cursor:pointer" class="fa fa-chevron-circle-down" aria-hidden="true" onClick="showDoc(this)" ></i> 
						<?php } else { echo "No Document"; } ?>
					<table class="doc_tbl disp-none" style="margin:0;">
							<?php 
							  $result_document = $mysqli->query($select_object_agreement_document);
							  $total_document = $result_document->num_rows;
							  if($total_document>0) {
								 while($fetch_document = $result_document->fetch_array()) { ?>
								<tr>										
									<td style="border:0 !important"><?php echo ucfirst($fetch_document['document_name']); ?></td>
									<td style="border:0 !important"> <?php if($fetch_document['document_path']!=''){ ?> <a href="<?php echo $fetch_document['document_path']; ?>" download="<?php echo $fetch_document['document_path']; ?>"> <img src="img/download-icon.png" title="<?php $explode = explode("/",$fetch_document['document_path']); echo $explode[1];  ?>"></a> <?php } else { echo "No file"; } ?></td>
								</tr>
							<?php }	} else
									 {
										echo '<tr><td colspan="2">No Data Available</td></tr>'; 
									 }?>
									
						</table>
						</td>
					<td valign="top"><form method="post">
					<input type="hidden" name="archive_frame_id" value="<?php  echo $fetch_object_agreement['id']; ?>">
						<button name="move_archive" onClick="if(confirm('Are You Sure, You Want To Archive This Record ?')){ return true;} else { return false; }" style="display: inline-block; padding-left: 1.2em; padding-right: 1.2em;height: 3.7rem; font-size:1.2rem;">Move to Archive</button>
				</form></td>
					<td><?php  $select_archives_objective_agreement="select * from usaid_archive_objective_agreement where objective_agreement_id = '".				$fetch_object_agreement['id']."' order by archived_on desc";
	$result_archives_objective_agreement = $mysqli->query($select_archives_objective_agreement);
	$total_num_row = $result_archives_objective_agreement->num_rows; if($total_num_row>0) {?>
					<a href="doag_history_list.php?history_id=<?php echo $fetch_object_agreement['id']; ?>">View History </a> <?php } else echo "No History"; ?></td> 
					<td valign="top">  
						<form method="get" action="add_doag.php" style="display:inline">
						<input type="hidden" name="objective_agreement_edit_id" value="<?php  echo $fetch_object_agreement['id']; ?>">
						<button name="edit"  class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></button>
						</form>
					
						<form method="post" action="" style="display:inline">
						<input type="hidden" name="objective_agreement_delete_id" value="<?php  echo $fetch_object_agreement['id']; ?>">
						<button name="delete" class="btn btn-danger" onClick="if(confirm('Are you sure, you want to delete this record?')){ return true;} else { return false; }"><i class="fa fa-trash" aria-hidden="true"></i></button>
						</form>
						
					</td>							
				</tr>
			
			<?php } } else { ?>
				<tr>
					<td colspan="10">No Record Available</td>					
				</tr>
				<?php } ?>
				
			</tbody>
		</table>
	</div>
	<script>
	function showDoc(elem){
		$(elem).next('.doc_tbl').toggleClass('disp-none');
	}
	</script>
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script src="js/uswds.min.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.responsive.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript">	
		$(document).ready(function() {
			$('#manage-table').DataTable({"lengthMenu": [ 10, 20, 30, 40 ]});
		});	
	</script>
	
</body>
</html>