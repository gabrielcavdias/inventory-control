<script lang="ts" setup>
import LoadingScreen from "@/components/common/LoadingScreen.vue";
import IconBox from "@/components/icons/IconBox.vue";
import IconMoney from "@/components/icons/IconMoney.vue";
import IconUser from "@/components/icons/IconUser.vue";
import { useApi } from "@/composables/useApi";
import { money } from "@/lib/utils";
import type { Purchase } from "@/models/transactions";
import { onMounted, computed } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const { data: purchase, isFetching, fetchData } = useApi<Purchase>();

const totalItems = computed(() => {
  if (!purchase.value?.produtos) return 0;
  return purchase.value.produtos.reduce(
    (acc, produto) => acc + produto.quantidade,
    0,
  );
});

onMounted(async () => {
  await fetchData(`/compras/${route.params.id}`);
});
</script>

<template>
  <div class="max-w-7xl mx-auto p-4">
    <div class="mb-8">
      <h1
        class="text-center text-3xl font-bold text-emerald-600 dark:text-emerald-400"
      >
        Compra #{{ purchase?.id }}
      </h1>
      <p class="text-center text-gray-600 dark:text-gray-400 mt-2">
        Realizada com o fornecedor "{{ purchase?.fornecedor }}"
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
              {{ purchase?.fornecedor }}
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
            <p class="text-sm text-gray-600 dark:text-gray-400">Custo Total</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ money(purchase?.custo_total || 0) }}
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
          {{ purchase?.produtos?.length || 0 }} produtos no total
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
            <tr v-for="produto in purchase?.produtos" :key="produto.id">
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
      <div v-if="!purchase?.produtos?.length" class="text-center py-12">
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
  </div>
</template>
