<?php

namespace Opis\Closure\Test\PHP80;

use Opis\Closure\ReflectionClosure;
use Opis\Closure\Test\SerializeTestCase;

class NestedClosureFromArrowFunctionTest extends SerializeTestCase
{
    public function testFnReturningStaticFunction()
    {
        $factory = fn() => static function() { return 'inner'; };
        $closure = $factory();

        $result = $this->process($closure);
        $this->assertSame('inner', $result());
    }

    public function testFnReturningFunction()
    {
        $factory = fn() => function() { return 'inner'; };
        $closure = $factory();

        $result = $this->process($closure);
        $this->assertSame('inner', $result());
    }

    public function testFnReturningFn()
    {
        $factory = fn() => fn() => 'arrow_inner';
        $closure = $factory();

        $result = $this->process($closure);
        $this->assertSame('arrow_inner', $result());
    }

    public function testThreeLevelDeepNesting()
    {
        $factory = fn() => fn() => static function() { return 'deep'; };
        $closure = $factory()();

        $result = $this->process($closure);
        $this->assertSame('deep', $result());
    }

    public function testStandaloneFunction()
    {
        $closure = static function() { return 'standalone'; };

        $result = $this->process($closure);
        $this->assertSame('standalone', $result());
    }

    public function testStandaloneFn()
    {
        $closure = fn() => 'arrow';

        $result = $this->process($closure);
        $this->assertSame('arrow', $result());
    }

    public function testFactorySelfSerialization()
    {
        $factory = fn() => static function() { return 'inner'; };

        $restored = $this->process($factory);
        $this->assertSame('inner', $restored()());
    }

    public function testFnWithParamReturningFunctionWithUse()
    {
        $value = 'captured';
        $factory = fn($v) => static function() use ($v) { return $v; };
        $closure = $factory($value);

        $result = $this->process($closure);
        $this->assertSame('captured', $result());
    }

    public function testFnReturningFunctionWithReturnType()
    {
        $factory = fn() => static function(): string { return 'typed'; };
        $closure = $factory();

        $result = $this->process($closure);
        $this->assertSame('typed', $result());
    }

    public function testStaticFnReturningStaticFn()
    {
        $factory = static fn() => static fn() => 'static_arrow';
        $closure = $factory();

        $result = $this->process($closure);
        $this->assertSame('static_arrow', $result());
    }

    public function testFnReturningStaticFunctionReflectsCorrectly()
    {
        $factory = fn() => static function() { return 'inner'; };
        $closure = $factory();

        $reflector = new ReflectionClosure($closure);
        $info = $reflector->info();

        $this->assertFalse($info->isShort());
        $this->assertTrue($info->isStatic());
    }

    public function testFnReturningFnReflectsCorrectly()
    {
        $factory = fn() => fn() => 'arrow_inner';
        $closure = $factory();

        $reflector = new ReflectionClosure($closure);
        $info = $reflector->info();

        $this->assertTrue($info->isShort());
    }
}
