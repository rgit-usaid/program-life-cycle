$(".leftpanel .menu-item").click(function(){
	var elem = $(this).closest('li');
	if(!$(this).closest('li').hasClass('active')){
		$(this).closest('li').addClass("active");
		$(this).closest('li').find('ul').slideDown();	
	}
	else{
		$(this).closest('li').find('ul').slideUp(function(){
			elem.removeClass("active");											 
		});	
	}
});

$(".tbl-up").click(function(){
	$(this).closest(".tbl-caption").next(".table-container").slideUp("fast");
});
$(".tbl-down").click(function(){
	$(this).closest(".tbl-caption").next(".table-container").slideDown("fast");
});