<template>
  <aside class="bg-surface w-46 p-2">
    <nav class="flex flex-col gap-2">
      <NuxtLink
        to="/"
        class="btn-primary"
        :class="route.path === '/' ? 'bg-primary' : 'hover:bg-hover'"
        >Home</NuxtLink
      >
      <NuxtLink
        to="/games"
        class="btn-primary"
        :class="route.path === '/games' ? 'bg-primary' : 'hover:bg-hover'"
        >Games</NuxtLink
      >
      <NuxtLink
        to="/publishers"
        class="btn-primary"
        :class="route.path === '/publishers' ? 'bg-primary' : 'hover:bg-hover'"
        >Publishers</NuxtLink
      >
      <NuxtLink
        to="/developers"
        class="btn-primary"
        :class="route.path === '/developers' ? 'bg-primary' : 'hover:bg-hover'"
        >Developers</NuxtLink
      >
      <NuxtLink
        to="/creators"
        class="btn-primary"
        :class="route.path === '/creators' ? 'bg-primary' : 'hover:bg-hover'"
        >Creators</NuxtLink
      >
    </nav>
  </aside>
</template>

<script setup>
const route = useRoute();
</script>
