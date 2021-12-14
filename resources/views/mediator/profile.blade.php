@extends('mediator.layouts.app')
@section('title', 'Users')

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Profile </a></li>
<!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="text-center card-box shadow-none border border-secoundary">
                <div class="member-card">
                    @if(Session::has('key'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('key') }}</p>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered m-0">

                            <thead>
                                <tr>
                                    <td colspan="4" class="bg-dark text-center text-white">Mediator Profile</td>
                                </tr>
                            </thead>
                            <tbody class="text-left">
                                <tr>
                                    <td scope="row"><strong>Full Name :</strong></td>
                                    <td><span class="ml-4">{{ $profileData->first_name }} {{ $profileData->last_name }}</span></td>
                                </tr>
                                <tr>
                                    <th scope="row"> <strong>Email :</strong></th>
                                    <td><span class="ml-4">{{ $profileData->email }}</span></td>
                                </tr>
                                <tr>
                                    <th scope="row"> <strong>Username  :</strong></th>
                                    <td><span class="ml-4">{{ $profileData->username }}</span></td>
                                </tr>
                                <tr>
                                    <th scope="row"> <strong>Mobile : </strong></th>
                                    <td><span class="ml-4">{{ $profileData->mobile_number }}</span></td>
                                </tr>
                                <tr>
                                    <th scope="row"> <strong>Organization : </strong></th>
                                    <td><span class="ml-4">{{ $profileData->organization }}</span></td>
                                </tr>
                                <tr>
                                    <th scope="row"> <strong>Role : </strong></th>
                                    <td>
                                        @if($profileData->role==1)
                                        <span class="ml-4">Mediator</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- <a href="#custom-modal" class="btn btn-dark waves-effect waves-light mt-3" data-animation="blur" data-plugin="custommodal" data-overlaySpeed="100" data-overlayColor="#36404a" >Edit profile</a>
                    -->
                    <!-- Responsive modal -->
                    <button class="btn btn-dark waves-effect waves-light mt-3" data-toggle="modal" data-target="#custom-modal">Edit profile</button>
                    <!-- Accordion modal -->

                    <ul class="social-links list-inline mt-4">
                        <li class="list-inline-item">
                            <a  href="#" title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="Twitter"><i class="fab fa-twitter"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="Skype"><i class="fab fa-skype"></i></a>
                        </li>
                    </ul>

                </div>
                @if($errors->has('firstName'))
                <div class="text-danger"><b>{{ $errors->first('firstName') }}</b></div>
                @endif
                <!-- end card-box -->
                <!-- edite profile Modal -->      
                <div id="custom-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">

                        <form action = "{{ route('mediator.profile.update',['id'=> $profileData->id]) }}" method = "post">
                            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                            <input type = "hidden" name = "profileID" value = "{{ $profileData->id }}">

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title mt-0">Modal Content is Responsive</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-left">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="field-1" class="control-label">First Name : </label>
                                                <input type="text" name="firstName"  value="{{ $profileData->first_name }}"  class="form-control" id="field-1" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="field-2" class="control-label">Last name : </label>
                                                <input type="text" name="lastName"  value="{{ $profileData->last_name }}"  class="form-control" id="field-2" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="field-3" class="control-label">Email : </label>
                                                <input type="email" name="email"  value="{{ $profileData->email }}"  class="form-control" id="field-3" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="field-3" class="control-label">Username : </label>
                                                <input type="text" name="username"  value="{{ $profileData->username }}"  class="form-control" id="field-3" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="field-3" class="control-label">Change Your Password : </label>
                                                <a href="{{ route('mediator.change.password',['id'=> $profileData->id]) }}" class="btn-sm btn-warning px-2" >Change Password </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="field-4" class="control-label">Mobile Number : </label>
                                                <input type="text" name="mobile"  value="{{ $profileData->mobile_number }}" data-validation="required custom length" data-validation-length="8-15" data-validation-regexp="^([0-9\s+-]+)$"  class="form-control" id="field-4" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="field-5" class="control-label">Organization Name : </label>
                                                <input type="text" name="orgName"  value="{{ $profileData->organization }}"  class="form-control" id="field-5">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="field-5" class="control-label">Experience : </label>
                                                <input type="text" name="expName"  value="{{ $profileData->experience }}"  class="form-control" id="field-5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-info waves-effect waves-light">Save changes</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--end Modal -->      

            <!-- end col -->
        </div>
    </div>
</div>

@endsection

<!-- Table datatable css -->


@section('footer')
<script>
    $.validate();
</script>
@endsection























