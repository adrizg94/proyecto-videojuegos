<template>
  <Teleport to="body">
    <div
      v-if="show"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4"
      @click.self="$emit('cancel')"
    >
      <div
        class="w-full max-w-md rounded-xl border border-white/10 bg-surface p-6 shadow-xl"
      >
        <div class="flex items-start gap-4">
          <div
            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-red-500/10 text-red-400"
          >
            <FontAwesomeIcon icon="fa-triangle-exclamation" />
          </div>

          <div>
            <h2 class="text-lg font-bold">
              {{ title }}
            </h2>

            <p class="mt-2 text-sm text-text-muted">
              {{ message }}
            </p>
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
          <button
            type="button"
            class="rounded-lg px-4 py-2 text-sm font-medium text-text-muted transition-colors hover:bg-hover hover:text-white cursor-pointer"
            @click="$emit('cancel')"
          >
            Cancel
          </button>

          <button
            type="button"
            :disabled="loading"
            class="inline-flex min-w-24 items-center justify-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white transition-opacity hover:opacity-80 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
            @click="$emit('confirm')"
          >
            <FontAwesomeIcon
              v-if="loading"
              icon="fa-spinner"
              class="animate-spin"
            />

            <FontAwesomeIcon v-else icon="fa-trash" />

            {{ confirmText }}
          </button>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
defineProps({
  show: {
    type: Boolean,
    default: false,
  },

  title: {
    type: String,
    default: "Confirm deletion",
  },

  message: {
    type: String,
    required: true,
  },

  confirmText: {
    type: String,
    default: "Delete",
  },

  loading: {
    type: Boolean,
    default: false,
  },
});

defineEmits(["confirm", "cancel"]);
</script>
