<?php
namespace Lapaz\Aura\Di\Injection;

use Aura\Di\Injection\LazyArray;
use Aura\Di\Injection\LazyInterface;
use PHPUnit\Framework\TestCase;

class LazyIncludeTest extends TestCase
{
    public function testLaziness()
    {
        $lazyObject = new LazyInclude(__DIR__ . '/scripts/returns-abc.php');

        $this->assertInstanceOf(LazyInterface::class, $lazyObject);
    }

    public function testEvalScriptWithoutParams()
    {
        $lazyInclude = new LazyInclude(__DIR__ . '/scripts/returns-abc.php');

        $this->assertEquals('abc', $lazyInclude->__invoke());
    }

    public function testEvalLazyScriptNameWithoutParams()
    {
        $lazyScriptName = $this->createMock(LazyInterface::class);
        $lazyScriptName->method('__invoke')->willReturn(__DIR__ . '/scripts/returns-abc.php');

        $lazyRequire = new LazyInclude($lazyScriptName);

        $this->assertEquals('abc', $lazyRequire->__invoke());
    }

    public function testEvalScriptWithParams()
    {
        $lazyInclude = new LazyInclude(__DIR__ . '/scripts/returns-foo-bar.php', [
            'foo' => 'a',
            'bar' => 'b',
        ]);

        $this->assertEquals(['a', 'b'], $lazyInclude->__invoke());
    }

    public function testEvalScriptWithLazyParams()
    {
        $mockFoo = $this->createMock(LazyInterface::class);
        $mockFoo->method('__invoke')->willReturn('a');

        $mockBar = $this->createMock(LazyInterface::class);
        $mockBar->method('__invoke')->willReturn('b');

        if (class_exists(LazyArray::class)) {
            $lazyArray = new LazyArray([
                'foo' => $mockFoo,
                'bar' => $mockBar,
            ]);
        } else {
            $lazyArray = [
                'foo' => $mockFoo,
                'bar' => $mockBar,
            ];
        }

        $lazyInclude = new LazyInclude(__DIR__ . '/scripts/returns-foo-bar.php', $lazyArray);

        $this->assertEquals(['a', 'b'], $lazyInclude->__invoke());
    }
}
