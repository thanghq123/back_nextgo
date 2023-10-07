@extends('layouts.admin')
@section('title','List')
@section('content')
    <div class="modal fade" tabindex="-1" id="kt_modal_stacked_2">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Stacked modal title</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    ...

                    <button type="button" class="btn btn-primary" data-bs-stacked-modal="#kt_modal_stacked_3">
                        Launch stacked modal
                    </button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="kt_modal_stacked_3" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Stacked modal title</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    ...
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
        <!--begin::Section-->
        <div class="p-0">
            <!--begin::Heading-->
            <h1 class="anchor fw-bold mb-5" id="server-side">
                <a href="#server-side"></a>
                List Pricing
            </h1>
            <!--end::Heading-->


            <!--begin::CRUD-->
            <div class="py-5">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-wrap mb-5">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1 mb-2 mb-md-0">
                        <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span class="path1"></span><span
                                class="path2"></span></i> <input type="text" data-kt-docs-table-filter="search"
                                                                 class="form-control form-control-solid w-250px ps-15"
                                                                 placeholder="Search Customers">
                    </div>
                    <!--end::Search-->

                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
                        <!--begin::Filter-->
                        <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-filter fs-2"><span class="path1"></span><span class="path2"></span></i>
                            Filter
                        </button>
                        <!--begin::Menu 1-->
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true"
                             id="kt-toolbar-filter" style="">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-4 text-dark fw-bold">Filter Options</div>
                            </div>
                            <!--end::Header-->

                            <!--begin::Separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Separator-->

                            <!--begin::Content-->
                            <div class="px-7 py-5">
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fs-5 fw-semibold mb-3">Payment Type:</label>
                                    <!--end::Label-->

                                    <!--begin::Options-->
                                    <div class="d-flex flex-column flex-wrap fw-semibold"
                                         data-kt-docs-table-filter="payment_type">
                                        <!--begin::Option-->
                                        <label
                                            class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                            <input class="form-check-input" type="radio" name="payment_type" value="all"
                                                   checked="checked">
                                            <span class="form-check-label text-gray-600">
                        All
                    </span>
                                        </label>
                                        <!--end::Option-->

                                        <!--begin::Option-->
                                        <label
                                            class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                            <input class="form-check-input" type="radio" name="payment_type"
                                                   value="visa">
                                            <span class="form-check-label text-gray-600">
                        Visa
                    </span>
                                        </label>
                                        <!--end::Option-->

                                        <!--begin::Option-->
                                        <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                            <input class="form-check-input" type="radio" name="payment_type"
                                                   value="mastercard">
                                            <span class="form-check-label text-gray-600">
                        Mastercard
                    </span>
                                        </label>
                                        <!--end::Option-->

                                        <!--begin::Option-->
                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" name="payment_type"
                                                   value="americanexpress">
                                            <span class="form-check-label text-gray-600">
                        American Express
                    </span>
                                        </label>
                                        <!--end::Option-->
                                    </div>
                                    <!--end::Options-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-light btn-active-light-primary me-2"
                                            data-kt-menu-dismiss="true" data-kt-docs-table-filter="reset">Reset
                                    </button>

                                    <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true"
                                            data-kt-docs-table-filter="filter">Apply
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Menu 1-->    <!--end::Filter-->

                        <!--begin::Add customer-->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#form-card"
                                data-kt-initialized="1">
                            <i class="ki-duotone ki-plus fs-2"></i> Add Customer
                        </button>
                        <!--end::Add customer-->
                    </div>
                    <!--end::Toolbar-->

                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none"
                         data-kt-docs-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-docs-table-select="selected_count">10</span> Selected
                        </div>

                        <button type="button" class="btn btn-danger" data-kt-docs-table-select="delete_selected">
                            Selection Action
                        </button>
                    </div>
                    <!--end::Group actions-->        </div>
                <!--end::Wrapper-->

                <!--begin::Datatable-->
                <div id="kt_datatable_example_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="table-responsive">
                        <table id="kt_datatable_example_1"
                               class="table align-middle table-row-dashed min-h-400px fs-6 gy-5 dataTable no-footer"
                               aria-describedby="kt_datatable_example_1_info" style="width: 1053px;">
                            <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2 sorting_disabled" rowspan="1" colspan="1" style="width: 30.5px;"
                                    aria-label="



                ">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true"
                                               data-kt-check-target="#kt_datatable_example_1 .form-check-input"
                                               value="1">
                                    </div>
                                </th>
                                <th class="sorting sorting_desc" tabindex="0" aria-controls="kt_datatable_example_1"
                                    rowspan="1" colspan="1" style="width: 150.25px;"
                                    aria-label="Customer Name: activate to sort column ascending"
                                    aria-sort="descending">Customer Name
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="kt_datatable_example_1" rowspan="1"
                                    colspan="1" style="width: 237.25px;"
                                    aria-label="Email: activate to sort column ascending">Email
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="kt_datatable_example_1" rowspan="1"
                                    colspan="1" style="width: 196.25px;"
                                    aria-label="Company: activate to sort column ascending">Company
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="kt_datatable_example_1" rowspan="1"
                                    colspan="1" style="width: 119.25px;"
                                    aria-label="Payment Method: activate to sort column ascending">Payment Method
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="kt_datatable_example_1" rowspan="1"
                                    colspan="1" style="width: 154.25px;"
                                    aria-label="Created Date: activate to sort column ascending">Created Date
                                </th>
                                <th class="text-end min-w-100px sorting_disabled" rowspan="1" colspan="1"
                                    style="width: 100.25px;" aria-label="Actions">Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                            <tr class="odd">
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="276">
                                    </div>
                                </td>
                                <td class="sorting_1">Rubina Ropars</td>
                                <td>rropars7n@stumbleupon.com</td>
                                <td>Kutch, Johnson and Hickle</td>
                                <td data-filter="mastercard"><img
                                        src="{{asset('assets/media/svg/card-logos/mastercard.svg')}}"
                                        class="w-35px me-3" alt="mastercard">**** 6640
                                </td>
                                <td>27 Nov 2020, 6:30 am</td>
                                <td class="  text-end">
                                    <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                       data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"
                                       data-kt-menu-flip="top-end">
                                        Actions
                                        <span class="svg-icon fs-5 m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                            <path
                                                d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z"
                                                fill="currentColor" fill-rule="nonzero"
                                                transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                        </g>
                                    </svg>
                                </span>
                                    </a>
                                    <!--begin::Menu-->
                                    <div
                                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                        data-kt-menu="true" style="">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#form-card" data-bs-toggle="modal" data-bs-target="#form-card"
                                               class="menu-link px-3"
                                            >
                                                Edit
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-billing-action="card-delete"
                                               data-kt-docs-table-filter="delete_row">
                                                Delete
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div id="kt_datatable_example_1_processing" class="dataTables_processing" role="status"
                             style="display: none;"><span
                                class="spinner-border w-15px h-15px text-muted align-middle me-2"></span> <span
                                class="text-gray-600">Loading...</span>
                            <div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div
                            class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                            <div class="dataTables_length" id="kt_datatable_example_1_length"><label><select
                                        name="kt_datatable_example_1_length" aria-controls="kt_datatable_example_1"
                                        class="form-select form-select-sm form-select-solid">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select></label></div>
                            <div class="dataTables_info" id="kt_datatable_example_1_info" role="status"
                                 aria-live="polite">Showing 1 to 10 of 350 records
                            </div>
                        </div>
                        <div
                            class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                            <div class="dataTables_paginate paging_simple_numbers" id="kt_datatable_example_1_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button page-item previous disabled"
                                        id="kt_datatable_example_1_previous"><a href="#"
                                                                                aria-controls="kt_datatable_example_1"
                                                                                data-dt-idx="0" tabindex="0"
                                                                                class="page-link"><i
                                                class="previous"></i></a></li>
                                    <li class="paginate_button page-item active"><a href="#"
                                                                                    aria-controls="kt_datatable_example_1"
                                                                                    data-dt-idx="1" tabindex="0"
                                                                                    class="page-link">1</a></li>
                                    <li class="paginate_button page-item "><a href="#"
                                                                              aria-controls="kt_datatable_example_1"
                                                                              data-dt-idx="2" tabindex="0"
                                                                              class="page-link">2</a></li>
                                    <li class="paginate_button page-item "><a href="#"
                                                                              aria-controls="kt_datatable_example_1"
                                                                              data-dt-idx="3" tabindex="0"
                                                                              class="page-link">3</a></li>
                                    <li class="paginate_button page-item "><a href="#"
                                                                              aria-controls="kt_datatable_example_1"
                                                                              data-dt-idx="4" tabindex="0"
                                                                              class="page-link">4</a></li>
                                    <li class="paginate_button page-item "><a href="#"
                                                                              aria-controls="kt_datatable_example_1"
                                                                              data-dt-idx="5" tabindex="0"
                                                                              class="page-link">5</a></li>
                                    <li class="paginate_button page-item disabled" id="kt_datatable_example_1_ellipsis">
                                        <a href="#" aria-controls="kt_datatable_example_1" data-dt-idx="6" tabindex="0"
                                           class="page-link">…</a></li>
                                    <li class="paginate_button page-item "><a href="#"
                                                                              aria-controls="kt_datatable_example_1"
                                                                              data-dt-idx="7" tabindex="0"
                                                                              class="page-link">35</a></li>
                                    <li class="paginate_button page-item next" id="kt_datatable_example_1_next"><a
                                            href="#" aria-controls="kt_datatable_example_1" data-dt-idx="8" tabindex="0"
                                            class="page-link"><i class="next"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Datatable-->
            </div>
            <!--end::CRUD-->
            @endsection
            @push('modal')
                <div class="modal fade" id="form-card" tabindex="-1" aria-hidden="true">
                    <!--begin::Modal dialog-->
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <!--begin::Modal content-->
                        <div class="modal-content">
                            <!--begin::Modal header-->
                            <div class="modal-header">
                                <!--begin::Modal title-->
                                <h2>Add New Card</h2>
                                <!--end::Modal title-->
                                <!--begin::Close-->
                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                    <i class="ki-duotone ki-cross fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <!--end::Modal header-->
                            <!--begin::Modal body-->
                            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                <!--begin::Form-->
                                <form id="form-submit" class="form" action="#">
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-column mb-7 fv-row">
                                        <!--begin::Label-->
                                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                            <span class="required">Name On Card</span>
                                            <span class="ms-1" data-bs-toggle="tooltip"
                                                  title="Specify a card holder's name">
                                <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                                        </label>
                                        <!--end::Label-->
                                        <input type="text" class="form-control form-control-solid" placeholder=""
                                               name="card_name" value="Max Doe"/>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-column mb-7 fv-row">
                                        <!--begin::Label-->
                                        <label class="required fs-6 fw-semibold form-label mb-2">Card Number</label>
                                        <!--end::Label-->
                                        <!--begin::Input wrapper-->
                                        <div class="position-relative">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid"
                                                   placeholder="Enter card number" name="card_number"
                                                   value="4111 1111 1111 1111"/>
                                            <!--end::Input-->
                                            <!--begin::Card logos-->
                                            <div class="position-absolute translate-middle-y top-50 end-0 me-5">
                                                <img src="{{asset('assets/media/svg/card-logos/visa.svg')}}" alt=""
                                                     class="h-25px"/>
                                                <img src="{{asset('assets/media/svg/card-logos/mastercard.svg')}}"
                                                     alt=""
                                                     class="h-25px"/>
                                                <img src="{{asset('assets/media/svg/card-logos/american-express.svg')}}"
                                                     alt=""
                                                     class="h-25px"/>
                                            </div>
                                            <!--end::Card logos-->
                                        </div>
                                        <!--end::Input wrapper-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="row mb-10">
                                        <!--begin::Col-->
                                        <div class="col-md-8 fv-row">
                                            <!--begin::Label-->
                                            <label class="required fs-6 fw-semibold form-label mb-2">Expiration
                                                Date</label>
                                            <!--end::Label-->
                                            <!--begin::Row-->
                                            <div class="row fv-row">
                                                <!--begin::Col-->
                                                <div class="col-6">
                                                    <select name="card_expiry_month"
                                                            class="form-select form-select-solid" data-control="select2"
                                                            data-hide-search="true" data-placeholder="Month">
                                                        <option></option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                    </select>
                                                </div>
                                                <!--end::Col-->
                                                <!--begin::Col-->
                                                <div class="col-6">
                                                    <select name="card_expiry_year"
                                                            class="form-select form-select-solid" data-control="select2"
                                                            data-hide-search="true" data-placeholder="Year">
                                                        <option></option>
                                                        <option value="2023">2023</option>
                                                        <option value="2024">2024</option>
                                                        <option value="2025">2025</option>
                                                        <option value="2026">2026</option>
                                                        <option value="2027">2027</option>
                                                        <option value="2028">2028</option>
                                                        <option value="2029">2029</option>
                                                        <option value="2030">2030</option>
                                                        <option value="2031">2031</option>
                                                        <option value="2032">2032</option>
                                                        <option value="2033">2033</option>
                                                    </select>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Row-->
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-md-4 fv-row">
                                            <!--begin::Label-->
                                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                <span class="required">CVV</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                      title="Enter a card CVV code">
                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input wrapper-->
                                            <div class="position-relative">
                                                <!--begin::Input-->
                                                <input type="text" class="form-control form-control-solid" minlength="3"
                                                       maxlength="4" placeholder="CVV" name="card_cvv"/>
                                                <!--end::Input-->
                                                <!--begin::CVV icon-->
                                                <div class="position-absolute translate-middle-y top-50 end-0 me-3">
                                                    <i class="ki-duotone ki-credit-cart fs-2hx">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                                <!--end::CVV icon-->
                                            </div>
                                            <!--end::Input wrapper-->
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-stack">
                                        <!--begin::Label-->
                                        <div class="me-5">
                                            <label class="fs-6 fw-semibold form-label">Save Card for further
                                                billing?</label>
                                            <div class="fs-7 fw-semibold text-muted">If you need more info, please check
                                                budget planning
                                            </div>
                                        </div>
                                        <!--end::Label-->
                                        <!--begin::Switch-->
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                   checked="checked"/>
                                            <span class="form-check-label fw-semibold text-muted">Save Card</span>
                                        </label>
                                        <!--end::Switch-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Actions-->
                                    <div class="text-center pt-15">
                                        <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                                            <span class="indicator-label">Submit</span>
                                            <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </div>
                                    <!--end::Actions-->
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end::Modal body-->
                        </div>
                        <!--end::Modal content-->
                    </div>
                    <!--end::Modal dialog-->
                </div>
            @endpush
            @push('js')
                <script>
                    var KTModalNewCard = function () {
                        var t, e, n, r, o, i;
                        return {
                            init: function () {
                                (i = document.querySelector("#form-card")) && (o = new bootstrap.Modal(i), r = document.querySelector("#form-submit"), t = document.getElementById("kt_modal_new_card_submit"), e = document.getElementById("kt_modal_new_card_cancel"), $(r.querySelector('[name="card_expiry_month"]')).on("change", (function () {
                                    n.revalidateField("card_expiry_month")
                                })), $(r.querySelector('[name="card_expiry_year"]')).on("change", (function () {
                                    n.revalidateField("card_expiry_year")
                                })), n = FormValidation.formValidation(r, {
                                    fields: {
                                        card_name: {validators: {notEmpty: {message: "Name on card is required"}}},
                                        card_number: {
                                            validators: {
                                                notEmpty: {message: "Card member is required"},
                                                creditCard: {message: "Card number is not valid"}
                                            }
                                        },
                                        card_expiry_month: {validators: {notEmpty: {message: "Month is required"}}},
                                        card_expiry_year: {validators: {notEmpty: {message: "Year is required"}}},
                                        card_cvv: {
                                            validators: {
                                                notEmpty: {message: "CVV is required"},
                                                digits: {message: "CVV must contain only digits"},
                                                stringLength: {
                                                    min: 3,
                                                    max: 4,
                                                    message: "CVV must contain 3 to 4 digits only"
                                                }
                                            }
                                        }
                                    },
                                    plugins: {
                                        trigger: new FormValidation.plugins.Trigger,
                                        bootstrap: new FormValidation.plugins.Bootstrap5({
                                            rowSelector: ".fv-row",
                                            eleInvalidClass: "",
                                            eleValidClass: ""
                                        })
                                    }
                                }), t.addEventListener("click", (function (e) {
                                    e.preventDefault(), n && n.validate().then((function (e) {
                                        console.log("validated!"), "Valid" == e ? (t.setAttribute("data-kt-indicator", "on"), t.disabled = !0, setTimeout((function () {
                                            t.removeAttribute("data-kt-indicator"), t.disabled = !1, Swal.fire({
                                                text: "Form has been successfully submitted!",
                                                icon: "success",
                                                buttonsStyling: !1,
                                                confirmButtonText: "Ok, got it!",
                                                customClass: {confirmButton: "btn btn-primary"}
                                            }).then((function (t) {
                                                t.isConfirmed && o.hide()
                                            }))
                                        }), 2e3)) : Swal.fire({
                                            text: "Sorry, looks like there are some errors detected, please try again.",
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {confirmButton: "btn btn-primary"}
                                        })
                                    }))
                                })))
                            }
                        }
                    }();
                    KTUtil.onDOMContentLoaded((function () {
                        KTModalNewCard.init()
                    }));
                    KTUtil.on(document.body, '[data-kt-billing-action="card-delete"]', "click", (function (t) {
                        t.preventDefault();
                        var n = this;
                        swal.fire({
                            text: "Are you sure you would like to delete selected card ?",
                            icon: "warning",
                            buttonsStyling: !1,
                            showDenyButton: !0,
                            confirmButtonText: "Yes",
                            denyButtonText: "No",
                            customClass: {confirmButton: "btn btn-primary", denyButton: "btn btn-light-danger"}
                        }).then((t => {
                            t.isConfirmed && (n.setAttribute("data-kt-indicator", "on"), n.disabled = !0, setTimeout((function () {
                                Swal.fire({
                                    text: "Your selected card has been successfully deleted",
                                    icon: "success",
                                    confirmButtonText: "Ok",
                                    buttonsStyling: !1,
                                    customClass: {confirmButton: "btn btn-light-primary"}
                                }).then((t => {
                                    n.closest('[data-kt-billing-element="card"]').remove()
                                }))
                            }), 2e3))
                        }))
                    }))
                </script>
                <!--begin::Vendors Javascript(used for this page only)-->
                <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
                <script src="{{asset('assets/js/custom/pages/user-profile/general.js')}}"></script>
                <script src="{{asset('assets/js/widgets.bundle.js')}}"></script>
                <script src="{{asset('assets/js/custom/widgets.js')}}"></script>
                <script src="{{asset('assets/js/custom/apps/chat/chat.js')}}"></script>
                <script src="{{asset('assets/js/custom/utilities/modals/upgrade-plan.js')}}"></script>
                <script src="{{asset('assets/js/custom/utilities/modals/create-app.js')}}"></script>
                <script src="{{asset('assets/js/custom/utilities/modals/offer-a-deal/type.js')}}"></script>
                <script src="{{asset('assets/js/custom/utilities/modals/offer-a-deal/details.js')}}"></script>
                <script src="{{asset('assets/js/custom/utilities/modals/offer-a-deal/finance.js')}}"></script>
                <script src="{{asset('assets/js/custom/utilities/modals/offer-a-deal/complete.js')}}"></script>
                <script src="{{asset('assets/js/custom/utilities/modals/offer-a-deal/main.js')}}"></script>
                <script src="{{asset('assets/js/custom/utilities/modals/new-address.js')}}"></script>
                <script src="{{asset('assets/js/custom/utilities/modals/users-search.js')}}"></script>
    @endpush
