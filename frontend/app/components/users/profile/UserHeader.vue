<template>
  <section
    class="flex items-center justify-between rounded-2xl bg-surface p-6 mb-10"
  >
    <div>
      <p class="text-text-muted text-sm">
        {{ isPrivate ? "My profile" : "Profile" }}
      </p>

      <h1 class="text-3xl font-bold">
        {{ user.username }}
      </h1>

      <p v-if="user.created_at" class="text-text-muted mt-1">
        Member since {{ formatDate(user.created_at) }}
      </p>
    </div>

    <NuxtLink
      v-if="isPrivate"
      to="/profile/settings"
      class="rounded-lg px-4 py-2 bg-hover hover:bg-primary transition-colors"
    >
      Settings
    </NuxtLink>
  </section>
</template>

<script setup>
const props = defineProps({
  user: {
    type: Object,
    required: true,
  },

  isPrivate: {
    type: Boolean,
    default: false,
  },
});

const formatDate = (date) => {
  return new Intl.DateTimeFormat("en-GB", {
    day: "numeric",
    month: "short",
    year: "numeric",
  }).format(new Date(date));
};
</script>
