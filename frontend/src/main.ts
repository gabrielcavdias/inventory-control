import "./assets/main.css";

import { createApp } from "vue";
import { createPinia } from "pinia";
import Axios from "axios";
import { client } from "laravel-precognition-vue";

import App from "./App.vue";
import router from "./router";

// @ts-ignore
window.axios = new Axios.create();
// @ts-ignore
(window.axios as Axios).interceptors.request.use(
  (config) => {
    const token = sessionStorage.getItem("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  },
);
// @ts-ignore
client.use(window.axios);
const app = createApp(App);

app.use(createPinia());
app.use(router);

app.mount("#app");
