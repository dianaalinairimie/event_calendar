(function ($, Drupal, settings) {
    "use strict";

    Drupal.behaviors.event_popup = {
        // On click on any table cell, open the add event pop-up.
        attach: function (context, settings) {
            $('table.full tr td, table.mini tr td', context).click(function (e) {
                    e.preventDefault();

                    $('a').click(function (event) {
                        event.stopPropagation();
                    });

                    $('.add-event-button').click();
                    // Gets clicked date.
                    var current_date = e.target.getAttribute('date-date');
                    if (current_date === null) {
                        current_date = e.target.closest('td').getAttribute('date-date');
                        // Sets default date as current date.
                        if (current_date === null) {
                            current_date = formatDate(Date());
                        }
                    }

                    var afterLoaded = function () {
                        $(".form-date").val(current_date);
                    };

                    var checkLoaded = function () {
                        var interval = setInterval(function () {
                            if (document.getElementsByClassName("ui-dialog").length) {
                                clearInterval(interval);
                                afterLoaded();
                            }
                        }, 1000);
                    };
                    checkLoaded();

                    // Converts date in required format.
                    function formatDate(date) {
                        var d = new Date(date),
                            month = '' + (d.getMonth() + 1),
                            day = '' + d.getDate(),
                            year = d.getFullYear();

                        if (month.length < 2) month = '0' + month;
                        if (day.length < 2) day = '0' + day;

                        return [year, month, day].join('-');
                    }
                }
            );
        }
    }
})
(jQuery, Drupal, drupalSettings);
