$('.add').click(function() {
	$('.body').addClass('onAdd');
	$('.popup').addClass('show');
});
$('.close').click(function() {
	$('.body').removeClass('onAdd');
	$('.popup').removeClass('show');
	$('.popup').addClass('hide');
});