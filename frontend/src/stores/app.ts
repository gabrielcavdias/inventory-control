import { defineStore } from "pinia";
import {
  useLocalStorage,
  usePreferredColorScheme,
  useSessionStorage,
} from "@vueuse/core";
import { computed, watch } from "vue";

export const useAppStore = defineStore("app", () => {
  const token = useSessionStorage<string | null>("token", null);
  const isUserLoggedIn = computed(
    () => token.value != null && token.value != "",
  );
  const theme = useLocalStorage("theme", usePreferredColorScheme().value);
  watch(
    theme,
    () => {
      const body = document.querySelector("body");
      if (theme.value == "dark") {
        body?.classList.contains("dark") ? null : body?.classList.add("dark");
        return;
      }
      if (!body?.classList.contains("dark")) return;
      body.classList.remove("dark");
    },
    { immediate: true },
  );
  return { token, isUserLoggedIn, theme };
});
