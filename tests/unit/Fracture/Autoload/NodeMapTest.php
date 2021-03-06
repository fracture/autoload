<?php


namespace Fracture\Autoload;

use Exception;
use ReflectionClass;
use PHPUnit_Framework_TestCase;

class NodeMapTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Fracture\Autoload\NodeMap::import
     * @covers Fracture\Autoload\NodeMap::getLocations
     *
     * @covers Fracture\Autoload\NodeMap::growElements
     * @covers Fracture\Autoload\NodeMap::setupElement
     * @covers Fracture\Autoload\NodeMap::handleParams
     * @covers Fracture\Autoload\NodeMap::expandBranch
     * @covers Fracture\Autoload\NodeMap::cleanedPath
     * @covers Fracture\Autoload\NodeMap::findNode
     * @covers Fracture\Autoload\NodeMap::extractPaths
     * @covers Fracture\Autoload\NodeMap::cleanUnmappedPart
     *
     * @dataProvider provideSimpleImport
     */
    public function testSimpleImport($config, $path, $class, $result)
    {
        $instance = new NodeMap;
        $instance->import($config, $path);

        $this->assertEquals($result, $instance->getLocations($class));
    }

    public function provideSimpleImport()
    {
        return include FIXTURE_PATH . '/imports-simple.php';
    }
}
