import Vue from 'vue';
import InstantSearch from 'vue-instantsearch';
import App from './App.vue';
import store from './store';

Vue.config.productionTip = false;

Vue.use(InstantSearch);

new Vue({
  store,
  render: (h) => h(App),
}).$mount('#app');
