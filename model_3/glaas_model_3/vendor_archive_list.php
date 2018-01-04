<?php
include('config/config.inc.php');
include('include/function.inc.php');

## Archive award requisition id for get archive details================
if($_REQUEST['archive_vendor_id']!='')
{
	$archive_vendor_id = $_REQUEST['archive_vendor_id'];
	$_SESSION['archive_vendor_id'] = $archive_vendor_id;
}
else
{
	$archive_vendor_id = $_SESSION['archive_vendor_id'];
}

## get all archive vendor list===============
$url = API_HOST_URL."get_all_archive_vendor.php?vendor_id=".$archive_vendor_id;
$archive_vendor_arr = requestByCURL($url); 
//echo "<pre>";
//print_r($archive_vendor_arr);exit
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Basic Page Needs
	================================================== -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Created By Abhilash">
	<title>USAID - GLAAS</title>
	<!-- <link rel="shortcut icon" type="image/x-icon" href="images/hr-logo.gif" /> -->	
	
    <!-- CSS
    ================================================== -->
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link href="css/font-awesome.min.css" type="text/css" rel="stylesheet"> 
    <style>
    	.disp-none{
    		display: none;
    	}
		.btnstyle{
padding-top:20px;
}
.tablegap {
    margin: 10px;
    border: 1px solid gray;
    padding: 5px;
}
.pointer{
cursor:pointer;}
#manage-vendor .new-form-content .manage-info {
    padding: 7px;
}
    </style>   
</head>
<body>
	<!-- Header Include Here -->
	<?php include 'include/header.html'; ?>

	<div class="top-menu">
		<ul class="list-inline">
			<li><a class="menu-active" href="vendor_management.php">Vendor Management</a></li>
			<li><a href="requisition.php">Requisition for Supplies or Services</a></li>
			<li><a href="award_instrument.php">Award / Modifications of Instrument</a></li>
		</ul>
	</div>
	<div class="container-fluid" id="form-requisiton">
	    <div class="row">
			<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 text-right">
			 <h2><?php echo $archive_vendor_arr['data'][0]['name']; ?></h2>
			</div>
			 <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-right btnstyle">
				<a href="vendor_management.php"><button type="button" class="btn btn-primary back_button">Back to Vendor</button></a>
			 </div>
		 </div>
 		 <table id="projects_table " class="display table table-bordered table-striped " cellspacing="0" width="100%">
			 <thead>
					<tr>
						<th class="text-center comm-width">Archive On</th>
						<th class="text-center comm-width">View</th>
					</tr>
			  </thead>
				    <tbody>
					<?php
					if(count($archive_vendor_arr['data'])>0)
					{
						for($k=0; $k<count($archive_vendor_arr['data']); $k++)
						{  ?>
						<tr>
							<td class="text-center"><?php echo dateTimeFormat($archive_vendor_arr['data'][$k]['archive_on']); ?></td>
							<td class="text-center text-center"><i class="fa fa-chevron-circle-down pointer show_table" aria-hidden="true"></i></td>
						</tr>
						<tr class="disp-none">
							<td colspan="3">
								 <div class="tablegap">
									<div class="container-fluid" id="manage-vendor">
										<div class="form-title">
											<i class="fa fa-minus" aria-hidden="true"></i>Vendor
										</div>
										<div class="new-form-content">
											<div class="manage-info">
													<form class="form-horizontal" role="form" method="post" action="">
														<div class="form-group">
															<label for="vendor_duns_numeber" class="col-md-3 control-label">Vendor DUNS Number:</label>
															<div class="col-md-3">
																<input type="text" class="form-control" name="DUNS_number" value="<?php echo $archive_vendor_arr['data'][$k]['DUNS_number']; ?>" readonly="">
															</div>
														</div>
														<div class="form-group">
															<label for="vendor_name" class="col-md-3 control-label">Vendor Name:</label>
															<div class="col-md-3">
																<input type="text" class="form-control" name="name" value="<?php echo $archive_vendor_arr['data'][$k]['name']; ?>" readonly="">
															</div>
														</div>
														<div class="form-group">
															<label for="vendor_address_street" class="col-md-3 control-label">Vendor Address Street:</label>
															<div class="col-md-3">
																<input type="text" class="form-control" name="address_street" value="<?php echo $archive_vendor_arr['data'][$k]['address_street']; ?>" readonly="">
															</div>
														</div>
														<div class="form-group">
															<label for="vendor_address_city" class="col-md-3 control-label">Vendor Address City:</label>
															<div class="col-md-3">
																<input type="text" class="form-control" name="address_city" value="<?php echo $archive_vendor_arr['data'][$k]['address_city']; ?>" readonly="">
															</div>
														</div>
														<div class="form-group">
															<label for="vendor_address_state" class="col-md-3 control-label">Vendor Address State/Province:</label>
															<div class="col-md-3">
																<input type="text" class="form-control" name="address_state_province" value="<?php echo $archive_vendor_arr['data'][$k]['address_state_province']; ?>" readonly="">
															</div>
														</div>
														<div class="form-group">
															<label for="vendor_address_country" class="col-md-3 control-label">Vendor Address Country:</label>
															<div class="col-md-3">
																<input type="text" class="form-control" name="address_country" value="<?php echo $archive_vendor_arr['data'][$k]['address_country']; ?>" readonly="">
															</div>
														</div>
														<div class="form-group">
															<label for="vendor_address_postal" class="col-md-3 control-label">Vendor Address Location or Postal Code:</label>
															<div class="col-md-3">
																<input type="text" class="form-control" name="address_location_code" value="<?php echo $archive_vendor_arr['data'][$k]['address_location_code']; ?>" readonly="">
															</div>
														</div>
														<div class="form-group">
															<label for="vendor_contact_name" class="col-md-3 control-label">Vendor Contact Name:</label>
															<div class="col-md-3">
																<input type="text" class="form-control" name="contact_name" value="<?php echo $archive_vendor_arr['data'][$k]['contact_name']; ?>" readonly="">
															</div>
														</div>
														<div class="form-group">
															<label for="vendor_email" class="col-md-3 control-label">Vendor Email Address:</label>
															<div class="col-md-3">
																<input type="text" class="form-control" name="email_address" value="<?php echo $archive_vendor_arr['data'][$k]['email_address']; ?>" readonly="">
															</div>
														</div>
														<div class="form-group">
															<label for="vendor_phone" class="col-md-3 control-label">Vendor Phone Number:</label>
															<div class="col-md-3">
																<input type="text" class="form-control" name="phone_number" value="<?php echo $archive_vendor_arr['data'][$k]['phone_number']; ?>" readonly="">
															</div>
														</div>
														<div class="form-group">
															<label for="vendor_deposit" class="col-md-3 control-label">Vendor Direct Deposit Number:</label>
															<div class="col-md-3">
															<input type="text" class="form-control" name="direct_deposit_number" value="<?php echo $archive_vendor_arr['data'][$k]['direct_deposit_number']; ?>" readonly="">
															</div>
														</div>
														<h3 class="text-center"><b>Local Address</b></h3>
														<table id="local_vendor" class="table table-striped table-bordered" cellspacing="0" width="100%">
															<thead>
																<tr>
																	<th class="text-center">Contact Name</th>
																	<th class="text-center">Contact Email</th>
																	<th class="text-center">Phone Number</th>
																	<th class="text-center">Street Address</th>
																	<th class="text-center">Address City</th>
																	<th class="text-center">State/Province</th>
																	<th class="text-center">Country</th>
																	<th class="text-center">Address Location Code</th>
																</tr>
															</thead>
															<tbody id="append_here">
															<?php 
														$url = API_HOST_URL."get_all_archive_vendor_local_address.php?archive_vendor_id=".$archive_vendor_arr['data'][$k]['id'];
														$archive_vendor_local_add_arr = requestByCURL($url);	
															if(count($archive_vendor_local_add_arr['data'])>0)
															{
																for($j=0; $j<count($archive_vendor_local_add_arr['data']); $j++)
																{  ?>
																<tr class="vendor-info">
																	<td><?php echo $archive_vendor_local_add_arr['data'][$j]['local_contact_name']; ?></td>
																	<td><?php echo $archive_vendor_local_add_arr['data'][$j]['local_contact_email']; ?></td>
																	<td><?php echo $archive_vendor_local_add_arr['data'][$j]['local_phone_number']; ?></td>
																	<td><?php echo $archive_vendor_local_add_arr['data'][$j]['local_address_street']; ?></td>
																	<td><?php echo $archive_vendor_local_add_arr['data'][$j]['local_address_city']; ?></td>
																	<td><?php echo $archive_vendor_local_add_arr['data'][$j]['local_address_state_province']; ?></td>
																	<td><?php echo $archive_vendor_local_add_arr['data'][$j]['local_address_country']; ?></td>
																	<td><?php echo $archive_vendor_local_add_arr['data'][$j]['local_address_location_code']; ?></td>
																</tr>
														<?php   } 
															} else {?>
																<tr class="vendor-info">
																	<td align="center" colspan="8">No Local Address</td>
																</tr>	
															<?php } ?>
																
														</tbody>
													</table>	
												</form>
											</div>
										</div>
									 </div>
								  </div>
							</td>
						</tr>
				<?php  	}	
					} else { ?>
						<tr>
							<td colspan="2" align="center">No Archive Data </td>
						</tr>
					<?php }?>	
						
					</tbody>
		  </table>
			
    </div>
<script src="js/jquery-2.1.4.min.js"></script>
<script>
	$('.fa-plus').css('display','none');
</script>
<script>
$(document).ready(function(){
	$('.show_table').click(function() {
	//console.log("sdfsdf");
	$(this).closest("tr").next().toggleClass("disp-none");
    if ($(this).hasClass('fa-chevron-circle-down')){
        $(this).removeClass('fa-chevron-circle-down').addClass('fa-chevron-circle-up');
    }
	 else {
         $(this).addClass('fa-chevron-circle-down').removeClass('fa-chevron-circle-up');
      }
});
});
</script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>