<?php

declare(strict_types=1);

namespace App\Services;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;
use Monolog\Level;
use Monolog\Logger;

class Log
{
	private static ?Logger $logger = null;

	public static function boot(): void
	{
		$logger = new Logger('app');
		$rotating_handler = new RotatingFileHandler(LOGS_PATH.'/app.log', 30, Level::Debug);

		$formatter = new JsonFormatter();
		$rotating_handler->setFormatter($formatter);

		$logger->pushHandler($rotating_handler);

		self::$logger = $logger;
	}

	public static function channel(string $channel): Logger
	{
		$data = config('logging.channels.'.$channel);
		if (!$data) {
			return self::$logger;
		}

		$logger = new Logger($channel);
		if ($data['driver'] == 'daily') {
			$handler = new RotatingFileHandler($data['path'], 30);
		} else {
			$handler = new StreamHandler($data['path']);
		}

		$formatter = new JsonFormatter();
		$handler->setFormatter($formatter);

		$logger->pushHandler($handler);

		return $logger;
	}

	public static function debug(string $message, array $context = []): void
	{
		self::$logger->debug($message, $context);
	}

	public static function info(string $message, array $context = []): void
	{
		self::$logger->info($message, $context);
	}

	public static function notice(string $message, array $context = []): void
	{
		self::$logger->notice($message, $context);
	}

	public static function warning(string $message, array $context = []): void
	{
		self::$logger->warning($message, $context);
	}

	public static function error(string $message, array $context = []): void
	{
		self::$logger->error($message, $context);
	}

	public static function critical(string $message, array $context = []): void
	{
		self::$logger->critical($message, $context);
	}

	public static function alert(string $message, array $context = []): void
	{
		self::$logger->alert($message, $context);
	}
}