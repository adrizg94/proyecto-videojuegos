import { GoogleGenAI } from "@google/genai";

export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event);
  const body = await readBody(event);

  const message = typeof body?.message === "string" ? body.message.trim() : "";

  if (!message) {
    throw createError({
      statusCode: 400,
      statusMessage: "Message is required",
    });
  }

  // Evitar mensajes largos
  if (message.length > 2000) {
    throw createError({
      statusCode: 400,
      statusMessage: "Message is too long",
    });
  }

  const ai = new GoogleGenAI({
    apiKey: config.geminiApiKey,
  });

  try {
    const response = await ai.models.generateContent({
      model: "gemini-3.5-flash",

      contents: message,

      config: {
        systemInstruction: `
          You are the assistant of a video game discovery platform.

          Your main purpose is to help users:
          - discover video games;
          - get video game recommendations;
          - learn about games, genres, developers, publishers and franchises;
          - decide what games they may want to play.

          Answer in the same language as the user.

          Be concise and useful.

          Do not invent information.
          If you do not have enough information to answer reliably, say so.

          Do not claim to know current prices, player counts, release dates
          or other live data unless that information has been provided to you.
        `,
      },
    });

    return {
      message: response.text,
    };
  } catch (error) {
    console.error("Gemini API error:", error?.message ?? error);

    throw createError({
      statusCode: 503,
      statusMessage: "AI service temporarily unavailable",
    });
  }
});
