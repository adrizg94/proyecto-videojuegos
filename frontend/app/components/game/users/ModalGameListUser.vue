<template>
  <Teleport to="body">
    <div
      class="fixed inset-0 z-100 flex items-center justify-center bg-black/60"
      @click.self="emit('close')"
    >
      <form
        class="w-80 rounded-xl bg-surface p-5 shadow-xl"
        @submit.prevent="createList"
      >
        <h2 class="mb-4 text-lg font-semibold">
          Create new list
        </h2>

        <input
          v-model="title"
          type="text"
          placeholder="List name"
          class="w-full rounded-lg border border-white/10 bg-background p-2 focus:outline-none focus:ring-1 focus:ring-primary"
        />

        <div class="mt-5 flex justify-end gap-2">
          <button
            type="button"
            class="rounded-lg px-3 py-2 hover:bg-hover cursor-pointer"
            @click="emit('close')"
          >
            Cancel
          </button>

          <button
            type="submit"
            class="rounded-lg bg-primary-light px-3 py-2 font-semibold hover:bg-primary cursor-pointer"
          >
            Create
          </button>
        </div>
      </form>
    </div>
  </Teleport>
</template>

<script setup>
const emit = defineEmits(["close", "created"]);

const { apiFetch } = useApi();
const { showToast } = useToast();

const title = ref("");

const createList = async () => {
  try {
    const response = await apiFetch("game-lists", {
      method: "POST",
      body: {
        title: title.value,
      },
    });

    showToast(response.message);

    title.value = "";

    emit("created");
    emit("close");
  } catch (error) {
    showToast(
      `ERROR: ${
        error.data?.message ??
        error.message ??
        "Error creating game list"
      }`,
    );
  }
};
</script>