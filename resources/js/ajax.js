function getItems(id) {
  $.ajax({
    type: 'POST',
    url: '/checklist/' + id + '/items',
    success: function (data) {
      console.log(data);
    }
  });
}
