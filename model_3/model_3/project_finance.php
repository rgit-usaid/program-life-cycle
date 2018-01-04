<?php include('config/functions.inc.php');
##==validate user====
validate_user();

###request for get single project details using project id ===========
$project_id = '';
$activity_id = '';

if(isset($_SESSION['project_id']))
{
	$project_id = $_SESSION['project_id'];
	$activity_id = $_SESSION['activity_id'];

	##==get project_info
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);

	##==get project_stage_info
    $url = API_HOST_URL_PROJECT."api_demo.php?stage"; 
  	$project_stage_arr = requestByCURL($url);
	
	
	##==get project_stage_info
    $url = API_HOST_URL_PROJECT."get_all_project_activity.php?project_id=".$project_id; 
  	$project_act_arr = requestByCURL($url);
	
}

$finance_arr = array();
$FY_year_arr= array();
$start_fyear = $end_fyear = $start_fmonth = $end_fmonth = $start_fquarter = $end_fquarter = 0;
$FY_finance_monthly = array();
$FY_finance_quarterly = array();
 
for($j=0;$j<count($project_act_arr['data']);$j++)
{
	$activity_id = $project_act_arr['data'][$j]['activity_id'];
	### get all award of a activity==============
	$url = API_HOST_URL_GLAAS."get_award_by_activity.php?activity_id=".$activity_id."";
	$award_arr  = requestByCURL($url);
	## loop for get only award number in array==============
	$award_number_arr = array();
	for($k=0; $k<count($award_arr['data']); $k++)
	{
		$award_number_arr[$k] = $award_arr['data'][$k]['award_number'];
	} 

	$data_award = urlencode(serialize($award_number_arr));

	$url = API_HOST_URL_PHOENIX."get_fund_by_awards.php?data_award=".$data_award."&fund_status=Obligate&fund_status2=Subobligate";  
	$activity_ob_fund  = requestByCURL($url);

	$url = API_HOST_URL_PHOENIX."get_fund_by_awards.php?data_award=".$data_award."&fund_status=Commit";  
	$activity_cm_fund  = requestByCURL($url);
	
	$url = API_HOST_URL_PHOENIX."get_all_account_payable_by_award.php?data_award=".$data_award."";  
	$activity_payable_fund  = requestByCURL($url);
	
	/*fill obligate data*/
	for($i=0; $i<count($activity_ob_fund['data']);$i++){
		$byear = $activity_ob_fund['data'][$i]['transaction_year'];
		$eyear = $byear+1;
		$disp_label = $byear.'-'.substr($eyear,-2,2);
		$FY_year_arr[$byear]['fiscal_year']= $disp_label;
		$FY_year_arr[$byear]['b_year']= $byear;
		$FY_year_arr[$byear]['obligated']= $FY_year_arr[$byear]['obligated'] + $activity_ob_fund['data'][$i]['credit_amount'];
	}
	
	/*fill commited data*/
	for($i=0; $i<count($activity_cm_fund['data']);$i++){
		$byear = $activity_cm_fund['data'][$i]['transaction_year'];
		$eyear = $byear+1;
		$disp_label = $byear.'-'.substr($eyear,-2,2);
		$FY_year_arr[$byear]['fiscal_year']= $disp_label;
		$FY_year_arr[$byear]['b_year']= $byear;
		$FY_year_arr[$byear]['committed']= $FY_year_arr[$byear]['committed'] + $activity_cm_fund['data'][$i]['credit_amount'];
		if(!array_key_exists('obligated',$FY_year_arr[$byear])){
			$FY_year_arr[$byear]['obligated']= 0;
		}
	}
	
	/*fill spend data*/
	for($i=0; $i<count($activity_payable_fund['data']);$i++){
		$invoice_date = date('Y-m-d',strtotime($activity_payable_fund['data'][$i]['invoice_date']."+".PLUS_EXTRA_SPEND_DAYS." days"));
		$actual_year = $byear = date('Y',strtotime($invoice_date));
		$month = date('m',strtotime($invoice_date));
		$quarter = get_quarter($month);
		if($month<10){
			$byear = date('Y',strtotime($invoice_date))-1;
		}
		
		$eyear = $byear+1;
		$disp_label = $byear.'-'.substr($eyear,-2,2);
		$FY_year_arr[$byear]['fiscal_year']= $disp_label;
		$FY_year_arr[$byear]['b_year']= $byear;
		if(!array_key_exists('obligated',$FY_year_arr[$byear])){
			$FY_year_arr[$byear]['spend']= $activity_payable_fund['data'][$i]['total_paid_amount'];
		}
		else{
			$FY_year_arr[$byear]['spend']= $activity_payable_fund['data'][$i]['total_paid_amount'] + $FY_year_arr[$byear]['spend'];
		}
		
		if(!array_key_exists('obligated',$FY_year_arr[$byear])){
			$FY_year_arr[$byear]['obligated']= 0;
		}
		
		if(!array_key_exists('committed',$FY_year_arr[$byear]) || !array_key_exists('obligated',$FY_year_arr[$byear])){
			$FY_year_arr[$byear]['committed']= 0;
		}
		
		/*fill spend array for monthly data*/
		$disp_arr_label = $actual_year.'-'.$month;
		if(!array_key_exists($disp_arr_label,$FY_finance_monthly)){
			$FY_finance_monthly[$disp_arr_label]['spend'] = $activity_payable_fund['data'][$i]['total_paid_amount'];
		}
		else{
			$FY_finance_monthly[$disp_arr_label]['spend'] = $FY_finance_monthly[$disp_arr_label]['spend'] + $activity_payable_fund['data'][$i]['total_paid_amount'];
		}
		
		/*fill spend array for quarterly data*/
		if($month>=10)
	           $disp_arr_qlabel = $actual_year.'-'.$quarter;
             else
    	       $disp_arr_qlabel = ($actual_year-1).'-'.$quarter;
		if(!array_key_exists($disp_arr_qlabel,$FY_finance_quarterly)){
			$FY_finance_quarterly[$disp_arr_qlabel]['spend'] = $activity_payable_fund['data'][$i]['total_paid_amount'];
		}
		else{
			$FY_finance_quarterly[$disp_arr_qlabel]['spend'] = $FY_finance_quarterly[$disp_arr_qlabel]['spend'] + $activity_payable_fund['data'][$i]['total_paid_amount'];
		}
	}
	
	/*====select forecaster data for this activity===*/
	$url = API_HOST_URL_PROJECT."get_project_activity_finance.php?activity_id=".$activity_id."&type=Forecaster";  
	$activity_forecaster_finance  = requestByCURL($url);
	for($i=0;$i<count($activity_forecaster_finance['data']);$i++){
		$actual_year = $byear =  $activity_forecaster_finance['data'][$i]['finance_year'];
		$month = $activity_forecaster_finance['data'][$i]['finance_month'];
		$quarter = get_quarter($month);
		if($month<10){
		$month= "0".$month; 
	    }
		if($activity_forecaster_finance['data'][$i]['finance_month']<10){
			$byear = $activity_forecaster_finance['data'][$i]['finance_year']-1;		
		}
	
		if(array_key_exists($byear,$FY_year_arr)){
			$FY_year_arr[$byear]['forecast'] = $FY_year_arr[$byear]['forecast'] + $activity_forecaster_finance['data'][$i]['total_amount'];
		}
		
		/*fill spend array for monthly data*/
		$disp_arr_label = $actual_year.'-'.$month;
		
		if(!array_key_exists($disp_arr_label,$FY_finance_monthly)){
			$FY_finance_monthly[$disp_arr_label]['forecaster'] = $activity_forecaster_finance['data'][$i]['total_amount'];
		}
		else{
			$FY_finance_monthly[$disp_arr_label]['forecaster'] = $FY_finance_monthly[$disp_arr_label]['forecaster'] + $activity_forecaster_finance['data'][$i]['total_amount'];
		}
		
		/*fill spend array for quarterly data*/
		if($month>=10)
	           $disp_arr_qlabel = $actual_year.'-'.$quarter;
             else
    	       $disp_arr_qlabel = ($actual_year-1).'-'.$quarter;
		if(!array_key_exists($disp_arr_qlabel,$FY_finance_quarterly)){
			$FY_finance_quarterly[$disp_arr_qlabel]['forecaster'] = $activity_forecaster_finance['data'][$i]['total_amount'];
		}
		else{
			$FY_finance_quarterly[$disp_arr_qlabel]['forecaster'] = $FY_finance_quarterly[$disp_arr_qlabel]['forecaster'] + $activity_forecaster_finance['data'][$i]['total_amount'];
		}
	}
	
	/*====select planner data for this activity===*/
	$url = API_HOST_URL_PROJECT."get_project_activity_finance.php?activity_id=".$activity_id."&type=Planner";  
	$activity_forecaster_finance  = requestByCURL($url);
	for($i=0;$i<count($activity_forecaster_finance['data']);$i++){
		$actual_year = $byear =  $activity_forecaster_finance['data'][$i]['finance_year'];
		$month = $activity_forecaster_finance['data'][$i]['finance_month'];
		$quarter = get_quarter($month);
		if($month<10){
		$month= "0".$month; 
	    }
		if($activity_forecaster_finance['data'][$i]['finance_month']<10){
			$byear = $activity_forecaster_finance['data'][$i]['finance_year']-1;		
		}
	
		if(array_key_exists($byear,$FY_year_arr)){
			$FY_year_arr[$byear]['planner'] = $FY_year_arr[$byear]['planner'] + $activity_forecaster_finance['data'][$i]['total_amount'];
		}
		
		/*fill spend array for monthly data*/
		$disp_arr_label = $actual_year.'-'.$month;
		if(!array_key_exists($disp_arr_label,$FY_finance_monthly)){
			$FY_finance_monthly[$disp_arr_label]['planner'] = $activity_forecaster_finance['data'][$i]['total_amount'];
		}
		else{
			$FY_finance_monthly[$disp_arr_label]['planner'] = $FY_finance_monthly[$disp_arr_label]['planner'] + $activity_forecaster_finance['data'][$i]['total_amount'];
		}
		
		/*fill spend array for quarterly data*/
		if($month>=10)
	           $disp_arr_qlabel = $actual_year.'-'.$quarter;
             else
    	       $disp_arr_qlabel = ($actual_year-1).'-'.$quarter;
		if(!array_key_exists($disp_arr_qlabel,$FY_finance_quarterly)){
			$FY_finance_quarterly[$disp_arr_qlabel]['planner'] = $activity_forecaster_finance['data'][$i]['total_amount'];
		}
		else{
			$FY_finance_quarterly[$disp_arr_qlabel]['planner'] = $FY_finance_quarterly[$disp_arr_qlabel]['planner'] + $activity_forecaster_finance['data'][$i]['total_amount'];
		}
	}

	/*sort finance array by year*/
	//ksort($FY_year_arr);
}
 
//** sort forecaster array by month and year
ksort($FY_finance_monthly);
ksort($FY_finance_quarterly);

$temp_month_arr = array_keys($FY_finance_monthly);
$temp_data = explode('-',$temp_month_arr[0]);
$start_fyear = $temp_data[0]; 
$start_fmonth = intval($temp_data[1]); 
$temp_data = explode('-',$temp_month_arr[count($temp_month_arr)-1]);
$end_fyear = $temp_data[0]; 
$end_fmonth = intval($temp_data[1]); 

$temp_quarter_arr = array_keys($FY_finance_quarterly);
$temp_data = explode('-',$temp_quarter_arr[0]);
$start_fquarter = $temp_data[1]; 
$temp_data = explode('-',$temp_quarter_arr[count($temp_quarter_arr)-1]);
$end_fquarter = $temp_data[1]; 

//*===fill month array data===*//
$tmp_sfm=0;$tmp_efm=0; //temp_sfm is temp_start-fiscal-month 
if($start_fyear>0 && $end_fyear>0 && $tmp_sfm>=0 && $tmp_efm>=0){
	for($i=$start_fyear;$i<=$end_fyear;$i++){
		if($i==$start_fyear && $i!=$end_fyear)
         {
         	$tmp_sfm=10;
         	$tmp_efm=12;
         }
         else if($i==$start_fyear && $i==$end_fyear)
		 {
		   $tmp_sfm = $start_fmonth;
		   $tmp_efm = 9;	
		 }
		 else if($i>$start_fyear && $i<=$end_fyear)
		 {
		 	$tmp_sfm=1;
		 	if($i==$end_fyear)
		 		$tmp_efm = 9;
		 	else
		 		$tmp_efm = 12;
		 }
		for($j=$tmp_sfm;$j<=$tmp_efm;$j++){
		if($j<10){
				$disp_label= $i."-0".$j;
			}
			else{
				$disp_label= $i.'-'.$j; 
			} 
		$quarter = get_quarter($j); 
		if($j>=10)
		  $disp_qlabel = $i.'-'.$quarter; 
		else
			$disp_qlabel = ($i-1).'-'.$quarter; 
			if(!array_key_exists('spend',$FY_finance_monthly[$disp_label])){
				$FY_finance_monthly[$disp_label]['spend']= 0;	
			}
			
			if(!array_key_exists('forecaster',$FY_finance_monthly[$disp_label])){
				$FY_finance_monthly[$disp_label]['forecaster']= 0;	
			}
			
			if(!array_key_exists('planner',$FY_finance_monthly[$disp_label])){
				$FY_finance_monthly[$disp_label]['planner']= 0;	
			}
			if(!array_key_exists('spend',$FY_finance_quarterly[$disp_qlabel])){
				$FY_finance_quarterly[$disp_qlabel]['spend']= 0;	
			}
			
			if(!array_key_exists('forecaster',$FY_finance_quarterly[$disp_qlabel])){
				$FY_finance_quarterly[$disp_qlabel]['forecaster']= 0;	
			}
			
			if(!array_key_exists('planner',$FY_finance_quarterly[$disp_qlabel])){
				$FY_finance_quarterly[$disp_qlabel]['planner']= 0;	
			}
		}
	}
}



/*===sort finance array by year===*/
ksort($FY_year_arr);
//** sort forecaster array by month and year
ksort($FY_finance_monthly);
ksort($FY_finance_quarterly);
$month = date('m');
if($month<10){
	$current_year_index = (date('Y')-1); 
}
else{
	$current_year_index = date('Y'); 
}

if(!array_key_exists($current_year_index,$FY_year_arr)){
	$current_year_index = key($FY_year_arr);
}

$finance_arr = $FY_year_arr; 
$total_budget=0;
$total_spend=0;
$total_forecast=0;


$total_budget = array_sum(array_map(create_function('$finance_arr', 'return $finance_arr["obligated"];'), $FY_year_arr));
$total_spend = array_sum(array_map(create_function('$finance_arr', 'return $finance_arr["spend"];'), $FY_year_arr));
$total_forecast = array_sum(array_map(create_function('$finance_arr', 'return $finance_arr["forecast"];'), $FY_year_arr));

//Calculation of max value at y-axis==================
$k=0;
foreach($FY_finance_quarterly as $key=>$budget_type_arr)
{
  foreach($budget_type_arr as $key=>$budget)
  {
  	$max_amount_arr[$k] = $budget;
  	$k++;
  }
}
$max_y_axis_value = max($max_amount_arr);
?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico"/>
<title>USAID-AMP</title>
<?php include('includes/resources.php');?>
</head>
<body class="page-ui-components" style="position:relative">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<?php include('includes/project_header.php');?>
		<!--main container blk start-->
		<div class="tbl-block">
			<div class="tbl-caption">
				<div class="tbl-content-head">Project Finance</div>
				<div class="clear"></div>
			</div>
			<div class="project-detail-blk table-container">
				<!--review display only block start-->
				<div class="form-blk">
					<!--<div id="submission_msg" class="form-msg <?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg_type'];}?>">
						<?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg'];}?>
					</div>-->
					
					<div class="row project-detail-textarea">
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="finance_dashboard bdbudget">
								<div class="head">
									<?php echo '$'.number_format($total_budget);?>
								</div>
								<div class="sub-head">Lifetime Budget 
								<input type="hidden" class="total_y-axis_budget_js" value="<?php echo ($max_y_axis_value+10000);?>">
								<input type="hidden" class="total_budget_js" value="<?php echo $total_budget;?>"></div>
							</div>	
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="finance_dashboard bdspend">
								<div class="head">
									<?php echo '$'.number_format($total_spend);?>
								</div>
								<div class="sub-head">Lifetime Actual Spend</div>
							</div>	
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="finance_dashboard bdforecast">
								<div class="head">
									<?php echo '$'.number_format($total_forecast);?>
								</div>
								<div class="sub-head">Lifetime Forecast</div>
							</div>	
						</div>
					</div>
					<header><h2 class="form-blk-head">Finance Profile</h2></header>
					<div class="row project-detail-textarea">
						<!-- line chart canvas element -->
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<button id="view_bar_chart" type="button" class="viewchart_btn usa-button-hover">View as bar chart</button>
							<button id="view_line_chart" type="button" class="viewchart_btn">View as line chart</button>
							<button id="view_hybrid_chart" type="button" class="viewchart_btn">View as hybrid chart</button>
							<div id="mode_blk">
							<div class="bold">Choose One</div>
							<div class="extra_ht"></div>
							<div><input type="radio"  value="monthly" checked="checked" name="mode"/><label> Month</label></div>
							<div class="extra_ht"></div>
							<div><input type="radio" value="quarterly" name="mode"/><label> Quarter</label></div>
							</div>
							<div class="clear extra_ht"></div><div class="extra_ht"></div>
							<div style="overflow:scroll; overflow-y:hidden">
							<div style="width:300%;">
								<div class="barchart_blk chart_blk">
									<div id="barchart_container" class="chart_cont"></div>
								</div>
								<div class="linechart_blk chart_blk disp-none">
									<div id="linechart_container" class="chart_cont"></div>
								</div>
								<div class="hybridchart_blk chart_blk disp-none">
									<div id="hybridchart_container" class="chart_cont"></div>
								</div>
							</div>
							</div>
						</div>
					</div>
					<header><h3 class="form-blk-head">Finance Details</h3></header>
					<a id="toggle_years_data" class="btn btn-blue">Show All Years</a> 
					<input type="hidden" id="show_years_data_flag" value="no"/>
					<div class="row project-detail-textarea">
						<!-- line chart canvas element -->
						<div class="review-listing col-lg-12 col-md-12 col-sm-12 col-xs-12 project_activity_finance_details">
							<div>
								<div>
									<table id="projects_finance_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
									<tr class="head">
										<td class="sm_wdth">Year</td>
										<td class="sm_wdth">Committed</td>
										<td class="comm_wdth">Obligated</td>
										<td class="lg_wdth">Actual Spend</td>
										<td class="comm_wdth">Available</td>
										<td class="comm_wdth">Forecast</td>
									</tr>
									</thead>
									<?php 
									##==if any finance related info exists==
									if(count($finance_arr)>0){?>
										<tfoot>
										<tr class="head">
											<td class="sm_wdth">Total</td>
											<td class="sm_wdth committed_data">$<?php echo number_format($finance_arr[$current_year_index]['committed']);?>
												<input type="hidden" value="<?php echo $finance_arr[$current_year_index]['committed'];?>"  class="value"/>
											</td>
											<td class="comm_wdth obligated_data">$<?php echo number_format($finance_arr[$current_year_index]['obligated']);?>
												<input type="hidden" value="<?php echo $finance_arr[$current_year_index]['obligated'];?>"  class="value"/>
											</td>
											<td class="lg_wdth actual_spend_data">$<?php echo number_format($finance_arr[$current_year_index]['spend']);?>
												<input type="hidden" value="<?php echo $finance_arr[$current_year_index]['spend'];?>"  class="value"/>
											</td>
											<?php $avail_val = $finance_arr[$current_year_index]['obligated'] - $finance_arr[$current_year_index]['spend'];?>
											<td class="comm_wdth available_data">$<?php echo number_format($avail_val);?>
												<input type="hidden" value="<?php echo $avail_val;?>"  class="value"/>
											</td>
											<td class="comm_wdth forecast_data">$<?php echo number_format($finance_arr[$current_year_index]['forecast']);?>
												<input type="hidden" value="<?php echo $finance_arr[$current_year_index]['forecast'];?>"  class="value"/>
											</td>
										</tr>
										</tfoot>
										<tbody>
										<?php 
										$i=0;
										foreach($finance_arr as $key => $obj){
											$avail_val= $finance_arr[$key]['obligated'] - $finance_arr[$key]['spend'];
										?>
										<tr class="data_row  <?php if($finance_arr[$key]['b_year']!=$current_year_index) {?> disp-none other_year <?php } else {?> current_year <?php }?>">
											<td class="sm_wdth">
												<?php echo $finance_arr[$key]['fiscal_year'];?>
												<input type="hidden" value="<?php echo $finance_arr[$key]['b_year'];?>" class="finance_year"/>
											</td>
											<td class="comm_wdth committed_data">$<span><?php echo number_format($finance_arr[$key]['committed']);?></span>
												<input type="hidden" value="<?php echo $finance_arr[$key]['committed'];?>"  class="value"/>
											</td>
											<td class="sm_wdth obligated_data">$<span><?php echo number_format($finance_arr[$key]['obligated']);?></span>
												<input type="hidden" value="<?php echo $finance_arr[$key]['obligated'];?>"  class="value"/>
											</td>
											<td class="lg_wdth actual_spend_data">$<span><?php echo number_format($finance_arr[$key]['spend']);?></span>
												<input type="hidden" value="<?php echo $finance_arr[$key]['spend'];?>"  class="value"/>
											</td>
											<td class="comm_wdth available_data">$<span><?php echo number_format($avail_val);?></span>
												<input type="hidden" value="<?php echo $avail_val;?>"  class="value"/>
											</td>
											<td class="comm_wdth forecast_data">$<span><?php echo number_format($finance_arr[$key]['forecast']);?></span>
												<input type="hidden" value="<?php echo $finance_arr[$key]['forecast'];?>"  class="value"/>
											</td>
										</tr>
										<?php $i++;}?>
										</tbody>
									<?php } else {?>
										<tbody>
											<tr class="data_row">
												<td colspan="6" class="text-danger bold">No data exists</td>
											</tr>
										</tbody>
									<?php }?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	    </div>
	  	<!--main container blk end-->
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
<script>
	var month_obligate_data = new Array();
	var month_spend_data = new Array();
	var month_forecaster_data = new Array();
	var month_finance_label_arr = new Array();
	
	var quarter_obligate_data = new Array();
	var quarter_spend_data = new Array();
	var quarter_forecaster_data = new Array();
	var quarter_finance_label_arr = new Array();
	
	var month_arr = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
</script>
<script src="<?php echo HOST_URL?>js/exporting.js"></script>
<script src="<?php echo HOST_URL?>js/highcharts.js"></script>
<?php if(count($FY_finance_monthly)>0){
	foreach($FY_finance_monthly as $key => $obj){
	$tmp_arr = explode("-",$key);
		if($tmp_arr[1]>=10)
	       $month_name = substr($key,strlen($key)-2);
        else
	       $month_name = substr($key,strlen($key)-1);
	$two_wrd_year = substr($key,2,2);
	
?>
<script>
	month_obligate_data.push(parseInt("<?php echo $obj['planner'];?>"));
	month_spend_data.push(parseInt("<?php echo $obj['spend'];?>"));
	month_forecaster_data.push(parseInt("<?php echo $obj['forecaster'];?>"));
	var temp =parseInt("<?php echo $month_name;?>")-1;
	month_finance_label_arr.push(month_arr[temp]+" <?php echo $two_wrd_year;?>");
</script>
<?php }}?>
<?php if(count($FY_finance_quarterly)>0){
	foreach($FY_finance_quarterly as $key => $obj){
	$quarter_name = substr($key,strlen($key)-1);
	$two_wrd_year = substr($key,2,2);
?>
<script>
	quarter_obligate_data.push(parseInt("<?php echo $obj['planner'];?>"));
	quarter_spend_data.push(parseInt("<?php echo $obj['spend'];?>"));
	quarter_forecaster_data.push(parseInt("<?php echo $obj['forecaster'];?>"));
	var temp =parseInt("<?php echo $quarter_name;?>");
	quarter_finance_label_arr.push('Q'+temp+" <?php echo $two_wrd_year;?>");
</script>
<?php }}?>
<script type="text/javascript">
var disp_month_arr = new Array();
var disp_quarter_arr = new Array();
//var total_budget = $('.total_budget_js').val(); //use height of the line chart
$(function () {
	Highcharts.setOptions({
		lang: {
			thousandsSep: ','
		}
	});
	Highcharts.setOptions({
    	colors: ['#8C0001','#ccc','#084A08']
	});
	
	var bar_chart;
	bar_chart = new Highcharts.Chart({
		chart: {
            zoomType : false,
			renderTo: 'barchart_container',
            type: 'column'
        },
		title: {
            text: ''
        },
		yAxis: [{
			title: {
                text: ' ',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
			labels: {
				format: '{value:,.0f}'
			},
			tickInterval: 10000,
			min: 0,
    		max: $('.total_y-axis_budget_js').val()
		}],
		
        xAxis: {
            categories: month_finance_label_arr,
			crosshair: true
        },
		 series: [{
            type: 'column',
            name: 'Budget',
            data: month_obligate_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'column',
            name: 'Forecast',
            data: month_forecaster_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'column',
            name: 'Spend',
            data: month_spend_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }],
		legend: {
            layout: 'vertical',
            align: 'left',
            x: 80,
            verticalAlign: 'top',
            y: 55,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
	
	});
	
	/* UPDATE value anytime
	var hybrid_chart = $('#hybridchart_container').highcharts();
	hybrid_chart.series[0].setData([400000, 550000, 400000, 580000, 350000, 450000, 950000, 450000, 300000, 200000, 100000, 55000]);*/
	
	/*====LINECHART======*/
	var line_chart;
	
	line_chart = new Highcharts.Chart({
		chart: {
            zoomType : false,
			renderTo: 'linechart_container',
            type: 'spline'
        },
		title: {
            text: ''
        },
        xAxis: {
            categories: month_finance_label_arr,
			crosshair: true
        },
		yAxis: [{
			title: {
                text: ' ',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
			labels: {
				format: '{value:,.0f}'
			},
			tickInterval: 10000,
			min: 0,
    		max: $('.total_y-axis_budget_js').val()
		}],
        plotOptions: {
            series: {
                colorByPoint: false
            }
        },
		   series: [{
            type: 'spline',
            name: 'Budget',
           data: month_obligate_data,
		   maxPointWidth: 50,
		   tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'spline',
            name: 'Forecast',
            data: month_forecaster_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }, {
             type: 'spline',
             name: 'Spend',
             data: month_spend_data,
			 maxPointWidth: 50,
			 tooltip: {
                valuePrefix: '$'
            }
        }],
		legend: {
            layout: 'vertical',
            align: 'Spend',
            x: 80,
            verticalAlign: 'top',
            y: 55,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
	
	});
	
	
	/*====HYBRID-CHART =====*/
	
	var hybrid_chart;
	hybrid_chart = new Highcharts.Chart({
		chart: {
            zoomType : false,
			renderTo: 'hybridchart_container',
            type: 'column',
        },
		title: {
            text: ''
        },
        xAxis: {
            categories: month_finance_label_arr,
			crosshair: true
        },
		yAxis: [{
			opposite: true,
			title: {
                text: ' ',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
			labels: {
				format: '{value:,.0f}'
			},
			tickInterval: 10000,
			min: 0,
			max: $('.total_y-axis_budget_js').val()
		}, // Primary yAx
		{ 
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
			labels: {
				format: '{value:,.0f}'
			},
			tickInterval: 10000,
			min: 0,
    		max: $('.total_y-axis_budget_js').val()

        }, { // Tertiary yAxis
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            opposite: true,
		
        },
		 { // Tertiary yAxis
			gridLineWidth: 0,
			title: {
				text: '',
				style: {
					color: Highcharts.getOptions().colors[2]
				}
			},
			labels: {
				format: '{value}',
				style: {
					color: Highcharts.getOptions().colors[2]
				}
			},
			opposite: true,
	     }
		],
        plotOptions: {
            series: {
                colorByPoint: false
            }
        },
		 series: [
		 {
            type: 'column',
            name: 'Budget',
            data: month_obligate_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'column',
            name: 'Forecast',
            data: month_forecaster_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'column',
            name: 'Spend',
            data: month_spend_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        },
		 {
            type: 'spline',
            name: 'Budget',
            data: month_obligate_data,
		    maxPointWidth: 50,
		   tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'spline',
            name: 'Forecast',
            data: month_forecaster_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }, {
             type: 'spline',
             name: 'Spend',
             data: month_spend_data,
			 maxPointWidth: 50,
			 tooltip: {
                valuePrefix: '$'
            }
        }],
		legend: {
            layout: 'vertical',
            align: 'left',
            x: 80,
            verticalAlign: 'top',
            y: 55,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
	
	});
});

$('#view_hybrid_chart').click(function(){
	$('.chart_blk').addClass('disp-none');
	$('.viewchart_btn').removeClass('btn-green').addClass('btn-blue');
	$(this).removeClass('btn-blue').addClass('btn-green');
	$('.hybridchart_blk').removeClass('disp-none');
});

$('#view_line_chart').click(function(){
	$('.chart_blk').addClass('disp-none');
	$('.viewchart_btn').removeClass('btn-green').addClass('btn-blue');
	$(this).removeClass('btn-blue').addClass('btn-green');
	$('.linechart_blk').removeClass('disp-none');
});

$('#view_bar_chart').click(function(){
	$('.chart_blk').addClass('disp-none');
	$('.viewchart_btn').removeClass('btn-green').addClass('btn-blue');
	$(this).removeClass('btn-blue').addClass('btn-green');
	$('.barchart_blk').removeClass('disp-none');
});
$('.chart_cont').width($('.barchart_blk .chart_cont').width());
//chart.series[0].data[[35.00,35.91,36.82,37.73,38.64]]; 

/*====toggle_years_data====*/
$('#toggle_years_data').click(function(){
	var hide_row = $('.other_year').filter(function(){
		return $(this).hasClass('disp-none');
	}).length;
	
	var elem_class=".data_row";
	if(hide_row>0){
		$(this).text("Hide All Years");
		$('#show_years_data_flag').val("yes");
	}
	else{
		var elem_class=".data_row.current_year";
		$(this).text("Show All Years");
		$('#show_years_data_flag').val("no");
	}
	
	var total_committed = sum_val(get_all_col_data($(elem_class).find('.committed_data')));
	var total_obligated = sum_val(get_all_col_data($(elem_class).find('.obligated_data')));
	var total_spent = sum_val(get_all_col_data($(elem_class).find('.actual_spend_data')));
	var total_available = sum_val(get_all_col_data($(elem_class).find('.available_data')));
	var total_forecast = sum_val(get_all_col_data($(elem_class).find('.forecast_data')));
	
	set_col_data('committed_data',total_committed);
	set_col_data('obligated_data',total_obligated);
	set_col_data('actual_spend_data',total_spent);
	set_col_data('available_data',total_available);
	set_col_data('forecast_data',total_forecast);
		
	$('.other_year').toggleClass('disp-none');
});

function get_all_col_data(elem){
	var arr = new Array();
	$(elem).each(function(index, el){
		var text = $(el).find('.value').val();
		text = text.replace(/,/g,'');
		arr.push(text);	
	});
	return arr;
}

function set_col_data(elem,val){
	$('#projects_finance_table tfoot').find('.'+elem).text('$'+Number(val).toLocaleString());
	$('#projects_finance_table tfoot').find('.'+elem+' .value').val(Number(val));
}

function sum_val(arr){
	var total = 0;
	$(arr).each(function(key, val){
		total = total + Number(val);
	});
	
	return total;
}




/*radio btn click*/
$('#mode_blk input[name="mode"]').click(function(){
	var val = $(this).val();
	//disp_quarter_arr = ["Q1","Q2","Q3","Q4"];
	//disp_month_arr = ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep'];
	var bar_chart = $('#barchart_container').highcharts();
	var line_chart = $('#linechart_container').highcharts();
	var hybrid_chart = $('#hybridchart_container').highcharts();
	if(val=="quarterly"){
		/*update bar chart*/
		bar_chart.xAxis[0].setCategories(quarter_finance_label_arr);
		bar_chart.series[0].setData(quarter_obligate_data);
		bar_chart.series[1].setData(quarter_forecaster_data);
		bar_chart.series[2].setData(quarter_spend_data);
	
		/*update line chart*/
		line_chart.xAxis[0].setCategories(quarter_finance_label_arr);
		line_chart.series[0].setData(quarter_obligate_data);
		line_chart.series[1].setData(quarter_forecaster_data);
		line_chart.series[2].setData(quarter_spend_data);
		
		/*update hybrid chart*/
		hybrid_chart.xAxis[0].setCategories(quarter_finance_label_arr);
		hybrid_chart.series[0].setData(quarter_obligate_data);
		hybrid_chart.series[1].setData(quarter_forecaster_data);
		hybrid_chart.series[2].setData(quarter_spend_data);
		hybrid_chart.series[3].setData(quarter_obligate_data);
		hybrid_chart.series[4].setData(quarter_forecaster_data);
		hybrid_chart.series[5].setData(quarter_spend_data);
	}
	else{
		/*update bar chart*/
		bar_chart.xAxis[0].setCategories(month_finance_label_arr);
		bar_chart.series[0].setData(month_obligate_data);
		bar_chart.series[1].setData(month_forecaster_data);
		bar_chart.series[2].setData(month_spend_data);
	
		/*update line chart*/
		line_chart.xAxis[0].setCategories(month_finance_label_arr);
		line_chart.series[0].setData(month_obligate_data);
		line_chart.series[1].setData(month_forecaster_data);
		line_chart.series[2].setData(month_spend_data);
		
		/*update hybrid chart*/
		hybrid_chart.xAxis[0].setCategories(month_finance_label_arr);
		hybrid_chart.series[0].setData(month_obligate_data);
		hybrid_chart.series[1].setData(month_forecaster_data);
		hybrid_chart.series[2].setData(month_spend_data);
		hybrid_chart.series[3].setData(month_obligate_data);
		hybrid_chart.series[4].setData(month_forecaster_data);
		hybrid_chart.series[5].setData(month_spend_data);
	}	
});
</script>
<?php unset($_SESSION['form_msg']);?>
</body>
</html>
