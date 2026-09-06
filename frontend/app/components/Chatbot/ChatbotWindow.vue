<template>
  <section
    class="flex flex-col fixed bottom-5 right-5 z-50 bg-hover w-110 h-125 rounded-lg shadow-2xl overflow-hidden"
  >
    <!-- Header -->
    <header
      class="relative flex justify-center gap-2 items-center bg-primary-light h-10 text-lg p-2"
    >
      <div class="flex items-center gap-2">
        <FontAwesomeIcon icon="fa-robot" />
        <span class="font-semibold">Asistente virtual</span>
      </div>
      <button
        type="button"
        aria-label="Close chat"
        @click="emit('close')"
        class="absolute right-2 cursor-pointer hover:bg-primary rounded-md p-1 transition-colors"
      >
        <FontAwesomeIcon icon="fa-xmark" />
      </button>
    </header>

    <!-- Mensajes -->
    <div
      ref="messagesContainer"
      class="flex-1 flex flex-col gap-3 p-3 overflow-y-auto custom-scrollbar"
    >
      <div
        v-for="(message, index) in messages"
        :key="index"
        :class="[
          'max-w-[85%] rounded-lg px-3 py-2',
          message.role === 'user'
            ? 'self-end bg-primary'
            : 'self-start bg-surface',
        ]"
      >
        <div
          v-if="message.role === 'assistant'"
          v-html="renderMarkdown(message.text)"
        ></div>
        <p v-else>
          {{ message.text }}
        </p>
      </div>
      <!-- Mensaje de carga -->
      <div
        v-if="isLoading"
        aria-label="Pensando"
        class="self-start flex items-center gap-1 bg-surface rounded-lg px-3 py-3"
      >
        <span class="w-2 h-2 rounded-full bg-current animate-bounce"></span>

        <span
          class="w-2 h-2 rounded-full bg-current animate-bounce [animation-delay:150ms]"
        ></span>

        <span
          class="w-2 h-2 rounded-full bg-current animate-bounce [animation-delay:300ms]"
        ></span>
      </div>
    </div>

    <!-- Formulario -->
    <form
      @submit.prevent="sendMessage"
      class="relative flex gap-2 p-3 border-t border-hover"
    >
      <input
        type="text"
        v-model="chatText"
        aria-label="Message"
        placeholder="Escribe aquí tu consulta..."
        class="flex-1 focus:outline-none focus:ring-3 focus:ring-primary bg-white text-black max-h-10 p-2"
      />
      <button
        type="submit"
        aria-label="Send message"
        class="absolute right-5 top-5 text-primary-light hover:text-primary cursor-pointer"
      >
        <FontAwesomeIcon icon="fa-paper-plane" />
      </button>
    </form>
  </section>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import MarkdownIt from "markdown-it";

const emit = defineEmits(["close"]);

const md = new MarkdownIt({
  html: false,
  breaks: true,
  linkify: true,
});

const renderMarkdown = (text) => {
  return md.render(text);
};

const chatText = ref("");
const isLoading = ref(false);

const messagesContainer = ref(null);

const scrollToBottom = async () => {
  await nextTick();

  const container = messagesContainer.value;

  if (container) {
    container.scrollTo({
      top: container.scrollHeight,
      behavior: "smooth",
    });
  }
};

const messages = ref([
  {
    role: "assistant",
    text: "¡Hola! 👋 Soy tu asistente virtual de videojuegos impulsado por IA. Puedo hacer recomendaciones personalizadas, responder preguntas sobre videojuegos y la industria o ayudarte a descubrir nuevos títulos.\n¿En qué puedo ayudarte hoy?",
  },
]);

// Mandar los mensajes del usuario a la API de Gemini
const sendMessage = async () => {
  const text = chatText.value.trim();

  if (!text || isLoading.value) return;

  // Preparar el historial ANTES de añadir el mensaje actual
  const history = messages.value
    .slice(1) // Ignorar el saludo inicial del asistente
    .filter((message) => !message.isError) // No enviar errores como contexto
    .slice(-12) // Máximo 12 mensajes anteriores
    .map((message) => ({
      role: message.role,
      text: message.text,
    }));

  // Añadir el mensaje actual a la interfaz
  messages.value.push({
    role: "user",
    text,
  });

  chatText.value = "";
  isLoading.value = true;

  // Llevar el scroll al mensaje nuevo
  await scrollToBottom();

  try {
    const response = await $fetch("/api/chat", {
      method: "POST",
      body: {
        message: text,
        history,
      },
    });

    messages.value.push({
      role: "assistant",
      text: response.message,
    });
  } catch (error) {
    console.error("Chat error:", error);

    const statusCode =
      error?.statusCode ??
      error?.status ??
      error?.response?.status ??
      error?.data?.statusCode;

    let errorMessage;

    switch (statusCode) {
      case 400:
        errorMessage = "El mensaje no es válido o es demasiado largo.";
        break;

      case 429:
        errorMessage =
          "He alcanzado el límite de consultas de IA. Inténtalo más tarde.";
        break;

      case 502:
        errorMessage =
          "Se ha producido un problema al comunicarme con el servicio de IA. Inténtalo de nuevo.";
        break;

      case 503:
        errorMessage =
          "El servicio de IA está saturado en este momento. Inténtalo de nuevo más tarde.";
        break;

      case 504:
        errorMessage =
          "El servicio de IA está tardando demasiado en responder. Inténtalo de nuevo más tarde.";
        break;

      default:
        errorMessage =
          "No he podido responder en este momento. Inténtalo de nuevo.";
    }

    messages.value.push({
      role: "assistant",
      text: errorMessage,
      isError: true,
    });
  } finally {
    isLoading.value = false;
  }
};

// sendMessage sin memoria
// const sendMessage = async () => {
//   const text = chatText.value.trim();

//   if (!text || isLoading.value) return;

//   messages.value.push({
//     role: "user",
//     text,
//   });

//   chatText.value = "";
//   isLoading.value = true;

//   await scrollToBottom();

//   //   try {
//   //     const response = await $fetch("/api/chat", {
//   //       method: "POST",
//   //       body: {
//   //         message: text,
//   //       },
//   //     });

//   //     messages.value.push({
//   //       role: "assistant",
//   //       text: response.message,
//   //     });
//   //   } catch (error) {
//   //     messages.value.push({
//   //       role: "assistant",
//   //       text: "No he podido responder en este momento. Inténtalo de nuevo.",
//   //     });
//   //   } finally {
//   //     isLoading.value = false;
//   //   }
//   try {
//     const response = await $fetch("/api/chat", {
//       method: "POST",
//       body: {
//         message: text,
//       },
//     });

//     messages.value.push({
//       role: "assistant",
//       text: response.message,
//     });
//   } catch (error) {
//     console.error("Chat error:", error);

//     const statusCode =
//       error?.statusCode ??
//       error?.status ??
//       error?.response?.status ??
//       error?.data?.statusCode;

//     let errorMessage;

//     switch (statusCode) {
//       case 400:
//         errorMessage = "El mensaje no es válido o es demasiado largo.";
//         break;

//       case 429:
//         errorMessage =
//           "He alcanzado el límite de consultas de IA. Inténtalo más tarde.";
//         break;

//       case 502:
//         errorMessage =
//           "Se ha producido un problema al comunicarme con el servicio de IA. Inténtalo de nuevo.";
//         break;

//       case 503:
//         errorMessage =
//           "El servicio de IA está saturado en este momento. Inténtalo de nuevo más tarde.";
//         break;

//       case 504:
//         errorMessage =
//           "El servicio de IA está tardando demasiado en responder. Inténtalo de nuevo más tarde.";
//         break;

//       default:
//         errorMessage =
//           "No he podido responder en este momento. Inténtalo de nuevo.";
//     }

//     messages.value.push({
//       role: "assistant",
//       text: errorMessage,
//     });
//   } finally {
//     isLoading.value = false;
//   }
// };
</script>
