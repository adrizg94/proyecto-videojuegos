<template>
  <nav v-if="showOptions">
    <NuxtLink
      to="/profile"
      @click="emit('close')"
      class="block rounded px-3 py-2 hover:bg-hover"
    >
      <FontAwesomeIcon icon="fa-id-card" />
      My profile
    </NuxtLink>

    <div class="my-1 border-t border-white/10" />

    <NuxtLink
      to="/profile/settings"
      @click="emit('close')"
      class="block rounded px-3 py-2 hover:bg-hover"
    >
      <FontAwesomeIcon icon="fa-cog" />
      Settings
    </NuxtLink>
    <button
      type="button"
      class="flex w-full items-center gap-2 px-3 py-2 text-left text-text-muted cursor-pointer hover:text-red-500"
      @click="
        emit('close');
        logout();
      "
    >
      <FontAwesomeIcon icon="fa-right-from-bracket" />
      Log out
    </button>
  </nav>
</template>

<script setup>
const propps = defineProps({
  showOptions: Boolean,
});

const emit = defineEmits(["close"]);

const { logout } = useAuth();
</script>
