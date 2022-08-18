<?php

declare(strict_types=1);

namespace VPA\Logger;

use VPA\DI\Injectable;

#[Injectable()]
class ConsoleLogger extends BaseLogger
{
    /**
     * Table information.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function table(string|\Stringable $message, array $context = []): void
    {
        $this->log('table', $message . "\n{Table}", ['Table' => print_r($context, true)]);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        $text = $this->interpolate($message, $context);
        $color = '1;37';
        switch ($level) {
            case 'emergency':
                $color = '0;35';
                break;
            case 'info':
                $color = '0;32';
                break;
            case 'debug':
                $color = '1;34';
                break;
            case 'notice':
                $color = '1;33';
                break;
            case 'error':
            case 'critical':
                $color = '0;31';
                break;
            case 'alert':
                $color = '1;31';
                break;
        }
        printf("\033[%sm%s [%s] %s\033[0m\n", $color, date('y-m-d H:i:s'), $level, $text);
    }
}
