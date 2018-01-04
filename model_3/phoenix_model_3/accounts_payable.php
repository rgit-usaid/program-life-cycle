<?php
include('config/config.inc.php');
include('include/function.inc.php');
## get all operating unit id==========
$url = API_HOST_URL."get_all_operating_unit.php";
$operating_unit_arr = requestByCURL($url);

## get all vendor id ==========
$url = GS_API_HOST_URL."get_all_vendor.php";
$vendor_unit_arr = requestByCURL($url);

### Save Invoice ===========
$error = '';
if(isset($_REQUEST['account_payable']))
{
	$operating_unit_id = trim($_REQUEST['operating_unit_id']);
	$vendor_id = trim($_REQUEST['vendor_id']);
	$award_instrument_no_dr = trim($_REQUEST['award_instrument_no']);
	$voucher = trim($_REQUEST['voucher']);
	$invoice_number = trim($_REQUEST['invoice_number']);
	$invoice_desc = trim($_REQUEST['invoice_desc']); 
	$invoice_date = dateFormat(trim($_REQUEST['invoice_date']));
	$invoce_year = getInvoiceYearByDate($invoice_date);
	$total_invoice_amt = trim($_REQUEST['total_invoice_amt']);
	$total_invoice_amt = getNumericAmount($total_invoice_amt); // use this function if amount has $ sign or comma
	$total_invoice_paid = trim($_REQUEST['total_invoice_paid']);
	$total_invoice_paid = getNumericAmount($total_invoice_paid); // use this function if amount has $ sign or comma
	// array variables ======
	$clin_number = $_REQUEST['clin_number']; 
	$invoice_description = $_REQUEST['invoice_description'];
	$invoice_amt = $_REQUEST['invoice_amt'];
	$invoice_paid = $_REQUEST['invoice_paid'];
	$fund = $_REQUEST['fund']; // fund strip array
	if($operating_unit_id=='')
	{
		$error="Please select any operating unit";
	} 
	elseif($vendor_id=='')
	{
		$error="Please select vendor";
	}
	elseif($award_instrument_no_dr=='')
	{
		$error="Please select award";
	}
	elseif($invoice_number=='')
	{
		$error='Please enter invoice number';
	}
	elseif($invoice_date=='')
	{
		$error='Please enter invoice date';
	}
	elseif($total_invoice_amt=='')
	{
		$error='Please enter invoice amount';
	}
	elseif($total_invoice_paid=='')
	{
		$error='Please enter total invoice amount paid';
	}
	else
	{
		$insert_account_payble_data = "insert into usaid_account_payble set
				operating_unit_id = '".$operating_unit_id."',
				vendor_id = '".$vendor_id."',
				voucher = '".$voucher."',
				invoice_number = '".$invoice_number."',
				invoice_description = '".$invoice_desc."',
				invoice_date = '".$invoice_date."',
				total_invoice_amt = '".$total_invoice_amt."',
				total_invoice_paid = '".$total_invoice_paid."',
				award_instrument_no = '".$award_instrument_no_dr."'";
		$result_account_payble_data = $mysqli->query($insert_account_payble_data);
		$account_payble_new_id = $mysqli->insert_id;  
		if($account_payble_new_id>0)
		{
			for($i=0; $i<count($clin_number); $i++)
			{
				if(!empty($clin_number[$i]))
				{	 
					$invoice_amt_val = getNumericAmount($invoice_amt[$i]);
					$fund_amount = getNumericAmount($invoice_paid[$i]);
					$insert_account_payble_detail = "insert into usaid_account_payble_detail set 
							acc_payable_id='".$account_payble_new_id."',
							clin_number='".$clin_number[$i]."',
							fund_strip='".$fund[$i]."',
							invoice_description='".$invoice_description[$i]."',
							invoice_amt='".$invoice_amt_val."',
							invoice_paid='".$fund_amount."'";  
					$result_account_payble_detail = $mysqli->query($insert_account_payble_detail) or die($mysqli->error);  
					
					## insert data in transction table=====================	
					$fund_all = explode('=', $fund[$i]);
					$narration=trim($fund_all[0]);
					$fund_status_dr = trim($fund_all[1]); //fund status Obligate or Subobligate

					########=======================
					$strip_year = str_replace("-"," ",trim($narration));
					$fund_year_arr = explode(' ', $strip_year);

					$fund_code = $fund_year_arr[0];

					## fund id by fund code like DA==================
					$url = API_HOST_URL."get_fund.php?fund_id=".$fund_code."";
					$fund_arr = requestByCURL($url);
					$fund_id = $fund_arr['data']['id'];
					$cond = '';
					if(in_array('FY',$fund_year_arr))
					{
					 	$beginning_fiscal_year = $fund_year_arr[2];
					 	$ending_fiscal_year = $fund_year_arr[3];
					 	$pe_id= $fund_year_arr[4];
					 	$cond = "fund_beginning_fiscal_year = '".$beginning_fiscal_year."', fund_ending_fiscal_year='".$ending_fiscal_year."',";
					}
					else
					{
					 	$pe_id= $fund_year_arr[1];//dr
					} 
					$insert_data = "insert into usaid_fund_transaction set
							transaction_type = 'Allocate',
							narration = '".$strip_year."',
							fund_id = '$fund_id',
							program_element_id = '$pe_id',
							".$cond."
							fund_amount = '".$fund_amount."'";
					$result_fund_transaction = $mysqli->query($insert_data) or die($mysqli->error);
					$transaction_id = $mysqli->insert_id;

					###insert query for credit amount into transaction detail table=======================
				 	$opn_clg_balance_arr = getClosingBalance($clin_number[$i],$beginning_fiscal_year,$ending_fiscal_year,$fund_id,'Award CLIN',$pe_id,'Paid');
				 	
				 	$opening_balance = $opn_clg_balance_arr['opening_balance']; 
				 	$closing_balance = $opn_clg_balance_arr['closing_balance'];
				 	$opening_balance =  $closing_balance;
				 	$closing_balance = ($closing_balance + $fund_amount);
				 	$insert_fund_transaction_detail_cr = "insert into usaid_fund_transaction_detail set
				 				transaction_id = '".$transaction_id."', 
				 				credit_amount = '".$fund_amount."',
				 				opening_balance = '".$opening_balance."',
				 				closing_balance = '".$closing_balance."',
				 				ledger_type = 'Award CLIN',
				 				fund_status = 'Paid',
				 				transaction_year = '".$invoce_year."',
				 				ledger_type_id = '".$clin_number[$i]."'"; 
				 	$result_fund_transaction_detail_cr = $mysqli->query($insert_fund_transaction_detail_cr);

				 	###insert query for debit amount into transaction table =======================
				 	$opn_clg_balance_arr = getClosingBalance($award_instrument_no_dr,$beginning_fiscal_year,$ending_fiscal_year,$fund_id,'Award CLIN',$pe_id,$fund_status_dr);
				 	$opening_balance = $opn_clg_balance_arr['opening_balance'];
				 	$closing_balance = $opn_clg_balance_arr['closing_balance'];
			 
				 	$opening_balance = $closing_balance;
				 	$closing_balance = ($closing_balance - $fund_amount);

				 	$insert_fund_transaction_detail_de = "insert into usaid_fund_transaction_detail set
				 				transaction_id = '".$transaction_id."', 
				 				debit_amount = '".$fund_amount."',
				 				opening_balance = '".$opening_balance."',
				 				closing_balance = '".$closing_balance."',
				 				ledger_type = 'Award CLIN',
								fund_status = '".$fund_status_dr."',
								transaction_year = '".$invoce_year."',
				 				ledger_type_id = '".$award_instrument_no_dr."'";
				 	$result_fund_transaction_detail_de = $mysqli->query($insert_fund_transaction_detail_de); 
				}
			}
			if($result_fund_transaction and $result_fund_transaction_detail_cr and  $result_fund_transaction_detail_de)
		 	{
		 		header("location:accounts_payable.php");
		 	} 		
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>USAID - Accounts Payable</title>
	<!-- <link rel="shortcut icon" type="image/x-icon" href="images/hr-logo.gif" />	 -->
	
    <!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- CSS
  ================================================== -->
 	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css"> 
	<link href="css/font-awesome.min.css" type="text/css" rel="stylesheet"> 
	<link href="css/style.css" type="text/css" rel="stylesheet">
	<link href="css/jquery-ui.css" type="text/css" rel="stylesheet">
	<style>
  		.disp-none{
  			display: none;
  		}
  	</style>  
  	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script>	
	$(document).ready(function() {
		$("#datepicker").datepicker({
			changeMonth: true,
			changeYear: true
		});
		$('#btn').click(function() {
			$("#datepicker").focus();
		});
	
	}); 

// JS for Date Picker
$(document).ready(function() {
	$('.datepicker').datepicker({
		startDate: '-3d'                
	});
});	
	</script>
    
</head>
<body>
	<!-- Header -->
	
	<?php include 'header.html'; ?>

	<!---  / Header - --> 
	<!-- Breadcrumbs --> 
	<ol class="breadcrumb">
  		<li><a href="#">Site Map</a></li>
  		<li><a href="index.php">Accounts Payable</a></li>
  	</ol> 
	<!-- / Breadcrumbs  --> 
	<!-- Pop Up Div Menu Nav -->

	<div class="menu-nav">
		<ul class="nav navbar-nav navbar">
			<li>Accounts Payable</li>
		</ul>
	</div> 
	<!-- Main Manage Accounts Payable Goes Here --> 
	<div class="container-fluid manage-form">
		<?php
		if($error!='')
		{
			echo '<div style="text-align:center;color:red;">'.$error.'</div>';
		}
		?>
		<div class="form-title" style="width:40px">
			<i class="fa fa-minus" aria-hidden="true"></i><i class="fa fa-plus" aria-hidden="true"></i>
		</div>
		<!----Section1------>
		<form class="form-horizontal" role="form" method="post" action=""> 
		<div class="new-form-content" style="width:54%; float:left">
			<div class="manage-funds"> 
				
					<div class="form-group">
						<label class="col-md-4" for="Operating Unit">Operating Unit ID:</label>
						<div class="col-sm-8">
							<select class="form-control operating_unit_id" name="operating_unit_id" onChange="getOperatingUnit()";>
								<option value="">Select</option>
								<?php
								for($i=0; $i<count($operating_unit_arr['data']); $i++)
								{	
								?>
									<option value="<?php echo $operating_unit_arr['data'][$i]['operating_unit_id'];?>"><?php echo $operating_unit_arr['data'][$i]['operating_unit_description'].' ('.$operating_unit_arr['data'][$i]['operating_unit_abbreviation'].')';?>
									</option>
								<?php
								}
								?>	 
							</select>
						</div>
					</div> 
					<div class="form-group">
						<label class="col-md-4" for="Fund Name">Vendor ID:</label>
						<div class="col-md-8 show_ou_fund">
							<select class="form-control vendor_id" name="vendor_id" onChange="getAwardInstrumentNumber()"; >
								<option value="">Select</option> 
								<?php
								for($i=0; $i<count($vendor_unit_arr['data']); $i++)
								{	
								?>
									<option value="<?php echo $vendor_unit_arr['data'][$i]['vendor_id'];?>"><?php echo $vendor_unit_arr['data'][$i]['name'];?>
									</option>
								<?php
								}
								?>	 
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4" for="Award Instrument Number">Award Instrument Number:</label>
						<div class="col-md-8 show_award_number">
							<select class="form-control award_number" name="award_instrument_no">
								<option value="">Select</option> 
							</select>
						</div>
						<div class="col-sm-8 aj_loader" style="display:none;">
							 <img src="images/loading.gif">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4" for="Voucher ID">Voucher ID:</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="voucher" id="" placeholder="Voucher ID" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4" for="Invoice Number">Invoice Number:</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="invoice_number" id="" placeholder="Invoice Number" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4" for="Invoice Description">Invoice Description:</label>
						<div class="col-md-8">
							<textarea class="form-control" name="invoice_desc"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4" for="Invoice Date">Invoice Date:</label>
						<div class="col-md-8">
						<div class="input-group">
						
						<input type="text" id="datepicker" name="invoice_date" value="" class="form-control" />
						<span class="input-group-addon" id="btn" style="cursor:pointer;">
						<span class="glyphicon glyphicon-calendar"></span>
						</span>
						
						</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4" for="Amount">Total Invoice Amount:</label>
						<div class="col-md-8">
							<input type="number" class="form-control" name="total_invoice_amt" id="inv_amount" placeholder="Amount" value="" readonly>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4" for="Amount">Total Invoice Paid:</label>
						<div class="col-md-8">
							<input type="number" class="form-control" name="total_invoice_paid" id="inv_amount_paid" placeholder="Amount" value="" readonly>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-4 col-md-8">
							<a href="" class="btn btn-default" style="margin-right:20px">Cancel</a>
							<button type="submit" class="btn btn-primary" name="account_payable">Save</button>
						</div>
					</div>
			
			</div>
		</div>
	
		<!----Section2------>
			<div class="clin_blk form-horizontal" style="width:40%; float:left; margin-left:20px;width:500px; ">
			<div class="new-form-content" style="width:500px;padding-right:20px;position:relative;">
			<div class="manage-funds"> 
				<div style="position:absolute;right:10px; top:10px"><i class="fa fa-times text-danger close_btn disp-none invoice_amount_clin amount_clin" style="cursor:pointer"></i></div>
			<!--	<form class="form-horizontal" role="form" method="post" action="">  -->
					<div class="form-group">
						<label class="col-md-4" for="Fund Strip">Fund Strip:</label>
						<div class="col-md-8 fund" >
							<select name="fund[]" class="form-control">
								<option value="">Select</option>
							</select>
						</div>
						<div class="col-sm-8 aj_loader_fund_strip" style="display:none;">
							 <img src="images/loading.gif">
						</div>
					</div> 
					<div class="form-group">
						<label class="col-md-4" for="Fund Name">CLIN Number:</label>
						<div class="col-md-8 clin_number">
							<select class="form-control " name="">
								<option value="">Select</option> 
							</select>
						</div>
						<div class="col-sm-8 aj_loader_clin" style="display:none;">
							 <img src="images/loading.gif">
						</div>
					</div> 
					<div class="form-group">
						<label class="col-md-4" for="Invoice Description">Invoice Description for CLIN:</label>
						<div class="col-md-8">
							<textarea class="form-control" name="invoice_description[]"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4" for="Invoice Date">Invoice Amount for CLIN:</label>
						<div class="col-md-8">
							<input type="text" class="form-control invoice_amount_clin" name="invoice_amt[]" placeholder="Invoice Amount for CLIN" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4" for="Amount">Invoice Paid Amount for CLIN:</label>
						<div class="col-md-8">
						<?php $value=1500; ?>
							<input type="text" class="form-control amount_clin" name="invoice_paid[]" placeholder="Invoice Paid Amount for CLIN" max="<?php echo $val; ?>"  value="">
						</div>
					</div>
				
			</div>
		</div>
		<div class="form-group">
			<div style="margin-top:10px">
				<button type="button" class="btn btn-primary" name="" id="add_more_clin">Add More</button>
			</div>
		</div>
		</div>
		<div style="clear:both"></div>
		</form>
	</div>

	<!-- Manage Accounts Payable Ends Here -->

	<!-- Data Display Here -->
	<br/>
	<div class="data-display container-fluid">
		 
		<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
	            <tr> 
	                <th style="width:150px">Operating Unit ID</th>
	                <th style="width:150px">Vendor Name</th>
	                <th style="width:150px">Voucher ID</th>
                    <th style="width:150px">Invoice Number</th>
	                <th style="width:150px">Invoice Description</th>
					<th style="width:150px">Invoice Date</th>
	                <th style="width:150px">Total Invoice Amount ($)</th>
	                <th style="width:150px">Total Invoice Paid ($)</th>
	                <th style="width:150px">Award Instrument Number</th>
	                <th>Action</th>
	            </tr>
	        </thead>
	        <tbody>
			<?php 
			$select_account_payble="select ap.*, ou.operating_unit_description 
									from usaid_account_payble as ap
									left join usaid_operating_unit as ou ON ou.operating_unit_id = ap.operating_unit_id";
			$result_account_payble = $mysqli->query($select_account_payble);
			while($fetch_account_payble = $result_account_payble->fetch_array())
			{ 
				$url = GS_API_HOST_URL."get_vendor.php?vendor_id=".$fetch_account_payble['vendor_id']."";
				$vendor_arr = requestByCURL($url);
				?>
				<tr class="parent-tbl">
					<td><?php echo $fetch_account_payble['operating_unit_description']; ?></td>
					<td><?php echo $vendor_arr['data']['name']; ?></td>
					<td><?php echo $fetch_account_payble['voucher']; ?></td>  
					<td><?php echo $fetch_account_payble['invoice_number']; ?></td>
					<td><?php echo $fetch_account_payble['invoice_description']; ?></td>
					<td><?php echo $fetch_account_payble['invoice_date']; ?></td>
					<td><?php echo '$'.number_format($fetch_account_payble['total_invoice_amt']); ?></td> 
					<td><?php echo '$'.number_format($fetch_account_payble['total_invoice_paid']); ?></td>
					<td><?php echo $fetch_account_payble['award_instrument_no']; ?></td>  
					<td> <i class="btn btn-xs fa fa-chevron-circle-down" style="margin-right:20px;" onClick="showChild(this);"></i></td> 
				</tr> 
	            <tr class="child-table disp-none">
	            	<td colspan="10">
	            		<div style="padding:10px;">
	            			<table class="table table-bordered" cellspacing="0" width="100%">
	            			<tr class="collapse in" style="background:#EBDEF0;">					
	            					<th class="text-center">CLIN Number</th> 
	            					<th class="text-center">Invoice Description</th>
									<th class="text-center">Invoice Amount ($)</th>
									<th class="text-center">Invoice Paid Amount ($)</th>
	            				</tr>
						<?php $select_account_payble_detail="select * from usaid_account_payble_detail where acc_payable_id='". $fetch_account_payble['id']."'";
								$result_account_payble_detail = $mysqli->query($select_account_payble_detail);
								while($fetch_account_payble_detail = $result_account_payble_detail->fetch_array()) {
						?>		
        						<tr>
									<td><?php echo $fetch_account_payble_detail['clin_number']; ?></td> 
        							<td><?php echo $fetch_account_payble_detail['invoice_description']; ?></td>
									<td ><?php echo '$'.number_format($fetch_account_payble_detail['invoice_amt']); ?></td> 
        							<td><?php echo '$'.number_format($fetch_account_payble_detail['invoice_paid']); ?></td>
        						</tr>
        				<?php } ?>	
	            			</table>
	            		</div>
	            	</td>
	            </tr>
		<?php } ?>			
		     </tbody>
		</table>
	</div> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/bootstrap.min.js"></script>
<!--	<script src="js/bootstrap-datepicker.js"></script> -->
	<script src="js/main.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript">
	
	function showChild(elem){
		//alert($(elem).closest('.child-table').length);
		$(elem).closest('.parent-tbl').next('.child-table').toggleClass('disp-none');
	}
	
	function showChild(elem){
		//alert($(elem).closest('.child-table').length);
		$(elem).closest('.parent-tbl').next('.child-table').toggleClass('disp-none');
	}
	/*=====function for get ou fund drop down ==================*/
	function getAwardInstrumentNumber()
	{
		$('.show_award_number').css('display','none');
		$('.aj_loader').css('display','');
		var vendor_id = $('.vendor_id').val();
		var operating_unit_id = $('.operating_unit_id').val(); 
		 
		$.ajax({
		  type: "POST",
		  url: "ajax_files/get_award_instrument_number.php",
		  data: {vendor_id:vendor_id,operating_unit_id:operating_unit_id},
		  success: function(data){
		  		$('.aj_loader').css('display','none');
		  		$('.show_award_number').css('display','block');	
		    	if(data!='')$('.show_award_number').html(data);
		  	}
		}); 
	}
	  
	/*=== get fund strip to a award==========*/
	function getAwardFundStrip(elem)
	{
		$('.fund').css('display','none');
		$('.aj_loader_fund_strip').css('display','');
		var award_number = $(elem).val();	 
		if(award_number=='')
		{
			$('.fund').next('.aj_loader_fund_strip').css('display','none');
			$('.fund').css('display','');
		}	
		else
		{ 
		 	$.ajax({
			  type: "POST",
			  url: "ajax_files/get_fund_strip_drop.php",
			  data: {award_number:award_number},
			  success: function(data){
			  		$('.aj_loader_fund_strip').css('display','none');
			  		$('.fund').css('display','block');	
			    	if(data!='')$('.fund').html(data);
			  	}
			});
		} 
	} 
	
	function getAwardNumber(elem)
	{
		$('.clin_number').css('display','none');
		$('.aj_loader_clin').css('display','');
		var award_number = $(elem).val();	 
		$.ajax({
		  type: "POST",
		  url: "ajax_files/get_clin_drop.php",
		  data: {award_number:award_number},
		  success: function(data){
		  		$('.aj_loader_clin').css('display','none');
		  		$('.clin_number').css('display','block');	
		    	if(data!='')$('.clin_number').html(data);
		  	}
		}); 
	}
	 
	$('#add_more_clin').click(function(){
		var clone = $(this).closest('.clin_blk').find('.new-form-content:last').clone();
		clone.find('input[type="text"],input[type="number"]').val("");
		clone.find('.close_btn').removeClass("disp-none");
		$(clone).insertAfter('.clin_blk .new-form-content:last');
	});
	$(document).on('click','.close_btn',function(){
		if($('.clin_blk .new-form-content').length>1){
			$(this).closest('.new-form-content').remove();
		}
	});
	
	$(document).on('input click','.invoice_amount_clin',function(){
		var total_amount=0;
	  $('.invoice_amount_clin').each(function(index, elem){
		     var amt = $(elem).val().replace(/,/g,"");
		     if(amt == '' || amt == '$') total_amount = total_amount;
		       else
			      total_amount = total_amount + parseInt(amt.match(/\d+/));
		}); 
      $('#inv_amount').val(total_amount);
	})

	$(document).on('input click','.amount_clin',function(){
		var total_amount=0;
		$('.amount_clin').each(function(index, elem){
			var amt = $(elem).val().replace(/,/g,"");
		     if(amt == '' || amt == '$') total_amount = total_amount;
		       else
			      total_amount = total_amount + parseInt(amt.match(/\d+/));
		});
		$('#inv_amount_paid').val(total_amount);
	})
	</script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
	
</body>
</html>