  $('.nav-event').click(function(event) {
    var elemHref = event.currentTarget.href.split('#');
    $([document.documentElement, document.body]).animate({
        scrollTop: $('#'+elemHref[1]).offset().top - 200
    }, 500)
    $('.nav-event').removeClass('w--current');
    $(event.currentTarget).addClass('w--current');
    return false;
  });