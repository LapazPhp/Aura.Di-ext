<?php
namespace Lapaz\Aura\Di\Injection;

use Aura\Di\Resolver\Resolver;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Resolver
     */
    protected $mockResolver;

    protected function setUp()
    {
        $this->mockResolver = $this->createMock(Resolver::class);
        $this->mockResolver->method('resolve')->willReturnCallback(function ($class) {
            return (object)[
                'reflection' => new \ReflectionClass($class),
                'params' => [],
                'setters' => [],
            ];
        });
    }

    public function testModification()
    {
        $factory = new Factory($this->mockResolver, \stdClass::class);
        $factory->modifiedBy(function ($object) {
            $object->prop = 1;
        });

        $object = $factory();
        $this->assertEquals(1, $object->prop);
    }

    public function testModificationScript()
    {
        $factory = new Factory($this->mockResolver, \stdClass::class);
        $factory->modifiedByScript(__DIR__ . '/scripts/modify-with-foo.php', [
            'foo' => 1,
        ]);

        $object = $factory();
        $this->assertEquals(1, $object->prop);
    }

    /**
     * @expectedException \Lapaz\Aura\Di\Exception\InvalidModifier
     */
    public function testInvalidModifier()
    {
        $factory = new Factory($this->mockResolver, \stdClass::class);
        $factory->modifiedBy([new \stdClass(), 'method']);

        $object = $factory();
        $this->assertEquals(1, $object->prop);
    }
}
