"use strict";
var KTPricingList = function () {
    var e, t, n, r, o = document.getElementById("kt_table_pricing"),
        c = () => {
            o.querySelectorAll('[data-kt-pricing-table-filter="delete_row"]').forEach((t => {
                t.addEventListener("click", (function (t) {
                    t.preventDefault();
                    const n = t.target.closest("tr");
                    let pricing_id = n.attributes['data-id'].value;
                    let pricing_name = n.querySelectorAll('td')[0].innerText;
                    Swal.fire({
                        text: "Bạn có chắc muốn xoá " + pricing_name + "?",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Đồng ý, xoá!",
                        cancelButtonText: "Không, huỷ bỏ!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then((function (t) {
                            t.value ? $.ajax({
                                url: '/admin/pricing/delete',
                                type: 'DELETE',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                    id: pricing_id
                                },
                                success: function (data) {
                                    Swal.fire({
                                        text: "Xoá thành công " + pricing_name + "!.",
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, đồng ý!",
                                        customClass: {confirmButton: "btn fw-bold btn-primary"}
                                    }).then((function () {
                                        e.row($(n)).remove().draw()
                                    }))
                                }
                            }) : "cancel" === t.dismiss && Swal.fire({
                                text: pricing_name + " chưa được xoá.",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, đồng ý!",
                                customClass: {confirmButton: "btn fw-bold btn-primary"}
                            })
                        }
                    ))
                }))
            }))
        }

    return {
        init: function () {
            o && ((e = $(o).DataTable({
                info: !1,
                order: [],
                pageLength: 10,
                lengthChange: !1,
                columnDefs: [{orderable: !1, targets: 0}, {orderable: !1, targets: 4}]
            })).on("draw", (function () {
                // l(),
                c()
            })), document.querySelector('[data-kt-pricing-table-filter="search"]').addEventListener("keyup", (function (t) {
                e.search(t.target.value).draw()
            })), c())
        }
    }
}();
KTUtil.onDOMContentLoaded((function () {
    KTPricingList.init()
}));
