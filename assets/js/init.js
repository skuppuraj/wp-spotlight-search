jQuery(document).ready(function() {
    jQuery('.ui.search')
      .search({
        source: wp_spotlite_full_menu,
        type          : 'category',
        selectFirstResult: true,
        fullTextSearch: true,
        searchFields   : [
              'title',
              'category',
              'ID'
            ],
        maxResults : 10,
      })
    ;
});