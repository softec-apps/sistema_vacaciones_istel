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
    buttons: ["pageLength"],
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

  $("#tablaConsultaTrabajo").DataTable({
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

  $("#tablaArchivosSubidos").DataTable({
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

  $("#tablaSubirArchivos").DataTable({
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
});
