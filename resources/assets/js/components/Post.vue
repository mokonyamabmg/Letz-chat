<template>
    <div class="container">
        <div class="row justify-content-center container-align">
            <div class="col-sm-12 col-md-7 post-wrapper">
                <md-progress-spinner class="spinner d-flex justify-content-center" :md-diameter="100" :md-stroke="10" md-mode="indeterminate" v-if="isLoading" ></md-progress-spinner>
                     <md-card
                        class="post-card"
                        v-if="!isLoading"
                        md-with-hover
                    >
                        <md-card-header class="post-header">

                            <div class="post-notication-wrapper">
                                 <md-avatar>
                                    <img :src="getProfile(post)" alt="Avatar">
                                </md-avatar>
                                <div class="d-flex flex-column">
                                    <div><span class="text-bold pl-2">{{post.user.name}}</span></div>
                                    <div class="post-notication-wrapper">
                                    <span class="pl-2">3 h</span>
                                    <span class="pl-1"><i class="fas fa-clock"></i></span>
                                </div>
                                </div>
                            </div>

                            <div>
                                <div class="md-title post-title mt-1">
                                    <p>{{post.post.title}}</p>
                                </div>
                                <div class="md-subhead">{{post.post.content}}</div>
                            </div>
                        </md-card-header>

                        <md-card-media class="post-card-media">
                            <img
                                :src="getProfilePhoto(post.photo)"
                                class="post-image"
                                alt="People"
                                v-if="post.photo"
                            />
                        </md-card-media>

                        <md-card-content>
                            <div>
                                <div v-if="isAuthorized(post.user.id)">
                                <md-button class="md-primary" @click="deletePost(post.post.id)"><md-icon><i class="fas fa-trash post-btn-alter-icons"></i></md-icon>Delete</md-button>
                                </div>
                                <md-field>
                                    <label>Write a Comment</label>
                                    <md-textarea v-model="content" md-counter="80"></md-textarea>
                                </md-field>
                                <md-button class="md-raised md-primary form-control mb-4 btn-submit" @click="makeComment(post.post.id)">Comment</md-button>

                            <div v-for="comment in post.post.comments">
                                <div class="comment-container mt-2 px-1">
                                      <md-avatar>
                                        <img :src="getUserPhoto(post.user)" alt="Avatar">
                                        </md-avatar>
                                        <md-content class="md-accent">{{comment.content}}</md-content>
                                </div>
                                <div class="post-notication-wrapper move-up mt-1">
                                    <span class="pl-4">3 h</span>
                                    <span class="pl-1"><i class="fas fa-clock"></i></span>
                                </div>
                                </div>

                            </div>
                        </md-card-content>

                    </md-card>
            </div>

             <div class="col-md-12 col-lg-2">
                <app-activity-panel></app-activity-panel>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        mounted() {

        },
        data() {
            return {
                content: '',
                id: this.$route.params.id,
                isLoading: false,
                post: {}
            }
        },
        methods: {
        deletePost(id) {
            this.$store.dispatch("deletePost", { id: id });
            this.$router.push("/");
        },
            makeComment(id) {

            const commentData = {content: this.content, post_id: id}

            axios.post('/api/comments/', commentData)
            .then(data => {
                this.fetchData();
                this.content = '';
            })
            .catch(err => {
                console.log(err);
            })


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
        fetchData(){
            this.isLoading = true;

             this.$store.dispatch('fetchPosts');

            axios.get('/api/posts/' + this.id)
            .then(data => {
                const post = data.data;

                this.post = post;
                this.isLoading = false;
            })
            .catch(err => {
                console.log(err);
            })
        },
        getProfilePhoto(post) {

                if(post != null)
                {
                    let photo = "/img/post/" + post.path;
                    return photo;
                }else{

                }

        },
        getProfile(post)
        {
            return '/img/profile/' + post.user.photo;
        },
        getUserPhoto(user){
            return '/img/profile/' + user.photo;
        }
        },
        created() {
            this.fetchData();
            // this.$store.dispatch('autoAuthUser');
            //  this.$store.dispatch('fetchAthenticatedUser', {id: this.$store.getters.getUserId});
        },
         watch: {

            '$route' (to, from) {
                this.id = to.params.id;
            }

        }

    }
</script>

<style lang="scss" lang="">
    .post-wrapper{
        min-height: 100vh;
    }

    .post-card{
        margin: 20vh 0;
    }

    .md-content {
    width: 200px;
    min-height: 30px;
    display: inline-flex;
    align-items: center;
    background-color: #f0f2f5 !important;
    border-radius: 30px;
    color: #000 !important;

    }
    .like-badge {
         font-size: 20px;
    }, .badge{
       margin-left: -40px !important;
    }

    .move-up{
        margin-top: -10px;
    }

    .card-align{
        margin-top: 50px;
    }
    .post-btn-alter-icons{
        font-size: 1rem;
        color: #0f4979;
    }
    .md-primary{
        color: #0f4979;
    }
    .btn-submit{
    background-color: #0f4979 !important;
    color: #fff !important;
    }
</style>
