$(document).ready(function(){
	$("#slide-down-button").click(function(){
		$(".slide-down-div").slideToggle();
	});
});

$(window).resize(function(){
	if( $(window).width() > 680 )
		$(".slide-down-div").attr('style', 'display: block;');
	else{
		$(".slide-down-div").attr('style', 'display: none;');
	}
});