<?php

declare(strict_types = 1);

namespace ASVoting\MemoryWarningCheck;

use Psr\Http\Message\ServerRequestInterface as Request;

interface MemoryWarningCheck
{
    /** returns the percentage of memory used */
    public function checkMemoryUsage(Request $request) : int;
}
