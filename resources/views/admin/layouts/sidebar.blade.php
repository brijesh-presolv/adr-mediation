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
                                <a  href="{{url('admin/new')}}"  class="waves-effect waves-light">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span>  New Request  </span>
                                </a>
                            </li>
                             
                            <li>
                                <a  href="{{url('admin/ongoing')}}"  class="waves-effect waves-light">
                                    <i class="fab fa-delicious"></i>
                                    <span>  Ongoing  </span>
                                </a>
                            </li>
                             
                            <li>
                                <a  href="{{url('admin/closed')}}"  class="waves-effect waves-light">
                                    <i class=" far fa-lightbulb"></i>
                                    <span>  Closed  </span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="{{url('admin/profile')}}" class="waves-effect waves-light">
                                    <i class=" fas fa-user-tie"></i>
                                    <span> Profile </span>
                                </a>
                            </li>
    
    
                        </ul>
    
                    </div>
                    <!-- End Sidebar -->
    
                    <div class="clearfix"></div>
    
                </div>
                <!-- Sidebar -left -->
    
            </div>