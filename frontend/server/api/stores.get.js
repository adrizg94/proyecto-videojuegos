export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event);

  return await $fetch("https://api.rawg.io/api/stores", {
    query: {
      key: config.rawgApiKey,
    },
  });
});
