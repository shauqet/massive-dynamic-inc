require('./bootstrap');

// Vue
import Vue from 'vue';

// Jquery

import $ from 'jquery';
window.$ = $;

// Vuex
import Vuex from 'vuex'
Vue.use(Vuex);
export const store = new Vuex.Store({
    state: {
        aboutUs: "",
        openCreateTextAndPhotoModal: false,
    },
    mutations: {
        setAboutUs(state, model) {
            state.aboutUs = model;
        }
    }
});

// Vue router and axios
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueRouter, VueAxios, axios);

// laravel Vue pagination
Vue.component('pagination', require('laravel-vue-pagination'));

// Sweetalert 2
import Swal from 'sweetalert2';
window.Swal = Swal;

// BootstrapVue
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';


// Custom directives
Vue.directive('tooltip', function(el, binding){
    $(el).tooltip({
        title: binding.value,
        placement: binding.arg,
        trigger: 'hover'
    })
});

/***** Components *****/
import App from './App.vue';
import HomeComponent from "./components/HomeComponent";
import UsersComponent from "./components/users/Index"

// Routes
const routes = [
    // CMS routes
    { path: '/admin', name: 'HomeComponent', component: HomeComponent },
    { path: '/admin/user', name: 'UsersComponent', component: UsersComponent },
];

// Router
const router = new VueRouter({
    routes,
    mode: 'history'
});

const app = new Vue({
    el: '#app',
    store,
    router,
    render(h) { return h(App) }
});
