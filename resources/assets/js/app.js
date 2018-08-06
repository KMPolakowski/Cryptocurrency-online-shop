/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


import VueRouter from 'vue-router'

import VueResource from 'vue-resource'

import VModal from 'vue-js-modal'



Vue.use(VModal, {
    dynamic: true,
    injectModalsContainer: true
})
Vue.use(VueRouter)
Vue.use(VueResource)


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('home', require('./components/Home.vue'));



let buy = require('./components/Buy.vue');
let home = require('./components/Home.vue');
let sell = require('./components/Sell.vue');
let wallet = require('./components/Wallet.vue');

Vue.http.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;


const routes = [{
        path: '/',
        component: home
    },
    {
        path: '/buy',
        component: buy
    },
    {
        path: '/sell',
        component: sell
    },
    {
        path: '/wallet',
        component: wallet
    }

]

const router = new VueRouter({
    mode: 'history',
    routes
})

const app = new Vue({
    el: '#app',
    components: {
        home,
        buy,
        sell,
        wallet
    },
    router

});

const app2 = new Vue({
    el: '#app2'

});