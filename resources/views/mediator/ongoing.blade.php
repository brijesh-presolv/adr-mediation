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
    <h4 class="page-title">Ongoing Cases</h4>
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <table  id="datatable" id="users" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>Case Id</th>
                        <th>Party Details</th>
                        <th>Party Details</th>

                        <th>Commets</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>MD000200</td>
                        <td><button class="btn   btn-sm btn-primary label label-success " data-toggle="modal" data-target="#myModalcomment" onclick="arbcommentmodal('1243',1,'425')">Case details</td>
                        <td>Party 1 <br>
                            Party 2 <br>
                            Party 3 <br>
                        </td>
                        <td>
                            <button class="btn   btn-sm btn-primary label label-success " data-toggle="modal" data-target="#myModalcomment" onclick="arbcommentmodal('1243',1,'425')">Private</button>
                            <button class="btn  btn-sm  btn-success label label-success " data-toggle="modal" data-target="#myModalcomment" onclick="arbcommentmodal('1243',2,'a')">Shared</button>
                        </td>
                        <td><!-- <button class="btn btn-inline btn-primary label label-success arbacceptbtn1" data-toggle="modal" data-target="#myModal53" data-cid="1243" >Accept</button> -->

                        <a href="/arbitrator/disclosure?id=1243" class="btn btn-primary btn-sm">Agreement</a>
                        <br></td>
                    </tr>
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























