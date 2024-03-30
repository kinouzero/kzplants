$(document).ready(function () {
  initDom($('body'));
});

function initDom(scope) {
  // Select2
  initSelect2(scope.find('.select2'));

  // Datatable
  initDatatable(scope.find('.datatable'));

  // Modal link
  initModal(scope.find('.modal-link'));

  // Switch collapse
  initSwitchCollapse(scope.find('.switch-collapse'));

  // Btn delete
  initBtnForm(scope.find('.btn-form'));

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
  dom.select2({
    theme: 'bootstrap-5',
    placeholder: {
      id: '',
      text: 'Select'
    },
    allowClear: true,
    width: 'style'
  });
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

function initModal(dom) {
  dom.click(function (event) {
    event.preventDefault();
    let url = this.getAttribute('href');

    $.ajax({
      url: url,
      method: 'GET',
      data: {
        noLayout,
        target: $(this).data('bs-target') || 'modal'
      },
      success: function (response) {
        console.log(response);
        $('body').append(response);

        let myModal = new bootstrap.Modal($(target)[0]);
        myModal.show();
      },
      error: function (xhr, status, error) {
        console.error('Erreur lors du chargement du contenu de la modal');
      }
    });
  });
}

function initSwitchCollapse(dom) {
  dom.change(function () {
    $($(this).data('target')).collapse('toggle');
    $($(this).data('form'))[0].submit();
  })
}

function initBtnForm(dom) {
  dom.click(function (event) {
    event.preventDefault();
    console.log($(this).data('form'));
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
