<?php

declare(strict_types=1);

namespace VPA\Logger;

use Psr\Log\LogLevel;
use VPA\DI\Injectable;
use \Stringable;

#[Injectable]
class JSConsoleLogger extends BaseLogger
{
    /**
     * Table information.
     *
     * @param string | Stringable $message
     * @param array $context
     * @return void
     */
    public function table(string | Stringable $message, array $context = []): void
    {
        $this->log('table', $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string | Stringable $message
     * @param array $context
     * @return void
     */
    public function log(mixed $level, string | Stringable $message, array $context = []): void
    {
        $text = $this->interpolate($message, $context);
        $jsMethod = match ($level) {
            LogLevel::ERROR => 'error',
            LogLevel::DEBUG => 'debug',
            LogLevel::INFO => 'info',
            LogLevel::WARNING => 'warn',
            default => 'log',
        };

        echo "<script>".printf(
            "console.%s('%s [%s] %s');",
            $jsMethod,
            date('y-m-d H:i:s'),
            $level,
            $this->stringFormat($text),
        )."</script>";
    }

    private function stringFormat(string $input): string
    {
        $output = addcslashes($input, "`'\"");
        $output = str_replace("\n", "\\n", $output);
        //$output= str_replace("\\","\\\\", $output);
        return $output;
    }
}
