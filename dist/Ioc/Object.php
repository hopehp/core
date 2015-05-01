<?php

namespace Hope\Core\Ioc
{

    use Hope\Core\Error;
    use Hope\Core\Injector;

    /**
     * Class Object
     *
     * @package Hope\Core\Ioc
     */
    class Object extends Builder
    {


        /**
         * Class reflection instance
         *
         * @var \ReflectionClass
         */
        protected $_reflection;

        /**
         * @inheritdoc
         */
        public function __construct($name, $config, Injector $injector)
        {
            parent::__construct($name, $config, $injector);

            /**
             * Analyze class hierarchy
             */
            if (is_string($config)) {
                $this->fetchImplements($config);
            } else if (is_array($config) && isset($config['class'])) {
                $this->fetchImplements($config['class']);
            }
        }

        protected function fetchImplements($class)
        {
            if (class_exists($class)) {
                $this->itImplements($class);

                array_map(function ($interface) {
                    $this->itImplements($interface);
                }, array_merge(class_implements($class), class_parents($class)));
            }
        }

        protected function fetchArguments(\ReflectionClass $class)
        {
            foreach ($class->getConstructor()->getParameters() as $param) {
                $name = $param->getClass()
                    ? $param->getClass()->getName()
                    : $param->getName();

                $this->_arguments[$name] = $param->isOptional();
            }
        }

        /**
         * Returns service result
         *
         * @throws \Hope\Core\Error
         *
         * @return mixed
         */
        protected function build()
        {
            $class = $this->getClass();
            $this->fetchArguments($class);

            $params = [];
            foreach ($this->_arguments as $name => $optional) {
                if ($value = $this->_injector->get($name, !$optional)) {
                    $params[] = $value;
                }
            }

            // Instantiate object
            return $class->newInstanceArgs($params);
        }

        /**
         * Returns class name
         *
         * @throws \Hope\Core\Error
         *
         * @return \ReflectionClass
         */
        protected function getClass()
        {
            if ($this->_reflection) {
                return $this->_reflection;
            }
            if (is_string($this->_config)) {
                $name = $this->_config;
            } else if (is_array($this->_config) && isset($this->_config['class'])) {
                $name = $this->_config['class'];
            } else {
                throw new Error('Class name not defined in object service');
            }

            if (false === class_exists($name)) {
                throw new Error('Class not found');
            }

            return $this->_reflection = new \ReflectionClass($name);
        }

        public function itImplements($class)
        {
            if (false === in_array($class, $this->_interfaces)) {
                $this->_interfaces[] = $class;
                $this->_injector->set($class, $this);
            }
            return $this;
        }


        /**
         * Debug information
         *
         * @return array
         */
        public function __debugInfo() {
            return [
                'methods' => $this->_methods,
                'arguments' => $this->_arguments,
                'properties' => $this->_properties,
                'interfaces' => $this->_interfaces,
            ];
        }

    }

}