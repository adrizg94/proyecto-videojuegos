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

  const maxAttempts = 3;

  for (let attempt = 1; attempt <= maxAttempts; attempt++) {
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
      console.error(
        `Gemini API error - intento ${attempt}/${maxAttempts}:`,
        error?.message ?? error,
      );

      const is503 =
        error?.status === 503 ||
        error?.statusCode === 503 ||
        error?.code === 503 ||
        error?.code === "503" ||
        error?.message?.includes("503") ||
        error?.message?.includes("UNAVAILABLE") ||
        error?.message?.includes("high demand");

      // Si no es un 503, no tiene sentido reintentarlo
      if (!is503) {
        throw createError({
          statusCode: 500,
          statusMessage: "AI service error",
        });
      }

      // Si ya hemos agotado los intentos, devolver error
      if (attempt === maxAttempts) {
        throw createError({
          statusCode: 503,
          statusMessage: "AI service temporarily unavailable",
        });
      }

      // Espera exponencial:
      // intento 1 -> 1 segundo
      // intento 2 -> 2 segundos
      const delay = 1000 * 2 ** (attempt - 1);

      await new Promise((resolve) => setTimeout(resolve, delay));
    }
  }
});
