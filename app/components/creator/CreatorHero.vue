<template>
  <header class="flex items-center gap-6 pb-4">
    <img
      :src="
        creator.image ?? 'https://placehold.co/600x400?text=Image+Not+Found'
      "
      :alt="creator.name"
      class="size-40 shrink-0 rounded-full object-cover"
    />
    <div>
      <div class="flex items-center gap-4">
        <h1 class="font-bold text-4xl">
          {{ creator.name }}
        </h1>
        <button type="button" class="cursor-pointer">
          <FontAwesomeIcon
            icon="fa-heart"
            class="text-4xl transition-colors hover:text-primary-light"
            aria-label="Add creator to favorites"
          />
        </button>
      </div>
      <p class="text-text-muted text-lg">
        {{ formattedRoles }}
      </p>
    </div>
  </header>
</template>

<script setup>
const props = defineProps({
  creator: Object,
});

const formattedRoles = computed(() =>
  (props.creator.positions ?? [])
    .map((role) =>
      role.name
        .split(" ")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" "),
    )
    .join(" · "),
);
</script>
