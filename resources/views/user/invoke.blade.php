<?php

function isreadonly($rows) {

    if ($rows > 0 ) {
        echo "readonly";
    }
}

function isreadonlys($rows) {


    if ($rows!='') {
        echo "readonly";
    }
}
?>
@section('title', 'Invoke mediation')
@extends('user.layouts.app')


@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Invoke </a></li>
<!-- end page title -->
@endsection
@section('content')

<div class="card">
    <div class="card-body">



        <section>

            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
        </section>

        <form  method="post" enctype="multipart/form-data" action="" class="smkform">

            @csrf
            <section class="claimant">
                <div class="row">
                    <div class="col-md-12 ">
                        <h6>Initiating Party</h6>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Name<span style="color:red; ">*</span></label>
                            <input type="text" class="form-control" value="<?= $user->first_name . ' ' . $user->last_name ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Email <span style="color:red; ">*</span></label>
                            <input type="text"class="form-control" value="<?= $user->email ?>"readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Phone <span style="color:red; ">*</span></label>
                            <input type="text"class="form-control" value="<?= $user->mobile_number ?>"readonly >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address line 1 <span style="color:red; ">*</span></label>
                            <input type="text" name="useraddress" class="form-control" value="<?= ($user->address1 !='NULL') ? $user->address : ''; ?>" <?= isreadonlys($user->address) ?> required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address line 2</label>
                            <input type="text" name="useraddress1" class="form-control" value="<?= ($user->address1 !='NULL') ? $user->address1 : ''; ?>" <?= isreadonlys($user->address1) ?> >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City <span style="color:red; ">*</span></label>
                            <input type="text" name="usercity" class="form-control" value="<?= ($user->city !='NULL') ? $user->city : ''; ?>" <?= isreadonlys($user->city) ?> required data-smk-pattern="[a-zA-Z\s]{2,100}" data-smk-msg="Enter valid city">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Pincode <span style="color:red; ">*</span></label>
                            <input type="text" name="userpincode" class="form-control" value="<?= ($user->pincode !='NULL') ? $user->pincode : ''; ?>" <?= isreadonlys($user->pincode) ?> required data-smk-type="number" minlength="6" maxlength="6" data-smk-msg="Enter vaild pincode">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>State <span style="color:red; ">*</span></label>
                            <input type="text" name="userstate" class="form-control" value="<?= ($user->state !='NULL') ? $user->state : ''; ?>" <?= isreadonlys($user->state) ?> required data-smk-pattern="[a-zA-Z\s]{2,100}" data-smk-msg="Enter valid state">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Country <span style="color:red; ">*</span></label>
                            <input type="text" name="usercountry" class="form-control" value="<?= ($user->country !='NULL') ? $user->country : ''; ?>" <?= isreadonlys($user->country) ?> required data-smk-pattern="[a-zA-Z\s]{2,100}" data-smk-msg="Enter valid country">
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="row">
                    <div class="col-md-12 ">
                        <h6>Responding Party</h6>
                    </div>
                </div>
            </section>
            <section class="respondent">


                <?php
                $rows = count($InvoledUser);

                if ($rows == 0) {
                    $rowcount = ($medcase->noOfParties - 1);
                } else {
                    $rowcount = $rows;
                }

                for ($i = 0; $i < $rowcount; $i++) {
                    ?>


                    <div class="row">
                        <div class="col-md-12">
                            <h6>#<?= $i + 1 ?></h6>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Name<span style="color:red; ">*</span></label>
                                <input type="text" name="name[]" class="form-control" value="<?= isset($InvoledUser[$i]['name']) ? $InvoledUser[$i]['name'] : ''; ?>" <?= isreadonly($rows) ?>  required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email <span style="color:red; ">*</span></label>
                                <input type="email" name="email[]" class="form-control" value="<?= isset($InvoledUser[$i]['userEmail']) ? $InvoledUser[$i]['userEmail'] : ''; ?>" <?= isreadonly($rows) ?>  required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Phone <span style="color:red; ">*</span></label>
                                <input type="text" name="phone[]" class="form-control" value="<?= isset($InvoledUser[$i]['userPhone']) ? $InvoledUser[$i]['userPhone'] : ''; ?>" <?= isreadonly($rows) ?>  required data-smk-type="number" minlength="10" maxlength="10" data-smk-msg="Enter vaild phone number">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Address line 1 <span style="color:red; ">*</span></label>
                                <input name="add1[]" type="text" class="form-control" value="<?= isset($InvoledUser[$i]['address1']) ? $InvoledUser[$i]['address1'] : ''; ?>" <?= isreadonly($rows) ?>  required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Address line 2</label>
                                <input name="add2[]" type="text" class="form-control" value="<?= isset($InvoledUser[$i]['address2']) ? $InvoledUser[$i]['address2'] : ''; ?>" <?= isreadonly($rows) ?>  >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>City <span style="color:red; ">*</span></label>
                                <input name="city[]" type="text" class="form-control" value="<?= isset($InvoledUser[$i]['city']) ? $InvoledUser[$i]['city'] : ''; ?>" <?= isreadonly($rows) ?>  required data-smk-pattern="[a-zA-Z\s]{2,100}" data-smk-msg="Enter valid city">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pincode <span style="color:red; ">*</span></label>
                                <input name="pincode[]" type="text" class="form-control" value="<?= isset($InvoledUser[$i]['pincode']) ? $InvoledUser[$i]['pincode'] : ''; ?>" <?= isreadonly($rows) ?>  required data-smk-type="number" minlength="6" maxlength="6" data-smk-msg="Enter vaild pincode">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>State <span style="color:red; ">*</span></label>
                                <input name="state[]" type="text" class="form-control" value="<?= isset($InvoledUser[$i]['state']) ? $InvoledUser[$i]['state'] : ''; ?>" <?= isreadonly($rows) ?>  required data-smk-pattern="[a-zA-Z\s]{2,100}" data-smk-msg="Enter valid state">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Country <span style="color:red; ">*</span></label>
                                <input name="country[]" type="text" class="form-control" value="<?= isset($InvoledUser[$i]['country']) ? $InvoledUser[$i]['country'] : ''; ?>" <?= isreadonly($rows) ?>  required data-smk-pattern="[a-zA-Z\s]{2,100}" data-smk-msg="Enter valid country">
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </section>

            <?php if ($rowcount == 1 and 1 == 2) { ?>
                <section class="respondents">

                </section>
                <section>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-warning" id="addmore">Add</button>
                            <button class="btn btn-sm btn-danger" id="removeresp">Remove</button>

                        </div>
                    </div>
                </section>
            <?php } ?>
            <section>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                        <div class="form-group">
                            <label>Dispute details <span style="color:red; ">*</span></label>
                            <textarea class="form-control" rows="4" name="issue" required=""><?= $medcase->issue ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Supporting document</label><br>
                            <?php if ($rows == 0) { ?>
                                <input class="form-control dropify" type="file" name="document" data-allowed-file-extensions="pdf zip png jpg jpeg"  data-max-file-size="20M"></input>
                            <?php } else {
                                if ($medcase->documentPath != '') { ?>

                                    <a href="<?= public_path('mediation').'/'.$medcase->id.'/'.$medcase->documentPath ?>" class="btn btn-success btn-sm" >Supporting Document</a> 

                                <?php } else {
                                    echo "Not avalable";
                                }
                            } ?>
                            <!-- {{$errors->document}} -->
                        </div>
                    </div>
                </div>
            </section>
        </form>
        <section>
            <div class="row">
                <div class="col-md-12">
                            <button class="btn btn-success smksubmit">Invoke</button>
                        </div>
            </div>
        </section>
    </div>
</div>
@endsection


@section('head')
<link href="{{url('assets/')}}/css/smoke.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
<link href="{{url('assets/')}}/css/dropify.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

@endsection('head')

@section('footer')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script src="{{url('assets/')}}/js/smoke.js"></script>
<script src="{{url('assets/')}}/js/dropify.min.js"></script>



    <script type="text/javascript">
        
     $(document).ready(function(){


        //form validation

        $('.smksubmit').click(function(){

    if( $('.smkform').smkValidate() ){
        
        $('.smkform').submit();

   } 

});
});
</script>

@endsection('footer')



