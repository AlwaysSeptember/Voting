<?php

declare(strict_types = 1);

namespace ASVoting\ApiController;

use SlimAuryn\Response\JsonResponse;

class Vote
{
    public function index()
    {
        return new JsonResponse(['status' => 'ok']);
    }
}
