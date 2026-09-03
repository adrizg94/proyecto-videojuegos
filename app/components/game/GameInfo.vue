<!-- Descripción, duración, información general -->
<template>
  <section class="flex flex-col gap-2 text-white pb-10">
    <h2 class="font-bold text-2xl">About</h2>
    <p>{{ visibleDescription }}</p>
    <button
      class="hover:bg-hover cursor-pointer rounded-sm"
      v-if="description.length > maxCharDescription"
      type="button"
      @click="showFullDescription = !showFullDescription"
    >
      {{ showFullDescription ? "Show less" : "Show more" }}
    </button>
    <div class="flex items-center justify-between mt-3">
      <a
        v-if="steamAppId"
        :href="`https://store.steampowered.com/app/${steamAppId}`"
        class="group flex items-center gap-1 w-fit"
      >
        <FontAwesomeIcon :icon="['fab', 'steam']" />
        <div class="group-hover:text-primary-light">
          <span v-if="details.is_free">Free to play</span>
          <div
            v-else-if="details.price_overview.initial_formatted"
            class="flex items-center gap-2"
          >
            <span>{{ details.price_overview.final_formatted }}</span>
            <span
              v-if="details.price_overview.discount_percent"
              class="text-text-muted line-through"
            >
              {{ details.price_overview.initial_formatted }}
            </span>
            <span
              v-if="details.price_overview.discount_percent"
              class="rounded bg-success/20 px-1.5 py-0.5 font-semibold text-success"
            >
              -{{ details.price_overview.discount_percent }}%
            </span>
          </div>
          <div v-else>{{ details.price_overview.final_formatted }}</div>
        </div>
      </a>
      <div class="flex items-center gap-1.5 text-sm text-text-muted">
        <FontAwesomeIcon icon="fa-users" /><span
          class="size-2 rounded-full bg-success"
        ></span>
        
        <span class="text-white font-semibold">{{ formattedPlayers }}</span
        ><span class="relative -top-px">players</span>
      </div>
      <!-- <p class="flex items-center gap-2 text-sm text-text-muted"><FontAwesomeIcon icon="fa-signal" /> online: {{ players.toLocaleString("en-US") }}</p> -->
    </div>
  </section>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps({
  description: String,
  isHtml: {
    type: Boolean,
    default: false,
  },
  details: Object,
  steamAppId: String,
  players: Number,
});

const showFullDescription = ref(false);

const maxCharDescription = 500;

const visibleDescription = computed(() => {
  return props.description.length < maxCharDescription ||
    showFullDescription.value
    ? props.description
    : props.description.slice(0, maxCharDescription) + "...";
});

const formattedPlayers = computed(() => {
  return new Intl.NumberFormat("en", {
    notation: "compact",
    maximumFractionDigits: 1,
  }).format(props.players);
});
</script>

<!-- Versión con composable useShowMore -->
<!-- <template>
  <section class="flex flex-col gap-2 text-white pb-10">
    <h1 class="font-bold text-2xl">About</h1>
    <p>{{ visibleContent }}</p>
    <button
    class="hover:bg-hover cursor-pointer rounded-sm"
    v-if="hasMore"
    type="button"
    @click="toggleShowMore"
    >
    {{ showAll ? "Show less" : "Show more" }}
  </button>
</section>
</template>

<script setup>
const props = defineProps({
  description: String,
});

const maxCharDescription = 500;
const descriptionContent = computed(() => props.description);

const { showAll, hasMore, visibleContent, toggleShowMore } = useShowMore(
  descriptionContent,
  maxCharDescription,
  );
</script> -->
