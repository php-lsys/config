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
class File implements Config,\Serializable{
	//find the dir
	protected static $_dirs=array();
	/**
	 * set config scan dir
	 * @param array $dirs
	 */
	public static function dirs(array $dirs,$overwrite=false){
		foreach ($dirs as $k=>&$v){
			if ($v==null)unset($dirs[$k]);
			$v=strval(rtrim($v,'\\/').DIRECTORY_SEPARATOR);
		}
		if (!$overwrite)$dirs=array_merge($dirs,self::$_dirs);
		self::$_dirs=$dirs;
	}
	protected $_load;
	protected $_file;
	protected $_name;
	protected $_dir;
	protected $_node=array();
	protected static $_cahe=array();
	/**
	 * php file config
	 * @param string $name
	 */
	public function __construct ($name,$dir=NULL){
		$this->_load=false;
		$this->_name=$name;
		$this->_dir=$dir;
		if ($this->_dir!==null){
			$this->_dir=strval(rtrim($this->_dir,'\\/').DIRECTORY_SEPARATOR);
		}
		$group=$this->_init();
		if($group===false)return;
		$tmp=self::$_cahe[$this->_file];
		$this->_node=&$tmp;
		$this->_load=true;
		while (count($group)){
			$node=array_shift($group);
			if(isset($this->_node[$node])){
				$this->_node=&$this->_node[$node];
			}else{
				$this->_load=false;
				$this->_node=array();
				break;
			}
		}
	}
	
	protected function _init(){
		$group= explode('.', $this->_name);
		$path=array_shift($group);
		if ($this->_dir!==null)$file=$this->_dir.$path.".php";
		else foreach (self::$_dirs as $v){
			$file=$v.$path.".php";
			if (is_file($file))break;
			else unset($file);
		}
		if (!isset($file)) return false;
		if(!isset(self::$_cahe[$file])){
			self::$_cahe[$file]=include $file;
			if(!is_array(self::$_cahe[$file])){
				unset(self::$_cahe[$file]);
				return false;
			}
		}
		$this->_file=$file;
		return $group;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::loaded()
	 */
	public function loaded(){
		return $this->_load;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::name()
	 */
	public function name(){
		return $this->_name;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::get()
	 */
	public function get ($key,$default=NULL){
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
	 * @see \LSYS\Config::get()
	 */
	public function exist($key){
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
	 * @see \LSYS\Config::asArray()
	 */
	public function asArray(){
		return $this->_node;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::set()
	 */
	public function set ($key,$value = NULL){
		throw new Exception(__("file config not support set method"));//文件配置不支持设置操作
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::readonly()
	 */
	public function readonly (){
		return true;
	}
	/**
	 * dump object
	 */
	public function __debugInfo(){
		return array(
			'find_dir'=>self::$_dirs,
			'dir'=>$this->_dir,
			'loaded'=>$this->loaded(),
			'file'=>$this->_file,
			'name'=>$this->_name,
			'note'=>$this->_node,
			'readonly'=>$this->readonly(),
		);
	}
	/**
	 * {@inheritDoc}
	 * @see \Serializable::serialize()
	 */
	public function serialize () {
		return $this->_name;
	}
	/**
	 * {@inheritDoc}
	 * @see \Serializable::unserialize()
	 */
	public function unserialize ($serialized) {
		$this->__construct($serialized);
	}
}
