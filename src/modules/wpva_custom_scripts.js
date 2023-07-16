;(function ($) {
  "use strict"

  $(function () {
    // Place your public-facing JavaScript here

    jQuery(".pvm_front_end_filter").each(function () {
      var $pvm_front_end_filter = jQuery(this)

      $pvm_front_end_filter.on("change", function () {
        var filter_id = jQuery(this).data("filter_id"),
          interval = jQuery(this).val(),
          vote_type = jQuery(this).data("vote_type"),
          order = jQuery(this).data("order"),
          limit = jQuery(this).data("limit"),
          result_widget_id = jQuery("#rw_" + filter_id)

        result_widget_id.addClass("wrf-overlay")
        jQuery.when(bpvm_get_woo_product_data(interval, vote_type, order, limit)).done(function (response_data) {
          result_widget_id.html(response_data)
          result_widget_id.removeClass("wrf-overlay")
        })
      })
    })

    function bpvm_get_woo_product_data(interval, vote_type, order, limit) {
      return jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
          action: "bpvm_get_woo_product_data", // this is the name of our WP AJAX function that we'll set up next
          interval: interval,
          vote_type: vote_type,
          order: order,
          limit: limit,
        },
        dataType: "HTML",
      })
    }
  })
})(jQuery)
