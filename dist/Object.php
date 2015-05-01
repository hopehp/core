<?php

namespace Hope\Core
{

    /**
     * Class Object
     *
     * @package Hope\Core
     */
    abstract class Object
    {

        /**
         * Instantiate object
         *
         * @param array $options
         */
        public function __construct(array $options = [])
        {
            foreach ($options as $key => $value) {
                $this->{$key} = $value;
            }
        }

        /**
         * Magic property setter
         *
         * @param string $key
         * @param mixed  $value
         *
         * @throws \Hope\Core\Error
         *
         * @return mixed
         */
        public function __set($key, $value)
        {
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {
                return call_user_func($method, $value);
            }

            throw new Error(['Can\'t set property %s for object', $key]);
        }

        /**
         * Magic property getter
         *
         * @param string $name
         *
         * @throws \Hope\Core\Error
         *
         * @return mixed
         */
        public function __get($name)
        {
            $method = 'get' . ucfirst($name);

            if (method_exists($this, $method)) {
                return call_user_func($method);
            }

            throw new Error(['Can\'t get property %s from object', $name]);
        }

        /**
         * @param string $name
         *
         * @return bool
         */
        public function hasSetter($name)
        {
            return $this->hasMethod('set' . ucfirst($name));
        }

        /**
         * @param string $name
         *
         * @return bool
         */
        public function hasGetter($name)
        {
            return $this->hasMethod('get' . ucfirst($name));
        }

        /**
         * @param string $name Method name
         *
         * @return bool
         */
        public function hasMethod($name)
        {
            return method_exists($this, $name);
        }

    }

}