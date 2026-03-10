<script lang="ts" setup>
import LoadingScreen from "@/components/common/LoadingScreen.vue";
import IconBox from "@/components/icons/IconBox.vue";
import IconClose from "@/components/icons/IconClose.vue";
import IconMoney from "@/components/icons/IconMoney.vue";
import IconUser from "@/components/icons/IconUser.vue";
import { useApi } from "@/composables/useApi";
import { api_url, date, money } from "@/lib/utils";
import type { Purchase, Sale } from "@/models/transactions";
import { useAxios } from "@vueuse/integrations/useAxios";
import { onMounted, computed, useTemplateRef } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const { data: sale, isFetching, fetchData } = useApi<Sale>();

const dialogRef = useTemplateRef<HTMLDialogElement>("dialogRef");

const totalItems = computed(() => {
  if (!sale.value?.produtos) return 0;
  return sale.value.produtos.reduce(
    (acc, produto) => acc + produto.quantidade,
    0,
  );
});

const openConfirModal = () => {
  if (!dialogRef.value) return;

  dialogRef.value.showModal();
};

const cancelSale = async () => {
  await useAxios(
    api_url(`/vendas/${route.params.id}`),
    { method: "DELETE" },
    // @ts-ignore
    window.axios,
  );
  await fetchData(`/vendas/${route.params.id}`);
  dialogRef.value?.close();
};

onMounted(async () => {
  await fetchData(`/vendas/${route.params.id}`);
});
</script>

<template>
  <div class="max-w-7xl mx-auto p-4">
    <div class="mb-8">
      <h1
        class="text-center text-3xl font-bold text-emerald-600 dark:text-emerald-400"
      >
        Venda #{{ sale?.id }}
      </h1>
      <time
        v-if="sale?.cancelada"
        :datetime="new Date().toISOString()"
        class="block text-red-500 text-xl italic text-center my-4"
      >
        Venda cancelada em
        {{ date(sale?.cancelada) }}
      </time>
      <div v-else class="my-4 flex justify-center">
        <button
          @click="openConfirModal"
          class="bg-red-500 hover:bg-red-400 rounded-lg text-center px-6 py-3 text-gray-200 cursor-pointer"
        >
          Cancelar
        </button>
      </div>

      <p class="text-center text-gray-600 dark:text-gray-400 mt-2">
        Realizada para o cliente "{{ sale?.cliente }}"
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-emerald-500"
      >
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-emerald-100 dark:bg-emerald-900 mr-4">
            <IconUser class="size-6 text-emerald-600 dark:text-emerald-400" />
          </div>
          <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Fornecedor</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">
              {{ sale?.cliente }}
            </p>
          </div>
        </div>
      </div>

      <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-emerald-500"
      >
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-emerald-100 dark:bg-emerald-900 mr-4">
            <IconMoney class="size-6 text-emerald-600 dark:text-emerald-400" />
          </div>
          <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Lucro Estimado
            </p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ money(sale?.lucro || 0) }}
            </p>
          </div>
        </div>
      </div>

      <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-emerald-500"
      >
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-emerald-100 dark:bg-emerald-900 mr-4">
            <IconBox class="size-6 text-emerald-600 dark:text-emerald-400" />
          </div>
          <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Total de Itens
            </p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ totalItems }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabela de Produtos -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
          Produtos da Compra
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
          {{ sale?.produtos?.length || 0 }} produtos no total
        </p>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
              >
                ID
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
              >
                Produto
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
              >
                Quantidade
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
              >
                Preço Unitário
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
              >
                Subtotal
              </th>
            </tr>
          </thead>
          <tbody
            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
          >
            <tr v-for="produto in sale?.produtos" :key="produto.id">
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
              >
                #{{ produto.id }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium"
              >
                {{ produto.nome || "Produto sem nome" }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
              >
                {{ produto.quantidade }} unidades
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
              >
                {{ money(produto.preco_unitario) }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-600 dark:text-emerald-400"
              >
                {{ money(produto.quantidade * produto.preco_unitario) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mensagem quando não há produtos -->
      <div v-if="!sale?.produtos?.length" class="text-center py-12">
        <svg
          class="mx-auto h-12 w-12 text-gray-400"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
          />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
          Nenhum produto
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Esta compra não possui produtos listados.
        </p>
      </div>
    </div>

    <!-- Loading Screen -->
    <LoadingScreen v-if="isFetching" />

    <dialog
      ref="dialogRef"
      class="mx-auto my-auto p-4 rounded-md dark:bg-slate-800 dark:text-gray-200 focus:outline-0 backdrop:black/20"
    >
      <div class="flex justify-end">
        <button @click="dialogRef?.close()">
          <IconClose class="size-6 cursor-pointer" />
        </button>
      </div>
      <h3 class="font-bold text-2xl text-center">Cancelar essa venda?</h3>
      Essa ação não pode ser desfeita, tem certeza que deseja fazer isso?

      <div class="flex justify-center mt-4">
        <button
          @click="cancelSale"
          class="bg-red-500 hover:bg-red-400 rounded-lg text-center px-4 py-2 text-gray-200 cursor-pointer"
        >
          Sim, cancelar essa venda!
        </button>
      </div>
    </dialog>
  </div>
</template>
<style scoped>
dialog::backdrop {
  backdrop-filter: blur(3px);
}
</style>
