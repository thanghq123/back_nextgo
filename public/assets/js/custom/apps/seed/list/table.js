"use strict";
var SeedList = function () {
    var dataTable, deleteRow;

    deleteRow = () => {
        dataTable.querySelectorAll('[data-kt-seed-table-filter="delete_row"]').forEach((element => {
            element.addEventListener("click", (function (event) {
                event.preventDefault();
                const row = event.target.closest("tr");
                let seed_id = row.attributes['data-id'].value;
                let seed_name = row.querySelectorAll('td')[0].innerText;

                Swal.fire({
                    text: "Bạn có chắc muốn xoá " + seed_name + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Đồng ý, xoá!",
                    cancelButtonText: "Không, huỷ bỏ!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((function (result) {
                    if (result.value) {
                        $.ajax({
                            url: (window.location.href + '/delete').replace('#', ''),
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id: seed_id
                            },
                            success: function (data) {
                                Swal.fire({
                                    text: "Xoá thành công " + seed_name + "!.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, đồng ý!",
                                    customClass: {confirmButton: "btn fw-bold btn-primary"}
                                }).then((function () {
                                    dataTable.row($(row)).remove().draw();
                                }));
                            }
                        });
                    } else if (result.dismiss === "cancel") {
                        Swal.fire({
                            text: seed_name + " chưa được xoá.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, đồng ý!",
                            customClass: {confirmButton: "btn fw-bold btn-primary"}
                        });
                    }
                }));
            }));
        }));
    };

    return {
        init: function () {
            dataTable = document.getElementById("kt_table_seed");

            if (dataTable) {
                dataTable = $(dataTable).DataTable({
                    info: false,
                    order: [],
                    pageLength: 10,
                    lengthChange: false,
                    columnDefs: [{orderable: false, targets: 0}, {orderable: false, targets: 3}]
                });

                dataTable.on("draw", (function () {
                    deleteRow();
                }));

                deleteRow();

                document.querySelector('[data-kt-seed-table-filter="search"]').addEventListener("keyup", (function (event) {
                    dataTable.search(event.target.value).draw();
                }));
            }
        }
    };
}();

KTUtil.onDOMContentLoaded((function () {
    SeedList.init();
}));
