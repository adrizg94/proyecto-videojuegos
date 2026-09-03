<template>
  <!-- class="bg-background flex min-h-screen" -->
  <div class="bg-background flex min-h-screen">
    <SideBar />
    <main class="flex-1">
      <slot />
    </main>
    <div v-if="isLoading" class="fixed inset-0 z-9998 cursor-wait" />
  </div>
</template>

<script setup>
const { isLoading } = useLoadingIndicator();
</script>
