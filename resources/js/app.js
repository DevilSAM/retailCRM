/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
var _ = require('lodash');

require('./script');

require('bootstrap-select');
require('bootstrap-select/js/i18n/defaults-ru_RU');

import Spinner from 'vue-spinkit'

Vue.component('Spinner', Spinner);

const VueInputMask = require('vue-inputmask').default;
Vue.use(VueInputMask);

import VueImg from './v-img';
Vue.use(VueImg);


import VueFlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

Vue.use(VueFlatPickr);

const Russian = require("flatpickr/dist/l10n/ru.js").default.ru;
flatpickr.localize(Russian);


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


const VueUploadComponent = require('vue-upload-component');
Vue.component('file-upload', VueUploadComponent);

// подключаю все кастомные компоненты
Vue.component('order-component', require('./components/OrderComponent').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
