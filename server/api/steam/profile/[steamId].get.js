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
      "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/",
      {
        query: {
          key: config.steamApiKey,
          steamids: steamId,
        },
      },
    );

    return data.response.players[0] ?? null;
  } catch (error) {
    console.error("Steam API error:", error);

    throw createError({
      statusCode: 502,
      statusMessage: "Steam API error",
    });
  }
});