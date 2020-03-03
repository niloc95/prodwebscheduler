/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

/**
 * Backend Calendar Unavailabilities Modal
 *
 * This module implements the unavailabilities modal functionality.
 *
 * @module BackendCalendarUnavailabilitiesModal
 */
window.BackendCalendarUnavailabilitiesModal = window.BackendCalendarUnavailabilitiesModal || {};

(function (exports) {

    'use strict';

    function _bindEventHandlers() {
        /**
         * Event: Manage Unavailable Dialog Save Button "Click"
         *
         * Stores the unavailable period changes or inserts a new record.
         */
        $('#manage-unavailable #save-unavailable').click(function () {
            var $dialog = $('#manage-unavailable');
            $dialog.find('.has-error').removeClass('has-error');
            var start = $dialog.find('#unavailable-start').datetimepicker('getDate');
            var end = Date.parse($dialog.find('#unavailable-end').datetimepicker('getDate'));

            if (start > end) {
                // Start time is after end time - display message to user.
                $dialog.find('.modal-message')
                    .text(WBLang.start_date_before_end_error)
                    .addClass('alert-danger')
                    .removeClass('hidden');

                $dialog.find('#unavailable-start, #unavailable-end').closest('.form-group').addClass('has-error');
                return;
            }

            // Unavailable period records go to the appointments table.
            var unavailable = {
                start_datetime: start.toString('yyyy-MM-dd HH:mm'),
                end_datetime: end.toString('yyyy-MM-dd HH:mm'),
                notes: $dialog.find('#unavailable-notes').val(),
                id_users_provider: $('#unavailable-provider').val() // curr provider
            };

            if ($dialog.find('#unavailable-id').val() !== '') {
                // Set the id value, only if we are editing an appointment.
                unavailable.id = $dialog.find('#unavailable-id').val();
            }

            var successCallback = function (response) {
                if (response.exceptions) {
                    response.exceptions = GeneralFunctions.parseExceptions(response.exceptions);
                    GeneralFunctions.displayMessageBox(GeneralFunctions.EXCEPTIONS_TITLE,
                        GeneralFunctions.EXCEPTIONS_MESSAGE);
                    $('#message_box').append(GeneralFunctions.exceptionsToHtml(response.exceptions));

                    $dialog.find('.modal-message')
                        .text(WBLang.unexpected_issues_occurred)
                        .addClass('alert-danger')
                        .removeClass('hidden');

                    return;
                }

                if (response.warnings) {
                    response.warnings = GeneralFunctions.parseExceptions(response.warnings);
                    GeneralFunctions.displayMessageBox(GeneralFunctions.WARNINGS_TITLE,
                        GeneralFunctions.WARNINGS_MESSAGE);
                    $('#message_box').append(GeneralFunctions.exceptionsToHtml(response.warnings));
                }

                // Display success message to the user.
                $dialog.find('.modal-message')
                    .text(WBLang.unavailable_saved)
                    .addClass('alert-success')
                    .removeClass('alert-danger hidden');

                // Close the modal dialog and refresh the calendar appointments after one second.
                setTimeout(function () {
                    $dialog.find('.alert').addClass('hidden');
                    $dialog.modal('hide');
                    $('#select-filter-item').trigger('change');
                }, 2000);
            };

            var errorCallback = function (jqXHR, textStatus, errorThrown) {
                GeneralFunctions.displayMessageBox('Communication Error', 'Unfortunately ' +
                    'the operation could not complete due to server communication errors.');

                $dialog.find('.modal-message').txt(WBLang.service_communication_error);
                $dialog.find('.modal-message').addClass('alert-danger').removeClass('hidden');
            };

            BackendCalendarApi.saveUnavailable(unavailable, successCallback, errorCallback);
        });

        /**
         * Event: Manage Unavailable Dialog Cancel Button "Click"
         *
         * Closes the dialog without saveing any changes to the database.
         */
        $('#manage-unavailable #cancel-unavailable').click(function () {
            $('#manage-unavailable').modal('hide');
        });

        /**
         * Event : Insert Unavailable Time Period Button "Click"
         *
         * When the user clicks this button a popup dialog appears and the use can set a time period where
         * he cannot accept any appointments.
         */
        $('#insert-unavailable').click(function () {
            BackendCalendarUnavailabilitiesModal.resetUnavailableDialog();
            var $dialog = $('#manage-unavailable');

            // Set the default datetime values.
            var start = new Date();
            var currentMin = parseInt(start.toString('mm'));

            if (currentMin > 0 && currentMin < 15) {
                start.set({'minute': 15});
            } else if (currentMin > 15 && currentMin < 30) {
                start.set({'minute': 30});
            } else if (currentMin > 30 && currentMin < 45) {
                start.set({'minute': 45});
            } else {
                start.addHours(1).set({'minute': 0});
            }

            if ($('.calendar-view').length === 0) {
                $dialog.find('#unavailable-provider')
                    .val($('#select-filter-item').val())
                    .closest('.form-group')
                    .hide();
            }

            $dialog.find('#unavailable-start').val(GeneralFunctions.formatDate(start, GlobalVariables.dateFormat, true));
            $dialog.find('#unavailable-end').val(GeneralFunctions.formatDate(start.addHours(1), GlobalVariables.dateFormat, true));
            $dialog.find('.modal-header h3').text(WBLang.new_unavailable_title);
            $dialog.modal('show');
        });
    }

    /**
     * Reset unavailable dialog form.
     *
     * Reset the "#manage-unavailable" dialog. Use this method to bring the dialog to the initial state
     * before it becomes visible to the user.
     */
    exports.resetUnavailableDialog = function () {
        var $dialog = $('#manage-unavailable');

        $dialog.find('#unavailable-id').val('');

        // Set default time values
        var start = GeneralFunctions.formatDate(new Date(), GlobalVariables.dateFormat, true);
        var end = GeneralFunctions.formatDate(new Date().addHours(1), GlobalVariables.dateFormat, true);
        var dateFormat;

        switch (GlobalVariables.dateFormat) {
            case 'DMY':
                dateFormat = 'dd/mm/yy';
                break;
            case 'MDY':
                dateFormat = 'mm/dd/yy';
                break;
            case 'YMD':
                dateFormat = 'yy/mm/dd';
                break;
        }


        $dialog.find('#unavailable-start').datetimepicker({
            dateFormat: dateFormat,
            timeFormat: GlobalVariables.timeFormat === 'regular' ? 'h:mm TT' : 'HH:mm',

            // Translation
            dayNames: [WBLang.sunday, WBLang.monday, WBLang.tuesday, WBLang.wednesday,
                WBLang.thursday, WBLang.friday, WBLang.saturday],
            dayNamesShort: [WBLang.sunday.substr(0, 3), WBLang.monday.substr(0, 3),
                WBLang.tuesday.substr(0, 3), WBLang.wednesday.substr(0, 3),
                WBLang.thursday.substr(0, 3), WBLang.friday.substr(0, 3),
                WBLang.saturday.substr(0, 3)],
            dayNamesMin: [WBLang.sunday.substr(0, 2), WBLang.monday.substr(0, 2),
                WBLang.tuesday.substr(0, 2), WBLang.wednesday.substr(0, 2),
                WBLang.thursday.substr(0, 2), WBLang.friday.substr(0, 2),
                WBLang.saturday.substr(0, 2)],
            monthNames: [WBLang.january, WBLang.february, WBLang.march, WBLang.april,
                WBLang.may, WBLang.june, WBLang.july, WBLang.august, WBLang.september,
                WBLang.october, WBLang.november, WBLang.december],
            prevText: WBLang.previous,
            nextText: WBLang.next,
            currentText: WBLang.now,
            closeText: WBLang.close,
            timeOnlyTitle: WBLang.select_time,
            timeText: WBLang.time,
            hourText: WBLang.hour,
            minuteText: WBLang.minutes,
            firstDay: 0
        });
        $dialog.find('#unavailable-start').val(start);

        $dialog.find('#unavailable-end').datetimepicker({
            dateFormat: dateFormat,
            timeFormat: GlobalVariables.timeFormat === 'regular' ? 'h:mm TT' : 'HH:mm',

            // Translation
            dayNames: [WBLang.sunday, WBLang.monday, WBLang.tuesday, WBLang.wednesday,
                WBLang.thursday, WBLang.friday, WBLang.saturday],
            dayNamesShort: [WBLang.sunday.substr(0, 3), WBLang.monday.substr(0, 3),
                WBLang.tuesday.substr(0, 3), WBLang.wednesday.substr(0, 3),
                WBLang.thursday.substr(0, 3), WBLang.friday.substr(0, 3),
                WBLang.saturday.substr(0, 3)],
            dayNamesMin: [WBLang.sunday.substr(0, 2), WBLang.monday.substr(0, 2),
                WBLang.tuesday.substr(0, 2), WBLang.wednesday.substr(0, 2),
                WBLang.thursday.substr(0, 2), WBLang.friday.substr(0, 2),
                WBLang.saturday.substr(0, 2)],
            monthNames: [WBLang.january, WBLang.february, WBLang.march, WBLang.april,
                WBLang.may, WBLang.june, WBLang.july, WBLang.august, WBLang.september,
                WBLang.october, WBLang.november, WBLang.december],
            prevText: WBLang.previous,
            nextText: WBLang.next,
            currentText: WBLang.now,
            closeText: WBLang.close,
            timeOnlyTitle: WBLang.select_time,
            timeText: WBLang.time,
            hourText: WBLang.hour,
            minuteText: WBLang.minutes,
            firstDay: 0
        });
        $dialog.find('#unavailable-end').val(end);

        // Clear the unavailable notes field.
        $dialog.find('#unavailable-notes').val('');
    };

    exports.initialize = function () {
        var $unavailabilityProvider = $('#unavailable-provider');

        for (var index in GlobalVariables.availableProviders) {
            var provider = GlobalVariables.availableProviders[index];

            $unavailabilityProvider.append(new Option(provider.first_name + ' ' + provider.last_name, provider.id));
        }

        _bindEventHandlers();
    };

})(window.BackendCalendarUnavailabilitiesModal);
