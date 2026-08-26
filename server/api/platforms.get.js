export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event);

  return await $fetch("https://api.rawg.io/api/platforms", {
    query: {
      key: config.rawgApiKey,
    },
  });
});
