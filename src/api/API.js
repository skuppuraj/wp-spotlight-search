import axios from "axios";
import { serialize } from "object-to-formdata";
import { useMainStore } from "../store/main";
export default {
  post(request) {
    const mainStore = useMainStore();
    const controller = new AbortController();
    const serializeOption = {
      allowEmptyArrays: false,
    };
    if (request.action && mainStore.getRefByAction[request.action]) {
      mainStore.getRefByAction[request.action].abort();
    }
    // eslint-disable-next-line no-undef
    let url = wp_spotlight_search_object.ajaxurl
      .toString()
      .replace("%%endpoint%%", request.action);
    mainStore.setRefByAction(request.action, controller);
    return axios.post(url, serialize(request, serializeOption), {
      signal: controller.signal,
    });
  },
};
