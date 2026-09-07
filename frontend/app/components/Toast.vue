<script setup>
const { toast } = useToast();
</script>

<template>
  <Transition
    enter-active-class="transition duration-200"
    enter-from-class="opacity-0 translate-y-2"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition duration-200"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="toast.show"
      class="fixed bottom-20 left-1/2 -translate-x-1/2 z-50 rounded-lg bg-surface px-5 py-3 shadow-xl"
    >
      {{ toast.message }}
    </div>
  </Transition>
</template>
