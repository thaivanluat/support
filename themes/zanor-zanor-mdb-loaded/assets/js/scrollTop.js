// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 550 || document.documentElement.scrollTop > 550) {
    document.getElementById("btnScrollTop").style.display = "block";
  } else {
    document.getElementById("btnScrollTop").style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    var pos = document.documentElement.scrollTop? document.documentElement.scrollTop : document.body.scrollTop;
    var id = setInterval(frame, 0.3);
    function frame() {
      if (pos <= 0) {
        clearInterval(id);
      } else {
        pos = pos-15;
        document.body.scrollTop = pos ;// For Safari
        document.documentElement.scrollTop = pos;// For Chrome, Firefox, IE and Opera  
      }
    }
  }
