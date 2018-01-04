<?php 
	$clp_sel ="cdcs";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>USAID-4</title>
	<link href="css/uswds.min.css" rel="stylesheet">
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Theme CSS -->
	<link href="css/style.css" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<style>
		
		.disp-none{
			display: none;
		}
	</style>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href=".">Home</a> &raquo; <a href="existing_project.php">Existing Project</a> &raquo; <a class="active">Project Finance</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/project_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal app_cdcs_no" action="" method="post">
						<div  class="form_grp" style="padding:20px 0px; font-size:16px;">
							<h3 class="form-blk-head" style="margin-top:0px; font-weight:bold;">Manage Project Finance</h3>
							<hr>
						</div>
						<div class="row" style="font-family:Arial, Helvetica, sans-serif">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<div style="height:55px; border-left:solid 6px #8c0001; padding:5px;">
									<div class="bold" style="font-size:18px;">$230,000</div>
									<div>
										Lifetime Budget
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<div style="height:55px; border-left:solid 6px #084a08; padding:5px;">
									<div class="bold" style="font-size:18px">$230,000</div>
									<div>
										Lifetime Actual Spend
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<div style="height:55px; border-left:solid 6px #ccc; padding:5px;">
									<div class="bold" style="font-size:18px">$230,000</div>
									<div>
										Lifetime Forecast
									</div>
								</div>
							</div>
						</div>
						<div class="form_grp"></div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 blk_head">
								Finance Profile 
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 bold" style="padding-top:10px">
								Choose One  &nbsp;&nbsp;<input id="month_radio" type="radio" value="Month" name="month"/><label for="month_radio">Month </label> &nbsp;&nbsp;<input id="quarter_radio" type="radio" value="Quarter" name="month"/><label for="quarter_radio">Quarter </label>
							</div>
						</div>
						<div class="form_grp clearfix"></div>
						<div class="form_grp">
							<button type="button" id="view_barchart">View as Bar Chart</button> <button type="button" id="view_linechart">View as Line Chart</button> 
							<button type="button" id="view_hybridchart">View as hybrid Chart</button>
						</div>
						<div id="barchart_container" class="chart-cont"></div>
						<div id="linechart_container" class="disp-none chart-cont"></div>
						<div id="hybridchart_container" class="disp-none chart-cont"></div>
						<div class="form_grp"></div>
						<div class="blk_head">
							Finance Details
						</div>
						<div>
							<table>
								<tr>
									<td>Year</td>
									<td>Committed</td>
									<td>Obligated</td>
									<td>Actual Spend</td>
									<td>Available</td>
									<td>Forecast</td>
								</tr>
								<tr>
									<td>2018-19</td>
									<td>$100,000</td>
									<td>$170,000</td>
									<td>$0</td>
									<td>$170,000</td>
									<td>$0</td>
								</tr>
							</table>
						</div>
						<div class="form_grp">
							<button type="button" class="usa-button-outline">Cancel</button> <button type="button" id="proceed">Save & Proceed</button> <button type="button">Save & Exit</button>
						</div>
					</form> 
				</div> 
			</div>
		</div>
		<!-- Help Content -->
		<div class="col-md-3">
			<div class="wrap-right-menu">
				<div id="help">
					<h3 class="text-center">HELP</h3>
					<hr>
					<div class="blk">
						 This screen only shows the budget, spend, and other financial data for this project.  Please use Phoenix to manage financial and accounting data for activities.<br/>
						<a id="project_finance">Move to Phoenix</a> 
					</div>	
				</div>
			</div>		
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/exporting.js"></script>
	<script type="text/javascript" src="js/highcharts.js"></script>
	<script>
		$('#show_team_member_blk').click(function(){
			$('#add_team_member').toggleClass('disp-none');
		});
		
		$('#show_team_blk').click(function(){
			$('#team_history_blk').toggleClass('disp-none');
		});
		
		$('#proceed').click(function(){
			window.location = "project_performance.php";
		});
		
		$('#project_finance').click(function(){
			//window.location = "project_finance.php";
		});
	</script>
	<script>
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
    		max: 100000			
		}],
		
        xAxis: {
            categories: ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep'],
			crosshair: true
        },
		 series: [{
            type: 'column',
            name: 'Budget',
            data: [13000, 26500, 40000, 2800, 1100, 3000, 5000, 4000, 500, 900, 500, 5000],
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'column',
            name: 'Forecast',
            data: [3400, 1200, 7000, 3000, 9000, 6000, 6000, 4000, 1000, 1000, 470, 4000],
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'column',
            name: 'Spend',
            data: [1450, 1200, 5600, 4400, 60000, 9330 , 0 , 0 , 0, 0],
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
            categories: ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep'],
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
    		max: 100000	
		}],
        plotOptions: {
            series: {
                colorByPoint: false
            }
        },
		   series: [{
            type: 'spline',
            name: 'Budget',
           data: [13000, 26500, 40000, 2800, 1100, 3000, 5000, 4000, 500, 900, 500, 5000],
		   tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'spline',
            name: 'Forecast',
            data: [3400, 1200, 7000, 3000, 9000, 6000, 6000, 4000, 1000, 1000, 470, 4000],
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'spline',
            name: 'Spend',
             data: [1450, 1200, 5600, 4400, 60000, 9330 , 0 , 0 , 0, 0],
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
            categories: ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep'],
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
    		max: 100000
           
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
    		max: 100000	

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
            data: [13000, 26500, 40000, 2800, 1100, 3000, 5000, 4000, 500, 900, 500, 5000],
            tooltip: {
                valuePrefix: '$'
            }

        }, {
            name: 'Forecast',
            type: 'spline',
			maxPointWidth: 50,
            data: [3400, 1200, 7000, 3000, 9000, 6000, 6000, 4000, 1000, 1000, 470, 4000],
            tooltip: {
                valuePrefix: '$'
            },
        },{
            name: 'Spend',
            type: 'spline',
			maxPointWidth: 50,
            data: [1450, 1200, 5600, 4400, 60000, 9330 , 0 , 0 , 0, 0],
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

$('#view_barchart').click(function(){
	$('.chart-cont').addClass('disp-none');
	$('#barchart_container').removeClass('disp-none');
});


$('#view_linechart').click(function(){
	$('.chart-cont').addClass('disp-none');
	$('#linechart_container').removeClass('disp-none');
});

$('#view_hybridchart').click(function(){
	$('.chart-cont').addClass('disp-none');
	$('#hybridchart_container').removeClass('disp-none');
});
</script>
<style>
	.chart-cont{min-width:650px}
</style>
</body>
</html>