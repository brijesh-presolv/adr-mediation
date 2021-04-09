@extends('admin.layouts.app')
@section('title', 'Users')

@section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard </a></li>
    <!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <h4 class="header-title"><b>User's Data</b></h4>
            <table  id="users" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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























