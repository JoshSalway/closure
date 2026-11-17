<?php
require 'vendor/autoload.php';
use Opis\Closure\Serializer;

$transforms = [fn($x) => $x * 2, fn($x) => $x + 10, fn($x) => $x - 5];
$serialized = array_map(fn($t) => Serializer::serialize($t), $transforms);
$restored = array_map(fn($s) => Serializer::unserialize($s), $serialized);
echo "Bug test on master (input 10):\n";
echo "  [0]: " . $restored[0](10) . " (expected 20)\n";
echo "  [1]: " . $restored[1](10) . " (should be 20 from x+10, but bug gives...)\n";
echo "  [2]: " . $restored[2](10) . " (should be 5 from x-5, but bug gives...)\n";
