<?php
/**
 *
 */
namespace Hope\Core
{

    /**
     * Class Injector
     *
     * @package Hope\Core
     */
    class Injector extends Object
    {

        /**
         * Injector values configuration
         *
         * @var array
         */
        protected $_config = [];

        /**
         * @var array
         */
        protected $_values = [];

        /**
         * Create injector instance
         *
         * @param array $values [optional]
         */
        public function __construct(array $values = null)
        {
            if (count($values)) {
                $this->_values = $values;
            }
            $this->set('injector', $this);
        }

        /**
         * Register injector raw value
         *
         * @param string $name
         * @param mixed  $value
         *
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Core\Injector
         */
        public function set($name, $value)
        {
            if (false === is_string($name)) {
                throw new Error('Injector value name must be a string');
            }
            $this->_values[$name] = $value;

            return $this;
        }

        /**
         * Returns injector value
         *
         * @param string $name  Injector value key name
         * @param bool   $throw [optional] If `true` throws Error
         *
         * @throws \Hope\Core\Error Throws error if value not found and `$throw` argument is `true`
         *
         * @return bool|mixed
         */
        public function get($name, $throw = true)
        {
            if (false === isset($this->_values[$name])) {
                if ($throw) {
                    throw new Error(['Service %s not found in injector', $name]);
                }
                return false;
            }
            $value = $this->_values[$name];

            if ($value instanceof Ioc\Builder) {
                return $value->getValue();
            }
            return $value;
        }

        /**
         * Register service
         *
         * @param string        $name
         * @param string|array  $service
         *
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Core\Ioc\Builder
         */
        public function service($name, $service)
        {
            $this->set($name,
                $service = Ioc\Object::make($name, $service, $this)
                    ->shared()
            );

            return $service;
        }

        /**
         * Register new injector factory builder
         *
         * @param string         $name
         * @param callable|array $factory
         *
         * @return \Hope\Core\Ioc\Builder
         */
        public function factory($name, $factory)
        {

        }

        /**
         * Check if key exists
         *
         * @param string $name
         *
         * @return bool
         */
        public function exists($name)
        {
            return isset($this->_values[$name]);
        }

        /**
         * Returns injector configuration
         *
         * @return array
         */
        public function getConfig()
        {
            return $this->_config;
        }

        /**
         * Setup injector
         *
         * @param array $config
         */
        public function setConfig(array $config)
        {
            $this->_config = $config;
        }

    }

}