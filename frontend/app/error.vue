<template>
  <NuxtLayout name="default">
    <main class="w-full h-full flex items-center justify-center px-6 py-12">
      <section class="w-full max-w-xl text-center">
        <!-- Icon -->
        <div
          class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-surface text-primary"
        >
          <FontAwesomeIcon
            :icon="isNotFound ? 'fa-gamepad' : 'fa-triangle-exclamation'"
            class="text-4xl"
          />
        </div>

        <!-- Error code -->
        <p class="mt-6 text-2xl font-bold text-primary">
          {{ error.statusCode }}
        </p>

        <!-- Title -->
        <h1 class="mt-3 text-3xl font-bold">
          {{ title }}
        </h1>

        <!-- Description -->
        <p class="mt-3 text-text-muted">
          {{ description }}
        </p>

        <!-- Actions -->
        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
          <button
            type="button"
            class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white transition-opacity hover:opacity-80 cursor-pointer"
            @click="goHome"
          >
            <FontAwesomeIcon icon="fa-house" />

            Back to home
          </button>

          <button
            type="button"
            class="inline-flex items-center gap-2 rounded-lg bg-surface px-5 py-2.5 text-sm font-medium text-text-muted transition-colors hover:bg-hover hover:text-white cursor-pointer"
            @click="goCommunity"
          >
            <FontAwesomeIcon icon="fa-users" />

            Community
          </button>
        </div>
      </section>
    </main>
  </NuxtLayout>
</template>

<script setup>
const props = defineProps({
  error: {
    type: Object,
    required: true,
  },
});

const isNotFound = computed(() => {
  return props.error.statusCode === 404;
});

const title = computed(() => {
  if (isNotFound.value) {
    return "Page not found";
  }

  return "Something went wrong";
});

const description = computed(() => {
  if (isNotFound.value) {
    return "The page you're looking for doesn't exist or may have been moved.";
  }

  return "An unexpected error occurred. Please try again.";
});

const goHome = async () => {
  await clearError({
    redirect: "/",
  });
};

const goCommunity = async () => {
  await clearError({
    redirect: "/community",
  });
};
</script>
