$(document).ready(function(){
	$('.hire,.nonhire').hide();
	$('.hire_type').click(function(){
	$('.hire,.nonhire').hide();
	
	if($('input[name="radio_hire"]:checked').val()=='Direct Hire')
	{
		$('.hire').show();
	}
	else{
		$('.nonhire').show();
	}
	 
	});
	///onload
	if($('input[name="radio_hire"]:checked').val()=='Direct Hire')
	{
		$('.hire').show();
	}
	if($('input[name="radio_hire"]:checked').val()=='Non-Direct Hire')
	{
		$('.nonhire').show();
	}
		 
});

        /*====code for manage foreign or general input=====*/
        $(document).ready(function(){
            $('.foreign_service,.general_service').hide();
            $('.service_type').click(function(){
                $('.foreign_service,.general_service').hide();
                
                if($('input[name="service_type"]:checked').val()=='Foreign')
                {
                    $('.foreign_service').show();
                }
                else{
                    $('.general_service').show();
                }
                 
            });
            ///onload
            if($('input[name="service_type"]:checked').val()=='Foreign')
            {
                $('.foreign_service').show();
            }
            if($('input[name="service_type"]:checked').val()=='General')
            {
                $('.general_service').show();
            }
             
        });