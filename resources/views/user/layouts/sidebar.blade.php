<style type="text/css">
    
   #sidebar-menu>ul>li>a.active {

    background-color: #ffa600 !important;
   }
</style>

<div class="left-side-menu" style="background-color: #fdfdfd; " >

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul class="metismenu usersidebar" id="side-menu">


                <li class="menu-title">Navigation</li>

                <li>
                    <a  href="{{route('user.dashboard')}}" class="waves-effect waves-light" style="color: #575a65;" >
                        <i class="mdi mdi-view-dashboard"></i>
                        <span  style="color: #000000;"> <b>  Dashboard  </b></span>
                    </a>
                </li>
                <li>
                    <a  href="{{route('user.newrequest')}}"  class="waves-effect waves-light" style="color: #575a65;" >
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span  style="color: #000000;">  <b> Pending  </b></span>
                    </a>
                </li>
                 
                <li>
                    <a  href="{{route('user.ongoing')}}"  class="waves-effect waves-light" style="color: #575a65;" >
                        <i class="fab fa-delicious"></i>
                        <span  style="color: #000000;"> <b>  Ongoing  </b></span>
                    </a>
                </li>
                 
                <li>
                    <a  href="{{route('user.closed')}}"  class="waves-effect waves-light" style="color: #575a65;" >
                        <i class=" far fa-lightbulb"></i>
                        <span  style="color: #000000;"> <b>  Closed  </b></span>
                    </a>
                </li>
                <li>
                    <a  href="{{route('user.rejected')}}"  class="waves-effect waves-light" style="color: #575a65;" >
                        <i class="fas fa-bullseye"></i>
                        <span  style="color: #000000;"> <b>  Rejected  </b></span>
                    </a>
                </li>
                
                <li>
                    <a href="{{route('user.profile')}}" class="waves-effect waves-light" style="color: #575a65;" >
                        <i class=" fas fa-user-tie"></i>
                        <span  style="color: #000000;"> <b> Profile </b></span>
                    </a>
                </li>


            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
                <!-- Sidebar -left -->
    
            </div>