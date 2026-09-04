<template>
  <section>
    {{ achievements }}
  </section>
</template>

<script setup>
const props = defineProps({
  achievements: Array,
});
</script>
