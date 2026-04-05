<?php

namespace Opis\Closure\Test\PHP80;

use Opis\Closure\Test\SerializeTestCase;

class SameLineClosureTest extends SerializeTestCase
{
    public function testMultipleArrowClosuresOnSameLinePreserveIdentity()
    {
        $closures = [fn() => 'a', fn() => 'b', fn() => 'c'];

        $results = array_map(function ($c) {
            return $this->process($c);
        }, $closures);

        $this->assertEquals('a', $results[0]());
        $this->assertEquals('b', $results[1]());
        $this->assertEquals('c', $results[2]());
    }

    public function testTwoClosuresOnSameLine()
    {
        $a = fn() => 'x'; $b = fn() => 'y';

        $ra = $this->process($a);
        $rb = $this->process($b);

        $this->assertEquals('x', $ra());
        $this->assertEquals('y', $rb());
    }

    public function testClosuresOnDifferentLinesStillWork()
    {
        $a = fn() => 'a';
        $b = fn() => 'b';

        $ra = $this->process($a);
        $rb = $this->process($b);

        $this->assertEquals('a', $ra());
        $this->assertEquals('b', $rb());
    }

    public function testSingleClosureUnaffected()
    {
        $f = fn() => 42;
        $result = $this->process($f);
        $this->assertEquals(42, $result());
    }

    public function testSameLineClosuresWithParameters()
    {
        $double = fn($x) => $x * 2; $triple = fn($x) => $x * 3;

        $rd = $this->process($double);
        $rt = $this->process($triple);

        $this->assertEquals(10, $rd(5));
        $this->assertEquals(15, $rt(5));
    }

    public function testSameLineStaticClosures()
    {
        $a = static fn() => 'static_a'; $b = static fn() => 'static_b';

        $ra = $this->process($a);
        $rb = $this->process($b);

        $this->assertEquals('static_a', $ra());
        $this->assertEquals('static_b', $rb());
    }
}
