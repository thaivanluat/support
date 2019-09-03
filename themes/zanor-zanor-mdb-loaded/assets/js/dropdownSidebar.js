$(document).ready(function(){
    if($( window ).width() < 768) {
    $(".lv1").css('display','none');
    }
    $(".btn-category-mobile").click(function(){
        var display = $('.lv1').css('display');
        if(display == 'block') {
            $(".lv1").css('display','none');
        }
        else {
            $(".lv1").css('display','block');
        }
    })
});


