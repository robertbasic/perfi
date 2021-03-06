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

    public function mockSetNamedParameter(string $parameterName, $value)
    {
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with($parameterName, $value)
            ->andReturnSelf();
    }

    private function mockSetValue(string $field)
    {
        $this->queryBuilder->shouldReceive('set')
            ->once()
            ->with($field, '?')
            ->andReturnSelf();
    }
}
