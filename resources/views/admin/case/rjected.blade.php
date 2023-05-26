@extends('admin.layouts.app')
@section('title', 'Rejected Request')

@section('breadcrumb')
    <!-- start page title -->
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.rejected_request')</a></li>
    <!-- end page title -->
@endsection
@section('page_title', 'Rejected Request')

@section('content')
<style>
    table tbody .btn,  table tbody td{
        font-size: 14px;
    }
    table tbody button {
        margin-top: 7px;
    }
    table tbody input[type='checkbox'] {
        margin: 15px;
        height: 12px;
    }
</style>


    <section class="tabs-section">

        <div class="tabs-section-nav">

            <div class="tbl">

                <ul class="nav" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tabs-2-tab-3" role="tab" data-toggle="tab" id="tab1">
                            <span class="nav-link-in">
                                Bulk Cases
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#tabs-2-tab-1" role="tab" data-toggle="tab" id="tab2">
                            <span class="nav-link-in">
                                Individual Cases
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active show" id="tabs-2-tab-3">
                <div class="card-box table-responsive">

                    <table id="usersBulk" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('case.serial_number')</th>
                                <th>@lang('case.case_id')</th>
                                <th>@lang('case.ref_id')</th>
                                <th>@lang('case.date') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Date and time of raising the 'Request for Mediation'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.case_details') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to view the 'Request for Mediation'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.party_details')</th>
                                <th>@lang('case.status_logs') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Current status of the Mediation appears here."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tabs-2-tab-1">
                <div class="card-box table-responsive">

                    <table id="users" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('case.serial_number')</th>
                                <th>@lang('case.case_id')</th>
                                <th>@lang('case.ref_id')</th>
                                <th>@lang('case.date') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Date and time of raising the 'Request for Mediation'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.case_details') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to view the 'Request for Mediation'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.party_details')</th>
                                <th>@lang('case.status_logs') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Current status of the Mediation appears here."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="midaterAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('case.mediator_add')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="MidaterForm" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id" class="form-control" id="recipient-name">

                        <div class="form-group">
                            <label for="message-text" class="col-form-label">@lang('case.mediator'):</label>
                            <select class="form-control" name="midater" required>
                                <option value="">@lang('case.form_select_mediator')</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->username }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('case.btn_close')</button>
                        <button type="submit" class="btn btn-primary">@lang('case.btn_accept')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<!-- Table datatable css -->
@section('head')

    <link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

@endsection


@section('footer')
    <!-- Datatable plugin js -->
    <script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Datatables init -->
    <script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function pad(str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }

        var userTableBulk = $('#usersBulk').DataTable({
            "serverMethod": "POST",
            "sAjaxSource": '{{ route('admin.case.json', [$confirm_status, 1]) }}',
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "order": [
                [1, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, 100, 250, 500, 1000],
                [10, 25, 50, 100, 250, 500, 1000],
            ],
            "iDisplayLength": 10,
            "responsive": true,
            "columns": [{
                    "data": "case.id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "case.id",
                    render: function(data) {
                        var button = "M" + pad(data, 6);
                        button = button + `<br><a href="{{ url('admin/track/') }}/` + data +
                            `" target="_blank" class="btn btn-secondary waves-effect  waves-light btn-sm" title="Track">Track</a> `
                        return button;
                    }
                },
                {
                    "data": "case.ref_id",
                    render: function(data, type, row, meta) {
                        if (data == null) {
                            var button = "";
                            button = button + `<p style="font-size: 16px;"> -- </p>`;
                            return button;
                        } else {
                            var button = "";
                            button = button + `<p style="font-size: 16px;">` + data + `</p>`;
                            return button;
                        }

                    }
                },
                {
                    "data": "date"
                },
                {
                    "data": "case.id",
                    render: function(data, type, row) {
                        var button = `<a href="{{ url('admin/casedetails/') }}/` + data +
                            `" target="_blank" class="btn btn-primary waves-effect  waves-light"><i class="mdi mdi-file-eye-outline"></i></a> `;
                        
                        // Batch Name //
                        var batch =
                        `<p style="margin-bottom: 0px; margin-top: 5px; font-size:13px;">Batch Name</p><p style="color: blue; font-size:13px;">` +
                        row.case.batch_name + `</p> </div>`;
                        // Batch Name //
                        
                        return button + batch;
                    }
                },
                {
                    "data": "party",
                    render: function(data, type, row) {
                        var d = "";
                        var d_ip = "<strong>Initiating Party(s) :</strong><br/>";
                        var d_rp = "<br/><strong>Responding Party(s) :</strong><br/>";
                        for (i in data) {
                            if (data[i].isOnboarded == 1) {
                                var class_name = "text-success";
                            } else {
                                var class_name = "text-danger";
                            }

                            if (data[i].name != null && data[i].isClaimant == 0) {
                                d_ip = d_ip +
                                        `<span class="party_name" data-inid="` +
                                        data[
                                            i].id + `" data-id="` + data[i].userId +
                                        `">` + data[i]
                                        .name + `</span><br>`;
                            } else {
                                d_rp = d_rp +
                                        `<span class="`+ class_name + ` party_name" data-inid="` +
                                        data[i]
                                        .id + `" data-id="` + data[i].userId + `">` + data[
                                            i].name +
                                        `</span><br>`;
                            }


                            /*
                            if (data[i].isOnboarded == 1) {
                                if (data[i].name != null) {
                                    if (data[i].organization != null && data[i].isClaimant == 0) {
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .organization + `</span><br>`;

                                      
                                        var ip_name = data[i].name;
                                        if (ip_name != "") {
                                            d = d + `<span class="text-success" data-inid="` + data[
                                                    i].id + `" data-id="` + data[i].userId + `">` +
                                                ip_name + `</span><br>`;
                                        }
                                      
                                    } else {
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .name + `</span><br>`;
                                    }
                                }
                            } else {
                                if (data[i].name != null) {
                                    // d = d + `<span class="text-danger">` + data[i].name + `</span><br>`;
                                    if (data[i].isClaimant == 0) {
                                        d = d + `<span class="text-success" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .name + `</span><br>`;
                                    } else {
                                        d = d + `<span class="text-danger party_name" data-inid="` + data[i]
                                            .id + `" data-id="` + data[i].userId + `">` + data[i].name +
                                            `</span><br>`;
                                    }
                                }
                            }
                            */
                        }
                        d = d + d_ip + d_rp;
                        return d;
                    }
                },
                {
                    "data": "status_log",
                    render: function(data, type, row) {
                        var button = "";
                        for (i in data) {
                            if (data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_ADMIN }}' || data[
                                    i].status == '{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}'
                            ) {
                                button = button + `<span class="">` + data[i]
                                    .description + ` <br> At : ` + data[i].created + `</span><br>`;
                            }
                            if (data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_MEDIATOR }}') {
                                button = button + `<span class="">` + data[i].description +
                                    ` <br> At : ` + data[i].created + `</span><br>`;
                            }
                            if (data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_ADMIN }}' || data[
                                    i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_MEDIATOR }}' ||
                                data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}' || data[i]
                                .status == '{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}') {
                                button = button + `<span class="">` + data[i]
                                    .description + ` <br> At : ` + data[i].created + `</span><br>`;
                            }
                        }
                        return button;
                    }
                },
            ],
        });

        var userTable;
        $("a.nav-link").click(function() {
            // $("#filesForBulk").val('');

            if ($(this).attr("id") == "tab1") {
                userTableBulk.ajax.reload(null, false);
            }
            if ($(this).attr("id") == "tab2") {
                userTable = $('#users').DataTable({
                    "serverMethod": "POST",
                    "sAjaxSource": '{{ route('admin.case.json', [$confirm_status, 0]) }}',
                    "processing": true,
                    "serverSide": true,
                    "bDestroy": true,
                    "order": [
                        [1, "desc"]
                    ],
                    "lengthMenu": [
                        [10, 25, 50, 100, 250, 500, 1000],
                        [10, 25, 50, 100, 250, 500, 1000],
                    ],
                    "iDisplayLength": 10,
                    "responsive": true,
                    "columns": [{
                            "data": "case.id",
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "data": "case.id",
                            render: function(data) {
                                var button = "M" + pad(data, 6);
                                button = button + `<br><a href="{{ url('admin/track/') }}/` +
                                    data +
                                    `" target="_blank" class="btn btn-secondary waves-effect  waves-light btn-sm" title="Track">Track</a> `
                                return button;
                            }
                        },
                        {
                            "data": "case.ref_id",
                            render: function(data, type, row, meta) {
                                if (data == null) {
                                    var button = "";
                                    button = button + `<p style="font-size: 16px;"> -- </p>`;
                                    return button;
                                } else {
                                    var button = "";
                                    button = button + `<p style="font-size: 16px;">` + data + `</p>`;
                                    return button;
                                }

                            }
                        },
                        {
                            "data": "date"
                        },
                        {
                            "data": "case.id",
                            render: function(data, type, row) {
                                var button = `<a href="{{ url('admin/casedetails/') }}/` + data +
                                    `" target="_blank" class="btn btn-primary waves-effect  waves-light"><i class="mdi mdi-file-eye-outline"></i></a> `;
                                
                                // Batch Name //
                                var batch =
                                `<p style="margin-bottom: 0px; margin-top: 5px; font-size:13px;">Batch Name</p><p style="color: blue; font-size:13px;">` +
                                row.batch_name + `</p> </div>`;
                                // Batch Name //
                                
                                return button + batch;
                            }
                        },
                        {
                            "data": "party",
                            render: function(data, type, row) {
                                var d = "";
                                var d_ip = "<strong>Initiating Party(s) :</strong><br/>";
                                var d_rp = "<br/><strong>Responding Party(s) :</strong><br/>";
                                for (i in data) {
                                    if (data[i].isOnboarded == 1) {
                                        var class_name = "text-success";
                                    } else {
                                        var class_name = "text-danger";
                                    }

                                    if (data[i].name != null && data[i].isClaimant == 0) {
                                        d_ip = d_ip +
                                                `<span class="party_name" data-inid="` +
                                                data[
                                                    i].id + `" data-id="` + data[i].userId +
                                                `">` + data[i]
                                                .name + `</span><br>`;
                                    } else {
                                        d_rp = d_rp +
                                                `<span class="`+ class_name + ` party_name" data-inid="` +
                                                data[i]
                                                .id + `" data-id="` + data[i].userId + `">` + data[
                                                    i].name +
                                                `</span><br>`;
                                    }

                                    /*
                                    if (data[i].isOnboarded == 1) {
                                        if (data[i].name != null) {
                                            if (data[i].organization != null && data[i]
                                                .isClaimant == 0) {
                                                d = d +
                                                    `<span class="text-success party_name" data-inid="` +
                                                    data[
                                                        i].id + `" data-id="` + data[i].userId +
                                                    `">` + data[i]
                                                    .organization + `</span><br>`;

                                               
                                                var ip_name = data[i].name;
                                                if (ip_name != "") {
                                                    d = d +
                                                        `<span class="text-success party_name" data-inid="` +
                                                        data[
                                                            i].id + `" data-id="` + data[i].userId +
                                                        `">` + ip_name + `</span><br>`;
                                                }
                                                
                                            } else {
                                                d = d +
                                                    `<span class="text-success party_name" data-inid="` +
                                                    data[
                                                        i].id + `" data-id="` + data[i].userId +
                                                    `">` + data[i]
                                                    .name + `</span><br>`;
                                            }
                                        }
                                    } else {
                                        if (data[i].name != null) {
                                            // d = d + `<span class="text-danger">` + data[i].name +
                                            //     `</span><br>`;
                                            if (data[i].isClaimant == 0) {
                                                d = d + `<span class="text-success party_name" data-inid="` +
                                                    data[
                                                        i].id + `" data-id="` + data[i].userId +
                                                    `">` + data[i].name + `</span><br>`;
                                            } else {
                                                d = d +
                                                    `<span class="text-danger party_name" data-inid="` +
                                                    data[i]
                                                    .id + `" data-id="` + data[i].userId + `">` +
                                                    data[i].name +
                                                    `</span><br>`;
                                            }
                                        }
                                    }
                                    */
                                }
                                d = d + d_ip + d_rp;
                                return d;
                            }
                        },
                        {
                            "data": "status_log",
                            render: function(data, type, row) {
                                var button = "";
                                for (i in data) {
                                    if (data[i].status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_ADMIN }}' ||
                                        data[
                                            i].status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}'
                                    ) {
                                        button = button + `<span class="">` +
                                            data[i]
                                            .description + ` <br> At : ` + data[i].created +
                                            `</span><br>`;
                                    }
                                    if (data[i].status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_MEDIATOR }}'
                                    ) {
                                        button = button + `<span class="">` + data[
                                                i].description +
                                            ` <br> At : ` + data[i].created + `</span><br>`;
                                    }
                                    if (data[i].status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_ADMIN }}' ||
                                        data[
                                            i].status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_MEDIATOR }}' ||
                                        data[i].status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}' ||
                                        data[i]
                                        .status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}'
                                    ) {
                                        button = button + `<span class="">` +
                                            data[i]
                                            .description + ` <br> At : ` + data[i].created +
                                            `</span><br>`;
                                    }
                                }
                                return button;
                            }
                        },
                    ],
                });
            }
        });

        $(document).on('click', ".reject", function() {
            var id = $(this).val();
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            swal({
                title: "@lang('case.are_you_sure')",
                text: "@lang('case.reject_this_request')",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: '{{ route('admin.case.reject_status') }}',
                        method: "post",
                        data: {
                            id: id,
                            '_token': csrf
                        },
                    }).done(function(data) {
                        if (typeof userTable !== "undefined") {
                            userTable.ajax.reload(null, false);
                        } else {
                            userTablebulk.ajax.reload(null, false);
                        }
                        swal("@lang('case.reject_successfully')", {
                            icon: "success",
                        });
                    });
                } else {
                    swal("@lang('case.cansel_reject_request')");
                }
            });
        });
        $(document).on('submit', "#MidaterForm", function() {
            var id = $(this).find("input[name='id']").val();
            var midater = $(this).find("select[name='midater']").val();
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            swal({
                title: "@lang('case.are_you_sure')",
                text: "@lang('case.canform_this_request')",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: '{{ route('admin.case.midater_add') }}',
                        method: "post",
                        data: {
                            id: id,
                            midater: midater,
                            '_token': csrf
                        },
                    }).done(function(data) {
                        swal("@lang('case.mediator_assigned_successfully')", {
                            icon: "success",
                        });
                        $.ajax({
                            url: '{{ route('admin.case.confirm_status') }}',
                            method: "post",
                            data: {
                                id: id,
                                '_token': csrf
                            },
                        }).done(function(data) {
                            if (typeof userTable !== "undefined") {
                                userTable.ajax.reload(null, false);
                            } else {
                                userTablebulk.ajax.reload(null, false);
                            }
                            swal("@lang('case.conform_successfully')", {
                                icon: "success",
                            });
                        });
                        //userTable.ajax.reload();
                        $('#midaterAdd').modal("hide");
                    });


                } else {
                    swal("@lang('case.cansel_confirm_request')");
                }
            });
            return false;
        });
        $('#midaterAdd').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('id') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-body input[name="id"]').val(recipient)
        })
    </script>
@endsection
