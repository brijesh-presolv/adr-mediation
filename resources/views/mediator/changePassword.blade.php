@extends('mediator.layouts.app')
@section('title', 'Users')

@section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">New request </a></li>
    <!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <h4 class="header-title"><b>Change Your Password</b></h4>
        <div class="card-body">
                    <form method="POST" action="{{ route('mediator.profile.update',['id'=> $data->id]) }}">
                        <input type="hidden" name="an" value="cp">
                        @csrf 
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Current Password</label>
            
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="current_password" autocomplete="current-password" placeholder="Current Password" value="{{ old('password') }}">
                                @if($errors->has('current_password'))
                                    <div class="text-danger"><b>{{ $errors->first('current_password') }}</b></div>
                                @endif
                            </div>
                        </div>
  
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>
  
                            <div class="col-md-6">
                                <input id="new_password" type="password" class="form-control" name="new_password" autocomplete="current-password" placeholder="Enter New Password" >
                                 @if($errors->has('new_password'))
                                    <div class="text-danger"><b>{{ $errors->first('new_password') }}</b></div>
                                @endif
                            </div>
                        </div>
  
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">New Confirm Password</label>
    
                            <div class="col-md-6">
                                <input id="new_confirm_password" type="password" class="form-control" name="new_confirm_password" autocomplete="current-password" placeholder="Confirm your new password" >
                                 @if($errors->has('new_confirm_password'))
                                    <div class="text-danger"><b>{{ $errors->first('new_confirm_password') }}</b></div>
                                @endif
                            </div>
                        </div>
   
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4 text-center">
                                <button type="submit" class="btn-sm btn-primary">
                                    Update Password
                                </button>
                            </div>
                        </div>
                    </form>
            </div>
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
