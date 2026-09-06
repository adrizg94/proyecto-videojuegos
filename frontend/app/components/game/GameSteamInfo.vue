<template>
  <div class="flex items-center justify-between mt-3">
    <a
      v-if="steamAppId"
      :href="`https://store.steampowered.com/app/${steamAppId}`"
      class="group flex items-center gap-1 w-fit"
    >
      <FontAwesomeIcon :icon="['fab', 'steam']" />
      <div class="group-hover:text-primary-light">
        <span v-if="details?.is_free">Free to play</span>
        <div
          v-else-if="details?.price_overview?.discount_percent > 0"
          class="flex items-center gap-2"
        >
          <span>{{ details?.price_overview?.final_formatted }}</span>
          <span class="text-text-muted line-through">
            {{ details?.price_overview?.initial_formatted }}
          </span>
          <span
            class="rounded bg-success/20 px-1.5 py-0.5 font-semibold text-success"
          >
            -{{ details?.price_overview?.discount_percent }}%
          </span>
        </div>
        <div v-else-if="details?.price_overview?.final_formatted">
          {{ details?.price_overview?.final_formatted }}
        </div>
      </div>
    </a>
    <div
      v-if="players != null"
      class="flex items-center gap-1.5 text-sm text-text-muted"
    >
      <FontAwesomeIcon icon="fa-users" /><span
        class="size-2 rounded-full bg-success"
      ></span>

      <span class="text-white font-semibold">{{ formattedPlayers }}</span
      ><span class="relative -top-px">players</span>
    </div>
  </div>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
const props = defineProps({
  details: {
    type: Object,
    default: null,
  },
  steamAppId: {
    type: String,
    default: null,
  },
  players: {
    type: Number,
    default: null,
  },
});

const formattedPlayers = computed(() => {
  if (props.players == null) return null;

  return new Intl.NumberFormat("en", {
    notation: "compact",
    maximumFractionDigits: 1,
  }).format(props.players);
});
</script>
