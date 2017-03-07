<?php
declare(strict_types=1);

namespace PerFi\Domain;

interface Event
{
    public function payload();
}
