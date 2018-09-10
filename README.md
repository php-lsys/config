# 配置层  

[![Build Status](https://travis-ci.com/php-lsys/config.svg?branch=master)](https://travis-ci.com/php-lsys/config)
[![Coverage Status](https://coveralls.io/repos/github/php-lsys/config/badge.svg?branch=master)](https://coveralls.io/github/lsys/config?branch=master)

> 封装此类库是为了实现功能与配置的分离
> 接口参考yaf的config接口


使用示例:
```
//-----------------------通过文件------------------------
//配置文件 :dome/config/aa.php
use LSYS\Config\File;
$c=new File("aa.a");
var_dump($c->get('fasd'));
```
```
//-----------------------可写的文件------------------------
//配置文件 :dome/config/aa.php
use LSYS\Config\FileRW;
$c = new FileRW("aa");
$c->set("a",array("fasd"=>"fasdf","faasdsd"=>"fadafdssdf"));
var_dump($c->get("a"));
```
```
//-----------------------INI文件------------------------
//配置文件 :dome/config/application.ini
//此方式跟yaf的config的ini类似
use LSYS\Config\INI;
//选择区段,区段间可继承,方便各种环境切换
INI::$section='cccc';
$c=new INI("application.application");
print_r($c->get('dispatcher'));
```

```
//---------------------数组配置----------------------------
//通过数组生成配置对象,方便你已有配置,需转换成config接口对象时使用
$config=new LSYS\Config\Arr("name",array(
'dome'=>'domevalue'
/*示例数组*/
));
```

> 如果你的配置需求上面的还不能满足,你实现下config接口吧...