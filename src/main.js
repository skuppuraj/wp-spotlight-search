import { createApp } from "vue";
import App from "./App.vue";
import { createPinia } from "pinia";
import shadow from "vue-shadow-dom";

const myV3App = createApp(App);
myV3App.use(createPinia());
myV3App.use(shadow);
myV3App.mount("#wp-spotlight-search-content");
