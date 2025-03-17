@extends('admin.layouts.app')
@section('title', "Manage Users")
<style>
  body{
    padding-bottom: 20px !important;
  }
  #wrapper {
    overflow-y: scroll !important;
  }
</style>


<!------- Collapse Style ------------->
<style>
  #accordion h4.panel-title a {
    display: inline-block;
    width: 100%;
    font-size: 15px;
    color: #797979;
    font-style: italic;
  }

  #accordion h4.panel-title {
    border: 1px solid #cdcdcd;
    border-radius: 15px;
    padding: 7px;
    position: relative;
  }

  #accordion h4.panel-title i {
    position: absolute;
    font-size: 13px;
    right: 1%;
    top: 25%;
  }

  #accordion .panel {
    background-color: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
  }

  #accordion .panel-collapse {
    padding: 10px;
  }

  #users a.btn {
    padding: 0px 10px !important;
    color: #fff;
  }
</style>
<!------- Collapse Style ------------->

    @section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">Manage User </a></li>
    <!-- end page title -->
    @endsection

    @section('page_title', "Manage Users")
@section('content')

<div class="container">
  <div class="row">
    <div class="col-sm-12">
          <div class="card-box table-responsive">
              <h4 class="header-title"><b>Manage User</b></h4>
                <div class="card-body">
                      <form method="POST" action="{{route('admin.addmiiuser')}}">
                          <input type="hidden" name="an" value="cp">
                          @csrf 
                          <div class="form-group row">
                              <label for="first_name" class="col-md-4 col-form-label text-md-right">First Name</label>
              
                              <div class="col-md-6">
                                  <input id="first_name" type="text" class="form-control" name="first_name" autocomplete="first_name" placeholder="First Name" value="{{ old('first_name') }}">
                                  @if($errors->has('first_name'))
                                      <div class="text-danger"><b>{{ $errors->first('first_name') }}</b></div>
                                  @endif
                              </div>
                          </div>
    
                          <div class="form-group row">
                              <label for="last_name" class="col-md-4 col-form-label text-md-right">Last Name</label>
    
                              <div class="col-md-6">
                                  <input id="last_name" type="text" class="form-control" name="last_name" autocomplete="last_name" placeholder="Last Name" value="{{ old('last_name') }}">
                                  @if($errors->has('last_name'))
                                      <div class="text-danger"><b>{{ $errors->first('last_name') }}</b></div>
                                  @endif
                              </div>
                          </div>
    
                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right"> Mobile</label>
      
                              <div class="col-md-6">
                                  <input id="mobile_number" type="text" class="form-control" name="mobile_number" autocomplete="mobile_number" placeholder="Mobile" >
                                  @if($errors->has('mobile_number'))
                                      <div class="text-danger"><b>{{ $errors->first('mobile_number') }}</b></div>
                                  @endif
                              </div>
                          </div>


                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right"> Email</label>
      
                              <div class="col-md-6">
                                  <input id="email" type="text" class="form-control" name="email" autocomplete="email" placeholder="Email" >
                                  @if($errors->has('email'))
                                      <div class="text-danger"><b>{{ $errors->first('email') }}</b></div>
                                  @endif
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right"> Password</label>
      
                              <div class="col-md-6">
                                  <input id="password" type="password" class="form-control" name="password" autocomplete="password" placeholder="Password: Minimum 6 characters, 1 uppercase, 1 lowercase, 1 numerical" >
                                  @if($errors->has('password'))
                                      <div class="text-danger"><b>{{ $errors->first('password') }}</b></div>
                                  @endif
                              </div>
                          </div>
    
                          <div class="form-group row mb-0">
                              <div class="col-md-6 offset-md-4 text-center">
                                  <button type="submit" class="btn-sm btn-primary">
                                      Add User
                                  </button>
                              </div>
                          </div>
                      </form>
              </div>
          </div>
      </div>
  </div>




  <div class="row">
    <div class="col-sm-12">
      <div class="card-box table-responsive">

          <table id="users" id="" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                  <?php
                  foreach($sub_users as $key => $sub) {
                  ?>
                  <tr>
                      <td><?php echo $key+1; ?></td>
                      <td><?php echo $sub->first_name .' '.$sub->last_name; ?></td>
                      <td><?php echo $sub->email; ?></td>
                      <td><?php echo $sub->mobile_number; ?></td>
                      <td>
                        <a data-toggle="modal" data-target="#updateSubUser-modal" data-subid="<?php echo $sub->id;?>" target="_blank" class="btn btn-primary waves-effect  waves-light btn-sm" title="Edit User Info" style="padding: 10px">
                            <i class="mdi mdi-content-save-edit-outline"></i>
                        </a>  
                        <a data-subid="<?php echo $sub->id;?>" target="_blank" class="btn btn-info waves-effect waves-light btn-sm deleteSubUser" title="Delete User" style="padding: 10px">
                            <i class="mdi mdi-trash-can-outline"></i>
                        </a>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
          </table>

      </div>
    </div>
  </div>
</div>




<!------- Update Sub user modal --------------------->
<div class="modal fade h-75" id="updateSubUser-modal" tabindex="-1" role="dialog" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h4 class="modal-title text-white">Update User</h4>
                    <!-- <h5 class="modal-title mt-0">Last Session Records</h5> -->
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateSubUser">
                      <input type="hidden" name="createdBy" id="editcreatedByF" value="{{ Auth::id() }}">
                      <input type="hidden" name="UserId" id="editUserId">

                          <div class="form-group row">
                              <label for="first_name" class="col-md-4 col-form-label text-md-right">First Name</label>
              
                              <div class="col-md-6">
                                  <input id="first_name" type="text" class="form-control" name="first_name" autocomplete="first_name" placeholder="First Name" value="{{ old('first_name') }}">
                                  @if($errors->has('first_name'))
                                      <div class="text-danger"><b>{{ $errors->first('first_name') }}</b></div>
                                  @endif
                              </div>
                          </div>
    
                          <div class="form-group row">
                              <label for="last_name" class="col-md-4 col-form-label text-md-right">Last Name</label>
    
                              <div class="col-md-6">
                                  <input id="last_name" type="text" class="form-control" name="last_name" autocomplete="last_name" placeholder="Last Name" value="{{ old('last_name') }}">
                                  @if($errors->has('last_name'))
                                      <div class="text-danger"><b>{{ $errors->first('last_name') }}</b></div>
                                  @endif
                              </div>
                          </div>
    
                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right"> Mobile</label>
      
                              <div class="col-md-6">
                                  <input id="mobile_number" type="text" class="form-control" name="mobile_number" autocomplete="mobile_number" placeholder="Mobile" >
                                  @if($errors->has('mobile_number'))
                                      <div class="text-danger"><b>{{ $errors->first('mobile_number') }}</b></div>
                                  @endif
                              </div>
                          </div>


                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right"> Email</label>
      
                              <div class="col-md-6">
                                  <input id="email" type="text" class="form-control" name="email" autocomplete="email" placeholder="Email" >
                                  @if($errors->has('email'))
                                      <div class="text-danger"><b>{{ $errors->first('email') }}</b></div>
                                  @endif
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right"> Password</label>
      
                              <div class="col-md-6">
                                  <input id="password" type="password" class="form-control" name="password" autocomplete="password" placeholder="Password: Minimum 6 characters, 1 uppercase, 1 lowercase, 1 numerical" >
                                  @if($errors->has('password'))
                                      <div class="text-danger"><b>{{ $errors->first('password') }}</b></div>
                                  @endif
                              </div>
                          </div>
    
                          <div class="form-group row mb-0">
                              <div class="col-md-6 offset-md-4 text-center">
                                  <button type="submit" class="btn-sm btn-primary" id="updateSubUserData">
                                      Update User
                                  </button>
                              </div>
                          </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm btn-primary" data-dismiss="modal" aria-label="Close">
                        <span>Close</span>
                    </button>
                    <div id="sessionShowBtn" class="text-center"></div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<!------- Update Sub user modal --------------------->



@endsection
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

<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="{{ url('/') }}/assets/js/sweetalert.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#users').DataTable();


    $('#updateSubUser-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
           // $("#editsessionParty").html("");
            var editId = button.data('subid');
            $.ajax({
                type: "GET",
                url: "{{ route('admin.getSubUserData') }}",
                data: {
                    id: editId
                },
                dataType: "JSON",
                success: function(response) {
                  // console.log(response.sub_user_data.first_name);
                  // return false;
                    if (response.sub_user_data.id) {
                        // var data = $('#Sessview' + response.case_id).parent().parent().find(
                        //     '.party_name');

                         $('#editUserId').attr('value', response.sub_user_data.id);
                        // $('#CaseId').attr('value', response.case_id);
                        // $('#zoomChoice').attr('value', response.zoom_link_choice);
                        // $('#editsessionDate').attr('value', response.session_date.substring(0, 10));
                        // var convertTime = convertTime12to24(response.session_date.substring(11));
                        // $('#editsessionTime').attr('value', convertTime);
                        $('#updateSubUser #first_name').attr('value', response.sub_user_data.first_name);
                        $('#updateSubUser #last_name').attr('value', response.sub_user_data.last_name);
                        $('#updateSubUser #mobile_number').attr('value', response.sub_user_data.mobile_number);
                        $('#updateSubUser #email').attr('value', response.sub_user_data.email);
                       

                       

                       
                    } else {
                        console.log('response Not Found');
                    }

                }
            });
        });







        $("#updateSubUser").on('submit', function(e) {
            e.preventDefault();
            var formSet = $('#updateSubUser').serialize();
            $.ajax({
                type: "POST",
                url: "{{ route('admin.updateSubUser') }}",
                data: formSet,
                dataType: "JSON",
                beforeSend: function() {
                    //$('#viewSession-modal').modal("hide");

                    swal({
                        title: 'Loading...',
                        showConfirmButton: false,
                        buttons: false,
                        allowOutsideClick: false,
                    });
                },
                success: function(response) {
                    //$('#Session-edit').modal('hide');
                    swal("User updated successfully.", {
                        icon: "success",
                    }).then(function() {
                        location.reload();
                    });
                }
            });
        });


        $('.deleteSubUser').on('click', function(e){
          var deleteId = $(this).data('subid');


          swal({
                title: "@lang('case.are_you_sure')",
                text:  "This user will be deleted and it won't be revert back.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                }).then(function(willDelete) {

                  if(willDelete){
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.deleteSubUser') }}",
                        data: {deleteId : deleteId},
                        dataType: "JSON",
                        
                        beforeSend: function() {

                            swal({
                                title: 'Loading...',
                                showConfirmButton: false,
                                buttons: false,
                                allowOutsideClick: false,
                            });
                        },
                        success: function(response) {
                            swal("User deleted Successfully.", {
                                icon: "success",
                            }).then(function() {
                                location.reload();
                            });
                        }
                    });
                  }

          });
           
          
        });
  });
</script>
@endsection


