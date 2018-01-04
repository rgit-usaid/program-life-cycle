$('.threshold label').click(function(){
	$(this).closest('.threshold').find('label').removeClass('select');
	$(this).addClass('select');
});