@extends('admin.layouts.app')
@section('title', 'Users')
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url("/")}}">Home</a></li>
    <li class="breadcrumb-item active">Users</li>
</ol>
@endsection
@section('content')
<!-- Info boxes -->
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Users</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

            </div>
        </div>
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@endsection
@section('head')
<link rel="stylesheet" href="{{url('/assert/admin/')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{url('/assert/admin/')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="{{url('/assert/admin/')}}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection
@section('footer')
<script src="{{url('/assert/admin/')}}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{url('/assert/admin/')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{url('/assert/admin/')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{url('/assert/admin/')}}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{url('/assert/admin/')}}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{url('/assert/admin/')}}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script>
('#users').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
});
</script>
@endsection