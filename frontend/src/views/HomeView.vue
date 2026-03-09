<script setup lang="ts">
import LoadingScreen from "@/components/common/LoadingScreen.vue";
import IconArrowLongRight from "@/components/icons/IconArrowLongRight.vue";
import { useApi } from "@/composables/useApi";
import { money } from "@/lib/utils";
import type { DashboardData } from "@/models/dashboard";
import { onMounted } from "vue";

const { data, isFetching, fetchData } = useApi<DashboardData>();
onMounted(async () => {
  fetchData("/dashboard");
});
</script>

<template>
  <h1
    class="text-center text-2xl font-bold mb-6 text-emerald-600 dark:text-emerald-400"
  >
    Métricas do negócio
  </h1>
  <main class="max-w-7xl mx-auto" v-if="!isFetching && data">
    <!-- Métricas gerais -->
    <ul class="grid md:grid-cols-4 gap-2 min-h-32">
      <li
        class="bg-emerald-500 text-gray-200 font-bold grid place-items-center text-center text-2xl rounded-2xl"
      >
        <div>
          {{ data.vendas_hoje }}
          <span class="block text-sm">Vendas realizadas hoje</span>
        </div>
      </li>
      <li
        class="bg-emerald-500 text-gray-200 font-bold grid place-items-center text-center text-2xl rounded-2xl"
      >
        <div>
          {{ money(data.lucro_hoje) }}
          <span class="block text-sm"> Lucro de hoje </span>
        </div>
      </li>
      <li
        class="bg-emerald-500 text-gray-200 font-bold grid place-items-center text-center text-2xl rounded-2xl"
      >
        <div>
          {{ money(data.total_gasto) }}
          <span class="block text-sm">Total gasto em compras hoje</span>
        </div>
      </li>
      <li
        class="bg-emerald-500 text-gray-200 font-bold grid place-items-center text-center text-2xl rounded-2xl"
      >
        <div>
          {{ data.itens_vendidos }}
          <span class="block text-sm"> Quantidade de itens vendidos hoje </span>
        </div>
      </li>
    </ul>
    <div class="mt-4 grid md:grid-cols-3 gap-4">
      <section aria-labelledby="products_title" class="outline p-4 rounded-xl">
        <h2
          id="products_title"
          class="text-emerald-600 dark:text-emerald-400 font-bold text-xl"
        >
          Produtos
        </h2>
        <ul>
          <li
            class="flex justify-between font-bold text-emerald-800 dark:text-emerald-200"
          >
            <span>Produto</span>
            <span>Preço sugerido</span>
          </li>
          <li class="flex justify-between" v-for="produto in data.produtos">
            <span>{{ produto.nome }}</span>
            <span>{{ money(produto.preco_venda) }}</span>
          </li>
        </ul>
        <div class="mt-4 flex justify-end">
          <RouterLink
            to="/produtos"
            class="text-emerald-600 dark:text-emerald-400 font-bold inline-flex items-center gap-2 border-b-2 border-transparent hover:border-current transition"
          >
            Ver Mais
            <IconArrowLongRight class="size-6" />
          </RouterLink>
        </div>
      </section>
      <section aria-labelledby="sales_title" class="outline p-4 rounded-xl">
        <h2
          id="sales_title"
          class="text-emerald-600 dark:text-emerald-400 font-bold text-xl"
        >
          Vendas
        </h2>
        <ul>
          <li
            class="flex justify-between font-bold text-emerald-800 dark:text-emerald-200"
          >
            <span>Cliente</span>
            <span>Lucro</span>
          </li>
          <li class="flex justify-between" v-for="venda in data.vendas">
            <span>{{ venda.cliente }}</span>
            <span>{{ money(venda.lucro) }}</span>
          </li>
        </ul>
        <div class="mt-4 flex justify-end">
          <RouterLink
            to="/vendas"
            class="text-emerald-600 dark:text-emerald-400 font-bold inline-flex items-center gap-2 border-b-2 border-transparent hover:border-current transition"
          >
            Ver Mais
            <IconArrowLongRight class="size-6" />
          </RouterLink>
        </div>
      </section>
      <section aria-labelledby="purchases_title" class="outline p-4 rounded-xl">
        <h2
          id="purchases_title"
          class="text-emerald-600 dark:text-emerald-400 font-bold text-xl"
        >
          Compras
        </h2>
        <ul>
          <li
            class="flex justify-between font-bold text-emerald-800 dark:text-emerald-200"
          >
            <span>Fornecedor</span>
            <span>Custo Total</span>
          </li>
          <li class="flex justify-between" v-for="compra in data.compras">
            <span>{{ compra.fornecedor }}</span>
            <span>{{ money(compra.custo_total) }}</span>
          </li>
        </ul>
        <div class="mt-4 flex justify-end">
          <RouterLink
            to="/compras"
            class="text-emerald-600 dark:text-emerald-400 font-bold inline-flex items-center gap-2 border-b-2 border-transparent hover:border-current transition"
          >
            Ver Mais
            <IconArrowLongRight class="size-6" />
          </RouterLink>
        </div>
      </section>
    </div>
  </main>
  <LoadingScreen v-if="isFetching" />
</template>
