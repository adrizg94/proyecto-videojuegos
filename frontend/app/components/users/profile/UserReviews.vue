<template>
  <section id="reviews" class="mb-12 scroll-mt-20">
    <div class="flex items-center justify-between mb-5">
      <h2 class="text-2xl font-bold">
        {{ title }}
      </h2>

      <span class="text-text-muted">
        {{ reviews.length }}
        {{ reviews.length === 1 ? "review" : "reviews" }}
      </span>
    </div>

    <div v-if="reviews.length" class="flex flex-col gap-4">
      <NuxtLink
        v-for="review in visibleReviews"
        :key="review.id"
        :to="`/games/${review.game.rawg_id}`"
        class="rounded-xl bg-surface p-5 hover:bg-hover transition-colors"
      >
        <div class="flex items-center justify-between gap-5">
          <div>
            <h3 class="font-semibold text-lg">
              {{ review.game.name }}
            </h3>

            <div class="flex gap-1 mt-1">
              <FontAwesomeIcon
                v-for="star in 5"
                :key="star"
                icon="fa-star"
                :class="
                  star <= review.rating
                    ? 'text-primary-light'
                    : 'text-text-muted'
                "
              />
            </div>
          </div>

          <span class="text-text-muted text-sm">
            {{ formatDate(review.created_at) }}
          </span>
        </div>

        <p class="mt-4 text-text-muted line-clamp-3">
          {{ review.review }}
        </p>
      </NuxtLink>
    </div>

    <p v-else class="text-text-muted">No reviews yet.</p>

    <button
      v-if="reviews.length > maxReviews"
      type="button"
      class="mt-5 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-text-muted hover:bg-hover hover:text-white transition-colors cursor-pointer"
      @click="showAllReviews = !showAllReviews"
    >
      {{ showAllReviews ? "Show less" : "Show more" }}
    </button>
  </section>
</template>

<script setup>
const props = defineProps({
  reviews: {
    type: Array,
    default: () => [],
  },

  title: {
    type: String,
    default: "My reviews",
  },
});

const showAllReviews = ref(false);
const maxReviews = 3;

const visibleReviews = computed(() => {
  return showAllReviews.value
    ? props.reviews
    : props.reviews.slice(0, maxReviews);
});

const formatDate = (date) => {
  return new Intl.DateTimeFormat("en-GB", {
    day: "numeric",
    month: "short",
    year: "numeric",
  }).format(new Date(date));
};
</script>
