<!-- Portada, nombre, fecha de lanzamiento, valoración, platadormas -->

<template>
  <header class="flex flex-col gap-3 pt-8 pb-5">
    <div class="flex items-center gap-5">
      <p class="bg-white text-black rounded-sm px-2 w-fit">
        {{ game.tba ? "TBA" : game.released }}
      </p>
      <div class="flex items-center gap-2">
        <FontAwesomeIcon
          v-if="hasPC"
          :icon="['fab', 'windows']"
          class="text-white"
        />
        <FontAwesomeIcon
          v-if="hasPlaystation"
          :icon="['fab', 'playstation']"
          class="text-white"
        />
        <FontAwesomeIcon
          v-if="hasXbox"
          :icon="['fab', 'xbox']"
          class="text-white"
        />
      </div>
    </div>
    <h1 class="font-bold text-4xl">{{ game.name }}</h1>
    <div class="text-xl flex gap-3 items-center">
      <span class="font-bold"
        ><FontAwesomeIcon icon="fa-star" /> {{ game.rating }}</span
      >
      <span class="font-semibold">{{ ratingValue }}</span>
    </div>
  </header>
</template>

<script setup>
const props = defineProps({
  game: Object,
});

const ratingValue = computed(() => {
  if (props.game.rating >= 4.5) return "Exceptional";
  if (props.game.rating >= 4) return "Excellent";
  if (props.game.rating >= 3) return "Good";
  if (props.game.rating >= 2) return "Average";
  return "Poor";
});

const hasPC = computed(() =>
  props.game.parent_platforms?.some((p) => p.platform.id === 1),
);

const hasPlaystation = computed(() =>
  props.game.parent_platforms?.some((p) => p.platform.id === 2),
);

const hasXbox = computed(() =>
  props.game.parent_platforms?.some((p) => p.platform.id === 3),
);
</script>
