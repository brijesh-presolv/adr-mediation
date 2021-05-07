<?php
use App\Models\InvoledUser;
?>
@extends('user.layouts.app')
@section('title', 'Pending')

@section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">Pending </a></li>
    <!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <!-- <h4 class="header-title"><b>New Request</b></h4> -->
            <table  id="datatable" id="users" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>Case Id</th>
                        <th>Date</th>
                        <th>Case Details</th>
                        <th>Party Details</th>
                        <th>Action</th>
                        <td>Status</td>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    $i=1;
                    $id='';

                    foreach ($pending as $key => $value) {


                     ?>
                     <tr>

                        <?php 

                        if($value->id==$id){
                            continue;
                        }

                        $id=$value->id;

                        ?>
                        <td>{{$i++}}</td>
                        <td><?= 'M'.sprintf('%06d',$value->id) ?></td>
                        <td><?= date('d-m-Y',strtotime($value->created_at))?></td>

                        <td><a class="btn   btn-sm btn-primary label label-success" href="{{route('user.casedetails',$value->id)}}">View</a></td>

                        <td>
  
                            <?php

                        if(isset($value->party) and count($value->party)>0){

                        foreach ($value->party as $key => $v) {

                            if($v->isOnboarded==1){
                                echo '<span class="text-success">'.$v->name.'</span></br>';
                            } else{
                                echo '<span class="text-danger">'.$v->name.'</span></br>';
                            }
                            
                        }
                    } else { ?>


                        <a href="invoke?id=<?= $value->id ?>" class="btn btn-sm btn-danger">Pending</a>

                    <?php } ?>



                        </td>
                        <td>
                        <button  class="btn btn-sm btn-inline btn-danger label label-success" data-toggle="modal" data-target="#withdrawModal" data-id="<?= $value->id ?>" >Withdraw</button>
                        <br></td>
                        <td>Pending</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel">Withdraw</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="withdrawForm" method="post">
                <div class="modal-body">
                    <input type="hidden" name="case_id" class="form-control" >
                    @csrf

                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Withdraw Comment:</label>
                        <textarea class="form-control" name="withdraw_comment"  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Close Request</button>
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

    <script type="text/javascript">
        
     $(document).ready(function(){


        <?php if($response=='success'){ ?>

             swal("Success", "Form has been submitted", "success").then(function() {
    window.location ="{{route('user.newrequest')}}"

});

<?php } ?>

        

    //withdraw the case

            $('#withdrawForm').on('submit', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "Withdraw case!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'post',
                    url: '{{ route("user.case.withdraw") }}',
                    data: $('#withdrawForm').serialize(),
                    success: function (data) {


                        console.log(data);
                        // alert('form was submitted');
                        //userTable.ajax.reload();


                        swal("withdraw successfully!", {
                            icon: "success",
                        });
                        $('#withdrawModal').modal("hide");
                    },
                    error:function(err){

                        console.log(err);
                    }
                });
            } else {
                swal("Cancle Withdraw Request!");
            }
        });
        return false;
    });

            $('#withdrawModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('id');
        var modal = $(this)
        modal.find('.modal-body input[name="case_id"]').val(recipient);
    });

            });
    </script>



@endsection('footer')



























