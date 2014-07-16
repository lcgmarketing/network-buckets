/* Smooth scroll One page Nav-----------------------------------------------*/
$(document).ready(function(){
    $('nav#navigation').onePageNav({
        currentClass: 'current',
        changeHash: false,
        scrollSpeed: 750,
        scrollThreshold: 0.5,
        filter: '',
        easing: 'swing',
        begin: function() {
            //I get fired when the animation is starting
        },
        end: function() {
            //I get fired when the animation is ending
        },
        scrollChange: function($currentListItem) {
            //I get fired when you enter a section and I pass the list item of the section
        }
    });
});

$(document).ready(function() {
  $('.flexslider').flexslider({
    animation: "slide",
    slideshow: true,
    slideshowSpeed: 5000,
    controlNav: true,
    directionNav: true
  });
});

// prettyPhoto Lightbox

$(document).ready(function(){
  $("a[data-gal^='prettyPhoto']").prettyPhoto(); 
});

// Flexslider

// Responsive Nav

$(function(){
    var justAdded = false;
    $('#toggle').click(function(){
        if(!$('#toggle').hasClass("toggled")){
            justAdded = true;       
            $('#toggle').addClass("toggled");
            $('header nav#navigation').addClass("opened");
        } else{
            $('#toggle').removeClass("toggled");
            $('header nav#navigation').removeClass("opened");
        }
    });
    $('body').click(function(){
        if(justAdded == false){
            $('#toggle').removeClass("toggled");
            $('header nav#navigation').removeClass("opened");
        }
        justAdded = false;
    });
});
 

