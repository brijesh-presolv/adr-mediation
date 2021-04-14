<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a  href="{{url('/')}}" class="waves-effect waves-light">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>  Dashboard  </span>
                    </a>
                </li>
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
                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Case</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{route('admin.case.index',"newrequest")}}">New Request</a></li>
                        <li><a href="{{route('admin.case.index',"confirmrequest")}}">Confirm Request</a></li>
                    </ul>
                </li>


            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>