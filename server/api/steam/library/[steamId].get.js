export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event);
  const steamId = getRouterParam(event, "steamId");

  if (!steamId) {
    throw createError({
      statusCode: 400,
      statusMessage: "Steam ID required",
    });
  }

  try {
    const data = await $fetch(
      "https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/",
      {
        query: {
          key: config.steamApiKey,
          steamid: steamId,
          include_appinfo: true,
          include_played_free_games: true,
        },
      },
    );

    return data.response;
  } catch (error) {
    console.error("Steam API error:", error);

    throw createError({
      statusCode: 502,
      statusMessage: "Steam API error",
    });
  }
});
