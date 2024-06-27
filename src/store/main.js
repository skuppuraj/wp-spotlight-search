import { defineStore } from "pinia";

export const useMainStore = defineStore({
  id: "main",
  state: () => {
    return {
      ajaxRef: [],
    };
  },
  getters: {
    getRefByAction: (state) => {
      return (action) => state.ajaxRef.find(action);
    },
  },
  actions: {
    setRefByAction(action, obj) {
      this.ajaxRef[action] = obj;
    },
    deleteRefByAction(action) {
      delete this.ajaxRef[action];
    },
  },
});
