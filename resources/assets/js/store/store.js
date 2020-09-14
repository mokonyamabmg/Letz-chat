import Vue from 'vue';
import Vuex from 'vuex';
import  {router}  from '../app';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        idToken: null,
        userId: null,
        authenticatedUser: {},
        posts: [],
        users: [],
        friends: [],
        post: {},
        mostCommentedPosts: []
    },
    mutations: {
        authUser(state, userData) {
            state.idToken = userData.token;
            state.userId = userData.userId;
        },
        storeUser(state, userData) {
            state.authenticatedUser = userData.userData
        },
        clearAuthData (state) {
            state.idToken = null;
            state.userId = null;
            localStorage.removeItem('expiresIn');
            localStorage.removeItem('userId');
            localStorage.removeItem('token');
            localStorage.removeItem('user');
        },
        posts(state, postsData) {
            state.posts = postsData;
        },
        setUsers(state, usersData) {
            state.users = usersData;
        },
        setFriends(state, friends) {
            state.friends = friends;
        },
        setPost(state, postData) {

            state.post = postData;
        },
        setTopPosts(state, mostCommented) {
            state.mostCommentedPosts = mostCommented;
        }
    },
    getters: {
        getUserId(state) {

            return state.userId;
        },
        getAuthenticatedUser(state)
        {
            return state.authenticatedUser;
        },
        getToken(state) {
            return state.idToken;
        },
        isAuthenticated(state) {
        return state.idToken !== null
        },
        getStorageToken(state) {
            return localStorage.getItem('token');
        },
        getPosts(state) {
            return state.posts;
        },
        getUsers(state) {
            return state.users;
        },
        getFriends(state){
            return state.friends;
        },
        getPost(state) {

            return state.post;
        },
        getMostCommentedPosts(state){

            return state.mostCommentedPosts;
        }
    },
    actions: {

        addFriend({commit}, payload) {

            axios.post('/api/addFriend', {user_id: payload.userId, friend_id: payload.friendId})
            .then(data => {

            })
            .catch(err => {
                console.log(err);
            })
        },
        fetchPost({commit}, payload) {

            axios.get('/api/posts/' + payload)
            .then(data => {
                const post = data.data;


                commit('setPost', post);
            })
            .catch(err => {
                console.log(err);
            })


        },
        getAllUsers({commit}) {

            axios.get('/api/user')
            .then(data => {

                const users = data.data.users;

                commit('setUsers', users);

            })
            .catch(err => {
                console.log(err);
            })
        },
        fetchFriends({commit}, payload) {
            axios.get('/api/friend/' + payload)
            .then(data => {

                const friends = data.data.friends;

                commit('setFriends', friends);
            })
            .catch(err => {
                console.log(err);
            })
        },
        deletePost({commit}, payload) {
            const id = payload.id;

            axios.delete('/api/posts/'+ id)
            .then(data => {

            })
            .catch(err => {
                console.log(err);
            })
        },
        fetchPosts ({commit}) {

            axios.get("/api/posts").then(data => {
                const postsData = data.data.postData;
                const mostCommented = data.data.mostCommented;

                commit('posts', postsData);
                commit('setTopPosts', mostCommented);
            });
        },
        setLogoutTimer ({commit}, expirationTime) {
            setTimeout(() => {
                commit('clearAuthData')
                router.push("/signin");
            }, expirationTime * 8000)
        },
        signUp({commit}, authData) {

            axios.post('api/signup', {
                email: authData.email,
                password: authData.password,
                name: authData.name,
                confirmPassword: authData.confirmPassword,
                terms: authData.terms,
                photo: authData.photo
            })
            .then(data => {
                console.log(data);
                commit('authUser', {
                    token: data.data.token,
                    userId: data.data.user.id
                });


                //store data in localstorage
                const now = new Date();
                const expirationDate = new Date(now.getTime() + data.data.expires * 1000);

                localStorage.setItem('token', data.data.token);
                localStorage.setItem('userId', data.data.user.id);
                localStorage.setItem('expiresIn', expirationDate);

                dispatch('setLogoutTimer', data.data.expires);

                router.push("/");

            })
            .catch( err => {

            })
        },
        login({commit, dispatch}, authData) {
            axios.post('api/signin', {
                email: authData.email,
                password: authData.password
            })
            .then(data => {
                console.log(data);
                commit('authUser', {
                    token: data.data.token,
                    userId: data.data.user.id
                });

                //store data in localstorage
                const now = new Date();
                const expirationDate = new Date(now.getTime() + data.data.expires * 1000);

                localStorage.setItem('token', data.data.token);
                localStorage.setItem('userId', data.data.user.id);
                localStorage.setItem('expiresIn', expirationDate);

                dispatch('setLogoutTimer', data.data.expires);

                router.push("/");
            })
            .catch(err => {
                console.log(err);
            })
        },
        autoAuthUser({commit}) {
            const token = localStorage.getItem('token');

            if(!token)
            {
                return;
            }

            const expirationDate = localStorage.getItem('expiresIn');
            const now = new Date();

            if(now >= expirationDate)
            {
                return;
            }
            const userId = localStorage.getItem('userId');

            commit('authUser', {
                token: token,
                userId: userId
            })
        },
        logout({commit}) {
            commit('clearAuthData');
            router.push("/signin");
        },
        fetchUser({commit, state}) {

            if(state.idToken)
            {
                axios.get('api/user/' + state.userId)
                .then(res => {

                    commit('storeUser', {userData: res.data.user});
                })
                .catch(err => {
                    console.log(err);
                })
            }
        },
        fetchAthenticatedUser({commit})
        {

            const userId = localStorage.getItem('userId');

            axios.get('/api/authUser/' + userId)
            .then(res => {


                commit('storeUser', {userData: res.data.user});
            })
            .catch(err => {
                console.log(err);
            })
        },
        likePost({commit}, state)
        {

            axios.post('api/likePost', {blogpost_id: state.blogpost_id, user_id: state.user_id})
            .then(res => {
                console.log(res);
            })
            .catch(res => {
                console.log(err);
            })
        }
    }
});
