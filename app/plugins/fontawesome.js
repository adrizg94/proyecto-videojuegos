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
  faRankingStar,
  faGamepad,
  faXmark,
  faCalendarDay,
  faCrosshairs,
  faFilter,
  faListCheck,
  faRocket,
  faArrowLeftLong,
  faArrowRightLong,
  faComment,
  faRobot,
  faWandMagicSparkles,
  faPaperPlane,
  faUsers,
  faSignal,
} from "@fortawesome/free-solid-svg-icons";

import {
  faWindows,
  faPlaystation,
  faXbox,
  faSteam,
} from "@fortawesome/free-brands-svg-icons";

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
    faRankingStar,
    faWindows,
    faPlaystation,
    faXbox,
    faSteam,
    faGamepad,
    faXmark,
    faCalendarDay,
    faCrosshairs,
    faFilter,
    faListCheck,
    faRocket,
    faArrowLeftLong,
    faArrowRightLong,
    faComment,
    faRobot,
    faWandMagicSparkles,
    faPaperPlane,
    faUsers,
    faSignal,
  );

  nuxtApp.vueApp.component("FontAwesomeIcon", FontAwesomeIcon);
});
