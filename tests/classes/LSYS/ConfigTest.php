<?php
namespace LSYS;
use PHPUnit\Framework\TestCase;
use LSYS\Config\File;
use LSYS\Config\INI;
use LSYS\Config\FileRW;
use LSYS\Config\Arr;
use LSYS\Config\SubSet;
final class ConfigTest extends TestCase
{
    public function testFile()
    {
       File::dirs([__DIR__.'/../../config']);
       $file=new File("aa.a");
       $this->assertTrue($file->loaded());
       $this->assertTrue($file->exist("test"));
       $this->assertFalse($file->exist("test2"));
       $this->assertEquals($file->get("test"), "test");
       $this->assertEquals($file->get("test3",'def'), "def");
       $this->assertEquals($file->name(), "aa.a");
       $this->assertTrue($file->readonly());
       $this->assertArrayHasKey("test",$file->asArray());
       $_file=unserialize(serialize($file));
       $this->assertTrue($_file instanceof File);
       $this->assertTrue($_file->loaded());
       
       
       $filee=new File("aa.ab");
       $this->assertFalse($filee->loaded());
       
       $this->expectException(Exception::class);
       $file->set("test","test");
       
    }
    public function testFilerw()
    {
       File::dirs([__DIR__.'/../../config']);
       $file=new FileRW("aa.a");
       $file->set("test4");
       $file->set("test4.test","test4");
       $this->assertEquals($file->get("test4.test"), "test4");
       $this->assertFalse($file->readonly());
    }
    public function testIni()
    {
        INI::dirs([__DIR__.'/../../config']);
        INI::$section="cccc";
        $file=new INI("test.application");
        $this->assertTrue($file->loaded());
        $this->assertTrue($file->exist("dispatcher"));
        $this->assertFalse($file->exist("dispatcher1"));
        $this->assertEquals($file->name(), "test.application");
        $this->assertTrue($file->readonly());
        $this->assertArrayHasKey("dispatcher",$file->asArray());
    }
    public function testArr()
    {
        $file=new Arr(array("test"=>"teset"),"arrname");
        $file->set("test4.test","test4");
        $this->assertEquals($file->get("test4.test"), "test4");
        $this->assertTrue($file->loaded());
        $this->assertEquals($file->name(), "arrname");
        $this->assertTrue($file->exist("test"));
        $this->assertFalse($file->exist("test1"));
        $this->assertFalse($file->readonly());
        $this->assertArrayHasKey("test",$file->asArray());
    }
    public function testsub()
    {
        $file=new Arr(array("test"=>["test"=>"test"]));
        $file=new SubSet($file, "test");
        $file->set("test4.test","test4");
        $this->assertEquals($file->get("test4.test"), "test4");
        $this->assertTrue($file->loaded());
        $this->assertTrue($file->exist("test"));
        $this->assertFalse($file->exist("test1"));
        $this->assertFalse($file->readonly());
        $this->assertArrayHasKey("test",$file->asArray());
        $_file=unserialize(serialize($file));
        $this->assertTrue($_file instanceof SubSet);
    }
}