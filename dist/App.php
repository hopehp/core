<?php

namespace Hope\Core
{

    use Hope\Util\Set;
    use Hope\Util\Path;

    /**
     * Class App
     *
     * @package Hope\Core
     * @property \Hope\Util\Path $path
     * @property \Hope\Util\Set $config
     * @property \Hope\Core\Injector $injector
     */
    class App extends Object
    {

        use Emitter;

        /**
         * Application injector
         *
         * @var \Hope\Core\Injector
         */
        protected $_injector;

        /**
         * Application constructor
         *
         * @throws \Hope\Core\Error
         */
        public function __construct()
        {
            $this->_injector = new Injector([
                'app' => $this,
                'path' => new Path(),
                'config' => new Config(),
            ]);

            // Register paths
            $this->path->addLink('root', getcwd());
            $this->path->addLink('hope', dirname(__DIR__));
        }

        /**
         * Returns values from injector
         *
         * @param string $name
         *
         * @throws \Hope\Core\Error
         *
         * @return mixed
         */
        public function __get($name)
        {
            return $this->_injector->get($name);
        }

        /**
         * Make a service
         *
         * @param string|array $service
         * @param bool         $throw
         *
         * @throws \Hope\Core\Error
         *
         * @return bool|mixed
         */
        public function make($service, $throw = true)
        {
            if (is_string($service)) {
                if ($this->injector->exists($service)) {
                    return $this->injector->get($service);
                } else if (class_exists($service)) {
                    return $this->injector;
                }
            } else if ($throw) {
                throw new Error();
            }
            return false;
        }

        /**
         * Configure application configuration
         *
         * @param string                $env [optional]
         * @param string|array|callable $config
         *
         * @return \Hope\Core\App
         */
        public function config($env, $config = null)
        {
            if (is_null($config)) {
                $config = $env;
            }

            if (is_string($config)) {
                $config = $this->import($config);
            }

            if (is_callable($config)) {
                $config = call_user_func($config, $this);
            }

            if (is_array($config)) {
                $this->config->set($config);
            }

            return $this;
        }

        /**
         * File to import
         *
         * @param string $file
         *
         * @return mixed
         */
        public function import($file)
        {
            if ($path = $this->path->resolve($file)) {
                /** @var string $path */
                return require ($path);
            }
            return false;
        }

    }

}