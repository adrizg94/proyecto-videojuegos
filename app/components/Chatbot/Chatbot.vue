<template>
  <section>
    <button
      v-if="!showChat"
      type="button"
      aria-label="Gemini assistant"
      class="fixed bottom-5 right-5 z-40 flex items-center justify-center size-14 rounded-full bg-primary hover:bg-primary-light shadow-lg cursor-pointer transition-colors"
      @click="showChat = true"
    >
      <FontAwesomeIcon icon="fa-comment" class="text-3xl" />

      <FontAwesomeIcon icon="fa-robot" class="absolute text-xl text-primary" />

      <!-- <FontAwesomeIcon
        icon="fa-wand-magic-sparkles"
        class="text-3xl"
      /> -->
    </button>
    <ChatbotWindow v-if="showChat" @close="showChat = false" />
  </section>
</template>

<script setup>
const showChat = ref(false);
</script>
