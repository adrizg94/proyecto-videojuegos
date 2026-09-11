<template>
  <div>
    <div
      v-if="threads.length"
      class="overflow-hidden rounded-xl border border-white/10 bg-surface"
    >
      <!-- Header -->
      <div
        class="hidden grid-cols-[1fr_180px_90px] gap-4 border-b border-white/10 px-5 py-3 text-xs font-medium uppercase tracking-wide text-text-muted md:grid"
      >
        <span>Discussion</span>
        <span>Author</span>
        <span class="text-center">Replies</span>
      </div>

      <!-- Threads -->
      <NuxtLink
        v-for="thread in threads"
        :key="thread.id"
        :to="`/community/threads/${thread.id}`"
        class="grid gap-3 border-b border-white/10 px-5 py-4 transition-colors last:border-b-0 hover:bg-hover md:grid-cols-[1fr_180px_90px] md:items-center md:gap-4"
      >
        <!-- Thread -->
        <div class="min-w-0">
          <p class="truncate font-medium">
            {{ thread.title }}
          </p>

          <div class="mt-1 flex items-center gap-2 text-xs text-text-muted">
            <template v-if="thread.game">
              <FontAwesomeIcon icon="fa-gamepad" />

              <span class="truncate">
                {{ thread.game.name }}
              </span>
            </template>

            <template v-else>
              <FontAwesomeIcon icon="fa-comments" />

              <span> General </span>
            </template>
          </div>
        </div>

        <!-- Author -->
        <div class="text-sm">
          <span class="md:hidden text-text-muted"> by </span>

          <span>
            {{ thread.user.username }}
          </span>
        </div>

        <!-- Replies -->
        <div
          class="flex items-center gap-2 text-sm text-text-muted md:justify-center"
        >
          <FontAwesomeIcon icon="fa-comment" />

          <span>
            {{ thread.replies_count }}
          </span>
        </div>
      </NuxtLink>
    </div>

    <div
      v-else
      class="rounded-xl border border-white/10 bg-surface px-6 py-8 text-center"
    >
      <FontAwesomeIcon icon="fa-comments" class="text-3xl text-text-muted" />
      <p class="mt-3 text-sm text-text-muted">
        {{ emptyMessage }}
      </p>
      <NuxtLink
        v-if="isAuthenticated && showEmptyAction"
        to="/community/general"
        class="group mt-2 inline-flex items-center gap-2 text-sm font-medium text-primary transition-colors hover:text-white"
        >Start the first one
        <FontAwesomeIcon
          icon="fa-arrow-right"
          class="text-xs transition-transform group-hover:translate-x-1"
      /></NuxtLink>
    </div>
  </div>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const { isAuthenticated } = useAuth();

defineProps({
  threads: {
    type: Array,
    default: () => [],
  },

  emptyMessage: {
    type: String,
    default: "No discussions yet.",
  },
  showEmptyAction: {
    type: Boolean,
    default: true,
  },
});
</script>
