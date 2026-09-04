<!-- Portada, nombre, fecha de lanzamiento, valoración, platadormas -->

<template>
  <header class="flex flex-col gap-3 pt-8 pb-5">
    <div class="flex items-center gap-5">
      <p class="bg-white text-black rounded-sm px-2 w-fit">
        {{ game.tba ? "TBA" : game.released }}
      </p>
      <div class="flex items-center gap-2">
        <FontAwesomeIcon v-if="hasPC" :icon="['fab', 'windows']" />
        <FontAwesomeIcon v-if="hasPlaystation" :icon="['fab', 'playstation']" />
        <FontAwesomeIcon v-if="hasXbox" :icon="['fab', 'xbox']" />
      </div>
      <img
        v-if="pegiIcons[details?.ratings?.pegi?.rating]"
        :src="pegiIcons[details?.ratings?.pegi?.rating]"
        :alt="`PEGI ${details?.ratings?.pegi?.rating}`"
        :title="`PEGI ${details?.ratings?.pegi?.rating}`"
        class="h-9"
      />
    </div>
    <h1 class="font-bold text-4xl">{{ game.name }}</h1>
    <div class="text-xl flex gap-3 items-center">
      <span class="font-bold"
        ><FontAwesomeIcon icon="fa-star" /> {{ game.rating }}</span
      >
      <span class="font-semibold">{{ ratingValue }}</span>
      <a
        v-if="details?.metacritic?.url"
        :href="details?.metacritic?.url"
        class="group flex items-center gap-1 ml-4"
      >
        <span class="group-hover:text-primary-light transition-colors"
          >Metacritic</span
        >
        <span class="rounded px-1.5 py-0.5 bg-surface/70 font-semibold">{{
          details?.metacritic?.score
        }}</span></a
      >
    </div>
  </header>
</template>

<script setup>
const props = defineProps({
  game: Object,
  details: {
    type: Object,
    default: null,
  },
});

const pegiIcons = {
  3: "/images/pegi/3.svg",
  7: "/images/pegi/7.svg",
  12: "/images/pegi/12.svg",
  16: "/images/pegi/16.svg",
  18: "/images/pegi/18.svg",
};

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
