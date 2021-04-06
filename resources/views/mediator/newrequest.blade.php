@extends('mediator.layouts.app')
@section('title', 'New Request')
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url("/")}}">Home</a></li>
    <li class="breadcrumb-item active">New request</li>
</ol>
@endsection
@section('content')

<!-- /.row -->

<div class="row">
	<div class="col-md-12">
		<div class="card">
			
		<div class="card-body">
		<table class=" table table-bordered" style="width:100%">
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

                <button class="btn   btn-sm btn-primary label label-success " data-toggle="modal" data-target="#myModalcomment" onclick="arbcommentmodal('1243',1,'425')">Private

                </button>
                <button class="btn  btn-sm  btn-success label label-success " data-toggle="modal" data-target="#myModalcomment" onclick="arbcommentmodal('1243',2,'a')">Shared</button>

            </td>
                <td><!-- <button class="btn btn-inline btn-primary label label-success arbacceptbtn1" data-toggle="modal" data-target="#myModal53" data-cid="1243" >Accept</button> -->

                              <a href="/arbitrator/disclosure?id=1243" class="btn btn-primary btn-sm"> Accept</a>
                              <button onclick="rejectarbcase('1243')" class="btn btn-sm btn-inline btn-danger label label-success">Reject</button>
                                                            <br></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>Sr. No</th>
                <th>Case Id</th>
                <th>Party Details</th>
                <th>Commets</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
    </div>
    </div>
	</div>
</div>
@endsection
@section('head')
@endsection
@section('footer')
@endsection