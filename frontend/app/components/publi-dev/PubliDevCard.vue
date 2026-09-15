<template>
  <article
    class="flex flex-col bg-surface cursor-pointer rounded-lg h-68 w-78 transition duration-200 hover:bg-hover hover:scale-105 hover:shadow-xl"
  >
    <div class="relative group w-full mx-auto">
      <NuxtLink :to="link">
        <img
          :src="entity.image_background || '/images/no-company.svg'"
          :alt="entity.name"
          class="h-48 rounded-t-lg w-full group-hover:brightness-40 transition"
          :class="
            entity.image_background ? 'object-cover' : 'object-contain p-8'
          "
        />
      </NuxtLink>

      <button
        type="button"
        class="absolute inset-0 z-10 m-auto w-fit h-fit opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
      >
        <FontAwesomeIcon
          icon="fa-heart"
          class="text-3xl transition hover:text-primary-light hover:scale-110"
        />
      </button>
    </div>
    <NuxtLink :to="link" class="flex-1 flex items-center justify-center">
      <h2 class="font-bold">
        {{ entity.name }}
      </h2>
    </NuxtLink>
  </article>
</template>

<script setup>
const props = defineProps({
  entity: Object,
  link: String,
});
</script>
