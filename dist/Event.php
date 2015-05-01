<?php

namespace Hope\Core
{

    /**
     * Class Event
     *
     * @package Hope\Core
     */
    class Event extends Object
    {

        /**
         * Event name
         *
         * @var string
         */
        protected $_name;

        /**
         * Event handle state
         *
         * @var bool
         */
        protected $_stop = false;

        /**
         * Event fired state
         *
         * @var bool
         */
        protected $_fired;

        /**
         * Create event
         *
         * @param string $name
         */
        public function __construct($name = null)
        {
            if (is_string($name)) {
                $this->setName($name);
            }
        }

        /**
         * Set event name
         *
         * @param string $name
         *
         * @return \Hope\Core\Event
         */
        public function setName($name)
        {
            $this->_name = $name;
            return $this;
        }

        /**
         * Returns event name
         *
         * @return string
         */
        public function getName()
        {
            return $this->_name;
        }

        /**
         * Stop event handling loop
         *
         * @return \Hope\Core\Event
         */
        public function stop()
        {
            $this->_stop = true;
            return $this;
        }

        /**
         * Returns true if event handling is stopped
         *
         * @return bool
         */
        public function isStopped()
        {
            return $this->_stop;
        }

    }

}