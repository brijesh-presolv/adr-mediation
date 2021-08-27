<?php
use App\Models\InvoledUser;
?>
@extends('user.layouts.app')
@section('title', 'Pending')

@section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('site.Home')</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('site.Pending') </a></li>
    <!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <!-- <h4 class="header-title"><b>New Request</b></h4> -->
            <table  id="datatable" id="" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>@lang('case.Sr. No')</th>
                        <th>@lang('case.case_id')</th>
                        <th>@lang('case.date')</th>
                        <th>@lang('case.case_details')</th>
                        <th>@lang('case.party_details')</th>
                        <th>@lang('case.status_logs')</th>
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

                        <td><a class="btn   btn-sm btn-primary label label-success {{(count($value->party)>0)?'':'disabled'}}" href="{{route('user.casedetails',$value->id)}}" >View</a></td>

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


                        <a href="invoke?id=<?= $value->id ?>" class="btn btn-danger btn-sm">@lang('site.Pending')</a>

                    <?php } ?>



                        </td>
                        <td><span class="badge badge-danger">@lang('site.Pending')</span></td>
                        </tr>
                    <?php } ?>
                </tbody>
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



























