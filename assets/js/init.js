jQuery(document).ready(function() {
  
  if (typeof wp_spotlight_full_menu != 'undefined') {
      jQuery('.ui.search')
        .search({
          source: wp_spotlight_full_menu,
          type          : 'category',
          selectFirstResult: true,
          fullTextSearch: true,
          searchFields   : [
                'title',
                'category',
                'ID'
              ],
          maxResults : 10,
        });
  }
});

wp_spotlight_shortcut.add("Ctrl+S",function() {
  jQuery('#wp_spotlight_search_box').focus();
})