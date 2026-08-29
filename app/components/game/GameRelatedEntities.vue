<template>
  <section class="flex flex-col gap-8">
    <GameRelatedEntitiesLinks name="Developers" :links="game.developers" />
    <GameRelatedEntitiesLinks name="Publishers" :links="game.publishers" />
    <GameRelatedEntitiesLinks name="Creators" :links="creators" />
  </section>
</template>

<script setup>
const propts = defineProps({
  game: Object,
  creators: Object,
});
</script>
