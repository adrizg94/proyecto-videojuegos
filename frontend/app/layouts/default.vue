<template>
  <div class="grid grid-cols-[180px_minmax(0,1fr)] bg-background min-h-dvh">
    <SideBar />
    <div class="flex min-h-0 flex-col">
      <TopBar class="shrink-0 bg-surface" />
      <div class="flex flex-1">
        <main class="min-h-0 flex-1">
          <slot />
        </main>
      </div>
    </div>
    <div v-if="isLoading" class="fixed inset-0 z-9998 cursor-wait" />
  </div>
</template>

<script setup>
import SideBar from "~/components/SideBar.vue";
import TopBar from "~/components/TopBar.vue";

const { isLoading } = useLoadingIndicator();
</script>
