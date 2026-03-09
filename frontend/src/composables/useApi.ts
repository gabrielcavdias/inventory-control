import { api_url } from "@/lib/utils";
import { useAppStore } from "@/stores/app";
import { useAxios } from "@vueuse/integrations/useAxios";
import { AxiosError } from "axios";
import { ref } from "vue";

export const useApi = <T>() => {
  const data = ref<T>();
  const isFetching = ref(false);
  // @ts-ignore
  const { execute } = useAxios(window.axios) as unknown as {
    execute: Function;
  };
  const appStore = useAppStore();
  const fetchData = async (path: string) => {
    isFetching.value = true;
    try {
      // @ts-ignore
      const response = await execute(api_url(path), window.axios);
      console.log(response.data.value);
      data.value = response.data.value;
    } catch (e) {
      if (!(e instanceof AxiosError)) return;
      if (e.response?.status == 401) {
        appStore.token = ""; // Força o logout
      }
    } finally {
      isFetching.value = false;
    }
  };

  return {
    data,
    isFetching,
    fetchData,
  };
};
