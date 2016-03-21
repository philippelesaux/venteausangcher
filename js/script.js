$(function() {
    // sets the height of panels with the class .same-height so they're...wait for it...the same height
    $('.same-height').matchHeight();

    // scrolls the page when clicking on internal links
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

    $('#form').parsley().on('field:validated', function() {
        var ok = $('.parsley-error').length === 0;
        $('.bs-callout-info').toggleClass('hidden', !ok);
        $('.bs-callout-warning').toggleClass('hidden', ok);
    });
});
