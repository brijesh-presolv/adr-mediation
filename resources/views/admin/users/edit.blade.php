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
        <div class="card-box">
            <form action="{{route('admin.users.update')}}" method="post">
                <input type="hidden" name="id" value="{{$user->id}}">
                <h4 class="header-title"><b>Edit</b></h4>
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"  value="{{$user->first_name}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"  value="{{$user->last_name}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"  value="{{$user->email}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="username">username</label>
                            <input type="text" class="form-control" id="username" name="username"  value="{{$user->username}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="mobile_number">mobile number</label>
                            <input type="number" class="form-control" id="mobile_number" name="mobile_number"  value="{{$user->mobile_number}}">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="organization">organization</label>
                            <input type="text" class="form-control" id="organization" name="organization"  value="{{$user->organization}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="country_code">country code</label>
                            <input type="text" class="form-control" id="country_code" name="country_code"  value="{{$user->country_code}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="country">country</label>
                            <input type="text" class="form-control" id="country" name="country"  value="{{$user->country}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address">address Line1</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{$user->address}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address">address Line2</label>
                            <input type="text" class="form-control" id="address1" name="address1" value="{{$user->address1}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pincode">pincode</label>
                            <input type="number" class="form-control" id="pincode" name="pincode" value="{{$user->pincode}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="city">city</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{$user->city}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="state">state</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{$user->state}}">
                        </div>
                        <?php if($user->role==1){ ?>
                        <div class="form-group col-md-4">
                            <label for="state">Approve</label>
                            <select name="status" class="form-control">
                                <option value="1">Approve</option>
                                <option value="0">Unapprove</option>

                            </select>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
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

<script>

var userTable = $('#users').DataTable({
    "ajax": '{{ route("admin.users.json") }}',
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
                  <input type="checkbox" id="customSwitch` + row.id + `" name=="user_status" value="` + row.id + `" class="custom-control-input statuschang" ` + ((data == 1) ? "checked" : "") + `>
                  <label class="custom-control-label" for="customSwitch` + row.id + `"> </label>
                </div>
              </div>`;
                return button;
            }
        },
        {"data": "id", sortable: false,
            render: function (data, type, row) {
                var button = `<form method="post" action="{{route('admin.users.edit')}}">@csrf<button type="submit" value="` + data + `" class="btn btn-primary"><i class="fas fa-user-edit"></i></button></form> `;
                button += ` <form method="post" action="{{route('admin.users.edit')}}">@csrf<button type="submit"  value="` + data + `" class="btn btn-danger"><i class="far fa-trash-alt"></i></button></form>`;
                return button;
            }
        },
    ],
});
$(document).on('change', ".statuschang", function () {
    var id = $(this).val();
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    if ($(this).is(':checked')) {
        var status = 1;
    } else {
        var status = 0;
    }
    $.ajax({
        url: '{{ route("admin.users.status_change") }}',
        method: "post",
        data: {id: id, status: status, '_token': csrf},
    }).done(function (data) {
        userTable.ajax.reload()
    });
});
</script>
@endsection























