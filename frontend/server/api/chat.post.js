import { GoogleGenAI } from "@google/genai";

// Configuración
// const MODEL = "gemini-3.8-flash"; // Principal candidato actual.             //SATURADO
// const MODEL = "gemini-3.7-flash"; // Alternativa Flash reciente.                //DEADLINE EXCEDED
// const MODEL = "gemini-3.5-flash"; // Ya comprobaste que responde bien.       //FUNCIONA - 20 peticiones diarias
const MODEL = "gemini-3.5-flash-lite"; // Interesante para reducir consumo.     //LA QUE MEJOR FUNCIONA
// const MODEL = "gemini-2.5-flash"; // Alternativa estable y madura.
// const MODEL = "gemini-2.5-flash-lite"; // Alternativa ligera y rápida.
const MAX_ATTEMPTS = 3;
const REQUEST_TIMEOUT = 20000;
const MAX_HISTORY_MESSAGES = 12;
const MAX_MESSAGE_LENGTH = 2000;

const SYSTEM_INSTRUCTION = `
  You are the assistant of a video game discovery platform.

  Your main purpose is to help users:
  - discover video games;
  - get video game recommendations;
  - learn about games, genres, developers, publishers and franchises;
  - compare games and help users decide what they may want to play.

  Answer in the same language as the user.

  Be useful and sufficiently detailed when the user asks for comparisons,
  summaries, recommendations, strengths, weaknesses or explanations.

  Do not invent information.
  If you are uncertain about an important factual detail, clearly express
  that uncertainty instead of guessing.

  You do not have access to the internet or web search unless external
  information is explicitly provided to you by the application.

  Never claim that you searched, checked, verified or found information online
  if no external source has actually been provided.

  If the user explicitly asks you to search, check or verify something online,
  clearly state that you cannot browse the web.

  However, lack of web access does not mean you must refuse to answer.
  If you have relevant and reasonably reliable knowledge, answer normally.

  Do not routinely mention that your answer is based on internal knowledge,
  that you lack web access, or that the information has not been externally
  verified.

  Only mention these limitations when they are materially relevant, such as when:
  - the user explicitly asks you to search, check or verify information online;
  - the user asks for current or live information;
  - you are uncertain about an important factual detail;
  - the reliability of the answer genuinely depends on external verification.

  For ordinary recommendations, comparisons, summaries, explanations and
  well-established video game information, answer directly without an
  epistemic disclaimer.

  Avoid repeating the same limitation or disclaimer in consecutive messages.
  If a limitation has already been established in the conversation, do not
  repeat it unless it becomes relevant again.

  If you do not have enough reliable information to answer, say so instead of
  inventing details.

  If the user provides factual information or cites an external source, you may
  use that information as conversational context, but do not claim that you have
  independently verified it.

  When this distinction matters, naturally indicate that the information comes
  from the user, without using a fixed or repetitive phrase.

  Do not claim to know current prices, player counts, availability, release
  dates or other live data unless that information has been provided to you.

  For factual identity and attribution questions, such as developers,
  publishers, studio names, company relationships or name changes, do not
  fill gaps with plausible-sounding details.

  If you are not sufficiently confident about an exact factual attribution,
  express uncertainty instead of guessing or inventing names, locations,
  companies or relationships.

  When the user questions or challenges a factual attribution, do not
  confidently contradict them based only on uncertain internal knowledge.

  If you are not sufficiently confident, acknowledge the uncertainty and say
  that you cannot verify the claim rather than asserting that the user is
  mistaken.

  When relying on factual information provided by the user, do not extend it
  with additional factual details, causes, companies, people, dates, locations
  or relationships unless you are sufficiently confident about those details
  independently.
  
  Do not treat plausibility or consistency as verification.
  If the user asks you to verify a claim and you cannot access an external source,
  clearly distinguish between what the user provided and what you can independently
  support.
  `;

// Pausa entre reintentos
const sleep = (ms) => {
  return new Promise((resolve) => setTimeout(resolve, ms));
};

// Obtener el código HTTP del error de Gemini
const getGeminiErrorCode = (error) => {
  const directCode = Number(error?.status ?? error?.statusCode ?? error?.code);

  if (directCode) {
    return directCode;
  }

  const errorMessage = error?.message;

  if (typeof errorMessage !== "string") {
    return null;
  }

  // El SDK puede devolver el error como JSON dentro de error.message
  try {
    const parsedError = JSON.parse(errorMessage);

    return Number(parsedError?.error?.code) || null;
  } catch {
    // Si no es JSON, intentamos identificarlo por el texto
    if (
      errorMessage.includes("RESOURCE_EXHAUSTED") ||
      errorMessage.includes("quota")
    ) {
      return 429;
    }

    if (
      errorMessage.includes("UNAVAILABLE") ||
      errorMessage.includes("high demand")
    ) {
      return 503;
    }

    if (
      errorMessage.includes("DEADLINE_EXCEEDED") ||
      errorMessage.includes("Deadline expired")
    ) {
      return 504;
    }

    return null;
  }
};

// Detectar un timeout provocado por nuestra petición
const isTimeoutError = (error) => {
  const errorMessage = error?.message?.toLowerCase() ?? "";

  return (
    error?.name === "AbortError" ||
    error?.name === "TimeoutError" ||
    error?.code === "ETIMEDOUT" ||
    errorMessage.includes("timeout") ||
    errorMessage.includes("timed out")
  );
};

// Preparar el historial para Gemini
const buildContents = (history, message) => {
  const contents = history
    .slice(-MAX_HISTORY_MESSAGES)
    .filter(
      (item) =>
        (item?.role === "user" || item?.role === "assistant") &&
        typeof item?.text === "string" &&
        item.text.trim(),
    )
    .map((item) => ({
      role: item.role === "assistant" ? "model" : "user",
      parts: [
        {
          text: item.text.trim(),
        },
      ],
    }));

  // Gemini espera que la conversación empiece por el usuario
  while (contents[0]?.role === "model") {
    contents.shift();
  }

  // Añadir el mensaje actual
  contents.push({
    role: "user",
    parts: [
      {
        text: message,
      },
    ],
  });

  return contents;
};

export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event);
  const body = await readBody(event);

  const message = typeof body?.message === "string" ? body.message.trim() : "";

  const history = Array.isArray(body?.history) ? body.history : [];

  // Validar mensaje vacío
  if (!message) {
    throw createError({
      statusCode: 400,
      statusMessage: "Message is required",
    });
  }

  // Evitar mensajes excesivamente largos
  if (message.length > MAX_MESSAGE_LENGTH) {
    throw createError({
      statusCode: 400,
      statusMessage: "Message is too long",
    });
  }

  const ai = new GoogleGenAI({
    apiKey: config.geminiApiKey,
  });

  const contents = buildContents(history, message);

  for (let attempt = 1; attempt <= MAX_ATTEMPTS; attempt++) {
    const controller = new AbortController();

    const timeoutId = setTimeout(() => {
      controller.abort();
    }, REQUEST_TIMEOUT);

    try {
      const response = await ai.models.generateContent({
        model: MODEL,

        contents,

        config: {
          abortSignal: controller.signal,

          httpOptions: {
            timeout: REQUEST_TIMEOUT,
          },

          systemInstruction: SYSTEM_INSTRUCTION,
        },
      });

      clearTimeout(timeoutId);

      return {
        message: response.text,
      };
    } catch (error) {
      clearTimeout(timeoutId);

      const errorCode = getGeminiErrorCode(error);

      const timedOut = controller.signal.aborted || isTimeoutError(error);

      console.error(
        `Gemini API error - modelo ${MODEL} - intento ${attempt}/${MAX_ATTEMPTS} - ${
          timedOut ? "TIMEOUT" : `código ${errorCode ?? "desconocido"}`
        }:`,
        error?.message ?? error,
      );

      // 429: cuota agotada / rate limit
      // No reintentamos
      if (errorCode === 429) {
        throw createError({
          statusCode: 429,
          statusMessage: "AI quota exceeded",
        });
      }

      // Errores temporales que sí reintentamos
      const isRetryable = errorCode === 503 || errorCode === 504 || timedOut;

      if (!isRetryable) {
        throw createError({
          statusCode: 502,
          statusMessage: "AI service error",
        });
      }

      // Último intento
      if (attempt === MAX_ATTEMPTS) {
        if (errorCode === 504 || timedOut) {
          throw createError({
            statusCode: 504,
            statusMessage: "AI service timeout",
          });
        }

        throw createError({
          statusCode: 503,
          statusMessage: "AI service temporarily unavailable",
        });
      }

      // Exponential backoff
      const delay = 1000 * 2 ** (attempt - 1);

      console.log(
        timedOut || errorCode === 504
          ? `Gemini ha tardado demasiado. Reintentando en ${delay / 1000}s...`
          : `Gemini no disponible. Reintentando en ${delay / 1000}s...`,
      );

      await sleep(delay);
    }
  }
});
