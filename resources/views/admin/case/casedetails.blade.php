@section('title', 'Casedetails M'.sprintf('%06d',$case->id))
@extends('admin.layouts.app')


@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Casedetails </a></li>
<!-- end page title -->
@endsection
@section('content')

<div class="card">
    <div class="card-body">



        <section>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <td> Initiating party</td>
                            <td>
                                Name: {{ $case->party[0]->name}}<br>
                                Emai: {{ $case->party[0]->userEmail}}<br>   
                                Phone: {{ $case->party[0]->userPhone}}<br>
                                Address: <?= $case->party[0]->address1.' '.$case->party[0]->address2.' '.$case->party[0]->city.'-'.$case->party[0]->pincode.', '.$case->party[0]->state.' '.$case->party[0]->country    ?><br>  

                                    

                            </td>

                        </tr>
                        <tr>
                            <td> Responding party</td>
                            <td>

                                <?php 
                                $pcount=0;

                                foreach ($case->party as $key => $value) { 
                                    if($pcount>0){
             
                                    ?>

                                Name: {{ $value->name}}<br>
                                Emai: {{ $value->userEmail}}<br>    
                                Phone: {{ $value->userPhone}}<br>
                                Address: <?= $value->address1.' '.$value->address2.' '.$value->city.'-'.$value->pincode.', '.$value->state.' '.$value->country    ?><br><br>

                            <?php 
                        }
                        $pcount++;
                        } ?>
                                    

                            </td>
                        </tr>
                        <tr>
                            <td> Issue</td>
                            <td>{{$case->issue}}</td>
                        </tr>
                        <tr>
                            <td>Supporting Document (User)</td>
                            <td>
                                
                                <?php if($case->documentPath){ 

                                    $doc='storage/app/public/mediation/'.$case->id.'/'.$case->documentPath;
                                    ?>

                                    <a href="{{url($doc)}}" target="_blank">View</a>
                                <?php } else {?>


                                   Na

                                <?php } ?>
                            </td>
                        </tr>

                        <?php if($case->mfirstname){ ?>

                        <tr>
                            <td>Mediator</td>
                            <td>{{$case->mfirstname}}  {{$case->mlastname}}</td>
                        </tr>
                    <?php } ?>
                        <tr>
                            <td>Invitation to mediate</td>
                            <td>
                                
                                <?php 
                                if($case->invitation){ 

                                    $doc='storage/app/public/mediation/'.$case->id.'/'.$case->invitation->file_name;
                                    ?>

                                    <a href="{{url($doc)}}" target="_blank">View</a>
                                <?php } else {?>


                                   Na

                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                    <?php if(count($case->supporting_document)>0) {?>
                    <table class="table table-bordered">
                        <tr >
                            <th colspan="2">Supporting documents</th>
                        </tr>
                        
                            <tr>
                            <?php foreach ($case->supporting_document as $k => $v) { ?>

                            <td><?= basename($v->file_name)?></td>
                            
                            <td><a class="btn btn-sm btn-success" target="_blank" href="{{url('storage/app/'.$v->file_name)}}">view</a></td>
                        </tr>
                        <?php } ?>

                    
                    </table>
                    <?php } ?>
                    
                </div>
            </div>
        </section>

    </div>
</div>


@endsection
