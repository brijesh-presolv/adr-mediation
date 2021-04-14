@extends('mediator.layouts.app')
@section('title', 'Users')

@section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">New request </a></li>
    <!-- end page title -->
@endsection

@section('content')


@section('pageTitleOnDashboard')
    <h4 class="page-title">New Request</h4>
@endsection


<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <table  id="request" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>Case Id</th>
                        <th>Party Details</th>
                        <th>Commets</th>
                        <th>Action</th>
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

var userTable = $('#request').DataTable({
"ajax": '{{ route('mediator.newjson') }}',
        "responsive": true,
        "columns": [
        {"data": "id"},
        {"data": "id",
                render: function (data, type, row) {
                return "MD00"+data
                }
        },
        {"data": "party",
                render: function (data, type, row) {
                    var d=[];
                    for(i in data){
                        d[i]=data[i].name;
                    }
                     return d.join(',');
                    
                }
        },
        {"data": "comments",
                render: function (data, type, row) {
                return data;
                }
        },
        {"data": "status",
                render: function (data, type, row) {

                    // if(data==1){
                      var button = `<button class="btn-sm btn-success" value="`+data.id+`" id="statuschang" >Accept</button><button class="btn-sm btn-danger mx-2" value="`+data.id+`" id="statuschang">Reject</button>`;
                        return button;  
                    // }else{
                    //   var button = `<button class="btn-sm btn-success" value="`+data.id+`" id="statuschang" >Accept</button>`;
                    //     return button;
                    // }



                  //   var button = `<div class="form-group">
                  //   <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                  //     <input type="checkbox" name=="user_status" id="customSwitch` + row.id + `" value="` + row.id + `" class="custom-control-input statuschang" ` + ((data == 1) ? "checked" : "") + `>
                  //     <label class="custom-control-label" for="customSwitch` + row.id + `"> </label>
                  //   </div>
                  // </div>`;
                  //   return button;
                }
        }
        ],
});
    $(document).on('click', "#statuschang", function () {


    var do_action = $(this).html();
    var id = $(this).val();

    if(do_action == 'Accept'){
        status = 1;
    }

    if(do_action == 'Reject'){
        status = 2
    }

    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    // if ($(this).is(':checked')){
    // var status = 1;
    // } else{
    // var status = 0;
    // }
    $.ajax({
    url: '{{ route('mediator.activeDeactive') }}',
            method: "post",
            data: {id:id, status:status, '_token': csrf},
            }).done(function (data) {
            userTable.ajax.reload()
        });
    });
</script>

@endsection





















