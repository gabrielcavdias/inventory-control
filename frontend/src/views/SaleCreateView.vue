<script setup lang="ts">
import { Field, FieldGroup, FieldLabel, FieldSet } from "@/components/ui/field";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { useForm } from "laravel-precognition-vue";
import { api_url } from "@/lib/utils";
import { useRouter } from "vue-router";
// @ts-ignore
import { ref } from "vue";

import ProductsChooseBox from "@/components/ProductsChooseBox.vue";
import type { ProductTransaction } from "@/models/transactions";

const router = useRouter();
const form = useForm<{ cliente: string; produtos: ProductTransaction[] }>(
  "post",
  api_url("/vendas"),
  {
    cliente: "",
    produtos: [],
  },
);
const productsBoxRef = ref<InstanceType<typeof ProductsChooseBox> | null>();

const submit = async () => {
  if (!productsBoxRef.value) return;
  try {
    form.produtos = productsBoxRef.value.selectedProducts;
    await form.submit();
    router.push("/vendas");
    form.cliente = "";
    form.produtos = [];
  } catch (e) {
    console.error(e);
  }
};
</script>

<template>
  <h1
    class="text-center text-2xl font-bold mb-6 text-emerald-600 dark:text-emerald-400"
  >
    Registrar Venda
  </h1>
  <aside
    class="max-w-7xl mx-auto bg-red-500 p-2 rounded-xl text-center"
    v-if="form.invalid('produtos')"
  >
    {{ form.errors.produtos }}
  </aside>
  <form class="mt-12 w-7xl mx-auto" @submit.prevent="submit">
    <FieldSet class="w-full">
      <FieldGroup>
        <Field>
          <FieldLabel for="nome"> Nome do cliente </FieldLabel>
          <Input
            id="nome"
            type="text"
            placeholder="Fulano X"
            v-model="form.cliente"
            @change="form.validate('cliente')"
          />
          <span
            v-if="form.invalid('cliente')"
            class="text-red-500 dark:text-red-300"
          >
            {{ form.errors.cliente }}
          </span>
        </Field>
      </FieldGroup>
      <ProductsChooseBox ref="productsBoxRef" :errors="form.errors" />
      <Button type="submit">Registrar Venda</Button>
    </FieldSet>
  </form>
</template>
