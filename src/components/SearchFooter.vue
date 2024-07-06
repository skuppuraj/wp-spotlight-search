<template>
  <div class="footer">
    <a target="_blank">WP Spotlight Search</a>
    <a href="https://www.buymeacoffee.com/wpspotlight"><img style="height: 30px;" src="https://img.buymeacoffee.com/button-api/?text=Buy me a coffee&emoji=&slug=wpspotlight&button_colour=FFDD00&font_colour=000000&font_family=Comic&outline_colour=000000&coffee_colour=ffffff" /></a>
    <a
      target="_blank"
      class="share-love"
      style="margin-left: auto; margin-right: 25px"
      >Share the</a
    >
    <a target="_blank" style="margin-right: 10px">Give Feedback</a>
    <a target="_blank" :href="url.rating" style="margin-right: 0"
      >Rate the plugin</a
    >
  </div>
</template>

<script>
export default {
  name: 'SearchFooter',
  data() {
    return {
      url: {
        rating:
          'https://wordpress.org/support/plugin/wp-spotlight-search/reviews/#new-post',
      },
    };
  },
};
</script>

<style scoped>
div {
  display: none;
}
</style>
