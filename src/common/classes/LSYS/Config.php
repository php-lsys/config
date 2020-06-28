<?php
/**
 * lsys config
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS;
interface Config{
	/**
	 * return config name
	 * @return string
	 */
	public function name():string;
	/**
	 * is loaded config
	 * @return bool
	 */
	public function loaded():bool;
	/**
	 * get config
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get (string $key,$default=NULL);
	/**
	 * set config
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	public function set (string $key,$value = NULL):bool;
	/**
	 * check if key is exist
	 * @param string $key
	 * @return bool
	 */
	public function exist(string $key):bool;
	/**
	 * check config is readonly
	 * @return bool
	 */
	public function readonly ():bool;
	/**
	 * to config is array
	 * @return array
	 */
	public function asArray():array;
}