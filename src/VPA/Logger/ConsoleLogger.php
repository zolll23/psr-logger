<?php

declare(strict_types=1);

namespace VPA\Logger;

use VPA\DI\Injectable;
use \Stringable;

#[Injectable]
class ConsoleLogger extends BaseLogger
{
    private const PREPEND_COL = '| ';
    private const SEPARATOR_COL = ' | ';
    private const APPEND_COL = ' |';

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string | Stringable $message
     * @param array $context
     * @return void
     */
    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        $text = $this->interpolate($message, $context);

        $color = match ($level) {
            'emergency' => '0;35',
            'info' => '0;32',
            'debug' => '1;34',
            'notice' => '1;33',
            'error', 'critical' => '0;31',
            'alert' => '1;31',
            default => '1;37',
        };
        printf("\033[%sm%s [%s] %s\033[0m\n", $color, date('y-m-d H:i:s'), $level, $text);
    }

    protected function castValue(mixed $value): string
    {
        $type = gettype($value);
        return match ($type) {
            'array' => $this->formatArray($value),
            default => $value,
        };
    }


    private function format1DArray(array $value): string
    {
        $lengthFirstColumn = array_reduce(array_keys($value), function (mixed $carry,  mixed $string) {
            return max($carry, strlen((string) $string));
        }
        );
        $lengthSecondColumn = array_reduce($value, function (mixed $carry, string $string) {
            return max($carry, strlen($string));
        }
        );

        $lengthTotal = strlen(self::APPEND_COL) +
            $lengthFirstColumn +
            strlen(self::SEPARATOR_COL) +
            $lengthSecondColumn +
            strlen(self::APPEND_COL);
        $output = $footer = str_repeat('-', $lengthTotal);
        foreach ($value as $key => $item) {
            $output .= sprintf(
                "\n%s%s%s%s%s",
                self::PREPEND_COL,
                str_pad($key, $lengthFirstColumn),
                self::SEPARATOR_COL,
                str_pad($item, $lengthSecondColumn),
                self::APPEND_COL
            );
        }
        return "\n" . $output . "\n" . $footer . "\n";
    }

    private function getDimensional(array $array): int
    {
        if (is_array(reset($array))) {
            $return = $this->getDimensional(reset($array)) + 1;
        } else {
            $return = 1;
        }

        return $return;
    }

    private function formatArray(array $value): string
    {
        $dim = $this->getDimensional($value);
        return match ($dim) {
            1 => $this->format1DArray($value),
            default => "Array with dimension $dim not supported",
        };
    }
}
