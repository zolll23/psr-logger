<?php
declare(strict_types=1);

namespace VPA\Logger;

use Psr\Log\AbstractLogger;
use VPA\DI\Injectable;

#[Injectable()]
class ConsoleLogger extends AbstractLogger
{

    function __construct()
    {
    }

    /**
     * Emegency situation
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->log('emergency', $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->log('alert', $message, $context);
    }

    /**
     * Critical conditions.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->log('critical', $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->log('notice', $message, $context);
    }

    /**
     * Interesting events.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->log('debug', $message, $context);
    }

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

    /**
     * Interpolates context values into the message placeholders.
     * Taken from PSR-3's example implementation.
     * @param string $message
     * @param array $context
     * @return string
     */
    protected function interpolate(string|\Stringable $message, array $context = []): string
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

}