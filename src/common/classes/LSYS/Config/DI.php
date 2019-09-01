<?php
namespace LSYS\Config;
/**
 * @method \LSYS\Config config($name)
 */
class DI extends \LSYS\DI{
    /**
     * @return static
     */
    public static function get(){
        $di=parent::get();
        !isset($di->config)&&$di->config(new \LSYS\DI\ShareCallback(function($name){
            return $name;
        },function($name){
            return new \LSYS\Config\File($name);
        }));
        return $di;
    }
}