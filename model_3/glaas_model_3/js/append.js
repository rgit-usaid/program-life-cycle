function getCodeDescription(elem)
{
	var description =  $(elem).val(); 
	var arr = new Array();
	arr = description.split("=");
	var code = arr[1];
	var cost_code_elem = $(elem).closest('.Budget-info').find('.cost_code');
	//alert(code);
	 $(cost_code_elem).val(code);
}

function addVendor() {
	
	var div = '<tr class="vendor-info"><td><input type="text" class="form-control" name="local_contact_name[]"></td><td><input type="text" class="form-control" name="local_contact_email[]"></td><td><input type="text" class="form-control" name="local_phone_number[]"></td><td><input type="text" class="form-control" name="local_address_street[]"></td><td><input type="text" class="form-control" name="local_address_city[]"></td><td><input type="text" class="form-control" name="local_address_state_province[]" value=""></td><td><input type="text" class="form-control" name="local_address_country[]"></td><td><input type="text" class="form-control" name="local_address_location_code[]"></td><td width="120" class="text-center"><button type="button" class="btn btn-danger" onclick="removeVendor(this)" id="remove_vendor"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td></tr>';
	$('#append_here').append(div);
}

function removeVendor(elem) {
	if($('#append_here').find('.vendor-info').length>1){
		$(elem).closest('tr').remove();
	}
	else{
		alert("Sorry you can't delete this row");
	}
}

function showChild(elem){
	//alert($(elem).closest('.child-table').length);
	$(elem).closest('.parent-tbl').nextUntil('.parent-tbl','.child-table').toggleClass('disp-none');
}

function showLink(elem){
	//alert('hello');
	if($(elem).closest('.parent-link').nextUntil('.parent-link','.child-link').hasClass('disp-none'))
	{
		$(elem).html("Hide Link");
	}
	else
	{
		$(elem).html("Show Link");
	} 
	 $(elem).closest('.parent-link').nextUntil('.parent-link','.child-link').toggleClass('disp-none');
}

function showClinOne(elem){
	if($(elem).closest('.parent-link').nextUntil('.parent-link','.child-clin1').hasClass('disp-none'))
	{
		$(elem).html("<img src='images/minus.png'>");
	}
	else
	{
		$(elem).html("<img src='images/plus.png'>");
	} 
	$(elem).closest('.parent-link').nextUntil('.parent-link','.child-clin1').toggleClass('disp-none');
}

function showClinTwo(elem){
	if($(elem).closest('.parent-tbl').nextUntil('.parent-tbl','.child-clin2').hasClass('disp-none'))
	{
		$(elem).html("<img src='images/minus.png'>");
	}
	else
	{
		$(elem).html("<img src='images/plus.png'>");
	} 
	$(elem).closest('.parent-tbl').nextUntil('.parent-tbl','.child-clin2').toggleClass('disp-none');
}

function showClinThree(elem){
	if($(elem).closest('.child-clin3').nextUntil('.child-clin3','.child-clin4').hasClass('disp-none'))
	{
		$(elem).html("<img src='images/minus.png'>");
	}
	else
	{
		$(elem).html("<img src='images/plus.png'>");
	} 
	
	$(elem).closest('.child-clin3').nextUntil('.child-clin3','.child-clin4').toggleClass('disp-none');
}

function showClinFour(elem){
	if($(elem).closest('.child-clin5').nextUntil('.child-clin5','.child-clin6').hasClass('disp-none'))
	{
		$(elem).html("<img src='images/minus.png'>");
	}
	else
	{
		$(elem).html("<img src='images/plus.png'>");
	} 
	
	$(elem).closest('.child-clin5').nextUntil('.child-clin5','.child-clin6').toggleClass('disp-none');
}

function addBudget() {
	
	var div = '<tr class="Budget-info"><td><input type="text" class="form-control cost_code" name="cost_code[]" value="" readonly></td><td>';
	div = div+ '<select class="form-control" name="code_description[]" onChange="getCodeDescription(this)";>';
	div = div+	'<option value="">Select</option>';
	div = div+	'<option value="Supplies=2001">Supplies </option>';
	div = div+	'<option value="Travel and Per diem=3001">Travel and Per diem </option>';
	div = div+	'<option value="Benefits=1002">Benefits </option>';
	div = div+	'<option value="Equipment=2002">Equipment </option>';
	div = div+	'<option value="Leases and Rentals=4001">Leases and Rentals </option>';
	div = div+	'<option value="Consultants=1003">Consultants </option>';
	div = div+	'<option value="Operating expenses=2003">Operating expenses </option>';
	div = div+	'<option value="Other expenses=5001">Other expenses </option></select>';
 	div = div + '</td><td><input type="text" class="form-control" name="budget_amount[]" value=""></td><td width="120" class="text-center"><button type="button" class="btn btn-danger" onclick="removeBudget(this)" id="remove_vendor"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td></tr>';
	$('#append_Budget').append(div);
}  

function removeBudget(elem) {
	if($('#append_Budget').find('.Budget-info').length>1){
		$(elem).closest('tr').remove();
	}
	else{
		alert("Sorry you can't delete this row");
	}
}

//  JS for Show & Hide Budget

$(document).ready(function(){
	$('.show_budget').click(function(){
		$('.budget').slideToggle('slow');
		if( $(this).hasClass('active') )
			$(this).text('Show Budget');
		else
			$(this).text('Hide Budget');        
		$(this).toggleClass('active');
	});
});

// JS for Save Update Button
$(document).ready(function(){
	$('.fa-pencil').click(function(){
		$('.save').text('Update');		
	});
});

// JS for Date Picker
$(document).ready(function() {
	$('.datepicker').datepicker({
		startDate: '-3d'                
	});
});	

$(document).ready(function() {
	$('.arrow').click(function(){
		if($(this).hasClass('fa-arrow-down')){
			$(this).removeClass('fa-arrow-down');
			$(this).addClass('fa-arrow-up')
		}
		else{
			$(this).addClass('fa-arrow-down')	
		}		
	});
});	

// Date picker Selection Issue
$(document).ready(function() {
	$("#datepicker , #datepicker1 , #datepicker2").datepicker({
		changeMonth: true,
		changeYear: true
	});
	$('#btn').click(function() {
		$("#datepicker").focus();
	});
	$('#btn1').click(function() {
		$("#datepicker1").focus();
	});
	$('#btn2').click(function() {
		$("#datepicker2").focus();
	});
});


//  Award Screen JS
$(document).ready(function() {
	$('#selectBox').on('change', function() {
		$('.award').fadeIn().removeClass('disp-none');
	});
});

// Vendor JS on Award Screen

$(document).ready(function() {
	$('#vendor-select').on('change', function() {
		$('.vendor-hide').fadeIn().removeClass('disp-none');
		
	});
	
	//  ToolTip

	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})

});


// Clone Link to Project / Activity / DOAG

function showAssociate(elem,val)
{
	var associate_type = $(elem).val();
	var selected_val = ""; 
	if(val!=="undefined"){
		selected_val = val;		
	}
	
	$.ajax({
		type: "POST",
		url: "ajax_files/get_associate.php",
		data: {associate_type:associate_type,selected_val:selected_val},
		context:elem,
		success: function(data){
			$(elem).closest('.req_link_to').find('.associate').css('display','block');
			$(elem).closest('.req_link_to').find('.associate').html(data);
		}
	}); 
}
function showProjectActivity(elem)
{
	var project_id = $(elem).val();
	var selected_val = $(elem).closest('.link_to_act_blk').find('.sel_activity_id').val();
	$.ajax({
		type: "POST",
		url: "ajax_files/get_activity_drop.php",
		data: {project_id:project_id,selected_val:selected_val},
		context:elem,
		success: function(data){
			$(elem).closest('.req_link_to').find('.show_activity').html(data);
		}
	}); 
}  

$(document).ready(function(){
	$('#add_more_link_to').click(function(){
		var clone = $('.req_link_to:first').clone();
		clone.find('.assoc_link_id_arr, .associate_type').val("");
		clone.find('.associate').html("");
		clone.find('.remove_link_to_blk').removeClass("disp-none");
		$(clone).insertAfter($('.req_link_to:last'));
	});
});
$(document).on('click','.remove_link_to_blk', function(){
	$(this).closest('.req_link_to').remove();
});

$(document).on('change','.associate_type', function(){ 
	var pre_val = $(this).parent().find('.assoc_link_id_arr').val();
	$(this).parent().find('.assoc_link_id_arr').val("");
});

