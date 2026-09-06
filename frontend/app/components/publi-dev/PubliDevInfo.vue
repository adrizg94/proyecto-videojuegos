<template>
  <div v-html="description" class="description pb-6"></div>
</template>

<script setup>
const props = defineProps({
  description: String,
});
</script>
