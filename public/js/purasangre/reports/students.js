
const monthNames = [
  'Enero',
  'Febrero',
  'Marzo',
  'Abril',
  'Mayo',
  'Junio',
  'Julio',
  'Agosto',
  'Septiembre',
  'Octubre',
  'Noviembre',
  'Diciembre'
];

$(document).ready(function() {
  const reportsTable = $('#reports-table').DataTable({
    language: {
      zeroRecords: 'Sin resultados',
      infoEmpty: 'Sin resultados',
      infoFiltered: '(filtered from _MAX_ total records)'
    },
    processing: true,
    serverSide: true,
    sort: false,
    ajax: {
      url: '/reports/students-filter',
      dataType: 'json',
      type: 'POST',
      data: function(d) {
        d._token = token;
        d.year = $('#students-year-select').val();
        d.month = $('#students-month-select').val();
      }
    },
    dom: 'rt',
    columns: [
      {
        data: 'year'
      },
      {
        data: 'month',
        render: function(data, type, row) {
          return monthNames[data - 1];
        }
      },
      {
        data: 'active_students_start'
      },
      {
        data: 'active_students_end'
      },
      {
        data: 'dropouts'
      },
      {
        data: 'dropout_percentage',
        render: function(data, type, row) {
          return data + '%';
        }
      },
      {
        data: 'new_students'
      },
      {
        data: 'new_students_percentage',
        render: function(data, type, row) {
          return data + '%';
        }
      },
      // { "data": "turnaround"},
      {
        data: 'previous_month_difference'
      },
      {
        data: 'growth_rate',
        render: function(data, type, row) {
          return data + '%';
        }
      },
      {
        data: 'retention_rate',
        render: function(data, type, row) {
          return data + '%';
        }
      },
      {
        data: 'rotation',
        render: function(data, type, row) {
          return data + '%';
        }
      }
    ]
  });

  $('#students-year-select, #students-month-select').change(function() {
    reportsTable.ajax.reload();
  });
});

function turnDown(el) {
  if (el.getAttribute('aria-expanded') === 'true') {
    el.setAttribute('aria-expanded', 'false');
  } else {
    el.setAttribute('aria-expanded', 'true');
  }
}
