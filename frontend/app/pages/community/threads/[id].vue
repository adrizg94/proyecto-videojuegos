<template>
  <main class="w-full max-w-5xl mx-auto px-6 py-8">
    <Loading v-if="pending" />

    <div v-else-if="thread">
      <!-- Back -->
      <NuxtLink
        :to="backLink"
        class="mb-5 inline-flex items-center gap-2 text-sm text-text-muted transition-colors hover:text-white"
      >
        <FontAwesomeIcon icon="fa-arrow-left-long" class="text-xs" />

        {{ backText }}
      </NuxtLink>

      <!-- Thread header -->
      <header class="mb-6">
        <div
          class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
        >
          <div class="min-w-0 flex-1">
            <!-- Category -->
            <div class="mb-3 flex items-center gap-2 text-sm text-text-muted">
              <FontAwesomeIcon
                :icon="thread.game ? 'fa-gamepad' : 'fa-comments'"
                class="text-primary"
              />

              <NuxtLink
                v-if="thread.game"
                :to="`/community/games/${thread.game.rawg_id}`"
                class="transition-colors hover:text-white"
              >
                {{ thread.game.name }}
              </NuxtLink>

              <span v-else> General discussions </span>
            </div>

            <!-- Normal title -->
            <div v-if="!editingTitle">
              <h1 class="text-3xl font-bold wrap-break-word">
                {{ thread.title }}
              </h1>

              <div
                class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-text-muted"
              >
                <NuxtLink
                  :to="`/users/${thread.user.username}`"
                  class="flex items-center gap-2 transition-colors hover:text-white"
                >
                  <FontAwesomeIcon icon="fa-user" />

                  {{ thread.user.username }}
                </NuxtLink>

                <span class="flex items-center gap-2">
                  <FontAwesomeIcon icon="fa-clock" />

                  {{ formatDate(thread.created_at) }}
                </span>

                <span class="flex items-center gap-2">
                  <FontAwesomeIcon icon="fa-comment" />

                  {{ thread.replies_count }}
                  {{ thread.replies_count === 1 ? "reply" : "replies" }}
                </span>
              </div>
            </div>

            <!-- Edit title -->
            <form v-else class="max-w-2xl" @submit.prevent="updateThreadTitle">
              <input
                v-model.trim="titleForm"
                type="text"
                maxlength="150"
                class="w-full rounded-lg border border-white/10 bg-surface px-4 py-3 text-xl font-bold outline-none transition-colors focus:border-primary"
              />

              <p v-if="titleErrors.title" class="mt-2 text-sm text-red-400">
                {{ titleErrors.title[0] }}
              </p>

              <div class="mt-3 flex gap-2">
                <button
                  type="submit"
                  :disabled="savingTitle"
                  class="inline-flex items-center gap-2 rounded-lg bg-primary px-3 py-2 text-sm font-medium text-white transition-opacity hover:opacity-80 disabled:opacity-50 cursor-pointer"
                >
                  <FontAwesomeIcon
                    v-if="savingTitle"
                    icon="fa-spinner"
                    class="animate-spin"
                  />

                  <FontAwesomeIcon v-else icon="fa-check" />

                  Save
                </button>

                <button
                  type="button"
                  class="rounded-lg px-3 py-2 text-sm text-text-muted transition-colors hover:bg-hover hover:text-white cursor-pointer"
                  @click="cancelTitleEdit"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>

          <!-- Thread owner actions -->
          <div
            v-if="isThreadOwner && !editingTitle"
            class="flex shrink-0 items-center gap-2"
          >
            <button
              type="button"
              title="Edit thread title"
              class="flex h-9 w-9 items-center justify-center rounded-lg text-text-muted transition-colors hover:bg-hover hover:text-white cursor-pointer"
              @click="startTitleEdit"
            >
              <FontAwesomeIcon icon="fa-pen" />
            </button>

            <button
              type="button"
              title="Delete thread"
              class="flex h-9 w-9 items-center justify-center rounded-lg text-text-muted transition-colors hover:bg-red-500/10 hover:text-red-400 cursor-pointer"
              @click="askDeleteThread"
            >
              <FontAwesomeIcon icon="fa-trash" />
            </button>
          </div>
        </div>
      </header>

      <!-- Posts -->
      <section class="space-y-4">
        <article
          v-for="(post, index) in thread.posts"
          :key="post.id"
          class="overflow-hidden rounded-xl border border-white/10 bg-surface"
        >
          <!-- Post header -->
          <div
            class="flex items-center justify-between gap-4 border-b border-white/10 px-5 py-3"
          >
            <div class="flex min-w-0 items-center gap-3">
              <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-hover text-primary"
              >
                <FontAwesomeIcon icon="fa-user" />
              </div>

              <div class="min-w-0">
                <NuxtLink
                  :to="`/users/${post.user.username}`"
                  class="font-medium transition-colors hover:text-primary"
                >
                  {{ post.user.username }}
                </NuxtLink>

                <div
                  class="flex flex-wrap items-center gap-2 text-xs text-text-muted"
                >
                  <span>
                    {{ formatDate(post.created_at) }}
                  </span>

                  <span
                    v-if="index === 0"
                    class="rounded bg-primary/15 px-2 py-0.5 text-primary"
                  >
                    Original post
                  </span>

                  <span v-if="post.updated_at !== post.created_at">
                    · edited
                  </span>
                </div>
              </div>
            </div>

            <!-- Post owner actions -->
            <div
              v-if="isPostOwner(post)"
              class="flex shrink-0 items-center gap-1"
            >
              <button
                type="button"
                title="Edit post"
                class="flex h-8 w-8 items-center justify-center rounded-lg text-sm text-text-muted transition-colors hover:bg-hover hover:text-white cursor-pointer"
                @click="startPostEdit(post)"
              >
                <FontAwesomeIcon icon="fa-pen" />
              </button>

              <!-- El primer post no puede borrarse por separado -->
              <button
                v-if="index !== 0"
                type="button"
                title="Delete post"
                class="flex h-8 w-8 items-center justify-center rounded-lg text-sm text-text-muted transition-colors hover:bg-red-500/10 hover:text-red-400 cursor-pointer"
                @click="askDeletePost(post)"
              >
                <FontAwesomeIcon icon="fa-trash" />
              </button>
            </div>
          </div>

          <!-- Post body -->
          <div class="p-5">
            <template v-if="editingPostId !== post.id">
              <p class="whitespace-pre-wrap break-words leading-7">
                {{ post.body }}
              </p>
            </template>

            <!-- Edit post -->
            <form v-else @submit.prevent="updatePost(post)">
              <textarea
                v-model="postEditBody"
                maxlength="5000"
                rows="6"
                class="w-full resize-y rounded-lg border border-white/10 bg-background px-4 py-3 text-sm outline-none transition-colors focus:border-primary"
              ></textarea>

              <div class="mt-1 flex items-center justify-between">
                <p v-if="postErrors.body" class="text-sm text-red-400">
                  {{ postErrors.body[0] }}
                </p>

                <span class="ml-auto text-xs text-text-muted">
                  {{ postEditBody.length }}/5000
                </span>
              </div>

              <div class="mt-3 flex gap-2">
                <button
                  type="submit"
                  :disabled="savingPost"
                  class="inline-flex items-center gap-2 rounded-lg bg-primary px-3 py-2 text-sm font-medium text-white transition-opacity hover:opacity-80 disabled:opacity-50 cursor-pointer"
                >
                  <FontAwesomeIcon
                    v-if="savingPost"
                    icon="fa-spinner"
                    class="animate-spin"
                  />

                  <FontAwesomeIcon v-else icon="fa-check" />

                  Save
                </button>

                <button
                  type="button"
                  class="rounded-lg px-3 py-2 text-sm text-text-muted transition-colors hover:bg-hover hover:text-white cursor-pointer"
                  @click="cancelPostEdit"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </article>
      </section>

      <!-- Reply -->
      <section class="mt-8">
        <div
          v-if="isAuthenticated"
          class="rounded-xl border border-white/10 bg-surface p-5"
        >
          <div class="mb-4">
            <h2 class="text-lg font-bold">Write a reply</h2>

            <p class="mt-1 text-sm text-text-muted">Join the discussion.</p>
          </div>

          <form @submit.prevent="createReply">
            <textarea
              v-model="replyBody"
              maxlength="5000"
              rows="6"
              placeholder="Write your reply..."
              class="w-full resize-y rounded-lg border border-white/10 bg-background px-4 py-3 text-sm outline-none transition-colors focus:border-primary"
            ></textarea>

            <div class="mt-1 flex items-center justify-between gap-3">
              <p v-if="replyErrors.body" class="text-sm text-red-400">
                {{ replyErrors.body[0] }}
              </p>

              <span class="ml-auto text-xs text-text-muted">
                {{ replyBody.length }}/5000
              </span>
            </div>

            <div class="mt-4 flex justify-end">
              <button
                type="submit"
                :disabled="publishingReply"
                class="inline-flex min-w-32 items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white transition-opacity hover:opacity-80 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
              >
                <FontAwesomeIcon
                  v-if="publishingReply"
                  icon="fa-spinner"
                  class="animate-spin"
                />

                <FontAwesomeIcon v-else icon="fa-paper-plane" />

                {{ publishingReply ? "Publishing..." : "Reply" }}
              </button>
            </div>
          </form>
        </div>

        <!-- Guest -->
        <div
          v-else
          class="rounded-xl border border-white/10 bg-surface px-6 py-8 text-center"
        >
          <FontAwesomeIcon
            icon="fa-comments"
            class="text-3xl text-text-muted"
          />

          <p class="mt-3 text-sm text-text-muted">
            Log in to join the discussion.
          </p>

          <NuxtLink
            to="/login"
            class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-primary transition-colors hover:text-white"
          >
            Log in

            <FontAwesomeIcon icon="fa-arrow-right" class="text-xs" />
          </NuxtLink>
        </div>
      </section>

      <Toast />
    </div>

    <!-- Thread not found / error -->
    <div
      v-else
      class="rounded-xl border border-white/10 bg-surface px-6 py-10 text-center"
    >
      <FontAwesomeIcon icon="fa-comments" class="text-3xl text-text-muted" />

      <p class="mt-3 text-text-muted">Discussion not found.</p>

      <NuxtLink
        to="/community"
        class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-primary transition-colors hover:text-white"
      >
        Back to community

        <FontAwesomeIcon icon="fa-arrow-right" class="text-xs" />
      </NuxtLink>
    </div>
    <ConfirmModal
      :show="deleteModal.show"
      :title="
        deleteModal.type === 'thread' ? 'Delete discussion' : 'Delete reply'
      "
      :message="
        deleteModal.type === 'thread'
          ? 'Are you sure you want to delete this discussion? All replies will also be deleted.'
          : 'Are you sure you want to delete this reply?'
      "
      :loading="deleting"
      @confirm="confirmDelete"
      @cancel="cancelDelete"
    />

    <Toast />
  </main>
</template>

<script setup>
import ConfirmModal from '~/components/common/ConfirmModal.vue';

const route = useRoute();

const { apiFetch } = useApi();
const { user, isAuthenticated, fetchUser } = useAuth();

const { showToast } = useToast();

const pending = ref(true);
const thread = ref(null);

/*
|--------------------------------------------------------------------------
| THREAD TITLE
|--------------------------------------------------------------------------
*/

const editingTitle = ref(false);
const savingTitle = ref(false);
const titleForm = ref("");
const titleErrors = ref({});

/*
|--------------------------------------------------------------------------
| POSTS
|--------------------------------------------------------------------------
*/

const editingPostId = ref(null);
const postEditBody = ref("");
const savingPost = ref(false);
const postErrors = ref({});

/*
|--------------------------------------------------------------------------
| REPLY
|--------------------------------------------------------------------------
*/

const replyBody = ref("");
const publishingReply = ref(false);
const replyErrors = ref({});

/*
|--------------------------------------------------------------------------
| COMPUTED
|--------------------------------------------------------------------------
*/

const isThreadOwner = computed(() => {
  return user.value && thread.value && user.value.id === thread.value.user_id;
});

const backLink = computed(() => {
  if (thread.value?.game) {
    return `/community/games/${thread.value.game.rawg_id}`;
  }

  return "/community/general";
});

const backText = computed(() => {
  return thread.value?.game
    ? `Back to ${thread.value.game.name} discussions`
    : "Back to general discussions";
});

/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/

const isPostOwner = (post) => {
  return user.value?.id === post.user_id;
};

const formatDate = (date) => {
  if (!date) return "";

  return new Date(date).toLocaleString("en-GB", {
    day: "numeric",
    month: "short",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

/*
|--------------------------------------------------------------------------
| FETCH THREAD
|--------------------------------------------------------------------------
*/

const fetchThread = async () => {
  try {
    thread.value = await apiFetch(`community/threads/${route.params.id}`);
  } catch (error) {
    console.error("Error loading thread:", error);
    thread.value = null;
  }
};

/*
|--------------------------------------------------------------------------
| UPDATE THREAD TITLE
|--------------------------------------------------------------------------
*/

const startTitleEdit = () => {
  titleForm.value = thread.value.title;
  titleErrors.value = {};
  editingTitle.value = true;
};

const cancelTitleEdit = () => {
  editingTitle.value = false;
  titleErrors.value = {};
};

const updateThreadTitle = async () => {
  titleErrors.value = {};
  savingTitle.value = true;

  try {
    const response = await apiFetch(`community/threads/${thread.value.id}`, {
      method: "PUT",
      body: {
        title: titleForm.value,
      },
    });

    showToast(response.message);

    editingTitle.value = false;

    await fetchThread();
  } catch (error) {
    titleErrors.value = error.data?.errors ?? {};

    if (!error.data?.errors) {
      showToast(
        `ERROR: ${
          error.data?.message ?? error.message ?? "Error updating thread"
        }`,
      );
    }
  } finally {
    savingTitle.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| EDIT POST
|--------------------------------------------------------------------------
*/

const startPostEdit = (post) => {
  editingPostId.value = post.id;
  postEditBody.value = post.body;
  postErrors.value = {};
};

const cancelPostEdit = () => {
  editingPostId.value = null;
  postEditBody.value = "";
  postErrors.value = {};
};

const updatePost = async (post) => {
  savingPost.value = true;
  postErrors.value = {};

  try {
    const response = await apiFetch(`community/posts/${post.id}`, {
      method: "PUT",
      body: {
        body: postEditBody.value,
      },
    });

    showToast(response.message);

    cancelPostEdit();

    await fetchThread();
  } catch (error) {
    postErrors.value = error.data?.errors ?? {};

    if (!error.data?.errors) {
      showToast(
        `ERROR: ${
          error.data?.message ?? error.message ?? "Error updating post"
        }`,
      );
    }
  } finally {
    savingPost.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| DELETE THREAD / POST
|--------------------------------------------------------------------------
*/

const deleteModal = reactive({
  show: false,
  type: null,
  post: null,
});

const deleting = ref(false);

const askDeleteThread = () => {
  deleteModal.type = "thread";
  deleteModal.post = null;
  deleteModal.show = true;
};

const askDeletePost = (post) => {
  deleteModal.type = "post";
  deleteModal.post = post;
  deleteModal.show = true;
};

const confirmDelete = async () => {
  deleting.value = true;

  try {
    if (deleteModal.type === "thread") {
      const destination = backLink.value;

      const response = await apiFetch(`community/threads/${thread.value.id}`, {
        method: "DELETE",
      });

      showToast(response.message);

      deleteModal.show = false;

      await navigateTo(destination);

      return;
    }

    if (deleteModal.type === "post" && deleteModal.post) {
      const response = await apiFetch(
        `community/posts/${deleteModal.post.id}`,
        {
          method: "DELETE",
        },
      );

      showToast(response.message);

      deleteModal.show = false;
      deleteModal.post = null;

      await fetchThread();
    }
  } catch (error) {
    showToast(
      `ERROR: ${error.data?.message ?? error.message ?? "Error deleting"}`,
    );
  } finally {
    deleting.value = false;
  }
};

const cancelDelete = () => {
  if (deleting.value) return;

  deleteModal.show = false;
  deleteModal.type = null;
  deleteModal.post = null;
};

/*
|--------------------------------------------------------------------------
| CREATE REPLY
|--------------------------------------------------------------------------
*/

const createReply = async () => {
  publishingReply.value = true;
  replyErrors.value = {};

  try {
    const response = await apiFetch(
      `community/threads/${thread.value.id}/posts`,
      {
        method: "POST",
        body: {
          body: replyBody.value,
        },
      },
    );

    showToast(response.message);

    replyBody.value = "";

    await fetchThread();
  } catch (error) {
    replyErrors.value = error.data?.errors ?? {};

    if (!error.data?.errors) {
      showToast(
        `ERROR: ${
          error.data?.message ?? error.message ?? "Error publishing reply"
        }`,
      );
    }
  } finally {
    publishingReply.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| LOAD PAGE
|--------------------------------------------------------------------------
*/

onMounted(async () => {
  try {
    // Necesitamos conocer el usuario también en una página pública
    // para saber si puede editar/responder.
    if (!user.value) {
      await fetchUser();
    }

    await fetchThread();
  } finally {
    pending.value = false;
  }
});
</script>
