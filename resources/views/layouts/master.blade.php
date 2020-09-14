<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Letz Chat</title>

    <link href="/css/app.css" rel="stylesheet">
    <link rel="icon" href="./img/onlinechat.png">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">
          <!-- Left navbar links -->
          <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
              </li>
            </ul>
          <!-- SEARCH FORM -->
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" @keyup="searchit" v-model="search">
              <div class="input-group-append">
                <button class="btn btn-navbar" @click="searchit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            <div class="menu-items">
                <div class="px-2 d-flex justify-content-center">
                    <div id="profile-icon_container">
                    </div>
                   <div class="mt-1">
                    <span class="pl-1"><strong>Bestyn</strong></span>
                   </div>
                </div>
                <div class="px-2">
                    <button class="btn btn-primary create-post-btn" data-toggle="modal" data-target="#postModal"><span><i class="fas fa-edit"></i></span></button>
                </div>
                <div class="px-2">
                    <div class="profile-icons profile-icon-wrapper"><span><i class="fa fa-bell profile-icon"></i></span></div>
                </div>
                <div class="dropdown">
                    <div class="profile-icons profile-icon-wrapper dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </div>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('logout') }}"><span><i class="fas fa-sign-out-alt profile-icon"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        </i></span>Logout</a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    </div>
                </div>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
          <!-- Brand Logo -->
          <a href="index3.html" class="brand-link">
            <img src="./img/onlinechat.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">LetZ Chat</span>
          </a>

          <!-- Sidebar -->
          <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
                <img src="/img/people.png" class="img-circle elevation-2" alt="User Image">
              </div>
              <div class="info">
              <a href="#" class="d-block text-white">{{ Auth::user()->name}}</a>
              </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-5">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <router-link to="/dashboard" class="nav-link">
                            <i class="nav-icon fab fa-facebook-messenger white"></i>
                          <p>Dashboard</p>
                        </router-link>
                      </li>
                              <li class="nav-item">
                                <router-link to="/profile" class="nav-link">
                                    <i class="nav-icon fas fa-user-friends green"></i>
                                  <p>Friendz</p>
                                </router-link>
                              </li>
                              <li class="nav-item">
                                <router-link to="/profile" class="nav-link">
                                    <i class="nav-icon fas fa-users green"></i>
                                  <p>All Users</p>
                                </router-link>
                              </li>
                              <li class="nav-item">
                                <router-link to="/profile" class="nav-link">
                                    <i class="nav-icon fas fa-paper-plane white"></i>
                                  <p>Pages</p>
                                </router-link>
                              </li>
                              <li class="nav-item">
                                <router-link to="/profile" class="nav-link">
                                    <i class="nav-icon fas fa-calendar-day white"></i>
                                  <p>Events</p>
                                </router-link>
                              </li>
                              <li class="nav-item has-treeview menu-open mt-4">
                                <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-info-circle  green"></i>
                                <p>
                                  See More
                                  <i class="right fas fa-angle-left white"></i>
                                </p>
                                </a>
                                  <ul class="nav nav-treeview">
                                      <li class="nav-item">
                                          <router-link to="/users" class="nav-link active">
                                            <i class="nav-icon fas fa-cog orange"></i>
                                          <p class="text-white">Developers</p>
                                          </router-link>
                                      </li>
                                  </ul>
                            </li>
                          </ul>
            </nav>
            <!-- /.sidebar-menu -->
          </div>
          <!-- /.sidebar -->
        </aside>

        <div class="content-wrapper">
          <!-- /.content -->
          <div class="content">
          <div class="container-fluid">
              <div class="row bg-primary">
                  <div class="col-12">
                        <router-view></router-view>
                        <vue-progress-bar></vue-progress-bar>

                        <app-create></app-create>
                    </div>
                  </div>
              </div>
          </div>
        </div>
    </div>
        </div>

        @auth
            <script>
                window.user = @json(auth()->user())
            </script>
        @endauth
        <script src="/js/app.js"></script>
        <script src="/js/bootstrap.js"></script>
</body>
</html>
