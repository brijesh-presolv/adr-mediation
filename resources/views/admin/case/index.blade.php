@extends('admin.layouts.app')
@section('title', ($confirm_status==0)?"New Request":"Mediator")

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">{{($confirm_status==0)?"newrequest":"Mediator" }} </a></li>
<!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <h4 class="header-title"><b>{{($confirm_status==0)?"newrequest":"Mediators" }}'s Data</b></h4>
            <table  id="users" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>Case Id</th>
                        <th>Party Details</th>
                        <th>Party Details</th>
                        <th>Commets</th>
                        <th>Assign Mediator</th>
                        <th>Acttion</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Midater Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="MidaterForm" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" class="form-control" id="recipient-name">

                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Midater:</label>
                        <select class="form-control" name="midater"  required>
                            <option value="">select Midater</option>
                            @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->username}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
            </form>
        </div>
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

<script>

var userTable = $('#users').DataTable({
    "ajax": '{{ route("admin.case.json",$confirm_status) }}',
    "responsive": true,
    "columns": [
        {"data": "id",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {"data": "id",
            render: function (data) {
                return "MD000" + data;
            }
        },
        {"data": "username"},
        {"data": "username"},
        {"data": "documentPath"},
        {"data": "confirm_status",
            render: function (data, type, row) {
                if (row.confirm_status == 1) {
                    if (row.mediator_username == null) {
                        var button = ` <button  data-id="` + row.id + `" value="` + row.id + `" data-toggle="modal" data-target="#midaterAdd" class="btn btn-success midater-add">Midater Add</button>`;
                    } else {
                        var button = ` <button  data-id="` + row.id + `" value="` + row.id + `" data-toggle="modal" data-target="#midaterAdd" class="btn btn-success midater-add">`+row.mediator_username+`</button>`;
                    }
                    //console.log(row.mediator_username);
                } else {
                    var button = `-`;
                }
                return button;
            }
        },
        {"data": "id",
            render: function (data, type, row) {
                if (row.confirm_status == 0) {
                    var button = `<button value="` + data + `" class="btn btn-info confirm">confirm</button>`;
                    button = button + ` <button value="` + data + `" class="btn btn-danger reject">Reject</button>`;
                } else if (row.confirm_status == 1) {
                    var button = ` <button value="` + data + `" class="btn btn-danger reject">Reject</button>`;
                } else {
                    var button = `<button value="` + data + `" class="btn btn-info confirm">confirm</button>`;
                }
                return button;
            }
        },
    ],
});
$(document).on('click', ".confirm", function () {
    var id = $(this).val();
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    $.ajax({
        url: '{{ route("admin.case.confirm_status") }}',
        method: "post",
        data: {id: id, '_token': csrf},
    }).done(function (data) {
        userTable.ajax.reload()
    });
});
$(document).on('click', ".reject", function () {
    var id = $(this).val();
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    $.ajax({
        url: '{{ route("admin.case.reject_status") }}',
        method: "post",
        data: {id: id, '_token': csrf},
    }).done(function (data) {
        userTable.ajax.reload()
    });
});
$(document).on('submit', "#MidaterForm", function () {
    var id = $(this).find("input[name='id']").val();
    var midater = $(this).find("select[name='midater']").val();
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    $.ajax({
        url: '{{ route("admin.case.midater_add") }}',
        method: "post",
        data: {id: id, midater: midater, '_token': csrf},
    }).done(function (data) {
        userTable.ajax.reload();
        $('#midaterAdd').modal("hide");
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























