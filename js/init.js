jQuery(document).ready(function() {
    //   jQuery('.ui.search').search({
    //     apiSettings: {
    //       url: my_ajax_object.ajax_url+'?q={query}',
    //       'type': 'post',
    //       data : {
    //         action : 'post_love_add_love',
    //       }
    //     },
    //     fields: {
    //       results : 'items',
    //       title   : 'name',
    //       url     : 'html_url'
    //     },
    //     minCharacters : 3
    // });

    jQuery('.ui.search')
      .search({
        source: wp_spotlite_full_menu,
        type          : 'category',
        fullTextSearch: false
        // searchFields   : [
        //       'title'
        //     ],

        // fields: {
        //           title   : 'name',
        //           url     : 'html_url',
        //         },
      })
    ;
});