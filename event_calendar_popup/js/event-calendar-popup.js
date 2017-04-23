(function ($, Drupal, settings) {
    "use strict";

    Drupal.behaviors.event_popup = {
        attach: function (context, settings) {
            $('table.full tr td, table.mini tr td', context).click(function () {

            });
        }
    }
})(jQuery, Drupal, drupalSettings);
