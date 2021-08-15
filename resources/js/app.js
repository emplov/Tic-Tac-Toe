require('./bootstrap');

window.Vue = require('vue').default;

// Register Vue Components
Vue.component('rooms', require('./Components/Rooms.vue').default);
Vue.component('game', require('./Components/Game.vue').default);

// Initialize Vue
const app = new Vue({
    el: '#app',
});
