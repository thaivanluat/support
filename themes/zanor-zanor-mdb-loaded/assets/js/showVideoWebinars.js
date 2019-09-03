$(document).ready(function(){
    $('.webinar-content').click(function(){
        //set iframe src autoplay
        var iframeOld = $(this).find('.video-iframe').html();
        var src = $(this).find('iframe').attr('src')+"?autoplay=1";
        $(this).find('iframe').attr('src',src);
        //get video iframe
        var content = $(this).find('.video-iframe').html();
        //get video title
        var caption = $(this).find('.video-url').text();
        //get video link
        var videoUrl = $(this).find('.video-url').attr('href');

        $('#modalContain').append(content);
        $('#caption').append(caption);
        $('#caption').attr('href',videoUrl);
        $('#myModal').show();
        $(this).find('.video-iframe').html(iframeOld);
    });

    $('.close').eq(0).click(function(){
        $('#modalContain').html('');
        $('#caption').html('');
        $('#myModal').hide();
    })
});


