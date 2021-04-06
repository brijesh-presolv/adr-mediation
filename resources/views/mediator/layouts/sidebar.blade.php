<?php 
  


?>
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{url('/assert/admin/')}}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">{{ Auth::user()->first_name }} {{ Auth::user()->last_name}}</a>
        </div>
    </div>
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{url("/")}}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard                        
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{url("mediator/new")}}" class="nav-link">
                    <i class="nav-icon fa fa-caret-square-o-right"></i>
                    <p>
                        New Request                        
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{url("mediator/ongoing")}}" class="nav-link">
                    <i class="nav-icon fas fa fa-pencil-square-o"></i>
                    <p>
                        Ongoing                        
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{url("mediator/closed")}}" class="nav-link">
                    <i class="nav-icon fa fa-file-text-o"></i>
                    <p>
                        Closed                        
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{url("mediator/profile")}}" class="nav-link">
                    <i class="nav-icon fas fa fa-file-text-o"></i>
                    <p>
                        Profile                        
                    </p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>