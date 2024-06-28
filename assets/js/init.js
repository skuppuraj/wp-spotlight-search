// eslint-disable-next-line no-undef
jQuery(function ($) {
  $("#wp_spotlight_search_box").on("click", function () {
    wp_spotlight_trigger_event();
  });
  // eslint-disable-next-line no-undef
  hotkeys("command+k,ctrl+k", function () {
    wp_spotlight_trigger_event();
  });
  function wp_spotlight_trigger_event() {
    $("#wp-spotlight-search-dialog").show();
    var event = new CustomEvent("wp_spotlight_dialog_open", {
      detail: "Example of an event",
    });

    // Dispatch/Trigger/Fire the event
    window.dispatchEvent(event);
  }

  window.onclick = function (event) {
    if (event.target.id == "wp-spotlight-search-dialog") {
      $("#wp-spotlight-search-dialog").hide();
    }
  };

  document.addEventListener.call(window, "wp_spotlight_dialog_close", () => {
    $("#wp-spotlight-search-dialog").hide();
  });
  
  hotkeys.filter = function(event){
    return true;
  }
});
