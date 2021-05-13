<?php

function isreadonly($rows) {

    if ($rows > 0 ) {
        echo "readonly";
    }
}

function isreadonlys($rows) {


    if ($rows!='NULL') {
        echo "readonly";
    }
}
?>
@section('title', 'Update mediation')
@extends('admin.layouts.app')


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
                            <input type="text"class="form-control" value="<?= $user->mobile_number ?>"readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address line 1 <span style="color:red; ">*</span></label>
                            <input type="text" name="useraddress" class="form-control" value="<?= ($user->address1 !='NULL') ? $user->address : ''; ?>"  required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address line 2</label>
                            <input type="text" name="useraddress1" class="form-control" value="<?= ($user->address1 !='NULL') ? $user->address1 : ''; ?>"  >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City <span style="color:red; ">*</span></label>
                            <input type="text" name="usercity" class="form-control" value="<?= ($user->city !='NULL') ? $user->city : ''; ?>"  required data-smk-pattern="[a-zA-Z\s]{2,100}" data-smk-msg="Enter valid city">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Pincode <span style="color:red; ">*</span></label>
                            <input type="text" name="userpincode" class="form-control" value="<?= ($user->pincode !='NULL') ? $user->pincode : ''; ?>"  required data-smk-type="number" minlength="6" maxlength="6" data-smk-msg="Enter vaild pincode">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>State <span style="color:red; ">*</span></label>
                            <input type="text" name="userstate" class="form-control" value="<?= ($user->state !='NULL') ? $user->state : ''; ?>"  required="" data-smk-pattern="[a-zA-Z\s]{2,100}" data-smk-msg="Enter valid state">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Country <span style="color:red; ">*</span></label>
                            <input type="text" name="usercountry" class="form-control" value="<?= ($user->country !='NULL') ? $user->country : ''; ?>"  required data-smk-pattern="[a-zA-Z\s]{2,100}" data-smk-msg="Enter valid country">
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="row">
                    <div class="col-md-12 ">
                        <h6>Reponding Party</h6>
                    </div>
                </div>
            </section>
            


                <?php
                $rows = count($InvoledUser);

                if ($rows == 0) {
                    $rowcount = ($medcase->noOfParties - 1);
                } else {
                    $rowcount = $rows;
                }

                for ($i = 0; $i < $rowcount; $i++) {
                    ?>

                    <section class="respondent" id="{{ 'rowid'.($i+1)}}">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="respcount">#Respondent <?= $i + 1 ?></h6>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Name<span style="color:red; ">*</span></label>
                                <input type="text" name="name[]" class="form-control" value="<?= isset($InvoledUser[$i]['name']) ? $InvoledUser[$i]['name'] : ''; ?>"   required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email <span style="color:red; ">*</span></label>
                                <input type="email" name="email[]" class="form-control" value="<?= isset($InvoledUser[$i]['userEmail']) ? $InvoledUser[$i]['userEmail'] : ''; ?>"   required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Phone <span style="color:red; ">*</span></label>
                                <input type="text" name="phone[]" class="form-control" value="<?= isset($InvoledUser[$i]['userPhone']) ? $InvoledUser[$i]['userPhone'] : ''; ?>"   required data-smk-type="number" minlength="10" maxlength="10" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Address line 1 <span style="color:red; ">*</span></label>
                                <input name="add1[]" type="text" class="form-control" value="<?= isset($InvoledUser[$i]['address1']) ? $InvoledUser[$i]['address1'] : ''; ?>"   required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Address line 2</label>
                                <input name="add2[]" type="text" class="form-control" value="<?= isset($InvoledUser[$i]['address2']) ? $InvoledUser[$i]['address2'] : ''; ?>"   >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>City <span style="color:red; ">*</span></label>
                                <input name="city[]" type="text" class="form-control" value="<?= isset($InvoledUser[$i]['city']) ? $InvoledUser[$i]['city'] : ''; ?>"  required data-smk-pattern="[a-zA-Z\s]{2,100}" data-smk-msg="Enter valid city">
                            </div>
                        </div>
                    </div>
                    <div class="row lastsection">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pincode <span style="color:red; ">*</span></label>
                                <input name="pincode[]" type="text" class="form-control" value="<?= isset($InvoledUser[$i]['pincode']) ? $InvoledUser[$i]['pincode'] : ''; ?>" required data-smk-type="number" minlength="6" maxlength="6" data-smk-msg="Enter vaild pincode">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>State <span style="color:red; ">*</span></label>
                                <input name="state[]" type="text" class="form-control" value="<?= isset($InvoledUser[$i]['state']) ? $InvoledUser[$i]['state'] : ''; ?>"   required="" data-smk-pattern="[a-zA-Z\s]{2,100}" data-smk-msg="Enter valid state">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Country <span style="color:red; ">*</span></label>
                                <input name="country[]" type="text" class="form-control" value="<?= isset($InvoledUser[$i]['country']) ? $InvoledUser[$i]['country'] : ''; ?>"   required data-smk-pattern="[a-zA-Z\s]{2,100}" data-smk-msg="Enter valid country">
                                <input type="hidden" name="invid[]" class="form-control" value="{{$InvoledUser[$i]['id']}}" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn badge badge-danger removeresp"  data-id="{{ 'rowid'.($i+1)}}" data-ivid="{{$InvoledUser[$i]['id']}}">Remove</button>
                        </div>
                    </div>
                    </section>
                <?php } ?>
            

            <?php if (1 == 1) { ?>
                <section class="respondents">

                </section>
                <section>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <button class="btn btn-sm btn-warning" id="addmore">Add</button>
                            
                        </div>
                    </div>
                </section>
            <?php } ?>
            <section>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                        <div class="form-group">
                            <label>Disupute details <span style="color:red; ">*</span></label>
                            <textarea class="form-control" rows="4" name="issue" required=""><?= $medcase->issue ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Supporting document</label><br>
                            <?php if ($rows == 0) { ?>
                                <input class="form-control" type="file" name="document"></input>
                            <?php } else {
                                if ($medcase->documentPath != '') { ?>

                                    <a href="<?= public_path('mediation').'/'.$medcase->id.'/'.$medcase->documentPath ?>" class="btn badge badge-success" >Supporting Document</a> 

                                <?php } else {
                                    echo "Not avalable";
                                }
                            } ?>
                            <!-- {{$errors->document}} -->
                        </div>
                    </div>
                        
                </div>
            </section>
            <input type="hidden" name="rminv" value="" id="rminv">
        </form>

        <section>
            <div class="row">
                <div class="col-md-12">
                            <button class="btn btn-success smksubmit">Update</button>
                        </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('head')
<link href="{{url('assets/')}}/css/smoke.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
@endsection('head')

@section('footer')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="{{url('assets/')}}/js/smoke.js"></script>


    <script type="text/javascript">
        
     $(document).ready(function(){


        //form validation

        $('.smksubmit').click(function(){

    if( $('.smkform').smkValidate() ){
        
        $('.smkform').submit();

   } 

});


        <?php if($response=='success'){ ?>

             swal("Success", "Case has been updated", "success").then(function() {
    //window.location ="{{route('user.newrequest')}}"

    });


<?php } ?>


        var rowid={{count($InvoledUser)}};

        $(document).on('click','#addmore',function(e){

            rowid++;

            e.preventDefault();

            $('.removeresp').show();

            var resp=$('.respondent').first().clone();
            
            resp.attr('id','rowid'+rowid);
            resp.find('.respcount').text('#Respondent '+rowid);


           resp.find('.removeresp').attr('data-id','rowid'+rowid);

            resp.find('.form-control').val('');



            $('.respondents').append(resp);

        });

        $(document).on('click','.removeresp',function(e){

            e.preventDefault();

            rid='#'+$(this).data('id');

            

            $(rid).remove();

            if($(this).data('ivid')!=''){

                if($('#rminv').val()==''){
                var invvalue=$(this).data('ivid');
            } else{
                var invvalue=$('#rminv').val()+','+$(this).data('ivid');
            }

                $('#rminv').val(invvalue);
            }

            if(rowid>1){
            rowid--;
        }

        $('.respcount').each(function(k,v){

            $(this).text('#Respondent '+(k+1));
            
        });

        if($('.removeresp').length==1){
             
             $('.removeresp').hide();
        } else{
            $('.removeresp').show();
        }

       

        });

         if($('.removeresp').length==1){
             
             $('.removeresp').hide();
        } else{
            $('.removeresp').show();
        }
    });
</script>
@endsection('footer')




