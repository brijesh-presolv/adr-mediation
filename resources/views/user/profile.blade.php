@extends('user.layouts.app')
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
                                    <td colspan="4" class="bg-dark text-center text-white">User Profile</td>
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
                                {{-- <th scope="row"> <strong>Address : </strong></th>
                                <td><span class="ml-4">{{ $profileData->address }}</span></td>
                                    </tr>
                                <th scope="row"> <strong>Address Line 2 : </strong></th>
                            <td><span class="ml-4">{{ $profileData->address1 }}</span></td>
                            </tr>
                            <th scope="row"> <strong>City : </strong></th>
                            <td><span class="ml-4">{{ $profileData->city }}</span></td>
                            </tr>
                            <th scope="row"> <strong>State : </strong></th>
                            <td><span class="ml-4">{{ $profileData->state }}</span></td>
                            </tr>
                            <th scope="row"> <strong>Country : </strong></th>
                            <td><span class="ml-4">{{ $profileData->country }}</span></td>
                            </tr>
                            <th scope="row"> <strong>Pincode : </strong></th>
                            <td><span class="ml-4">{{ $profileData->pincode }}</span></td>
                            </tr> --}}
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

                        <form action = "{{ route('user.profile.update',['id'=> $profileData->id]) }}" method = "post">
                            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                            <input type = "hidden" name = "profileID" value = "{{ $profileData->id }}">

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title mt-0">User profile</h4>
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
                                                <a href="{{ route('user.change.password',['id'=> $profileData->id]) }}" class="btn-sm btn-warning px-2" >Change Password </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="field-4" class="control-label">Mobile Number : </label>
                                                <input type="text" name="mobile"  value="{{ $profileData->mobile_number }}"  class="form-control" id="field-4" >
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
                                                <label for="field-5" class="control-label">Address : </label>
                                                <input type="text" name="address"  value="{{ $profileData->address }}"  class="form-control" id="field-5">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="field-5" class="control-label">Address Line 2 : </label>
                                                <input type="text" name="address1"  value="{{ $profileData->address1 }}"  class="form-control" id="field-5">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="field-5" class="control-label">City : </label>
                                                <input type="text" name="city"  value="{{ $profileData->city }}"  class="form-control" id="field-5">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="state">@lang('user.state')</label>
                                                <select  class="form-control" id="state" name="state">
                                                    <option {{($user->state=="dolnośląskie")?"selected":"" }} >
                                                        dolnośląskie
                                                    </option>
                                                    <option {{($user->state=="kujawsko-pomorskie")?"selected":"" }}>
                                                        kujawsko-pomorskie
                                                    </option>
                                                    <option {{($user->state=="lubelskie")?"selected":"" }}>
                                                        lubelskie
                                                    </option>
                                                    <option {{($user->state=="lubuskie")?"selected":"" }}>
                                                        lubuskie
                                                    </option>
                                                    <option {{($user->state=="łódzkie")?"selected":"" }}>
                                                        łódzkie
                                                    </option>
                                                    <option {{($user->state=="małopolskie")?"selected":"" }}>
                                                        małopolskie
                                                    </option>
                                                    <option {{($user->state=="mazowieckie")?"selected":"" }}>
                                                        mazowieckie
                                                    </option>
                                                    <option {{($user->state=="opolskie")?"selected":"" }}>
                                                        opolskie
                                                    </option>
                                                    <option {{($user->state=="podkarpackie")?"selected":"" }}>
                                                        podkarpackie
                                                    </option>
                                                    <option {{($user->state=="podlaskie")?"selected":"" }}>
                                                        podlaskie
                                                    </option>
                                                    <option {{($user->state=="pomorskie")?"selected":"" }}>
                                                        pomorskie
                                                    </option>
                                                    <option {{($user->state=="śląskie")?"selected":"" }}>
                                                        śląskie
                                                    </option>
                                                    <option {{($user->state=="świętokrzyskie")?"selected":"" }}>
                                                        świętokrzyskie
                                                    </option>
                                                    <option {{($user->state=="warmińsko-mazurskie")?"selected":"" }}>
                                                        warmińsko-mazurskie
                                                    </option>
                                                    <option {{($user->state=="wielkopolskie")?"selected":"" }}>
                                                        wielkopolskie
                                                    </option>
                                                    <option {{($user->state=="zachodniopomorskie")?"selected":"" }}>
                                                        zachodniopomorskie
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="country">country</label>
                                                <select class="form-control" id="country" name="country">
                                                    <option>Polska</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="field-5" class="control-label">pincode : </label>
                                                <input type="text" name="pincode"  value="{{ $profileData->pincode }}"  class="form-control" id="field-5">
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

<!-- 
@section('footer')

@endsection -->























