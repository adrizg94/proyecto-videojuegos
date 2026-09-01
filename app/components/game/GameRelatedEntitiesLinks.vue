<template>
  <section>
    <h3 class="p-1 text-center text-lg border-b-2 border-surface mb-1">
      {{ name }}
    </h3>
    <ul>
      <li
        v-for="link in visibleLinks"
        :key="link.id"
        class="grid grid-cols-[1fr_auto] w-full my-2 hover:bg-hover transition-colors cursor-pointer rounded-lg"
      >
        <NuxtLink
          :to="`/${name.toLowerCase()}/${link.id}`"
          class="flex items-center gap-3 p-2"
        >
          <img
            :src="link.image_background || fallbackImage"
            :alt="link.name"
            class="size-16 shrink-0 rounded-full object-cover"
          />
          {{ link.name }}
        </NuxtLink>
        <button
          type="button"
          class="cursor-pointer mr-4"
          aria-label="Add to favorites"
        >
          <FontAwesomeIcon
            icon="fa-heart"
            class="text-2xl transition hover:text-primary-light hover:scale-110"
          />
        </button>
      </li>
    </ul>
    <button
      v-if="normalizedLinks.length > maxLinks"
      type="button"
      @click="showAllLinks = !showAllLinks"
      class="hover:bg-hover cursor-pointer rounded-sm mx-auto px-2 w-full my-2"
      :aria-expanded="showAllLinks"
    >
      {{ showAllLinks ? "Show less" : "Show more" }}
    </button>
  </section>
</template>

<script setup>
const props = defineProps({
  name: String,
  links: Array,
});

const normalizedLinks = computed(() => {
  return props.links.map((link) => {
    if (props.name === "Creators") {
      return {
        ...link,
        image_background: link.image,
      };
    }
    return link;
  });
});

const fallbackImage = computed(() =>
  props.name === "Creators"
    ? "/images/no-avatar.svg"
    : "/images/no-company.svg",
);

const showAllLinks = ref(false);
const maxLinks = 3;

const visibleLinks = computed(() =>
  showAllLinks.value
    ? normalizedLinks.value
    : normalizedLinks.value.slice(0, maxLinks),
);
</script>
