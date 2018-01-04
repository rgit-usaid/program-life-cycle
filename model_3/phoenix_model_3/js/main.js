$(document).ready(function() {
	$(".fa-plus").hide();
	$(".fa-minus").click(function(){
		$(".fa-minus").hide();
		$(".form-horizontal").slideUp("slow");
		$(".fa-plus").show();
	});
	$(".fa-plus").click(function(){
		$(".fa-minus").show();
		$(".form-horizontal").slideDown("slow");
		$(".fa-plus").hide();
	});
	

});

 

// $(document).ready(function() {
// 	$(".collapse").click(function(){
// 		$(".data-display").slideUp("slow");
// 		$(".manage-form").slideUp("slow");
// 	});
// 	$(".expand").click(function(){
// 		$(".data-display").slideDown("slow");
// 		$(".manage-form").slideDown("slow");
// 	});
// });