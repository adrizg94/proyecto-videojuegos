// https://nuxt.com/docs/api/configuration/nuxt-config
import tailwindcss from "@tailwindcss/vite";

export default defineNuxtConfig({
  devServer: {
    host: "localhost",
    port: 3000,
  },
  compatibilityDate: "2025-07-15",
  devtools: { enabled: true },

  css: ["~/assets/css/main.css"],

  vite: {
    plugins: [tailwindcss()],
  },

  runtimeConfig: {
    rawgApiKey: "",
    geminiApiKey: "",
    steamApiKey: "",
    public: {
      api: process.env.NUXT_PUBLIC_API_URL,
      adminUrl: process.env.NUXT_PUBLIC_ADMIN_URL,
    },
  },
});
