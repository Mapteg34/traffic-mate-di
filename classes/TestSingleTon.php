<?php

namespace Mapt\TrafficMateDi;

class TestSingleTon implements TestInterface
{
    public function __construct()
    {
        echo sprintf('I am %s constructor. My class is a singleton, so container calls me only once.', static::class) . PHP_EOL;
    }

    public function test($message): void
    {
        echo $message . PHP_EOL;
    }
}
