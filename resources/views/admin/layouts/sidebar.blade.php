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
                    <a  href="{{route('admin.users.list',"mediator")}}"  class="waves-effect waves-light">
                        <i class="fas fa-user-friends"></i>
                        <span>  Users  </span>
                    </a>
                </li>
                <li>
                    <a  href="{{route('admin.users.list',"user")}}"  class="waves-effect waves-light">
                        <i class="fas fa-user-friends"></i>
                        <span>  Users  </span>
                    </a>
                </li>
                <li>
                    <a  href="{{route('admin.case.index',"newrequest")}}"  class="waves-effect waves-light">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span> New Request </span>
                    </a>
                </li>
                <li>
                    <a  href="{{route('admin.case.index',"confirmrequest")}}"  class="waves-effect waves-light">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span> Confirm Request </span>
                    </a>
                </li>
               


            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>