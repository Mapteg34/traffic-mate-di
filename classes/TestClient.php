<?php

namespace Mapt\TrafficMateDi;

class TestClient
{
    private TestInterface $test;

    public function __construct(Test $test, TestSingleTon $singleTon)
    {
        $this->test = $test;
        $singleTon->test('hello from TestClient::class constructor (this message printed from TestSingleTon::class dependency');
    }

    public function test(string $message): void
    {
        $this->test->test($message);
    }
}
