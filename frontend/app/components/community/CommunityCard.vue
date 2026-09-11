<template>
  <article
    class="flex flex-col rounded-xl bg-surface p-6 border border-white/10"
  >
    <div class="flex items-start gap-4">
      <div
        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-hover text-primary"
      >
      <FontAwesomeIcon :icon="icon" />
      </div>

      <div>
        <h2 class="text-lg font-bold">
          {{ title }}
        </h2>

        <p class="mt-1 text-sm text-text-muted">
          {{ description }}
        </p>
      </div>
    </div>

    <div class="mt-auto pt-6">
      <slot>
        <NuxtLink
          v-if="to"
          :to="to"
          class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-text-muted transition-colors hover:bg-hover hover:text-white"
        >
          {{ action }}

          <FontAwesomeIcon icon="fa-arrow-right" class="text-xs" />
        </NuxtLink>
      </slot>
    </div>
  </article>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },

  description: {
    type: String,
    required: true,
  },

  icon: {
    type: String,
    required: true,
  },

  to: {
    type: String,
    default: null,
  },

  action: {
    type: String,
    default: null,
  },
});
</script>
