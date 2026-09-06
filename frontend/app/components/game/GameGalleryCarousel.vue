<template>
  <div
    ref="carousel"
    tabindex="-1"
    class="fixed inset-0 z-60 flex items-center justify-center bg-black/90 outline-none"
    @click.self="emit('close')"
    @keydown.esc="emit('close')"
    @keydown.left.prevent="previous"
    @keydown.right.prevent="next"
  >
    <!-- Cerrar -->
    <button
      type="button"
      class="absolute top-4 right-4 z-10 p-2 cursor-pointer text-text-muted hover:text-white transition-colors"
      aria-label="Close screenshot"
      @click="emit('close')"
    >
      <FontAwesomeIcon icon="fa-xmark" class="text-2xl" />
    </button>

    <!-- Anterior -->
    <button
      type="button"
      class="absolute left-6 top-1/2 -translate-y-1/2 z-10 p-3 cursor-pointer text-white hover:text-primary-light transition-colors"
      aria-label="Previous screenshot"
      @click="previous"
    >
      <FontAwesomeIcon icon="fa-chevron-left" class="text-4xl" />
    </button>

    <!-- Imagen -->
    <img
      :src="screenshots[currentIndex].path_full"
      :alt="`Screenshot ${screenshots[currentIndex].id}`"
      class="max-w-[85vw] max-h-[90vh] object-contain rounded-lg"
    />

    <!-- Siguiente -->
    <button
      type="button"
      class="absolute right-6 top-1/2 -translate-y-1/2 z-10 p-3 cursor-pointer text-white hover:text-primary-light transition-colors"
      aria-label="Next screenshot"
      @click="next"
    >
      <FontAwesomeIcon icon="fa-chevron-right" class="text-4xl" />
    </button>
  </div>
</template>

<script setup>
const props = defineProps({
  screenshots: {
    type: Array,
    default: () => [],
  },

  initialIndex: {
    type: Number,
    default: 0,
  },
});

const emit = defineEmits(["close"]);

const currentIndex = ref(props.initialIndex);
const carousel = ref(null);

const next = () => {
  currentIndex.value = (currentIndex.value + 1) % props.screenshots.length;
};

const previous = () => {
  currentIndex.value =
    (currentIndex.value - 1 + props.screenshots.length) %
    props.screenshots.length;
};

onMounted(() => {
  nextTick(() => {
    carousel.value?.focus();
  });
});
</script>
