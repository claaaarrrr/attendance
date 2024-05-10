import api from "@/api/index";

export default {
  state: {
    attLogs: [],
  },

  getters: {
    GET_ATTLOGS: (state) => state.attLogs,
  },

  mutations: {
    SET_ATTLOGS: (state, data) => {
      state.attLogs = data;
    },
  },

  actions: {
    getAttendance({ commit }, params) {
      return new Promise((resolve, reject) => {
        api
          .get("api/getAttendance", { params })
          .then((response) => {
            if (response.status == 200) {
              resolve(response);
              commit("SET_ATTLOGS", response.data);
            } else if (response.status == 204) {
              return;
            }
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
  },
};
