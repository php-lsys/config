<?php
/**
 * lsys config
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Config;
use LSYS\Config;
class Arr implements Config,\Serializable{
	protected $_node=array();
	protected $_name;
	/**
	 * php file config
	 * @param string $name
	 */
	public function __construct (array $array,string $name=null){
		$this->_node=$array;
		$this->_name=$name?$name:uniqid();
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::loaded()
	 */
	public function loaded():bool{
		return count($this->_node)>0;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::name()
	 */
	public function name():string{
		return $this->_name;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::get()
	 */
	public function get(string $key,$default=NULL){
		$group= explode('.', $key);
		$t=$this->_node;
		while (count($group)){
			$node=array_shift($group);
			if(isset($t[$node])){
				$t=&$t[$node];
			}else return $default;
		}
		return $t;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::asArray()
	 */
	public function asArray():array{
	    return is_array($this->_node)?$this->_node:[];
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::set()
	 */
	public function set (string $key,$value = NULL):bool{
		$keys=explode(".",$key);
		$config=&$this->_node;
		foreach ($keys as $v){
			if(!isset($config[$v]))$config[$v]=array();
			$config=&$config[$v];
		}
		if ($config!=$value){
			$config=$value;
		}
		return true;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::exist()
	 */
	public function exist(string $key):bool{
		$group= explode('.', $key);
		$t=$this->_node;
		while (count($group)){
			$node=array_shift($group);
			if(isset($t[$node])){
				$t=&$t[$node];
			}else return false;
		}
		return true;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::readonly()
	 */
	public function readonly ():bool{
		return false;
	}
	/**
	 * {@inheritDoc}
	 * @see \Serializable::serialize()
	 */
	public function serialize () {
		return json_encode(array($this->_name,$this->_node));
	}
	/**
	 * {@inheritDoc}
	 * @see \Serializable::unserialize()
	 */
	public function unserialize ($serialized) {
		list($this->_name,$this->_node)=json_decode($serialized,true);
	}
}
