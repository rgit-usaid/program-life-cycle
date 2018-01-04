<?php 
include('config/functions.inc.php');

	if(isset($_POST['logout_user']) && $_POST['logout_user']!="")
	{
		unset($_SESSION['user']);
	}

$lp_sel="home";
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
	<link href="css/responsive.dataTables.min.css" type="text/css" rel="stylesheet">
	<!-- Theme CSS -->
	<link href="css/style.css" rel="stylesheet">
	<link href="css/login_css.css" rel="stylesheet" />
	<!-- Custom Fonts -->
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<style>
		span>a:visited{
			color: #fff;
		}
		.dataTables_length{display:none;}
		#left-menu > li >a >p{
			display: table;
		}
		#left-menu > li >a >img{
			margin-top: 18px;
			margin-right: 10px;
			-ms-transform: rotate(270deg); /* IE 9 */
			-webkit-transform: rotate(270deg); /* Chrome, Safari, Opera */
			transform: rotate(270deg);
		}
		#left-menu > li >a {
			text-decoration: none;
		}
		#manage-table_wrapper{
			margin-top: 5px;
		}
		.btn-warning,.btn-danger{
			border-radius: 3px;
		}
	</style>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid"> 
		<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="login-panel">
				<legend>Sign in</legend>
				<form action="index.php" method="post" autocomplete="off" id="login_form">
					<fieldset>
					<div class="ip-outer">
						<div id="err-msg" style="display:none;color:#FF0000;">We didn't find your email address in the HR Connect model. Please try again. </div>
						<div class="extra_ht"></div><div class="extra_ht"></div>
						<label class="" for="input-error">Your Email <span class="usa-additional_text">Required</span></label>
						<input type="hidden" name="employee_id" id="employee_id"/>
						<input type="text" id="username" name="username"/>
						<div class="clearfix"></div>
					</div>
					<input type="submit"  value="Sign in" id="login" />
					</fieldset>
				</form>
			</div>
		</div>
		</div>
	</div>
	<footer class="usa-footer usa-footer-slim" role="contentinfo">
    <div class="usa-footer-primary-section">
      <div class="usa-grid-full">
        <nav class="usa-footer-nav usa-width-two-thirds">
          <ul class="usa-unstyled-list">
            <li class="usa-width-one-fourth usa-footer-primary-content">
              <a class="usa-footer-primary-link" href="http://rgdemo.com/usaid/phoenix3/" target="_blank">Phoenix</a>
            </li>
            <li class="usa-width-one-fourth usa-footer-primary-content">
              <a class="usa-footer-primary-link" href="http://rgdemo.com/usaid/glaas3/" target="_blank">GLAAS</a>
            </li>
            <li class="usa-width-one-fourth usa-footer-primary-content">
              <a class="usa-footer-primary-link" href="http://rgdemo.com/usaid/hr-connect/" target="_blank">HR Connect</a>
            </li>
			<li class="usa-width-one-fourth usa-footer-primary-content">
              <a class="usa-footer-primary-link" href="http://rgdemo.com/usaid/strategy3/" target="_blank">Strategy</a>
            </li>
            <li class="usa-width-one-fourth usa-footer-primary-content">
              <a class="usa-footer-primary-link" href="https://docs.google.com/document/d/1COwMKs9n_J43_rnn9KEgNzS9ejs4h9QrvAu-wByPcRY/edit" target="_blank">Feedback</a>
            </li>
          </ul>
        </nav>
        <div class="usa-width-one-third clear">
  
        </div>
      </div>
    </div>

	<div class="usa-footer-secondary_section ">
      <div class="usa-grid">
        <div class="usa-footer-logo">
          <img class="usa-footer-slim-logo-img" src="img/logo.png" alt="Logo image">
        </div>
      </div>
    </div>
	<div class="copyright">&copy; usaid.gov</div>
  </footer>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.responsive.js"></script>
	<script type="text/javascript">	
		$(document).ready(function() {
			$('#manage-table').DataTable({"lengthMenu": [ 10, 25, 50, 100 ],responsive: true});
		});	
	</script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script>
		$('#login').click(function(){
		
			var return_val = false;
			if($('#username').val()==""){
				$('.ip-outer').find('#err-msg').css({display:'block'});
				document.getElementById('username').style.borderColor = "red";
				return_val = false;	
			}
			else{
				var val = $('#username').val();
				
				$.ajax({
					url:'<?php echo API_HOST_URL_PROJECT2;?>/get_login_details.php',
					type:'POST',
					data:{'user_email':val},
					datatype:'jsonp',
					context:this,
					success:function(data){
						if(data['status_msg']=="Record Found"){
							var employee_id  = data['data']['employee_id'];	
							$('#employee_id').val(employee_id);
							$('#login_form').submit();
						}
						else{
							$('#username').val("");
							$('.ip-outer').find('#err-msg').css({display:'block'});
						}
					}
				});
				return_val = false;	
			}
			
			return return_val;
		});	
	</script>
 
  
</body>
</html>