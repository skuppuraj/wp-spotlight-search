import { defineStore } from 'pinia';

export const useMainStore = defineStore({
  id: 'main',
  state: () => ({
    ajaxRef: [],
  }),
  getters: {
    getRefByAction: (state) => (action) => state.ajaxRef.find(action),
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
