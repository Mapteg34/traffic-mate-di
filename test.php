<?php

use Mapt\TrafficMateDi\Container;
use Mapt\TrafficMateDi\Test;
use Mapt\TrafficMateDi\TestClient;
use Mapt\TrafficMateDi\TestInterface;
use Mapt\TrafficMateDi\TestSingleTon;

require __DIR__ . '/vendor/autoload.php';

$container = Container::getInstance();
$container->make(Test::class)->test('hello from created Test::class (without any manual bindings)');

$container->singleton(TestSingleTon::class);
$container->make(TestSingleTon::class)->test('hello from created TestSingleTon::class (manually bound as singleton)');

$container->bind(TestInterface::class, TestSingleTon::class);
$container->make(TestInterface::class)->test('hello from created TestInterface::class (manually bound)');

$container->bind('TEST', TestSingleTon::class);
$container->make('TEST')->test('hello from created TEST (manually bound)');

$container->make(TestClient::class)->test('hello from created TestClient::class (without any manual bindings, have dependencies)');
