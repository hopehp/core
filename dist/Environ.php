<?php

namespace Hope\Core
{

    /**
     * Class Environ
     *
     * @package Hope\Core
     */
    class Environ
    {

        /**
         * Predefined environment names
         */
        const DEVELOPMENT   = 'development';
        const PRODUCTION    = 'production';
        const STAGING       = 'staging';
        const TESTING       = 'testing';

        /**
         * Environment name
         *
         * @var string
         */
        protected $_name = Environ::DEVELOPMENT;

        /**
         * Returns environment name
         *
         * @return string
         */
        public function getName()
        {
            return $this->_name;
        }

        /**
         * Set environment name
         *
         * @param string $name
         *
         * @return \Hope\Core\Environ
         */
        public function setName($name)
        {
            $this->_name = $name;
            return $this;
        }
    }

}