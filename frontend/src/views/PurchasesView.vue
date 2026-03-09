<script setup lang="ts">
import LoadingScreen from "@/components/common/LoadingScreen.vue";
import { useApi } from "@/composables/useApi";
import { money } from "@/lib/utils";
import type { PaginatedResponse } from "@/models/api";
import type { Product } from "@/models/product";
import type { Purchase } from "@/models/transactions";
import { useAppStore } from "@/stores/app";
import { onMounted, ref } from "vue";

// @ts-ignore
const {
  data: purchases,
  isFetching,
  fetchData,
} = useApi<PaginatedResponse<Purchase>>();
const appStore = useAppStore();

const fetchPurchases = async (page: number = 1, perPage: number = 10) => {
  const params = new URLSearchParams();
  params.set("page", page.toString());
  params.set("per_page", perPage.toString());
  // @ts-ignore
  await fetchData("/compras" + "?" + params.toString());
};

const changePage = (n: number) => {
  fetchPurchases((purchases.value?.meta.current_page ?? 0) + n);
};
onMounted(() => {
  fetchPurchases();
});
</script>
<template>
  <h1
    class="text-center text-2xl font-bold mb-6 text-emerald-600 dark:text-emerald-400"
  >
    Compras
  </h1>
  <main class="max-w-7xl mx-auto">
    <div class="flex justify-end mb-8">
      <RouterLink
        to="/compras/novo"
        class="bg-emerald-600 hover:bg-emerald-500 rounded-lg text-center p-4 text-gray-200"
      >
        Adicionar Compra
      </RouterLink>
    </div>
    <div class="shadow-sm rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="text-gray-700 dark:text-gray-300">
          <tr>
            <th
              class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider"
            >
              Fornecedor
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider"
            >
              Custo Total
            </th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-200 text-gray-200">
          <tr v-for="compra in purchases?.data">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              {{ compra.fornecedor }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              {{ money(compra.custo_total) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="my-6 flex justify-between">
      <button
        @click="changePage(-1)"
        v-if="purchases?.meta.current_page !== 1"
        class="flex items-center gap-2 text-gray-400 hover:text-gray-100 cursor-pointer"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="size-6"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M15.75 19.5 8.25 12l7.5-7.5"
          />
        </svg>
        Anterior
      </button>
      <button
        @click="changePage(1)"
        v-if="
          (purchases?.meta?.current_page ?? 0) <
          (purchases?.meta?.last_page ?? 0)
        "
        class="ml-auto flex items-center gap-2 text-gray-400 hover:text-gray-100 cursor-pointer"
      >
        Próximo
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="size-6 rotate-180"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M15.75 19.5 8.25 12l7.5-7.5"
          />
        </svg>
      </button>
    </div>
  </main>
  <!-- <LoadingScreen v-if="isFetching" /> -->
</template>
