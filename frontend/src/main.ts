import App from "./App.vue";
import store from "./store";
import { createApp } from "vue";
import Swal from "sweetalert2";
import { registerPlugins } from "@/plugins";
import Pusher from "pusher-js";
import router from "./router";
import Vue3IconPicker from "vue3-icon-picker";
import "vue3-icon-picker/dist/style.css";

declare global {
  interface Window {
    Pusher: any;
    echo: any;
  }
}
window.Pusher = new Pusher("99d346007d0f5fccc8a8", { cluster: "ap1" });

const app = createApp(App);
app.config.globalProperties.$swal = Swal;
registerPlugins(app);

app.use(store);
app.use(router);
app.use(Vue3IconPicker);
app.mount("#app");
