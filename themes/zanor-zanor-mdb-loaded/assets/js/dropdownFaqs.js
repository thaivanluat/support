$(document).ready(function(){
    $('.question-title').click(function(){
        var display = $(this).next().css('display');
        if(display == 'none'){
            $(this).next().css('display','block');         
        }
        else {
            $(this).next().css('display','none');
        }   
        $(this).find('.circle').toggleClass('hidden');    
        $(this).toggleClass('active');
    });     
  });


