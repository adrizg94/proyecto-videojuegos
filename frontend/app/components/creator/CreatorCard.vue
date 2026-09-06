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
        type="button"
        class="absolute inset-0 z-10 m-auto w-fit h-fit opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
      >
        <FontAwesomeIcon
          icon="fa-heart"
          class="text-3xl transition hover:text-primary-light hover:scale-110"
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
  creator: Object,
  link: String,
});
</script>

<!-- Template con button dentro de NuxtLink, borrar despues de testear el otro -->
<!-- <template>
  <NuxtLink :to="link" class="block">
    <article
      class="flex justify-around flex-col bg-surface cursor-pointer rounded-lg h-68 w-60 transition duration-200 hover:bg-hover hover:scale-105 hover:shadow-xl"
    >
      <div class="relative group w-fit mx-auto">
        <img
          :src="
            creator.image ?? 'https://placehold.co/600x400?text=Image+Not+Found'
          "
          :alt="name"
          class="size-40 shrink-0 rounded-full object-cover mx-auto group-hover:brightness-40 transition"
        />
        <button
          class="absolute inset-0 z-10 m-auto w-fit h-fit opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
        >
          <FontAwesomeIcon
            icon="fa-heart"
            class="text-3xl hover:text-primary-light"
          />
        </button>
      </div>
      <div>
        <h2
          class="flex justify-center items-center font-bold text-white text-center"
        >
          {{ creator.name }}
        </h2>
        <div class="flex gap-2 justify-center text-text-muted">
          <p v-for="(roles, index) in creator.positions" :key="roles.id">
            <span class="text-sm">
              {{
                index === creator.positions.length - 1
                  ? roles.name
                  : `${roles.name} ·`
              }}
            </span>
          </p>
        </div>
      </div>
    </article>
  </NuxtLink>
</template> -->
