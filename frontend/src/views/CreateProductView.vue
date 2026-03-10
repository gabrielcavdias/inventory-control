<script setup lang="ts">
import { Field, FieldGroup, FieldLabel, FieldSet } from "@/components/ui/field";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { useForm } from "laravel-precognition-vue";
import { api_url } from "@/lib/utils";
import { useAppStore } from "@/stores/app";
import { useRouter } from "vue-router";
// @ts-ignore
import { useCurrencyInput } from "vue-currency-input";

const router = useRouter();
const form = useForm("post", api_url("/produtos"), {
  nome: "",
  preco_venda: 0,
});
const { inputRef, numberValue } = useCurrencyInput(
  {
    currency: "BRL",
    currencyDisplay: "symbol",
    valueRange: {
      max: 99999,
    },
    hideCurrencySymbolOnFocus: true,
    hideGroupingSeparatorOnFocus: true,
    hideNegligibleDecimalDigitsOnFocus: true,
    autoDecimalDigits: false,
    useGrouping: true,
    accountingSign: false,
  },
  false,
);

const submit = async () => {
  try {
    form.preco_venda = numberValue.value;
    await form.submit();
    router.push("/produtos");
    form.nome = "";
    form.preco_venda = 0;
  } catch (e) {
    console.error(e);
  }
};
</script>

<template>
  <h1
    class="text-center text-2xl font-bold mb-6 text-emerald-600 dark:text-emerald-400"
  >
    Adicionar Produto
  </h1>
  <form class="mt-12 w-full max-w-md mx-auto" @submit.prevent="submit">
    <FieldSet class="w-full">
      <FieldGroup>
        <Field>
          <FieldLabel for="nome"> Nome do produto </FieldLabel>
          <Input
            id="nome"
            type="text"
            placeholder="Coca cola 2L"
            v-model="form.nome"
            @change="form.validate('nome')"
          />
          <span
            v-if="form.invalid('nome')"
            class="text-red-500 dark:text-red-300"
          >
            {{ form.errors.nome }}
          </span>
        </Field>
        <Field>
          <FieldLabel for="preco_venda"> Preço Sugerido </FieldLabel>
          <Input
            id="preco_venda"
            type="text"
            placeholder="Coca cola 2L"
            v-model="form.preco_venda"
            ref="inputRef"
          />
          <span
            v-if="form.invalid('preco_venda')"
            class="text-red-500 dark:text-red-300"
          >
            {{ form.errors.preco_venda }}
          </span>
        </Field>
      </FieldGroup>
      <Button type="submit">Criar</Button>
    </FieldSet>
  </form>
</template>
