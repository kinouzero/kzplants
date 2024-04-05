$(document).ready(function () {
  initDom($('body'));
});

function initDom(scope) {
  // Select2
  initSelect2(scope.find('.select2'));

  // Datatable
  initDatatable(scope.find('.datatable'));

  // Switch form
  initSwitchForm(scope.find('.switch-form'));

  // Btn delete
  initBtnForm(scope.find('.btn-form'));

  // Input upload
  initUpload(scope.find('.upload'));

  // Masonry
  initMasonry(scope.find('.masonry'));

  // Add row btn
  scope.find('.btn-add-row').click(function () {
    addRow($($(this).data('row-container')));
  });

  // Tooltip
  scope.find('[data-bs-toggle="tooltip"]').tooltip();

  // Popover
  scope.find('[data-bs-toggle="popover"]').popover({ html: true, sanitize: false })
    .on('show.bs.popover', function () {
      $('[data-bs-toggle="popover"]').not(this).popover('hide');
    })
    .on('shown.bs.popover', function () {
      $('#' + $(this).attr('aria-describedby')).find('.collapse').collapse();
      initDom($('#' + $(this).attr('aria-describedby')));
    });
}

function initSelect2(dom) {
  $.each(dom, function () {
    $(this).select2({
      theme: 'bootstrap-5',
      placeholder: {
        id: '',
        text: $(this).data('placeholder')
      },
      allowClear: true,
      width: 'style'
    });
  })
}

function initDatatable(dom) {
  dom.DataTable({
    "paging": true,
    "pagingType": "simple_numbers",
    "lengthChange": false,
    "ordering": true,
    "info": true,
    "autoWidth": true,
    "responsive": true,
    "layout": {
      topStart: 'search',
      topEnd: 'paging',
      bottomStart: 'info',
      bottomEnd: 'paging'
    }
  });
}

function initSwitchForm(dom) {
  dom.change(function () {
    $($(this).data('target')).collapse('toggle');
    $($(this).data('form'))[0].submit();
  })
}

function initBtnForm(dom) {
  dom.click(function (event) {
    event.preventDefault();
    $($(this).data('form'))[0].submit();
  });
}

function addRow(dom) {
  let newRow = dom.find('.row-clone.d-none').clone().removeClass('row-clone d-none');
  newRow.html(newRow.html().replace(/uid/g, Math.random().toString(36).substring(2, 11)));
  newRow.find('select').addClass('select2');
  dom.append(newRow);
  initSelect2(newRow.find('.select2'));
}

function initMasonry(dom) {
  let grid = dom.masonry({
    percentPosition: true
  });

  grid
    .on('click', '[data-bs-toggle="collapse"]', function () {
      setTimeout(function () {
        grid.masonry('layout');
      }, 500);
    });
}

function initUpload(dom) {
  dom.fileinput();
}