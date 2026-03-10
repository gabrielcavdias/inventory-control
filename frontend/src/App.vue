<script setup lang="ts">
import { RouterLink, RouterView, useRoute, useRouter } from "vue-router";
import { useAppStore } from "@/stores/app";

import { onMounted, watch } from "vue";
import AppHeader from "./components/common/AppHeader.vue";
const appStore = useAppStore();
const router = useRouter();
const route = useRoute();
watch(
  () => appStore.isUserLoggedIn,
  () => {
    if (!appStore.isUserLoggedIn) {
      router.push("/login");
    }
  },
  { immediate: true },
);
</script>

<template>
  <header v-if="route.name !== 'login'">
    <AppHeader />
  </header>

  <RouterView />
</template>

<style scoped></style>
