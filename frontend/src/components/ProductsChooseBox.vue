<script setup lang="ts">
import { ref, computed, watch, onMounted, shallowRef } from "vue";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
// @ts-ignore
import { useApi } from "@/composables/useApi";
import type { PaginatedResponse } from "@/models/api";
import type { Product } from "@/models/product";
import { refDebounced } from "@vueuse/core";

const {
  data: products,
  isFetching,
  fetchData,
} = useApi<PaginatedResponse<Product>>();

const { errors } = defineProps<{ errors?: Record<string, string> }>();
const parsedErrors = computed(() => {
  const errorsMap = new Map<number, string>();
  Object.entries(errors ?? []).forEach(([label, error]) => {
    const matches = label.match(/produtos\.(\d+)\.(.+)/);
    if (matches) {
      const [_, index] = matches;
      errorsMap.set(+(index as string), error);
    }
  });
  return errorsMap;
});
const search = shallowRef("");
const searchDebounced = refDebounced(search, 500);
const selectedProducts = ref<
  Array<{
    id: number;
    nome: string;
    quantidade: number;
    preco_unitario: number;
    preco_venda: number;
  }>
>([]);

const fetchProducts = async (
  page: number = 1,
  perPage: number = 50,
  name: string = "",
) => {
  const params = new URLSearchParams();
  params.set("page", page.toString());
  params.set("per_page", perPage.toString());
  params.set("nome", name);
  // @ts-ignore
  await fetchData("/produtos" + "?" + params.toString());
};

const addProduct = (product: Product) => {
  if (!selectedProducts.value.find((p) => p.id === product.id)) {
    selectedProducts.value.push({
      id: product.id,
      nome: product.nome,
      quantidade: 1,
      preco_unitario: product.preco_venda, // Começa com o preco_venda / Preço sugerido
      preco_venda: product.preco_venda,
    });
  }
};

const removeProduct = (productId: number) => {
  selectedProducts.value = selectedProducts.value.filter(
    (p) => p.id !== productId,
  );
};

onMounted(() => fetchProducts());
watch(searchDebounced, () => {
  fetchProducts(1, 10, searchDebounced.value);
});

defineExpose({
  selectedProducts: computed(() =>
    selectedProducts.value.map(({ id, quantidade, preco_unitario }) => ({
      id,
      quantidade,
      preco_unitario,
    })),
  ),
  clearSelection: () => {
    selectedProducts.value = [];
  },
});
</script>
<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
      <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">
        Selecionar Produtos
      </h2>

      <Input
        type="text"
        placeholder="Buscar produtos por nome..."
        v-model="search"
        class="mb-4"
      />

      <div
        class="max-h-96 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded"
      >
        <div v-if="isFetching" class="p-4 text-center text-gray-500">
          Carregando produtos...
        </div>
        <div
          v-else-if="products?.data.length === 0"
          class="p-4 text-center text-gray-500"
        >
          Nenhum produto encontrado
        </div>
        <div
          v-for="product in products?.data"
          :key="product.id"
          @click="addProduct(product)"
          class="p-3 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition"
        >
          <div class="font-medium">{{ product.nome }}</div>
          <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            <span>Estoque: {{ product.estoque_atual }}</span>
            <span class="mx-2">•</span>
            <span>Preço sugerido: R$ {{ product.preco_venda.toFixed(2) }}</span>
          </div>
        </div>
        <div
          v-if="products?.data && products.data.length > 50"
          class="p-3 text-center text-sm text-gray-500 bg-gray-50 dark:bg-gray-700"
        >
          ... e mais {{ products.data.length - 50 }} produtos. Use a busca para
          encontrar específicos.
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">
          Produtos Selecionados
        </h2>
        <span class="text-sm text-gray-500">
          {{ selectedProducts.length }} item(ns)
        </span>
      </div>

      <div
        v-if="selectedProducts.length === 0"
        class="text-center text-gray-500 py-8"
      >
        Nenhum produto selecionado
      </div>

      <div v-else class="space-y-4 max-h-96 overflow-y-auto">
        <div
          v-for="(product, index) in selectedProducts"
          :key="product.id"
          class="p-3 border border-gray-200 dark:border-gray-700 rounded"
        >
          <span
            class="text-red-500 dark:text-red-400"
            v-if="parsedErrors.has(index)"
            >{{ parsedErrors.get(index) }}</span
          >

          <div class="flex justify-between items-start mb-2">
            <div>
              <span class="font-medium">{{ product.nome }}</span>
              <span class="text-xs text-gray-500 ml-2">
                (sugerido: R$ {{ product.preco_venda.toFixed(2) }})
              </span>
            </div>
            <Button
              type="button"
              variant="ghost"
              size="sm"
              @click="removeProduct(product.id)"
              class="text-red-500 hover:text-red-700 cursor-pointer"
            >
              Remover
            </Button>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label
                class="block text-sm text-gray-600 dark:text-gray-400 mb-1"
              >
                Quantidade
              </label>
              <Input
                type="number"
                :value="product.quantidade"
                v-model="product.quantidade"
                min="0"
                class="w-full"
              />
            </div>
            <div>
              <label
                class="block text-sm text-gray-600 dark:text-gray-400 mb-1"
              >
                Preço Unitário
              </label>
              <div class="flex gap-2">
                <Input
                  type="number"
                  v-model="product.preco_unitario"
                  min="0"
                  step="0.01"
                  class="w-full"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
