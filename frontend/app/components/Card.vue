<template>
  <article
    class="flex flex-col bg-surface cursor-pointer rounded-lg h-68 w-78 transition duration-200 hover:bg-hover hover:scale-105 hover:shadow-xl"
  >
    <div class="relative group w-full mx-auto">
      <NuxtLink :to="link">
        <img
          :src="background || noImage"
          :alt="name"
          class="h-48 rounded-t-lg w-full group-hover:brightness-40 transition"
          :class="background ? 'object-cover' : 'object-contain p-8'"
        />
      </NuxtLink>

      <button
        type="button"
        title="Add to favorites"
        @click="handleFavorite"
        class="absolute z-10 m-auto w-fit h-fit opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
        :class="showReleaseAlert ? 'inset-y-0 left-28' : 'inset-0'"
      >
        <FontAwesomeIcon
          v-if="isAuthenticated"
          icon="fa-heart"
          class="text-3xl transition hover:text-primary-light hover:scale-110"
          :class="{ 'text-primary-light': isFavorite }"
        />
      </button>
      <button
        v-if="showReleaseAlert && isAuthenticated"
        type="button"
        title="Notify me on release"
        class="absolute inset-y-0 right-28 z-10 m-auto w-fit h-fit opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
      >
        <FontAwesomeIcon
          icon="fa-bell"
          class="text-3xl transition hover:text-primary-light hover:scale-110"
        />
      </button>
    </div>
    <NuxtLink :to="link" class="flex-1 flex items-center justify-center">
      <h2 class="font-bold">
        {{ name }}
      </h2>
    </NuxtLink>
  </article>
</template>

<script setup>
const props = defineProps({
  game: {
    type: Object,
    default: null,
  },
  entity: {
    type: Object,
    default: null,
  },
  link: {
    type: String,
    default: "",
  },
  isFavorite: {
    type: Boolean,
    default: false,
  },
});

// const emit = defineEmits(["addFavorite", "removeFavorite"]);

const { isAuthenticated } = useAuth();

const { toggleFavorite } = useFavorites();

const handleFavorite = () => {
  toggleFavorite(
    props.isFavorite,
    id.value,
    name.value,
    props.game?.released,
    background.value,
  );
};

const background = computed(
  () => props.game?.background_image ?? props.entity?.image_background,
);

const noImage = computed(() =>
  props.game ? "/images/no-game.svg" : "/images/no-company.svg",
);

const id = computed(() => props.game?.id ?? props.entity?.id);
const name = computed(() => props.game?.name ?? props.entity?.name);

const showReleaseAlert = computed(() => {
  // Si es Publisher/Developer/etc., nunca hay campana
  if (!props.game) return false;

  // Fecha todavía por determinar
  if (props.game.tba) return true;

  // No tenemos fecha de lanzamiento
  if (!props.game.released) return true;

  const [year, month, day] = props.game.released.split("-").map(Number);

  const releaseDate = new Date(year, month - 1, day);

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  // Campana solamente si todavía no ha salido
  return releaseDate > today;
});

// const toggleFavorite = () => {
//   if (props.isFavorite) {
//     emit("removeFavorite", id.value);
//   } else {
//     emit(
//       "addFavorite",
//       id.value,
//       name.value,
//       props.game?.released,
//       background.value,
//     );
//   }
// };

// const isFavorite = computed(() => favoriteIds.value?.includes(props.game?.id));

// const findFavorie = computed((gameId) => isFavorite.value.find(gameId));
// const actionFavorite = (gameId) => {
//   if (findFavorie(id)) {
//     return emit("removeFavorite", gameId);
//   } else {
//     return emit("addFavorite", id, name, background);
//   }
// };

// if (props.game) {
//   const release = computed(() => props.game.released);
//   const [year, month, day] = release.value.split("-").map(Number);
//   const releaseDate = new Date(year, month - 1, day);
//   const today = new Date();
//   today.setHours(0, 0, 0, 0);
//   const isReleased = releaseDate <= today;
// }
</script>

<!-- Template antiguo sin botón de favorito -->
<!-- <template>
  <NuxtLink :to="link" class="block">
    <article
      class="flex flex-col bg-surface cursor-pointer rounded-lg h-68 w-78 transition duration-200 hover:bg-hover hover:scale-105 hover:shadow-xl"
    >
      <img
        :src="publisher.image ?? 'https://placehold.co/600x400?text=Image+Not+Found'"
        :alt="publisher.name"
        class="h-48 object-cover rounded-t-lg w-full"
      />
      <h2
        class="flex flex-1 justify-center items-center font-bold text-white text-center"
      >
        {{ publisher.name }}
      </h2>
    </article>
  </NuxtLink>
</template> -->
