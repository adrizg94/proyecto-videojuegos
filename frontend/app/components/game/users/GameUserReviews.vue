<template>
  <section class="flex flex-col gap-2">
    <h2 class="text-xl text-center">User Reviews</h2>
    <form
      v-if="
        (isAuthenticated && !isReviewPublished) ||
        (isAuthenticated && editReview)
      "
      @submit.prevent="submitReview"
    >
      <div class="flex flex-col items-end gap-1">
        <div class="flex gap-1 mb-2">
          <button
            v-for="star in 5"
            :key="star"
            type="button"
            @mouseenter="hoverRating = star"
            @mouseleave="hoverRating = 0"
            @click="rating = star"
            class="cursor-pointer"
          >
            <FontAwesomeIcon
              icon="fa-star"
              class="text-xl transition-colors"
              :class="
                star <= (hoverRating || rating)
                  ? 'text-yellow-400'
                  : 'text-gray-500'
              "
            />
          </button>
        </div>
        <textarea
          v-model="review"
          placeholder="Write your review..."
          class="w-full min-h-32 p-3 rounded-lg bg-surface border border-white/10 resize-y focus:outline-none focus:ring-1 focus:ring-primary"
        />
        <button
          type="submit"
          class="w-fit mx-2 font-semibold text-md px-2.5 py-1.5 my-2 rounded-lg hover:cursor-pointer bg-primary-light hover:bg-primary transition-colors"
        >
          Publish
        </button>
      </div>
    </form>
    <template v-for="(review, index) in visibleReviews" :key="review.id">
      <article
        v-if="index !== 0 || !editReview"
        class="relative flex flex-col gap-2 mb-4 rounded-md bg-surface/60 px-4 py-3"
      >
        <div class="flex items-center justify-between">
          <div
            class="flex items-center gap-2 font-semibold underline hover:text-primary-light"
          >
            {{ review.user.username }}
          </div>
          <span>
            <FontAwesomeIcon
              v-for="star in 5"
              icon="fa-star"
              class="text-xl transition-colors"
              :class="{ 'text-yellow-400': star <= review.rating }"
            />
          </span>
        </div>
        <div class="flex text-xs text-text-muted/70 justify-center gap-1.5">
          <span>{{
            new Date(review.created_at).toLocaleDateString("en-GB", {
              day: "numeric",
              month: "short",
              year: "numeric",
            })
          }}</span>
        </div>
        <div class="flex flex-col items-center gap-2">
          <span
            class="w-full wrap-break-word text-left text-sm leading-relaxed text-text-muted"
            >{{ visibleReview(review.review) }}</span
          >
          <button
            v-if="review.review.length > maxCharReview"
            type="button"
            class="hover:bg-hover cursor-pointer rounded-sm text-sm w-fit px-2 py-1"
            @click="showFullReview = !showFullReview"
          >
            {{ showFullReview ? "Show less" : "Show more" }}
          </button>
          <div
            v-if="index === 0 && isReviewPublished"
            class="w-full flex justify-end"
          >
            <button
              type="button"
              class="w-fit mx-2 font-semibold text-md px-2.5 py-1.5 my-2 rounded-lg hover:cursor-pointer bg-primary-light hover:bg-primary transition-colors"
              @click="startEditReview"
            >
              Edit review
            </button>
            <button
              type="button"
              title="Delete review"
              class="w-fit mx-2 font-semibold text-md px-2.5 py-1.5 my-2 rounded-lg hover:cursor-pointer bg-red-700 hover:bg-red-800 transition-colors"
              @click="deleteReview"
            >
              <FontAwesomeIcon icon="fa-trash" />
            </button>
          </div>
        </div>
      </article>
    </template>
    <Toast />
  </section>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps({
  game: Object,
});

const config = useRuntimeConfig();

const maxReviews = 5;
const maxCharReview = 350;

const rating = ref(0);
const hoverRating = ref(0);
const review = ref("");

const showAllReviews = ref(false);
const showFullReview = ref(false);

const editReview = ref(false);

const { apiFetch } = useApi();
const { showToast } = useToast();
const { user, isAuthenticated } = useAuth();

const { data: reviews, refresh: refreshReviews } = await useAsyncData(
  `reviews-${props.game.id}`,
  () => apiFetch(`reviews?rawg_id=${props.game.id}`),
  {
    default: () => [],
  },
);

const addReview = async () => {
  try {
    const response = await apiFetch("reviews", {
      method: "POST",

      body: {
        rawg_id: props.game?.id,
        name: props.game?.name,
        release_date: props.game?.released ?? null,
        image: props.game?.background_image ?? null,
        rating: rating.value,
        review: review.value,
      },
    });

    showToast(response.message);
  } catch (error) {
    showToast(`${error.data?.message ?? "Error at create review"}`);
  }
};

const updateReview = async () => {
  try {
    const response = await apiFetch(`reviews/${userReview.value.id}`, {
      method: "PUT",

      body: {
        rating: rating.value,
        review: review.value,
      },
    });

    showToast(response.message);
  } catch (error) {
    showToast(`${error.data?.message ?? "Error at update review"}`);
  }
};

const deleteReview = async () => {
  try {
    const response = await apiFetch(`reviews/${userReview.value.id}`, {
      method: "DELETE",
    });

    review.value = "";
    rating.value = 0;
    await refreshReviews();
    showToast(response.message);
  } catch (error) {
    showToast(`${error.data?.message ?? "Error at update review"}`);
  }
};

const userReview = computed(() => {
  if (!user.value) return null;

  return (
    reviews.value.find((review) => review.user?.id === user.value.id) ?? null
  );
});

const sortedReviews = computed(() => {
  if (!userReview.value) {
    return reviews.value;
  }

  const otherReviews = reviews.value.filter(
    (review) => review.id !== userReview.value.id,
  );

  return [userReview.value, ...otherReviews];
});

//Comprobamos si el usuario tiene review publicada
const isReviewPublished = computed(() => {
  return userReview.value !== null;
});

const visibleReviews = computed(() =>
  showAllReviews.value
    ? sortedReviews.value
    : sortedReviews.value.slice(0, maxReviews),
);

const startEditReview = () => {
  if (!userReview.value) return;

  review.value = userReview.value.review;
  rating.value = userReview.value.rating;

  editReview.value = true;
};

const visibleReview = (review) => {
  return review.length < maxCharReview || showFullReview.value
    ? review
    : review.slice(0, maxCharReview) + "...";
};

const submitReview = async () => {
  if (editReview.value) {
    await updateReview();
  } else {
    await addReview();
  }
  editReview.value = false;
  await refreshReviews();
};
</script>
