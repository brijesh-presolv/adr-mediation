@extends('admin.layouts.app')
@section('title', 'Users')
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url("/")}}">Home</a></li>
    <li class="breadcrumb-item active">Users</li>
</ol>
@endsection
@section('content')
<!-- Info boxes -->
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Users</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="users" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Full Name</th>
                            <th>username</th>
                            <th>email</th>
                            <th>mobile number</th>
                            <th>User Type</th>
                            <th>Active/Inactive</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@endsection
@section('head')
<link rel="stylesheet" href="{{url('/assert/admin/')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{url('/assert/admin/')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="{{url('/assert/admin/')}}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection
@section('footer')
<script src="{{url('/assert/admin/')}}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{url('/assert/admin/')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{url('/assert/admin/')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{url('/assert/admin/')}}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{url('/assert/admin/')}}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{url('/assert/admin/')}}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script>

var userTable = $('#users').DataTable({
"ajax": '{{ route('admin.users.json') }}',
        "responsive": true,
        "columns": [
        {"data": "id"},
        {"data": "first_name",
                render: function (data, type, row) {
                return data + " " + row.last_name;
                }
        },
        {"data": "username"},
        {"data": "email"},
        {"data": "mobile_number"},
        {"data": "role",
                render: function (data, type, row) {
                if (data == 2) {
                var role = `<sapm class="badge badge-danger">Admin</span>`;
                } else if (data == 1) {
                var role = `<sapm class="badge badge-success">Mediator</span>`;
                } else {
                var role = `<sapm class="badge badge-info">User</span>`;
                }
                return role;
                }
        },
        {"data": "status",
                render: function (data, type, row) {
                var button = `<div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                      <input type="checkbox" name=="user_status" value="` + row.id + `" class="custom-control-input statuschang" ` + ((data == 1) ? "checked" : "") + `>
                      <label class="custom-control-label" for="customSwitch3"> </label>
                    </div>
                  </div>`;
                return button;
                }
        },
        {"data": "id", sortable: false,
                render: function (data, type, row) {
                var button = `<a href="#" class="btn btn-primary"><i class="fas fa-user-edit"></i></a> `;
                button += ` <a href="#" class="btn btn-danger"><i class="far fa-trash-alt"></i></a>`;
                return button;
                }
        },
        ],
});
$(document).on('change', ".statuschang", function () {
var id = $(this).val();
var csrf = document.querySelector('meta[name="csrf-token"]').content;
if ($(this).is(':checked')){
var status = 1;
} else{
var status = 0;
}
$.ajax({
url: '{{ route('admin.users.status_change') }}',
        method: "post",
        data: {id:id, status:status, '_token': csrf},
        }).done(function (data) {
        userTable.ajax.reload()
    });
});
</script>
@endsection