<?php
declare(strict_types=1);

namespace PerFiUnitTest\Traits;

trait QueryBuilderTrait
{
    public function mockSetPositionalParameter(int $position, $value)
    {
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with($position, $value)
            ->andReturnSelf();
    }
}
