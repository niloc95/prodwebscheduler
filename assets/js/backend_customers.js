/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

window.BackendCustomers = window.BackendCustomers || {};

/**
 * Backend Customers
 *
 * Backend Customers javascript namespace. Contains the main functionality of the backend customers
 * page. If you need to use this namespace in a different page, do not bind the default event handlers
 * during initialization.
 *
 * @module BackendCustomers
 */
(function (exports) {

    'use strict';

    /**
     * The page helper contains methods that implement each record type functionality
     * (for now there is only the CustomersHelper).
     *
     * @type {Object}
     */
    var helper = {};

    /**
     * This method initializes the backend customers page. If you use this namespace
     * in a different page do not use this method.
     *
     * @param {Boolean} defaultEventHandlers Optional (false), whether to bind the default
     * event handlers or not.
     */
    exports.initialize = function (defaultEventHandlers) {
        defaultEventHandlers = defaultEventHandlers || false;

        helper = new CustomersHelper();
        helper.resetForm();
        helper.filter('');

        if (defaultEventHandlers) {
            _bindEventHandlers();
        }
    };

    /**
     * Default event handlers declaration for backend customers page.
     */
    function _bindEventHandlers() {
        helper.bindEventHandlers();
    }

})(window.BackendCustomers);
