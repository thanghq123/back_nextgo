"use strict";
const KTbusiness_fieldAddbusiness_field = function () {
    const modalForm = document.getElementById("kt_modal_add_business_field"),
        form = modalForm.querySelector("#kt_modal_add_business_field_form"),
        modal = new bootstrap.Modal(modalForm);
    return {
        init: function () {
            (() => {
                var checkForm = FormValidation.formValidation(form, {
                    fields: {
                        name: {validators: {notEmpty: {message: "Tên không được để trống"}}},
                        code: {validators: {notEmpty: {message: "Mã ngành hàng không được để trống"}}},
                        detail: {validators: {notEmpty: {message: "Mô tả không được để trống"}}},
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger,
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: ""
                        })
                    }
                });
                const btn_submit = modalForm.querySelector('[data-kt-business_field-modal-action="submit"]');
                btn_submit.addEventListener("click", (modalForm => {
                        let business_field_id = form.querySelector('[name="id"]')?form.querySelector('[name="id"]').value:"";
                    modalForm.preventDefault(), checkForm && checkForm.validate().then((function (modalForm) {
                        if ("Valid" == modalForm) {
                            (btn_submit.setAttribute("data-kt-indicator", "on"), btn_submit.disabled = !0,
                                $.ajax({
                                    url: business_field_id!=""?'/admin/business-field/update':'/admin/business-field/store',
                                    method: business_field_id!=""?'PUT':'POST',
                                    data: {
                                        _token: form.querySelector('[name="_token"]').value,
                                        name: form.querySelector('[name="name"]').value,
                                        code: form.querySelector('[name="code"]').value.toUpperCase(),
                                        detail: form.querySelector('[name="detail"]').value,
                                        id: business_field_id!=""?business_field_id:null
                                    },
                                    success: function (data) {
                                        btn_submit.removeAttribute("data-kt-indicator"), btn_submit.disabled = !1,
                                            Swal.fire({
                                                text: data.payload,
                                                icon: "success",
                                                buttonsStyling: !1,
                                                confirmButtonText: "Ok, đồng ý!",
                                                customClass: {confirmButton: "btn btn-primary"}
                                            }).then((function (modalForm) {
                                                modalForm.isConfirmed && modal.hide()
                                                window.location.reload()
                                            }))
                                    }, error: function (data) {
                                        btn_submit.removeAttribute("data-kt-indicator"), btn_submit.disabled = !1,
                                            Swal.fire({
                                                text: "Xin lỗi, đã có lỗi xảy ra xin vui lòng thử lại.",
                                                icon: "error",
                                                buttonsStyling: !1,
                                                confirmButtonText: "Ok, đồng ý!",
                                                customClass: {confirmButton: "btn btn-primary"}
                                            })
                                    }
                                }))
                        } else {
                            Swal.fire({
                                text: "Xin lỗi, đã có lỗi xảy ra xin vui lòng thử lại.",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, đồng ý!",
                                customClass: {confirmButton: "btn btn-primary"}
                            })
                        }
                    }))
                }))
                modalForm.querySelector('[data-kt-business_field-modal-action="cancel"]').addEventListener("click", (modalForm => {
                    modalForm.preventDefault(), Swal.fire({
                        text: "Bạn có chắc chắn muốn huỷ?",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Đúng, huỷ nó!",
                        cancelButtonText: "Không, trở lại",
                        customClass: {confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light"}
                    }).then((function (modalForm) {
                        modalForm.value ? (form.reset(), modal.hide()) : "cancel" === modalForm.dismiss && Swal.fire({
                            text: "Biểu mẫu của bạn chưa bị hủy!.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, đồng ý!",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                    }))
                })), modalForm.querySelector('[data-kt-business_field-modal-action="close"]').addEventListener("click", (modalForm => {
                    modalForm.preventDefault(), Swal.fire({
                        text: "Bạn có chắc chắn muốn huỷ?",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Đúng, huỷ nó!",
                        cancelButtonText: "Không, trở lại",
                        customClass: {confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light"}
                    }).then((function (modalForm) {
                        modalForm.value ? (form.reset(), modal.hide()) : "cancel" === modalForm.dismiss && Swal.fire({
                            text: "Biểu mẫu của bạn chưa bị hủy!.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Đúng, huỷ nó!",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                    }))
                }))
            })()
        }
    }
}();
KTUtil.onDOMContentLoaded((function () {
    KTbusiness_fieldAddbusiness_field.init()
}));
