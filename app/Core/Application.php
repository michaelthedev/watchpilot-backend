<?php

declare(strict_types=1);

namespace App\Core;

use App\Exceptions\Handler;
use App\Services\Cache;
use App\Services\Log;
use Dotenv\Dotenv;
use Pecee\SimpleRouter\SimpleRouter;
use Illuminate\Database\Capsule\Manager as Capsule;

final class Application
{
	private array $routes;

	public function __construct(
		public readonly string $basePath
	){}

	public function loadRoutes(array $routes): void
	{
		$this->routes = $routes;
	}

	private function registerRoutes(): self
	{
		foreach ($this->routes as $routeFile)
		{
			require $routeFile;
		}

		return $this;
	}

	private function startRouter(): void
	{
		SimpleRouter::enableMultiRouteRendering(false);

		try {
			SimpleRouter::start();
		}
		catch (\Exception $e) {
			$this->exceptionHandler()
				->report($e);
		}
	}

	private function bootDatabase(): void
	{
		// Setup database
		$capsule = new Capsule();
		$capsule->addConnection([
			'host' => config('database.host'),
			'driver' => config('database.driver'),
			'charset' => config('database.charset'),
			'database' => config('database.name'),
			'username' => config('database.username'),
			'password' => config('database.password'),
			'collation' => config('database.collation'),
		]);
		$capsule->setAsGlobal();
		$capsule->bootEloquent();
	}

	private function exceptionHandler(): Handler
	{
		return new Handler();
	}

	public function boot(bool $withRouter = false): void
	{
		// .env parser
		$dotenv = Dotenv::createImmutable($this->basePath);
		$dotenv->load();

		// Boot database
		$this->bootDatabase();

		// Boot cache system and log
		Cache::boot();
		Log::boot();

		// set default timezone
		date_default_timezone_set(config('app.timezone'));

		if ($withRouter) {
			// Start router
			$this->registerRoutes()
				->startRouter();
		}
	}
}