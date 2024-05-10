import { createStore } from "vuex";
import User from "@/store/modules/User";
import Attendance from "@/store/modules/Attendance";

export default createStore({
  strict: false,
  modules: {
    User,
    Attendance,
  },
});
