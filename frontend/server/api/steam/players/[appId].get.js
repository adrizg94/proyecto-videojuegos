export default defineEventHandler(async (event) => {
  const appId = getRouterParam(event, "appId");

  if (!appId) {
    throw createError({
      statusCode: 400,
      statusMessage: "Steam App ID required",
    });
  }

  try {
    return await $fetch(
      "https://api.steampowered.com/ISteamUserStats/GetNumberOfCurrentPlayers/v1/",
      {
        query: {
          appid: appId,
        },
      },
    );

    // return {
    //   appId,
    //   players: data.response.player_count,
    // };
  } catch (error) {
    console.error("Steam API error:", error);

    throw createError({
      statusCode: 502,
      statusMessage: "Steam API error",
    });
  }
});
