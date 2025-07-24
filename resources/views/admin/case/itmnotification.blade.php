@extends('admin.layouts.app')
@section('title', "ITM Notifications")

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Invitation To Mediate Notifications</a></li>
<!-- end page title -->
@endsection
@section('page_title', "ITM Notifications")

@section('content')
    <style>
        .my_switch {
            position: relative;
            left: 7%;
            width: 20px;
            height: 20px;
        }

        .mySwitchLabel {
            position: relative;
            left: 10%;
            bottom: 5px;
        }

        .select2-container--default .select2-selection--single {
            height: 35px;
            border: 1px solid #dee2e6;
        }
    </style>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <form id="batchSubmit">
                    <div class="form-group">
                        <label>Select the Batch in which ITM notification will be disabled</label>
                        <br>
                        <select name="batch" id="batchSelect" class="form-control">
                            <option value="" selected>Select Batch...</option>
                            @foreach ($batchName as $value)
                                <option value={{ $value->id }}>{{ $value->batch_name }}</option>
                            @endforeach
                        </select>
                    </div>

                   
                    <div class="form-group">
                        <label for="">Disable ITM notification</label>
                        <br>
                        <input class="form-check-input my_switch" type="checkbox" id="mySwitchIP" name="mySwitchIP">
                        <label for="" class="mySwitchLabel">Initiating Party(s)</label>

                        <br>
                        <input class="form-check-input my_switch" type="checkbox" id="mySwitchRP" name="mySwitchRP">
                        <label for="" class="mySwitchLabel">Responding Party(s)</label>

                        <br>
                        <input class="form-check-input my_switch" type="checkbox" id="mySwitchMed" name="mySwitchMed">
                        <label for="" class="mySwitchLabel">Mediator</label>
                    </div>

                    <input type="submit" id="submit" name="batchNotification"
                            class="btn btn-sm btn-primary mt-3">
                </form>
            </div>
        </div>
    </div>

@endsection

<!-- Table datatable css -->

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endsection

@section('footer')
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="{{ url('/') }}/assets/js/sweetalert.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $('#batchSubmit').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.case.batchNotificationStop') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function() {
                    swal({
                        title: 'Loading...',
                        showConfirmButton: false,
                        buttons: false,
                        allowOutsideClick: false,
                    });
                },
                success: (data) => {
                    //this.reset();
                    swal(data.message, {
                        icon: "success",
                    });
                    // $("#uploadSupportingDocsModal").modal("hide");
                },
                error: function(data) {
                    //alert(data.responseJSON.errors.files[0]);
                    console.log(data);
                }
            });
        });



        // On branch change get data 
        $('#batchSelect').on('change', function(){
            var batch_id = $(this).val();
           
            $.ajax({
                type: 'GET',
                url: '{{ route('admin.case.getDisableParty') }}',
                data: {
                    batch_id : batch_id
                },
                dataType: 'json',
                success: (data) => {
                   if(data.ip == 1) {
                    $("input[type='checkbox']").prop('checked', false); 
                    $('#mySwitchIP').prop("checked", "1");
                   } else if(data.rp == 1) {
                    $("input[type='checkbox']").prop('checked', false); 
                    $('#mySwitchRP').prop("checked", "1");
                   } else if(data.med == 1){
                    $("input[type='checkbox']").prop('checked', false); 
                    $('#mySwitchMed').prop("checked", "1");
                   }
                }
            });
        });
        // On branch change get data 


        $('#batchSelect').select2();
    });
</script>

@endsection























