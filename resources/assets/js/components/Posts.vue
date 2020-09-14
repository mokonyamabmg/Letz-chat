<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-10 post-card-wrapper">
                <div class="post-card-container">
                <md-progress-spinner class="spinner d-flex justify-content-center" :md-diameter="100" :md-stroke="10" md-mode="indeterminate" v-if="isLoading"></md-progress-spinner>
                    <md-card
                        class="post-card"
                        md-with-hover
                         v-if="!isLoading"
                        v-for="post in arrPosts"
                    >
                        <md-card-header class="post-header">
                            <div class="d-flex justify-content-between post-header-icons">
                            <div class="post-notication-wrapper">
                                 <md-avatar>
                                    <img :src="getUserPhoto(post)" alt="Avatar">
                                </md-avatar>
                                <div class="d-flex flex-column">
                                    <div><span class="text-bold pl-2">{{ post.user.name }}</span></div>
                                    <div class="post-notication-wrapper">
                                    <span class="pl-2">{{ getDate(post.created.date) }}</span>
                                    <span class="pl-1"><i class="fas fa-clock"></i></span>
                                </div>
                                </div>
                                </div>
                                <div class="float-right d-flex flex-row" v-if="isAuthorized(post.user.id)">
                                    <div class="post-btn-alter" @click="deletePost(post.post_id)"><md-icon><i class="fas fa-trash post-btn-alter-icons"></i></md-icon></div>
                                </div>
                            </div>

                            <div>
                                <div class="md-title post-title mt-1">
                                    <p>{{ post.title }}</p>
                                </div>
                                <div class="md-subhead">{{ post.content }}</div>
                            </div>
                        </md-card-header>

                        <md-card-media class="post-card-media">
                            <img
                                :src="getProfilePhoto(post)"
                                class="posts-image"
                                alt="People"
                                v-if="post.photo"
                            />
                        </md-card-media>

                        <md-card-content>
                        <div class="post-notification-wrapper">
                        <div class="like-wrapper">
                        <span><i class="fab fa-gratipay pr-1"><i class="fas fa-grin-squint-tears"></i><i class="fas fa-grin-hearts"></i></i></span><span>{{post.likesCount}}</span>
                        </div>
                        <div><span>{{post.commentsCount}} Comments</span></div>
                        </div>
                        <div class="btn-group btn-tab-group mt-1" role="group">
                        <button @click="likePost({blogpost_id: post.post_id, user_id: post.user.id})" type="button" class="btn btn-tab" ><img src="/img/icons8-facebook-like-64.png"/><span class="pl-2">Like</span></button>
                        <router-link tag="button" :to="'/post/' + post.post_id" type="button" class="btn btn-tab"><img src="/img/icons8-topic-50.png"/><span class="pl-2">Comment</span></router-link>
                        </div>
                        </md-card-content>

                    </md-card>
                </div>
            </div>

            <div class="col-md-12 col-lg-2 activity-wrapper">
                <app-activity-panel></app-activity-panel>
            </div>
        </div>
    </div>
</template>

<script>
import actions from "vuex";

import moment from "moment";
 import EventBus from "../eventBus";

export default {
    // name: 'ProgressSpinnerIndeterminate'

    mounted() {

    },
    data() {
        return {
            isLoading: false,
            posts: [],
            userId: '',
            user: {},
        }
    },
    methods: {
        deletePost(id) {
            this.$store.dispatch("deletePost", { id: id });
            this.$store.dispatch('fetchPosts');
        },
        isAuthorized(id){

        const authUserId = this.$store.getters.getUserId;


            if(authUserId == id)
            {
                return true;
            }else
            {
                return false;
            }

        },
        getProfilePhoto(post) {

                let photo = "/img/post/" + post.photo;
                return photo;

        },
        likePost(postData)
        {
            this.$store.dispatch('likePost', {blogpost_id: postData.blogpost_id, user_id: postData.user_id});
            this.$store.dispatch('fetchPosts');

        },
        getUserPhoto(post){
            return '/img/profile/' + post.user.photo;
        },
         getDate(value)
        {
            let current = new Date();
            let postCreated = new Date(value);
            let diffMs = (current.getTime()  - postCreated.getTime());
            let durationYear =  moment().diff(value, 'years');
            let durationMonth =  moment().diff(value, 'months');
            let durationDays =  moment().diff(value, 'days');

            let durationHours =  Math.round(Math.abs(diffMs) / 36e5);
            // let durationMinutes =  moment().diff(value, 'minutes');
            let durationMinutes =  Math.floor(diffMs / 60000);
            let durationSeconds =  Math.abs(diffMs / 1000);

            if(durationMinutes < 1)
            {
                return 'Just Now';

            }else if(durationMinutes < 60)
            {
                return durationMinutes + ' min ago';
            }else if(durationHours < 24)
            {
                return durationHours + ' hrs ago'
            }else if(durationDays < 31)
            {
                return durationDays + ' day(s) ago'
            }else if(durationMonth < 12)
            {
                return durationMonth + ' month ago'
            }else
            {
                return durationYear + ' yrs ago'
            }


        }
    },
    created() {

        this.isLoading = true;
        this.userId = this.$store.getters.getUserId;

        this.$store.dispatch('fetchPosts');
    },
    computed: {
        arrPosts() {
            this.isLoading = false;
            this.posts = this.$store.getters.getPosts;
            return this.posts;
        }


    },
    watch: {
        posts: function(value) {
            this.posts = this.$store.getters.getPosts;
                return this.posts;
        }
    }
};
</script>

<style lang="scss" scoped>
        .activity-wrapper{
            height: 100vh;
        }
        .post-btn-alter-icons{
            font-size: 1rem;
            color: #0f4979 !important;
        }

        .post-btn-alter{
            width: 1.4rem !important;
        }
    .post-header{
        width: 100%;
    }

    .md-subhead{
        color: #000 !important;
    }

</style>
