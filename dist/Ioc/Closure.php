<?php

namespace Hope\Core\Ioc
{
    use Hope\Core\Error;

    /**
     * Class Closure
     *
     * @package Hope\Core\Ioc
     */
    class Closure extends Builder
    {

        /**
         * Returns service result
         *
         * @throws \Hope\Core\Error
         *
         * @return mixed
         */
        protected function build()
        {
            if (is_callable($this->_config)) {
                return call_user_func_array($this->_config, []);
            }
            throw new Error('Factory builder must be a callable');
        }
    }

}