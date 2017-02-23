<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;
use Mockery\MockInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $type = 'Autobus';
    protected $number = 17;
    protected $route = 'ДС Сухарево-5 - ДС Кунцевщина';
    protected $stop = 'Лобанка';

    protected function mock(string $class): MockInterface
    {
        $object = Mockery::mock($class);
        $this->app->bind($class, $object);
        return $object;
    }
}
