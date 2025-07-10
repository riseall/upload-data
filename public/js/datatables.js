$(document).ready(function () {
    // Inisialisasi DataTables untuk setiap tabel
    $("#otifTable").DataTable();
    $("#topTable").DataTable();
    $("#sellingOutTable").DataTable();
    $("#inventoryTable").DataTable();

    // Perbaikan untuk render tabel di dalam tab
    $('button[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
});
