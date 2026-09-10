<template>
  <aside class="bg-surface p-2">
    <nav class="flex flex-col gap-2">
      <NuxtLink to="/" class="flex items-center h-14 justify-center gap-1">
        <img src="/images/branding/logo.png" class="h-13 object-contain pb-1 pl-2" />
        <img src="/images/branding/nextplay.png" class="h-10.5" />
      </NuxtLink>
      <!-- <NuxtLink to="/" class="flex items-center gap-2 h-12 border-b-2 border-white/10">
        <div
          class="flex size-9 px-1 items-center justify-start gap-0.5 rounded-lg bg-primary"
        >
          <span class="text-lg font-black">N</span>
          <FontAwesomeIcon icon="fa-play" class="text-xs" />
        </div>

        <span class="text-xl font-bold">
          Next<span class="text-primary-light">Play</span>
        </span>
      </NuxtLink> -->
      <NuxtLink
        to="/"
        class="btn-primary"
        :class="route.path === '/' ? 'bg-primary' : 'hover:bg-hover'"
        >Releases</NuxtLink
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
