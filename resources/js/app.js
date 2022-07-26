require('./bootstrap');

// Vue
import Vue from 'vue';

// Jquery
import $ from 'jquery';
window.$ = $;

// Vuex
import Vuex from 'vuex'
Vue.use(Vuex);
export const store = new Vuex.Store({});

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
import ContactPersonsComponent from "./components/contact-persons/Index"
import DocumentsComponent from "./components/documents/Index"

// Routes
const routes = [
    { path: '/admin', name: 'HomeComponent', component: HomeComponent },
    { path: '/admin/user', name: 'UsersComponent', component: UsersComponent },
    { path: '/admin/contact-person/:id', name: 'ContactPersonsComponent', component: ContactPersonsComponent },
    { path: '/admin/document/:id', name: 'DocumentsComponent', component: DocumentsComponent },
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
