<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">


                   <div class="container">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <div class="card card-widget widget-user">
                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                    <div class="widget-user-header text-white">
                                        <h3 class="widget-user-username">dgdfgdfgd</h3>
                                        <h5 class="widget-user-desc">dfgdfgdfgdgdf</h5>
                                    </div>
                                    <div class="widget-user-image">
                                        <img class="img-circle mt-4" src="/img/friends.jpg" alt="User Avatar">
                                    </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-sm-4 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header">3,200</h5>
                                                <span class="description-text">Mutual Friends</span>
                                            </div>
                                            <!-- /.description-block -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header">13,000</h5>
                                                <span class="description-text">Friends</span>
                                            </div>
                                        <!-- /.description-block -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4">
                                            <div class="description-block">
                                                <h5 class="description-header">35</h5>
                                                <span class="description-text">Request</span>
                                            </div>
                                        <!-- /.description-block -->
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                <!-- /.row -->
                                </div>
                            </div>
                         </div>

                                     <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab" @click="getFriends">Friendz</a></li>
                        <li class="nav-item"><a class="nav-link active show" href="#settings" data-toggle="tab"  @click="getAllUsers">All Users</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane" id="activity">
                                  <div class="row">
                                    <div class="col-sm-12 col-md-6 px-2" v-for="user of friends">
                                        <md-card class="mt-2">
                                        <md-card-content class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <img class="friend-img" src="/img/person.png"/>
                                                </div>
                                                <div class="friend-name px-2">{{ user.name }}</div>
                                                <div class="d-flex flex-column justify-content-center px-1">
                                                    <md-button class="md-raised md-primary friend-btn ml-2">Delete Friend</md-button>
                                                </div>
                                        </md-card-content>
                                    </md-card>
                                    </div>
                                </div>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">

                                <div class="row">
                                    <div class="col-sm-12 col-md-6 px-2" v-for="user of users">
                                        <md-card class="mt-2">
                                        <md-card-content class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <img class="friend-img" src="/img/person.png"/>
                                                </div>
                                                <div class="friend-name px-2">{{ user.name }}</div>
                                                <div class="d-flex flex-column justify-content-center px-1">
                                                    <md-button class="md-raised md-primary friend-btn" v-if="!isFriend(user.id)" @click="addFriend(user.id)">Add Friend</md-button>
                                                </div>
                                        </md-card-content>
                                    </md-card>
                                    </div>
                                </div>
                            </div>
                        <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
          </div>

                        </div>
                    </div>

            </div>
        </div>
    </div>
</template>

<script>


    export default {

        data() {
            return {
                arrUsers: [],
                user_id: '',
                allUsers: [],
                allFriends: []
            }
        },
        methods: {
            getFriends() {
                this.user_id = this.$store.getters.getUserId;
                this.$store.dispatch('fetchFriends', this.user_id);
            },
            getAllUsers() {
                this.getFriends();
                this.$store.dispatch('getAllUsers');
            },
            isFriend(id)
            {

                let isFound = false;

                this.allFriends.map(user => {
                    if(user.id == id)
                    {
                        isFound = true;
                    }
                });

                return isFound;
            },
            addFriend(id)
            {
                this.$store.dispatch('addFriend', {friendId: id, userId: this.user_id});
                this.getAllUsers();
            }

        },
        mounted() {

        },
        created() {

            this.getAllUsers();
        },
        computed: {
            users() {

                let users;
                let updatedsers;
                let authUser = authUser = this.$store.getters.getUserId;

                users = this.$store.getters.getUsers;
                updatedsers = users;

                users.forEach((user, index) => {

                    if(user.id == authUser)
                    {
                        updatedsers.splice(index, 1);
                    }
                });


                this.allUsers = updatedsers;
                this.allFriends = this.$store.getters.getFriends;
                return updatedsers;
            },
             friends() {

                let users;
                let updatedsers;
                let authUser = authUser = this.$store.getters.getUserId;

                users = this.$store.getters.getFriends;
                updatedsers = users;

                users.forEach((user, index) => {

                    if(user.id == authUser)
                    {
                        updatedsers.splice(index, 1);
                    }
                });


                this.allFriends = updatedsers;
                return updatedsers;
            }
        }
    }
</script>

<style>
.widget-user-header{
    background-position: center center;
    background-size: cover;
    background-image: linear-gradient(to bottom right, rgba(245, 246, 252, 0.52), rgba(117, 19, 93, 0.73)), url('../../../../public/img/unnamed.jpg');
    height: 250px !important;
}
.widget-user .card-footer{
    padding: 0;
}

.friend-btn{
width: 7rem;
height: 2rem;
background-color: #0f4979 !important;
color: #fff !important;
}

.friend-img{
    width: 4rem;
    height: 4rem;
    border: 1px solid grey;
    border-radius: 0.5rem;
}

.friend-name{
    font-size: 1.3rem;
}
</style>
