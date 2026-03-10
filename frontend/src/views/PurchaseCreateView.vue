<script setup lang="ts">
import { Field, FieldGroup, FieldLabel, FieldSet } from "@/components/ui/field";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { useForm } from "laravel-precognition-vue";
import { api_url } from "@/lib/utils";
import { useRouter } from "vue-router";
// @ts-ignore
import { ref, useTemplateRef } from "vue";

import ProductsChooseBox from "@/components/ProductsChooseBox.vue";
import type { ProductTransaction } from "@/models/transactions";

const router = useRouter();
const form = useForm<{ fornecedor: string; produtos: ProductTransaction[] }>(
  "post",
  api_url("/compras"),
  {
    fornecedor: "",
    produtos: [],
  },
);
const search = ref("");
const productsBoxRef = ref<InstanceType<typeof ProductsChooseBox> | null>();

const submit = async () => {
  if (!productsBoxRef.value) return;
  try {
    form.produtos = productsBoxRef.value.selectedProducts;
    await form.submit();
    router.push("/compras");
    form.fornecedor = "";
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
    Registrar Compra
  </h1>
  {{ search }}
  <form class="mt-12 w-7xl mx-auto" @submit.prevent="submit">
    <FieldSet class="w-full">
      <FieldGroup>
        <Field>
          <FieldLabel for="nome"> Nome do forncedor </FieldLabel>
          <Input
            id="nome"
            type="text"
            placeholder="Distribuidora João"
            v-model="form.fornecedor"
            @change="form.validate('fornecedor')"
          />
          <span
            v-if="form.invalid('fornecedor')"
            class="text-red-500 dark:text-red-300"
          >
            {{ form.errors.fornecedor }}
          </span>
        </Field>
      </FieldGroup>
      <ProductsChooseBox ref="productsBoxRef" />
      <Button type="submit">Registrar Compra</Button>
    </FieldSet>
  </form>
</template>
