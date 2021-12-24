@extends('admin.layouts.app')
@section('title', ($role=="user")?"Users":"Mediator")

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">{{($role=="user")?"Users":"Mediator" }} </a></li>
<!-- end page title -->
@endsection

@section('content')
<section class="tabs-section">
<div class="tabs-section-nav">
    <div class="tbl">
      <ul class="nav" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" href="#tabs-2-tab-1" role="tab" data-toggle="tab" id="tab1">
            <span class="nav-link-in">
              Approved {{($role=="user")?"Users":"Mediator" }}
            </span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#tabs-2-tab-2" role="tab" data-toggle="tab" id="tab2">
            <span class="nav-link-in">
              Unapproved {{($role=="user")?"Users":"Mediator" }}
            </span>
          </a>
        </li>
      </ul>
    </div>
  </div>
  <!--.tabs-section-nav-->

  <div class="tab-content">
    <div role="tabpanel" class="tab-pane fade in active show" id="tabs-2-tab-1">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive">
                    <h4 class="header-title"><b>{{($role=="user")?"Users":"Mediators" }}'s Data</b></h4>
                    <table  id="usersApprove" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Full Name</th>
                                <th>username</th>
                                <th>email</th>
                                <th>mobile number</th>
                                <th>User Type</th>
                                <th>Active/Inactive</th>
                                <th>Approve/Unapprove</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--.tab-pane-->
    <div role="tabpanel" class="tab-pane fade" id="tabs-2-tab-2">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive">
                    <h4 class="header-title"><b>{{($role=="user")?"Users":"Mediators" }}'s Data</b></h4>
                    <table  id="usersUnapprove" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Full Name</th>
                                <th>username</th>
                                <th>email</th>
                                <th>mobile number</th>
                                <th>User Type</th>
                                <th>Active/Inactive</th>
                                <th>Approve/Unapprove</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--.tab-pane-->
  </div>
</section>

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

// userTable.reload();

DataTables("usersApprove", '{{ route("admin.users.jsonApprove",$role) }}')
var userTable;
var tableid;
var link;
function DataTables(tableID, url) {
    userTable = $('#'+tableID).DataTable({
            "ajax": url,
            "responsive": true,
            "pageLength":50,
            "retrieve": true,
            // "paging": false,
            // "searching": false,
           
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
                {"data": "isActive",
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
                {"data": "status",
                    render: function (data, type, row) {
                        var button = `<div class="form-group">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <input type="checkbox" id="status_change_approvel` + row.id + `" name=="user_status" value="` + row.id + `" class="custom-control-input status_change_approvel" ` + ((data == 1) ? "checked" : "") + `>
                        <label class="custom-control-label" for="status_change_approvel` + row.id + `"> </label>
                        </div>
                    </div>`;
                        return button;
                    }
                },
                {"data": "id", sortable: false,
                    render: function (data, type, row) {
                        var button = `<form method="post" action="{{route('admin.users.edit', '')}}/`+data+`"> @csrf <button type="submit" value="` + data + `" name="id" class="btn btn-primary"><i class="fas fa-user-edit"></i></button></form> `;
                        button += ` <form method="post" > @csrf <button type="submit"  value="` + data + `" name="id"  class="btn btn-danger"><i class="far fa-trash-alt"></i></button></form>`;
                        return button;
                    }
                },
            ],
    });
}
$(document).ready(function () {
    $("a.nav-link").click(function () {
      if ($(this).attr("id") == "tab1") {
          link = '{{ route("admin.users.jsonApprove",$role) }}';
          tableid = "usersApprove";
        //   userTable.ajax.reload( null, false);

        // DataTables("usersApprove", '{{ route("admin.users.jsonApprove",$role) }}')
      } else if ($(this).attr("id") == "tab2") { 
        link = '{{ route("admin.users.jsonUnapprove",$role) }}';
        tableid = "usersUnapprove";
        // userTable.ajax.reload( null, false);
        // DataTables(tableID, url);
        // DataTables("usersUnapprove", '{{ route("admin.users.jsonUnapprove",$role) }}')
      }
      DataTables(tableid, link);
        userTable.ajax.reload( null, false)
      
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
        // userTable.ajax.reload(null, false)
    });
    });
    $(document).on('change', ".status_change_approvel", function () {
        var id = $(this).val();
        var csrf = document.querySelector('meta[name="csrf-token"]').content;
        if ($(this).is(':checked')) {
            var status = 1;
        } else {
            var status = 0;
        }
        $.ajax({
            url: '{{ route("admin.users.status_change_approvel") }}',
            method: "post",
            data: {id: id, status: status, '_token': csrf},
        }).done(function (data) {
            userTable.ajax.reload( null, false)
        });
    });

});



</script>
@endsection