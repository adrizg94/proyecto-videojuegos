export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event);
  const id = getRouterParam(event, "id");

  return await $fetch(`https://api.rawg.io/api/games/${id}/stores`, {
    query: {
      key: config.rawgApiKey,
    },
  });
});
