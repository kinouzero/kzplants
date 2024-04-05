$(document).ready(function () {
  const media = window.matchMedia("(max-width: 1200px)");
  smallScreen(media);
  media.addEventListener('change', smallScreen);
});

function smallScreen(media) {
  const pageWrapper = $('.page-wrapper');
  if (media.matches) pageWrapper.removeClass('toggled');
  else pageWrapper.addClass('toggled');
}

jQuery(function ($) {
  $('.sidebar-dropdown > a').click(function () {
    $(this).toggleClass('show').next('.sidebar-submenu').slideToggle(200);
  });

  $('#close-sidebar').click(function () {
    $('.page-wrapper').removeClass('toggled');
  });

  $('#show-sidebar').click(function () {
    $('.page-wrapper').addClass('toggled');
  });

  $('#toggle-theme').click(function () {
    const body = $('body');
    const theme = body.attr('data-bs-theme');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (theme === 'dark') body.attr('data-bs-theme', 'light');
    else body.attr('data-bs-theme', 'dark');

    $(this).find('i').toggleClass('fa-sun fa-moon');

    $.ajax({
      type: 'POST',
      url: '/theme/toggle',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    });
  });
});
