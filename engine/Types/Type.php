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

/**
 * Abstract Type Class
 *
 * This class needs to be extended by the type classes which must implement the validation logic.
 */
abstract class Type {
    /**
     * Class Constructor
     *
     * @param mixed $value The type value to be validated.
     */
    public function __construct($value)
    {
        if ( ! $this->_validate($value))
        {
            throw new \InvalidArgumentException(__CLASS__ . ': Invalid argument value provided (' . $value . ')');
        }

        $this->value = $value;
    }

    /**
     * Get the type value.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * Implement the validation logic in the children classes.
     *
     * @param mixed $value The value to be validated.
     *
     * @return bool Returns whether the value is valid or not.
     */
    abstract protected function _validate($value);
}
