$(document).ready(function(){
   $('section[data-type="parallax_section"]').each(function(){
        var $bgobj = $(this);
        $(window).scroll(function(){
           $window = $(window);
            var ypos = -($window.scrollTop() / $bgobj.data('speed'));
            var coor = '50%' + ypos + 'px';
            
            $bgobj.css({backgroundPosition: coor});
        });
     });
});