<template>{{ profile }}</template>

<script setup>
// const steamId = "76561198992660060";
const steamId = "2124490";

// const { data: profile } = await useFetch(`/api/steam/details/${steamId}`);
const { data: profile } = await useFetch(`/api/steam/apps`);
</script>
