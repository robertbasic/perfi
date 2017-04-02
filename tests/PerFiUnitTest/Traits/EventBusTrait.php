<?php
declare(strict_types=1);

namespace PerFiUnitTest\Traits;

use Mockery as m;
use SimpleBus\Message\Bus\MessageBus;

trait EventBusTrait
{
    public function mockEventBus()
    {
        $eventBus = m::mock(MessageBus::class);
        $eventBus->shouldReceive('handle')
            ->byDefault();

        return $eventBus;
    }
}
