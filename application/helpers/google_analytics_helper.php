<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

/**
 * Print Google Analytics script.
 *
 * This helper function should be used in view files in order to output the Google Analytics
 * script. It will check whether the code is set in the database and print it, otherwise nothing
 * will be outputted. This eliminates the need for extra checking before outputting.
 */
function google_analytics_script()
{
    $ci =& get_instance();

    $ci->load->model('settings_model');

    $google_analytics_code = $ci->settings_model->get_setting('google_analytics_code');

    if ($google_analytics_code !== '')
    {
        echo '
            <script>
                (function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,"script","//www.google-analytics.com/analytics.js","ga");
                ga("create", "' . $google_analytics_code . '", "auto");
                ga("send", "pageview");
            </script>
        ';
    }
}
