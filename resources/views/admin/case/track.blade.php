@extends('admin.layouts.app')
@section('title', 'Track M'.sprintf('%06d',$id))


@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Track</a></li>
<!-- end page title -->
@endsection
@section('page_title', 'Track for Case ID: M'.sprintf('%06d',$id))

@section('content')

<?php
function mapEmail($d,$e){
    
    if($d['user1email']==$e){

    return ['type'=>'Claimant','name'=>$d['user1name'],'id'=>1];
} else if($d['user2email']==$e){

return ['type'=>'Respondent','name'=>$d['user2name'],'id'=>2];
} else if($d['arbemail']==$e){
  
  return ['type'=>'Arbitrator','name'=>$d['arbname'],'id'=>3];
} else{
  
  if($d['OtherEmail']!=''){

     if(in_array($e,explode(',',$d['OtherEmail']))){

     return ['type'=>'Other Respondent','name'=>$d['Other Respondent'],'id'=>2];
   }

   return ['type'=>'Claimant','name'=>$d['user1name'],'id'=>1];
}

  
}

}
?>

<div class="card">

    <div class="card-body">
        <h5>WhatsApp Track </h5>
        <section>
            <div class="row">
                <div class="col-md-12">
                    
                    <table  id="whatsappTrack" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Event Title</th>
                            <th>Event Description</th>
                            <th>Event Date</th>
                            <th>Message</th>
                            <th>Media</th>
                            
                            <th>Name</th>
                            <th>Mobile No.</th>
                            <th>Status</th>
                            <th>Date</th>

                        </tr>
                      </thead>
                      <tbody>
                          @foreach ($whatsapp as $key => $value)
                          <?php $date = new DateTime($value->created_at);?>
                          <?php $wldate = new DateTime($value->wldate); ?>
                          
                          <tr>
                              <td>{{$key + 1}}</td>
                              <td>{{$value->title}}</td>
                              <td>{{$value->whdescription}}</td>
                              <td>{{$date->format('d-m-Y H:i:s')}}</td>
                              <td>@if($value->content != "")
                                <button class="btn btn-primary viewmsg" data-toggle="modal" data-target="#myModal230"  data-msg="{{str_replace(['::',';;'],['‘','’'],$value->content)}}">View</button>
                              @endif
                              </td>
                            <td>
                                @if($value->media != "")
                                  {{-- {{$value->media}} --}}
                                  <a class="btn btn-primary" href="{{$value->media}}">View</a>
                                @endif
                            </td>
                              <td></td>
                              <td>{{$value->contact}}</td>
                              <td>{{ucfirst($value->wlstatus)}}</td>
                              <td>{{$wldate->format('d-m-Y H:i:s')}}</td>
                           </tr>
                              
                          @endforeach
                          

                       
                      </tbody>
                    </table>

                </div>
            </div>
        </section>

    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>Email Track </h5>

        <section>
            <div class="row">
                <div class="col-md-12">
                    
                    <table  id="emailTrack" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Event Title</th>
                            <th>Event Description</th>
                            <th>Event Date</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Date</th>

                        </tr>
                      </thead>
                      <tbody>
                        
                        @foreach($email as $key => $value)
                        {{-- {{dd($value)}} --}}
                        <?php $date = new DateTime($value->created_at);?>

                        <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$value->title}}</td>
                        <td>{{$value->description}}</td>
                        <td>{{$date->format('d-m-Y H:i:s')}}</td>
                        <td></td>
                        <td>{{$value->edemail}}</td>
                        <td>{{ucfirst($value->edevent)}}
                            @if($value->edevent=='click')
                            <br />
                            <span title='{{$value->url}}' style='cursor: pointer;color: green;'><b>Link</b></span>
                            @endif</td>
                        <td>{{date('d-m-Y H:i:s', $value->timestamp)}}</td>
                        </tr>
                        @endforeach
                       
                      </tbody>
                    </table>

                </div>
            </div>
        </section>

    </div>
</div>

<div id="myModal230" class="logmodal modal fade " role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Message</h4>

          <button type="button" class="close"  data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body msgBody">
              <span>

              </span>
        </div>
      </div>
  
    </div>
  </div>

{{-- <script type="text/javascript">
function xyz($) {
    $(document).ready( function() {



$('.viewmsg').on('click',function(){
    console.log($(this).data('msg'));


$('#myModal230 .modal-body span').empty();

$('#myModal230 .modal-body span').append( $(this).data('msg'));

});
})
}
</script> --}}


@endsection

@section('footer')
 <!-- Datatable plugin js -->
    <script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

 <!-- Datatables init -->
    <script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript">



        $(document).ready( function() {

        $('.viewmsg').on('click',function(){
            // console.log($(this).data('msg'));


        $('#myModal230 .modal-body span').empty();

        $('#myModal230 .modal-body span').append( $(this).data('msg'));



        });
        $('#whatsappTrack').DataTable();

        $('#emailTrack').DataTable();


        });
        
    </script>

@endsection