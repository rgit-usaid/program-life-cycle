function ajax_elem_click(elem){
	var project_id = $(elem).find('.project_id').val();
	var project_title = $(elem).find('.project_title').val();
	$('#selected_project').find('.project_id').val(project_id);
	var project_stage_id = $(elem).find('.project_stage_id').val();
	$('#selected_project').find('.project_stage_id').val(project_stage_id);
	$('#find_all_project').val(project_title);
	$('.ajax_data').html("");
	//$('#selected_project').submit();
}

/*lookahead search*/
$('#find_all_project').keyup(function(){
	var name = $(this).val();
	$.ajax({
		url:'http://rgdemo.com/usaid/api-amp3/get_all_project.php',
		data:{name:name},
		datatype:"jsonp",
		success:function(data){
			$('.ajax_data').html("");
			var project_arr = data['data'];
			var html ='';
			$.each(project_arr,function(index, project){					
					var actual_val = $('#find_all_project').val();
					var re_oth = new RegExp(actual_val,"gi");
					var ptitle = project.title;
					if(ptitle.search(re_oth)!=-1){
						var re = new RegExp(actual_val,"gi");
						var act_project_title= ptitle;
						var project_title= ptitle.replace(re,function(str) {return '<b>'+str+'</b>'});
						html = html+'<div class="elem"  tabindex="0">'+project_title+'<input type="hidden" value="'+act_project_title+'" name="project_title" class="project_title"/><input type="hidden" value="'+project.project_id+'" name="project_id" class="project_id"/><input type="hidden" value="'+project.project_stage_id+'" name="project_stage_id" class="project_stage_id"/></div>';
					}
			});
			$('.ajax_data').html(html);
		}
	});	
});
