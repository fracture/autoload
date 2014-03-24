<?php

namespace Fracture\Autoload;

use Exception;
use ReflectionClass;
use PHPUnit_Framework_TestCase;

class NodeTest extends PHPUnit_Framework_TestCase
{


    /**
     * @covers Fracture\Autoload\Node::setNamespace
     * @covers Fracture\Autoload\Node::getNamespace
     */
    public function testSettingOfNamespace()
    {
        $instance = new Node;
        $instance->setNamespace('foobar');

        $this->assertEquals('foobar', $instance->getNamespace());
    }


    /**
     * @covers Fracture\Autoload\Node::getPaths
     */
    public function testWithNoPaths()
    {
        $instance = new Node;
        $this->assertEquals([], $instance->getPaths());
    }

    /**
     * @dataProvider provideAddingOfPaths
     *
     * @covers Fracture\Autoload\Node::addPath
     * @covers Fracture\Autoload\Node::getPaths
     * @covers Fracture\Autoload\Node::findUniquePaths
     */
    public function testAddingOfPaths($list, $result)
    {
        $instance = new Node;
        foreach ($list as $path) {
            $instance->addPath($path);
        }

        $this->assertEquals($result, $instance->getPaths());
    }


    public function provideAddingOfPaths()
    {
        return include FIXTURE_PATH . '/node-paths.php';
    }


    /**
     * @covers Fracture\Autoload\Node::addPath
     * @covers Fracture\Autoload\Node::getPaths
     * @covers Fracture\Autoload\Node::findUniquePaths
     */
    public function testRepeatedAddingOfPaths()
    {
        $instance = new Node;

        $instance->addPath('/duplicate');
        $instance->addPath('/unique');
        $temp  = $instance->getPaths();
        $instance->addPath('/duplicate');

        $this->assertEquals([
            '/duplicate',
            '/unique',
        ], $instance->getPaths());
    }


    /**
     * @covers Fracture\Autoload\Node::addChild
     * @covers Fracture\Autoload\Node::getNamespace
     */
    public function testSimpleChildAddition()
    {
        $parent = new Node;
        $child = new Node;

        $parent->addChild('test', $child);

        $this->assertEquals('test', $child->getNamespace());
    }


    /**
     * @dataProvider provideChildAdditionWithParentNamespace
     *
     * @covers Fracture\Autoload\Node::addChild
     * @covers Fracture\Autoload\Node::setNamespace
     * @covers Fracture\Autoload\Node::getNamespace
     */
    public function testChildAdditionWithParentNamespace($namespace, $name, $result)
    {
        $parent = new Node;
        $child = new Node;

        $parent->setNamespace($namespace);
        $parent->addChild($name, $child);

        $this->assertEquals($result, $child->getNamespace());
    }


    public function provideChildAdditionWithParentNamespace()
    {
        return include FIXTURE_PATH . '/node-namespaces.php';
    }


    /**
     * @dataProvider provideCheckIfChildExists
     *
     * @covers Fracture\Autoload\Node::addChild
     * @covers Fracture\Autoload\Node::hasChild
     */
    public function testCheckIfChildExists($names, $key, $result)
    {
        $instance = new Node;

        foreach ($names as $name) {
            $instance->addChild($name, new Node);
        }

        $this->assertEquals($result, $instance->hasChild($key));
    }

    public function provideCheckIfChildExists()
    {
        return include FIXTURE_PATH . '/node-children.php';
    }


    /**
     * @covers Fracture\Autoload\Node::addChild
     * @covers Fracture\Autoload\Node::getChild
     */
    public function testGetterForChildNodes()
    {
        $instance = new Node;
        $instance->addChild('foo', new Node);

        $this->assertInstanceOf(
            'Fracture\Autoload\Node',
            $instance->getChild('foo')
        );
        $this->assertNull($instance->getChild('bar'));
    }
}
