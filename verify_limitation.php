<?php
require 'vendor/autoload.php';
use Opis\Closure\SerializableClosure;

// Test standard pattern (should work with fix)
$closures = [fn() => 'a', fn() => 'b', fn() => 'c'];
$results = [];
foreach ($closures as $c) {
    $s = serialize(new SerializableClosure($c));
    $results[] = unserialize($s)->getClosure()();
}
echo "Standard pattern: " . implode(', ', $results) . " (expected: a, b, c)\n";

// Test out-of-order (known limitation)
$closures2 = [fn() => 'a', fn() => 'b', fn() => 'c'];
$r2 = unserialize(serialize(new SerializableClosure($closures2[2])))->getClosure()();
$r0 = unserialize(serialize(new SerializableClosure($closures2[0])))->getClosure()();
echo "Out-of-order: [2]=$r2, [0]=$r0 (expected limitation: c->a swapped to a,b or similar)\n";
