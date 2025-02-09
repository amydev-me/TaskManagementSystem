import Vue from "vue";
import VueRouter from "vue-router";
import Login from "./components/LoginPage.vue";
import TaskList from "./components/TaskList.vue";

Vue.use(VueRouter);

const routes = [
  { path: "/login", component: Login },
  { path: "/", component: TaskList, meta: { requiresAuth: true }, },
];

const router = new VueRouter({
  mode: "history",
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token");
  const isAuthenticated = token !== null && token !== "undefined"; 

  if (to.matched.some((record) => record.meta.requiresAuth) && !isAuthenticated) {
    return next("/login"); // Redirect unauthenticated users
  }
  
  next(); // Proceed if authenticated
});


export default router;
