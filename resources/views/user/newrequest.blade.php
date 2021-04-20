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
                        <th>Party Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    $i=1;
                    $id='';

                    foreach ($pending as $key => $value) { ?>
                     <tr>

                        <?php 

                        if($value->caseid==$id){
                            continue;
                        }

                        $id=$value->caseid;

                        ?>
                        <td>{{$i++}}</td>
                        <td><?= 'M'.sprintf('%06d',$value->caseid) ?></td>
                        <td><?= date('d-m-Y',strtotime($value->date))?></td>

                        <td><?php

                        $invuser=InvoledUser::select('name','isOnboarded')->where(['userPlanid'=>$value->caseid])->get();

                        if(count($invuser)==0){ ?>

                            <a href="invoke?id=<?= $value->caseid ?>" class="btn btn-sm btn-danger">Pending</a>

                        <?php } 

                        foreach ($invuser as $key => $value) {

                            if($value->isOnboarded==1){
                                echo '<span class="text-success">'.$value->name.'</span></br>';
                            } else{
                                echo '<span class="text-danger">'.$value->name.'</span></br>';
                            }
                            
                        }



                        ?></td>
                        <td>
                        <button onclick="withdraw('1243')" class="btn btn-sm btn-inline btn-danger label label-success">Withdraw</button>
                        <br></td>
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

@endsection

<?php if($response=='success'){ ?>

@section('footer')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
    swal("Success", "Form has been submitted", "success").then(function() {
    window.location ="{{route('user.newrequest')}}"
});
</script>

@endsection('footer')

<?php } ?>





















