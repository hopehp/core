<?php

namespace Hope\Core\Ioc
{

    use Hope\Core\Injector;

    /**
     * Class Builder
     *
     * @package Hope\Core\Ioc
     */
    abstract class Builder
    {

        /**
         * Service name
         *
         * @var string
         */
        protected $_name;

        /**
         * Builder result value
         *
         * @var mixed
         */
        protected $_value;

        /**
         * Builder config
         *
         * @var mixed
         */
        protected $_config;

        /**
         * If `true` builder runs one times and save result into `$_value`
         *
         * @see Builder::$_value;
         * @var bool
         */
        protected $_shared = false;

        /**
         * Builder owner
         *
         * @var \Hope\Core\Injector
         */
        protected $_injector;

        /**
         * Methods to calling
         *
         * @var array
         */
        protected $_methods;

        /**
         * Arguments for constructor
         *
         * @var array
         */
        protected $_arguments;

        /**
         * Object properties for setting
         *
         * @var array
         */
        protected $_properties;

        /**
         * Object interfaces
         *
         * @var string[]
         */
        protected $_interfaces = [];

        /**
         * Create builder instance
         *
         * @param string               $name
         * @param mixed                $config
         * @param \Hope\Core\Injector $injector
         */
        public function __construct($name, $config, Injector $injector)
        {
            $this->_name = $name;
            $this->_config = $config;
            $this->_injector = $injector;
        }

        /**
         * Returns service result
         *
         * @return mixed
         */
        abstract protected function build();

        /**
         * Returns builder name
         *
         * @return string
         */
        public function getName()
        {
            return $this->_name;
        }

        /**
         * Returns builder result value
         *
         * @return mixed
         */
        public function getValue()
        {
            if ($this->isShared() && $this->_value) {
                return $this->_value;
            }
            return $this->_value = $this->build();
        }

        /**
         * Register builder as shared
         *
         * @return \Hope\Core\Ioc\Builder
         */
        public function shared()
        {
            return $this->setShared(true);
        }

        /**
         * Returns `true` if builder is shared
         *
         * @return bool
         */
        public function isShared()
        {
            return $this->_shared;
        }

        /**
         * Set builder sharing state
         *
         * @param bool $bool
         *
         * @return \Hope\Core\Ioc\Builder
         */
        public function setShared($bool)
        {
            $this->_shared = (bool) $bool;
            return $this;
        }

        /**
         * Returns builder sharing state
         *
         * @return bool
         */
        public function getShared()
        {
            return $this->_shared;
        }

        /**
         * Create builder instance
         *
         * @param string               $name
         * @param mixed                $config
         * @param \Hope\Core\Injector $injector
         *
         * @return Builder
         */
        public static function make($name, $config, Injector $injector)
        {
            return new static($name, $config, $injector);
        }

        /**
         * Register that this service resolve interfaces
         *
         * @param string ...$class
         *
         * @return \Hope\Core\Ioc\Builder
         */
        public function implement(...$classes)
        {
            foreach ($classes as $class) {
                $this->_interfaces[] = $class;
                $this->_injector->set($class, $this);
            }
            return $this;
        }

        /**
         * Method calling
         *
         * Says that method need call after build
         *
         * @param string $method
         * @param array  $args
         *
         * @return \Hope\Core\Ioc\Builder
         */
        public function call($method, array $args)
        {
            $this->_methods[$method] = $args;
            return $this;
        }

        /**
         * Property setting
         *
         * Says that property need set
         *
         * @param string $property
         * @param mixed  $value
         *
         * @return \Hope\Core\Ioc\Builder
         */
        public function set($property, $value)
        {
            $this->_properties[$property] = $value;
            return $this;
        }

    }

}