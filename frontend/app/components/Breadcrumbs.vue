<template>
  <nav
    v-if="breadcrumbs.length"
    aria-label="Breadcrumb"
    class="flex min-w-0 items-center gap-2 text-sm"
  >
    <template
      v-for="(item, index) in breadcrumbs"
      :key="`${item.label}-${index}`"
    >
      <FontAwesomeIcon
        v-if="index > 0"
        icon="fa-chevron-right"
        class="text-[10px] text-text-muted"
      />

      <NuxtLink
        v-if="item.to && index !== breadcrumbs.length - 1"
        :to="item.to"
        class="shrink-0 text-text-muted transition-colors hover:text-white"
      >
        {{ item.label }}
      </NuxtLink>

      <span v-else class="truncate font-semibold text-white">
        {{ item.label }}
      </span>
    </template>
  </nav>
</template>

<script setup>
const { breadcrumbs } = useBreadcrumbs();
</script>
