<?php

namespace Tests\Unit\Conversation;

use App\Conversation\CheckWay;
use App\Entity\State;
use App\Message;
use App\Route;
use App\Stop;
use App\Time;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CheckWayTest extends TestCase
{
    protected $state;
    public function setUp()
    {
        parent::setUp();
}

    public function test_getRoutes()
    {
        $model = (new Route())->checkRoute([
            'type' => $this->type,
            'number' => $this->number,
        ]);

        if(null !== $model) {
            $this->assertTrue($model->delete());
        }

        $state = $this->mock(State::class);
        $state->shouldReceive('getType')->andReturn($this->type);
        $state->shouldReceive('getNumber')->andReturn($this->number);

        $useParser = CheckWay::getRoutes($state);
        $this->assertEquals(2, count($useParser));

        $useDB = CheckWay::getRoutes($state);
        $this->assertEquals(2, count($useDB));
    }

    public function test_getStops()
    {
        $model = (new Stop())->checkStop([
            'type' => $this->type,
            'number' => $this->number,
            'route' => $this->route
        ]);

        if(null !== $model) {
            $this->assertTrue($model->delete());
        }

        $state = $this->mock(State::class);
        $state->shouldReceive('getType')->andReturn($this->type);
        $state->shouldReceive('getNumber')->andReturn($this->number);
        $state->shouldReceive('getRoute')->andReturn($this->route);

        $useParser = CheckWay::getStops($state);
        $this->assertGreaterThan(8, count($useParser));

        $useDB = CheckWay::getStops($state);
        $this->assertGreaterThan(8, count($useDB));
    }

    public function test_getTime()
    {
        $model = (new Time())->checkTime([
            'type' => $this->type,
            'number' => $this->number,
            'route' => $this->route,
            'stop' => $this->stop
        ]);

        if(null !== $model) {
            $this->assertTrue($model->delete());
        }

        $state = $this->mock(State::class);
        $state->shouldReceive('getType')->andReturn($this->type);
        $state->shouldReceive('getNumber')->andReturn($this->number);
        $state->shouldReceive('getRoute')->andReturn($this->route);
        $state->shouldReceive('getStop')->andReturn($this->stop);

        $useParser = CheckWay::getTime($state);
        $this->assertGreaterThan(8, count($useParser));

        $useDB = CheckWay::getTime($state);
        $this->assertGreaterThan(8, count($useDB));
    }
}
