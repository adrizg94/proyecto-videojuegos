export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event);
  const id = getRouterParam(event, "id");

  const creators = [];

  let page = 1;
  let nextPage;

  do {
  const data = await $fetch(
    `https://api.rawg.io/api/games/${id}/development-team`,
    {
      query: {
        key: config.rawgApiKey,
        page,
        page_size: 100,
      },
    },
  );

  creators.push(...data.results);

  nextPage = data.next;
  page++;
} while (nextPage);

  return creators;
});
