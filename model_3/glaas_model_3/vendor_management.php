<?php
include('config/config.inc.php');
include('include/function.inc.php');
unset($_SESSION['archive_vendor_id']);
##	Created archived list after save frame stracture ===========
function insertArchiveVendorManagement($vendor_id)
{
	global $mysqli;
	if($vendor_id!='')
	{
		$url = API_HOST_URL."get_vendor.php?vendor_id=".$vendor_id;
		$vendor_arr = requestByCURL($url);
		if(count($vendor_arr['data'])>0)
		{
			$insert_archive_vendor = "insert into usaid_archive_vendor set
				 vendor_id='".$vendor_arr['data']['vendor_id']."',
				 DUNS_number='".$vendor_arr['data']['DUNS_number']."',
				 name='".$mysqli->real_escape_string($vendor_arr['data']['name'])."',
				 address_street='".$mysqli->real_escape_string($vendor_arr['data']['address_street'])."',
				 address_city='".$mysqli->real_escape_string($vendor_arr['data']['address_city'])."',
				 address_state_province='".$mysqli->real_escape_string($vendor_arr['data']['address_state_province'])."',
				 address_country='".$mysqli->real_escape_string($vendor_arr['data']['address_country'])."',
				 address_location_code='".$vendor_arr['data']['address_location_code']."',
				 contact_name='".$mysqli->real_escape_string($vendor_arr['data']['contact_name'])."',
				 email_address='".$mysqli->real_escape_string($vendor_arr['data']['email_address'])."',
				 phone_number='".$vendor_arr['data']['phone_number']."',
				 direct_deposit_number='".$vendor_arr['data']['direct_deposit_number']."'";
			$result_archive_vendor = $mysqli->query($insert_archive_vendor);
			$archive_id = $mysqli->insert_id;
			
				if($result_archive_vendor)
				{
						##get all local address of this vendor===========
						$url = API_HOST_URL."get_vendor_local_address.php?vendor_id=".$vendor_id;
						$local_address_arr = requestByCURL($url); 
					if(count($local_address_arr['data'])>0)
					{
						for($i=0; $i<count($local_address_arr['data']); $i++)
						{
							$insert_archive_vendor_local_address = "insert into usaid_archive_vendor_local_address set
								 archive_vendor_id='".$archive_id."',
								 local_contact_name='".$local_address_arr['data'][$i]['local_contact_name']."',
								 local_contact_email='".$local_address_arr['data'][$i]['local_contact_email']."',
								 local_phone_number='".$local_address_arr['data'][$i]['local_phone_number']."',
								 local_address_street='".$local_address_arr['data'][$i]['local_address_street']."',
								 local_address_city='".$local_address_arr['data'][$i]['local_address_city']."',
								 local_address_state_province='".$local_address_arr['data'][$i]['local_address_state_province']."',
								 local_address_country='".$local_address_arr['data'][$i]['local_address_country']."',
								 local_address_location_code='".$local_address_arr['data'][$i]['local_address_location_code']."'";
							$result_archive_vendor_local_address = $mysqli->query($insert_archive_vendor_local_address);
						}
					}
				}
		}
	}
}	

## add new vendor wither thier address ============
if(isset($_REQUEST['vender_management']))
{
	$DUNS_number = trim($_REQUEST['DUNS_number']); 
	$vendor_id = trim($_REQUEST['edit_vendor_id']);
	$name = $mysqli->real_escape_string(trim($_REQUEST['name']));
	$address_street = $mysqli->real_escape_string(trim($_REQUEST['address_street']));
	$address_city = $mysqli->real_escape_string(trim($_REQUEST['address_city']));
	$address_state_province = $mysqli->real_escape_string(trim($_REQUEST['address_state_province']));
	$address_country = $mysqli->real_escape_string(trim($_REQUEST['address_country']));
	$address_location_code = trim($_REQUEST['address_location_code']);
	$contact_name = $mysqli->real_escape_string(trim($_REQUEST['contact_name']));
	$email_address = $mysqli->real_escape_string(trim($_REQUEST['email_address']));
	$phone_number = trim($_REQUEST['phone_number']);
	$direct_deposit_number = trim($_REQUEST['direct_deposit_number']);
	
	##get vendor local address input==============
	$local_contact_name_arr = $_REQUEST['local_contact_name'];
	$local_contact_email_arr = $_REQUEST['local_contact_email'];  
	$local_phone_number_arr = $_REQUEST['local_phone_number']; 
	$local_address_street_arr = $_REQUEST['local_address_street']; 
	$local_address_city_arr = $_REQUEST['local_address_city'];
	$local_address_state_province_arr = $_REQUEST['local_address_state_province'];
	$local_address_country_arr = $_REQUEST['local_address_country'];
	$local_address_location_code_arr = $_REQUEST['local_address_location_code'];
	$local_address_id_arr = $_REQUEST['local_address_id_arr'];        

	$error='';
	if($DUNS_number=='')
	{
		$error = "Please input vendor DUNS number";
	}
	if(!is_numeric($DUNS_number))
	{
		$error = "Please input vendor DUNS number numeric only";
	}
	elseif($name=='')
	{
		$error = "Please input vendor name";
	}
	else
	{
		if($vendor_id=='')
		{
			$insert_vendor = "insert into usaid_vendor set DUNS_number='".$DUNS_number."', name='".$name."', address_street='".$address_street."', address_city='".$address_city."', address_state_province='".$address_state_province."', address_country='".$address_country."', address_location_code='".$address_location_code."', contact_name='".$contact_name."', email_address='".$email_address."', phone_number='".$phone_number."', direct_deposit_number='".$direct_deposit_number."'";
			$result_vendor = $mysqli->query($insert_vendor);
			$vendor_id_new = $mysqli->insert_id;
			if($result_vendor)
			{
				for($i=0; $i<count($local_contact_name_arr); $i++)
				{
					if(!empty($local_contact_name_arr[$i]))
					{
						$insert_local_address = "insert into usaid_vendor_local_address set 
						vendor_id='".$vendor_id_new."',
						local_contact_name='".$local_contact_name_arr[$i]."',
						local_contact_email='".$local_contact_email_arr[$i]."', 
						local_phone_number='".$local_phone_number_arr[$i]."',
						local_address_street='".$local_address_street_arr[$i]."',
						local_address_city='".$local_address_city_arr[$i]."',
						local_address_state_province='".$local_address_state_province_arr[$i]."',
						local_address_country='".$local_address_country_arr[$i]."',
						local_address_location_code='".$local_address_location_code_arr[$i]."'
						";
						$result_local_address = $mysqli->query($insert_local_address); 	
					}
				}
				header("location:vendor_management.php?succ=1"); 
			}
		}
		else
		{
			insertArchiveVendorManagement($vendor_id);  // call for insert archive vendor management data
			$update_vendor = "update usaid_vendor set DUNS_number='".$DUNS_number."', name='".$name."', address_street='".$address_street."', address_city='".$address_city."', address_state_province='".$address_state_province."', address_country='".$address_country."', address_location_code='".$address_location_code."', contact_name='".$contact_name."', email_address='".$email_address."', phone_number='".$phone_number."', direct_deposit_number='".$direct_deposit_number."' where id='".$vendor_id."'";
			$result_vendor = $mysqli->query($update_vendor);

			if($update_vendor!='')
			{
				
				$url = API_HOST_URL."get_vendor_local_address.php?vendor_id=".$vendor_id;
				$local_address_arr = requestByCURL($url);
				for($k=0; $k<count($local_address_arr['data']);$k++)
				{
					if(!in_array($local_address_arr['data'][$k]['local_address_id'], $local_address_id_arr))
					{
						$delete_local = "delete from usaid_vendor_local_address where id='".$local_address_arr['data'][$k]['local_address_id']."'";
						$mysqli->query($delete_local);
					}
				}
				for($i=0; $i<count($local_contact_name_arr); $i++)
				{
					if($local_address_id_arr[$i]!='')
					{
						if($local_contact_name_arr[$i]!='')
						{
							$update_local_address = "update usaid_vendor_local_address set 
							local_contact_name='".$local_contact_name_arr[$i]."',
							local_contact_email='".$local_contact_email_arr[$i]."', 
							local_phone_number='".$local_phone_number_arr[$i]."',
							local_address_street='".$local_address_street_arr[$i]."',
							local_address_city='".$local_address_city_arr[$i]."',
							local_address_state_province='".$local_address_state_province_arr[$i]."',
							local_address_country='".$local_address_country_arr[$i]."',
							local_address_location_code='".$local_address_location_code_arr[$i]."'
							where id='".$local_address_id_arr[$i]."'";
							$result_local_address = $mysqli->query($update_local_address); 			
						}	
					}
					else
					{
						if($local_contact_name_arr[$i]!='')
						{	
							$insert_local_address = "insert into usaid_vendor_local_address set 
							vendor_id='".$vendor_id."',
							local_contact_name='".$local_contact_name_arr[$i]."',
							local_contact_email='".$local_contact_email_arr[$i]."', 
							local_phone_number='".$local_phone_number_arr[$i]."',
							local_address_street='".$local_address_street_arr[$i]."',
							local_address_city='".$local_address_city_arr[$i]."',
							local_address_state_province='".$local_address_state_province_arr[$i]."',
							local_address_country='".$local_address_country_arr[$i]."',
							local_address_location_code='".$local_address_location_code_arr[$i]."'
							";
							$result_local_address = $mysqli->query($insert_local_address);
						}	 
					} 
				} 
				header("location:vendor_management.php?update=1"); 
			}
		}
	}
}

###delete vendor===============
if(isset($_REQUEST['delete_vendor']))
{
	$vendor_id = trim($_REQUEST['vendor_id']);
	$delete_ven_local = "delete from usaid_vendor_local_address where vendor_id='".$vendor_id."'";
	$res_local = $mysqli->query($delete_ven_local);
	if($res_local)
	{
		$delete_ven = "delete from usaid_vendor where id='".$vendor_id."'";
		$res_local = $mysqli->query($delete_ven);
		header("location:vendor_management.php?delete=1"); 
	}
}

###delete vendor===============
if(isset($_REQUEST['delete_vendor_local_address']))
{
	$local_address_id = trim($_REQUEST['local_address_id']);
	$delete_ven_local = "delete from usaid_vendor_local_address where id='".$local_address_id."'";
	$res_local = $mysqli->query($delete_ven_local);
	header("location:vendor_management.php?delete=1");
}

## API for get all vendor list=======
$url = API_HOST_URL."get_all_vendor.php";
$all_vendor_arr = requestByCURL($url);

if(isset($_REQUEST['edit_vendor']))
{
	$vendor_id = trim($_REQUEST['vendor_id']);
	$url = API_HOST_URL."get_vendor.php?vendor_id=".$vendor_id;
	$vendor_arr = requestByCURL($url);

	##get all local address of this vendor===========
	$url = API_HOST_URL."get_vendor_local_address.php?vendor_id=".$vendor_id;
	$local_address_arr = requestByCURL($url); 
}
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
    <link href="css/jquery.dataTables.min.css" type="text/css" rel="stylesheet"> 
    <link href="css/responsive.dataTables.min.css" type="text/css" rel="stylesheet">
    <style>
    	.disp-none{
    		display: none;
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

	<div class="container-fluid" id="manage-vendor">
		<div class="form-title">
			<i class="fa fa-minus" aria-hidden="true"></i>Vendor
		</div>
		<div class="new-form-content">
			<div class="manage-info">
				<!--  ## show for error OR succ msg ====================  -->
				<p style="text-align:center"><span style="color:red;">  <?php echo $error; ?></span>
					<?php
					if(isset($_REQUEST['succ']) and $_REQUEST['succ']==1)
					{
						echo '<span style="color:green; text-align:center;">Vendor has been added successfully.</span></p>';	
					}
					if(isset($_REQUEST['update']) and $_REQUEST['update']==1)
					{
						echo '<span style="color:green; text-align:center;">Vendor has been updated successfully.</span></p>';	
					}

					?>

					<form class="form-horizontal" role="form" method="post" action="">
						<input type="hidden" name="edit_vendor_id" value="<?php echo $vendor_arr['data']['vendor_id']; ?>">
						<div class="form-group">
							<label for="vendor_duns_numeber" class="col-md-3 control-label">Vendor DUNS Number:</label>
							<div class="col-md-3">
								<?php 
								$DUNS_number='';
								$flag = 0;
								if(isset($_REQUEST['DUNS_number']))$DUNS_number = trim($_REQUEST['DUNS_number']);
								if(isset($vendor_arr)){$DUNS_number = $vendor_arr['data']['DUNS_number'];}
								?>		
								<input type="text" class="form-control" name="DUNS_number" value="<?php echo $DUNS_number; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="vendor_name" class="col-md-3 control-label">Vendor Name:</label>
							<div class="col-md-3">
								<?php 
								$name='';
								if(isset($_REQUEST['name']))$name = trim($_REQUEST['name']);
								if(isset($vendor_arr)){$name = $vendor_arr['data']['name'];}
								?>	
								<input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="vendor_address_street" class="col-md-3 control-label">Vendor Address Street:</label>
							<div class="col-md-3">
								<?php 
								$address_street='';
								if(isset($_REQUEST['address_street']))$address_street = trim($_REQUEST['address_street']);
								if(isset($vendor_arr)){$address_street = $vendor_arr['data']['address_street'];}
								?>	
								<input type="text" class="form-control" name="address_street" value="<?php echo $address_street; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="vendor_address_city" class="col-md-3 control-label">Vendor Address City:</label>
							<div class="col-md-3">
								<?php 
								$address_city='';
								if(isset($_REQUEST['address_city']))$address_city = trim($_REQUEST['address_city']);
								if(isset($vendor_arr)){$address_city = $vendor_arr['data']['address_city'];}
								?>	
								<input type="text" class="form-control" name="address_city" value="<?php echo $address_city; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="vendor_address_state" class="col-md-3 control-label">Vendor Address State/Province:</label>
							<div class="col-md-3">
								<?php 
								$address_state_province='';
								if(isset($_REQUEST['address_state_province']))$address_state_province = trim($_REQUEST['address_state_province']);
								if(isset($vendor_arr)){$address_state_province = $vendor_arr['data']['address_state_province'];}
								?>
								<input type="text" class="form-control" name="address_state_province" value="<?php echo $address_state_province; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="vendor_address_country" class="col-md-3 control-label">Vendor Address Country:</label>
							<div class="col-md-3">
								<?php 
								$address_country='';
								if(isset($_REQUEST['address_country']))$address_country = trim($_REQUEST['address_country']);
								if(isset($vendor_arr)){$address_country = $vendor_arr['data']['address_country'];}
								?>
								<input type="text" class="form-control" name="address_country" value="<?php echo $address_country; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="vendor_address_postal" class="col-md-3 control-label">Vendor Address Location or Postal Code:</label>
							<div class="col-md-3">
								<?php 
								$address_location_code='';
								if(isset($_REQUEST['address_location_code']))$address_location_code = trim($_REQUEST['address_location_code']);
								if(isset($vendor_arr)){$address_location_code = $vendor_arr['data']['address_location_code'];}
								?>
								<input type="text" class="form-control" name="address_location_code" value="<?php echo $address_location_code; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="vendor_contact_name" class="col-md-3 control-label">Vendor Contact Name:</label>
							<div class="col-md-3">
								<?php 
								$contact_name='';
								if(isset($_REQUEST['contact_name']))$contact_name = trim($_REQUEST['contact_name']);
								if(isset($vendor_arr)){$contact_name = $vendor_arr['data']['contact_name'];}
								?>
								<input type="text" class="form-control" name="contact_name" value="<?php echo $contact_name; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="vendor_email" class="col-md-3 control-label">Vendor Email Address:</label>
							<div class="col-md-3">
								<?php 
								$email_address='';
								if(isset($_REQUEST['email_address']))$email_address = trim($_REQUEST['email_address']);
								if(isset($vendor_arr)){$email_address = $vendor_arr['data']['email_address'];}
								?>
								<input type="text" class="form-control" name="email_address" value="<?php echo $email_address; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="vendor_phone" class="col-md-3 control-label">Vendor Phone Number:</label>
							<div class="col-md-3">
								<?php 
								$phone_number='';
								if(isset($_REQUEST['phone_number']))$phone_number = trim($_REQUEST['phone_number']);
								if(isset($vendor_arr)){$phone_number = $vendor_arr['data']['phone_number'];}
								?>
								<input type="text" class="form-control" name="phone_number" value="<?php echo $phone_number; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="vendor_deposit" class="col-md-3 control-label">Vendor Direct Deposit Number:</label>
							<div class="col-md-3">
								<?php 
								$direct_deposit_number='';
								if(isset($_REQUEST['direct_deposit_number']))$direct_deposit_number = trim($_REQUEST['direct_deposit_number']);
								if(isset($vendor_arr)){$direct_deposit_number = $vendor_arr['data']['direct_deposit_number'];}
								?>
								<input type="text" class="form-control" name="direct_deposit_number" value="<?php echo $direct_deposit_number; ?>">
							</div>
						</div>
						<div class="form-group">

							<div class="col-md-offset-6 pull-right">
								
							</div>
						</div>
						<table id="local_vendor" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<h2 class="text-center"><b>Local Address</b></h2>
								</tr>
								<tr>
									<button type="button" class="btn btn-success pull-right" class="add_vendor" id="add_vendor" onclick="addVendor()" style="margin-right:5px; margin-bottom:5px;"><i class="fa fa-plus-square" aria-hidden="true"></i>  Add Local Address</button>
								</tr>
								
								<tr>
									<th class="text-center">Contact Name</th>
									<th class="text-center">Contact Email</th>
									<th class="text-center">Phone Number</th>
									<th class="text-center">Street Address</th>
									<th class="text-center">Address City</th>
									<th class="text-center">State/Province</th>
									<th class="text-center">Country</th>
									<th class="text-center">Address Location Code</th>
									<th class="text-center">Delete</th>
								</tr>
							</thead>
							<tbody id="append_here">
								<?php
								if(count($local_address_arr['data'])>0)
								{	
									for($j=0; $j<count($local_address_arr['data']); $j++)
									{
										?> 
										<tr class="vendor-info">
											<td>
												<input type="text" class="form-control" name="local_contact_name[]" value="<?php echo $local_address_arr['data'][$j]['local_contact_name']; ?>">
												<input type="hidden" class="form-control" name="local_address_id_arr[]" value="<?php echo $local_address_arr['data'][$j]['local_address_id']; ?>">
											</td>
											<td>
												<input type="text" class="form-control" name="local_contact_email[]"  value="<?php echo $local_address_arr['data'][$j]['local_contact_email']; ?>">
											</td>
											<td>
												<input type="text" class="form-control" name="local_phone_number[]"  value="<?php echo $local_address_arr['data'][$j]['local_phone_number']; ?>">
											</td>
											<td>
												<input type="text" class="form-control" name="local_address_street[]"  value="<?php echo $local_address_arr['data'][$j]['local_address_street']; ?>">
											</td>
											<td>
												<input type="text" class="form-control" name="local_address_city[]"  value="<?php echo $local_address_arr['data'][$j]['local_address_city']; ?>">
											</td>
											<td>
												<input type="text" class="form-control" name="local_address_state_province[]"  value="<?php echo $local_address_arr['data'][$j]['local_address_state_province']; ?>">
											</td>
											<td>
												<input type="text" class="form-control" name="local_address_country[]"  value="<?php echo $local_address_arr['data'][$j]['local_address_country']; ?>">
											</td> 
											<td>
												<input type="text" class="form-control" name="local_address_location_code[]"  value="<?php echo $local_address_arr['data'][$j]['local_address_location_code']; ?>">
											</td>
											<td width="120" class="text-center">				
												<button type="button" class="btn btn-danger" onClick="removeVendor(this)" id="remove_vendor"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
											</td>
										</tr>
										<?php
									}  
								}
								else{ ?>
								<tr class="vendor-info">
									<td>
										<input type="text" class="form-control" name="local_contact_name[]" >
										<input type="hidden" class="form-control" name="local_address_id_arr[]" >
									</td>
									<td>
										<input type="text" class="form-control" name="local_contact_email[]" >
									</td>
									<td>
										<input type="text" class="form-control" name="local_phone_number[]" >
									</td>
									<td>
										<input type="text" class="form-control" name="local_address_street[]" >
									</td>
									<td>
										<input type="text" class="form-control" name="local_address_city[]" >
									</td>
									<td>
										<input type="text" class="form-control" name="local_address_state_province[]">
									</td>
									<td>
										<input type="text" class="form-control" name="local_address_country[]">
									</td>
									<td>
										<input type="text" class="form-control" name="local_address_location_code[]" >
									</td>
									<td width="120" class="text-center">				
										<button type="button" class="btn btn-danger" onClick="removeVendor(this)" id="remove_vendor"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
									</td>
								</tr>
								<?php	
							}
							?>
						</tbody>
					</table>

					<div class="row">
						<div class="col-md-offset-5 col-md-6">
							<a href="vendor_management.php" class="btn btn-default" style="margin-right:25px;">Cancel</a>
							<button type="submit" class="btn btn-primary" name="vender_management" >Save</button>
						</div>							
					</div>	
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Table Data -->

<div class="container-fluid" style="margin-top: 20px;">
	<table id="manage-parent-table" class="table table-striped" cellspacing="0" width="100%" border="2">
		<thead>
			<tr>
				<th class="text-center">DUNS Number</th>
				<th class="text-center">Vendor Name</th>
				<th class="text-center">Vendor Address City</th>
				<th class="text-center">Vendor Address State</th>
				<th class="text-center">Vendor Address Country</th>
				<th class="text-center">Contact Name</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
				## fetch all vendor data==========================
			if(count($all_vendor_arr['data'])>0)
			{	
				for($i=0 ;$i<count($all_vendor_arr['data']); $i++)
					{ ?>
				<tr class="parent-tbl">
					<td><?php echo $all_vendor_arr['data'][$i]['DUNS_number']; ?></td>
					<td><?php echo $all_vendor_arr['data'][$i]['name']; ?></td>
					<td><?php echo $all_vendor_arr['data'][$i]['address_city']; ?></td>
					<td><?php echo $all_vendor_arr['data'][$i]['address_state_province']; ?></td>
					<td><?php echo $all_vendor_arr['data'][$i]['address_country']; ?></td>
					<td><?php echo $all_vendor_arr['data'][$i]['contact_name']; ?></td>
					<td class="text-left">
						<i class="btn btn-xs fa fa-chevron-circle-down" onClick="showChild(this);"></i>
						|
						<form method="post" action="">
							<input type="hidden" name="vendor_id" style="display:inline" value="<?php echo $all_vendor_arr['data'][$i]['vendor_id']; ?>">
							<input type="submit" name="edit_vendor" value="Edit" class="project_btn"> 
							|
							<input type="submit" name="delete_vendor" value="Remove" class="project_btn" onClick="return window.confirm('Are you sure you want to remove this vendor');">				
						</form> | <?php 
							$url = API_HOST_URL."get_all_archive_vendor.php?vendor_id=".$all_vendor_arr['data'][$i]['vendor_id'];
$archive_vendor_arr = requestByCURL($url); if(count($archive_vendor_arr['data'])>0) {?>
							<a href="vendor_archive_list.php?archive_vendor_id=<?php echo $all_vendor_arr['data'][$i]['vendor_id']; ?>">Change Log</a>
							<?php } else { ?> <a href="javascript:void(0)">No Change Log</a><?php } ?>
					</td>
				</tr>
				<tr class="child-table disp-none">
					<td colspan="7">
						<div style="padding:2px; margin-top: 14px;">
							<table id="manage-child-table" class="table table-striped" border="1" cellspacing="0" width="90%" border="5">
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
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<?php
								$url = API_HOST_URL."get_vendor_local_address.php?vendor_id=".$all_vendor_arr['data'][$i]['vendor_id'];
								$local_address_arr = requestByCURL($url);
								if(count($local_address_arr['data'])>0)
								{ 
									for($a=0; $a<count($local_address_arr['data']); $a++)
									{
										?>
										<tr class="collapse in ">					
											<td><?php echo $local_address_arr['data'][$a]['local_contact_name'];?></td>
											<td><?php echo $local_address_arr['data'][$a]['local_contact_email'];?></td>
											<td><?php echo $local_address_arr['data'][$a]['local_phone_number'];?></td>
											<td><?php echo $local_address_arr['data'][$a]['local_address_street'];?></td>
											<td><?php echo $local_address_arr['data'][$a]['local_address_city'];?></td>
											<td><?php echo $local_address_arr['data'][$a]['local_address_state_province'];?></td>
											<td><?php echo $local_address_arr['data'][$a]['local_address_country'];?></td>
											<td><?php echo $local_address_arr['data'][$a]['local_address_location_code'];?></td>
											<td>
												<form method="post">							
													<input type="hidden" name="local_address_id" value="<?php echo $local_address_arr['data'][$a]['local_address_id']; ?>" class="project_btn"> 
													<input type="submit" name="delete_vendor_local_address" value="Remove" class="project_btn" onClick="return window.confirm('Are you sure you want to remove this local address');">
												</form>
											</td>
										</tr>
										<?php
									}
								}else echo '<tr><td colspan="9" text-align="center">No Local Address</td></tr>';
								?>
							</table>
						</div>
					</td>
				</tr>
				<?php }

			}else echo '<tr align="center"><td colspan="7">No Vendor Found</td></tr>';
			?>

		</tbody>
	</table>
</div>

<br>
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.responsive.js"></script>
<script type="text/javascript">	
	$(document).ready(function() {
		$('#manage-table').DataTable({"lengthMenu": [ 25, 50, 75, 100 ]});
	});	
</script>

<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/append.js"></script>

<script>
	$('.fa-plus').css('display','none');


</script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>