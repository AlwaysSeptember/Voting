<?php

declare(strict_types = 1);

namespace Portal;

trait Event
{
    private $event_id;

    private $was_ended = false;

    private function initEvent()
    {
        $this->event_id = bin2hex(random_bytes(16));
    }

    private function close()
    {
        $this->was_ended = true;
    }

    public function emit(array $params)
    {
        $string = json_encode_safe($params);

        $env = getConfig(['osf', 'env']);
        if ($env !== 'local') {
            return;
        }

        \error_log($string);
    }

    public function emitNotFinalised(string $name)
    {
        $this->emit(['shutdown_failed' => $name]);
    }
}
