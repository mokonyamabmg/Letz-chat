
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import { routes } from './routes';
import { store } from './store/store';
import {eventBus} from './eventBus';
import VueRouter from 'vue-router';
import VueProgressBar from 'vue-progressbar';
import VueResource from 'vue-resource';
import LaravelValidator from 'vue-laravel-validator';
import vueDebounce from 'vue-debounce';
import Vuelidate from 'vuelidate';
import { progress } from './progressBar';
import { Form, HasError, AlertError } from 'vform';
import swal from 'sweetalert2';
import Gate from './Gate';
import { MdButton, MdContent, MdTabs, MdField, MdCard, MdCheckbox, MdBadge, MdProgress, MdAvatar} from 'vue-material/dist/components'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'




Vue.prototype.$gate = new Gate(window.user);

window.swal = swal;
window.EventBus = new Vue();


const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000
});


window.toast = toast;
window.Form = Form;


Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.use(VueProgressBar, progress);
Vue.use(MdButton)
Vue.use(MdContent)
Vue.use(MdTabs)
Vue.use(MdField)
Vue.use(MdCard)
Vue.use(MdCheckbox)
Vue.use(MdBadge)
Vue.use(MdProgress)
Vue.use(MdAvatar)
Vue.use(Vuelidate)
Vue.use(VueResource);
Vue.use(LaravelValidator);
Vue.use(vueDebounce)



Vue.component(
    'passport-clients',
    require('../../js/components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('../../js/components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('../../js/components/passport/PersonalAccessTokens.vue')
);

Vue.component(
    'app-header',
    require('./components/layouts/header.vue')
)

Vue.component(
    'not-found',
    require('./components/NotFound.vue')
)

Vue.component(
    'app-signin',
    require('./components/auth/signin.vue')
)

Vue.component(
    'app-signup',
    require('./components/auth/signup.vue')
)

Vue.component(
    'app-create',
    require('./components/Create.vue')
)

Vue.component(
    'app-activity-panel',
    require('./components/ActivityPanel.vue')
)

Vue.component(
    'pagination',
    require('laravel-vue-pagination')
)

Vue.component(
    'profile',
    require('./components/Profile.vue')
)

Vue.use(VueRouter)
Vue.use(require('vue-moment'));

export const router = new VueRouter({
    mode: 'history',
    routes
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));



const app = new Vue({
    el: '#app',
    router,
    store,
    data: {
    },

});


