<script setup lang="ts">
import { ref, useTemplateRef, watch } from "vue";
import Input from "./ui/input/Input.vue";
import IconChevron from "./icons/IconChevron.vue";
import { onClickOutside } from "@vueuse/core";

const { options, selectedLabel } = defineProps<{
  options: Partial<{ label: string }>[];
  selectedLabel: string;
}>();

const search = ref("");
const focused = ref(false);
const selectedRef = useTemplateRef("selectedRef");
const ignoreRef = useTemplateRef("ignoreRef");
onClickOutside(selectedRef, () => (focused.value = false), {
  ignore: [ignoreRef],
});
const emit = defineEmits(["selected", "search"]);
const selectedClasses =
  "dark:bg-input/30 border-input h-9 w-full min-w-0 rounded-md border bg-white px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive flex justify-between";

const select = (option: Partial<{ label: string }>) => {
  focused.value = false;
  emit("selected", option);
};
watch(search, () => {
  emit("search", search.value);
});
</script>
<template>
  <div class="relative h-8">
    <p :class="selectedClasses" @click="focused = true" ref="selectedRef">
      <span>{{ selectedLabel }}</span>
      <IconChevron class="size-6 text-gray-400" />
    </p>
    <Transition v-if="focused">
      <ul class="absolute w-full bg-white" ref="ignoreRef">
        <li>
          <Input id="nome" type="text" v-model="search" />
        </li>
        <li
          v-for="option in options"
          @click="select(option)"
          class="p-4 hover:bg-emerald-400 cursor-pointer"
        >
          {{ option.label }}
        </li>
      </ul>
    </Transition>
  </div>
</template>
<style>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
