$(function() {
    $('.same-height').matchHeight();

    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();

        var target = this.hash;
        var $target = $(target);

        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 300, 'swing', function() {
            window.location.hash = target;
        });
    });
});
