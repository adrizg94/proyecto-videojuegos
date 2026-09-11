<template>
  <NuxtLink
    :to="{
      path: `/users/${list.user.username}/lists/${list.id}`,
      query: {
        from: 'community',
      },
    }"
    class="group flex flex-col rounded-xl border border-white/10 bg-surface p-5 transition duration-200 hover:bg-hover hover:shadow-lg"
  >
    <div class="flex items-start justify-between gap-4">
      <div
        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-hover text-primary"
      >
        <FontAwesomeIcon icon="fa-list" />
      </div>

      <div class="flex items-center gap-2 text-sm text-text-muted">
        <FontAwesomeIcon icon="fa-gamepad" class="text-xs" />

        <span>
          {{ list.games_count }}
          {{ list.games_count === 1 ? "game" : "games" }}
        </span>
      </div>
    </div>

    <div class="mt-4 flex-1">
      <h2 class="text-lg font-bold transition-colors group-hover:text-primary">
        {{ list.title }}
      </h2>

      <div class="mt-2 flex items-center gap-2 text-sm text-text-muted">
        <FontAwesomeIcon icon="fa-user" class="text-xs" />

        <span> by {{ list.user.username }} </span>
      </div>
    </div>

    <div
      class="mt-5 flex items-center justify-between border-t border-white/10 pt-4"
    >
      <span class="text-xs text-text-muted">
        {{ formattedDate }}
      </span>

      <span
        class="flex items-center gap-2 text-sm font-medium text-primary transition-colors group-hover:text-white"
      >
        View list

        <FontAwesomeIcon
          icon="fa-arrow-right"
          class="text-xs transition-transform group-hover:translate-x-1"
        />
      </span>
    </div>
  </NuxtLink>
</template>

<script setup>
const props = defineProps({
  list: {
    type: Object,
    required: true,
  },
});

const formattedDate = computed(() => {
  if (!props.list.created_at) {
    return "";
  }

  return new Date(props.list.created_at).toLocaleDateString("en-GB", {
    day: "numeric",
    month: "short",
    year: "numeric",
  });
});
</script>
