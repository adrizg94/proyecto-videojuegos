<template>
  <section class="flex flex-col gap-2">
    <h2 class="text-xl text-center">User Reviews</h2>
    <article
      v-for="review in visibleReviews"
      :key="review.recomendation"
      class="flex flex-col gap-2 mb-4 rounded-md bg-surface/60 px-4 py-3"
    >
      <div class="flex items-center justify-between">
        <a
          :href="review.author.profile_url"
          target="_blank"
          rel="noopener noreferrer"
          class="flex items-center gap-2 font-semibold underline hover:text-primary-light"
        >
          <img
            :src="`https://avatars.steamstatic.com/${review.author.avatar}_full.jpg`"
            :alt="review.author.personaname"
            class="size-10 rounded-full object-cover"
          />
          {{ review.author.personaname }}
        </a>
        <span
          class="text-xl"
          :class="review.voted_up ? 'text-success' : 'text-red-400'"
          :title="review.voted_up ? 'Recomended' : 'Not recomended'"
        >
          <FontAwesomeIcon
            :icon="review.voted_up ? 'fa-thumbs-up' : 'fa-thumbs-down'"
          />
        </span>
      </div>
      <div class="flex text-xs text-text-muted/70 justify-center gap-1.5">
        <span>{{
          new Date(review.timestamp_created * 1000).toLocaleDateString(
            "en-GB",
            {
              day: "numeric",
              month: "short",
              year: "numeric",
            },
          )
        }}</span>
        ·
        <span
          >{{ (review.author.playtime_at_review / 60).toFixed(1) }}h
          played</span
        >
        ·
        <span>{{ review.author.num_reviews }} reviews</span>
      </div>
      <div class="flex flex-col items-center gap-2">
        <span class="text-sm leading-relaxed text-text-muted">{{
          visibleReview(review.review)
        }}</span>
        <button
          v-if="review.review.length > maxCharReview"
          type="button"
          class="hover:bg-hover cursor-pointer rounded-sm text-sm w-fit px-2 py-1"
          @click="showFullReview = !showFullReview"
        >
          {{ showFullReview ? "Show less" : "Show more" }}
        </button>
      </div>
    </article>
    <button
      v-if="reviews.length > maxReviews"
      type="button"
      class="hover:bg-hover cursor-pointer rounded-sm"
      @click="showAllReviews = !showAllReviews"
    >
      {{ showAllReviews ? "Show less" : "Show more" }}
    </button>
  </section>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps({
  reviews: Array,
});

const showAllReviews = ref(false);
const showFullReview = ref(false);
const maxReviews = 5;
const maxCharReview = 350;

const reviewsFormatted = computed(() => {
  return props.reviews.filter(
    (review) => review.language === "spanish" || review.language === "english",
  );
});

const visibleReviews = computed(() =>
  showAllReviews.value
    ? reviewsFormatted.value
    : reviewsFormatted.value.slice(0, maxReviews),
);

const visibleReview = (review) => {
  return review.length < maxCharReview || showFullReview.value
    ? review
    : review.slice(0, maxCharReview) + "...";
};
</script>
