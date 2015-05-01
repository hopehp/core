<?php

namespace Hope\Core
{
    use Hope\Util\Queue\Sorted;

    /**
     * Class Emitter
     *
     * @package Hope\Core
     */
    trait Emitter
    {

        /**
         * Events list
         *
         * @var \Hope\Util\Queue\Sorted[]
         */
        protected $_events;

        /**
         * @param          $name
         * @param callable $handler
         * @param int      $priority
         *
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Core\Emitter
         */
        public function on($name, callable $handler, $priority = 0)
        {
            return $this->addListener($name, $handler, $priority);
        }

        /**
         * Detach event handler
         *
         * @param string   $name
         * @param callable $handler
         *
         * @return bool
         */
        public function off($name, callable $handler)
        {
            return $this->removeListener($name, $handler);
        }

        /**
         * Emit event handlers
         *
         * @param string|Event $name
         * @param array        $args
         *
         * @throws \Hope\Core\Error
         *
         * @return bool
         */
        public function emit($name, array $args = [])
        {
            $event = $name instanceof Event ? $name : new Event($name);

            if (false === $this->hasListeners($event->getName())) {
                return false;
            }

            array_unshift($args, $event);

            foreach ($this->getListeners($event->getName()) as $handler) {
                call_user_func_array($handler, $args);
                // If handler stopped event dispatch
                if ($event->isStopped()) {
                    break;
                }
            }

            return true;
        }


        /**
         * Register event listener
         *
         * @param string   $name
         * @param callable $handler
         * @param int      $priority [optional]
         *
         * @throws \Hope\Core\Error
         *
         * @return $this
         */
        public function addListener($name, callable $handler, $priority = 0)
        {
            if (false === is_string($name)) {
                throw new Error(['Event name must be a string. %s given', gettype($name)]);
            }
            if (false === $this->hasListeners($name)) {
                $this->_events[$name] = new Sorted();
            }
            $this->_events[$name]->insert($handler, $priority);

            return $this;
        }

        /**
         * @param string   $name
         * @param callable $handler
         *
         * @return bool
         */
        public function removeListener($name, callable $handler)
        {
            if (false === $this->hasListeners($name)) {
                return false;
            }
            return $this->_events[$name]->delete($handler);
        }

        /**
         * Remove all event listeners
         *
         * @param string $name
         *
         * @return $this
         */
        public function removeListeners($name)
        {
            if ($this->hasListeners($name)) {
                $this->_events[$name]->clear();
            }
            return $this;
        }

        /**
         * Returns `true` if event has at least one listener
         *
         * @param string $name
         *
         * @throws \Hope\Core\Error
         *
         * @return bool
         */
        public function hasListeners($name)
        {
            if (false === is_string($name)) {
                throw new Error(['Event name must be a string. %s given', gettype($name)]);
            }
            return isset($this->_events[$name]) && !$this->_events[$name]->isEmpty();
        }

        /**
         * Returns event listeners or empty list
         *
         * @param string $name
         *
         * @return array
         */
        public function getListeners($name)
        {
            return $this->hasListeners($name) ? $this->_events[$name]->extract() : [];
        }
    }

}