<?php

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

namespace WB\Engine\Types;

class Url extends NonEmptyText {
    protected function _validate($value)
    {
        return parent::_validate($value) && filter_var($value, FILTER_VALIDATE_URL);
    }
}
