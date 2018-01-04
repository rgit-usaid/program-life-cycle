<?php include('config/config.inc.php');
	if(isset($_POST['logout_user']) && $_POST['logout_user']!=""){
		unset($_SESSION['user']);
	}
?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title>USAID-AMP</title>
<?php include('includes/resources.php');?> 
</head>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
	<div class="container-fluid" style="min-height:700px;">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div style="max-width:550px;margin:auto; margin-top:120px; background:#f8f8f8; padding:50px;">
					<header><h1 style='font-size:1.8em; font-family:"Source Sans Pro", "Helvetica Neue", "Helvetica", "Roboto", "Arial", sans-serif'>Sign in</h1></header>
					<form action="home" method="post" autocomplete="off">
						<div class="ip-outer">
							<span class="usa-input-error-message" id="input-error-message" role="alert">Please enter your name</span>
							<label for="input-type-text">Your Name<span class="usa-additional_text">Required</span></label>
							<input type="text" id="username" style='font-family:.5em; font-family:"Source Sans Pro", "Helvetica Neue", "Helvetica", "Roboto", "Arial", sans-serif'  name="username"/>
							<input type="submit"  value="Sign in" id="login"/>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php //include('includes/footer.php');?>
	</div>
	<script>
		$('#login').click(function(){
			var return_val = false;
			if($('#username').val()==""){
				$(this).closest('.ip-outer').addClass('usa-input-error');
				$(this).closest('.ip-outer').find('.usa-input-error-message').css({display:'block'});
				return_val = false;	
			}
			else{
				return_val = true;	
			}
			
			return return_val;
		});	
	</script>
</body>
</html>
