(function ($, Drupal, settings) {
    "use strict";

    Drupal.behaviors.event_popup = {
        attach: function (context, settings) {
            // Not working.
            // $('#view-header').append('<a href="/modal-example/modal/nojs" class="use-ajax" id="add-event-button" data-dialog-type="modal" data-dialog-options="{&quot;width&quot;:400}">Click</a>');
            $('table.full tr td, table.mini tr td', context).click(function (e) {
                    e.preventDefault();
                    $('.add-event-button').click();
                    // Gets clicked date.
                    var current_date = e.target.getAttribute('date-date');
                    if (current_date === null) {
                        current_date = e.target.parentNode.getAttribute('date-date');
                    }

                    // Add simple modal

                    // var $myDialog = $('<div> Simple modal ' + current_date + '</div>').appendTo('body');
                    // Drupal.dialog($myDialog, {
                    //     title: 'Add event',
                    //     buttons: [{
                    //         text: 'Close',
                    //         click: function() {
                    //             $(this).dialog('close');
                    //         }
                    //     }]
                    // }).showModal();
                }
            );
        }
    }
})
(jQuery, Drupal, drupalSettings);
