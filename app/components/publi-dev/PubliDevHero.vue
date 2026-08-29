<template>
  <header>
    <h1 class="font-bold text-4xl not-even:pb-5">
      {{ name }}
    </h1>
  </header>
</template>

<script setup>
const props = defineProps({
  name: String,
});
</script>
