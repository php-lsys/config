<?php
/**
 * lsys config
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Config;
use LSYS\Config;
use LSYS\Exception;
class SubSet implements Config,\Serializable{
	protected $_key;
	protected $_config;
	/**
	 * php file config
	 * @param string $name
	 */
	public function __construct (Config $config,$key){
		$this->_config=$config;
		$this->_key=$key;
	}
	public function name(){
		return $this->_config->name().".".$this->_key;
	}
	public function loaded(){
		return $this->_config->exist($this->_key);
	}
	public function get ($key,$default=NULL){
		return $this->_config->get($this->_key.".".$key,$default);
	}
	public function set ($key,$value = NULL){
		return $this->_config->set($this->_key.".".$key,$value);
	}
	public function exist($key){
		return $this->_config->exist($this->_key.".".$key);
	}
	public function readonly (){
		return $this->_config->readonly();
	}
	public function as_array (){
		$arr=$this->_config->get($this->_key);
		if (is_array($arr))return $arr;
		return array();
	}
	/**
	 * {@inheritDoc}
	 * @see \Serializable::serialize()
	 */
	public function serialize () {
	    if (!$this->_config instanceof \Serializable){
			throw new Exception(__("the config can't be serializable [:class]",array("class"=>get_class($this->_config))));
		}
		$data=array($this->_key,serialize($this->_config));
		return json_encode($data);
	}
	/**
	 * {@inheritDoc}
	 * @see \Serializable::unserialize()
	 */
	public function unserialize ($serialized) {
		list($key,$config)=json_decode($serialized,true);
		$this->_key=$key;
		$this->_config=unserialize($config);
	}
}
