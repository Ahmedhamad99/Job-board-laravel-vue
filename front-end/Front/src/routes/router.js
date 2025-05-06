import Bodycomponent from "@/components/Bodycomponent.vue";
import App from "@/App.vue";
import { createRouter,createWebHashHistory } from "vue-router";
import Slider from "@/components/Slider.vue";
import Header from "@/components/Header.vue";
import Studentdetalis from "@/components/Studentdetalis.vue";
import { popScopeId } from "vue";
const routes = [
    {path:'/',component:Header},
    {path:'/home',component:Slider},
    {path:'/body',component:Bodycomponent},
    {path:'/studentd/:id',component:Studentdetalis}
    

];
const Router = createRouter({history:createWebHashHistory(),routes});

export default Router;
