<?php
declare(strict_types=1);

namespace PerFi\Domain;

interface Event
{
    /**
     * Gets the payload of the event, usually a root aggregate
     */
    public function payload();
}
