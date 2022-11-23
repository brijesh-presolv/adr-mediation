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
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">

                <table id="users" class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>@lang('case.serial_number')</th>
                            <th>@lang('case.case_id')</th>
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
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">@lang('case.btn_close')</button>
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

        var userTable = $('#users').DataTable({
            "serverMethod": "POST",
            "sAjaxSource": '{{ route('admin.case.json', $confirm_status) }}',
            "processing": true,
            "serverSide": true,
            "order": [[1, "desc"]],
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
                    "data": "date"
                },
                {
                    "data": "case.id",
                    render: function(data) {
                        var button = `<a href="{{ url('admin/casedetails/') }}/` + data +
                            `" target="_blank" class="btn btn-primary waves-effect  waves-light"><i class="mdi mdi-file-eye-outline"></i></a> `;
                        return button;
                    }
                },
                {
                    "data": "party",
                    render: function(data, type, row) {
                        var d = "";
                        for (i in data) {
                            if (data[i].isOnboarded == 1) {
                                if (data[i].name != null) {
                                    if (data[i].organization != null && data[i].isClaimant == 0) {
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .organization + `</span><br>`;
                                    } else {
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .name + `</span><br>`;
                                    }
                                }
                            } else {
                                if (data[i].name != null) {
                                    d = d + `<span class="text-danger">` + data[i].name + `</span><br>`;
                                }
                            }
                        }
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
                                button = button + `<span class="badge badge-success">` + data[i]
                                    .description + ` | At : ` + data[i].created + `</span><br>`;
                            }
                            if (data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_MEDIATOR }}') {
                                button = button + `<span class="badge badge-info ">` + data[i].description +
                                    ` | At : ` + data[i].created + `</span><br>`;
                            }
                            if (data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_ADMIN }}' || data[
                                    i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_MEDIATOR }}' ||
                                data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}' || data[i]
                                .status == '{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}') {
                                button = button + `<span class="badge badge-danger">` + data[i]
                                    .description + ` | At : ` + data[i].created + `</span><br>`;
                            }
                        }
                        return button;
                    }
                },
            ],
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
                        userTable.ajax.reload(null, false);
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
                            userTable.ajax.reload(null, false)
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
