import Profile from './components/Profile.vue';
import Posts from './components/Posts.vue';
import Users from './components/Users.vue';
import Developer from './components/Developer.vue';
import NotFound from './components/NotFound.vue';
import SignIn from './components/auth/signin.vue';
import SignUp from './components/auth/signup.vue';
import Dashboard from './components/Dashboard.vue';
import Post from './components/Post.vue';
import EditUser from './components/EditUser.vue'



export const routes = [
    { path: '', component: Dashboard , children : [
        { path: '', component: Posts },
        { path: '/profile', component: Profile },
        { path: '/developer', component: Developer },
        { path: '/users', component: Users },
        { path: '/post/:id', component: Post},
        {path: '/editUser', component: EditUser}
    ],
        beforeEnter: (to, from, next) => {

            if(localStorage.getItem('token')) {
                next()
            }else{
                next('/signin')
            }
        }
    },
    { path: '/signin', component: SignIn },
    { path: '/signup', component: SignUp },
    { path: '*', component: NotFound }
]
