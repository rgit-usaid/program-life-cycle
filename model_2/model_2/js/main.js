/*generate format date for form submission*/
$('.date_ip').blur(function(){
	var month = $(this).closest('.project_dates').find('.month').val();
	var date = $(this).closest('.project_dates').find('.date').val();
	var year = $(this).closest('.project_dates').find('.year').val();
	if(month.length >0 && month.length<2 && month<10){
		month="0"+month;
	}
	
	if(date.length >0 && date.length<2 && date<10){
		date="0"+date;
	}

	var formatted_date = month+'/'+date+'/'+year;
	$(this).closest('.project_dates').next('.formatted_date').val("");
	if(month!="" && date!="" && year!=""){
		$(this).closest('.project_dates').next('.formatted_date').val(formatted_date);
	}
});

/*generate format date for form submission*/
$(".tbl-up").click(function(){
	$(this).closest(".tbl-caption").next(".table-container").slideUp("fast");
});
$(".tbl-down").click(function(){
	$(this).closest(".tbl-caption").next(".table-container").slideDown("fast");
});


/*generate format date for form submission*/
$.fn.validate_ip = function() {
	var val = this.val();
	if(this.hasClass('only_num')){ 
		if(val.search(/^[0-9]+$/)!=-1){
			$(this).removeClass('error_ip invalid_ip');
			
			if(this.hasClass('month')){
				val = Number(val);
				if(val > 12 ){
					$(this).addClass('error_ip invalid_ip');	
				}
				else{
					$(this).removeClass('error_ip invalid_ip');
				}
			}
	
			if(this.hasClass('date')){
				val = Number(val);
				if(val > 31){
					$(this).addClass('error_ip invalid_ip');	
				}
				else{
					$(this).removeClass('error_ip invalid_ip');
				}
			}
	
			if(this.hasClass('year')){
				val = Number(val);
				if(val < 1970){
					$(this).addClass('error_ip invalid_ip');	
				}
				else{
					$(this).removeClass('error_ip invalid_ip');
				}
			}
		}
		else if(!$(this).hasClass('can_be_blank')){
			$(this).addClass('error_ip invalid_ip');
		}
	}
	else if(this.hasClass('only_num_with_blank')){ 
		if(val.search(/^[0-9]+$/)==-1 && $(this).val()!=""){
			$(this).addClass('error_ip invalid_ip');
		}
		else{
			$(this).removeClass('error_ip invalid_ip');
		}
	}
	else if(this.hasClass('only_string')){
		if(val.search(/^[a-zA-Z\s]+$/)!=-1){
			$(this).removeClass('error_ip invalid_ip');
		}
		else{
			$(this).addClass('error_ip invalid_ip');	
		}
	}
	else if(this.hasClass('formatted_amount')){
		if(val.search(/^[0-9,$\s]+$/)>-1){
			if(val.indexOf('$')>0){
				$(this).addClass('error_ip invalid_ip');
			}
			else{
				$(this).removeClass('error_ip invalid_ip');
			}
		}
		else if(val!=""){
			$(this).addClass('error_ip invalid_ip');	
		}
	}
}

/*validate ip on keypress on keyup*/
$('.date_ip').keypress(function(){
	var val = $(this).val();
	if(($(this).hasClass('month') || $(this).hasClass('date')) && val.length>1){
		return false;
	}
	
	if($(this).hasClass('year') && val.length>3){
		return false;
	}
});
$('.date_ip').keyup(function(){
	$(this).validate_ip();
});

$('.only_string').keyup(function(){
	$(this).validate_ip();
});

/*resize textarea*/
$(document).on('focus','.autoh_textarea',function(){
	if($(this).val()!="" && $(this).attr('readonly')!="readonly"){
		$(this).animate({height:'250px'});
	}
});
$(document).on('blur','.autoh_textarea',function(){
	$(this).height('');
});

/*lookahead search element event*/
$(document).on('click','.ajax_data .elem',function(){
	ajax_elem_click($(this));
});
$(document).on('focus','.ajax_data .elem',function(e){
	$(this).addClass('select');
});
$(document).on('blur','.ajax_data .elem',function(){
	$(this).removeClass('select');
});
$(document).on('keydown','.ajax_data .elem',function(e){
	var keycode = e.which || e.keycode; 
	if(keycode==13){
		ajax_elem_click($(this));
	}
});

$(document).on('keydown','.ajax_data',function(e){
	var keycode = e.which || e.keycode;
	var child_length = $('.ajax_data .elem').length;
	var elem_index = $('.ajax_data .elem:focus').index();
	if(keycode==40){
		if(elem_index < child_length){ elem_index = elem_index+1; }
		$('.ajax_data .elem').eq(elem_index).trigger('focus');
	}
	else if(keycode==38){
		if(elem_index >0 ){ elem_index = elem_index- 1; }
		$('.ajax_data .elem').eq(elem_index).trigger('focus');
	}
});
$( "body" ).click(function( event ) {
	//console.log('1');
});
$(document).on('focus','body',function(e){
	//console.log(e.target.id);
	if(e.target.id!="find_all_project"/*|| e.target.className.search('elem') ==-1*/){
	   //console.log(e.target.id);
	   if(e.target.className.search('elem')==-1 && (e.target.id==="" || e.target.id==="undefined" || e.target.id== null)){
		   $('.ajax_data').html("");
	   }
	}
});

