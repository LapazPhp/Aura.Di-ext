<?php
namespace Lapaz\Aura\Di\Injection;

use Aura\Di\Injection\LazyArray;
use Aura\Di\Injection\LazyInterface;
use PHPUnit\Framework\TestCase;

class LazyRequireTest extends TestCase
{
    public function testLaziness()
    {
        $lazyObject = new LazyRequire(__DIR__ . '/scripts/returns-abc.php');

        $this->assertInstanceOf(LazyInterface::class, $lazyObject);
    }

    public function testEvalScriptWithoutParams()
    {
        $lazyRequire = new LazyRequire(__DIR__ . '/scripts/returns-abc.php');

        $this->assertEquals('abc', $lazyRequire->__invoke());
    }

    public function testEvalLazyScriptNameWithoutParams()
    {
        $lazyScriptName = $this->createMock(LazyInterface::class);
        $lazyScriptName->method('__invoke')->willReturn(__DIR__ . '/scripts/returns-abc.php');

        $lazyRequire = new LazyRequire($lazyScriptName);

        $this->assertEquals('abc', $lazyRequire->__invoke());
    }

    public function testEvalScriptWithParams()
    {
        $lazyRequire = new LazyRequire(__DIR__ . '/scripts/returns-foo-bar.php', [
            'foo' => 'a',
            'bar' => 'b',
        ]);

        $this->assertEquals(['a', 'b'], $lazyRequire->__invoke());
    }

    public function testEvalScriptWithLazyParams()
    {
        $mockFoo = $this->createMock(LazyInterface::class);
        $mockFoo->method('__invoke')->willReturn('a');

        $mockBar = $this->createMock(LazyInterface::class);
        $mockBar->method('__invoke')->willReturn('b');

        $lazyArray = new LazyArray([
            'foo' => $mockFoo,
            'bar' => $mockBar,
        ]);

        $lazyRequire = new LazyRequire(__DIR__ . '/scripts/returns-foo-bar.php', $lazyArray);

        $this->assertEquals(['a', 'b'], $lazyRequire->__invoke());
    }
}
