<template>
  <div>
    <shadow-root id="wpss" ref="wpss">
      <shadow-own-style></shadow-own-style>
      <div id="wpss-main-container">
        <label for="search-box">
          <div class="search-icon"></div>
          <input
            id="search-box"
            type="text"
            class=""
            :placeholder="translatedStrings.search_place_holder"
            v-model="search"
            ref="search"
            @keydown.up.prevent="highlightPrevious"
            @keydown.down.prevent="highlightNext"
            @keydown.enter.prevent="openLink"
            @keydown.esc.prevent="closeModal"
          />
          <span class="cmd-tag" style="display: none">{{
            translatedStrings.commands_title
          }}</span>
          <a
            class="clear-search-box"
            v-show="search"
            :title="translatedStrings.clear_title"
          ></a>
          <div class="search-help-text cmd" v-show="!search">
            {{ translatedStrings.help_text_cmd }}
          </div>
          <div class="search-help-text" v-show="search">
            {{ translatedStrings.help_text_navigation }}
          </div>
        </label>
        <div class="options-title">{{ translatedStrings.category_title }}</div>
        <ul class="horiz-tabs">
          <li
            v-for="(category, index) in categories"
            :key="index"
            :class="{ active: isAllCategory(category.type) }"
            @click="categoryClick(category.type)"
          >
            {{ category.label }}
          </li>
        </ul>
        <div class="search-container" ref="search_results">
          <div>
            <div
              class="options-title"
              v-if="!search && finderResult.length > 0"
            >
              Recent searches
            </div>
            <ul class="options-list" v-if="finderResult.length > 0">
              <li
                v-for="(items, iIndex) in finderResult"
                :key="iIndex"
                :id="listIDCreate(iIndex)"
                :class="{ active: iIndex == navigationIndex }"
                @mouseover="navigationIndex = iIndex"
                :ref="listIDCreate(iIndex)"
                @click="saveRecentSearch(items)"
              >
                <a :href="generateHref(items['item']['url'])">
                  <div
                    class="option-item"
                    v-html="
                      generateElementText(
                        items['item']['title'],
                        items['matches'],
                        'title'
                      )
                    "
                  ></div>
                  <div
                    class="option-desc"
                    v-if="items['item']['type'] == 'menu'"
                    v-html="
                      generateElementText(
                        items['item']['parent'],
                        items['matches'],
                        'parent'
                      )
                    "
                  ></div>
                  <div
                    class="option-desc"
                    v-if="items['item']['type'] != 'menu'"
                    v-html="items['item']['category']"
                  ></div>
                </a>
                <div class="arrow-section">
                  <div class="enter-icon"></div>
                  <div class="right-arrow"></div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <search-footer></search-footer>
      </div>
    </shadow-root>
  </div>
</template>
<script>
import { ShadowRoot } from 'vue-shadow-dom';
import Fuse from 'fuse.js';
import _ from 'lodash';
import api from '../api/API';
import SearchFooter from './SearchFooter.vue';
import ShadowOwnStyle from './ShadowOwnStyle.vue';

export default {
  name: 'SearchElement',
  data() {
    return {
      categories: {},
      search: '',
      activeCategories: [],
      list: [],
      finderResult: [],
      searchOption: {
        keys: [
          { name: 'title', weight: 0.1 },
          { name: 'email', weight: 0.1 },
          { name: 'ID', weight: 0.1 },
          { name: 'role', weight: 0.1 },
        ],
        includeMatches: true,
        includeScore: true,
        shouldSort: true,
        distance: 1000,
        findAllMatches: true,
        threshold: 0.1,
        location: 1,
      },
      translatedStrings: {},
      navigationIndex: 0,
      recent_search: {},
      isAdmin: false,
    };
  },
  components: {
    ShadowRoot,
    ShadowOwnStyle,
    SearchFooter,
  },
  watch: {
    search(value) {
      if (value.length >= 1) {
        this.resetNavigationIndex();
        this.finder(value);
        if (value.length >= 3) {
          this.debounceSearch();
        }
      } else {
        this.resetNavigationIndex();
        this.resetFinderResult();
      }
    },
    activeCategories: {
      handler() {
        this.finder(this.search);
        if (this.search.length >= 3) {
          this.debounceSearch();
        }
      },
      deep: true,
    },
  },
  beforeMount() {
    // eslint-disable-next-line no-undef
    this.translatedStrings = wp_spotlight_search_object.front_element;
  },
  mounted() {
    this.getInit();
    this.assignMenu();
    this.isAdmin = this.isWPAdmin();
    // eslint-disable-next-line no-undef
    this.categories = wp_spotlight_search_object.categories;
    // eslint-disable-next-line no-undef
    this.recent_search = wp_spotlight_search_object.recent_search;
    // eslint-disable-next-line no-undef
    this.activeCategories = wp_spotlight_search_object.saved_category;
    this.finderResult = this.recent_search;
  },
  created() {
    document.addEventListener.call(window, 'wp_spotlight_dialog_open', () => {
      if (this.$refs.search != null) {
        this.$refs.search.focus();
        this.selectAll();
      }
    });
    this.debounceSearch = _.debounce(this.triggerSearch, 500);
  },
  methods: {
    getInit() {
      const request = {
        action: 'get_init',
      };
      api.post(request).then((response) => {
        if (response.data.post_types) {
          this.mergeToList(response.data.post_types);
        }
      });
    },
    finder(value) {
      const fuse = new Fuse(this.list, this.searchOption);
      const result = fuse.search(value);
      if (value.length >= 1) {
        this.resetFinderResult(true);
      }
      // eslint-disable-next-line consistent-return
      _.forEach(result, (itemList, lKey) => {
        if (lKey === 20) {
          return false;
        }
        if (
          this.activeCategories.length > 0
          && _.indexOf(this.activeCategories, itemList.item.type) == -1
        ) {
          return;
        }
        this.finderResult.push(itemList);
        _.sortedIndex(this.finderResult);
      });
      console.log(this.finderResult);
    },
    triggerSearch() {
      const request = {
        action: 'fire_search',
        search: this.search,
      };
      request.activeCategories = [];
      request.activeCategories = this.activeCategories;
      api.post(request).then((response) => {
        console.log(response);
      });
    },
    isAllCategory(type) {
      return _.indexOf(this.activeCategories, type) !== -1;
    },
    categoryClick(type) {
      const index = _.indexOf(this.activeCategories, type);
      if (index !== -1) {
        _.remove(this.activeCategories, (n) => n == type);
      } else {
        this.activeCategories.push(type);
      }
      this.saveChoosenCategory();
    },
    saveChoosenCategory() {
      const request = {
        action: 'save_category',
        activeCategories: this.activeCategories,
      };
      api.post(request);
    },
    optionsTitle(type_key) {
      let title = '';
      if (type_key == 'menu') {
        title = this.translatedStrings.admin_category_title;
      } else {
        title = _.find(this.categories, { type: type_key });
        title = title.label;
      }
      return `<div class="options-title">${title}</div>`;
    },
    generateElementText(value, matches, type) {
      let matchIndex = {};
      let addIndice = 0;
      matchIndex = _.find(matches, { key: type });
      if (typeof matchIndex !== 'undefined') {
        _.forEach(matchIndex.indices, (indice) => {
          const addOne = 1;
          const newIndiceStart = indice[0] + addIndice;
          const newIndiceEnd = indice[1] + addIndice + addOne;
          let chr = value.slice(newIndiceStart, newIndiceEnd);
          chr = `<strong>${chr}</strong>`;
          value = value.substring(0, newIndiceStart)
            + chr
            + value.substring(newIndiceEnd);
          addIndice += 17; // 17 is strong tag length
        });
      }
      return value;
    },
    listIDCreate(key) {
      return `list-${key}`;
    },
    highlightNext() {
      this.navigationIndex++;
      if (this.finderResult.length <= this.navigationIndex) {
        this.navigationIndex = 0;
      }
      this.scrollIntoView();
    },
    highlightPrevious() {
      this.navigationIndex--;
      if (this.navigationIndex < 0) {
        this.navigationIndex = this.finderResult.length - 1;
      }
      this.scrollIntoView();
    },
    openLink() {
      const selected = this.finderResult[this.navigationIndex];
      this.saveRecentSearch(selected);
      window.location = this.generateHref(selected.item.url);
    },
    scrollIntoView() {
      const letRef = this.listIDCreate(this.navigationIndex);
      const el = this.$refs.wpss.shadow_root.getElementById(letRef);
      if (el != null) {
        el.scrollIntoView(false);
      }
    },
    resetNavigationIndex() {
      this.navigationIndex = 0;
    },
    resetFinderResult(hardReset = false) {
      if (hardReset) {
        this.finderResult = [];
      } else {
        this.finderResult = this.recent_search;
      }
    },
    saveRecentSearch(item) {
      const request = {
        action: 'save_recent',
        item,
      };
      api.post(request);
    },
    mergeToList(destination) {
      this.assignMenu();
      _.forEach(destination, (data) => {
        this.list.push(data);
      });
    },
    assignMenu() {
      // eslint-disable-next-line no-undef
      this.list = _.clone(wp_spotlight_search_object.searchabel_menu_item);
    },
    isWPAdmin() {
      const path = window.location.pathname;
      return path.indexOf('wp-admin') != -1;
    },
    generateHref(url) {
      if (this.isAdmin) {
        return url;
      }
      return `${window.location.origin}/wp-admin/${url}`;
    },
    selectAll() {
      this.$refs.search.select();
    },
    closeModal() {
      if (this.search) {
        this.search = '';
      } else {
        const event = new CustomEvent('wp_spotlight_dialog_close', {
          detail: 'Example of an event',
        });

        // Dispatch/Trigger/Fire the event
        window.dispatchEvent(event);
      }
    },
  },
};
</script>
