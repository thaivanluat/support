$(document).ready(function(){
    $('.question-title').click(function(){
        var display = $(this).next().css('display');
        $('.question-title').css('color','#42464e');
        $('.question-content').css('display','none');
        $('.fa.fa-angle-up').removeClass('fa-angle-up').addClass('fa-angle-down');
        if(display == 'none'){
            $(this).next().css('display','block');
            $(this).find('[class*="angle"]').toggleClass('fa-angle-up fa-angle-down');
            $(this).next().find('img').css('width','100%');
            $(this).css('color','#1792fc');
        }
        else {
            $(this).next().css('display','none');
            $(this).css('color','#42464e');
        }
    });
  });

