<?php
require 'vendor/autoload.php';
use Opis\Closure\Serializer;

// Test standard pattern (should work with fix)
$closures = [fn() => 'a', fn() => 'b', fn() => 'c'];
$results = [];
foreach ($closures as $c) {
    $s = Serializer::serialize($c);
    $results[] = Serializer::unserialize($s)();
}
echo "Standard pattern (fix): " . implode(', ', $results) . " (expected: a, b, c)\n";

// Test transforms from PR description
$transforms = [fn($x) => $x * 2, fn($x) => $x + 10, fn($x) => $x - 5];
$serialized = array_map(fn($t) => Serializer::serialize($t), $transforms);
$restored = array_map(fn($s) => Serializer::unserialize($s), $serialized);
echo "Transform test (fix, input 10):\n";
echo "  [0]: " . $restored[0](10) . " (expected 20)\n";
echo "  [1]: " . $restored[1](10) . " (expected 20)\n";
echo "  [2]: " . $restored[2](10) . " (expected 5)\n";

// Out-of-order test
$closures2 = [fn() => 'a', fn() => 'b', fn() => 'c'];
$r2 = Serializer::unserialize(Serializer::serialize($closures2[2]))();
$r0 = Serializer::unserialize(Serializer::serialize($closures2[0]))();
$r1 = Serializer::unserialize(Serializer::serialize($closures2[1]))();
echo "Out-of-order: [2]=$r2, [0]=$r0, [1]=$r1 (known limitation)\n";
