<template>
  <header class="flex items-center gap-4 mb-5">
    <h1 class="font-bold text-4xl">
      {{ entity.name }}
    </h1>
    <button
      v-if="isAuthenticated"
      type="button"
      title="Add to favorites"
      @click="handleFavorite"
      class="cursor-pointer"
    >
      <FontAwesomeIcon
        icon="fa-heart"
        class="text-4xl transition hover:text-primary-light hover:scale-110"
        :class="{ 'text-primary-light': isFavorite }"
        aria-label="Add creator to favorites"
      />
    </button>
  </header>
</template>

<script setup>
const props = defineProps({
  entity: {
    type: Object,
    default: null,
  },
  isFavorite: {
    type: Boolean,
    default: false,
  },
});

const { isAuthenticated } = useAuth();

const emit = defineEmits(["toggleFavorite"]);

const handleFavorite = () => {
  emit("toggleFavorite", props.entity);
};
</script>
