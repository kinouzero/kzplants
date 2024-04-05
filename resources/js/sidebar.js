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

  $('#pin-sidebar').click(function () {
    const pageWrapper = $('.page-wrapper');
    if (pageWrapper.hasClass('pinned')) {
      pageWrapper.removeClass('pinned');
      $(this).find('i').toggleClass('fa-thumbtack fa-down-left-and-up-right-to-center');
      $('#sidebar').unbind();
    } else {
      pageWrapper.addClass('pinned');
      $(this).find('i').toggleClass('fa-down-left-and-up-right-to-center fa-thumbtack');
      $('#sidebar').hover(function () {
        pageWrapper.addClass('sidebar-hovered');
      }, function () {
        pageWrapper.removeClass('sidebar-hovered');
      });
    }
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
