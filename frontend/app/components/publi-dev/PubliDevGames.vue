<template>
  <h3 class="text-xl text-center font-semibold pb-2">
    {{ gamesCountText }}
  </h3>
  <ul class="flex flex-col gap-y-3">
    <li v-for="game in games.results" :key="game.id">
      <NuxtLink
        :to="`/games/${game.id}`"
        class="grid grid-cols-[1fr_auto_0.7fr] items-center gap-4 rounded-sm hover:bg-hover p-2 transition-colors"
      >
        <div class="flex items-center gap-2 justify-self-start">
          <img
            :src="game.background_image || '/images/no-game.svg'"
            :alt="game.name"
            class="size-10 shrink-0 rounded-full"
            :class="
              game.background_image ? 'object-cover' : 'object-contain p-1'
            "
          />
          {{ game.name }}
        </div>
        <div class="text-text-muted justify-self-center">
          {{
            [
              game.released,
              ...game.genres.map((genre) => genre.name),
              ...getPlatformFamilies(game.platforms),
            ]
              .filter(Boolean)
              .join(" · ")
          }}
        </div>
        <FontAwesomeIcon
          icon="fa-chevron-right"
          class="text-text-muted justify-self-end"
        />
      </NuxtLink>
    </li>
  </ul>
  <NuxtLink
    :to="{ path: '/games', query: { [queryName]: id } }"
    v-if="games.count > games.results.length"
    class="hover:bg-primary bg-hover cursor-pointer mx-auto px-3 py-2 rounded-full my-3 transition-colors"
  >
    View all games
  </NuxtLink>
</template>

<script setup>
const props = defineProps({
  games: Object,
  queryName: String,
  id: Number,
  gamesCountText: String,
});

// Limitar las plataformas visibles
const getPlatformFamilies = (platforms = []) => {
  const families = new Set();

  platforms.forEach(({ platform }) => {
    const slug = platform.slug;

    if (slug === "pc") {
      families.add("PC");
    } else if (slug.includes("playstation")) {
      families.add("PlayStation");
    } else if (slug.includes("xbox")) {
      families.add("Xbox");
    } else if (slug === "nintendo-switch") {
      families.add("Nintendo Switch");
    }
  });

  return [...families];
};
</script>
