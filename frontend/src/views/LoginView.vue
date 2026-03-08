<script setup lang="ts">
import {
  Field,
  FieldDescription,
  FieldGroup,
  FieldLabel,
  FieldSet,
} from "@/components/ui/field";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { useForm } from "laravel-precognition-vue";
import { api_url } from "@/lib/utils";
import { useAppStore } from "@/stores/app";
import { useRouter } from "vue-router";
import { onMounted } from "vue";

const appStore = useAppStore();
const router = useRouter();
const form = useForm("post", api_url("/login"), {
  password: "",
  email: "",
});

const submit = async () => {
  try {
    const response = (await form.submit()) as { data: { token: string } };
    if (response.data.token) {
      appStore.token = response.data.token;
      router.push("/");
    }
  } catch (e) {
    console.error(e);
  }
};

onMounted(() => {
  if (appStore.isUserLoggedIn) {
    router.push("/");
  }
});
</script>

<template>
  <form
    class="w-full max-w-md mx-auto h-screen grid place-items-center"
    @submit.prevent="submit"
  >
    <FieldSet class="w-full">
      <FieldGroup>
        <Field>
          <FieldLabel for="email"> Email </FieldLabel>
          <Input
            id="email"
            type="email"
            placeholder="Seu melhor email"
            v-model="form.email"
            @change="form.validate('email')"
          />
          <span
            v-if="form.invalid('email')"
            class="text-red-500 dark:text-red-300"
          >
            {{ form.errors.email }}
          </span>
        </Field>
        <Field>
          <FieldLabel for="password"> Senha </FieldLabel>
          <Input
            id="password"
            type="password"
            placeholder="********"
            v-model="form.password"
          />
          <span
            v-if="form.invalid('password')"
            class="text-red-500 dark:text-red-300"
          >
            {{ form.errors.password }}
          </span>
        </Field>
      </FieldGroup>
      <Button type="submit">Entrar</Button>
    </FieldSet>
  </form>
</template>
