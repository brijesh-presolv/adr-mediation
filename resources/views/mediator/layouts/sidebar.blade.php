<div class="left-side-menu usersidebar">

                <div class="slimscroll-menu">
    
                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
    
                        <ul class="metismenu" id="side-menu">
    
                            <li class="menu-title">Navigation</li>
                            @if(Auth::user()->isDone==1 && Auth::user()->isActive==1)
                            <li>
                                <a  href="{{route('mediator.dashboard')}}" class="waves-effect waves-light">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    <span>  Dashboard  </span>
                                </a>
                            </li>
                            <li>
                                <a  href="{{url('mediator/new')}}"  class="waves-effect waves-light">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span>  New Request  </span>
                                </a>
                            </li>
                             
                            <li>
                                <a  href="{{url('mediator/ongoing')}}"  class="waves-effect waves-light">
                                    <i class="fab fa-delicious"></i>
                                    <span>  Ongoing  </span>
                                </a>
                            </li>
                             
                            <li>
                                <a  href="{{url('mediator/closed')}}"  class="waves-effect waves-light">
                                    <i class=" far fa-lightbulb"></i>
                                    <span>  Closed  </span>
                                </a>
                            </li>

                            <li>
                                <a  href="{{route('mediator.rejectCase')}}"  class="waves-effect waves-light">
                                    <i class=" fas fa-comment-slash"></i>
                                    <span>  Reject Case  </span>
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{url('mediator/profile')}}" class="waves-effect waves-light">
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