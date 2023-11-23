"use strict";
const KTbusiness_fieldAddbusiness_field = function () {
    const modalForm = document.getElementById("kt_modal_add_business_field"),
        form = modalForm.querySelector("#kt_modal_add_business_field_form"), modal = new bootstrap.Modal(modalForm);
    var checkForm;
    const initFormValidation = (fields) => {
        checkForm = FormValidation.formValidation(form, {
            fields: fields, plugins: {
                trigger: new FormValidation.plugins.Trigger, bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row", eleInvalidClass: "", eleValidClass: ""
                })
            }
        });
    }
    const removeAccent = (str) => {
        return str.normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/đ/g, "d").replace(/Đ/g, "D");
    }
    return {
        init: function () {
            (() => {
                initFormValidation({
                    email_user: form.querySelector('[name="email_user"]')?{
                        validators: {
                            notEmpty: {message: "Email không được để trống"}, regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: "The value is not a valid email address"
                            }
                        }
                    }:null,
                    ten_chi_nhanh: form.querySelector('[name="ten_chi_nhanh"]') ? {validators: {notEmpty: {message: "Tên chi nhánh không được để trống"}}} : null,
                    ten_user: form.querySelector('[name="ten_user"]') ? {validators: {notEmpty: {message: "Tên người dùng không được để trống"}}} : null,
                    nguoi_tao: form.querySelector('[name="nguoi_tao"]') ? {validators: {notEmpty: {message: "Người tạo không được để trống"}}} : null,
                    linh_vuc_kinh_doanh: form.querySelector('[name="linh_vuc_kinh_doanh"]') ? {validators: {notEmpty: {message: "Lĩnh vực kinh doanh không được để trống"}}} : null,
                    trang_thai: form.querySelector('[name="trang_thai"]') ? {validators: {notEmpty: {message: "Trạng thái không được để trống"}}} : null,
                    name: form.querySelector('[name="name"]') ? {validators: {notEmpty: {message: "Tên không được để trống"}}} : null,
                    code: form.querySelector('[name="code"]') ? {validators: {notEmpty: {message: "Mã ngành hàng không được để trống"},}} : null,
                    detail: form.querySelector('[name="detail"]') ? {validators: {notEmpty: {message: "Mô tả không được để trống"}}} : null,
                    name_tenant: form.querySelector('[name="name_tenant"]') ? {validators: {notEmpty: {message: "Tên không được để trống"}}} : null,
                    user_id: form.querySelector('[name="user_id"]') ? {validators: {notEmpty: {message: "Chọn user"}}} : null,
                    business_field: form.querySelector('[name="business_field"]') ? {validators: {notEmpty: {message: "Chọn lĩnh vực kinh doanh"}}} : null,
                });
                // Add or remove validation rules when clicking on the show-add-user button
                let showuser = document.querySelector('#add-user')
                if (showuser) {
                    showuser.addEventListener('click', () => {
                        initFormValidation({
                            name_tenant: null,
                            user_id: null,
                            business_field: null,
                            username: {validators: {notEmpty: {message: "Tên không được để trống"}}},
                            email: {
                                validators: {
                                    notEmpty: {message: "Email không được để trống"}, regexp: {
                                        regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                        message: "The value is not a valid email address"
                                    }
                                }
                            },
                            password: {validators: {notEmpty: {message: "Mật khẩu không được để trống"}}},
                        })

                    })
                }
                let hiddenUser = document.querySelector('#hidden-user')
                if (hiddenUser) {
                    hiddenUser.addEventListener('click', () => {
                        initFormValidation({
                            name_tenant: form.querySelector('[name="name_tenant"]') ? {validators: {notEmpty: {message: "Tên không được để trống"}}} : null,
                            user_id: form.querySelector('[name="user_id"]') ? {validators: {notEmpty: {message: "Chọn user"}}} : null,
                            business_field: form.querySelector('[name="business_field"]') ? {validators: {notEmpty: {message: "Chọn lĩnh vực kinh doanh"}}} : null,
                            username: null,
                            email: null,
                            password: null,
                        });
                    });
                }
                const btn_submit = modalForm.querySelector('[data-kt-business_field-modal-action="submit"]');
                btn_submit.addEventListener("click", (modalForm => {
                    let business_field_id = form.querySelector('[name="id"]') ? form.querySelector('[name="id"]').value : null;
                    modalForm.preventDefault(), checkForm && checkForm.validate().then((function (modalForm) {
                        if ("Valid" == modalForm) {
                            (btn_submit.setAttribute("data-kt-indicator", "on"), btn_submit.disabled = !0, $.ajax({
                                url: business_field_id != null ? (window.location.href + '/update').replace('#', '') : (window.location.href + '/store').replace('#', ''),
                                method: business_field_id != null ? 'PUT' : 'POST',
                                data: {
                                    _token: form.querySelector('[name="_token"]').value,
                                    business_name: form.querySelector('[name="business_name"]') ? form.querySelector('[name="business_name"]').value : null,
                                    address: form.querySelector('[name="address"]') ? form.querySelector('[name="address"]').value : null,
                                    name: form.querySelector('[name="name"]') ? form.querySelector('[name="name"]').value : null,
                                    code: form.querySelector('[name="code"]') ? form.querySelector('[name="code"]').value.toUpperCase() : null,
                                    detail: form.querySelector('[name="detail"]') ? form.querySelector('[name="detail"]').value : null,
                                    name_tenant: form.querySelector('[name="name_tenant"]') ? removeAccent(form.querySelector('[name="name_tenant"]').value.replace(/\s/g, "")) : null,
                                    business_field: form.querySelector('[name="business_field"]') ? form.querySelector('[name="business_field"]').value : null,
                                    username: form.querySelector('[name="username"]') ? form.querySelector('[name="username"]').value : null,
                                    user_id: form.querySelector('[name="user_id"]') ? form.querySelector('[name="user_id"]').value : null,
                                    email: form.querySelector('[name="email"]') ? form.querySelector('[name="email"]').value : null,
                                    password: form.querySelector('[name="password"]') ? form.querySelector('[name="password"]').value : null,
                                    id: business_field_id != "" ? business_field_id : null
                                },
                                success: function (data) {
                                    if (!data.status) {
                                        btn_submit.removeAttribute("data-kt-indicator"), btn_submit.disabled = !1, Swal.fire({
                                            text: data.meta,
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, đồng ý!",
                                            customClass: {confirmButton: "btn btn-primary"}
                                        })
                                    } else {
                                        btn_submit.removeAttribute("data-kt-indicator"), btn_submit.disabled = !1, Swal.fire({
                                            text: data.payload,
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, đồng ý!",
                                            customClass: {confirmButton: "btn btn-primary"}
                                        }).then((function (modalForm) {
                                            modalForm.isConfirmed && modal.hide()
                                            window.location.reload()
                                        }))
                                    }
                                },
                                error: function (data) {
                                    btn_submit.removeAttribute("data-kt-indicator"), btn_submit.disabled = !1, Swal.fire({
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
