 $(document).ready(function(){
    $('#SeeMore').click(function() {
    	$('.MoreColumn').toggle();
    	$('#HideLess').toggle();
    	$(this).toggle();
    });
    $('#HideLess').click(function() {
    	$('.MoreColumn').toggle();
    	$('#SeeMore').toggle();
    	$(this).toggle();
    	$('html, body').animate({
            scrollTop: $("#guide1").offset().top
        },0);
    });

    $('#SeeMore1').click(function() {
    	$('.MoreColumn1').toggle();
    	$('#HideLess1').toggle();
    	$(this).toggle();
    });
    $('#HideLess1').click(function() {
    	$('.MoreColumn1').toggle();
    	$('#SeeMore1').toggle();
    	$(this).toggle();
        $('html, body').animate({
            scrollTop: $("#guide2").offset().top
        },0);
  //   	var position = $("#SeeMore1").offset() ;
		// scroll(0,position.top);
    })


    $('ul.PostList').each(function(){
		var max = 5;
		var index = max - 1;
	    if ($(this).find('li').length > max+1) {
	        $(this).find('li:gt('+index+'):not(:last-child)').hide().end();
	        $(this).find('.sub_accordian').click( function(){
	            $(this).siblings('li:gt('+index+')').toggle();
	            $(this).find('.show_more').toggle();
	            $(this).find('.show_less').toggle();
	        });
	    }
	    else {
	    	$(this).find('.sub_accordian').hide().end();
	    };	
	});
});