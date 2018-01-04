<?php include('config/functions.inc.php');

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
<body>
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
	<!--main-container start-->
	<div class="container-fluid main-container">
		<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="login-panel">
				<header><h1>Sign in</h1></header>
				<form action="home" method="post" autocomplete="off" id="login_form">
					<div class="ip-outer">
						<span class="error-message" role="alert">We didn’t find your email address in the HR Connect model. Please try again.</span>
						<label for="input-type-text">Your Email<span class="ip-imp">*</span></label>
						<input type="hidden" name="employee_id" id="employee_id"/>
						<input type="hidden" name="first_last_name" id="first_last_name"/>
						<input type="text" id="username" name="username" class="form-control"/>
						<input type="submit"  value="Sign in" id="login"  class="btn btn-success pull-right"/>
					</div>
				</form>
			</div>
		</div>
		</div>
	</div>
	<!--main-container end-->
	<!--footer start-->
	<?php include('includes/footer.php');?>
	<!--footer end-->
	<script>
		$('#login').click(function(){
			var return_val = false;
			if($('#username').val()==""){
				$(this).closest('.ip-outer').addClass('input-error');
				$(this).closest('.ip-outer').find('.error-message').css({display:'block'});
				return_val = false;	
			}
			else{
				var val = $('#username').val();
				$.ajax({
					url:'<?php echo API_HOST_URL_PROJECT;?>get_login_details.php',
					type:'POST',
					data:{'user_email':val},
					datatype:'jsonp',
					context:this,
					success:function(data){ 
						if(data['status_msg']=="Record Found"){
							var employee_id  = data['data']['employee_id'];	
							var first_last_name  = data['data']['first_last_name'];		
							$('#employee_id').val(employee_id);
							$('#first_last_name').val(first_last_name);
							$('#login_form').submit();
						}
						else{
							$('#username').val("");
							$('.ip-outer').addClass('input-error');
							$('.ip-outer').find('.error-message').css({display:'block'});
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
