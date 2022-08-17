<?php
declare(strict_types=1);

namespace VPA\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use VPA\DI\Container;
use VPA\DI\Injectable;

#[Injectable()]
class JSConsoleLogger extends BaseLogger
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
        $text = $this->interpolate($message, $context);
        switch ($level) {
            case LogLevel::ERROR:
                printf("<script>console.error('%s [%s] %s');</script>", date('y-m-d H:i:s'), $level, $this->stringFormat($text));
                break;
            case LogLevel::DEBUG:
                printf("<script>console.debug('%s [%s] %s');</script>", date('y-m-d H:i:s'), $level, $this->stringFormat($text));
                break;
            case LogLevel::INFO:
                printf("<script>console.info('%s [%s] %s');</script>", date('y-m-d H:i:s'), $level, $this->stringFormat($text));
                break;
            case LogLevel::WARNING:
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
}