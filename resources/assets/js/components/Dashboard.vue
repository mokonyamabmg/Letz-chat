<template>
    <div>
                <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">
          <!-- Left navbar links -->
          <ul class="navbar-nav">
                     <md-button class="md-fab md-mini menu-burger" data-widget="pushmenu">
                    <md-icon><i class="fas fa-bars"></i></md-icon>
                    </md-button>
            </ul>
          <!-- SEARCH FORM -->
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" v-model="search" @keyup="searchit" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" @click="searchit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            <div class="menu-items">
                <router-link  tag="div" to="editUser" class="px-2 d-flex justify-content-center profileName">
                     <md-avatar>
                        <img :src="getUserPhoto()" alt="Avatar">
                    </md-avatar>
                   <div class="mt-1">
                    <span class="pl-1"><strong>{{userData.name}}</strong></span>
                   </div>
                </router-link>
                <div class="px-2">
                     <md-button class="md-fab md-plain button-create" data-toggle="modal" data-target="#postModal">
                        <md-icon><i class="fas fa-edit"></i></md-icon>
                    </md-button>
                </div>
                <div class="px-2">
                  <md-badge md-content="1">
                        <md-button class="md-icon-button md-raised">
                        <md-icon><i class="fa fa-bell profile-icon"></i></md-icon>
                        </md-button>
                    </md-badge>
                </div>
                <div class="dropdown">
                    <md-button class="md-icon-button md-raised dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <md-icon><i class="fas fa-caret-down"></i></md-icon>
                    </md-button>
                    <div class="dropdown-menu dropdown-menu-right px-2 py-3" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item">
                    <router-link  tag="div" to="editUser" class="d-flex justify-content-center profileName mt-4">
                     <md-avatar>
                        <img :src="getUserPhoto()" alt="Avatar">
                    </md-avatar>
                </router-link>
                    </a>
                     <hr>
                    <a class="dropdown-item mt-1 py-3" @click="onLogout">
                    <i class="fas fa-sign-out-alt profile-icon pr-2"></i>
                    <span>Logout</span></a>
                    </div>
                </div>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
          <!-- Brand Logo -->
          <a href="index3.html" class="brand-link">
            <img src="./../../../../public/img/onlinechat.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">LetZ Chat</span>
          </a>

          <!-- Sidebar -->
          <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
                 <md-avatar>
                        <img :src="getUserPhoto()" alt="Avatar">
                    </md-avatar>
              </div>
              <div class="info">
              <a href="#" class="d-block text-white">{{userData.name}}</a>
              </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-5">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link">
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
              <div class="row">
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
</template>

<script>
    import { mapActions } from 'vuex';
    import EventBus from "../eventBus";

    export default {
        mounted() {
        },
        data() {
            return {
                user: {},
                userId: '',
                token: '',
                 search: ''
            }
        },
        methods: {
            onLogout() {
            this.$store.dispatch('logout');
        },
        getUserPhoto(){
            return '/img/profile/' + this.userData.photo;
        },
         searchit() {
            //  Fire.$emit('searching');
            EventBus.$emit("searching", this.search);
         }
        },
        computed: {
            userData(){
                this.user = this.$store.getters.getAuthenticatedUser;
                return this.user;
            }
        },
        watch: {
            user: function(value) {
                this.user = this.$store.getters.getAuthenticatedUser;
                return this.user;
            }

        },
        created() {
             this.$store.dispatch('autoAuthUser');
             this.$store.dispatch('fetchAthenticatedUser');

        }
    }
</script>

<style lang="scss" scoped>
    .dropdown-menu{
        background-color: #fff;
        margin-top: 1rem;
        width: 16rem;
        border-radius: 1rem;
    }
    .dropdown-menu .profile-icon{
        font-size: 1rem;
        color: #66686c;    }
     .dropdown-menu span{
        font-size: 1rem;
        color: #66686c;
    }

    .dropdown-menu a{
        cursor: pointer;
    }

    .dropdown-menu a:active, .dropdown-menu a:focus{
        background-color: transparent;
    }
    .dropdown-menu hr{
        width: 100%;
    }
    .router-link-active, .router-link-exact-active{
        background-color: transparent !important;
    }

    .profileName{
        color: grey;
        cursor: pointer;
    }
</style>
