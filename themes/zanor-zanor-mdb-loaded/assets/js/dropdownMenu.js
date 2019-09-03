  $(document).ready(function(){
        $(".menuDropdown").mouseenter(function(){
            if($( window ).width() > 1190) {
                $(this).find(".menuItemDropdown").show();
                $(this).find(".nav-link").css('color','#007bff');
            }
            else {
                $(this).find(".menuItemDropdown").removeAttr('style');
            }
        })
        $(".menuDropdown").mouseleave(function(){
            if($( window ).width() > 1190){
                $(this).find(".menuItemDropdown").hide();
                $(this).find(".nav-link").css('color','#000')
            }
        })

        // Add to zoom image (because this file appear most so yeah ....)
        $('img.fr-fic.fr-dib').on('click', function() {
            if (window.innerWidth > 760) {
                $('#overlay')
                .css({backgroundImage: `url(${this.src})`})
                .addClass('open')
                .one('click', function() { $(this).removeClass('open'); });
            }      
        });

        $('img.fr-fic.fr-dii').on('click', function() {
            if (window.innerWidth > 760) {
                $('#overlay')
                .css({backgroundImage: `url(${this.src})`})
                .addClass('open')
                .one('click', function() { $(this).removeClass('open'); });        
            }    
        });

        // $('img.fr-fic.fr-dib').each(function() {
        //     $(this).hide();
        // });

        // $('img.fr-fic.fr-dii').each(function() {
        //     $(this).hide();
        // });
    }
  );


