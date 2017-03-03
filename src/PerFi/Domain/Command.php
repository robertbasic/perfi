<?php
declare(strict_types=1);

namespace PerFi\Domain;

interface Command
{
    public function payload();
}
