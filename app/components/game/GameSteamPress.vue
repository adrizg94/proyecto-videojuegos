<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <article
      v-for="review in reviews"
      :key="review.quote"
      class="flex flex-col justify-center gap-2 bg-surface/70 rounded-md p-2"
    >
      <p class="w-full font-semibold text-center">
        {{ review.quote }}
      </p>

      <p class="w-full text-text-muted text-center">
        {{ review.score }}
      </p>
    </article>
  </div>
</template>

<script setup>
const props = defineProps({
  reviews: {
    type: String,
    default: "",
  },
});

const reviews = computed(() => {
  if (!props.reviews) return [];

  return props.reviews
    .split(/<br\s*\/?>\s*<br\s*\/?>/i)
    .filter(Boolean)
    .map((review) => {
      const [quote, score] = review.split(/<br\s*\/?>/i);

      return {
        quote: quote?.trim(),
        score: score?.trim(),
      };
    });
});
</script>
