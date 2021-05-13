@extends('admin.layouts.app')
@section('title', "New Request")

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">New Request</a></li>
<!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <table  id="users" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>@lang('case.serial_number')</th>
                        <th>@lang('case.case_id')</th>
                        <th>@lang('case.date')</th>
                        <th>@lang('case.case_details')</th>
                        <th>@lang('case.party_details')</th>
                        <th>@lang('case.action')</th>
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
                <h5 class="modal-title" id="exampleModalLabel">@lang('case.assign_mediator')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="MidaterForm" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" class="form-control" id="recipient-name">

                    <div class="form-group">
                        <label for="message-text" class="col-form-label">@lang('case.form_mediator')</label>
                        <select class="form-control" name="midater"  required>
                            <option value="">@lang('case.form_select_mediator')</option>
                            @foreach($users as $user)
                            @if ($user->isActive)
                                <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}} - {{$user->organization}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Accept</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<!-- Table datatable css -->
@section('head')

<link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

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
    "ajax": '{{ route("admin.case.json",$confirm_status) }}',
    "responsive": true,
    "order": [[1, "desc"]],
    "columns": [
        {"data": "case.id",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {"data": "case.id",
            render: function (data) {
                return "M" + pad(data, 6);
            }
        },
        {"data": "date"},
        {"data": "case.id",
            render: function (data) {
                var d='';

                if(row.party.length==0){
                     d="disabled";
                }
                var button = ` <a href="{{ url('admin/casedetails/') }}/` + data + `" class="btn btn-primary waves-effect  waves-light btn-sm `+d+`" title="@lang('case.btn_case_details_view')"><i class="mdi mdi-file-eye-outline"></i></a> `;
                button = button+ ` <a href="{{ url('admin/updatecase/') }}/` + data + `" class="btn btn-info waves-effect waves-light btn-sm `+d+`" title="@lang('case.btn_case_details_edit')"><i class="mdi mdi-content-save-edit-outline"></i></a> `;
                return button;
            }
        },
        {"data": "party",
            render: function (data, type, row) {
                var d = "";
                for (i in data) {
                    if (data[i].isOnboarded == 1) {
                        d = d + `<span class="text-success">` + data[i].name + `</span><br>`;
                    } else {
                        d = d + `<span class="text-danger">` + data[i].name + `</span><br>`;
                    }
                }

                if(d==''){

                    return `<span class="text-danger">Pending</span><br>`;
                }
                return d;
            }
        },
        {"data": "case.id",
            render: function (data, type, row) {

                 var d='';

                if(row.party.length==0){
                     return button='NA';
                }

                var button = "";
                button = button + `<button value="` + data + `"  data-id="` + data + `" data-toggle="modal" data-target="#midaterAdd"  class="btn btn-info `+d+`">Confirm</button>`;
                button = button + ` <button value="` + data + `" class="btn btn-danger reject `+d+`">Reject</button>`;
                return button;
            }
        },
    ],
});
$(document).on('click', ".reject", function () {
    var id = $(this).val();
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    swal({
        title: "Are you sure?",
        text: "Reject this request!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: '{{ route("admin.case.reject_status") }}',
                method: "post",
                data: {id: id, '_token': csrf},
            }).done(function (data) {
                userTable.ajax.reload();
                swal("Reject successfully!", {
                    icon: "success",
                });
            });
        } else {
            swal("Cansel Reject Request!");
        }
    });
});
$(document).on('submit', "#MidaterForm", function () {
    var id = $(this).find("input[name='id']").val();
    var midater = $(this).find("select[name='midater']").val();
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    swal({
        title: "Are you sure?",
        text: "Confirm this request!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: '{{ route("admin.case.midater_add") }}',
                method: "post",
                data: {id: id, midater: midater, '_token': csrf},
            }).done(function (data) {
                swal("mediator assigned successfully!", {
                    icon: "success",
                });
                $.ajax({
                    url: '{{ route("admin.case.confirm_status") }}',
                    method: "post",
                    data: {id: id, '_token': csrf},
                }).done(function (data) {
                    userTable.ajax.reload()
                    swal("Confirm successfully!", {
                        icon: "success",
                    });
                });
                //userTable.ajax.reload();
                $('#midaterAdd').modal("hide");
            });


        } else {
            swal("Cansel Confirm Request!");
        }
    });
    return false;
});
$('#midaterAdd').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var recipient = button.data('id') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-body input[name="id"]').val(recipient)
})
</script>
@endsection
