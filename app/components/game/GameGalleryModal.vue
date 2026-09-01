<template>
  <div
    class="fixed inset-0 z-50 flex justify-center items-center bg-black/60"
    @click.self="emit('closeGallery')"
  >
    <section
      ref="modal"
      tabindex="-1"
      @keydown.esc="emit('closeGallery')"
      class="flex justify-around flex-wrap gap-x-6 gap-y-8 w-full max-w-245 max-h-214 bg-surface rounded-xl p-6 overflow-y-auto custom-scrollbar"
    >
      <button
        type="button"
        class="absolute top-4 right-4 z-10 p-2 cursor-pointer text-text-muted hover:text-white transition-colors"
        aria-label="Close gallery"
        @click="emit('closeGallery')"
      >
        <FontAwesomeIcon icon="fa-xmark" class="text-2xl" />
      </button>
      <img
        v-for="(screenshot, index) in screenshots"
        :key="screenshot.id"
        :src="screenshot.image"
        :alt="`Screenshot ${screenshot.id}`"
        @click="selectedIndex = index"
        class="w-110 object-cover rounded-lg transition cursor-pointer hover:brightness-80"
      />
    </section>
    <GameGalleryCarousel
      v-if="selectedIndex !== null"
      :screenshots="screenshots"
      :initial-index="selectedIndex"
      @close="selectedIndex = null"
    />
  </div>
</template>

<script setup>
defineProps({
  screenshots: Array,
});

const emit = defineEmits(["closeGallery"]);

const modal = ref(null);
const selectedIndex = ref(null);

onMounted(() => {
  nextTick(() => {
    modal.value?.focus();
  });
});
</script>
