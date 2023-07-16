;(function ($) {
  function wpva_bpvm_installation_counter() {
    return $.ajax({
      type: "POST",
      url: ajaxurl,
      data: {
        action: "wpva_bpvm_installation_counter", // this is the name of our WP AJAX function that we'll set up next
        product_id: wpvaBpvmAdminData.product_id, // change the localization variable.
      },
      dataType: "JSON",
    })
  }

  if (typeof wpvaBpvmAdminData.installation != "undefined" && wpvaBpvmAdminData.installation != 1) {
    $.when(wpva_bpvm_installation_counter()).done(function (response_data) {
      // console.log(response_data)
    })
  }
})(jQuery)
