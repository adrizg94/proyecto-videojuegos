<!-- screenshots, imágenes, trailers -->
<template>
  <section class="grid grid-cols-2 py-8 gap-5">
    <video
      v-if="trailer"
      controls
      :poster="trailer.poster"
      :src="trailer.trailer"
      class="col-span-2 w-full aspect-video object-cover rounded-lg"
    >
      Video not found
    </video>
    <div
      v-for="(screenshot, index) in screenshots.slice(0, 4)"
      :key="screenshot.id"
      class="relative group"
    >
      <img
        :src="screenshot.image"
        :alt="`Screenshot ${screenshot.id}`"
        class="w-full h-40 object-cover rounded-lg transition cursor-pointer hover:scale-110"
        @click="openScreenshot(index)"
        :class="{
          'brightness-30 group-hover:brightness-40': index === 3,
        }"
      />
      <button
        v-if="index === 3"
        type="button"
        @click="galleryModalOn = !galleryModalOn"
        class="absolute inset-0 z-10 transition cursor-pointer text-lg"
      >
        View All
      </button>
    </div>
  </section>
  <GameGalleryModal
    v-if="galleryModalOn"
    :screenshots="screenshots"
    @close-gallery="galleryModalOn = !galleryModalOn"
  />
  <GameGalleryCarousel
    v-if="selectedIndex !== null"
    :screenshots="screenshots"
    :initial-index="selectedIndex"
    @close="selectedIndex = null"
  />
</template>

<script setup>
const props = defineProps({
  screenshots: Array,
  trailer: Object,
});

const galleryModalOn = ref(false);
const selectedIndex = ref(null);

const openScreenshot = (index) => {
  if (index < 3) {
    selectedIndex.value = index;
  }
};
</script>

<!-- Videos e imagenes de rawg, cambiar, si hay video video en grande,
sino primera imagen en grande -->
<!-- <video
  v-if="trailer"
  :src="trailer"
  controls
  class="col-span-2 w-full aspect-video object-cover rounded-lg"
>
  Video not compatible
</video> -->
<!-- <img
  v-for="(screenshot, index) in screenshots.slice(0, 3)"
  :key="screenshot.id"
  :src="screenshot.image"
  :alt="`Screenshot ${screenshot.id}`"
  :class="
    index === 0
      ? 'col-span-2 w-full h-80 object-cover rounded-lg'
      : 'w-full h-40 object-cover rounded-lg'
  "
/> -->

<!-- <div
  v-for="(screenshot, index) in screenshots.slice(0, 4)"
  :key="screenshot.id"
  class="relative group"
>
  <img
    :src="screenshot.image"
    :alt="`Screenshot ${screenshot.id}`"
    class="w-full h-40 object-cover rounded-lg transition cursor-pointer"
    :class="index === 3 ? 'group-hover:brightness-30' : 'hover:scale-110'"
  />
  <button
    v-if="index === 3"
    type="button"
    class="absolute inset-0 z-10 opacity-0 group-hover:opacity-100 transition cursor-pointer text-lg fontbo"
  >
    View All
  </button>
</div> -->
