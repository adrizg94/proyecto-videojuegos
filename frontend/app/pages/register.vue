<template>
  <section class="flex justify-center items-center h-full">
    <div class="flex flex-col w-[25vw] h-[50vh] bg-surface rounded-lg py-3">
      <div class="flex justify-center items-center gap-1.5">
        <FontAwesomeIcon
          icon="fa-user-plus"
          class="text-3xl text-primary-light"
        />
        <h1 class="text-2xl font-bold text-center py-4">Sign Up</h1>
      </div>
      <form
        @submit.prevent="register"
        class="flex-1 flex flex-col justify-between mx-10"
      >
        <div class="flex flex-col gap-1">
          <label for="email">Email</label>
          <input
            v-model="email"
            id="email"
            name="email"
            type="email"
            placeholder="you@example.com"
            autocomplete="email"
            required
            class="bg-white px-3 py-1 rounded-lg text-black focus:ring-3 focus:outline-none focus:ring-primary"
          />
        </div>
        <div class="flex flex-col gap-1">
          <label for="username">Username</label>
          <input
            v-model="username"
            id="username"
            name="username"
            type="text"
            autocomplete="username"
            required
            class="bg-white px-3 py-1 rounded-lg text-black focus:ring-3 focus:outline-none focus:ring-primary"
          />
        </div>
        <div class="flex flex-col gap-1">
          <label for="password">Password</label>
          <input
            v-model="password"
            id="password"
            name="password"
            type="password"
            autocomplete="new-password"
            required
            class="bg-white px-3 py-1 rounded-lg text-black focus:ring-3 focus:outline-none focus:ring-primary"
            :class="
              passwordNotLength
                ? 'ring-2 ring-red-500 focus:ring-red-500'
                : 'focus:ring-primary'
            "
          />
          <p v-if="passwordNotLength" class="text-sm text-red-400">
            The password field must be at least 8 characters.
          </p>
        </div>
        <div class="flex flex-col gap-1">
          <label for="password_confirmation">Verify Password</label>
          <input
            v-model="passwordConfirmation"
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            autocomplete="new-password"
            required
            class="bg-white px-3 py-1 rounded-lg text-black focus:ring-3 focus:outline-none focus:ring-primary"
            :class="
              passwordMismatch
                ? 'ring-2 ring-red-500 focus:ring-red-500'
                : 'focus:ring-primary'
            "
          />
          <p v-if="passwordMismatch" class="text-sm text-red-400">
            Passwords do not match.
          </p>
        </div>
        <button
          type="submit"
          class="w-fit font-semibold text-lg px-4 py-2 mx-auto my-2 rounded-lg hover:cursor-pointer bg-primary-light hover:bg-primary focus:outline-none transition-colors"
        >
          Register
        </button>
      </form>
    </div>
  </section>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const username = ref("");
const email = ref("");
const password = ref("");
const passwordConfirmation = ref("");
const passwordMismatch = ref(false);
const passwordNotLength = ref(false);
let passwordTimeout;
const config = useRuntimeConfig();

const register = async () => {
  if (password.value !== passwordConfirmation.value) {
    passwordMismatch.value = true;
    return;
  }
  if (password.value.length < 8) {
    passwordNotLength.value = true;
    return;
  }

  try {
    const response = await $fetch(`${config.public.api}/api/register`, {
      method: "POST",

      body: {
        username: username.value,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value,
      },
    });

    console.log(response);
  } catch (error) {
    console.error(error);
  }
};

watch([password, passwordConfirmation], () => {
  clearTimeout(passwordTimeout);

  passwordMismatch.value = false;
  passwordNotLength.value = false;

  passwordTimeout = setTimeout(() => {
    passwordMismatch.value =
      passwordConfirmation.value !== "" &&
      password.value !== passwordConfirmation.value;

    passwordNotLength.value = password.value.length < 8;
  }, 700);
});
</script>

<!-- Labels flotantes -->
<!-- <template>
  <section class="flex h-full items-center justify-center">
    <div class="flex h-[50vh] w-[25vw] flex-col rounded-lg bg-surface py-3">
      <div class="flex items-center justify-center gap-1.5">
        <FontAwesomeIcon
          icon="fa-user-plus"
          class="text-3xl text-primary-light"
        />
        <h1 class="py-2 text-center text-2xl font-bold">
          Sign Up
        </h1>
      </div>

      <form
        @submit.prevent="register"
        class="mx-10 flex flex-1 flex-col justify-between"
      >
        <div class="relative">
          <input
            v-model="email"
            id="email"
            type="email"
            placeholder=" "
            class="peer w-full rounded-lg bg-white px-3 pb-1.5 pt-5 text-black
                   focus:outline-none focus:ring-3 focus:ring-primary"
          />

          <label
            for="email"
            class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2
                   text-gray-500 transition-all
                   peer-focus:top-2 peer-focus:translate-y-0
                   peer-focus:text-xs peer-focus:text-primary
                   peer-not-placeholder-shown:top-2
                   peer-not-placeholder-shown:translate-y-0
                   peer-not-placeholder-shown:text-xs"
          >
            Email
          </label>
        </div>

        <div class="relative">
          <input
            v-model="name"
            id="name"
            type="text"
            placeholder=" "
            class="peer w-full rounded-lg bg-white px-3 pb-1.5 pt-5 text-black
                   focus:outline-none focus:ring-3 focus:ring-primary"
          />

          <label
            for="name"
            class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2
                   text-gray-500 transition-all
                   peer-focus:top-2 peer-focus:translate-y-0
                   peer-focus:text-xs peer-focus:text-primary
                   peer-not-placeholder-shown:top-2
                   peer-not-placeholder-shown:translate-y-0
                   peer-not-placeholder-shown:text-xs"
          >
            Username
          </label>
        </div>

        <div class="relative">
          <input
            v-model="password"
            id="password"
            type="password"
            placeholder=" "
            class="peer w-full rounded-lg bg-white px-3 pb-1.5 pt-5 text-black
                   focus:outline-none focus:ring-3 focus:ring-primary"
          />

          <label
            for="password"
            class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2
                   text-gray-500 transition-all
                   peer-focus:top-2 peer-focus:translate-y-0
                   peer-focus:text-xs peer-focus:text-primary
                   peer-not-placeholder-shown:top-2
                   peer-not-placeholder-shown:translate-y-0
                   peer-not-placeholder-shown:text-xs"
          >
            Password
          </label>
        </div>

        <div class="relative">
          <input
            v-model="passwordConfirmation"
            id="password_confirmation"
            type="password"
            placeholder=" "
            class="peer w-full rounded-lg bg-white px-3 pb-1.5 pt-5 text-black
                   focus:outline-none focus:ring-3 focus:ring-primary"
          />

          <label
            for="password_confirmation"
            class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2
                   text-gray-500 transition-all
                   peer-focus:top-2 peer-focus:translate-y-0
                   peer-focus:text-xs peer-focus:text-primary
                   peer-not-placeholder-shown:top-2
                   peer-not-placeholder-shown:translate-y-0
                   peer-not-placeholder-shown:text-xs"
          >
            Verify Password
          </label>
        </div>

        <button
          type="submit"
          class="mx-auto my-2 w-fit rounded-lg bg-primary-light px-4 py-2
                 text-lg font-semibold transition-colors
                 hover:cursor-pointer hover:bg-primary focus:outline-none"
        >
          Register
        </button>
      </form>
    </div>
  </section>
</template> -->
