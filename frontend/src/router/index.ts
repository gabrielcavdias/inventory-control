import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/sobre",
      name: "about",
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import("../views/AboutView.vue"),
    },
    {
      path: "/compras",
      name: "compras",
      component: () => import("../views/PurchasesView.vue"),
    },
    {
      path: "/compras/:id",
      name: "compras-exibir",
      component: () => import("../views/PurchaseShowView.vue"),
    },
    {
      path: "/compras/novo",
      name: "compras-criar",
      component: () => import("../views/PurchaseCreateView.vue"),
    },
    {
      path: "/vendas",
      name: "vendas",
      component: () => import("../views/SalesView.vue"),
    },
    {
      path: "/vendas/:id",
      name: "vendas-exibir",
      component: () => import("../views/SaleShowView.vue"),
    },
    {
      path: "/vendas/novo",
      name: "vendas-criar",
      component: () => import("../views/SaleCreateView.vue"),
    },
    {
      path: "/produtos",
      name: "produtos",
      component: () => import("../views/ProductsView.vue"),
    },
    {
      path: "/produtos/novo",
      name: "produtos-criar",
      component: () => import("../views/CreateProductView.vue"),
    },
    {
      path: "/login",
      name: "login",
      component: () => import("../views/LoginView.vue"),
    },
  ],
});

export default router;
