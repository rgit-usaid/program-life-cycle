<?php include('config/functions.inc.php');
##==validate user====
validate_user();

###request for get single project details using project id ===========
$project_id = '';
$activity_id = '';
if(isset($_SESSION['project_id'])){
	$project_id = $_SESSION['project_id'];
}

if(isset($_SESSION['project_id']) && isset($_REQUEST['activity_id']))
{	
	$_SESSION['activity_id'] = $activity_id = $_REQUEST['activity_id'];
}

if(isset($_SESSION['project_id']) && isset($_SESSION['activity_id']))
{	
	$activity_id = $_SESSION['activity_id'];
}

if($project_id!="" && $activity_id!=""){
	##==get project_info
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);

	##==get project_stage_info
    $url = API_HOST_URL_PROJECT."api_demo.php?stage"; 
  	$project_stage_arr = requestByCURL($url);
	
	
	$url = "http://rgdemo.com/usaid/api/get_project_activity.php?project_id=".$project_id."&activity_id=".$activity_id;  
    $project_activity_arr   = requestByCURL($url);
	
	$activity_start_date = $project_activity_arr['data']['actual_start_date'];
	$activity_end_date = $project_activity_arr['data']['actual_end_date'];
	$fiscal_year_start = date('Y',strtotime('-1 year',strtotime($project_activity_arr['data']['actual_start_date']))); 
	$fiscal_year_end = date('Y',strtotime('-1 year', strtotime($project_activity_arr['data']['actual_end_date']))); 
}

$finance_arr = array();
$finance_arr[] = array('actvity_id'=>'000037-001','year'=>"2016-03-23",'aproved_budget'=>'90300','spent'=>'47000','committed'=>'80000',"balance"=>'80000'); 
$finance_arr[] = array('actvity_id'=>'000037-002','year'=>"2017-03-23",'aproved_budget'=>'102600','spent'=>'10800','committed'=>'100000',"balance"=>'56000'); 
$finance_arr[] = array('actvity_id'=>'000037-002','year'=>"2018-03-23",'aproved_budget'=>'300000','spent'=>'23600','committed'=>'60000',"balance"=>'44000'); 
$finance_arr[] = array('actvity_id'=>'000037-002','year'=>"2019-03-23",'aproved_budget'=>'300000','spent'=>'23600','committed'=>'60000',"balance"=>'64000'); 
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
		<?php include('includes/activity_header.php');?>
		<input type="hidden" value="<?php echo $activity_start_date;?>" id="activity_start_date"/>
		<input type="hidden" value="<?php echo $activity_end_date;?>" id="activity_end_date"/>
		<!--main container blk start-->
		<div class="tbl-block">
			<div class="tbl-caption">
				<div class="tbl-content-head"><?php if(!isset($edit_mode)){?>Project Activity Finance<?php } ?></div>
				<div class="tbl-content-btn">
					<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
					<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
				</div>
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
									$492,900<?php //$sum = array_map("return_elem", $finance_arr,'aproved_budget');
									print_r($sum);
									?>
								</div>
								<div class="sub-head">Lifetime Budget</div>
							</div>	
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="finance_dashboard bdspend">
								<div class="head">
									$492,900<?php //echo $sum = number_format(array_sum( array_map( function($element){ return $element['aproved_budget']; }, $finance_arr)));?>
								</div>
								<div class="sub-head">Lifetime Actual Spend</div>
							</div>	
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="finance_dashboard bdforecast">
								<div class="head">
									$492,900<?php //echo $sum = number_format(array_sum( array_map( function($element){ return $element['aproved_budget']; }, $finance_arr)));?>
								</div>
								<div class="sub-head">Lifetime Forecast</div>
							</div>	
						</div>
					</div>
					<header><h2 class="form-blk-head">Finance Profile</h2></header>
					<div class="row project-detail-textarea">
						<!-- line chart canvas element -->
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<a class="btn btn-green viewchart_btn" id="view_bar_chart">View as bar chart</a> <a class="btn btn-blue viewchart_btn" id="view_line_chart">View as line chart</a> <a class="btn btn-blue viewchart_btn" id="view_hybrid_chart">View as hybrid chart</a>
							
						<div id="mode_blk">
							<div class="bold">Choose One</div>
							<div><label><input type="radio"  value="monthly" checked="checked" name="mode"/> Month</label></div>
							<div><label><input type="radio" value="quarterly" name="mode"/> Quarter</label></div>
						</div>
							<div class="clear extra_ht"></div><div class="extra_ht"></div>
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
					<header><h3 class="form-blk-head">Finance Details</h3></header>
					<a id="toggle_years_data" class="btn btn-blue">Show All Years</a> <a class="btn btn-gray" id="open_forecaster_popup_btn">Open Forecaster</a> <a class="btn btn-gray" id="open_planner_popup_btn">Open Planner</a>
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
									<tfoot>
									<tr class="head">
										<td class="sm_wdth">Total</td>
										<td class="sm_wdth committed_data">$<?php echo number_format($finance_arr[0]['aproved_budget']);?></td>
										<td class="comm_wdth obligated_data">$<?php echo number_format($finance_arr[0]['aproved_budget']);?></td>
										<td class="lg_wdth actual_spend_data">$<?php echo number_format($finance_arr[0]['aproved_budget']);?></td>
										<td class="comm_wdth available_data">$<?php echo number_format($finance_arr[0]['aproved_budget']);?></td>
										<td class="comm_wdth forecast_data">$<?php echo number_format($finance_arr[0]['aproved_budget']);?></td>
									</tr>
									</tfoot>
									<tbody>
									<?php 
									for($i=0;$i<count($finance_arr);$i++){?>
									<tr class="data_row <?php if($i>0) {?> disp-none other_year <?php }?>">
										<td class="sm_wdth">
											<?php echo date('Y',strtotime($finance_arr[$i]['year']));?>
											<input type="hidden" value="<?php echo date('Y',strtotime($finance_arr[$i]['year']));?>" class="finance_year"/>
										</td>
										<td class="comm_wdth committed_data">$<span class="value"><?php echo number_format($finance_arr[$i]['aproved_budget']);?></span></td>
										<td class="sm_wdth obligated_data">$<span class="value"><?php echo number_format($finance_arr[$i]['aproved_budget']);?></span></td>
										<td class="lg_wdth actual_spend_data">$<span class="value"><?php echo number_format($finance_arr[$i]['aproved_budget']);?></span></td>
										<td class="comm_wdth available_data">$<span class="value"><?php echo number_format($finance_arr[$i]['aproved_budget']);?></span></td>
										<td class="comm_wdth forecast_data">$<span class="value"><?php echo number_format($finance_arr[$i]['aproved_budget']);?></span></td>
									</tr>
									<?php }?>
									</tbody>
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
	<div id="open_forecaster_popup" style="background:url('<?php echo HOST_URL?>img/light.png');">
		<div class="inner_cont">
			<div class="popup_header">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<header><h2 class="heading text-danger">Forecaster</h2></header>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div style="float:right;margin-top:10px;font-size:30px;cursor:pointer;"><a class="close_btn text-danger" title="close"><i class="fa fa-times-circle" aria-hidden="true"></i></a></div><div class="extra_ht"></div>
					</div>
				</div>
			</div>
			<div class="popup_datacont">
				<div class="row" style="font-size:18px">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<header><h3>Total Budget : $492,900</h3></header>
				</div>
				<div class="extra_ht clear"></div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 fiscal_year_start">
					<span class="text-success"></span> <span class="value"></span>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 fiscal_year_end">
					<span class="text-success"></span> <span class="value"></span>
				</div>
				</div>
			</div>
			<div class="extra_ht"></div><div class="extra_ht"></div><div class="extra_ht"></div><div class="extra_ht"></div>
			<div style="max-height:550px; overflow-y:auto; overflow-x:hidden">
				<div class="row data_cont">
					
				</div>
			</div>
			<div class="popup_footer">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<a class="btn btn-green">Save</a>
				</div>
			</div>
			</div>
		</div>
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
<script>
	setTimeout(function(){
		$('#submission_msg').html("");
	},10000);	
</script>
<script src="<?php echo HOST_URL?>js/exporting.js"></script>
<script src="<?php echo HOST_URL?>js/highcharts.js"></script>
<script type="text/javascript">
$(function () {
	Highcharts.setOptions({
		lang: {
			thousandsSep: ','
		}
	});
	Highcharts.setOptions({
    	colors: ['#8C0001','#ccc','#084A08']
	});
	
	var hybrid_chart;
	hybrid_chart = new Highcharts.Chart({
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
			tickInterval: 100000,
			min: 0,
    		max: 1000000			
		}],
		
        xAxis: {
            categories: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec','Jan', 'Feb', 'Mar'],
			crosshair: true
        },
		 series: [{
            type: 'column',
            name: 'Budget',
            data: [550000, 400000, 450000, 520000, 900000, 450000, 450000, 400000, 450000, 250000, 150000, 100000],
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'column',
            name: 'Forecast',
            data: [400000, 550000, 400000, 580000, 350000, 450000, 950000, 450000, 300000, 200000, 100000, 55000],
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'column',
            name: 'Spend',
            data: [400000, 50000, 400000, 50000, 45000, 0 , 0 , 0 , 0, 0],
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
            categories: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec','Jan', 'Feb', 'Mar'],
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
			tickInterval: 100000,
			min: 0,
    		max: 1000000	
		}],
        plotOptions: {
            series: {
                colorByPoint: false
            }
        },
		   series: [{
            type: 'spline',
            name: 'Budget',
           data: [550000, 400000, 450000, 520000, 900000, 450000, 450000, 400000, 450000, 250000, 150000, 100000],
		   tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'spline',
            name: 'Forecast',
            data: [400000, 550000, 400000, 580000, 350000, 450000, 950000, 450000, 300000, 200000, 100000, 55000],
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'spline',
            name: 'Spend',
             data: [400000, 50000, 400000, 50000, 45000, 0 , 0 , 0 , 0, 0],
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
	
	
	/*====BARCHART =====*/
	
	var bar_chart;
	bar_chart = new Highcharts.Chart({
		chart: {
            zoomType : false,
			renderTo: 'hybridchart_container',
            type: 'column',
        },
		title: {
            text: ''
        },
        xAxis: {
            categories: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec','Jan', 'Feb', 'Mar'],
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
			tickInterval: 100000,
			min: 0,
    		max: 1000000
           
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
			tickInterval: 100000,
			min: 0,
    		max: 1000000	

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
		 series: [{
            name: 'Budget',
            type: 'column',
			maxPointWidth: 50,
            yAxis: 1,
           data: [550000, 400000, 450000, 520000, 900000, 450000, 450000, 400000, 450000, 250000, 150000, 100000],
            tooltip: {
                valuePrefix: '$'
            }

        }, {
            name: 'Forecast',
            type: 'spline',
			maxPointWidth: 50,
            data: [400000, 550000, 400000, 580000, 350000, 450000, 950000, 450000, 300000, 200000, 100000, 55000],
            tooltip: {
                valuePrefix: '$'
            },
        },{
            name: 'Spend',
            type: 'spline',
			maxPointWidth: 50,
            data: [400000, 50000, 400000, 50000, 45000, 0 , 0 , 0 , 0, 0],
            tooltip: {
                valuePrefix: '$'
            },
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
	
	
	if(hide_row>0){
		var total_committed = sum_val(get_all_col_data($('.data_row').find('.committed_data')));
		var total_obligated = sum_val(get_all_col_data($('.data_row').find('.obligated_data')));
		var total_spent = sum_val(get_all_col_data($('.data_row').find('.actual_spend_data')));
		var total_available = sum_val(get_all_col_data($('.data_row').find('.available_data')));
		var total_forecast = sum_val(get_all_col_data($('.data_row').find('.forecast_data')));
		
		set_col_data('committed_data',total_committed);
		set_col_data('obligated_data',total_obligated);
		set_col_data('actual_spend_data',total_spent);
		set_col_data('available_data',total_available);
		set_col_data('forecast_data',total_forecast);
		
		$(this).text("Hide all years");
		$('#show_years_data_flag').val("yes");
	}
	else{
		var total_committed = sum_val(get_all_col_data($('.data_row:eq(0)').find('.committed_data')));
		var total_obligated = sum_val(get_all_col_data($('.data_row:eq(0)').find('.obligated_data')));
		var total_spent = sum_val(get_all_col_data($('.data_row:eq(0)').find('.actual_spend_data')));
		var total_available = sum_val(get_all_col_data($('.data_row:eq(0)').find('.available_data')));
		var total_forecast = sum_val(get_all_col_data($('.data_row:eq(0)').find('.forecast_data')));
		
		set_col_data('committed_data',total_committed);
		set_col_data('obligated_data',total_obligated);
		set_col_data('actual_spend_data',total_spent);
		set_col_data('available_data',total_available);
		set_col_data('forecast_data',total_forecast);
		
		$(this).text("Show all years");
		$('#show_years_data_flag').val("no");
	}
	$('.other_year').toggleClass('disp-none');
});

function get_all_col_data(elem){
	var arr = new Array();
	$(elem).each(function(index, el){
		var text = $(el).find('.value').text();
		text = text.replace(/,/g,'');
		arr.push(text);	
	});
	return arr;
}

function set_col_data(elem,val){
	$('#projects_finance_table tfoot').find('.'+elem).text('$'+Number(val).toLocaleString());
}

function sum_val(arr){
	var total = 0;
	$(arr).each(function(key, val){
		total = total + Number(val);
	});
	
	return total;
}


$('#open_forecaster_popup_btn').click(function(){
	$('body').scrollTop(0);
	$('#open_forecaster_popup').find('.heading').text('Forecaster');
	
	//var qarterly_date = new Array([{'quarter':'Q1','year':'2016'},{'quarter':'Q2','year':'2016'},{'quarter':'Q3','year':'2016'}]);
	var mode = $('#mode_blk').find('input[name="mode"]:checked').val();
	var finance_year = new Array();

	$('#open_forecaster_popup').find('.fiscal_year_start').find('.text-success').text("");
	$('#open_forecaster_popup').find('.fiscal_year_start').find('.value').text("");
	$('#open_forecaster_popup').find('.fiscal_year_end').find('.text-success').text("");
	$('#open_forecaster_popup').find('.fiscal_year_end').find('.value').text("");
		
	if($('#show_years_data_flag').val()=="no"){
		finance_year.push($('#projects_finance_table').find('.data_row:eq(0)').find('.finance_year').val());
		$('#open_forecaster_popup').find('.fiscal_year_start').find('.text-success').text("Start Fiscal Year :");
		$('#open_forecaster_popup').find('.fiscal_year_start').find('.value').text(finance_year[0]);
	}
	else{
		$('#projects_finance_table').find('.data_row').each(function(index, elem){
			var temp_year = $(elem).find('.finance_year').val();
			finance_year.push(temp_year);
		});
		
		$('#open_forecaster_popup').find('.fiscal_year_start').find('.text-success').text("Start Fiscal Year :");
		$('#open_forecaster_popup').find('.fiscal_year_start').find('.value').text(finance_year[0]);
		$('#open_forecaster_popup').find('.fiscal_year_end').find('.text-success').text("End Fiscal Year :");
		$('#open_forecaster_popup').find('.fiscal_year_end').find('.value').text(finance_year[finance_year.length-1]);
	}
	$('#open_forecaster_popup').find('.data_cont').html("");
	var activity_start_date = $('#activity_start_date').val();
	var activity_end_date = $('#activity_end_date').val();
	
	
	if(mode=="monthly"){	
		var monthly_date = get_dates_from_fiscal_year(activity_start_date,activity_end_date,finance_year,mode);
		for(var key in monthly_date ) {
			$(monthly_date[key]).each(function(i,a){
				var disabled_attr ='';
				if(monthly_date[key]['disabled']=="disabled"){
					disabled_attr ="disabled='disabled'";
				}
				var html = '<div class="data_row col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="row"><div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 label_name bold text-right">'+monthly_date[key]['label']+' '+monthly_date[key]['year']+'</div><div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ip-blk"><input type="text" class="form-control" '+disabled_attr+' /></div><div class="extra_ht clear"></div></div></div>';
				$('#open_forecaster_popup').find('.data_cont').append(html); 
			});
  		}
	}
	else if(mode=="quarterly"){
		var monthly_date = get_dates_from_fiscal_year(activity_start_date,activity_end_date,finance_year,mode);
		for(var key in monthly_date) {
			console.log(monthly_date);
			$(monthly_date[key]).each(function(i,a){
				var disabled_attr ='';
				if(monthly_date[key]['disabled']=="disabled"){
					disabled_attr ="disabled='disabled'";
				}
				var html = '<div class="data_row col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="row"><div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 label_name bold text-right">'+monthly_date[key]['label']+' '+monthly_date[key]['year']+'</div><div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ip-blk"><input type="text" class="form-control" '+disabled_attr+' /></div><div class="extra_ht clear"></div></div></div>';
				$('#open_forecaster_popup').find('.data_cont').append(html); 
			});
  		}
	}
	
	$('#open_forecaster_popup').css({display:'block'});
});

function get_dates_from_fiscal_year(act_start_date, act_end_date, finance_year, mode){
	var date_arr = new Array();
	var month_arr = new Array("Jan","Feb","Mar","Apr","May","June","Jul","Aug","Sep","Oct","Nov","Dec");
	var quarter_arr = new Array();
	quarter_arr[10] = "Q1";
	quarter_arr[1] = "Q2";
	quarter_arr[4] = "Q3";
	quarter_arr[7] = "Q4"
	var month_temp_arr = new Array();
	var year_diff = 0;
	var year_temp = 0;
	
	var act_start_date = act_start_date.split("/");
	var temp_act_start_date = new Date(Number(act_start_date[2]),Number(act_start_date[0])-1,1);
	
	
	var act_end_date = act_end_date.split("/");
	var temp_act_end_date = new Date(Number(act_end_date[2]),Number(act_end_date[0])-1,1);
	
	$(finance_year).each(function(index, year_val){ /*loop in all selected year*/
		
		var start_fiscal_year = Number(year_val)-1; /*starting fiscal year of this year*/
		var start_fiscal_date = new Date(start_fiscal_year,9,1); /*starting fiscal year date of this year*/
		
		var end_fiscal_year = Number(year_val); /*ending fiscal year of this year*/
		var end_fiscal_date = new Date(end_fiscal_year,8,30); /*ending fiscal year date of this year*/
		
		year_diff = Number(end_fiscal_year) - start_fiscal_year + 1; /*difference of years*/
		
		year_temp = start_fiscal_year;
			
		if(mode=="monthly"){ /*if mode is monthly*/
			for(var i=0; i < year_diff; i++){
				$(month_arr).each(function(month_in, month_name){
					var temp_date = new Date(year_temp,month_in,1);
					var disbled_attr = '';							
			
					if(temp_date>=start_fiscal_date && temp_date<end_fiscal_date){ 
						if(temp_act_start_date>temp_date ||  temp_date>temp_act_end_date){
							disbled_attr = "disabled";
						}
						/*if current date is lie between starting and ending fiscal year than add month objct*/
						var obj = new Object({'label':month_name,'year':year_temp,'disabled':disbled_attr});
						month_temp_arr.push(obj);								
					}
				});
				year_temp = year_temp +1;
			}
		}
		else if(mode=="quarterly"){ /*if mode is quarterly*/
			var start_fiscal_year = Number(year_val)-1; /*starting fiscal year of this year*/
			var start_fiscal_date = new Date(start_fiscal_year,9,1); /*starting fiscal year date of this year*/
			
			var end_fiscal_year = Number(year_val); /*ending fiscal year of this year*/
			var end_fiscal_date = new Date(end_fiscal_year,8,30); /*ending fiscal year date of this year*/
			
			year_diff = Number(end_fiscal_year) - start_fiscal_year + 1; /*difference of years*/
			
			year_temp = start_fiscal_year;
			
			for(var i=0; i < year_diff; i++){
				
				for(quarter_in in quarter_arr){
					var temp_date = new Date(year_temp,Number(quarter_in)-1,1);
					var disbled_attr = '';							
					if(temp_date>=start_fiscal_date && temp_date<end_fiscal_date){ 
						if(temp_act_start_date>temp_date ||  temp_date>temp_act_end_date){
							disbled_attr = "disabled";
						}
						/*if current date is lie between starting and ending fiscal year than add month objct*/
						var obj = new Object({'label':quarter_arr[quarter_in],'year':year_temp,'disabled':disbled_attr});
						month_temp_arr.push(obj);								
					}
				}
				
				
				year_temp = year_temp +1;
			}
		}
	});
	
	return month_temp_arr;
}

$('#open_forecaster_popup .close_btn').click(function(){
	$('#open_forecaster_popup').css({'display':'none'});
});

$('#open_planner_popup_btn').click(function(){
	$('#open_forecaster_popup_btn').trigger('click');
	$('#open_forecaster_popup').find('.heading').text('Planner');
});
</script>
<?php unset($_SESSION['form_msg']);?>
</body>
</html>
