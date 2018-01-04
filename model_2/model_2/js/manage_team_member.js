/*radio btn selection*/
$('.threshold label').click(function(){
	$(this).closest('.threshold').find('label').removeClass('select');
	$(this).addClass('select');
});

/*hide old team btn click*/
$('.hide_old_team_btn').click(function(){
	$('.old_team_blk').slideUp();
	$(this).addClass('disp-none');
	$('.show_old_team_btn').removeClass('disp-none');
});

/*show old team btn click*/
$('.show_old_team_btn').click(function(){
	$('.old_team_blk').slideDown();
	$(this).addClass('disp-none');
	$('.hide_old_team_btn').removeClass('disp-none');
});

/*add new team btn click*/
$('.add_new_team').click(function(){
	if($('.add_team_blk').hasClass('disp-none')){
		$('.add_team_blk').removeClass('disp-none');
	}
	else{
		$('.add_team_blk').addClass('disp-none');
	}
});


/*show save btn of add new team*/
function show_save_btn(){
	var empty_date_ip =0;
	$('.date_ip').each(function(index, elem){
		if($(elem).val()==="" || $(elem).val()==false){
			empty_date_ip++;
		}
	});
	
	if($('#find_team_member').val()!="" && $('.team_role:checked').length>0 && empty_date_ip ==0 && $('#find_team_member_id').val()!=""){
		$('.save_team').removeClass('disp-none'); 
	}
	else{
		$('.save_team').addClass('disp-none'); 
	}
	
}

/*lookahead search*/
$('#find_team_member').keyup(function(){
	var name = $(this).val();
	if($(this).prev('.activity_id').length<=0){
		var url = 'http://rgdemo.com/usaid/api/get_hr_employee_search.php';
	}
	else{
		var url = 'http://rgdemo.com/usaid/api/get_hr_employee_search.php';
	}
	if(name.length>1){
		$.ajax({
			url:url,
			data:{name:name},
			datatype:"jsonp",
			success:function(data){
				$('.ajax_data').html("");
				$('#find_team_member_id').val("");
				var emp_arr = data['data'];
				var html ='';
				$.each(emp_arr,function(index, emp){
					var actual_val = $('#find_team_member').val();
					var re = new RegExp(actual_val,"g");
			
					if(emp.second_name==''){
						var full_name= emp.first_name+' '+emp.last_name;
						full_name= full_name.replace(re,'<strong>'+actual_val+'</strong>');
						
					
						html = html+'<div class="elem"  tabindex="0">'+full_name+' ('+emp.employee_id+')'+"<input type='hidden' value='"+emp.employee_id+"' class='elem_id'/><input type='hidden' value='"+emp.first_name+' '+emp.last_name+"' class='elem_name'/></div>";
					}
					else{
						var full_name= emp.first_name+' '+emp.second_name+' '+emp.last_name;
						full_name= full_name.replace(re,'<strong>'+actual_val+'</strong>');
						
						html = html+'<div class="elem"  tabindex="0">'+full_name+' ('+emp.employee_id+')'+"<input type='hidden' value='"+emp.employee_id+"' class='elem_id'/><input type='hidden' value='"+emp.first_name+' '+emp.second_name+' '+emp.last_name+"' class='elem_name'/></div>";
					}
				});
				$('.ajax_data').html(html);
			}
		});
	}
	else{
		$('.ajax_data').html("");
		$('#find_team_member_id').val("");
	}
});


/*lookahead search element click event*/
function ajax_elem_click(elem){
	var id = $(elem).find('.elem_id').val();
	var name = $(elem).find('.elem_name').val();
	$('#find_team_member').val(name);
	$('#find_team_member_id').val(id);
	$('.ajax_data').html("");
	show_save_btn();
}


/*reset add new team form*/
function reset_add_new_team_form(){
	$('#find_team_member,#find_team_member_id,.date_ip,select').val("");
	$('.threshold label').removeClass('select');
	$('.team_role').prop("checked",false);
	$('.save_team').addClass('disp-none');	
}

/*reset add new team form*/
function reset_edit_teammember_form(){
	$('.project_team_role').prop("checked",false);	
	$('.project_team_role').closest('label').removeClass('select');
	$('.update_team').addClass('disp-none');
	$('.project_team_role').closest('label').css({'display':'inline-block'});
	$('.edit_team_member_blk').find('.extra_ht').removeClass('disp-none');
}

/*map add new team form reset*/
$('.cancel_team,.add_new_team').click(function(){
	reset_add_new_team_form();
});



