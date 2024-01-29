$(document).ready(function () {
  $(".crud-table").addClass("table table-striped table-bordered table-hover");

  $("#tabla_admininstradores").DataTable({
    language: {
      buttons: {
        sLengthMenu: "Mostrar MENU resultados",
        pageLength: {
          _: "Mostrar %d resultados",
        },
      },
      zeroRecords: "No hay coincidencias",
      info: "Mostrando _END_ resultados de _MAX_",
      infoEmpty: "No hay datos disponibles",
      infoFiltered: "(Filtrado de _MAX_ registros totales)",
      search: "Buscar",
      emptyTable: "No existen registros",
      paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último",
      },
    },
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      "pageLength",
      {
        extend: "excelHtml5",
        text: '<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      {
        extend: "print",
        text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      {
        text: '<i class="fa-lg text-success bi bi-person-add"></i>',
        action: function () {
          $("#registrar_administradores").modal("show");
        },
      },
    ],
    lengthMenu: [10, 25, 50, 100],
  });

  $("#tabla_permisos").DataTable({
    language: {
      buttons: {
        sLengthMenu: "Mostrar MENU resultados",
        pageLength: {
          _: "Mostrar %d resultados",
        },
      },
      zeroRecords: "No hay coincidencias",
      info: "Mostrando _END_ resultados de _MAX_",
      infoEmpty: "No hay datos disponibles",
      infoFiltered: "(Filtrado de _MAX_ registros totales)",
      search: "Buscar",
      emptyTable: "No existen registros",
      paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último",
      },
    },
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      "pageLength",
      {
        extend: "excelHtml5",
        text: '<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      {
        extend: "print",
        text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      // {
      //   text: '<i class="fa-lg text-success fa-solid fa-registered"></i>',
      //   action: function () {
      //     $("#registrar_permisos").modal("show");
      //   },
      // },
    ],
    lengthMenu: [10, 25, 50, 100],
  });

  $("#tabla_permisos_aceptados").DataTable({
    language: {
      buttons: {
        sLengthMenu: "Mostrar MENU resultados",
        pageLength: {
          _: "Mostrar %d resultados",
        },
      },
      zeroRecords: "No hay coincidencias",
      info: "Mostrando _END_ resultados de _MAX_",
      infoEmpty: "No hay datos disponibles",
      infoFiltered: "(Filtrado de _MAX_ registros totales)",
      search: "Buscar",
      emptyTable: "No existen registros",
      paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último",
      },
    },
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      "pageLength",
      {
        extend: "excelHtml5",
        text: '<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      {
        extend: "print",
        text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
    ],
    lengthMenu: [10, 25, 50, 100],
  });

  $("#tabla_permisos_registrados").DataTable({
    language: {
      buttons: {
        sLengthMenu: "Mostrar MENU resultados",
        pageLength: {
          _: "Mostrar %d resultados",
        },
      },
      zeroRecords: "No hay coincidencias",
      info: "Mostrando _END_ resultados de _MAX_",
      infoEmpty: "No hay datos disponibles",
      infoFiltered: "(Filtrado de _MAX_ registros totales)",
      search: "Buscar",
      emptyTable: "No existen registros",
      paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último",
      },
    },
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      "pageLength",
      {
        extend: "excelHtml5",
        text: '<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      {
        extend: "print",
        text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
    ],
    lengthMenu: [10, 25, 50, 100],
  });

  $("#tabla_permisos_pendientes").DataTable({
    language: {
      buttons: {
        sLengthMenu: "Mostrar MENU resultados",
        pageLength: {
          _: "Mostrar %d resultados",
        },
      },
      zeroRecords: "No hay coincidencias",
      info: "Mostrando _END_ resultados de _MAX_",
      infoEmpty: "No hay datos disponibles",
      infoFiltered: "(Filtrado de _MAX_ registros totales)",
      search: "Buscar",
      emptyTable: "No existen registros",
      paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último",
      },
    },
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      "pageLength",
      {
        extend: "excelHtml5",
        text: '<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      {
        extend: "print",
        text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
    ],
    lengthMenu: [10, 25, 50, 100],
  });

  $("#tabla_trabajo").DataTable({
    language: {
      buttons: {
        sLengthMenu: "Mostrar MENU resultados",
        pageLength: {
          _: "Mostrar %d resultados",
        },
      },
      zeroRecords: "No hay coincidencias",
      info: "Mostrando _END_ resultados de _MAX_",
      infoEmpty: "No hay datos disponibles",
      infoFiltered: "(Filtrado de _MAX_ registros totales)",
      search: "Buscar",
      emptyTable: "No existen registros",
      paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último",
      },
    },
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      "pageLength",
      {
        extend: "excelHtml5",
        text: '<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      {
        extend: "print",
        text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      // {
      //     text: '<i class="fa-lg text-success bi bi-person-add"></i>',
      //     action: function () {
      //         $('#registrar_trabajo').modal('show');
      //     }
      // },
    ],
    lengthMenu: [10, 25, 50, 100],
  });

  $("#tabla_vacaciones").DataTable({
    language: {
      buttons: {
        sLengthMenu: "Mostrar MENU resultados",
        pageLength: {
          _: "Mostrar %d resultados",
        },
      },
      zeroRecords: "No hay coincidencias",
      info: "Mostrando _END_ resultados de _MAX_",
      infoEmpty: "No hay datos disponibles",
      infoFiltered: "(Filtrado de _MAX_ registros totales)",
      search: "Buscar",
      emptyTable: "No existen registros",
      paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último",
      },
    },
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      "pageLength",
      {
        extend: "excelHtml5",
        text: '<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      {
        extend: "print",
        text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      // {
      //     text: '<i class="fa-lg text-success bi bi-person-add"></i>',
      //     action: function () {
      //         $('#registrar_vacaciones').modal('show');
      //     }
      // },
    ],
    lengthMenu: [10, 25, 50, 100],
  });

  $("#tabla_Funcionarios").DataTable({
    language: {
      buttons: {
        sLengthMenu: "Mostrar MENU resultados",
        pageLength: {
          _: "Mostrar %d resultados",
        },
      },
      zeroRecords: "No hay coincidencias",
      info: "Mostrando _END_ resultados de _MAX_",
      infoEmpty: "No hay datos disponibles",
      infoFiltered: "(Filtrado de _MAX_ registros totales)",
      search: "Buscar",
      emptyTable: "No existen registros",
      paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último",
      },
    },
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      "pageLength",
      {
        text: '<i class="fa-solid fa-life-ring"></i>',
        action: function () {
          $("#registrar_vacaciones").modal("show");
        },
      },
    ],
    lengthMenu: [10, 25, 50, 100],
  });

  $("#tabla_vacaciones_funcionarios").DataTable({
    language: {
      buttons: {
        sLengthMenu: "Mostrar MENU resultados",
        pageLength: {
          _: "Mostrar %d resultados",
        },
      },
      zeroRecords: "No hay coincidencias",
      info: "Mostrando _END_ resultados de _MAX_",
      infoEmpty: "No hay datos disponibles",
      infoFiltered: "(Filtrado de _MAX_ registros totales)",
      search: "Buscar",
      emptyTable: "No existen registros",
      paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último",
      },
    },
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      "pageLength",
      {
        extend: "excelHtml5",
        text: '<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      {
        extend: "print",
        text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      {
        text: '<i class="fa-solid fa-life-ring"></i>',
        action: function () {
          $("#registrar_vacaciones").modal("show");
        },
      },
    ],
    lengthMenu: [10, 25, 50, 100],
  });

  $("#tablaPermisosRechazados").DataTable({
    language: {
      buttons: {
        sLengthMenu: "Mostrar MENU resultados",
        pageLength: {
          _: "Mostrar %d resultados",
        },
      },
      zeroRecords: "No hay coincidencias",
      info: "Mostrando _END_ resultados de _MAX_",
      infoEmpty: "No hay datos disponibles",
      infoFiltered: "(Filtrado de _MAX_ registros totales)",
      search: "Buscar",
      emptyTable: "No existen registros",
      paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último",
      },
    },
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      "pageLength",
      {
        extend: "excelHtml5",
        text: '<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      {
        extend: "print",
        text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
    ],
    lengthMenu: [10, 25, 50, 100],
  });

  $("#tabla_imprimir").DataTable({
    language: {
      buttons: {
        sLengthMenu: "Mostrar MENU resultados",
        pageLength: {
          _: "Mostrar %d resultados",
        },
      },
      zeroRecords: "No hay coincidencias",
      info: "Mostrando _END_ resultados de _MAX_",
      infoEmpty: "No hay datos disponibles",
      infoFiltered: "(Filtrado de _MAX_ registros totales)",
      search: "Buscar",
      emptyTable: "No existen registros",
      paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último",
      },
    },
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      "pageLength",
      {
        extend: "excelHtml5",
        text: '<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
      {
        extend: "print",
        text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
        exportOptions: {
          columns: ":not(.exclude)",
        },
      },
    ],
    lengthMenu: [10, 25, 50, 100],
  });
  // $('#tabla_encuestas').DataTable({
  //     language:{
  //         zeroRecords:'No hay coincidencias',
  //         info:'Mostrando _END_ resultados de _MAX_',
  //         infoEmpty:'No hay datos disponibles',
  //         infoFiltered:'(Filtrado de _MAX_ registros totales)',
  //         search:'Buscar',
  //         emptyTable:     "No existen registros",
  //         paginate: {
  //             first:      "Primero",
  //             previous:   "Anterior",
  //             next:       "Siguiente",
  //             last:       "Último"
  //         },
  //     },
  //     responsive: true,
  //     dom: 'Bfrtip',
  //     buttons: [
  //         'pageLength',
  //         { extend: 'excelHtml5', text:'<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
  //             exportOptions: {
  //                 columns: ':not(.exclude)'
  //             }
  //         },
  //         { extend: 'print', text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
  //             exportOptions: {
  //                 columns: ':not(.exclude)'
  //             }
  //         },
  //         {
  //             text: '<i class="fa-lg text-success fas fa-plus-circle"></i>',
  //             action: function () {
  //                 $('#registrar_vaciones').modal('show');
  //             }
  //         },
  //     ],
  //     lengthMenu: [ 10, 25, 50, 100 ]
  // } );

  // $('#tabla_graduados').DataTable({
  //     language:{
  //         zeroRecords:'No hay coincidencias',
  //         info:'Mostrando _END_ resultados de _MAX_',
  //         infoEmpty:'No hay datos disponibles',
  //         infoFiltered:'(Filtrado de _MAX_ registros totales)',
  //         search:'Buscar',
  //         emptyTable:     "No existen registros",
  //         paginate: {
  //             first:      "Primero",
  //             previous:   "Anterior",
  //             next:       "Siguiente",
  //             last:       "Último"
  //         },
  //     },
  //     responsive: true,
  //     dom: 'Bfrtip',
  //     buttons: [
  //         'pageLength',
  //         { extend: 'excelHtml5', text:'<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
  //             exportOptions: {
  //                 columns: ':not(.exclude)'
  //             }
  //         },
  //         { extend: 'print', text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
  //             exportOptions: {
  //                 columns: ':not(.exclude)'
  //             }
  //         },
  //         {
  //             text: '<i class="fa-lg text-success fas fa-plus-circle"></i>',
  //             action: function () {
  //                 $('#registrar_graduados').modal('show');
  //             }
  //         },
  //     ],
  //     lengthMenu: [ 10, 25, 50, 100 ]
  // } );

  // $('#tabla_portafolio').DataTable({
  //     language:{
  //         zeroRecords:'No hay coincidencias',
  //         info:'Mostrando _END_ resultados de _MAX_',
  //         infoEmpty:'No hay datos disponibles',
  //         infoFiltered:'(Filtrado de _MAX_ registros totales)',
  //         search:'Buscar',
  //         emptyTable:     "No existen registros",
  //         paginate: {
  //             first:      "Primero",
  //             previous:   "Anterior",
  //             next:       "Siguiente",
  //             last:       "Último"
  //         },
  //     },
  //     responsive: true,
  //     dom: 'Bfrtip',
  //     buttons: [
  //         'pageLength',
  //         { extend: 'excelHtml5', text:'<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
  //             exportOptions: {
  //                 columns: ':not(.exclude)'
  //             }
  //         },
  //         { extend: 'print', text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
  //             exportOptions: {
  //                 columns: ':not(.exclude)'
  //             }
  //         },
  //     ],
  //     lengthMenu: [ 10, 25, 50, 100 ]
  // } );

  // $('#tabla_ver_enviados').DataTable({
  //     language:{
  //         zeroRecords:'No hay coincidencias',
  //         info:'Mostrando _END_ resultados de _MAX_',
  //         infoEmpty:'No hay datos disponibles',
  //         infoFiltered:'(Filtrado de _MAX_ registros totales)',
  //         search:'Buscar',
  //         emptyTable:     "No existen registros",
  //         paginate: {
  //             first:      "Primero",
  //             previous:   "Anterior",
  //             next:       "Siguiente",
  //             last:       "Último"
  //         },
  //     },
  //     responsive: true,
  //     dom: 'Bfrtip',
  //     buttons: [
  //         'pageLength',
  //         { extend: 'excelHtml5', text:'<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
  //             exportOptions: {
  //                 columns: ':not(.exclude)'
  //             }
  //         },
  //         { extend: 'print', text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
  //             exportOptions: {
  //                 columns: ':not(.exclude)'
  //             }
  //         },
  //         {
  //             text: '<i class=" text-primary fas fa-paper-plane"></i>',
  //             action: function () {
  //                 $('#encuesta_enviar').modal('show');
  //             }
  //         },
  //         {
  //             text: '<i class="fa-lg text-success bi bi-telegram"></i>',
  //             action: function () {
  //                 $('#recordatorio').modal('show');
  //             }
  //         },
  //         {
  //             text: '<i class="fa-lg bg-dark px-4 py-1 text-light bi bi-filter-square"></i>',
  //             action: function () {
  //                 $('#actualizar_lista').modal('show');
  //             }
  //         },
  //     ],
  //     lengthMenu: [ 10, 25, 50, 100 ]
  // } );

  // $('#tabla_eventos').DataTable({
  //     language:{
  //         zeroRecords:'No hay coincidencias',
  //         info:'Mostrando _END_ resultados de _MAX_',
  //         infoEmpty:'No hay datos disponibles',
  //         infoFiltered:'(Filtrado de _MAX_ registros totales)',
  //         search:'Buscar',
  //         emptyTable:     "No existen registros",
  //         paginate: {
  //             first:      "Primero",
  //             previous:   "Anterior",
  //             next:       "Siguiente",
  //             last:       "Último"
  //         },
  //     },
  //     responsive: true,
  //     dom: 'Bfrtip',
  //     buttons: [
  //         'pageLength',
  //         { extend: 'excelHtml5', text:'<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
  //             exportOptions: {
  //                 columns: ':not(.exclude)'
  //             }
  //         },
  //         { extend: 'print', text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
  //             exportOptions: {
  //                 columns: ':not(.exclude)'
  //             }
  //         },
  //         {
  //             text: '<i class="fa-lg text-success fas fa-plus-circle"></i>',
  //             action: function () {
  //                 $('#registrar_evento').modal('show');
  //             }
  //         },
  //     ],
  //     lengthMenu: [ 10, 25, 50, 100 ]
  // } );

  // $('#tabla_ver_eventos').DataTable({
  //     language:{
  //         zeroRecords:'No hay coincidencias',
  //         info:'Mostrando _END_ resultados de _MAX_',
  //         infoEmpty:'No hay datos disponibles',
  //         infoFiltered:'(Filtrado de _MAX_ registros totales)',
  //         search:'Buscar',
  //         emptyTable:     "No existen registros",
  //         paginate: {
  //             first:      "Primero",
  //             previous:   "Anterior",
  //             next:       "Siguiente",
  //             last:       "Último"
  //         },
  //     },
  //     responsive: true,
  //     dom: 'Bfrtip',
  //     buttons: [
  //         'pageLength',
  //         { extend: 'excelHtml5', text:'<i class="fa-lg text-success fa-solid fa-file-excel"></i>',
  //             exportOptions: {
  //                 columns: ':not(.exclude)'
  //             }
  //         },
  //         { extend: 'print', text: '<i class="fa-lg text-danger fa-solid fa-print"></i>',
  //             exportOptions: {
  //                 columns: ':not(.exclude)'
  //             }
  //         },
  //         {
  //             text: '<i class=" text-center text-success fa-regular fa-paper-plane"></i>',
  //             action: function () {
  //                 $('#evento_enviar').modal('show');
  //             }
  //         },
  //         {
  //             text: '<i class="fa-lg bg-dark px-4 py-1 text-light bi bi-filter-square"></i>',
  //             action: function () {
  //                 $('#actualizar_lista').modal('show');
  //             }
  //         },
  //     ],
  //     lengthMenu: [ 10, 25, 50, 100 ]
  // } );

  // $('#tabla_empleo').DataTable({
  //     language:{
  //         zeroRecords:'No hay coincidencias',
  //         info:'Mostrando _END_ resultados de _MAX_',
  //         infoEmpty:'No hay datos disponibles',
  //         infoFiltered:'(Filtrado de _MAX_ registros totales)',
  //         search:'Buscar',
  //         emptyTable:     "No existen registros",
  //         paginate: {
  //             first:      "Primero",
  //             previous:   "Anterior",
  //             next:       "Siguiente",
  //             last:       "Último"
  //         },
  //     },
  //     responsive: true,
  //     dom: 'Bfrtip',
  //     buttons: [
  //         'pageLength',
  //         {
  //             text: '<i class="fa-lg text-success fas fa-plus-circle"></i>',
  //             action: function () {
  //                 $('#registrar_empleo').modal('show');
  //             }
  //         },
  //     ],
  //     lengthMenu: [ 10, 25, 50, 100 ]
  // } );

  // $('#tabla_carreras1').DataTable({
  //     language:{
  //         zeroRecords:'No hay coincidencias',
  //         info:'Mostrando _END_ resultados de _MAX_',
  //         infoEmpty:'No hay datos disponibles',
  //         infoFiltered:'(Filtrado de _MAX_ registros totales)',
  //         search:'Buscar',
  //         emptyTable:     "No existen registros",
  //         paginate: {
  //             first:      "Primero",
  //             previous:   "Anterior",
  //             next:       "Siguiente",
  //             last:       "Último"
  //         },
  //     },
  //     responsive: true,
  //     dom: 'Bfrtip',
  //     buttons: [
  //         'pageLength',
  //         {
  //             text: '<i class=" text-info fa-solid fa-building-columns"></i>',
  //             action: function () {
  //                 window.location.href = 'carrera2';
  //             }
  //         },
  //     ],
  //     lengthMenu: [ 10, 25, 50, 100 ]
  // } );

  // $('#tabla_carreras').DataTable({
  //     language:{
  //         zeroRecords:'No hay coincidencias',
  //         info:'Mostrando _END_ resultados de _MAX_',
  //         infoEmpty:'No hay datos disponibles',
  //         infoFiltered:'(Filtrado de _MAX_ registros totales)',
  //         search:'Buscar',
  //         emptyTable:     "No existen registros",
  //         paginate: {
  //             first:      "Primero",
  //             previous:   "Anterior",
  //             next:       "Siguiente",
  //             last:       "Último"
  //         },
  //     },
  //     responsive: true,
  //     dom: 'Bfrtip',
  //     buttons: [
  //         'pageLength',
  //         {
  //             text: '<i class=" fa-lg text-success fas fa-plus-circle"></i>',
  //             action: function () {
  //                 $('#registrar_carrera').modal('show');
  //             }
  //         },
  //     ],
  //     lengthMenu: [ 10, 25, 50, 100 ]
  // } );
});
