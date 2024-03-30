$(document).ready(function () {
  $('.toggle-sidebar').on('click', function () {
    $('.sidebar').toggleClass('collapsed');
    $('.container').toggleClass('collapsed');
    $('header').toggleClass('collapsed');
  });
});
