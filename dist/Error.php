<?php

namespace Hope\Core
{

    use Exception;

    /**
     * Class Error
     *
     * @package Hope\Core
     */
    class Error extends Exception
    {

        /**
         * Make exception
         *
         * @param string|array $message
         * @param int          $code     [optional]
         * @param \Exception   $previous [optional]
         *
         * @return \Hope\Core\Error
         */
        public function __construct($message = "", $code = 0, Exception $previous = null)
        {
            if (is_array($message)) {
                $message = call_user_func_array('sprintf', $message);
            }
            parent::__construct($message, $code, $previous);
        }

    }


}