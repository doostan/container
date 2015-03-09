<?php
/**
 * Doostan\Container component is a sample Dependency Injection Container.
 * 
 * @link        https://github.com/doostan/container
 * @license     http://opensource.org/licenses/MIT MIT license
 * @copyright   Copyright (c) 2015 https://github.com/doostan
 * @author      doostan doostan.github@gmail.com
 */

namespace Doostan\Container;

class Container
{
    /**
     * @var array holds all services
     */
    protected $services;
    
    /**
     * constructor.
     */
    private function __construct()
    {
        $this->services=array();
    }
    
    /**
     * sets a service.
     *
     * @param string $id
     * @param closure|object $service
     * @return void
     */
    public function setService($id, $service)
    {
        if(is_callable($service)) {
            $this->services[$id] = function($container) use ($service) {
                static $ser=null;
                if ($ser === null) {
                    $ser = $service($container);
                }
                return $ser;
            };
        } elseif (is_object($service)) {
            $this->services[$id] = $service;
        }
    }

    /**
     * returns a service.
     *
     * @param string $id
     * @return mixed
     * @throws Exception
     */
    public function getService($id)
    {
        if (array_key_exists($id, $this->services)) {
            if (is_callable($this->services[$id])) {
                $ser = $this->services[$id];
                return $ser($this);
            } else {
                return $this->services[$id];
            }
        } else {
            throw new \Exception($id.' service not found');
        }
    }
}
