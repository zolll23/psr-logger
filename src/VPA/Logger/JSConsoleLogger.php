<?php
declare(strict_types=1);

namespace VPA\Logger;

use Psr\Log\AbstractLogger;
use VPA\DI\Container;
use VPA\DI\Injectable;

#[Injectable()]
class JSConsoleLogger extends AbstractLogger
{
    /**
     * @var mixed
     */
    private $serverRequest;

    function __construct()
    {
        $this->serverRequest = (new Container())->get('VPA\Framework\ServerRequest');
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
        $this->log('table', $message, $context);
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

        if ($this->serverRequest->isJson()) {
            return;
        }
        $text = $this->interpolate($message, $context);
        switch ($level) {
            case 'error':
                printf("<script>console.error('%s [%s] %s');</script>", date('y-m-d H:i:s'), $level, $this->stringFormat($text));
                break;
            case 'debug':
                printf("<script>console.debug('%s [%s] %s');</script>", date('y-m-d H:i:s'), $level, $this->stringFormat($text));
                break;
            case 'info':
                printf("<script>console.info('%s [%s] %s');</script>", date('y-m-d H:i:s'), $level, $this->stringFormat($text));
                break;
            case 'warning':
                printf("<script>console.warn('%s [%s] %s');</script>", date('y-m-d H:i:s'), $level, $this->stringFormat($text));
                break;
            case 'table':
                printf("<script>console.debug('%s [%s] %s');console.table(%s);</script>", date('y-m-d H:i:s'), $level, $this->stringFormat($message), json_encode($context));
                break;
            default:
                printf("<script>console.log('%s [%s] %s');</script>", date('y-m-d H:i:s'), $level, $this->stringFormat($text));
        }
    }

    private function stringFormat(string $input): string
    {
        $output = addcslashes($input, "`'\"");
        $output = str_replace("\n", "\\n", $output);
        //$output= str_replace("\\","\\\\", $output);
        return $output;
    }

    /**
     * Interpolates context values into the message placeholders.
     * Taken from PSR-3's example implementation.
     * @param string $message
     * @param array $context
     * @return string
     */
    protected function interpolate(string $message, array $context = []): string
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