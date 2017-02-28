<?php

namespace Tests\Unit\Parser;

use App\Parser\Main;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParserTest extends TestCase
{
    /** @var Main $parser */
    protected $parser;

    protected $type = 'Autobus';
    protected $number = 17;
    protected $route = 'ДС Сухарево-5 - ДС Кунцевщина';
    protected $stop = 'Лобанка';

    public function setUp()
    {
        parent::setUp();

        $this->parser = new Main($this->type, $this->number);
    }

    public function test_getFinalStops()
    {
        $routes = $this->parser->getFinalStops();

        $this->assertEquals(2, count($routes));
    }

    public function test_getAllStops()
    {
        $stops = $this->parser->getAllStops($this->route);

        $this->assertGreaterThan(5, count($stops));
    }

    public function test_getTime()
    {
        $time = $this->parser->getTime($this->route, $this->stop);

        $this->assertNotEmpty($time);
    }
}
