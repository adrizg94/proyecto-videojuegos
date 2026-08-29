export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event);
  const query = getQuery(event);

  return await $fetch("https://api.rawg.io/api/creators", {
    query: {
      ...query,
      key: config.rawgApiKey,
    },
  });
});
