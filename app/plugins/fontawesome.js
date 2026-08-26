import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

import {
  faSearch,
  faHeart,
  faStar,
  faPlus,
  faChevronLeft,
  faChevronRight,
  faChevronDown,
  faFilterCircleXmark,
} from "@fortawesome/free-solid-svg-icons";

export default defineNuxtPlugin((nuxtApp) => {
  library.add(
    faSearch,
    faHeart,
    faStar,
    faPlus,
    faChevronLeft,
    faChevronRight,
    faChevronDown,
    faFilterCircleXmark,
  );

  nuxtApp.vueApp.component("FontAwesomeIcon", FontAwesomeIcon);
});
