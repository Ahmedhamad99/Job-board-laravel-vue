import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
// import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
// import { library } from "@fortawesome/fontawesome-svg-core";
// import { faSearchengin } from "@fortawesome/free-brands-svg-icons";
import "../node_modules/bootstrap/dist/css/bootstrap.min.css";
import "../node_modules/bootstrap/dist/js/bootstrap.bundle.min";

// library.add(faSearchengin);

const app = createApp(App);
app.use(router);
// app.component("font-awesome-icon", FontAwesomeIcon);
app.mount("#app");
