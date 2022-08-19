<?php

namespace VPA\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use \Stringable;

class BaseLogger extends AbstractLogger
{
    /**
     * Emergency situation
     *
     * @param string | Stringable $message
     * @param array $context
     * @return void
     */
    public function emergency(string | Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * @param string | Stringable $message
     * @param array $context
     * @return void
     */
    public function alert(string | Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * @param string | Stringable $message
     * @param array $context
     * @return void
     */
    public function critical(string | Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should
     * be logged and monitored.
     *
     * @param string | Stringable $message
     * @param array $context
     * @return void
     */
    public function error(string | Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * @param string | Stringable $message
     * @param array $context
     * @return void
     */
    public function warning(string | Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string | Stringable $message
     * @param array $context
     * @return void
     */
    public function notice(string | Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * @param string | Stringable $message
     * @param array $context
     * @return void
     */
    public function info(string | Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string | Stringable $message
     * @param array $context
     * @return void
     */
    public function debug(string | Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * Interpolates context values into the message placeholders.
     * Taken from PSR-3's example implementation.
     * @param string | Stringable $message
     * @param array $context
     * @return string
     */
    protected function interpolate(string | Stringable $message, array $context = []): string
    {
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $value) {
            $replace['{' . $key . '}'] = $this->castValue($value);
        }

        // interpolate replacement values into the message and return
        return strtr((string)$message, $replace);
    }

    public function log(mixed $level, string | Stringable $message, array $context = []): void
    {
    }

    protected function castValue(mixed $value): mixed
    {
        return $value;
    }
}
