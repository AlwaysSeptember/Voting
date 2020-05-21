<?php

declare(strict_types = 1);

namespace ASVoting\CliController;

use SlimAuryn\Response\HtmlResponse;
use DMore\ChromeDriver\ChromeDriver;

class Debug
{
    public function hello()
    {
        return new HtmlResponse("Hello");
    }

    public function debug()
    {
//        $result = $stripeEventRepo->waitToAssignTask();
//
//        var_dump($result);
//
//        $redisData = $redis->blpop(['alishdoiashdoaisdoiahsdihasodaoshd'], 5);
//        var_dump($redisData);
//        exit(0);
    }

}
