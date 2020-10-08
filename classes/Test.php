<?php

namespace Mapt\TrafficMateDi;

class Test implements TestInterface
{
    public function __construct()
    {
        echo sprintf('I am a %s constructor. My class is not a singleton, so container calls me each time.', static::class) . PHP_EOL;
    }

    public function test(string $message): void
    {
        echo $message . PHP_EOL;
    }
}
