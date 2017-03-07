<?php
declare(strict_types=1);

namespace PerFi\Domain;

interface Command
{
    /**
     * Gets the payload of the command, usually a root aggregate
     */
    public function payload();
}
