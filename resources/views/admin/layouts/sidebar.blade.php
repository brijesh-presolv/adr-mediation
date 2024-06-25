<?php 
use App\Models\Notification;

$notification = Notification::where('view', 0)->count();
?>

<style>
    .notification-count {
        font-size: 0.7em;
        position: absolute;
        top: 0.2em;
        left: 2.5em;
    }
</style>

<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a  href="{{url('admin/dashboard')}}" class="waves-effect waves-light">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
                    </a>
                </li>
                <li>
                    <a  href="{{url('admin/notification')}}" class="waves-effect waves-light">
                        <i class="far fa-bell"> @if($notification > 0) <span class="badge badge-success notification-count"> {{$notification}} </span> @endif</i>
                        <span>  Notification  </span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Case</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{route('admin.case.newrequest')}}">New Request</a></li>
                        <li><a href="{{route('admin.case.ongoingrequest')}}">Ongoing </a></li>
                        <li><a href="{{route('admin.case.closedrequest')}}">Closed </a></li>
                        <li><a href="{{route('admin.case.rjectedrequest')}}">Rejected </a></li>
                    </ul>
                </li>
                <!---------- Batch wise notification for ITM : Start ------------>
                <li>
                    <a  href="{{url('admin/itmnotification')}}" class="waves-effect waves-light">
                        <i class="fa fa-toggle-on"> </i>
                        <span>ITM Notifications</span>
                    </a>
                </li>
                <!---------- Batch wise notification for ITM : End ------------>
                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="fas fa-user-friends"></i>
                        <span>Users</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{route('admin.users.list',"mediator")}}">Mediator</a></li>
                        <li><a href="{{route('admin.users.list',"user")}}">Users</a></li>
                        
                    </ul>
                </li>


            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>