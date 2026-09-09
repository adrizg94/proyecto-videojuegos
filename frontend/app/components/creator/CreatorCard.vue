<template>
  <article
    class="flex justify-around flex-col bg-surface rounded-lg h-68 w-60 transition duration-200 hover:bg-hover hover:scale-105 hover:shadow-xl"
  >
    <div class="relative group w-fit mx-auto">
      <NuxtLink :to="link">
        <img
          :src="creator.image || '/images/no-avatar.svg'"
          :alt="creator.name"
          class="size-40 shrink-0 rounded-full object-cover group-hover:brightness-40 transition"
        />
      </NuxtLink>

      <button
        v-if="isAuthenticated"
        type="button"
        title="Add to favorites"
        @click="handleFavorite"
        class="absolute inset-0 z-10 m-auto w-fit h-fit opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
      >
        <FontAwesomeIcon
          icon="fa-heart"
          class="text-3xl transition hover:text-primary-light hover:scale-110"
          :class="{ 'text-primary-light': isFavorite }"
        />
      </button>
    </div>

    <NuxtLink :to="link">
      <div>
        <h2 class="font-bold text-white text-center">
          {{ creator.name }}
        </h2>

        <p class="text-sm text-text-muted text-center">
          {{ creator.positions.map((role) => role.name).join(" · ") }}
        </p>
      </div>
    </NuxtLink>
  </article>
</template>

<script setup>
const props = defineProps({
  creator: {
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

const emit = defineEmits(["toggleFavorite"]);
const { isAuthenticated } = useAuth();

const handleFavorite = () => {
  emit("toggleFavorite", props.game ?? props.entity);
};
</script>
