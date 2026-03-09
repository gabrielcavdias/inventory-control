<script setup lang="ts">
import { useAppStore } from "@/stores/app";
import IconLogo from "../icons/IconLogo.vue";
import IconMoon from "../icons/IconMoon.vue";
import IconSun from "../icons/IconSun.vue";

const routes = [
  {
    name: "Início",
    path: "/",
  },
  {
    name: "Produtos",
    path: "/produtos",
  },
  {
    name: "Compras",
    path: "/compras",
  },
  {
    name: "Vendas",
    path: "/vendas",
  },
  {
    name: "Sobre",
    path: "/sobre",
  },
];

const appStore = useAppStore();
const toggleTheme = () => {
  if (appStore.theme == "dark") {
    appStore.theme = "light";
    return;
  }
  appStore.theme = "dark";
};
</script>
<template>
  <header
    class="my-4 px-4 py-2 flex items-center justify-between border-b border-emerald-400"
  >
    <IconLogo />
    <nav class="w-full">
      <ul class="flex items-center justify-center gap-2 col-span-4">
        <li v-for="route in routes">
          <RouterLink
            :to="route.path"
            class="py-2 px-5 rounded-xl transition dark:hover:bg-emerald-800/50 hover:bg-emerald-200/50"
            active-class="text-emerald-600 dark:text-emerald-400 font-bold border-b-2 !rounded-none border-emerald-600"
          >
            {{ route.name }}
          </RouterLink>
        </li>
      </ul>
    </nav>
    <button @click="toggleTheme" class="cursor-pointer">
      <IconMoon class="size-6" v-if="appStore.theme !== 'dark'" />
      <IconSun class="size-6" v-else />
    </button>
  </header>
</template>
