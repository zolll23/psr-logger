<?php
declare(strict_types=1);

/**
 * DEMO: Using Logger with DI
 */
use VPA\DI\Container;

require_once(__DIR__ . '/../vendor/autoload.php');

// We want use for logs JS Developer Console if we run the script in browser and CLI Console - if run in CLI mode
$classLogger = 'VPA\Logger\JSConsoleLogger';
if(php_sapi_name()=='cli') {
    $classLogger = 'VPA\Logger\ConsoleLogger';
}

$di = new Container();
$di->registerContainers([
    'VPA\Logger'=>$classLogger,
]);

$logger = $di->get('VPA\Logger');
$logger->emergency("emergency message {date}",['date'=>date('d.m.Y H:i:s')]);
$logger->alert("alert message {date}",['date'=>date('d.m.Y H:i:s')]);
$logger->critical("critical message {date}",['date'=>date('d.m.Y H:i:s')]);
$logger->error("error message {date}",['date'=>date('d.m.Y H:i:s')]);
$logger->warning("warning message {date}",['date'=>date('d.m.Y H:i:s')]);
$logger->notice("notice message {date}",['date'=>date('d.m.Y H:i:s')]);
$logger->info("info message {date}",['date'=>date('d.m.Y H:i:s')]);
$logger->debug("debug message {date}",['date'=>date('d.m.Y H:i:s')]);
