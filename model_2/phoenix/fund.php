<?php
include('config/config.inc.php');
include('include/function.inc.php');
$page_name = "fund";
$error = '';
function checkFundIDExist($fund_id, $id='')
{
	global $mysqli;
	$cond = '';
	if($id!='')
	{
		$cond = " and id!='".$id."'";
	}
	$select_id = "select fund_id from usaid_fund where fund_id = '".$fund_id."'".$cond;
	$result_id = $mysqli->query($select_id);
	$row = $result_id->num_rows;
	if($row<1) return true;
	else false;
}

## function for check exist fund in transaction or not=====
function checkExistInTransaction($fund_id)
{
	global $mysqli;
	$select_tran = "select transaction_id from usaid_fund_transaction_detail where ledger_type_id='".$fund_id."'";
	$result_tran = $mysqli->query($select_tran) or die('Error'. $mysqli->error);
	$total_record = $result_tran->num_rows;
	if($total_record<1) return true;
	else return false;
}

if(isset($_REQUEST['add_fund']))
{
	$id = trim($_REQUEST['id']);
	$fund_category = $mysqli->real_escape_string(trim($_REQUEST['fund_category']));
	$fund_id = trim($_REQUEST['fund_id']);
	$fund_name = $mysqli->real_escape_string(trim($_REQUEST['fund_name']));

	if($fund_category=='')
	{
	 	$error = "Please select fund category";
	}
	elseif($fund_name=='')
	{
	 	$error = "Please enter fund name";
	}
	elseif($fund_id=='')
	{
	 	$error = "Please enter fund ID";
	}
	else
	{
		if($id=='')
		{
			if(checkFundIDExist($fund_id)==true)
			{
				$insert_fund_category = "insert into usaid_fund set
		 				fund_id = '".$fund_id."',
		 				fund_name = '".$fund_name."',
		 				fund_category = '".$fund_category."'";
			 	$result_fund_category = $mysqli->query($insert_fund_category);
			 	if($result_fund_category)
			 	{
			 		header("location:fund.php");
			 	}
			}else $error = "Fund id already exist please change id";
		 	
		 }
		 else
		 {
		 	if(checkFundIDExist($fund_id,$id)==true)
			{
			 	$update_fund_category = "update usaid_fund set
			 	            fund_id = '".$fund_id."',
			 				fund_name = '".$fund_name."',
			 				fund_category = '".$fund_category."' where id='".$id."'";
			 	$result_fund_category = $mysqli->query($update_fund_category);
			 	if($result_fund_category)
			 	{
			 		header("location:fund.php");
			 	}
			 }else $error = "Fund id already exist please change Fund id"; 
		 }
	 }
}

###code for delete fund ==========
if(isset($_REQUEST['delete_fund']))
{
	$id_fund = $_REQUEST['id_fund'];
	if(checkExistInTransaction($id_fund))
	{
		$delete_fund = "delete from usaid_fund where fund_id='".$id_fund."'";
		$res_delete = $mysqli->query($delete_fund);
		if($res_delete)
		{
			header("location:fund.php");
		}
	}
	else
	{
		$error = "The fund can not be deleted. It has a fiscal year or money associated with it.";
	}
}

## API for get all fund list========
$url = API_HOST_URL."get_all_fund.php";
$fund_all_arr = requestByCURL($url);
  
if(isset($_REQUEST['edit_fund']))
{
	$url = API_HOST_URL."get_fund.php?fund_id=".$_REQUEST['id_fund']."";  
	$fund_arr = requestByCURL($url);
}
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>USAID - Phoenix</title>
	<!-- <link rel="shortcut icon" type="image/x-icon" href="images/hr-logo.gif" />	 -->
	
    <!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- CSS
  ================================================== -->
 	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<link rel="stylesheet" href="css/bootstrap-select.css"> 
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css"> 
	<link href="css/font-awesome.min.css" type="text/css" rel="stylesheet"> 
	<link href="css/style.css" type="text/css" rel="stylesheet">
	 
</head>
<body>
<!-- help popup section end-->
 <?php include ('include/help_popup.php'); ?>
<!-- help popup section end-->
	<!-- Header -->
	
	<?php include 'header.html'; ?>

	<!---  / Header - -->

	<!-- Breadcrumbs -->

	<ol class="breadcrumb">
  		<li><a href="#">Site Map</a></li>
  		<li><a href="index.php">Phoenix</a></li>
  		<li class="active">Manage Funds</li>
	</ol>

	<!-- / Breadcrumbs  -->

	<!-- Pop Up Div Menu Nav -->

	<div class="menu-nav">
		<ul class="nav navbar-nav navbar">
			<li><a href="fund.php" class="btn btn-default">Fund</a></li>
			<li><a href="fund_time_period.php" class="btn btn-default active">Fund Strip</a></li>
			<li><a href="fund_strip_program_element.php" class="btn btn-default active">Fund Strip + Program Element</a></li>
			<li><a href="allow_to_bureau.php" class="btn btn-default active">Allot to Bureau</a></li>
			<li><a href="allow_to_operating_unit.php" class="btn btn-default active">Allow to OU</a></li>
		</ul>
	</div> 
	<!-- Main Manage Fund Content Goes Here --> 
	<div class="container-fluid manage-form">
	<!--help icon section-->
     <!-- <div class="col-sm-offset-9 col-sm-3 text-right" style="float:right;height:20px;"><img src="images/information.png" class="pointer_help" title="Click to view help to add new Fund"></div>-->
  	<!-- help icon section end-->
		<div class="form-title">
			<i class="fa fa-minus" aria-hidden="true"></i><i class="fa fa-plus" aria-hidden="true"></i>Fund
		</div>
		<div class="new-form-content">
			<?php
			if($error!='')
			{
				echo '<div style="text-align:center;color:red;">'.$error.'</div>';
			}
			?>
			<div class="manage-funds"> 
				<form class="form-horizontal" role="form" method="post" action=""> 
					<?php
					$id = '';
					if(isset($_REQUEST['id'])) $id = $_REQUEST['id'];
					if(isset($fund_arr)) $id = $fund_arr['data']['id'];
					?>
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<div class="form-group">
						<label class="col-sm-4" for="Fund Category">Fund Category:</label>
						<div class="col-sm-8">
						<?php
						$fund_category = '';
						if(isset($_REQUEST['fund_category'])) $fund_category = $_REQUEST['fund_category'];
						if(isset($fund_arr)) $fund_category = $fund_arr['data']['fund_category'];
						?>	
							<select name="fund_category" class="form-control">
								<option value=''>Select</option>
								<option value='Core USAID Funds/Accounts' <?php if($fund_category=='Core USAID Funds/Accounts')echo 'selected="selected"'; ?>>Core USAID Funds/Accounts</option>
								<option value='Partially Managed USAID Funds/Accounts' <?php if($fund_category=='Partially Managed USAID Funds/Accounts')echo 'selected="selected"'; ?>>Partially Managed USAID Funds/Accounts</option>
								<option value='Others' <?php if($fund_category=='Others')echo 'selected="selected"'; ?>>Others</option>
							</select> 
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4" for="Fund Name">Fund Name:</label>
						<div class="col-sm-8">
						<?php
						$fund_name = '';
						if(isset($_REQUEST['fund_name'])) $fund_name = $_REQUEST['fund_name'];
						if(isset($fund_arr)) $fund_name = $fund_arr['data']['fund_name'];
						?>	
							<input type="text" class="form-control" name="fund_name" id="fund_name" placeholder="Fund Name" value="<?php echo $fund_name; ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4" for="Fund Name">Fund ID:</label>
						<div class="col-sm-8">
						<?php
						$fund_id= '';
						if(isset($_REQUEST['fund_id'])) $fund_id = $_REQUEST['fund_id'];
						if(isset($fund_arr)) $fund_id = $fund_arr['data']['fund_id'];
						?>
							<input type="text" class="form-control" name="fund_id" id="fund_id" placeholder="Fund ID" value="<?php echo $fund_id;?>">
						</div>
					</div> 
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-8">
							<a href="fund.php" class="btn btn-default" style="margin-right:20px">Cancel</a>
							<button type="submit" class="btn btn-default" name="add_fund">Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Manage Funds Content Ends Here -->

	<!-- Data Display Here -->
	<div class="data-display container-fluid">
   <!-- <div class="col-sm-offset-9 col-sm-3 text-right" style="float:right;height:20px;margin-bottom:10px"><img src="images/information.png" class="pointer_help2" title="Click to view help to know about list"></div>-->
		<table id="manage-table" class="table table-striped table-bordered clearfix" cellspacing="0" width="100%">
			<thead>
	            <tr> 
	                <th>Fund Name</th>
                        <th>Fund ID</th>
                         <th>Fund Category</th>                 
	                <th width="150px;">Action</th>
	            </tr>
	        </thead>
	        <tbody>
	        <?php
	        for($count_cat=0; $count_cat<count($fund_all_arr['data']); $count_cat++)
	        { 
	        ?>
	            <tr>
                    <td><?php echo $fund_all_arr['data'][$count_cat]['fund_name'];?></td> 
	            	<td><?php echo $fund_all_arr['data'][$count_cat]['fund_id'];?></td>
	            	<td><?php echo $fund_all_arr['data'][$count_cat]['fund_category'];?></td>
	            	      	
	            	<td style="text-align:center">
	            		<form action="" method="post">
	            			<input type="hidden" name="id_fund" value="<?php echo $fund_all_arr['data'][$count_cat]['fund_id'];?>">
	            			<input type="submit" name="edit_fund" value="Edit" class="btn btn-default" style="margin-right:10px;">
	            			<input type="submit" name="delete_fund" value="Delete" class="btn btn-default" onclick="return confirm('Are you sure you want to Delete?');">
	            		</form>
	            	</td>		
	            </tr>
	        <?php
	    	} ?> 
	        </tbody>
		</table>
	</div>
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/main.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	 
		<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-select.js"></script>
	<script>
    $(document).ready(function(){
		$('.pointer_help').click(function(){
			$(this).hide();
			$('.fund-help-popup').show();
			});
			
			$('.pointer_help2').click(function(){
			$(this).hide();
			console.log('fsdfdsf');
			$('.fund-help-popup2').show();
			});
			
			$('.cancel-help').click(function(){
				$('.fund-help-popup').hide();
				$('.pointer_help').show();
				
				});
				
				 $('.cancel-help2').click(function(){
				$('.fund-help-popup2').hide();
				$('.pointer_help2').show();
				
				});
					$('body').click(function() {
						if (!$(event.target).is('.pointer_help,.pointer_help2')){
							$("body").find(".fund-help-popup,.fund-help-popup2").hide();
							$('.pointer_help,.pointer_help2').show();
						}
           
		             });
					 
					 $(".fund-help-popup,.fund-help-popup2").click(function(e) {
						e.stopPropagation(); // This is the preferred method.
						return false;        // This should not be used unless you do not want
											 // any click events registering inside the div
					});
		});
			
		
    </script>
</body>
</html>