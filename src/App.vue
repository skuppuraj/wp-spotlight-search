<template>
  <div id="app">
    <ais-instant-search
        index-name="demo_ecommerce"
        :search-client="searchClient"
      >
        <!-- Widgets -->
      </ais-instant-search>
  </div>
</template>

<script>
import algoliasearch from 'algoliasearch/lite';

export default {
  name: 'App',
  components: {
  },
  data() {
    return {
      searchClient: algoliasearch(
        'GWTWIECXO9',
        'a96358d872df30b834a32d5f72d7c150',
      ),
    };
  },
};
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
