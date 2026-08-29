<template>
  <article
    class="flex flex-col bg-surface cursor-pointer rounded-lg h-68 w-78 transition duration-200 hover:bg-hover hover:scale-105 hover:shadow-xl"
  >
    <div class="relative group w-full mx-auto">
      <NuxtLink :to="link">
        <img
          :src="
            entity.image_background ??
            'https://placehold.co/600x400?text=Image+Not+Found'
          "
          :alt="entity.name"
          class="h-48 object-cover rounded-t-lg w-full group-hover:brightness-40 transition"
        />
      </NuxtLink>

      <button
        type="button"
        class="absolute inset-0 z-10 m-auto w-fit h-fit opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
      >
        <FontAwesomeIcon
          icon="fa-heart"
          class="text-3xl transition-colors hover:text-primary-light"
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
